@extends('admin.layouts.app')

@section('title', 'Enquiries')
@section('crumb', 'Messages')
@section('heading')Website <em>enquiries</em>@endsection

@section('content')
    <div class="tabs">
        <a href="{{ route('admin.enquiries.index', request()->except(['type', 'page'])) }}" class="tab {{ request('type') ? '' : 'is-active' }}">All <b>{{ $counts->sum() }}</b></a>
        @foreach ($types as $key => $label)
            <a href="{{ route('admin.enquiries.index', ['type' => $key] + request()->except(['type', 'page'])) }}" class="tab {{ request('type') === $key ? 'is-active' : '' }}">{{ $label }} <b>{{ $counts[$key] ?? 0 }}</b></a>
        @endforeach
    </div>

    <form method="get" class="card card-pad toolbar">
        @if (request('type'))<input type="hidden" name="type" value="{{ request('type') }}">@endif
        <div class="filters">
            <input type="search" name="search" value="{{ request('search') }}" placeholder="Name, email or phone" class="input">
            <select name="status" class="select" data-autosubmit>
                <option value="">Any status</option>
                @foreach (\App\Http\Controllers\Admin\EnquiryController::STATUSES as $key => $label)
                    <option value="{{ $key }}" @selected(request('status') === $key)>{{ $label }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn--ghost btn--sm"><x-admin.icon name="search" size="14" /> Search</button>
        </div>
    </form>

    <section class="card">
        @if ($enquiries->isEmpty())
            <div class="empty"><h3>No messages here</h3><p>Specimen copy requests, bulk orders, distributor applications and contact messages land here.</p></div>
        @else
            <div class="table-wrap">
                <table class="table">
                    <thead><tr><th>From</th><th>Form</th><th>Received</th><th>Status</th><th></th></tr></thead>
                    <tbody>
                        @foreach ($enquiries as $enquiry)
                            <tr>
                                <td><a href="{{ route('admin.enquiries.show', $enquiry) }}" class="{{ $enquiry->status === 'new' ? 'strong' : '' }}">{{ $enquiry->name }}</a><div class="muted">{{ $enquiry->email ?: $enquiry->phone }}</div></td>
                                <td>{{ $types[$enquiry->type] ?? Str::headline($enquiry->type) }}</td>
                                <td>{{ $enquiry->created_at->format('d M Y') }}<div class="muted">{{ $enquiry->created_at->diffForHumans() }}</div></td>
                                <td><span class="badge {{ ['new' => 'badge--brand', 'in_progress' => 'badge--warn', 'done' => 'badge--ok'][$enquiry->status] ?? 'badge--muted' }}">{{ \App\Http\Controllers\Admin\EnquiryController::STATUSES[$enquiry->status] ?? $enquiry->status }}</span></td>
                                <td><div class="row-actions"><a href="{{ route('admin.enquiries.show', $enquiry) }}" class="icon-btn" title="Open"><x-admin.icon name="arrow-right" size="15" /></a></div></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
        @include('admin.partials.pager', ['paginator' => $enquiries])
    </section>
@endsection
