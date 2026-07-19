@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ __('global.create') }} {{ __('cruds.userAlert.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('admin.user-alerts.store') }}" enctype="multipart/form-data" id="user-alert-form">
            @csrf

            <div class="form-group">
                <label class="required" for="user_type">{{ __('cruds.userAlert.fields.user_type') }}</label>
                <select class="form-control {{ $errors->has('user_type') ? 'is-invalid' : '' }}" name="user_type" id="user_type" required>
                    <option value="">{{ __('global.pleaseSelect') }}</option>
                    @foreach($userTypes as $key => $label)
                        <option value="{{ $key }}" {{ old('user_type') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('user_type'))
                    <div class="invalid-feedback">
                        {{ $errors->first('user_type') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.userAlert.fields.user_type_helper') }}</span>
            </div>

            <div class="form-group" id="recipient-mode-group" style="display: none;">
                <label class="required">{{ __('cruds.userAlert.fields.recipient_mode') }}</label>
                <div class="d-flex flex-column">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="recipient_mode" id="recipient_mode_all" value="all" {{ old('recipient_mode', 'all') === 'all' ? 'checked' : '' }}>
                        <label class="form-check-label" for="recipient_mode_all">
                            {{ __('cruds.userAlert.fields.recipient_mode_all') }}
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="recipient_mode" id="recipient_mode_specific" value="specific" {{ old('recipient_mode') === 'specific' ? 'checked' : '' }}>
                        <label class="form-check-label" for="recipient_mode_specific">
                            {{ __('cruds.userAlert.fields.recipient_mode_specific') }}
                        </label>
                    </div>
                </div>
                @if($errors->has('recipient_mode'))
                    <div class="text-danger mt-1">
                        {{ $errors->first('recipient_mode') }}
                    </div>
                @endif
            </div>

            <div class="form-group" id="users-group" style="display: none;">
                <label class="required" for="users">{{ __('cruds.userAlert.fields.user') }}</label>
                <div style="padding-bottom: 4px">
                    <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ __('global.select_all') }}</span>
                    <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ __('global.deselect_all') }}</span>
                </div>
                <select class="form-control select2 {{ $errors->has('users') ? 'is-invalid' : '' }}" name="users[]" id="users" multiple>
                </select>
                @if($errors->has('users'))
                    <div class="invalid-feedback">
                        {{ $errors->first('users') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.userAlert.fields.user_helper') }}</span>
            </div>

            <div class="form-group">
                <label class="required" for="title">{{ __('cruds.userAlert.fields.title_field') }}</label>
                <input class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" type="text" name="title" id="title" value="{{ old('title', '') }}" required>
                @if($errors->has('title'))
                    <div class="invalid-feedback">
                        {{ $errors->first('title') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.userAlert.fields.title_field_helper') }}</span>
            </div>

            <div class="form-group">
                <label class="required" for="alert_text">{{ __('cruds.userAlert.fields.alert_body') }}</label>
                <textarea class="form-control {{ $errors->has('alert_text') ? 'is-invalid' : '' }}" name="alert_text" id="alert_text" rows="4" required>{{ old('alert_text', '') }}</textarea>
                @if($errors->has('alert_text'))
                    <div class="invalid-feedback">
                        {{ $errors->first('alert_text') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.userAlert.fields.alert_body_helper') }}</span>
            </div>

            <div class="form-group">
                <label for="alert_link">{{ __('cruds.userAlert.fields.alert_link') }}</label>
                <input class="form-control {{ $errors->has('alert_link') ? 'is-invalid' : '' }}" type="text" name="alert_link" id="alert_link" value="{{ old('alert_link', '') }}">
                @if($errors->has('alert_link'))
                    <div class="invalid-feedback">
                        {{ $errors->first('alert_link') }}
                    </div>
                @endif
                <span class="help-block">{{ __('cruds.userAlert.fields.alert_link_helper') }}</span>
            </div>

            <div class="form-group" id="push-notification-group" style="display: none;">
                <div class="form-check">
                    <input type="hidden" name="send_push_notification" value="0">
                    <input class="form-check-input" type="checkbox" name="send_push_notification" id="send_push_notification" value="1" {{ old('send_push_notification') ? 'checked' : '' }}>
                    <label class="form-check-label" for="send_push_notification">
                        {{ __('cruds.userAlert.fields.send_push_notification') }}
                    </label>
                </div>
                <span class="help-block">{{ __('cruds.userAlert.fields.send_push_notification_helper') }}</span>
            </div>

            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ __('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
@parent
<script>
$(function () {
    const oldUsers = @json(old('users', []));
    const usersUrl = "{{ route('admin.user-alerts.usersByType') }}";
    const $userType = $('#user_type');
    const $recipientModeGroup = $('#recipient-mode-group');
    const $usersGroup = $('#users-group');
    const $usersSelect = $('#users');
    const $pushGroup = $('#push-notification-group');

    function toggleRecipientSections() {
        const userType = $userType.val();
        const recipientMode = $('input[name="recipient_mode"]:checked').val();

        if (!userType) {
            $recipientModeGroup.hide();
            $usersGroup.hide();
            $pushGroup.hide();
            return;
        }

        $recipientModeGroup.show();
        $pushGroup.toggle(userType === 'customer');

        if (recipientMode === 'specific') {
            $usersGroup.show();
        } else {
            $usersGroup.hide();
        }
    }

    function loadUsers(userType, selectedIds) {
        if (!userType) {
            $usersSelect.empty().trigger('change');
            return;
        }

        $.get(usersUrl, { user_type: userType })
            .done(function (users) {
                $usersSelect.empty();

                users.forEach(function (user) {
                    const selected = selectedIds.includes(String(user.id)) || selectedIds.includes(user.id);
                    const option = new Option(user.name, user.id, selected, selected);
                    $usersSelect.append(option);
                });

                $usersSelect.trigger('change');
            });
    }

    $userType.on('change', function () {
        const userType = $(this).val();
        loadUsers(userType, []);
        toggleRecipientSections();
    });

    $('input[name="recipient_mode"]').on('change', function () {
        toggleRecipientSections();
    });

    if ($userType.val()) {
        loadUsers($userType.val(), oldUsers);
    }

    toggleRecipientSections();
});
</script>
@endsection
