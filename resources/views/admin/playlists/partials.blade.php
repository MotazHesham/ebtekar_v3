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
                style="color: white;margin-bottom:20px;
                    @if ($item['quickly'] == 1 && $item['shipping_country_id'] == 20)  background-image: linear-gradient(270deg,#9f1b2e,black,#7C42C9); 
                    @elseif ($item['shipping_country_id'] == 20) background:#7c42c9;
                    @elseif ($item['quickly'] == 1)background-image: linear-gradient(#9f1b2e,#1a1313);@endif">

                {{ $item['order_num'] }}
                @if ($item['printing_times'] == 0)
                    <span class="pull-right badge badge-info">{{ __('New') }}</span>
                @endif
            </div>
            {{-- order info --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body row text-center">
                            <div class="col">
                                <small class="fw-semibold">{{ $item['created_at'] }}</small>
                                <div class="text-uppercase text-medium-emphasis small badge badge-light">
                                    {{ trans('cruds.playlist.fields.created_at') }}
                                </div>
                            </div>
                            <div class="vr"></div>
                            <div class="col">
                                <small class="fw-semibold">{{ $item['send_to_playlist_date'] }}</small>
                                <div class="text-uppercase text-medium-emphasis small badge badge-light">
                                    {{ trans('cruds.playlist.fields.send_to_playlist_date') }}
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
                            <span class="badge badge-light text-dark mb-1">{{ __('Description') }}</span>
                            <div class="container-scrollable">
                                <?php echo $item['description']; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- action Buttons --}}
            <div class="order-card-actions" id="order-card-actions-{{ $item['id'] }}">
                @if (auth()->user()->is_admin || $authenticated == auth()->user()->id)
                    <a class="btn btn-danger btn-sm rounded-pill text-white"
                        onclick="change_status('{{ $item['id'] }}','{{ $item['model_type'] }}','{{ $back_type }}','back')">
                        {{ $title_back }}
                    </a>
                    <a class="btn btn-warning btn-sm rounded-pill text-white"
                        @if ($item['printing_times'] == 0) onclick="alert('قم بالطباعة أولا')"  @else onclick="change_status('{{ $item['id'] }}','{{ $item['model_type'] }}','{{ $next_type }}','send')" @endif>
                        {{ $title_send }}
                    </a>
                @endif
                @if ($type == 'design')
                    <a target="_blanc" class="btn btn-light  btn-sm rounded-pill "
                        href="{{ route('admin.playlists.print', ['id' => $item['id'], 'model_type' => $item['model_type']]) }}">
                        {{ trans('global.print') }}
                    </a>
                @endif
                <a class="btn btn-success btn-sm rounded-pill text-white"
                    onclick="show_details('{{ $item['id'] }}','{{ $item['model_type'] }}')"
                    title="{{ __('Order Details') }}">
                    أظهارالصور
                </a>
            </div>
        </div>
    </div>
@endforeach
