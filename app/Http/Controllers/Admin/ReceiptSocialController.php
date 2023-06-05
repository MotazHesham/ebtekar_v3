<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyReceiptSocialRequest;
use App\Http\Requests\StoreReceiptSocialRequest;
use App\Http\Requests\UpdateReceiptSocialRequest;
use App\Models\Country;
use App\Models\ReceiptSocial;
use App\Models\Social;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ReceiptSocialController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('receipt_social_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = ReceiptSocial::with(['staff', 'designer', 'preparer', 'manufacturer', 'shipmenter', 'delivery_man', 'shipping_country', 'socials'])->select(sprintf('%s.*', (new ReceiptSocial)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'receipt_social_show';
                $editGate      = 'receipt_social_edit';
                $deleteGate    = 'receipt_social_delete';
                $crudRoutePart = 'receipt-socials';

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
                return $row->client_type ? ReceiptSocial::CLIENT_TYPE_SELECT[$row->client_type] : '';
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
            $table->editColumn('discount', function ($row) {
                return $row->discount ? $row->discount : '';
            });
            $table->editColumn('commission', function ($row) {
                return $row->commission ? $row->commission : '';
            });
            $table->editColumn('extra_commission', function ($row) {
                return $row->extra_commission ? $row->extra_commission : '';
            });
            $table->editColumn('total_cost', function ($row) {
                return $row->total_cost ? $row->total_cost : '';
            });
            $table->editColumn('done', function ($row) {
                return '<input type="checkbox" disabled ' . ($row->done ? 'checked' : null) . '>';
            });
            $table->editColumn('quickly', function ($row) {
                return '<input type="checkbox" disabled ' . ($row->quickly ? 'checked' : null) . '>';
            });
            $table->editColumn('confirm', function ($row) {
                return '<input type="checkbox" disabled ' . ($row->confirm ? 'checked' : null) . '>';
            });
            $table->editColumn('returned', function ($row) {
                return '<input type="checkbox" disabled ' . ($row->returned ? 'checked' : null) . '>';
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

            $table->editColumn('cancel_reason', function ($row) {
                return $row->cancel_reason ? $row->cancel_reason : '';
            });
            $table->editColumn('delay_reason', function ($row) {
                return $row->delay_reason ? $row->delay_reason : '';
            });
            $table->editColumn('delivery_status', function ($row) {
                return $row->delivery_status ? ReceiptSocial::DELIVERY_STATUS_SELECT[$row->delivery_status] : '';
            });
            $table->editColumn('note', function ($row) {
                return $row->note ? $row->note : '';
            });
            $table->editColumn('payment_status', function ($row) {
                return $row->payment_status ? ReceiptSocial::PAYMENT_STATUS_SELECT[$row->payment_status] : '';
            });
            $table->editColumn('playlist_status', function ($row) {
                return $row->playlist_status ? ReceiptSocial::PLAYLIST_STATUS_SELECT[$row->playlist_status] : '';
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

            $table->editColumn('socials', function ($row) {
                $labels = [];
                foreach ($row->socials as $social) {
                    $labels[] = sprintf('<span class="label label-info label-many">%s</span>', $social->name);
                }

                return implode(' ', $labels);
            });

            $table->rawColumns(['actions', 'placeholder', 'done', 'quickly', 'confirm', 'returned', 'supplied', 'staff', 'designer', 'preparer', 'manufacturer', 'shipmenter', 'delivery_man', 'shipping_country', 'socials']);

            return $table->make(true);
        }

        return view('admin.receiptSocials.index');
    }

    public function create()
    {
        abort_if(Gate::denies('receipt_social_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $staff = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $shipping_countries = Country::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $socials = Social::pluck('name', 'id');

        return view('admin.receiptSocials.create', compact('shipping_countries', 'socials', 'staff'));
    }

    public function store(StoreReceiptSocialRequest $request)
    {
        $receiptSocial = ReceiptSocial::create($request->all());
        $receiptSocial->socials()->sync($request->input('socials', []));

        return redirect()->route('admin.receipt-socials.index');
    }

    public function edit(ReceiptSocial $receiptSocial)
    {
        abort_if(Gate::denies('receipt_social_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $staff = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $shipping_countries = Country::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $socials = Social::pluck('name', 'id');

        $receiptSocial->load('staff', 'designer', 'preparer', 'manufacturer', 'shipmenter', 'delivery_man', 'shipping_country', 'socials');

        return view('admin.receiptSocials.edit', compact('receiptSocial', 'shipping_countries', 'socials', 'staff'));
    }

    public function update(UpdateReceiptSocialRequest $request, ReceiptSocial $receiptSocial)
    {
        $receiptSocial->update($request->all());
        $receiptSocial->socials()->sync($request->input('socials', []));

        return redirect()->route('admin.receipt-socials.index');
    }

    public function show(ReceiptSocial $receiptSocial)
    {
        abort_if(Gate::denies('receipt_social_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $receiptSocial->load('staff', 'designer', 'preparer', 'manufacturer', 'shipmenter', 'delivery_man', 'shipping_country', 'socials', 'receiptsReceiptSocialProducts');

        return view('admin.receiptSocials.show', compact('receiptSocial'));
    }

    public function destroy(ReceiptSocial $receiptSocial)
    {
        abort_if(Gate::denies('receipt_social_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $receiptSocial->delete();

        return back();
    }

    public function massDestroy(MassDestroyReceiptSocialRequest $request)
    {
        $receiptSocials = ReceiptSocial::find(request('ids'));

        foreach ($receiptSocials as $receiptSocial) {
            $receiptSocial->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
