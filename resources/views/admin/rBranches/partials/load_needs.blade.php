
                @foreach($qr_products as $product)
                    @if(count($product->names) > 0)
                        <h3>{{ $product->product ?? '' }}</h3>
                        <table class=" table table-bordered table-striped table-hover datatable table-responsive-lg table-responsive-md table-responsive-sm datatable-names2">  
                            <thead>
                                <tr>
                                    <th width="10">

                                    </th> 
                                    <th>
                                        name
                                    </th>  
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody> 
                                @foreach($product->names as $name)
                                    <tr data-entry-id="{{ $name->id }}" id="tr_needs_{{$name->id}}" @if(in_array($name->id,explode(',',$qr_scan_history->printed))) style="background-color: #53e753;" @endif>
                                        <td></td>
                                        <td> 
                                            {{ $name->name ?? '' }}
                                        </td>
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