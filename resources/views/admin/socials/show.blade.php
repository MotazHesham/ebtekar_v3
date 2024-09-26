@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ __('global.show') }} {{ __('cruds.social.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.socials.index') }}">
                    {{ __('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ __('cruds.social.fields.id') }}
                        </th>
                        <td>
                            {{ $social->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.social.fields.name') }}
                        </th>
                        <td>
                            {{ $social->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.social.fields.photo') }}
                        </th>
                        <td>
                            @if($social->photo)
                                <a href="{{ $social->photo->getUrl() }}" target="_blank" style="display: inline-block">
                                    <img src="{{ $social->photo->getUrl('thumb') }}">
                                </a>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.socials.index') }}">
                    {{ __('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection