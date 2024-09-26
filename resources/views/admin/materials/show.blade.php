@extends('layouts.admin')
@section('content')

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                {{ __('global.show') }} {{ __('cruds.material.title') }}
            </div>
        
            <div class="card-body">
                <div class="form-group">
                    <div class="form-group">
                        <a class="btn btn-default" href="{{ route('admin.materials.index') }}">
                            {{ __('global.back_to_list') }}
                        </a>
                    </div>
                    <table class="table table-bordered table-striped">
                        <tbody>
                            <tr>
                                <th>
                                    {{ __('cruds.material.fields.id') }}
                                </th>
                                <td>
                                    {{ $material->id }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    {{ __('cruds.material.fields.name') }}
                                </th>
                                <td>
                                    {{ $material->name }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    {{ __('cruds.material.fields.description') }}
                                </th>
                                <td>
                                    {{ $material->description }}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    {{ __('cruds.material.fields.remaining') }}
                                </th>
                                <td>
                                    {{ $material->remaining }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="form-group">
                        <a class="btn btn-default" href="{{ route('admin.materials.index') }}">
                            {{ __('global.back_to_list') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        
        <div class="card">
            <div class="card-header">
                {{ __('global.relatedData') }}
            </div>
            <ul class="nav nav-tabs" role="tablist" id="relationship-tabs">
                <li class="nav-item">
                    <a class="nav-link active" href="#materials_stock" role="tab" data-toggle="tab">
                        المخزن
                    </a>
                </li> 
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" role="tabpanel" id="materials_stock">
                    @includeIf('admin.materials.partials.stock', ['stock' => $material->stock()])
                </div> 
            </div>
        </div>
    </div>
</div>



@endsection