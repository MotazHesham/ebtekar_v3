<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="AjaxModalLabel">المنتجات المطوب تصنيعها</h5>
        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <h3>منتجات السوشيال</h3>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">المنتج</th>
                    <th scope="col">الكمية المطلوبة</th> 
                </tr>
            </thead>
            <tbody>
                @if($items)
                    @foreach ($items as $key => $item)
                        <tr>
                            <td scope="row">{{ $key + 1 }}</td>
                            <td>{{ $item->title }}</td>
                            <td>{{ $item->quantity}}</td> 
                        </tr> 
                    @endforeach
                @endif 
            </tbody>
        </table>
        <h3>منتجات الموقع</h3>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">المنتج</th>
                    <th scope="col">الكمية المطلوبة</th> 
                </tr>
            </thead>
            <tbody>
                @if($items2)
                    @foreach ($items2 as $key => $item)
                        <tr>
                            @php($product = \App\Models\Product::find($item->product_id))
                            <td scope="row">{{ $key + 1 }}</td>
                            <td>{{ $product ? $product->name : $item->product_id }}</td>
                            <td>{{ $item->quantity}}</td> 
                        </tr> 
                    @endforeach
                @endif 
            </tbody>
        </table>
    </div>
</div>