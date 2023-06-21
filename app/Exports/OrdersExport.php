<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class OrdersExport implements FromView, ShouldAutoSize
{
    protected $orders; 

    function __construct($orders) {
        $this->orders = $orders; 
    }

    public function view(): View
    { 

        return view('excel_exports.orders', [
            'orders' => $this->orders
        ]); 
    } 
}
