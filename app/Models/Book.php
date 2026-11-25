<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'slug', 'title', 'subject', 'subject_filter', 'series', 'class', 'section', 'boards', 'listed_as',
        'type', 'image', 'description', 'mrp', 'price', 'stock', 'sold_count', 'rating', 'reviews',
        'badge', 'is_new', 'cover_pending', 'is_active', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'boards' => 'array',
            'is_new' => 'boolean',
            'cover_pending' => 'boolean',
            'is_active' => 'boolean',
            'rating' => 'float',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /** "Mathematics Semester 1", "Smart Scores Science" */
    public function displayName(): string
    {
        return trim($this->title.' '.$this->subject);
    }

    /**
     * The array shape the storefront has always used (it was read from
     * config/catalog.php before the admin panel existed).
     */
    public function toCatalogArray(): array
    {
        return [
            'slug' => $this->slug,
            'image' => $this->image ?: '/images/books/placeholder.webp',
            'series' => $this->series,
            'title' => $this->title,
            'subject' => (string) $this->subject,
            'subject_filter' => $this->subject_filter,
            'class' => $this->class,
            'section' => $this->section,
            'boards' => $this->boards ?: [],
            'listed_as' => (string) $this->listed_as,
            'type' => $this->type,
            'custom_description' => $this->description,
            'mrp' => (int) $this->mrp,
            'price' => (int) $this->price,
            'stock' => (int) $this->stock,
            'rating' => (float) $this->rating,
            'reviews' => (int) $this->reviews,
            'badge' => $this->badge,
            'new' => (bool) $this->is_new,
            'cover_pending' => (bool) $this->cover_pending,
        ];
    }
}
