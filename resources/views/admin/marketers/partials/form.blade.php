<div class="row">
    <div class="form-group col-md-4">
        <label for="name">Name</label>
        <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name"
            value="{{ old('name', $marketer->name ?? '') }}" required>
    </div>
    <div class="form-group col-md-4">
        <label for="code">Referral Code</label>
        <input class="form-control {{ $errors->has('code') ? 'is-invalid' : '' }}" type="text" name="code" id="code"
            value="{{ old('code', $marketer->code ?? '') }}" required>
    </div>
    <div class="form-group col-md-4">
        <label for="commission_rate">Commission Rate %</label>
        <input class="form-control {{ $errors->has('commission_rate') ? 'is-invalid' : '' }}" type="number"
            name="commission_rate" id="commission_rate" min="0" max="100" step="0.01"
            value="{{ old('commission_rate', $marketer->commission_rate ?? 0) }}" required>
    </div>
    <div class="form-group col-md-6">
        <label for="user_name">Login Name</label>
        <input class="form-control {{ $errors->has('user_name') ? 'is-invalid' : '' }}" type="text" name="user_name"
            id="user_name" value="{{ old('user_name', optional(optional($marketer)->user)->name ?? optional($marketer)->name ?? '') }}" required>
        @error('user_name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="form-group col-md-6">
        <label for="website_setting_id">Website</label>
        <select class="form-control select2 {{ $errors->has('website_setting_id') ? 'is-invalid' : '' }}"
            name="website_setting_id" id="website_setting_id">
            @foreach ($websites as $id => $entry)
                <option value="{{ $id }}"
                    {{ (string) old('website_setting_id', $marketer->website_setting_id ?? '') === (string) $id ? 'selected' : '' }}>
                    {{ $entry }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-md-4">
        <label for="user_email">Login Email</label>
        <input class="form-control {{ $errors->has('user_email') ? 'is-invalid' : '' }}" type="email" name="user_email"
            id="user_email" value="{{ old('user_email', optional(optional($marketer)->user)->email ?? '') }}">
        @error('user_email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="form-group col-md-4">
        <label for="user_phone_number">Login Phone</label>
        <input class="form-control {{ $errors->has('user_phone_number') ? 'is-invalid' : '' }}" type="text"
            name="user_phone_number" id="user_phone_number"
            value="{{ old('user_phone_number', optional(optional($marketer)->user)->phone_number ?? '') }}">
        @error('user_phone_number')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="form-group col-md-4">
        <label for="user_password">{{ $marketer ? 'New Password (optional)' : 'Login Password' }}</label>
        <input class="form-control {{ $errors->has('user_password') ? 'is-invalid' : '' }}" type="password"
            name="user_password" id="user_password" {{ $marketer ? '' : 'required' }}>
        @error('user_password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="form-group col-md-6">
        <div class="form-check mt-3">
            <input class="form-check-input" type="checkbox" value="1" id="is_active" name="is_active"
                {{ old('is_active', $marketer ? $marketer->is_active : true) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">
                Active
            </label>
        </div>
    </div>
</div>
