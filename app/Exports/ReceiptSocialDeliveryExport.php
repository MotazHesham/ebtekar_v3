<?php

namespace App\Exports;

use App\Models\Receipt_social;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ReceiptSocialDeliveryExport implements FromView, ShouldAutoSize
{
    protected $receipts;
    protected $type;

    function __construct($receipts, $type) {
        $this->receipts = $receipts;
        $this->type = $type;
    }

    public function view(): View
    {
        return view('excel_exports.receipt_social_delivery', [
            'receipts' => $this->receipts,
            'type' => $this->type,
        ]);
    }
}
