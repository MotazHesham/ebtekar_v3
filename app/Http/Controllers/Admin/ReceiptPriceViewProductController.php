<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyReceiptPriceViewProductRequest;
use App\Http\Requests\StoreReceiptPriceViewProductRequest;
use App\Http\Requests\UpdateReceiptPriceViewProductRequest;
use App\Models\ReceiptPriceView;
use App\Models\ReceiptPriceViewProduct;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ReceiptPriceViewProductController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('receipt_price_view_product_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $receiptPriceViewProducts = ReceiptPriceViewProduct::with(['receipt_price_view'])->get();

        return view('admin.receiptPriceViewProducts.index', compact('receiptPriceViewProducts'));
    }

    public function create()
    {
        abort_if(Gate::denies('receipt_price_view_product_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $receipt_price_views = ReceiptPriceView::pluck('order_num', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.receiptPriceViewProducts.create', compact('receipt_price_views'));
    }

    public function store(StoreReceiptPriceViewProductRequest $request)
    {
        $receiptPriceViewProduct = ReceiptPriceViewProduct::create($request->all());

        return redirect()->route('admin.receipt-price-view-products.index');
    }

    public function edit(ReceiptPriceViewProduct $receiptPriceViewProduct)
    {
        abort_if(Gate::denies('receipt_price_view_product_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $receipt_price_views = ReceiptPriceView::pluck('order_num', 'id')->prepend(trans('global.pleaseSelect'), '');

        $receiptPriceViewProduct->load('receipt_price_view');

        return view('admin.receiptPriceViewProducts.edit', compact('receiptPriceViewProduct', 'receipt_price_views'));
    }

    public function update(UpdateReceiptPriceViewProductRequest $request, ReceiptPriceViewProduct $receiptPriceViewProduct)
    {
        $receiptPriceViewProduct->update($request->all());

        return redirect()->route('admin.receipt-price-view-products.index');
    }

    public function show(ReceiptPriceViewProduct $receiptPriceViewProduct)
    {
        abort_if(Gate::denies('receipt_price_view_product_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $receiptPriceViewProduct->load('receipt_price_view');

        return view('admin.receiptPriceViewProducts.show', compact('receiptPriceViewProduct'));
    }

    public function destroy(ReceiptPriceViewProduct $receiptPriceViewProduct)
    {
        abort_if(Gate::denies('receipt_price_view_product_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $receiptPriceViewProduct->delete();

        return back();
    }

    public function massDestroy(MassDestroyReceiptPriceViewProductRequest $request)
    {
        $receiptPriceViewProducts = ReceiptPriceViewProduct::find(request('ids'));

        foreach ($receiptPriceViewProducts as $receiptPriceViewProduct) {
            $receiptPriceViewProduct->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
