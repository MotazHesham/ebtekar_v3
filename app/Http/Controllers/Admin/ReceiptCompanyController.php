<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyReceiptCompanyRequest;
use App\Http\Requests\StoreReceiptCompanyRequest;
use App\Http\Requests\UpdateReceiptCompanyRequest;
use App\Models\Country;
use App\Models\ReceiptCompany;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ReceiptCompanyController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('receipt_company_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = ReceiptCompany::with(['staff', 'designer', 'preparer', 'manufacturer', 'shipmenter', 'delivery_man', 'shipping_country'])->select(sprintf('%s.*', (new ReceiptCompany)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'receipt_company_show';
                $editGate      = 'receipt_company_edit';
                $deleteGate    = 'receipt_company_delete';
                $crudRoutePart = 'receipt-companies';

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
            $table->editColumn('order_num', function ($row) {
                return $row->order_num ? $row->order_num : '';
            });
            $table->editColumn('client_name', function ($row) {
                return $row->client_name ? $row->client_name : '';
            });
            $table->editColumn('client_type', function ($row) {
                return $row->client_type ? ReceiptCompany::CLIENT_TYPE_SELECT[$row->client_type] : '';
            });
            $table->editColumn('phone_number', function ($row) {
                return $row->phone_number ? $row->phone_number : '';
            });
            $table->editColumn('phone_number_2', function ($row) {
                return $row->phone_number_2 ? $row->phone_number_2 : '';
            });
            $table->editColumn('deposit', function ($row) {
                return $row->deposit ? $row->deposit : '';
            });
            $table->editColumn('total_cost', function ($row) {
                return $row->total_cost ? $row->total_cost : '';
            });
            $table->editColumn('calling', function ($row) {
                return '<input type="checkbox" disabled ' . ($row->calling ? 'checked' : null) . '>';
            });
            $table->editColumn('quickly', function ($row) {
                return '<input type="checkbox" disabled ' . ($row->quickly ? 'checked' : null) . '>';
            });
            $table->editColumn('done', function ($row) {
                return '<input type="checkbox" disabled ' . ($row->done ? 'checked' : null) . '>';
            });
            $table->editColumn('no_answer', function ($row) {
                return '<input type="checkbox" disabled ' . ($row->no_answer ? 'checked' : null) . '>';
            });
            $table->editColumn('supplied', function ($row) {
                return '<input type="checkbox" disabled ' . ($row->supplied ? 'checked' : null) . '>';
            });
            $table->editColumn('printing_times', function ($row) {
                return $row->printing_times ? $row->printing_times : '';
            });

            $table->editColumn('shipping_country_name', function ($row) {
                return $row->shipping_country_name ? $row->shipping_country_name : '';
            });
            $table->editColumn('shipping_country_cost', function ($row) {
                return $row->shipping_country_cost ? $row->shipping_country_cost : '';
            });
            $table->editColumn('shipping_address', function ($row) {
                return $row->shipping_address ? $row->shipping_address : '';
            });
            $table->editColumn('note', function ($row) {
                return $row->note ? $row->note : '';
            });
            $table->editColumn('cancel_reason', function ($row) {
                return $row->cancel_reason ? $row->cancel_reason : '';
            });
            $table->editColumn('delay_reason', function ($row) {
                return $row->delay_reason ? $row->delay_reason : '';
            });
            $table->editColumn('photos', function ($row) {
                if (! $row->photos) {
                    return '';
                }
                $links = [];
                foreach ($row->photos as $media) {
                    $links[] = '<a href="' . $media->getUrl() . '" target="_blank"><img src="' . $media->getUrl('thumb') . '" width="50px" height="50px"></a>';
                }

                return implode(' ', $links);
            });
            $table->editColumn('delivery_status', function ($row) {
                return $row->delivery_status ? ReceiptCompany::DELIVERY_STATUS_SELECT[$row->delivery_status] : '';
            });
            $table->editColumn('payment_status', function ($row) {
                return $row->payment_status ? ReceiptCompany::PAYMENT_STATUS_SELECT[$row->payment_status] : '';
            });
            $table->editColumn('playlist_status', function ($row) {
                return $row->playlist_status ? ReceiptCompany::PLAYLIST_STATUS_SELECT[$row->playlist_status] : '';
            });
            $table->addColumn('staff_name', function ($row) {
                return $row->staff ? $row->staff->name : '';
            });

            $table->addColumn('designer_name', function ($row) {
                return $row->designer ? $row->designer->name : '';
            });

            $table->addColumn('preparer_name', function ($row) {
                return $row->preparer ? $row->preparer->name : '';
            });

            $table->addColumn('manufacturer_name', function ($row) {
                return $row->manufacturer ? $row->manufacturer->name : '';
            });

            $table->addColumn('shipmenter_name', function ($row) {
                return $row->shipmenter ? $row->shipmenter->name : '';
            });

            $table->addColumn('delivery_man_name', function ($row) {
                return $row->delivery_man ? $row->delivery_man->name : '';
            });

            $table->addColumn('shipping_country_name', function ($row) {
                return $row->shipping_country ? $row->shipping_country->name : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'calling', 'quickly', 'done', 'no_answer', 'supplied', 'photos', 'staff', 'designer', 'preparer', 'manufacturer', 'shipmenter', 'delivery_man', 'shipping_country']);

            return $table->make(true);
        }

        return view('admin.receiptCompanies.index');
    }

    public function create()
    {
        abort_if(Gate::denies('receipt_company_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $staff = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $shipping_countries = Country::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.receiptCompanies.create', compact('shipping_countries', 'staff'));
    }

    public function store(StoreReceiptCompanyRequest $request)
    {
        $receiptCompany = ReceiptCompany::create($request->all());

        foreach ($request->input('photos', []) as $file) {
            $receiptCompany->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('photos');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $receiptCompany->id]);
        }

        return redirect()->route('admin.receipt-companies.index');
    }

    public function edit(ReceiptCompany $receiptCompany)
    {
        abort_if(Gate::denies('receipt_company_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $staff = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $shipping_countries = Country::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $receiptCompany->load('staff', 'designer', 'preparer', 'manufacturer', 'shipmenter', 'delivery_man', 'shipping_country');

        return view('admin.receiptCompanies.edit', compact('receiptCompany', 'shipping_countries', 'staff'));
    }

    public function update(UpdateReceiptCompanyRequest $request, ReceiptCompany $receiptCompany)
    {
        $receiptCompany->update($request->all());

        if (count($receiptCompany->photos) > 0) {
            foreach ($receiptCompany->photos as $media) {
                if (! in_array($media->file_name, $request->input('photos', []))) {
                    $media->delete();
                }
            }
        }
        $media = $receiptCompany->photos->pluck('file_name')->toArray();
        foreach ($request->input('photos', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $receiptCompany->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('photos');
            }
        }

        return redirect()->route('admin.receipt-companies.index');
    }

    public function show(ReceiptCompany $receiptCompany)
    {
        abort_if(Gate::denies('receipt_company_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $receiptCompany->load('staff', 'designer', 'preparer', 'manufacturer', 'shipmenter', 'delivery_man', 'shipping_country');

        return view('admin.receiptCompanies.show', compact('receiptCompany'));
    }

    public function destroy(ReceiptCompany $receiptCompany)
    {
        abort_if(Gate::denies('receipt_company_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $receiptCompany->delete();

        return back();
    }

    public function massDestroy(MassDestroyReceiptCompanyRequest $request)
    {
        $receiptCompanies = ReceiptCompany::find(request('ids'));

        foreach ($receiptCompanies as $receiptCompany) {
            $receiptCompany->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('receipt_company_create') && Gate::denies('receipt_company_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new ReceiptCompany();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
