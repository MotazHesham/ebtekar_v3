<div class="card">
    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover datatable datatable-Role">
            <thead>
                <th>الاسم</th>
                <th>رقم الفون</th>
                <th>العنوان</th>
                <th>الموديل</th>
                <th>المبلغ</th>
            </thead>
            <tbody>
                @if(session('orders'))
                    @foreach (session('orders', []) as $order)
                        <tr>
                            <td>{{ $order['name'] }}</td>
                            <td>{{ $order['phone'] }}</td>
                            <td>{{ $order['address'] }}</td>
                            <td>{{ $order['model'] }}</td>
                            <td>{{ $order['cost'] }}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>

<script>
    
    $(function() {
        let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons) 
        $.extend(true, $.fn.dataTable.defaults, {
            orderCellsTop: false, 
        });
        let table = $('.datatable-Role:not(.ajaxTable)').DataTable({
            buttons: dtButtons
        })
        $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
            $($.fn.dataTable.tables(true)).DataTable()
                .columns.adjust();
        });

    })
</script>  