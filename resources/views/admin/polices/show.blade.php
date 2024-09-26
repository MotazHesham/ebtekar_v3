@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ __('global.show') }} {{ __('cruds.police.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.polices.index') }}">
                    {{ __('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ __('cruds.police.fields.id') }}
                        </th>
                        <td>
                            {{ $police->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.police.fields.name') }}
                        </th>
                        <td>
                            {{ $police->name ? \App\Models\Police::NAME_SELECT[$police->name] : '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.police.fields.content') }}
                        </th>
                        <td>
                            {!! $police->content !!}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.polices.index') }}">
                    {{ __('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection