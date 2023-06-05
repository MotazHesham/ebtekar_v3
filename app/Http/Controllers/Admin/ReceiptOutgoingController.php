<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyReceiptOutgoingRequest;
use App\Http\Requests\StoreReceiptOutgoingRequest;
use App\Http\Requests\UpdateReceiptOutgoingRequest;
use App\Models\ReceiptOutgoing;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ReceiptOutgoingController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('receipt_outgoing_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = ReceiptOutgoing::with(['staff'])->select(sprintf('%s.*', (new ReceiptOutgoing)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'receipt_outgoing_show';
                $editGate      = 'receipt_outgoing_edit';
                $deleteGate    = 'receipt_outgoing_delete';
                $crudRoutePart = 'receipt-outgoings';

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
            $table->editColumn('note', function ($row) {
                return $row->note ? $row->note : '';
            });
            $table->editColumn('done', function ($row) {
                return '<input type="checkbox" disabled ' . ($row->done ? 'checked' : null) . '>';
            });
            $table->editColumn('printing_times', function ($row) {
                return $row->printing_times ? $row->printing_times : '';
            });
            $table->addColumn('staff_name', function ($row) {
                return $row->staff ? $row->staff->name : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'done', 'staff']);

            return $table->make(true);
        }

        return view('admin.receiptOutgoings.index');
    }

    public function create()
    {
        abort_if(Gate::denies('receipt_outgoing_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $staff = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.receiptOutgoings.create', compact('staff'));
    }

    public function store(StoreReceiptOutgoingRequest $request)
    {
        $receiptOutgoing = ReceiptOutgoing::create($request->all());

        return redirect()->route('admin.receipt-outgoings.index');
    }

    public function edit(ReceiptOutgoing $receiptOutgoing)
    {
        abort_if(Gate::denies('receipt_outgoing_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $staff = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $receiptOutgoing->load('staff');

        return view('admin.receiptOutgoings.edit', compact('receiptOutgoing', 'staff'));
    }

    public function update(UpdateReceiptOutgoingRequest $request, ReceiptOutgoing $receiptOutgoing)
    {
        $receiptOutgoing->update($request->all());

        return redirect()->route('admin.receipt-outgoings.index');
    }

    public function show(ReceiptOutgoing $receiptOutgoing)
    {
        abort_if(Gate::denies('receipt_outgoing_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $receiptOutgoing->load('staff', 'receiptOutgoingReceiptOutgoingProducts');

        return view('admin.receiptOutgoings.show', compact('receiptOutgoing'));
    }

    public function destroy(ReceiptOutgoing $receiptOutgoing)
    {
        abort_if(Gate::denies('receipt_outgoing_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $receiptOutgoing->delete();

        return back();
    }

    public function massDestroy(MassDestroyReceiptOutgoingRequest $request)
    {
        $receiptOutgoings = ReceiptOutgoing::find(request('ids'));

        foreach ($receiptOutgoings as $receiptOutgoing) {
            $receiptOutgoing->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
