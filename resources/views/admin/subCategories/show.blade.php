@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ __('global.show') }} {{ __('cruds.subCategory.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-light" href="{{ route('admin.sub-categories.index') }}">
                    {{ __('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ __('cruds.subCategory.fields.id') }}
                        </th>
                        <td>
                            {{ $subCategory->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.subCategory.fields.name') }}
                        </th>
                        <td>
                            {{ $subCategory->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.subCategory.fields.slug') }}
                        </th>
                        <td>
                            {{ $subCategory->slug }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.subCategory.fields.meta_title') }}
                        </th>
                        <td>
                            {{ $subCategory->meta_title }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.subCategory.fields.meta_description') }}
                        </th>
                        <td>
                            {{ $subCategory->meta_description }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.subCategory.fields.category') }}
                        </th>
                        <td>
                            {{ $subCategory->category->name ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-light" href="{{ route('admin.sub-categories.index') }}">
                    {{ __('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection