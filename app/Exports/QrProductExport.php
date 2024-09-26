<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class QrProductExport implements FromView, ShouldAutoSize
{
    protected $raws; 

    function __construct($raws) {
        $this->raws = $raws; 
    }

    public function view(): View
    { 
        return view('admin.rBranches.partials.excel', [
            'raws' => $this->raws
        ]);
    }  
}
