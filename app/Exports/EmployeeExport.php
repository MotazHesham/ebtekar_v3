<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class EmployeeExport implements FromView, ShouldAutoSize
{
    protected $employee; 
    protected $month; 
    protected $year; 

    function __construct($employee,$month,$year) {
        $this->employee = $employee; 
        $this->month = $month; 
        $this->year = $year; 
    }

    public function view(): View
    { 

        return view('excel_exports.employee', [
            'employee' => $this->employee,
            'month' => $this->month,
            'year' => $this->year,
        ]); 
    } 
}
