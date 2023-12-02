<table>

    <thead>
        <tr>
            <th>
                النوع
            </th>
            <th>
                المبلغ
            </th> 
            <th>
                السبب
            </th>  
            <th>
                تاريخ  
            </th>  
        </tr>
    </thead>

    

    <tbody>  
        @php
            $total = 0;
        @endphp
        @foreach($employee->employeeEmployeeFinancials()->with('financial_category')->whereYear('entry_date', '=', $year)->whereMonth('entry_date', '=', $month)->get() as $raw)  
            <tr>
                <td>{{ $raw->financial_category->name ?? '' }}</td>
                <td>{{ $raw->amount }}</td>  
                <td>{{ $raw->reason }}</td>  
                <td>{{ $raw->entry_date }}</td>   
            </tr>
            @php
                $total += $raw->amount;
            @endphp
        @endforeach   
        <tr></tr>
        <tr></tr> 
        <tr>
            <td colspan="3" style="text-align: right; font-weight: bold;">{{' الراتب ' . $employee->salery}}</td>
        </tr>
        @foreach($employee->employeeEmployeeFinancials()->whereYear('entry_date', '=', $year)->whereMonth('entry_date', '=', $month)->get()->groupBy('financial_category_id') as $cat =>  $raw) 
            <tr>
                <td colspan="3" style="text-align: right; font-weight: bold;">{{\App\Models\FinancialCategory::find($cat)->name .' => ' . $raw->sum('amount')}}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="3" style="text-align: right; font-weight: bold;">{{' الأجمالي ' . ($employee->salery + $total)}}</td>
        </tr>
    </tbody>
</table>