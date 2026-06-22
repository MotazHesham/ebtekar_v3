@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            {{ __('global.show') }} {{ __('cruds.calendarDate.title_singular') }}
        </div>

        <div class="card-body">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.calendar-dates.index') }}">
                    {{ __('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>{{ __('cruds.calendarDate.fields.id') }}</th>
                        <td>{{ $calendarDate->id }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('cruds.calendarDate.fields.user') }}</th>
                        <td>{{ $calendarDate->user?->name }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('cruds.calendarDate.fields.user_phone') }}</th>
                        <td>{{ $calendarDate->user?->phone_number }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('cruds.calendarDate.fields.type') }}</th>
                        <td>{{ $calendarDate->type_label }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('cruds.calendarDate.fields.description') }}</th>
                        <td>{{ $calendarDate->description }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('cruds.calendarDate.fields.event_date') }}</th>
                        <td>{{ $calendarDate->event_date?->format('Y-m-d') }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('cruds.calendarDate.fields.reminder_days_before') }}</th>
                        <td>{{ $calendarDate->effective_reminder_days_before }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('cruds.calendarDate.fields.reminder_source') }}</th>
                        <td>
                            {{ is_null($calendarDate->reminder_days_before)
                                ? __('cruds.calendarDate.fields.uses_default_reminder')
                                : __('cruds.calendarDate.fields.custom_reminder') }}
                        </td>
                    </tr>
                    <tr>
                        <th>{{ __('cruds.calendarDate.fields.created_at') }}</th>
                        <td>{{ $calendarDate->created_at }}</td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.calendar-dates.index') }}">
                    {{ __('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
@endsection
