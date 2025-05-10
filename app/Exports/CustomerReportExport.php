<?php

namespace App\Exports;

use App\Models\ReceiptSocial;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class CustomerReportExport implements FromView, ShouldAutoSize
{
    public function view(): View
    {
        $customers = ReceiptSocial::select('client_name', 'phone_number')
            ->selectRaw('COUNT(*) as order_count')
            ->groupBy('client_name', 'phone_number')
            ->having('order_count', '>', 1)
            ->orderBy('order_count', 'desc')
            ->get();

        return view('excel_exports.customer_report', [
            'customers' => $customers
        ]);
    }
} 