<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="AjaxModalLabel">المنتجات المطوب تصنيعها</h5>
        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
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
                            <th scope="row">{{ $key + 1 }}</th>
                            <td>{{ $item->title }}</td>
                            <td>{{ $item->quantity}}</td> 
                        </tr> 
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>