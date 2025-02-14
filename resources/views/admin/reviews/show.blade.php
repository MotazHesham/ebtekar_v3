@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ __('global.show') }} {{ __('cruds.review.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.reviews.index') }}">
                    {{ __('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ __('cruds.review.fields.id') }}
                        </th>
                        <td>
                            {{ $review->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.review.fields.product') }}
                        </th>
                        <td>
                            {{ $review->product->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.review.fields.user') }}
                        </th>
                        <td>
                            {{ $review->user->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.review.fields.rating') }}
                        </th>
                        <td>
                            {{ $review->rating }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.review.fields.comment') }}
                        </th>
                        <td>
                            {{ $review->comment }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.review.fields.published') }}
                        </th>
                        <td>
                            <input type="checkbox" disabled="disabled" {{ $review->published ? 'checked' : '' }}>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.reviews.index') }}">
                    {{ __('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection