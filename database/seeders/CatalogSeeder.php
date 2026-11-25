<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Setting;
use Illuminate\Database\Seeder;

/**
 * Moves the book list from config/catalog.php into the `books` table and
 * fills the settings table with the current config values. Safe to run
 * again: existing rows are updated, nothing is duplicated.
 */
class CatalogSeeder extends Seeder
{
    public function run(): void
    {
        foreach (config('catalog.books') as $i => $book) {
            Book::updateOrCreate(['slug' => $book['slug']], [
                'title' => $book['title'],
                'subject' => $book['subject'] ?: null,
                'subject_filter' => $book['subject_filter'],
                'series' => $book['series'],
                'class' => $book['class'],
                'section' => $book['section'],
                'boards' => $book['boards'],
                'listed_as' => $book['listed_as'] ?? null,
                'type' => $book['type'],
                'image' => $book['image'],
                'mrp' => $book['mrp'],
                'price' => $book['price'],
                'rating' => $book['rating'],
                'reviews' => $book['reviews'],
                'badge' => $book['badge'],
                'is_new' => $book['new'],
                'cover_pending' => $book['cover_pending'] ?? false,
                'sort_order' => $i + 1,
            ]);
        }

        $order = 0;
        foreach (Setting::DEFINITIONS as $group => $fields) {
            foreach ($fields as $key => [$label, $type, $configKey]) {
                Setting::firstOrCreate(['key' => $key], [
                    'group' => $group,
                    'label' => $label,
                    'type' => $type,
                    'value' => (string) config($configKey),
                    'sort_order' => ++$order,
                ]);
            }
        }
        Setting::flush();
    }
}
