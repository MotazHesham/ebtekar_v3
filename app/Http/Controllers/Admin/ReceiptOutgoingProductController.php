<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyReceiptOutgoingProductRequest;
use App\Http\Requests\StoreReceiptOutgoingProductRequest;
use App\Http\Requests\UpdateReceiptOutgoingProductRequest;
use App\Models\ReceiptOutgoing;
use App\Models\ReceiptOutgoingProduct;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ReceiptOutgoingProductController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('receipt_outgoing_product_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $receiptOutgoingProducts = ReceiptOutgoingProduct::with(['receipt_outgoing'])->get();

        return view('admin.receiptOutgoingProducts.index', compact('receiptOutgoingProducts'));
    }

    public function create()
    {
        abort_if(Gate::denies('receipt_outgoing_product_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $receipt_outgoings = ReceiptOutgoing::pluck('order_num', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.receiptOutgoingProducts.create', compact('receipt_outgoings'));
    }

    public function store(StoreReceiptOutgoingProductRequest $request)
    {
        $receiptOutgoingProduct = ReceiptOutgoingProduct::create($request->all());

        return redirect()->route('admin.receipt-outgoing-products.index');
    }

    public function edit(ReceiptOutgoingProduct $receiptOutgoingProduct)
    {
        abort_if(Gate::denies('receipt_outgoing_product_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $receipt_outgoings = ReceiptOutgoing::pluck('order_num', 'id')->prepend(trans('global.pleaseSelect'), '');

        $receiptOutgoingProduct->load('receipt_outgoing');

        return view('admin.receiptOutgoingProducts.edit', compact('receiptOutgoingProduct', 'receipt_outgoings'));
    }

    public function update(UpdateReceiptOutgoingProductRequest $request, ReceiptOutgoingProduct $receiptOutgoingProduct)
    {
        $receiptOutgoingProduct->update($request->all());

        return redirect()->route('admin.receipt-outgoing-products.index');
    }

    public function show(ReceiptOutgoingProduct $receiptOutgoingProduct)
    {
        abort_if(Gate::denies('receipt_outgoing_product_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $receiptOutgoingProduct->load('receipt_outgoing');

        return view('admin.receiptOutgoingProducts.show', compact('receiptOutgoingProduct'));
    }

    public function destroy(ReceiptOutgoingProduct $receiptOutgoingProduct)
    {
        abort_if(Gate::denies('receipt_outgoing_product_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $receiptOutgoingProduct->delete();

        return back();
    }

    public function massDestroy(MassDestroyReceiptOutgoingProductRequest $request)
    {
        $receiptOutgoingProducts = ReceiptOutgoingProduct::find(request('ids'));

        foreach ($receiptOutgoingProducts as $receiptOutgoingProduct) {
            $receiptOutgoingProduct->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
