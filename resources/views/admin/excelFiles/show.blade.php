@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ __('global.show') }} {{ __('cruds.excelFile.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.excel-files.index') }}">
                    {{ __('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ __('cruds.excelFile.fields.id') }}
                        </th>
                        <td>
                            {{ $excelFile->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.excelFile.fields.type') }}
                        </th>
                        <td>
                            {{ App\Models\ExcelFile::TYPE_SELECT[$excelFile->type] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.excelFile.fields.uploaded_file') }}
                        </th>
                        <td>
                            @if($excelFile->uploaded_file)
                                <a href="{{ $excelFile->uploaded_file->getUrl() }}" target="_blank">
                                    {{ __('global.view_file') }}
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.excelFile.fields.result_file') }}
                        </th>
                        <td>
                            @if($excelFile->result_file)
                                <a href="{{ $excelFile->result_file->getUrl() }}" target="_blank">
                                    {{ __('global.view_file') }}
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ __('cruds.excelFile.fields.results') }}
                        </th>
                        <td>
                            {{ $excelFile->results }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.excel-files.index') }}">
                    {{ __('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection