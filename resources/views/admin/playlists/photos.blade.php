<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title"> <b>{{ $raw->order_num }}</b> </h5>
        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
    </div> 
    <div class="modal-body">
        <div class="row">
            <div class="@if($playlist->model_type == 'company') col-md-8 @else col-md-12 @endif">
                @if($playlist->model_type == 'order') 
                    @foreach($raw->orderDetails as $orderDetail)
                        @if ($orderDetail->product != null)
                            <div>
                                @php 
                                    $image = isset($orderDetail->product->photos[0]) ? $orderDetail->product->photos[0]->getUrl('preview2') : '';
                                @endphp     
                                <a href="{{ $image }}" target="_blank"><img width="150" height="150" src={{ $image }}/></a>
                                @if(is_array(json_decode($orderDetail->photos)) && count(json_decode($orderDetail->photos)) > 0)
                                    <div>
                                        @foreach (json_decode($orderDetail->photos) as $key => $photo)
                                            <div style="display: inline;position: relative;">
                                                <a href="{{ asset($photo->photo) }}" target="_blanc">
                                                    <img style="padding:3px" src="{{ asset($photo->photo) }}" alt="" height="140" width="140" title="{{ $photo->note }}">
                                                </a>
                                                <div style=" display: inline; position: absolute; left: 11px; top: -22px;">
                                                    <div style=" background-color: #00000069; text-align: center; color: white; width: 120px;">
                                                        {{ $photo->note }}
                                                    </div>
                                                </div>
                                                <div class="text-center" style="display: inline;position: absolute; left: 3px; top: -58px;">
                                                    <a href="{{ asset($photo->photo) }}" download="{{$order->code}}_{{$key}}_{{ $photo->note }}" class="btn btn-success btn-sm"><i class="fa fa-download"></i></a>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @else
                            <strong>{{ __('N/A') }}</strong>
                        @endif
                        <hr>
                    @endforeach
                @elseif($playlist->model_type == 'social') 
                    @foreach($raw->receiptsReceiptSocialProducts as $receipt_product) 
                        @if ($receipt_product->products != null)
                            <h3>{{$receipt_product->products->name}}</h3>
                            <div><?php echo $receipt_product->description; ?></div>
                            <div> 
                                @foreach ($receipt_product->products->photos as $key => $media)
                                    <a href="{{ $media->getUrl() }}" target="_blank"><img width="150" height="150" src={{ $media->getUrl()  }}/></a>
                                @endforeach 
                                @if(is_array(json_decode($receipt_product->photos)) && count(json_decode($receipt_product->photos)) > 0)
                                    <div>
                                        @foreach (json_decode($receipt_product->photos) as $key => $photo)
                                            <div style="display: inline;position: relative;">
                                                <a href="{{ asset($photo->photo) }}" target="_blanc">
                                                    <img style="padding:3px" src="{{ asset($photo->photo) }}" alt="" height="140" width="140" title="{{$photo->note}}">
                                                </a>
                                                <div style=" display: inline; position: absolute; left: 11px; top: -22px;">
                                                    <div style=" background-color: #00000069; text-align: center; color: white; width: 120px;">
                                                        {{$photo->note }}
                                                    </div>
                                                </div>
                                                <div class="text-center" style="display: inline;position: absolute; left: 3px; top: -58px;">
                                                    <a href="{{ asset($photo->photo) }}" download="{{$raw->order_num}}_{{$key}}_{{$photo->note}}" class="btn btn-success btn-sm"><i class="fa fa-download"></i></a>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                                <br>
                                @if($receipt_product->pdf)
                                    <a href="{{ asset($receipt_product->pdf) }}" target="_blanc" class="btn btn-info">show pdf</a>
                                @endif
                            </div>
                        @else
                            <strong>{{ __('N/A') }}</strong>
                        @endif
                        <hr>
                    @endforeach
                @elseif($playlist->model_type == 'company') 
                    @if($raw->photos)
                        @foreach ($raw->photos as $key => $media)
                            <a href="{{ $media->getUrl() }}">
                                <img src="{{ $media->getUrl() }}" alt="" class="img-responsive" width="200" height="200">
                            </a>  <br>
                        @endforeach
                    @endif
                @endif
            </div>
            <div class="@if($playlist->model_type == 'company') col-md-4 @else col-md-12 @endif">
                <div class="row">
                    <div class="col-md-8">
                        <span class="badge badge-dark">{{ trans('cruds.playlist.fields.description') }}</span> 
                        <div>
                            <?php echo $playlist->description; ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <span class="badge badge-dark">{{ trans('cruds.playlist.fields.note') }}</span> 
                        <div>
                            <?php echo $playlist->note; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>