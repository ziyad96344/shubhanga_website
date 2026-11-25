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
        $page = fn (string $url, string $priority, array $images = []) => compact('url', 'priority', 'images');

        $urls = collect([
            $page(route('home'), '1.0'),
            $page(route('books.index'), '0.9'),
        ])
            // one landing page per board and per section, like the board pages on the old site
            ->merge(collect(config('catalog.boards'))->map(fn ($board) => $page(route('books.index', ['board' => $board]), '0.8')))
            ->merge(collect(array_keys(config('catalog.sections')))->map(fn ($section) => $page(route('books.index', ['section' => $section]), '0.8')))
            ->merge(collect([route('about'), route('faq'), route('resources'), route('bookshops'), route('blog.index')])->map(fn ($url) => $page($url, '0.6')))
            ->merge(collect(array_keys(config('forms')))->map(fn ($form) => $page(route('forms.show', $form), '0.5')))
            ->merge(collect(array_keys(config('content.policies')))->map(fn ($slug) => $page(route('policy', $slug), '0.3')))
            ->merge(\App\Support\Catalog::books()->map(fn ($book) => $page(route('books.show', $book['slug']), '0.7', [[
                'loc' => url($book['image']),
                'title' => $book['name'].', '.$book['class_label'],
            ]])))
            ->merge(collect(config('content.posts'))->map(fn ($post) => $page(route('blog.show', $post['slug']), '0.5', [[
                'loc' => url($post['image']),
                'title' => $post['title'],
            ]])));

        $lastmod = date('Y-m-d', filemtime(config_path('catalog.php')));
        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n"
            .'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">'."\n"
            .$urls->map(function ($u) use ($lastmod) {
                $images = collect($u['images'])->map(fn ($i) => '<image:image><image:loc>'.e($i['loc']).'</image:loc><image:title>'.e($i['title']).'</image:title></image:image>')->implode('');

                return '  <url><loc>'.e($u['url']).'</loc><lastmod>'.$lastmod.'</lastmod><priority>'.$u['priority'].'</priority>'.$images.'</url>';
            })->implode("\n")
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
