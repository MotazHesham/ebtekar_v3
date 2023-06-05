<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyExcelFileRequest;
use App\Http\Requests\StoreExcelFileRequest;
use App\Http\Requests\UpdateExcelFileRequest;
use App\Models\ExcelFile;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ExcelFilesController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('excel_file_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = ExcelFile::query()->select(sprintf('%s.*', (new ExcelFile)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'excel_file_show';
                $editGate      = 'excel_file_edit';
                $deleteGate    = 'excel_file_delete';
                $crudRoutePart = 'excel-files';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('type', function ($row) {
                return $row->type ? ExcelFile::TYPE_SELECT[$row->type] : '';
            });
            $table->editColumn('uploaded_file', function ($row) {
                return $row->uploaded_file ? '<a href="' . $row->uploaded_file->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>' : '';
            });
            $table->editColumn('result_file', function ($row) {
                return $row->result_file ? '<a href="' . $row->result_file->getUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>' : '';
            });
            $table->editColumn('results', function ($row) {
                return $row->results ? $row->results : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'uploaded_file', 'result_file']);

            return $table->make(true);
        }

        return view('admin.excelFiles.index');
    }

    public function create()
    {
        abort_if(Gate::denies('excel_file_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.excelFiles.create');
    }

    public function store(StoreExcelFileRequest $request)
    {
        $excelFile = ExcelFile::create($request->all());

        if ($request->input('uploaded_file', false)) {
            $excelFile->addMedia(storage_path('tmp/uploads/' . basename($request->input('uploaded_file'))))->toMediaCollection('uploaded_file');
        }

        if ($request->input('result_file', false)) {
            $excelFile->addMedia(storage_path('tmp/uploads/' . basename($request->input('result_file'))))->toMediaCollection('result_file');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $excelFile->id]);
        }

        return redirect()->route('admin.excel-files.index');
    }

    public function edit(ExcelFile $excelFile)
    {
        abort_if(Gate::denies('excel_file_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.excelFiles.edit', compact('excelFile'));
    }

    public function update(UpdateExcelFileRequest $request, ExcelFile $excelFile)
    {
        $excelFile->update($request->all());

        if ($request->input('uploaded_file', false)) {
            if (! $excelFile->uploaded_file || $request->input('uploaded_file') !== $excelFile->uploaded_file->file_name) {
                if ($excelFile->uploaded_file) {
                    $excelFile->uploaded_file->delete();
                }
                $excelFile->addMedia(storage_path('tmp/uploads/' . basename($request->input('uploaded_file'))))->toMediaCollection('uploaded_file');
            }
        } elseif ($excelFile->uploaded_file) {
            $excelFile->uploaded_file->delete();
        }

        if ($request->input('result_file', false)) {
            if (! $excelFile->result_file || $request->input('result_file') !== $excelFile->result_file->file_name) {
                if ($excelFile->result_file) {
                    $excelFile->result_file->delete();
                }
                $excelFile->addMedia(storage_path('tmp/uploads/' . basename($request->input('result_file'))))->toMediaCollection('result_file');
            }
        } elseif ($excelFile->result_file) {
            $excelFile->result_file->delete();
        }

        return redirect()->route('admin.excel-files.index');
    }

    public function show(ExcelFile $excelFile)
    {
        abort_if(Gate::denies('excel_file_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.excelFiles.show', compact('excelFile'));
    }

    public function destroy(ExcelFile $excelFile)
    {
        abort_if(Gate::denies('excel_file_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $excelFile->delete();

        return back();
    }

    public function massDestroy(MassDestroyExcelFileRequest $request)
    {
        $excelFiles = ExcelFile::find(request('ids'));

        foreach ($excelFiles as $excelFile) {
            $excelFile->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('excel_file_create') && Gate::denies('excel_file_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new ExcelFile();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
