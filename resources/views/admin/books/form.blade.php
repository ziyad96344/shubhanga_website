@extends('admin.layouts.app')

@php($editing = $book->exists)
@section('title', $editing ? 'Edit book' : 'Add book')
@section('crumb', 'Books')
@section('heading'){!! $editing ? 'Edit <em>'.e($book->displayName()).'</em>' : 'Add a <em>book</em>' !!}@endsection
@section('actions')
    <a href="{{ route('admin.books.index') }}" class="btn btn--ghost btn--sm"><x-admin.icon name="arrow-left" size="14" /> All books</a>
@endsection

@section('content')
    <form method="post" action="{{ $editing ? route('admin.books.update', $book) : route('admin.books.store') }}" enctype="multipart/form-data" class="grid grid-form">
        @csrf
        @if ($editing) @method('put') @endif

        <div class="grid">
            <section class="card card-pad form">
                <div class="card-head" style="margin: -26px -26px 4px; padding-inline: 26px;"><h2>Book <em>details</em></h2></div>
                <div class="form-row">
                    <div class="field">
                        <label for="title">Title</label>
                        <input id="title" name="title" value="{{ old('title', $book->title) }}" class="input" required placeholder="e.g. Mathematics or Smart Scores">
                        @error('title')<span class="field-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="field">
                        <label for="subject">Second line</label>
                        <input id="subject" name="subject" value="{{ old('subject', $book->subject) }}" class="input" placeholder="e.g. Semester 1 or Science, Part 1">
                        <small>Shown after the title. Leave empty if not needed.</small>
                    </div>
                </div>
                <div class="form-row">
                    <div class="field">
                        <label for="subject_filter">Subject</label>
                        <input id="subject_filter" name="subject_filter" value="{{ old('subject_filter', $book->subject_filter) }}" class="input" list="subjects" required>
                        <datalist id="subjects">@foreach ($options['subjects'] as $s)<option value="{{ $s }}">@endforeach</datalist>
                        <small>Used by the Subject filter on the website.</small>
                        @error('subject_filter')<span class="field-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="field">
                        <label for="series">Series</label>
                        <input id="series" name="series" value="{{ old('series', $book->series) }}" class="input" list="series-list" required>
                        <datalist id="series-list">@foreach ($options['series'] as $s)<option value="{{ $s }}">@endforeach</datalist>
                    </div>
                </div>
                <div class="form-row" style="--cols: 3">
                    <div class="field">
                        <label for="section">Section</label>
                        <select id="section" name="section" class="select" required>
                            @foreach ($options['sections'] as $key => $label)
                                <option value="{{ $key }}" @selected(old('section', $book->section) === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field">
                        <label for="class">Grade</label>
                        <select id="class" name="class" class="select" required>
                            @foreach ($options['classes'] as $class)
                                <option value="{{ $class }}" @selected(old('class', $book->class) === $class)>{{ ($options['classLabel'])($class) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field">
                        <label for="type">Book type</label>
                        <select id="type" name="type" class="select" required>
                            @foreach ($options['types'] as $type)
                                <option @selected(old('type', $book->type) === $type)>{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="field">
                    <span class="field-label">Boards this book is listed on</span>
                    <div class="checks">
                        @foreach ($options['boards'] as $board)
                            <label class="check"><input type="checkbox" name="boards[]" value="{{ $board }}" @checked(in_array($board, old('boards', $book->boards ?? []), true))> {{ $board }}</label>
                        @endforeach
                    </div>
                    @error('boards')<span class="field-error">{{ $message }}</span>@enderror
                </div>
                <div class="field">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" class="textarea" placeholder="Leave empty to use the standard description for this kind of book.">{{ old('description', $book->description) }}</textarea>
                </div>
            </section>

            <section class="card card-pad form">
                <div class="card-head" style="margin: -26px -26px 4px; padding-inline: 26px;"><h2>Price and <em>stock</em></h2></div>
                <div class="form-row" style="--cols: 3">
                    <div class="field">
                        <label for="mrp">MRP (₹)</label>
                        <input id="mrp" type="number" min="0" name="mrp" value="{{ old('mrp', $book->mrp) }}" class="input" required>
                        @error('mrp')<span class="field-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="field">
                        <label for="price">Selling price (₹)</label>
                        <input id="price" type="number" min="0" name="price" value="{{ old('price', $book->price) }}" class="input" required>
                        @error('price')<span class="field-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="field">
                        <label for="stock">Copies in stock</label>
                        <input id="stock" type="number" min="0" name="stock" value="{{ old('stock', $book->stock) }}" class="input" required>
                        <small>At 0 the website shows “Out of stock”.</small>
                    </div>
                </div>
                <div class="form-row">
                    <div class="field">
                        <label for="badge">Badge</label>
                        <input id="badge" name="badge" value="{{ old('badge', $book->badge) }}" class="input" list="badges" placeholder="e.g. New, Best seller">
                        <datalist id="badges"><option value="New"><option value="Best seller"><option value="Bundle"></datalist>
                    </div>
                    <div class="field">
                        <label for="slug">Web address</label>
                        <input id="slug" name="slug" value="{{ old('slug', $book->slug) }}" class="input" placeholder="made from the title if left empty">
                        @error('slug')<span class="field-error">{{ $message }}</span>@enderror
                    </div>
                </div>
            </section>
        </div>

        <div class="sticky-side">
            <section class="card card-pad form">
                <span class="field-label">Cover</span>
                <div class="cover-drop">
                    <img id="cover-preview" src="{{ $book->image ?: asset('images/books/placeholder.webp') }}" alt="">
                    <input type="file" name="cover" accept="image/jpeg,image/png,image/webp" data-cover-input="#cover-preview">
                    <small class="muted">JPG, PNG or WebP, up to 5 MB. Saved as a 600px WebP.</small>
                </div>
                @error('cover')<span class="field-error">{{ $message }}</span>@enderror

                <label class="switch">
                    <span>Show on website<small>Hidden books stay here but not in the store.</small></span>
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $book->is_active))>
                </label>
                <label class="switch">
                    <span>New release<small>Appears in “New this session”.</small></span>
                    <input type="checkbox" name="is_new" value="1" @checked(old('is_new', $book->is_new))>
                </label>

                <div class="form-actions">
                    <button type="submit" class="btn" style="flex: 1;"><x-admin.icon name="check" size="15" /> {{ $editing ? 'Save changes' : 'Add book' }}</button>
                </div>
                @if ($editing)
                    <a href="{{ route('books.show', $book->slug) }}" target="_blank" class="link" style="text-align: center;">View on website ↗</a>
                    <p class="muted" style="text-align: center;">{{ $book->sold_count }} sold · updated {{ $book->updated_at?->diffForHumans() }}</p>
                @endif
            </section>
        </div>
    </form>
@endsection
