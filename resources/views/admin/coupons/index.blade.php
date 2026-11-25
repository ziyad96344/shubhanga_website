@extends('admin.layouts.app')

@section('title', 'Coupons')
@section('crumb', 'Marketing')
@section('heading')Coupon <em>codes</em>@endsection
@section('actions')
    <a href="{{ route('admin.coupons.create') }}" class="btn btn--sm"><x-admin.icon name="plus" size="14" /> New coupon</a>
@endsection

@section('content')
    <section class="card">
        @if ($coupons->isEmpty())
            <div class="empty"><h3>No coupons yet</h3><p>Create a code and customers can apply it at checkout.</p><p style="margin-top: 16px;"><a href="{{ route('admin.coupons.create') }}" class="btn btn--sm">Create the first coupon</a></p></div>
        @else
            <div class="table-wrap">
                <table class="table">
                    <thead><tr><th>Code</th><th>Discount</th><th>Minimum cart</th><th>Used</th><th>Expires</th><th>Status</th><th></th></tr></thead>
                    <tbody>
                        @foreach ($coupons as $coupon)
                            @php($expired = $coupon->expires_at && $coupon->expires_at->isPast())
                            <tr>
                                <td><span class="strong" style="font-family: ui-monospace, monospace; letter-spacing: .06em;">{{ $coupon->code }}</span></td>
                                <td>{{ $coupon->label() }}</td>
                                <td>{{ $coupon->min_cart_value ? '₹'.number_format($coupon->min_cart_value) : '—' }}</td>
                                <td>{{ $coupon->used_count }}{{ $coupon->max_uses ? ' / '.$coupon->max_uses : '' }}</td>
                                <td>{{ $coupon->expires_at ? $coupon->expires_at->format('d M Y') : 'Never' }}</td>
                                <td>
                                    @if (! $coupon->is_active)<span class="badge badge--muted">Off</span>
                                    @elseif ($expired)<span class="badge badge--warn">Expired</span>
                                    @else<span class="badge badge--ok">Active</span>@endif
                                </td>
                                <td>
                                    <div class="row-actions">
                                        <a href="{{ route('admin.coupons.edit', $coupon) }}" class="icon-btn" title="Edit"><x-admin.icon name="edit" size="15" /></a>
                                        <form method="post" action="{{ route('admin.coupons.destroy', $coupon) }}" data-confirm="Delete coupon {{ $coupon->code }}?">
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
    </section>
@endsection
