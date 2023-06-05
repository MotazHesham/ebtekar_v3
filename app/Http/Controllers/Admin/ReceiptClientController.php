<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyReceiptClientRequest;
use App\Http\Requests\StoreReceiptClientRequest;
use App\Http\Requests\UpdateReceiptClientRequest;
use App\Models\ReceiptClient;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ReceiptClientController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('receipt_client_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = ReceiptClient::with(['staff'])->select(sprintf('%s.*', (new ReceiptClient)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'receipt_client_show';
                $editGate      = 'receipt_client_edit';
                $deleteGate    = 'receipt_client_delete';
                $crudRoutePart = 'receipt-clients';

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
            $table->editColumn('deposit', function ($row) {
                return $row->deposit ? $row->deposit : '';
            });
            $table->editColumn('discount', function ($row) {
                return $row->discount ? $row->discount : '';
            });
            $table->editColumn('note', function ($row) {
                return $row->note ? $row->note : '';
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
            $table->editColumn('printing_times', function ($row) {
                return $row->printing_times ? $row->printing_times : '';
            });
            $table->addColumn('staff_name', function ($row) {
                return $row->staff ? $row->staff->name : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'done', 'quickly', 'staff']);

            return $table->make(true);
        }

        return view('admin.receiptClients.index');
    }

    public function create()
    {
        abort_if(Gate::denies('receipt_client_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $staff = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.receiptClients.create', compact('staff'));
    }

    public function store(StoreReceiptClientRequest $request)
    {
        $receiptClient = ReceiptClient::create($request->all());

        return redirect()->route('admin.receipt-clients.index');
    }

    public function edit(ReceiptClient $receiptClient)
    {
        abort_if(Gate::denies('receipt_client_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $staff = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $receiptClient->load('staff');

        return view('admin.receiptClients.edit', compact('receiptClient', 'staff'));
    }

    public function update(UpdateReceiptClientRequest $request, ReceiptClient $receiptClient)
    {
        $receiptClient->update($request->all());

        return redirect()->route('admin.receipt-clients.index');
    }

    public function show(ReceiptClient $receiptClient)
    {
        abort_if(Gate::denies('receipt_client_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $receiptClient->load('staff', 'receiptsReceiptClientProducts');

        return view('admin.receiptClients.show', compact('receiptClient'));
    }

    public function destroy(ReceiptClient $receiptClient)
    {
        abort_if(Gate::denies('receipt_client_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $receiptClient->delete();

        return back();
    }

    public function massDestroy(MassDestroyReceiptClientRequest $request)
    {
        $receiptClients = ReceiptClient::find(request('ids'));

        foreach ($receiptClients as $receiptClient) {
            $receiptClient->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
