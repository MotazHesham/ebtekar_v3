<h5>تقارير المنتجات المطلوبة </h5>
<table class="table datatable-reportt datatable">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">id</th>
            <th scope="col">المنتج</th>
            <th scope="col">منتج سيزون</th>
            <th scope="col">الكمية المطلوبة</th> 
            <th scope="col">الاجمالي</th> 
        </tr>
    </thead>
    <tbody> 
        @php
            $total = 0;
            $quantity = 0;
        @endphp
        @foreach ($products as $key => $product)
            @php
                $total += $product->total_cost;
                $quantity += $product->quantity;
            @endphp
            <tr>
                <th scope="row"></th>
                <td>{{ $key + 1 }}</td>
                <td>{{ $product->title }} 
                    @if($product->products && $product->products->shopify_id)
                        <span class="badge rounded-pill text-bg-success text-white">
                            Shopify id #{{ $product->products->shopify_id }}
                        </span>
                    @endif
                </td>
                <td>{{ $product->products && $product->products->product_type == 'season' ? 'منتج سيزون' : 'منتج غير سيزون' }}</td>
                <td>{{ $product->quantity}}</td> 
                <td>{{ $product->total_cost}}</td> 
            </tr> 
        @endforeach 
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td>الاجمالي: {{ $quantity }} </td>
            <td>الاجمالي: {{ $total }} </td>
        </tr>
    </tbody>
</table>


<script>
    $(function() {
        let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons) 

        $.extend(true, $.fn.dataTable.defaults, {
            orderCellsTop: true,
            order: [
                [1, 'desc']
            ],
            pageLength: 25,
        });
        let table = $('.datatable-reportt:not(.ajaxTable)').DataTable({
            buttons: dtButtons
        })
        $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
            $($.fn.dataTable.tables(true)).DataTable()
                .columns.adjust();
        });

    })
</script>