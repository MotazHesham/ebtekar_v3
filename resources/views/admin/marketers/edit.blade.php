@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            Edit Marketer
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.marketers.update', $marketer->id) }}">
                @method('PUT')
                @csrf
                @include('admin.marketers.partials.form', ['marketer' => $marketer])
                <div class="form-group">
                    <button class="btn btn-danger" type="submit">{{ __('global.save') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
