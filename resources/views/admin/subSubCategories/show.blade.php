@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ __('global.show') }} {{ __('cruds.subSubCategory.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-light" href="{{ route('admin.sub-sub-categories.index') }}">
                    {{ __('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ __('cruds.subSubCategory.fields.id') }}
                        </th>
                        <td>
                            {{ $subSubCategory->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.subSubCategory.fields.name') }}
                        </th>
                        <td>
                            {{ $subSubCategory->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.subSubCategory.fields.slug') }}
                        </th>
                        <td>
                            {{ $subSubCategory->slug }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.subSubCategory.fields.meta_title') }}
                        </th>
                        <td>
                            {{ $subSubCategory->meta_title }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.subSubCategory.fields.meta_description') }}
                        </th>
                        <td>
                            {{ $subSubCategory->meta_description }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.subSubCategory.fields.sub_category') }}
                        </th>
                        <td>
                            {{ $subSubCategory->sub_category->name ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-light" href="{{ route('admin.sub-sub-categories.index') }}">
                    {{ __('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection