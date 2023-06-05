<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyReceiptPriceViewRequest;
use App\Http\Requests\StoreReceiptPriceViewRequest;
use App\Http\Requests\UpdateReceiptPriceViewRequest;
use App\Models\ReceiptPriceView;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ReceiptPriceViewController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('receipt_price_view_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = ReceiptPriceView::query()->select(sprintf('%s.*', (new ReceiptPriceView)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'receipt_price_view_show';
                $editGate      = 'receipt_price_view_edit';
                $deleteGate    = 'receipt_price_view_delete';
                $crudRoutePart = 'receipt-price-views';

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
            $table->editColumn('phone_number', function ($row) {
                return $row->phone_number ? $row->phone_number : '';
            });
            $table->editColumn('total_cost', function ($row) {
                return $row->total_cost ? $row->total_cost : '';
            });
            $table->editColumn('place', function ($row) {
                return $row->place ? $row->place : '';
            });
            $table->editColumn('relate_duration', function ($row) {
                return $row->relate_duration ? $row->relate_duration : '';
            });
            $table->editColumn('supply_duration', function ($row) {
                return $row->supply_duration ? $row->supply_duration : '';
            });
            $table->editColumn('payment', function ($row) {
                return $row->payment ? $row->payment : '';
            });
            $table->editColumn('added_value', function ($row) {
                return '<input type="checkbox" disabled ' . ($row->added_value ? 'checked' : null) . '>';
            });
            $table->editColumn('printing_times', function ($row) {
                return $row->printing_times ? $row->printing_times : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'added_value']);

            return $table->make(true);
        }

        return view('admin.receiptPriceViews.index');
    }

    public function create()
    {
        abort_if(Gate::denies('receipt_price_view_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.receiptPriceViews.create');
    }

    public function store(StoreReceiptPriceViewRequest $request)
    {
        $receiptPriceView = ReceiptPriceView::create($request->all());

        return redirect()->route('admin.receipt-price-views.index');
    }

    public function edit(ReceiptPriceView $receiptPriceView)
    {
        abort_if(Gate::denies('receipt_price_view_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.receiptPriceViews.edit', compact('receiptPriceView'));
    }

    public function update(UpdateReceiptPriceViewRequest $request, ReceiptPriceView $receiptPriceView)
    {
        $receiptPriceView->update($request->all());

        return redirect()->route('admin.receipt-price-views.index');
    }

    public function show(ReceiptPriceView $receiptPriceView)
    {
        abort_if(Gate::denies('receipt_price_view_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.receiptPriceViews.show', compact('receiptPriceView'));
    }

    public function destroy(ReceiptPriceView $receiptPriceView)
    {
        abort_if(Gate::denies('receipt_price_view_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $receiptPriceView->delete();

        return back();
    }

    public function massDestroy(MassDestroyReceiptPriceViewRequest $request)
    {
        $receiptPriceViews = ReceiptPriceView::find(request('ids'));

        foreach ($receiptPriceViews as $receiptPriceView) {
            $receiptPriceView->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
