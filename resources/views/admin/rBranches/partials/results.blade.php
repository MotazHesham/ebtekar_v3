
<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="qr_scan_historyLabel">Results</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="card"> 
            <div class="card-body text-center">
                <div class="row"> 
                    <div class="col-md-6">
                        <h5 style="color: rgb(93, 143, 93)">Results</h5> 
                        <table class=" table table-bordered table-striped table-hover datatable table-responsive-lg table-responsive-md table-responsive-sm"> 
                            <tbody>
                                <tr>
                                    <th>المنتج</th>
                                    <th>الكمية</th>
                                    <th></th>
                                </tr>
                                @if(json_decode($qr_scan_history->results))
                                    @foreach(json_decode($qr_scan_history->results) as $key =>  $product)
                                        @php
                                            $qr_product_rbranch = \App\Models\QrProductRBranch::find($key);
                                            $quantity = $qr_product_rbranch->quantity ?? 0;
                                            $count = count($product->names) ?? 0;
                                        @endphp
                                        <tr>
                                            <th>  
                                                {{ $product->product ?? '' }}
                                            </th>
                                            <td> 
                                                <span class="badge badge-info"> الكمية الدورية : <h5>{{ $quantity}}</h5></span>
                                                <span class="badge badge-danger">  Scanned : <h5>{{ $count}}</h5></span>
                                                <span class="badge badge-success">  الاحتياج :  <h5>{{ $quantity - $count}}</h5></span> 
                                            </td>
                                            <td>
                                                <button class="btn btn-success" onclick="load_needs('{{$qr_scan_history->id}}','{{$qr_product_rbranch->id}}')">Load Needs</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>  
                    </div>
                    <div class="col-md-6" style="overflow-y: scroll;overflow-x:hidden;height:500px">
                        <h5 style="color: rgb(211, 54, 54)">Needs</h5> 
                        <hr>
                        <div id="load-needs">
                            click on (Load Needs) button on specific product... 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> 
</div>


