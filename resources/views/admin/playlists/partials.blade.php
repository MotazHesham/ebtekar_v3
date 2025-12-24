@foreach ($playlists as $key => $item)
    @php
        if ($type == 'design') {
            $authenticated = $item['designer_id'];
        } elseif ($type == 'manufacturing') {
            $authenticated = $item['manufacturer_id'];
        } elseif ($type == 'prepare') {
            $authenticated = $item['preparer_id'];
        } elseif ($type == 'shipment') {
            $authenticated = $item['shipmenter_id'];
        }
        
    @endphp

    {{-- order card --}}
    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
        <div class="card order-card" data-id="{{ $item['id'] }}" id="order-card-{{ $item['id'] }}"
            style="margin-bottom:30px"> 
            {{-- code --}}
            <div class=" order-card-left-side text-center mb-3"
                style="color: white;margin-bottom:20px;padding: 31px 0;font-size: 20px;
                    @if ($item['quickly'] == 1 && $item['shipping_country_id'] == 20)  background-image: linear-gradient(270deg,#9f1b2e,black,#7C42C9); 
                    @elseif ($item['client_review'] && $type == 'design') background-image: linear-gradient(90deg,#6de4a4,#4e54c8);
                    @elseif ($item['shipping_country_id'] == 20) background:#7c42c9;
                    @elseif ($item['quickly'] == 1)background-image: linear-gradient(#9f1b2e,#1a1313);@endif">

                {{ $item['order_num'] }} 
                @if ($item['client_review'])
                    <span class="pull-right badge badge-warning">مرسل للمراجعة</span>
                @endif
                @if ($item['printing_times'] == 0)
                    <span class="pull-right badge badge-info">{{ __('New') }}</span>
                @endif
                @if($type == 'design' && $item['returned_to_design'] > 1)
                    <span class="pull-right badge badge-danger">تم الإرجاع {{ $item['returned_to_design'] - 1 }} مرة</span>
                @endif
            </div>
            @if($type == 'design')
                @if($item['client_review'] && isset($item['client_review_comment']) && $item['client_review_comment'])
                    <span class="pull-right badge badge-secondary text-dark">{{ $item['client_review_comment'] }}</span>
                @endif
            @endif
            {{-- order info --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body row text-center">
                            <div class="col">
                                <small class="fw-semibold">{{ $item['created_at'] }}</small>
                                <div class="text-uppercase text-medium-emphasis small badge badge-light">
                                    {{ __('cruds.playlist.fields.created_at') }}
                                </div>
                            </div>
                            <div class="vr"></div>
                            <div class="col">
                                <small class="fw-semibold">{{ $item['send_to_playlist_date'] }}</small>
                                <div class="text-uppercase text-medium-emphasis small badge badge-light">
                                    {{ __('cruds.playlist.fields.send_to_playlist_date') }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <span class="badge badge-light text-dark mb-1">{{ __('Note') }}</span>
                            <div class="container-scrollable"><?php echo $item['note']; ?></div> 
                        </div>
                        <div class="col-md-8">
                            <span class="badge badge-success text-white mb-1">{{ $item['shipping_country_id'] ? getCountryNameById($item['shipping_country_id']) : '' }}</span>
                            <span class="badge badge-dark text-white mb-1">{{ $item['client_type'] == 'individual' ? 'فردي' : 'شركة' }}</span>
                            <span class="badge badge-light text-dark mb-1">{{ __('Description') }}</span>
                            <div class="container-scrollable"> 
                                {!! strip_tags($item['description'], '<p><br>')  !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- action Buttons --}}
            <div class="order-card-actions" id="order-card-actions-{{ $item['id'] }}">
                @if(!$item['hold'] || auth()->user()->is_admin)
                    
                    @if ($type == 'design') 
                        <a class="btn btn-info  btn-sm rounded-pill" 
                            href="{{ route('admin.playlists.client_review', ['id' => $item['id'], 'model_type' => $item['model_type']]) }}">
                            @if($item['client_review']) تعديل جديد @else مراجعة العميل @endif
                        </a>
                    @endif
                    @if (auth()->user()->is_admin || $authenticated == auth()->user()->id || Gate::allows('transfer_receipts'))
                        <a class="btn btn-danger btn-sm rounded-pill text-white"
                            onclick="if(confirm('هل أنت متأكد من الإرجاع؟')) change_status('{{ $item['id'] }}','{{ $item['model_type'] }}','{{ $back_type }}','back')">
                            {{ $title_back }}
                        </a>
                        <a class="btn btn-warning btn-sm rounded-pill text-white"
                            onclick="if(confirm('هل أنت متأكد من الإرسال؟')) change_status('{{ $item['id'] }}','{{ $item['model_type'] }}','{{ $next_type }}','send')">
                            {{ $title_send }}
                        </a>
                    @endif
                    @if ($type == 'design')  
                        <button class="btn btn-light btn-sm rounded-pill" 
                                onclick="if(confirm('هل أنت متأكد من الطباعة؟')) check_printable(this,'{{ $item['id'] }}', '{{ $item['model_type'] }}');">
                            {{ __('global.print') }}
                        </button>
                    @endif
                    <a class="btn btn-success btn-sm rounded-pill text-white"
                        onclick="show_details('{{ $item['id'] }}','{{ $item['model_type'] }}')"
                        title="{{ __('Order Details') }}">
                        أظهارالصور
                    </a>
                    <a class="btn btn-primary btn-sm rounded-pill text-white"
                        onclick="show_history('{{ $item['id'] }}','{{ $item['model_type'] }}')"
                        title="سجل حركة الفاتورة في البلاي ليست">
                        سجل الحالة
                    </a>
                @endif
            </div>
        </div>
    </div>
@endforeach
