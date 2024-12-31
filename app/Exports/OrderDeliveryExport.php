<?php

namespace App\Exports;

use App\Models\Receipt_social;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class OrderDeliveryExport implements FromView, ShouldAutoSize
{
    protected $orders;
    protected $type;

    function __construct($orders, $type) {
        $this->orders = $orders;
        $this->type = $type;
    }

    public function view(): View
    {
        return view('excel_exports.order_delivery', [
            'orders' => $this->orders,
            'type' => $this->type,
        ]);
    }
}
