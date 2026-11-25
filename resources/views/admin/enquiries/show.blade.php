@extends('admin.layouts.app')

@section('title', 'Enquiry from '.$enquiry->name)
@section('crumb', $types[$enquiry->type] ?? Str::headline($enquiry->type))
@section('heading')Message from <em>{{ $enquiry->name }}</em>@endsection
@section('actions')
    <a href="{{ route('admin.enquiries.index') }}" class="btn btn--ghost btn--sm"><x-admin.icon name="arrow-left" size="14" /> All enquiries</a>
@endsection

@section('content')
    <div class="grid grid-form">
        <section class="card card-pad">
            <dl class="dl">
                <div><dt>Name</dt><dd>{{ $enquiry->name }}</dd></div>
                @if ($enquiry->email)<div><dt>Email</dt><dd><a href="mailto:{{ $enquiry->email }}" class="link">{{ $enquiry->email }}</a></dd></div>@endif
                @if ($enquiry->phone)<div><dt>Phone</dt><dd><a href="tel:{{ $enquiry->phone }}" class="link">{{ $enquiry->phone }}</a></dd></div>@endif
                @foreach ($enquiry->data ?? [] as $key => $value)
                    @continue(in_array($key, ['name', 'email', 'phone'], true) || $value === null || $value === '')
                    <div>
                        <dt>{{ Str::headline($key) }}</dt>
                        <dd>
                            @if (is_array($value))
                                {{ implode(', ', $value) }}
                            @elseif (is_string($value) && Str::startsWith($value, 'enquiries/'))
                                <a href="{{ route('admin.enquiries.file', [$enquiry, $key]) }}" class="link">Download attachment</a>
                            @else
                                {!! nl2br(e($value)) !!}
                            @endif
                        </dd>
                    </div>
                @endforeach
                <div><dt>Received</dt><dd>{{ $enquiry->created_at->format('d M Y, g:i a') }}</dd></div>
            </dl>
        </section>

        <div class="sticky-side">
            <form method="post" action="{{ route('admin.enquiries.update', $enquiry) }}" class="card card-pad form">
                @csrf @method('patch')
                <div class="field">
                    <label for="status">Status</label>
                    <select id="status" name="status" class="select">
                        @foreach (\App\Http\Controllers\Admin\EnquiryController::STATUSES as $key => $label)
                            <option value="{{ $key }}" @selected($enquiry->status === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn"><x-admin.icon name="check" size="15" /> Save</button>
                @if ($enquiry->email)
                    <a href="mailto:{{ $enquiry->email }}?subject={{ rawurlencode('Re: your enquiry to '.config('site.full_name')) }}" class="btn btn--ghost"><x-admin.icon name="enquiries" size="15" /> Reply by email</a>
                @endif
            </form>
            <form method="post" action="{{ route('admin.enquiries.destroy', $enquiry) }}" data-confirm="Delete this message? This cannot be undone.">
                @csrf @method('delete')
                <button type="submit" class="btn btn--danger" style="width: 100%;"><x-admin.icon name="trash" size="15" /> Delete message</button>
            </form>
        </div>
    </div>
@endsection
