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
            <th></th>
        </tr>
    </thead>
    <tbody> 
        @php
            $total = 0;
            $quantity = 0;
        @endphp
        @foreach ($products as $key => $product)
            @php
                $quantity_product = $product->products->quantity ?? 1;
                $total += $product->total_cost;
                $quantity += $product->quantity * $quantity_product;
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
                <td>{{ $product->products && $product->products->product_type == 'season' ? 'منتج سيزون' : 'منتج عادي' }}</td>
                <td>{{ $product->quantity * $quantity_product}}</td> 
                <td>{{ $product->total_cost}}</td> 
                <td></td>
            </tr> 
        @endforeach 
    </tbody>
</table>

<div style="display: flex; justify-content: space-between; align-items: center;" class="mt-3"> 
    <p style="margin: 0; font-size: 26px; font-weight: bold;">الكمية: {{ $quantity }} </p>
    <p style="margin: 0; font-size: 26px; font-weight: bold;">الاجمالي: {{ $total }} ج.م</p>
</div>

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