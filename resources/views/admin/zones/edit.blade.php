@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.zone.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.zones.update", [$zone->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.zone.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $zone->name) }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.zone.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="delivery_cost">{{ trans('cruds.zone.fields.delivery_cost') }}</label>
                <input class="form-control {{ $errors->has('delivery_cost') ? 'is-invalid' : '' }}" type="number" name="delivery_cost" id="delivery_cost" value="{{ old('delivery_cost', $zone->delivery_cost) }}" required>
                @if($errors->has('delivery_cost'))
                    <div class="invalid-feedback">
                        {{ $errors->first('delivery_cost') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.zone.fields.delivery_cost_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="countries">{{ trans('cruds.zone.fields.countries') }}</label>
                <select class="form-control select2 {{ $errors->has('countries') ? 'is-invalid' : '' }}" name="countries[]" id="countries" multiple>
                    @foreach($countries as $id => $country)
                        <option value="{{ $id }}" {{ (in_array($id, old('countries', [])) || $zone->countries->contains($id)) ? 'selected' : '' }}>{{ $country }}</option>
                    @endforeach
                </select>
                @if($errors->has('countries'))
                    <div class="invalid-feedback">
                        {{ $errors->first('countries') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.zone.fields.countries_helper') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
@parent
<script>
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>
@endsection 