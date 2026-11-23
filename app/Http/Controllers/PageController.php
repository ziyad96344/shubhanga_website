<?php

namespace App\Http\Controllers;

/**
 * Content pages fed by config/content.php (later: the admin panel).
 */
class PageController extends Controller
{
    public function about()
    {
        return view('pages.about', ['about' => config('content.about')]);
    }

    public function faq()
    {
        return view('pages.faq', ['faqs' => config('content.faq')]);
    }

    public function policy(string $slug)
    {
        $policy = config("content.policies.$slug") ?? abort(404);

        return view('pages.policy', [
            'policy' => $policy,
            'slug' => $slug,
            'policies' => config('content.policies'),
        ]);
    }

    public function resources()
    {
        return view('pages.resources', [
            'groups' => collect(config('content.resources'))->groupBy('group'),
        ]);
    }

    public function bookshops()
    {
        $shops = collect(config('content.bookshops'))->sortBy('city')->values();

        return view('pages.bookshops', [
            'shops' => $shops,
            'states' => $shops->pluck('state')->unique()->sort()->values(),
        ]);
    }

    /** XML sitemap of every public page, book and article. */
    public function sitemap()
    {
        $urls = collect([
            route('home'), route('books.index'), route('about'), route('faq'), route('resources'),
            route('bookshops'), route('blog.index'),
        ])
            ->merge(collect(array_keys(config('forms')))->map(fn ($form) => route('forms.show', $form)))
            ->merge(collect(array_keys(config('content.policies')))->map(fn ($slug) => route('policy', $slug)))
            ->merge(\App\Support\Catalog::books()->map(fn ($book) => route('books.show', $book['slug'])))
            ->merge(collect(config('content.posts'))->map(fn ($post) => route('blog.show', $post['slug'])));

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n".'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n"
            .$urls->map(fn ($url) => '  <url><loc>'.e($url).'</loc></url>')->implode("\n")
            ."\n</urlset>\n";

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }

    public function blog()
    {
        return view('blog.index', ['posts' => config('content.posts')]);
    }

    public function post(string $slug)
    {
        $posts = collect(config('content.posts'));
        $post = $posts->firstWhere('slug', $slug) ?? abort(404);

        return view('blog.show', [
            'post' => $post,
            'more' => $posts->where('slug', '!=', $slug)->values(),
        ]);
    }
}
