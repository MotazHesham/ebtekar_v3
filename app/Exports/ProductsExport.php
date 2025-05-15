<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ProductsExport implements FromView, ShouldAutoSize
{
    protected $products; 

    function __construct($products) {
        $this->products = $products; 
    }

    public function view(): View
    { 

        return view('excel_exports.products', [
            'products' => $this->products
        ]); 
    } 
}
