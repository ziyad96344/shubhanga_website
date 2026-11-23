<?php

namespace App\Support;

use Illuminate\Support\Collection;

/**
 * Read-only access to the catalogue in config/catalog.php.
 *
 * Adds the display fields the pages need. Swap this class for Eloquent
 * queries when the admin panel is wired in.
 */
class Catalog
{
    /** Boards that also use the "All boards" pre-primary and primary books. */
    public const ALL_BOARDS = 'All boards';

    public static function books(): Collection
    {
        return collect(config('catalog.books'))->map(fn (array $book) => static::enrich($book));
    }

    public static function find(string $slug): ?array
    {
        return static::books()->firstWhere('slug', $slug);
    }

    /**
     * True when every word of the query appears somewhere in the book's
     * title, series, subject, class, board or type ("grade 10 science").
     */
    public static function matches(array $book, string $query): bool
    {
        $haystack = mb_strtolower(implode(' ', [
            $book['name'], $book['series'], $book['subject'], $book['class_label'], 'grade '.$book['class'], $book['board'], $book['type'],
        ]));

        foreach (preg_split('/\s+/', mb_strtolower($query)) as $word) {
            if ($word !== '' && ! str_contains($haystack, $word)) {
                return false;
            }
        }

        return true;
    }

    /** Whether a book is used on a given board (shared books count for every board). */
    public static function onBoard(array $book, string $board): bool
    {
        return $book['board'] === $board || $book['board'] === self::ALL_BOARDS;
    }

    public static function classLabel(string $class): string
    {
        return is_numeric($class) ? 'Grade '.$class : $class;
    }

    /**
     * Filter options shown in the catalogue sidebar.
     */
    public static function filters(): array
    {
        $books = static::books();

        return [
            'board' => config('catalog.boards'),
            'class' => collect(config('catalog.classes'))
                ->filter(fn ($class) => $books->contains('class', $class))
                ->values()
                ->all(),
            'type' => collect(config('catalog.book_types'))
                ->filter(fn ($type) => $books->contains('type', $type))
                ->values()
                ->all(),
            'subject' => $books->pluck('subject')->unique()->sort()->values()->all(),
        ];
    }

    private static function enrich(array $book): array
    {
        // `subject` in config is the line shown under the title; keep it for
        // display and use `subject_filter` as the book's subject everywhere else.
        $book['name'] = trim($book['title'].' '.$book['subject']);
        $book['subject_line'] = $book['subject'];
        $book['subject'] = $book['subject_filter'] ?? $book['subject'];

        $book['class_label'] = static::classLabel($book['class']);
        $book['board_label'] = $book['board'] === self::ALL_BOARDS ? 'All syllabuses' : $book['board'];
        $book['off'] = $book['mrp'] > $book['price'] ? (int) round((1 - $book['price'] / $book['mrp']) * 100) : 0;
        $book['language'] = in_array($book['subject'], ['Hindi', 'Telugu'], true) ? $book['subject'] : 'English';
        $book['description'] = static::describe($book);
        $book['contents'] = static::features($book);

        return $book;
    }

    private static function describe(array $book): string
    {
        $syllabus = $book['board'] === self::ALL_BOARDS ? 'schools on every syllabus' : $book['board'].' schools';

        return match ($book['type']) {
            'Pre-primary' => "{$book['series']} is part of the Shubhanga pre-primary programme for {$book['class_label']}: playful, activity-based pages for {$book['subject']} that build school readiness, aligned to NEP 2020 and NCF 2023.",
            'Practice books' => "Smart Scores {$book['subject']} for {$book['class_label']} CBSE: concept-driven practice with mind maps, worksheets and board-based assessments to help students prepare with confidence.",
            'Skill books' => "{$book['name']} for {$book['class_label']}, a Shubhanga skill book used by {$syllabus}. Step-by-step activities and regular practice to build the skill through the year.",
            default => "{$book['name']} for {$book['class_label']}, written by experienced educators for {$syllabus}. Syllabus-mapped, error-free content with activities, worksheets and assessments, aligned to NEP 2020.",
        };
    }

    /** "What's inside" points, drawn from what Shubhanga says about its books. */
    private static function features(array $book): array
    {
        return match ($book['type']) {
            'Pre-primary' => ['Activity-based pages for little learners', 'Aligned to NEP 2020 and NCF 2023', 'Teacher and parent guidance', 'Part of a complete pre-primary programme'],
            'Practice books' => ['Concept-driven explanations', 'Mind maps for quick revision', 'Worksheets for regular practice', 'Board-based assessments'],
            default => ['Syllabus-mapped chapters', 'Activities, worksheets and mind maps', 'Assessments for every unit', 'Multi-reviewed, error-free content'],
        };
    }
}
