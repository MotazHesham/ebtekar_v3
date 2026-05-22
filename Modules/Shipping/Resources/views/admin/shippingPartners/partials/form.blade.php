<div class="form-group">
    <label class="required">{{ __('cruds.shippingPartner.fields.name') }}</label>
    <input class="form-control" type="text" name="name" value="{{ old('name', $shippingPartner->name ?? '') }}" required>
</div>
<div class="form-group">
    <label>{{ __('cruds.shippingPartner.fields.code') }}</label>
    <input class="form-control" type="text" name="code" value="{{ old('code', $shippingPartner->code ?? '') }}">
</div>
<div class="form-group">
    <label class="required">{{ __('cruds.shippingPartner.fields.email') }}</label>
    <input class="form-control" type="email" name="email" value="{{ old('email', optional($shippingPartner ?? null)->user?->email) }}" required>
</div>
<div class="form-group">
    <label>{{ __('cruds.shippingPartner.fields.phone') }}</label>
    <input class="form-control" type="text" name="phone" value="{{ old('phone', $shippingPartner->phone ?? '') }}">
</div>
<div class="form-group">
    <label>{{ __('cruds.shippingPartner.fields.address') }}</label>
    <input class="form-control" type="text" name="address" value="{{ old('address', $shippingPartner->address ?? '') }}">
</div>
<div class="form-group">
    <label class="{{ isset($shippingPartner) ? '' : 'required' }}">{{ __('cruds.shippingPartner.fields.password') }}</label>
    <input class="form-control" type="password" name="password" {{ isset($shippingPartner) ? '' : 'required' }}>
</div>
<div class="form-group">
    <label>{{ __('cruds.shippingPartner.fields.internal_notes') }}</label>
    <textarea class="form-control" name="internal_notes" rows="3">{{ old('internal_notes', $shippingPartner->internal_notes ?? '') }}</textarea>
</div>
<div class="form-group">
    <label>
        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $shippingPartner->is_active ?? true) ? 'checked' : '' }}>
        {{ __('cruds.shippingPartner.fields.is_active') }}
    </label>
</div>
