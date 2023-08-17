<!DOCTYPE html>
<html dir="rtl"> 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>My Magic Trick</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet" />
    <link href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/buttons/1.2.4/css/buttons.dataTables.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/select/1.3.0/css/select.dataTables.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
    <link href="https://unpkg.com/@coreui/coreui@3.2/dist/css/coreui.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/1.5.0/css/perfect-scrollbar.min.css" rel="stylesheet" /> 
    <style> 
        .ck-editor__editable,
        textarea {
            min-height: 150px;
        }
        
        .datatable {
            width: 100% !important;
        }
        
        table.dataTable tbody td.select-checkbox::before,
        table.dataTable tbody td.select-checkbox::after,
        table.dataTable tbody th.select-checkbox::before,
        table.dataTable tbody th.select-checkbox::after {
            top: 50%;
        }
        
        .dataTables_length,
        .dataTables_filter,
        .dt-buttons {
            margin-bottom: 0.333em;
            margin-top: .2rem;
        }
        
        .dataTables_filter {
            margin-right: .2rem;
        }
        
        .dt-buttons .btn {
            margin-left: 0.333em;
            border-radius: 0;
        }
        
        .table.datatable {
            box-sizing: border-box;
            border-collapse: collapse;
        }
        
        table.dataTable thead th {
            border-bottom: 2px solid #c8ced3;
        }
        
        .dataTables_wrapper.no-footer .dataTables_scrollBody {
            border-bottom: 1px solid #c8ced3;
        }
        
        .select2 {
            max-width: 100%;
            width: 100% !important;
        }
        
        .select2-selection__rendered {
            padding-bottom: 5px !important;
        }
        
        .has-error .invalid-feedback {
            display: block !important;
        }
        
        .btn-info,
        .badge-info {
            color: white;
        }
        
        table.dataTable thead .sorting,
        table.dataTable thead .sorting_asc,
        table.dataTable thead .sorting_desc {
            background-image: none;
        }
        
        .sidebar .nav-item {
            cursor: pointer;
        }
        
        .btn-default {
            color: #23282c;
            background-color: #f0f3f5;
            border-color: #f0f3f5;
        }
        
        .btn-default.focus,
        .btn-default:focus {
            box-shadow: 0 0 0 .2rem rgba(209, 213, 215, .5);
        }
        
        .btn-default:hover {
            color: #23282c;
            background-color: #d9e1e6;
            border-color: #d1dbe1;
        }
        
        .btn-group-xs > .btn,
        .btn-xs {
            padding: 1px 5px;
            font-size: 12px;
            line-height: 1.5;
            border-radius: 3px;
        }
        
        .searchable-title {
            font-weight: bold;
        }
        .searchable-fields {
            padding-left:5px;
        }
        .searchable-link {
            padding:0 5px 0 5px;
        }
        .searchable-link:hover   {
            cursor: pointer;
            background: #eaeaea;
        }
        .select2-results__option {
            padding-left: 0px;
            padding-right: 0px;
        }
        
        .form-group .required::after {
            content: " *";
            color: red;
        }
        
        .form-check.is-invalid ~ .invalid-feedback {
            display: block;
        }
        
        .c-sidebar-brand .c-sidebar-brand-full:hover {
            color: inherit;
        }
        
        .custom-select.form-control-sm {
            padding: 0.25rem 1.5rem;
        }

    </style>
</head>

<body class="c-app">  
    <div class="c-wrapper" style="    background-image: url(https://ebtekarstore.net/public/magic.jpg);
    background-size: cover;"> 
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
                    
                    <div class="row">
                        <div class="col-md-4">
                            <form class="mt-2 mb-2">
                                <button name="reset" type="submit" class="btn btn-danger btn-lg rounded-pill">Erase The Table  </button>
                            </form>
                            <div class="form-group">
                                <textarea class="form-control" name="theorder" id="theorder" cols="10" rows="10"></textarea>
                            </div>
                            <button class="btn btn-dark btn-block" onclick="getTheOrder()">submit</button>
                        </div>
                        <div class="col-md-8">
                            <div id="results"> 
                            </div>
                        </div>
                    </div> 

                </div>


            </main>
            <form id="logoutform" action="{{ route('logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>
        </div>
    </div> 

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/1.5.0/perfect-scrollbar.min.js"></script>
    <script src="https://unpkg.com/@coreui/coreui@3.2/dist/js/coreui.min.js"></script>
    <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <script src="//cdn.datatables.net/buttons/1.2.4/js/dataTables.buttons.min.js"></script>
    <script src="//cdn.datatables.net/buttons/1.2.4/js/buttons.flash.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.colVis.min.js"></script> 
    <script src="//cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/select/1.3.0/js/dataTables.select.min.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/16.0.0/classic/ckeditor.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.js"></script> 

    <script> 
        $(function() {
            let copyButtonTrans = 'Copy'
            let csvButtonTrans = 'csv'
            let excelButtonTrans = 'excel'
            let pdfButtonTrans = 'pdf'
            let printButtonTrans = 'print'
            let colvisButtonTrans = 'colvis' 

            $.extend(true, $.fn.dataTable.Buttons.defaults.dom.button, {
                className: 'btn'
            })
            $.extend(true, $.fn.dataTable.defaults, {
                order: [],
                scrollX: true,
                pageLength: 100,
                dom: 'lBfrtip<"actions">',
                buttons: [
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
                        className: 'btn-warning',
                        text: csvButtonTrans,
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'excel',
                        className: 'btn-success',
                        text: excelButtonTrans,
                        exportOptions: {
                            columns: ':visible'
                        }
                    }, 
                    {
                        extend: 'print',
                        className: 'btn-dark',
                        text: printButtonTrans,
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'colvis',
                        className: 'btn-danger',
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
        function getTheOrder() {

            // Get the textarea element
            const textarea = document.getElementById('theorder');

            // Get the textarea value
            const textareaValue = textarea.value;

            // Split the input into an array of lines
            const lines = textareaValue.split('\n');

            // Trim whitespace from each line and remove empty lines
            const filteredLines = lines.filter(line => line.trim() !== '');

            $.ajax({
                type: 'POST',
                url: "{{ route('magic_trick_store') }}",
                data: {
                    _token: '{{ csrf_token() }}',
                    name: filteredLines[0],
                    phone: filteredLines[1],
                    address: filteredLines[2],
                    model: filteredLines[3],
                    cost: filteredLines[4]
                },
                success: function(results) { 
                    $('#theorder').val(null);
                    $('#results').html(results);
                }
            });
        }
    </script>
</body>

</html>


    
    
    
