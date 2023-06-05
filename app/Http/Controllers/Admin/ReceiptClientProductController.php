<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyReceiptClientProductRequest;
use App\Http\Requests\StoreReceiptClientProductRequest;
use App\Http\Requests\UpdateReceiptClientProductRequest;
use App\Models\ReceiptClientProduct;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ReceiptClientProductController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('receipt_client_product_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = ReceiptClientProduct::with(['receipts'])->select(sprintf('%s.*', (new ReceiptClientProduct)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'receipt_client_product_show';
                $editGate      = 'receipt_client_product_edit';
                $deleteGate    = 'receipt_client_product_delete';
                $crudRoutePart = 'receipt-client-products';

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
            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });
            $table->editColumn('price', function ($row) {
                return $row->price ? $row->price : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }

        return view('admin.receiptClientProducts.index');
    }

    public function create()
    {
        abort_if(Gate::denies('receipt_client_product_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.receiptClientProducts.create');
    }

    public function store(StoreReceiptClientProductRequest $request)
    {
        $receiptClientProduct = ReceiptClientProduct::create($request->all());

        return redirect()->route('admin.receipt-client-products.index');
    }

    public function edit(ReceiptClientProduct $receiptClientProduct)
    {
        abort_if(Gate::denies('receipt_client_product_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $receiptClientProduct->load('receipts');

        return view('admin.receiptClientProducts.edit', compact('receiptClientProduct'));
    }

    public function update(UpdateReceiptClientProductRequest $request, ReceiptClientProduct $receiptClientProduct)
    {
        $receiptClientProduct->update($request->all());

        return redirect()->route('admin.receipt-client-products.index');
    }

    public function show(ReceiptClientProduct $receiptClientProduct)
    {
        abort_if(Gate::denies('receipt_client_product_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $receiptClientProduct->load('receipts');

        return view('admin.receiptClientProducts.show', compact('receiptClientProduct'));
    }

    public function destroy(ReceiptClientProduct $receiptClientProduct)
    {
        abort_if(Gate::denies('receipt_client_product_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $receiptClientProduct->delete();

        return back();
    }

    public function massDestroy(MassDestroyReceiptClientProductRequest $request)
    {
        $receiptClientProducts = ReceiptClientProduct::find(request('ids'));

        foreach ($receiptClientProducts as $receiptClientProduct) {
            $receiptClientProduct->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
