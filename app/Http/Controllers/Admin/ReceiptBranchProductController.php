<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyReceiptBranchProductRequest;
use App\Http\Requests\StoreReceiptBranchProductRequest;
use App\Http\Requests\UpdateReceiptBranchProductRequest;
use App\Models\ReceiptBranchProduct;
use App\Models\WebsiteSetting;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ReceiptBranchProductController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('receipt_branch_product_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = ReceiptBranchProduct::with(['receipts','website'])->select(sprintf('%s.*', (new ReceiptBranchProduct)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'receipt_branch_product_show';
                $editGate      = 'receipt_branch_product_edit';
                $deleteGate    = 'receipt_branch_product_delete';
                $crudRoutePart = 'receipt-branch-products';

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

            $table->editColumn('website_site_name', function ($row) { 
                return $row->website->site_name ?? '';
            });

            $table->rawColumns(['actions', 'placeholder','website_site_name']);

            return $table->make(true);
        }

        return view('admin.receiptBranchProducts.index');
    }

    public function create()
    {
        abort_if(Gate::denies('receipt_branch_product_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $websites = WebsiteSetting::pluck('site_name', 'id');
        return view('admin.receiptBranchProducts.create',compact('websites'));
    }

    public function store(StoreReceiptBranchProductRequest $request)
    {
        $receiptBranchProduct = ReceiptBranchProduct::create($request->all());

        return redirect()->route('admin.receipt-branch-products.index');
    }

    public function edit(ReceiptBranchProduct $receiptBranchProduct)
    {
        abort_if(Gate::denies('receipt_branch_product_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $receiptBranchProduct->load('receipts');

        $websites = WebsiteSetting::pluck('site_name', 'id');
        return view('admin.receiptBranchProducts.edit', compact('receiptBranchProduct','websites'));
    }

    public function update(UpdateReceiptBranchProductRequest $request, ReceiptBranchProduct $receiptBranchProduct)
    {
        $receiptBranchProduct->update($request->all());

        return redirect()->route('admin.receipt-branch-products.index');
    }

    public function show(ReceiptBranchProduct $receiptBranchProduct)
    {
        abort_if(Gate::denies('receipt_branch_product_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $receiptBranchProduct->load('receipts');

        return view('admin.receiptBranchProducts.show', compact('receiptBranchProduct'));
    }

    public function destroy(ReceiptBranchProduct $receiptBranchProduct)
    {
        abort_if(Gate::denies('receipt_branch_product_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $receiptBranchProduct->delete();

        return back();
    }

    public function massDestroy(MassDestroyReceiptBranchProductRequest $request)
    {
        $receiptBranchProducts = ReceiptBranchProduct::find(request('ids'));

        foreach ($receiptBranchProducts as $receiptBranchProduct) {
            $receiptBranchProduct->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
