@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            Add Marketer
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.marketers.store') }}">
                @csrf
                @include('admin.marketers.partials.form', ['marketer' => null])
                <div class="form-group">
                    <button class="btn btn-danger" type="submit">{{ __('global.save') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
