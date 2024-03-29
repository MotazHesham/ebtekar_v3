<h5>تقارير المنتجات المطلوبة </h5>
<table class="table">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">المنتج</th>
            <th scope="col">الكمية المطلوبة</th> 
            <th scope="col">الاجمالي</th> 
        </tr>
    </thead>
    <tbody> 
        @foreach ($products as $key => $product)
            <tr>
                <th scope="row">{{ $key + 1 }}</th>
                <td>{{ $product->title }}</td>
                <td>{{ $product->quantity}}</td> 
                <td>{{ $product->total_cost}}</td> 
            </tr> 
        @endforeach 
    </tbody>
</table>