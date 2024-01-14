
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
                                </tr>
                                @if(json_decode($qr_scan_history->results))
                                    @foreach(json_decode($qr_scan_history->results) as $key =>  $product)
                                        @php
                                            $qr_product = \App\Models\QrProduct::find($key);
                                            $quantity = $qr_product->quantity ?? 0;
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
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>  
                    </div>
                    <div class="col-md-6" style="overflow-y: scroll;overflow-x:hidden;height:500px">
                        <h5 style="color: rgb(211, 54, 54)">Needs</h5> 
                        <hr>
                        @foreach($qr_products as $product)
                            @if(count($product->names) > 0)
                                <h3>{{ $product->product ?? '' }}</h3>
                                <table class=" table table-bordered table-striped table-hover datatable table-responsive-lg table-responsive-md table-responsive-sm"> 
                                    <tbody> 
                                        @foreach($product->names as $name)
                                            <tr id="tr_needs_{{$name->id}}" @if(in_array($name->id,explode(',',$qr_scan_history->printed))) style="background-color: #53e753;" @endif>
                                                <th> 
                                                    {{ $name->name ?? '' }}
                                                </th>
                                                <td> 
                                                    <a href="{{ route('admin.qr-products.print',$name->id) }}" target="print-frame" onclick="save_print('{{$qr_scan_history->id}}','{{$name->id}}')" class="btn btn-outline-warning">طباعة</a>
                                                </td>
                                            </tr>
                                        @endforeach 
                                    </tbody>
                                </table>  
                                <hr>
                            @endif
                        @endforeach 
                    </div>
                </div>
            </div>
        </div>
    </div> 
</div>

    
