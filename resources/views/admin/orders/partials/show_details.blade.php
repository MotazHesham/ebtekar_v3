<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-lg-5">
                <h4>{{ trans('cruds.order.extra.attached_photos') }}</h4>
                <hr>
                @php
                    $order_code = $orderDetail->order ? $orderDetail->order->order_num : '';
                @endphp
                @if (is_array(json_decode($orderDetail->photos)) && count(json_decode($orderDetail->photos)) > 0)
                    <div>
                        @foreach (json_decode($orderDetail->photos) as $key => $photo)
                            <div style="display: inline;position: relative;">
                                <img style="padding:3px" src="{{ asset($photo) }}" alt="" height="140" width="140"
                                    title="{{ json_decode($orderDetail->photos_note)[$key] ?? '' }}">
                                <div style=" display: inline; position: absolute; left: 11px; top: -22px;">
                                    <div
                                        style=" background-color: #00000069; text-align: center; color: white; width: 120px;">
                                        {{ json_decode($orderDetail->photos_note)[$key] ?? '' }}
                                    </div>
                                </div>
                                <div class="text-center" style="display: inline;position: absolute; left: 3px; top: -58px;">
                                    <a href="{{ asset($photo) }}"
                                        download="{{ $order_code }}_{{ $key }}_{{ json_decode($orderDetail->photos_note)[$key] ?? '' }}"
                                        class="btn btn-success btn-sm"><i class="fa fa-download"></i></a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p style="color: brown">{{ trans('cruds.order.extra.no_attached_photos') }}</p>
                @endif
            </div>





            <div class="col-lg-7">
                <!-- Product description -->
                <div class="product-description-wrapper">
                    <!-- Product title -->
                    <h3 class="product-title text-center">
                        {{ $orderDetail->product ? $orderDetail->product->name : '' }}
                    </h3>

                    @if ($orderDetail->variation != null)
                        <div>
                            <span class="badge badge-dark">{{ trans('cruds.order.extra.variation')}}</span> : <b
                                style="color: #2980B9;font-size: 23px;"> ( {{ $orderDetail->variation }} )</b>
                        </div>
                    @endif

                    <div>
                        <span class="badge badge-dark">{{ trans('cruds.order.extra.price')}}</span> : <b
                            style="color: #2980B9;font-size: 23px;">{{ dashboard_currency($orderDetail->price) }}</b>
                    </div>



                    <div>
                        <span class="badge badge-dark">{{ trans('cruds.order.extra.quantity')}}</span> : <b
                            style="color: #2980B9;font-size: 23px;">{{ $orderDetail->quantity }}</b>
                    </div>
                    <hr>

                    <div>
                        <span class="badge badge-dark">{{ trans('cruds.order.extra.description')}}</span> : <b
                            style="color: #2980B9;font-size: 23px;"><?php echo $orderDetail->description; ?> </b>
                    </div>

                    @if ($orderDetail->link != null)
                        <div>
                            <span class="badge badge-dark">{{ trans('cruds.order.extra.link')}}</span> : <b
                                style="color: #2980B9;font-size: 23px;"><a
                                    href="{{ $orderDetail->link }}">{{ $orderDetail->link }}</a></b>
                        </div>
                    @endif

                    <hr>

                    @if ($orderDetail->pdf != null)
                        <div>
                            <span class="badge badge-dark">{{ trans('cruds.order.order.pdf')}}</span> : <b
                                style="color: #2980B9;font-size: 23px;"><a href="{{ asset($orderDetail->pdf) }}"
                                    class="btn btn-outline-success"> {{ __('Download') }}</a></b>
                        </div>
                    @endif

                    <div>
                        <span class="badge badge-dark">{{ trans('cruds.order.extra.email_sent')}}</span> : <b
                            style="color: #2980B9;font-size: 23px;">
                            @if ($orderDetail->email_sent == 1)
                                Yes
                            @endif
                            @if ($orderDetail->email_sent == 0)
                                No
                            @endif
                        </b>
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>
