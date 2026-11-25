@extends('admin.layouts.app')

@php($editing = $coupon->exists)
@section('title', $editing ? 'Edit coupon' : 'New coupon')
@section('crumb', 'Coupons')
@section('heading'){!! $editing ? 'Edit <em>'.e($coupon->code).'</em>' : 'New <em>coupon</em>' !!}@endsection
@section('actions')
    <a href="{{ route('admin.coupons.index') }}" class="btn btn--ghost btn--sm"><x-admin.icon name="arrow-left" size="14" /> All coupons</a>
@endsection

@section('content')
    <form method="post" action="{{ $editing ? route('admin.coupons.update', $coupon) : route('admin.coupons.store') }}" class="card card-pad form" style="max-width: 760px;">
        @csrf
        @if ($editing) @method('put') @endif

        <div class="form-row">
            <div class="field">
                <label for="code">Code</label>
                <input id="code" name="code" value="{{ old('code', $coupon->code) }}" class="input" required placeholder="e.g. SCHOOL10" style="text-transform: uppercase;">
                @error('code')<span class="field-error">{{ $message }}</span>@enderror
            </div>
            <div class="field">
                <label for="type">Discount type</label>
                <select id="type" name="type" class="select">
                    <option value="percent" @selected(old('type', $coupon->type) === 'percent')>Percentage off</option>
                    <option value="flat" @selected(old('type', $coupon->type) === 'flat')>Flat amount off (₹)</option>
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="field">
                <label for="value">Value</label>
                <input id="value" type="number" min="1" name="value" value="{{ old('value', $coupon->value) }}" class="input" required>
                <small>10 means 10% or ₹10, depending on the type.</small>
                @error('value')<span class="field-error">{{ $message }}</span>@enderror
            </div>
            <div class="field">
                <label for="min_cart_value">Minimum cart value (₹)</label>
                <input id="min_cart_value" type="number" min="0" name="min_cart_value" value="{{ old('min_cart_value', $coupon->min_cart_value) }}" class="input" placeholder="No minimum">
            </div>
        </div>
        <div class="form-row">
            <div class="field">
                <label for="max_uses">Total uses allowed</label>
                <input id="max_uses" type="number" min="1" name="max_uses" value="{{ old('max_uses', $coupon->max_uses) }}" class="input" placeholder="Unlimited">
                @if ($editing)<small>Used {{ $coupon->used_count }} times so far.</small>@endif
            </div>
            <div class="field">
                <label for="expires_at">Expires on</label>
                <input id="expires_at" type="date" name="expires_at" value="{{ old('expires_at', $coupon->expires_at?->format('Y-m-d')) }}" class="input">
            </div>
        </div>
        <label class="switch">
            <span>Active<small>Customers can apply it at checkout.</small></span>
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $coupon->is_active))>
        </label>
        <div class="form-actions">
            <button type="submit" class="btn"><x-admin.icon name="check" size="15" /> {{ $editing ? 'Save coupon' : 'Create coupon' }}</button>
            <a href="{{ route('admin.coupons.index') }}" class="btn btn--ghost">Cancel</a>
        </div>
    </form>
@endsection
