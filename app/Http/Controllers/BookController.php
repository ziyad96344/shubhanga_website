<?php

namespace App\Http\Controllers;

use App\Support\Catalog;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class BookController extends Controller
{
    private const PER_PAGE = 12;

    private const SORTS = [
        'catalogue' => 'Catalogue order',
        'popular' => 'Most popular',
        'new' => 'Newest first',
        'price_asc' => 'Price: low to high',
        'price_desc' => 'Price: high to low',
    ];

    public function index(Request $request)
    {
        $filters = Catalog::filters();

        // Only accept values that exist, so the query string can't inject anything odd.
        $active = [];
        foreach (array_keys($filters) as $key) {
            $value = $request->query($key);
            if (is_string($value) && in_array($value, $filters[$key], true)) {
                $active[$key] = $value;
            }
        }

        $sort = array_key_exists($request->query('sort'), self::SORTS) ? $request->query('sort') : 'catalogue';
        $q = trim(mb_substr((string) $request->query('q', ''), 0, 80));

        // Old links used ?type=; map the two that have a section equivalent.
        if (! isset($active['section']) && in_array($request->query('type'), ['Pre-primary', 'Practice books'], true)) {
            $active['section'] = $request->query('type') === 'Pre-primary' ? 'Pre-primary' : 'CBSE High School';
        }

        $books = Catalog::books()->filter(function (array $book) use ($active, $q) {
            if ($q !== '' && ! Catalog::matches($book, $q)) {
                return false;
            }

            return Catalog::passes($book, $active);
        });

        $books = match ($sort) {
            'new' => $books->sortByDesc('new'),
            'price_asc' => $books->sortBy('price'),
            'price_desc' => $books->sortByDesc('price'),
            'popular' => $books->sortByDesc('reviews'),
            default => $books, // the source site's board > section > grade order
        };

        $page = max(1, (int) $request->query('page', 1));
        $paginator = new LengthAwarePaginator(
            $books->forPage($page, self::PER_PAGE)->values(),
            $books->count(),
            self::PER_PAGE,
            $page,
            ['path' => $request->url(), 'query' => $request->query()],
        );

        return view('books.index', [
            'books' => $paginator,
            'filters' => Catalog::facets($active),
            'active' => $active,
            'sort' => $sort,
            'sorts' => self::SORTS,
            'q' => $q,
        ]);
    }

    /** Quick matches for the header search box. */
    public function suggest(Request $request)
    {
        $q = trim(mb_substr((string) $request->query('q', ''), 0, 80));
        if (mb_strlen($q) < 2) {
            return response()->json([]);
        }

        return response()->json(
            Catalog::books()
                ->filter(fn ($book) => Catalog::matches($book, $q))
                ->sortByDesc('reviews')
                ->take(5)
                ->map(fn ($book) => [
                    'name' => $book['name'],
                    'meta' => $book['class_label'].', '.$book['board'],
                    'price' => $book['price'],
                    'image' => $book['image'],
                    'url' => route('books.show', $book['slug']),
                ])
                ->values()
        );
    }

    public function show(string $slug)
    {
        $book = Catalog::find($slug) ?? abort(404);

        $related = Catalog::books()
            ->where('slug', '!=', $book['slug'])
            ->filter(fn ($other) => array_intersect($other['boards'], $book['boards']))
            ->sortByDesc(fn ($other) => ($other['class'] === $book['class'] ? 4 : 0) + ($other['section'] === $book['section'] ? 2 : 0) + ($other['series'] === $book['series'] ? 1 : 0) - ($other['cover_pending'] ? 3 : 0))
            ->take(4)
            ->values();

        return view('books.show', compact('book', 'related'));
    }
}
