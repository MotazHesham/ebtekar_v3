<!DOCTYPE html>
<html @if (app()->getLocale() == 'ar') dir="rtl" @endif>

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        $site_settings = get_site_setting();
    @endphp
    <link name="favicon" type="image/x-icon" href="{{ asset($site_settings->logo ? $site_settings->logo->getUrl() : '') }}"
        rel="shortcut icon" />

    <title>{{ $site_settings->site_name }}</title>
    <link rel="stylesheet" href="{{ asset('dashboard_offline/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard_offline/css/font-awesome.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard_offline/css/all.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard_offline/css/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard_offline/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard_offline/css/buttons.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard_offline/css/select.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard_offline/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard_offline/css/bootstrap-datetimepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard_offline/css/coreui.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard_offline/css/font-awesome.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard_offline/css/dropzone.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard_offline/css/perfect-scrollbar.min.css') }}">
    <link href="{{ asset('plugins/bootstrap-tagsinput/bootstrap-tagsinput.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/simplebar.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/style.css') }}" rel="stylesheet" />
    @if (app()->getLocale() == 'ar')
        <style>
            .c-sidebar-nav .c-sidebar-nav-dropdown-items {
                padding-right: 5%;
            }
        </style>
    @else
        <style>
            .c-sidebar-nav .c-sidebar-nav-dropdown-items {
                padding-left: 5%;
            }
        </style>
    @endif
    <style>
        .order_num_ertgal {
            background: #5DADE2
        }

        .order_num_figures {
            background: #b96e14
        }

        .order_num_shirti {
            background: #F1948A
        }

        .order_num_ebtekar {
            background: #FBAC00
        }
        
        .order_num_martobia {
            background: #6e1554
        }

        .order_num_a1_digital {
            background: #20b127
        }

        .order_num_ein {
            background: #b1204c
        }

        .playlist_status {
            cursor: pointer;
            font-size: 16px
        }

        .order_num {
            cursor: pointer;
            font-size: 16px
        }

        .total_cost {
            font-size: 18px;
            padding: 18px;
        }

        .isset {
            box-shadow: 1px 2px 3px deepskyblue
        }

        .help-block {
            font-size: 12px;
            color: grey;
        }

        .quickly {
            background: linear-gradient(283deg, #ef8181 -1%, #ffffff 50%);
            border: 1px red double;
        }

        .quickly_return {
            background: linear-gradient(283deg, #8d8df3 0%, #ffffff 57%);
            border: 1px #b2b98d double;
        }

        .returned {
            background: linear-gradient(283deg, #ecf38d 0%, #ffffff 57%);
            border: 1px #b2b98d double;
        }

        .done {
            background: linear-gradient(276deg, #091e0461 0%, #ffffff 62%);
            ;
            border: 1px black double;
        }

        .no_answer {
            background: linear-gradient(283deg, #ecf38d 0%, #ffffff 57%);
            border: 1px #b2b98d double;
        }
    </style>
    @yield('styles')
</head>


<body class="c-app">


    {{-- for printing purpose --}}
    <iframe name="print-frame" id="print-frame" frameborder="0" style="width: 100%; position: absolute; z-index: -1; top: -14%;"></iframe>
    @include('partials.menu')

    <div class="c-wrapper">

        @include('partials.header')

        <div class="c-body">
            <main class="c-main">


                <div class="container-fluid">
                    @if (session('message'))
                        <div class="row mb-2">
                            <div class="col-lg-12">
                                <div class="alert alert-success" role="alert">{{ session('message') }}</div>
                            </div>
                        </div>
                    @endif
                    @if ($errors->count() > 0)
                        <div class="alert alert-danger">
                            <ul class="list-unstyled">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @yield('content')

                </div>


            </main>
            <form id="logoutform" action="{{ route('logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="AjaxModal" tabindex="-1" data-backdrop="static" data-keyboard="false" aria-labelledby="AjaxModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            {{-- ajax call --}}
        </div>
    </div>

    <!-- Modal2 -->
    <div class="modal fade" id="AjaxModal2" tabindex="-1" data-backdrop="static" data-keyboard="false" aria-labelledby="AjaxModal2Label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            {{-- ajax call --}}
        </div>
    </div>

    <!-- Modal Employee -->
    <div class="modal fade" id="employeeModal" tabindex="-1" data-backdrop="static" data-keyboard="false" aria-labelledby="employeeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered ">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="AjaxModalLabel">Write the Password Of Employee Access </h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.employees.access')}}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <input type="password" class="form-control" name="password" placeholder="Password">
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-danger" name="logout">Logout</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('sweetalert::alert')



    <script src="{{ asset('dashboard_offline/js/jquery.min.js') }}"></script>
    <script src="{{ asset('dashboard_offline/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('dashboard_offline/js/popper.min.js') }}"></script>
    <script src="{{ asset('dashboard_offline/js/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('dashboard_offline/js/coreui.min.js') }}"></script>
    <script src="{{ asset('dashboard_offline/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('dashboard_offline/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('dashboard_offline/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('dashboard_offline/js/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('dashboard_offline/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('dashboard_offline/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('dashboard_offline/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('dashboard_offline/js/pdfmake.min.js') }}"></script>
    <script src="{{ asset('dashboard_offline/js/vfs_fonts.js') }}"></script>
    <script src="{{ asset('dashboard_offline/js/jszip.min.js') }}"></script>
    <script src="{{ asset('dashboard_offline/js/dataTables.select.min.js') }}"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/16.0.0/classic/ckeditor.js"></script>
    <script src="{{ asset('dashboard_offline/js/moment.min.js') }}"></script>
    <script src="{{ asset('dashboard_offline/js/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ asset('dashboard_offline/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('dashboard_offline/js/dropzone.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap-tagsinput/bootstrap-tagsinput.js') }}"></script>
    <script src="{{ asset('js/simplebar.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.all.min.js"></script>


    <script src="https://www.gstatic.com/firebasejs/7.23.0/firebase.js"></script>
    <script>
        @if (!app()->isLocal())
            @if (!Cookie::has('device_token'))
                $(document).ready(function() {
                    initFirebaseMessagingRegistration();
                });
            @endif

            var firebaseConfig = {
                apiKey: "AIzaSyAk6X115oPiXKLSsqQKOY_laEJdL88T_ms",
                authDomain: "ebtekarstore.firebaseapp.com",
                projectId: "ebtekarstore",
                storageBucket: "ebtekarstore.appspot.com",
                messagingSenderId: "211443767149",
                appId: "1:211443767149:web:88eb69397da12e7e2b2c64"
            };
            firebase.initializeApp(firebaseConfig);
            const messaging = firebase.messaging();

            function initFirebaseMessagingRegistration() {
                messaging
                    .requestPermission()
                    .then(function() {
                        return messaging.getToken()
                    })
                    .then(function(token) {
                        console.log(token);
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        $.ajax({
                            url: '{{ route('save-token') }}',
                            type: 'POST',
                            data: {
                                token: token
                            },
                            dataType: 'JSON',
                            success: function(response) {
                                console.log('Token saved successfully.');
                            },
                            error: function(err) {
                                console.log('User Chat Token Error' + err);
                            },
                        });
                    }).catch(function(err) {
                        console.log('User Chat Token Error' + err);
                    });
            }
            messaging.onMessage(function(payload) {
                const noteTitle = payload.notification.title;
                const noteOptions = {
                    body: payload.notification.body,
                    icon: payload.notification.icon,
                };
                new Notification(noteTitle, noteOptions);
            });
        @endif
    </script>

    @if (!isset($enable_multiple_form_submit))
        <script>
            //perevent submittig multiple times
            $("body").on("submit", "form", function() {
                $(this).submit(function() {
                    return false;
                });
                return true;
            });
        </script>
    @endif

    <script>
        $(document).ready(function() {
            // Function to convert Arabic digits to English digits
            function convertDigitsToEnglish(input) {
                var arabicDigits = ["٠", "١", "٢", "٣", "٤", "٥", "٦", "٧", "٨", "٩"];
                var englishDigits = ["0", "1", "2", "3", "4", "5", "6", "7", "8", "9"];

                for (var i = 0; i < arabicDigits.length; i++) {
                    var regex = new RegExp(arabicDigits[i], "g");
                    input = input.replace(regex, englishDigits[i]);
                }

                return input;
            }

            // Handle keyup event on the input field
            $('input[name=phone_number]').on('keyup', function() {
                var inputValue = $(this).val();
                var englishValue = convertDigitsToEnglish(inputValue);
                $(this).val(englishValue);
            });
            $('input[name=phone_number_2]').on('keyup', function() {
                var inputValue = $(this).val();
                var englishValue = convertDigitsToEnglish(inputValue);
                $(this).val(englishValue);
            });
            $('input[name=phone]').on('keyup', function() {
                var inputValue = $(this).val();
                var englishValue = convertDigitsToEnglish(inputValue);
                $(this).val(englishValue);
            });
        });



        function playlistCounters(element) {
            
            if ($(element).data('loaded')) {
                return; // Exit the function if already loaded
            } 

            // Mark this element as loaded
            $(element).data('loaded', true);

            $('.playlist-counters').each(function() {
                var $this = $(this); 

                // Replace the content with a spinner
                $this.html('<div class="spinner-border spinner-border-sm text-dark" role="status"></div>');

            });

            $.post('{{ route('admin.playlists.getCounters') }}', {
                _token: '{{ csrf_token() }}', 
            }, function(data) {
                $('#playlist-counter-design').html(data['design']);
                $('#playlist-counter-manufacturing').html(data['manufacturing']);
                $('#playlist-counter-prepare').html(data['prepare']);
                $('#playlist-counter-shipment').html(data['shipment']); 
                $('#playlist-counter-total').html(data['total']); 
            });
        }

        function show_details(id, model_type) {
            $.post('{{ route('admin.playlists.show_details') }}', {
                _token: '{{ csrf_token() }}',
                id: id,
                model_type: model_type
            }, function(data) {
                $('#AjaxModal .modal-dialog').html(null);
                $('#AjaxModal').modal('show');
                $('#AjaxModal .modal-dialog').html(data);
            });
        }
        function show_history(id, model_type) {
            $.post('{{ route('admin.playlists.history') }}', {
                _token: '{{ csrf_token() }}',
                id: id,
                model_type: model_type
            }, function(data) {
                $('#AjaxModal .modal-dialog').html(null);
                $('#AjaxModal').modal('show');
                $('#AjaxModal .modal-dialog').html(data);
            });
        }
        function get_categories_by_website(call_others = null) {
            var website_setting_id = $('#website_setting_id').val();
            $.post('{{ route('admin.website-settings.get_categories_by_website') }}', {
                _token: '{{ csrf_token() }}',
                website_setting_id: website_setting_id
            }, function(data) {
                $('#category_id').html(null);

                for (var i = 0; i < data.length; i++) {
                    $('#category_id').append($('<option>', {
                        value: data[i].id,
                        text: data[i].name
                    }));
                }
                if (call_others) {
                    get_sub_categories_by_category();
                    get_sub_sub_categories_by_category();
                }
            });
        }

        function get_sub_categories_by_website() {
            var website_setting_id = $('#website_setting_id').val();
            $.post('{{ route('admin.website-settings.get_sub_categories_by_website') }}', {
                _token: '{{ csrf_token() }}',
                website_setting_id: website_setting_id
            }, function(data) {
                $('#sub_category_id').html(null);

                for (var i = 0; i < data.length; i++) {
                    $('#sub_category_id').append($('<option>', {
                        value: data[i].id,
                        text: data[i].name
                    }));
                }
            });
        }

        function show_logs(model, subject_id, crud_name) {
            $.post('{{ route('admin.receipts_logs') }}', {
                _token: '{{ csrf_token() }}',
                model: model,
                subject_id: subject_id,
                crud_name: crud_name
            }, function(data) {
                $('#AjaxModal .modal-dialog').html(null);
                $('#AjaxModal').modal('show');
                $('#AjaxModal .modal-dialog').html(data);
            });
        }

        function playlist_users(id, model_type) {
            $.post('{{ route('admin.playlists.playlist_users') }}', {
                _token: '{{ csrf_token() }}',
                id: id,
                model_type: model_type
            }, function(data) {
                $('#AjaxModal .modal-dialog').html(null);
                $('#AjaxModal').modal('show');
                $('#AjaxModal .modal-dialog').html(data);
            });
        }

        function showAlert(type, title, message,seconds = 3000) {
            swal({
                title: title,
                text: message,
                type: type,
                showConfirmButton: 'Okay',
                timer: seconds
            });
        }

        function deleteConfirmation(route, div = null, partials = false) {
            swal({
                title: "{{ __('flash.delete_') }}",
                text: "{{ __('flash.sure_') }}",
                type: "warning",
                showCancelButton: !0,
                confirmButtonText: "{{ __('flash.yes_') }}",
                cancelButtonText: "{{ __('flash.no_') }}",
                reverseButtons: !0
            }).then(function(e) {

                if (e.value === true) {

                    $.ajax({
                        type: 'DELETE',
                        url: route,
                        data: {
                            _token: '{{ csrf_token() }}',
                            partials: partials
                        },
                        success: function(results) {
                            location.reload();
                        }
                    });

                } else {
                    e.dismiss;
                }

            }, function(dismiss) {
                return false;
            })
        }
    </script>
    <script>
        function searchByPhone(e) {
            // sanitize: remove spaces and leading country code +20 or 0020
            var raw = e.value || '';
            var phone = raw.replace(/\s+/g, '').replace(/^\+2/, '');
            // reflect sanitized value back to the input
            e.value = phone;
            $.post('{{ route('admin.search_by_phone') }}', {
                _token: '{{ csrf_token() }}',
                phone: phone
            }, function(data) {
                $('#phoneModal #table-receipts').html(data);
            });
        }

        $(function() {
            let copyButtonTrans = '{{ __('global.datatables.copy') }}'
            let csvButtonTrans = '{{ __('global.datatables.csv') }}'
            let excelButtonTrans = '{{ __('global.datatables.excel') }}'
            let pdfButtonTrans = '{{ __('global.datatables.pdf') }}'
            let printButtonTrans = '{{ __('global.datatables.print') }}'
            let colvisButtonTrans = '{{ __('global.datatables.colvis') }}'
            let selectAllButtonTrans = '{{ __('global.select_all') }}'
            let selectNoneButtonTrans = '{{ __('global.deselect_all') }}'

            let languages = {
                'en': 'https://cdn.datatables.net/plug-ins/1.10.19/i18n/English.json'
            };

            $.extend(true, $.fn.dataTable.Buttons.defaults.dom.button, {
                className: 'btn'
            })
            $.extend(true, $.fn.dataTable.defaults, {
                language: {
                    url: languages['{{ app()->getLocale() }}']
                },
                columnDefs: [{
                    orderable: false,
                    className: 'select-checkbox',
                    targets: 0
                }, {
                    orderable: false,
                    searchable: false,
                    targets: -1
                }],
                select: {
                    style: 'multi+shift',
                    selector: 'td:first-child'
                },
                order: [],
                scrollX: true,
                pageLength: 100,
                dom: 'lBfrtip<"actions">',
                buttons: [{
                        extend: 'selectAll',
                        className: 'btn-primary',
                        text: selectAllButtonTrans,
                        exportOptions: {
                            columns: ':visible'
                        },
                        action: function(e, dt) {
                            e.preventDefault()
                            dt.rows().deselect();
                            dt.rows({
                                search: 'applied'
                            }).select();
                        }
                    },
                    {
                        extend: 'selectNone',
                        className: 'btn-primary',
                        text: selectNoneButtonTrans,
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'copy',
                        className: 'btn-light',
                        text: copyButtonTrans,
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'csv',
                        className: 'btn-light',
                        text: csvButtonTrans,
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'excel',
                        className: 'btn-light',
                        text: excelButtonTrans,
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'pdf',
                        className: 'btn-light',
                        text: pdfButtonTrans,
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'print',
                        className: 'btn-light',
                        text: printButtonTrans,
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'colvis',
                        className: 'btn-light',
                        text: colvisButtonTrans,
                        exportOptions: {
                            columns: ':visible'
                        }
                    }
                ]
            });

            $.fn.dataTable.ext.classes.sPageButton = '';
        });
    </script>
    <script>
        $(document).ready(function() {
            $(".notifications-menu").on('click', function() {
                if (!$(this).hasClass('open')) {
                    $('.notifications-menu .label-warning').hide();
                    $.get('/admin/user-alerts/read');
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('.searchable-field').select2({
                minimumInputLength: 3,
                ajax: {
                    url: '{{ route('admin.globalSearch') }}',
                    dataType: 'json',
                    type: 'GET',
                    delay: 200,
                    data: function(term) {
                        return {
                            search: term
                        };
                    },
                    results: function(data) {
                        return {
                            data
                        };
                    }
                },
                escapeMarkup: function(markup) {
                    return markup;
                },
                templateResult: formatItem,
                templateSelection: formatItemSelection,
                placeholder: '{{ __('global.search') }}...',
                language: {
                    inputTooShort: function(args) {
                        var remainingChars = args.minimum - args.input.length;
                        var translation = '{{ __('global.search_input_too_short') }}';

                        return translation.replace(':count', remainingChars);
                    },
                    errorLoading: function() {
                        return '{{ __('global.results_could_not_be_loaded') }}';
                    },
                    searching: function() {
                        return '{{ __('global.searching') }}';
                    },
                    noResults: function() {
                        return '{{ __('global.no_results') }}';
                    },
                }

            });

            function formatItem(item) {
                if (item.loading) {
                    return '{{ __('global.searching') }}...';
                }
                var markup = "<div class='searchable-link' href='" + item.url + "'>";
                markup += "<div class='searchable-title'>" + item.model + "</div>";
                $.each(item.fields, function(key, field) {
                    markup += "<div class='searchable-fields'>" + item.fields_formated[field] + " : " +
                        item[field] + "</div>";
                });
                markup += "</div>";

                return markup;
            }

            function formatItemSelection(item) {
                if (!item.model) {
                    return '{{ __('global.search') }}...';
                }
                return item.model;
            }
            $(document).delegate('.searchable-link', 'click', function() {
                var url = $(this).attr('href');
                window.location = url;
            });
        });
    </script>
    @yield('scripts')
</body>

</html>
