<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class OrdersResultsExport implements FromView, ShouldAutoSize
{

    protected $rows;

    function __construct($rows) {
        $this->rows = $rows;
    }

    public function view(): View
    {
        return view('excel_exports.orders_results', [
            'rows' => $this->rows
        ]);
    }
}
