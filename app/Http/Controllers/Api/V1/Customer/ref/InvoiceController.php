<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Customer\InvoiceResource;
use App\Http\ResponseHelper;
use App\Models\Order;
use Illuminate\Http\Request;
class InvoiceController extends Controller
{
    public function invoices()
    {
        $invoices = Order::where('user_id', auth()->user()->id)
            ->whereIn('delivery_status', ['delivered_from_store', 'client_received'])
            ->with('store')
            ->paginate(10);
        return ResponseHelper::returnResource(InvoiceResource::collection($invoices));
    }
    public function invoicePdf(Request $request)
    {
        $invoice = Order::where('user_id', auth()->user()->id)
            ->where('id', $request->id)
            ->whereIn('delivery_status', ['delivered_from_store', 'client_received'])
            ->firstOrFail();
        return ResponseHelper::returnResponse('',[
            'pdf' => generateInvoicePdf($invoice)
        ]);
    }
}
