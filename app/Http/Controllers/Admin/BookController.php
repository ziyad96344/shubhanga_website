<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Support\Catalog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::query();

        if ($request->filled('search')) {
            $term = '%'.$request->search.'%';
            $query->where(fn ($q) => $q->where('title', 'like', $term)->orWhere('subject', 'like', $term)
                ->orWhere('subject_filter', 'like', $term)->orWhere('slug', 'like', $term)->orWhere('series', 'like', $term));
        }
        if ($request->filled('board')) {
            $query->where('boards', 'like', '%"'.$request->board.'"%');
        }
        foreach (['section', 'class'] as $field) {
            if ($request->filled($field)) {
                $query->where($field, $request->input($field));
            }
        }
        match ($request->input('status')) {
            'active' => $query->where('is_active', true),
            'hidden' => $query->where('is_active', false),
            'low' => $query->where('stock', '<=', 10),
            'no_cover' => $query->where('cover_pending', true),
            default => null,
        };

        return view('admin.books.index', [
            'books' => $query->orderBy('sort_order')->orderBy('id')->paginate(25)->withQueryString(),
            'options' => $this->options(),
        ]);
    }

    public function create()
    {
        return view('admin.books.form', ['book' => new Book(['series' => 'Shubhanga', 'type' => 'Textbooks', 'stock' => 100, 'is_active' => true, 'boards' => []]), 'options' => $this->options()]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['sort_order'] = (int) Book::max('sort_order') + 1;
        $book = Book::create($data);
        $this->saveCover($request, $book);

        return redirect()->route('admin.books.index')->with('success', 'Book “'.$book->displayName().'” added.');
    }

    public function edit(Book $book)
    {
        return view('admin.books.form', ['book' => $book, 'options' => $this->options()]);
    }

    public function update(Request $request, Book $book)
    {
        $book->update($this->validated($request, $book));
        $this->saveCover($request, $book);

        return redirect()->route('admin.books.index')->with('success', 'Book “'.$book->displayName().'” updated.');
    }

    public function destroy(Book $book)
    {
        $book->delete();

        return back()->with('success', 'Book deleted.');
    }

    /** Quick show / hide from the list. */
    public function toggle(Book $book)
    {
        $book->update(['is_active' => ! $book->is_active]);

        return back()->with('success', $book->displayName().' is now '.($book->is_active ? 'visible' : 'hidden').' on the site.');
    }

    private function validated(Request $request, ?Book $book = null): array
    {
        $o = $this->options();
        $data = $request->validate([
            'title' => ['required', 'string', 'max:120'],
            'subject' => ['nullable', 'string', 'max:120'],
            'subject_filter' => ['required', 'string', 'max:80'],
            'series' => ['required', 'string', 'max:80'],
            'class' => ['required', Rule::in($o['classes'])],
            'section' => ['required', Rule::in(array_keys($o['sections']))],
            'boards' => ['required', 'array', 'min:1'],
            'boards.*' => [Rule::in($o['boards'])],
            'type' => ['required', Rule::in($o['types'])],
            'slug' => ['nullable', 'alpha_dash', 'max:150', Rule::unique('books', 'slug')->ignore($book?->id)],
            'description' => ['nullable', 'string', 'max:2000'],
            'mrp' => ['required', 'integer', 'min:0', 'max:100000'],
            'price' => ['required', 'integer', 'min:0', 'max:100000', 'lte:mrp'],
            'stock' => ['required', 'integer', 'min:0', 'max:1000000'],
            'badge' => ['nullable', 'string', 'max:30'],
            'cover' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ], ['price.lte' => 'Price cannot be more than the MRP.', 'boards.required' => 'Pick at least one board.']);

        $data['slug'] = $data['slug'] ?: Str::slug(trim($data['title'].' '.$data['subject'].' class '.$data['class']));
        $data['is_new'] = $request->boolean('is_new');
        $data['is_active'] = $request->boolean('is_active');
        unset($data['cover']);

        return $data;
    }

    /** Saves an uploaded cover as public/images/books/{slug}.webp (600px wide). */
    private function saveCover(Request $request, Book $book): void
    {
        if (! $request->hasFile('cover')) {
            return;
        }

        $file = $request->file('cover');
        $image = @imagecreatefromstring(file_get_contents($file->getRealPath()));
        if (! $image) {
            return;
        }
        if (imagesx($image) > 600) {
            $image = imagescale($image, 600);
        }
        $path = '/images/books/'.$book->slug.'.webp';
        imagewebp($image, public_path($path), 82);

        $book->update(['image' => $path.'?v='.time(), 'cover_pending' => false]);
    }

    private function options(): array
    {
        return [
            'boards' => config('catalog.boards'),
            'sections' => collect(config('catalog.sections'))->map(fn ($s) => $s['label'])->all(),
            'classes' => config('catalog.classes'),
            'types' => config('catalog.book_types'),
            'subjects' => Book::query()->distinct()->orderBy('subject_filter')->pluck('subject_filter')->all(),
            'series' => Book::query()->distinct()->orderBy('series')->pluck('series')->all(),
            'classLabel' => fn ($c) => Catalog::classLabel($c),
        ];
    }
}
