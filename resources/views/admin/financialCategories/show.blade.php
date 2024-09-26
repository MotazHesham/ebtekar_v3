@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ __('global.show') }} {{ __('cruds.financialCategory.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.financial-categories.index') }}">
                    {{ __('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ __('cruds.financialCategory.fields.id') }}
                        </th>
                        <td>
                            {{ $financialCategory->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.financialCategory.fields.name') }}
                        </th>
                        <td>
                            {{ $financialCategory->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.financialCategory.fields.type') }}
                        </th>
                        <td>
                            {{ App\Models\FinancialCategory::TYPE_RADIO[$financialCategory->type] ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.financial-categories.index') }}">
                    {{ __('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection