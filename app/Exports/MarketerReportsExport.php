<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class MarketerReportsExport implements FromView, ShouldAutoSize
{
    protected $rows;

    public function __construct($rows)
    {
        $this->rows = $rows;
    }

    public function view(): View
    {
        return view('excel_exports.marketers_report', [
            'rows' => $this->rows,
        ]);
    }
}
