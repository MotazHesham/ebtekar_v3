<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ShipmentsExport implements FromView, ShouldAutoSize
{
    public function __construct(protected Collection $shipments)
    {
    }

    public function view(): View
    {
        return view('excel_exports.shipments', [
            'shipments' => $this->shipments,
        ]);
    }
}
