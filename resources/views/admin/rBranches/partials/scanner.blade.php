
<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="qr_scan_historyLabel">Sacnner</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="card"> 
            <div class="card-body text-center">
                <div class="row">
                    <div class="col-md-5">
                        <section class="container" id="cam-content">
                            <div class="mb-3">
                                <button class="btn btn-pill btn-lg btn-success" id="startButton">Start</button>
                                <button class="btn btn-pill btn-lg btn-info " id="resetButton">Stop</button>
                            </div>
        
                            <div>
                                <video id="video" width="300" height="200" style="border: 1px solid gray"></video>
                            </div>
        
                            <div id="sourceSelectPanel" style="display:none">
                                <span for="sourceSelect">Change video source:</span>
                                <select id="sourceSelect" style="max-width:400px">
                                </select>
                            </div> 

                            <div style="display: none" class="text-center">
                                <span for="decoding-style"> Decoding Style:</span>
                                <select id="decoding-style" size="1">
                                    <option value="once">Decode once</option>
                                    <option value="continuously">Decode continuously</option>
                                </select>
                            </div> 
                        </section>
                    </div>
                    <div class="col-md-4" style="overflow-y: scroll;overflow-x:hidden;height:500px"> 
                        <h5>Scanned</h5>
                        <table class=" table table-bordered table-striped table-hover datatable table-responsive-lg table-responsive-md table-responsive-sm" id="scanned-table">
                            <thead>
                                <tr> 
                                    <th>
                                        id
                                    </th>
                                    <th>
                                        المنتج
                                    </th> 
                                    <th>
                                        الاسم
                                    </th>  
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(array_reverse(explode(',',$qr_scan_history->scanned)) as $qr_product_key_id)
                                @php
                                    $qr_product_key = \App\Models\QrProductKey::find($qr_product_key_id); 
                                @endphp
                                    <tr>
                                        <td>{{$qr_product_key->id ?? ''}}</td>
                                        <td>{{$qr_product_key->product->product ?? ''}}</td>
                                        <td>{{$qr_product_key->name ?? ''}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table> 
                    </div>
                    <div class="col-md-3">
                        <h5 style="color: rgb(93, 143, 93)">Results</h5> 
                        <table class=" table table-bordered table-striped table-hover datatable table-responsive-lg table-responsive-md table-responsive-sm" id="results"> 
                            <tbody>
                                <tr>
                                    <th>المنتج</th>
                                    <th>الكمية</th>
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
                                        </tr>
                                    @endforeach
                                @endif
                        </table>  
                    </div>
                </div>
            </div>
        </div>
    </div> 
</div>

    
