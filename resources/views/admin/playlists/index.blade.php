@extends('layouts.admin')
@section('styles')
    <style>
        td {
            font-size: 15px
        }

        .order-card {
            transition: all 0.3s;
            -webkit-transition: all 0.3s;
            overflow: hidden;
            max-width: 700px;
            padding: 15px;
            border-radius: 14px;
            box-shadow: 2px 1px 19px #48484863;
            direction: ltr
        }

        .order-card-left-side {
            background-image: linear-gradient(#348282, #3580b3);
            border-radius: 14px;
            padding: 15px;
        }

        .order-card:hover {
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            transform: translateY(-5px);
        }

        .order-card-actions {
            position: absolute;
            right: 0;
            top: -30px;
            visibility: hidden;
            transition: all 0.3s;
            -webkit-transition: all 0.3s;
        }

        .container-scrollable {
            font-weight: bolder;
            background: white;
            height: 100px;
            overflow-y: scroll;
            box-shadow: 0px 4px 5px #bd808063;
            border-radius: 7px;
        }

        .container-scrollable::-webkit-scrollbar {
            width: 5px;
        }

        .container-scrollable::-webkit-scrollbar-track {
            background: rgba(184, 34, 34, 0);
            border-radius: 10px;
        }

        .container-scrollable::-webkit-scrollbar-thumb {
            border-radius: 10px;
            background: rgba(159, 53, 53, 0.8);
        }

        .container-scrollable::-webkit-scrollbar-thumb:hover {
            background: black;
        }

        .date_selected {
            background-color: #9b4c4c;
            color: white;
        }

        .date_selected:hover {
            background-color: #9b4c4c !important;
            color: white !important;
        }

        .date_selected:focus {
            background-color: #9b4c4c !important;
            color: white !important;
        }

        .date_selected:active {
            background-color: #9b4c4c !important;
            color: white !important;
        }
    </style>
@endsection
@section('content')


    @php
        $title = '';
        $title_send = '';
        $back_type = '';
        $next_type = '';
        if ($type == 'design') {
            $title = 'الديزانر';
            $title_send = 'أرسال لقائمة التصنيع';
            $next_type = 'manufacturing';
            $title_back = 'أرجاع للشركة';
            $back_type = 'pending';
        } elseif ($type == 'manufacturing') {
            $title = 'التصنيع';
            $title_send = 'أرسال لقائمة التجهيز';
            $next_type = 'prepare';
            $title_back = 'أرجاع لقائمة الديزانر';
            $back_type = 'design';
        } elseif ($type == 'prepare') {
            $title = 'التجهيز';
            $title_send = 'أرسال للتجهيز للشحن';
            $next_type = 'shipment';
            $title_back = 'أرجاع لقائمة التصنيع';
            $back_type = 'manufacturing';
        } elseif ($type == 'shipment') {
            $title = 'التجهيز';
            $title_send = 'أرسال للشحن';
            $next_type = 'finish';
            $title_back = 'أرجاع لقائمة التجهيز';
            $back_type = 'prepare';
        }
        
    @endphp

    @include('admin.playlists.search')

    <div class="card">
        <div class="card-header">
            {{ trans('global.list') }} {{ trans('cruds.playlist.menu.' . $type) }}
        </div>

        <div class="card-body">

            @if ($view == 'by_date')
                <div class="row">
                    @foreach ($dates as $key0 => $playlists)
                        <div class="col-md-2">
                            <div class="card-header" id="heading{{ $key0 }}">
                                <h2 class="mb-0">
                                    <button class="btn btn-dark playlist-dates" type="button" data-toggle="collapse"
                                        data-target="#collapse{{ $key0 }}" aria-expanded="false"
                                        aria-controls="collapse{{ $key0 }}">
                                        {{ $key0 }} - [ {{ count($playlists) }} order]
                                    </button>
                                </h2>
                            </div>
                        </div>
                    @endforeach
                </div>


                <div>
                    {{ $dates->appends(request()->input())->links() }}
                </div>
            @endif

            @if ($view != 'by_date')
                <div>
                    {{ $playlists->appends(request()->input())->links() }}
                </div>
            @endif
            <div class="row">
                @if ($view == 'by_date')
                    @foreach ($dates as $key0 => $playlists)
                        <div class="card">
                            <div id="collapse{{ $key0 }}" class="collapse"
                                aria-labelledby="heading{{ $key0 }}">
                                <div class="card-body">
                                    <div class="row">
                                        <h5>{{ $key0 }}</h5>
                                        <hr>
                                        @include('admin.playlists.partials')
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    @include('admin.playlists.partials')
                @endif
            </div>
            @if ($view != 'by_date')
                <div>
                    {{ $playlists->appends(request()->input())->links() }}
                </div>
            @endif

        </div>
    </div>

@endsection
@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('.order-card').hover(function() {
                var id = $(this).data('id');
                $(this).css('background', '#00000032');
                $('#order-card-actions-' + id).css('top', '10px');
                $('#order-card-actions-' + id).css('visibility', 'visible');
            });
            $('.order-card').mouseleave(function() {
                var id = $(this).data('id');
                $(this).css('background', '#fff');
                $('#order-card-actions-' + id).css('top', '-30px');
                $('#order-card-actions-' + id).css('visibility', 'hidden');
            });
        });

        function sort_playlist(el) {
            $('#sort_playlist').submit();
        }

        function check_printable(id,model_type){
            $.post('{{ route('admin.playlists.check_printable') }}', {
                _token: '{{ csrf_token() }}',
                id: id,
                model_type: model_type, 
            }, function(data) {
                if (data == 1) { 
                    showAlert('error', 'تم الطباعة من قبل');
                }
            }); 
        }


        function change_status(id, model_type, type, condition) {
            $.post('{{ route('admin.playlists.update_playlist_status') }}', {
                _token: '{{ csrf_token() }}',
                id: id,
                model_type: model_type,
                status: type,
                condition: condition
            }, function(data) {
                if (data == 1) {
                    location.reload();
                    showAlert('success', 'تم الأرسال');
                }else{
                    showAlert('error', 'قم بالطباعة أولا');
                }
            });
        }


        $('.playlist-dates').on('click', function() {
            $(this).toggleClass('date_selected');
        })
    </script>
@endsection
