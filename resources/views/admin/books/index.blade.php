@extends('admin.layouts.app')

@section('title', 'Books')
@section('crumb', 'Catalogue')
@section('heading')All <em>books</em>@endsection
@section('actions')
    <a href="{{ route('admin.books.create') }}" class="btn btn--sm"><x-admin.icon name="plus" size="14" /> Add book</a>
@endsection

@section('content')
    <form method="get" class="card card-pad toolbar">
        <div class="filters">
            <input type="search" name="search" value="{{ request('search') }}" placeholder="Search title, subject or slug" class="input">
            <select name="board" class="select" data-autosubmit>
                <option value="">All boards</option>
                @foreach ($options['boards'] as $board)
                    <option @selected(request('board') === $board)>{{ $board }}</option>
                @endforeach
            </select>
            <select name="section" class="select" data-autosubmit>
                <option value="">All sections</option>
                @foreach ($options['sections'] as $key => $label)
                    <option value="{{ $key }}" @selected(request('section') === $key)>{{ $label }}</option>
                @endforeach
            </select>
            <select name="class" class="select" data-autosubmit>
                <option value="">All grades</option>
                @foreach ($options['classes'] as $class)
                    <option value="{{ $class }}" @selected(request('class') === $class)>{{ ($options['classLabel'])($class) }}</option>
                @endforeach
            </select>
            <select name="status" class="select" data-autosubmit>
                <option value="">Any status</option>
                <option value="active" @selected(request('status') === 'active')>Visible</option>
                <option value="hidden" @selected(request('status') === 'hidden')>Hidden</option>
                <option value="low" @selected(request('status') === 'low')>Low stock (10 or less)</option>
                <option value="no_cover" @selected(request('status') === 'no_cover')>Cover coming soon</option>
            </select>
            <button type="submit" class="btn btn--ghost btn--sm"><x-admin.icon name="search" size="14" /> Filter</button>
            @if (request()->hasAny(['search', 'board', 'section', 'class', 'status']))
                <a href="{{ route('admin.books.index') }}" class="link">Clear</a>
            @endif
        </div>
    </form>

    <section class="card">
        @if ($books->isEmpty())
            <div class="empty"><h3>No books found</h3><p>Try another filter, or add a new book.</p></div>
        @else
            <div class="table-wrap">
                <table class="table">
                    <thead>
                        <tr><th>Book</th><th>Grade</th><th>Section and boards</th><th class="num">Price</th><th class="num">Stock</th><th>Status</th><th></th></tr>
                    </thead>
                    <tbody>
                        @foreach ($books as $book)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.books.edit', $book) }}" class="cell-book">
                                        <img src="{{ $book->image }}" alt="" loading="lazy">
                                        <span>
                                            <strong>{{ $book->displayName() }}</strong>
                                            <span class="muted">{{ $book->series }} · {{ $book->subject_filter }}</span>
                                        </span>
                                    </a>
                                </td>
                                <td>{{ ($options['classLabel'])($book->class) }}</td>
                                <td style="max-width: 260px;">
                                    <div class="strong" style="font-size: .84rem;">{{ $options['sections'][$book->section] ?? $book->section }}</div>
                                    @if (count($book->boards) >= 6)
                                        <span class="chip">{{ count($book->boards) }} boards</span>
                                    @else
                                        @foreach ($book->boards as $board)<span class="chip">{{ $board }}</span>@endforeach
                                    @endif
                                </td>
                                <td class="num"><strong>₹{{ number_format($book->price) }}</strong>@if ($book->mrp > $book->price)<div class="muted"><s>₹{{ number_format($book->mrp) }}</s></div>@endif</td>
                                <td class="num"><span class="badge {{ $book->stock <= 0 ? 'badge--brand' : ($book->stock <= 10 ? 'badge--warn' : 'badge--ok') }}">{{ $book->stock }}</span></td>
                                <td>
                                    @if ($book->is_active)<span class="badge badge--ok">Visible</span>@else<span class="badge badge--muted">Hidden</span>@endif
                                    @if ($book->cover_pending)<div class="muted" style="margin-top: 4px;">Cover coming soon</div>@endif
                                </td>
                                <td>
                                    <div class="row-actions">
                                        <a href="{{ route('books.show', $book->slug) }}" target="_blank" class="icon-btn" title="View on website"><x-admin.icon name="external" size="15" /></a>
                                        <form method="post" action="{{ route('admin.books.toggle', $book) }}">
                                            @csrf @method('patch')
                                            <button type="submit" class="icon-btn" title="{{ $book->is_active ? 'Hide from website' : 'Show on website' }}"><x-admin.icon :name="$book->is_active ? 'eye-off' : 'eye'" size="15" /></button>
                                        </form>
                                        <a href="{{ route('admin.books.edit', $book) }}" class="icon-btn" title="Edit"><x-admin.icon name="edit" size="15" /></a>
                                        <form method="post" action="{{ route('admin.books.destroy', $book) }}" data-confirm="Delete “{{ $book->displayName() }}”? This cannot be undone.">
                                            @csrf @method('delete')
                                            <button type="submit" class="icon-btn icon-btn--danger" title="Delete"><x-admin.icon name="trash" size="15" /></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
        @include('admin.partials.pager', ['paginator' => $books])
    </section>
@endsection
