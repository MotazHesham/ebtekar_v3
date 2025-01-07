@extends('layouts.admin')
@section('styles')
    <style>
        .blur{
            color: transparent;
            text-shadow: 2px 3px 4px white; 
        }
    </style>
@endsection
@section('content')
    <div class="content">
        <div class="row">
            <div class="col-lg-12"> 
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif 
                <div class="row">
                    <div class="col-md-3">
                        <div class="card text-white bg-success">
                            <div class="card-body pb-0">
                                <div class="text-value blur" onmouseover="loadNum(this,'customer')">xxxx</div>
                                <div>{{ __('cruds.customer.title') }}</div>
                                <br />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-danger">
                            <div class="card-body pb-0">
                                <div class="text-value blur" onmouseover="loadNum(this,'product')">xxxx</div>
                                <div>{{ __('cruds.product.title') }}</div>
                                <br />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-info">
                            <div class="card-body pb-0">
                                <div class="text-value blur" onmouseover="loadNum(this,'order')">xxxx</div>
                                <div>{{ __('cruds.order.title') }}</div>
                                <br />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-white bg-warning">
                            <div class="card-body pb-0">
                                <div class="text-value blur" onmouseover="loadNum(this,'receiptSocial')">xxxx</div>
                                <div>{{ __('cruds.receiptSocial.title') }}</div>
                                <br />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <div style="display: flex;justify-content:space-between" id="first-chart">
                                    <h5>{!! __('cruds.order.extra.chart_by_order_type') !!}</h5>
                                    <button class="btn btn-info" onclick="loadChart(this,'first-chart')">Load Chart</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <div style="display: flex;justify-content:space-between" id="second-chart">
                                    <h5>{!! __('cruds.receiptSocial.extra.chart_by_month') !!}</h5>
                                    <button class="btn btn-info" onclick="loadChart(this,'second-chart')">Load Chart</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <div style="display: flex;justify-content:space-between" id="third-chart">
                                    <h5>{!! __('cruds.receiptSocial.extra.chart_by_website') !!}</h5>
                                    <button class="btn btn-info" onclick="loadChart(this,'third-chart')">Load Chart</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Widget - latest entries --}}
                    <div class="{{ $settings10['column_class'] }}" style="overflow-x: auto;">
                        <div class="card">
                            <div class="card-header">{{ $settings10['chart_title'] }}</div>
                            <div class="card-body">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            @foreach ($settings10['fields'] as $key => $value)
                                                <th>
                                                    {{ trans(sprintf('cruds.%s.fields.%s', $settings10['translation_key'] ?? 'pleaseUpdateWidget', $key)) }}
                                                </th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($settings10['data'] as $entry)
                                            <tr>
                                                @foreach ($settings10['fields'] as $key => $value)
                                                    <td>
                                                        @if ($value === '')
                                                            {{ $entry->{$key} }}
                                                        @elseif(is_iterable($entry->{$key}))
                                                            @foreach ($entry->{$key} as $subEentry)
                                                                <span
                                                                    class="label label-info">{{ $subEentry->{$value} }}</span>
                                                            @endforeach
                                                        @else
                                                            {{ data_get($entry, $key . '.' . $value) }}
                                                        @endif
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="{{ count($settings10['fields']) }}">
                                                    {{ __('No entries found') }}</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div> 
                    </div> 
                    <div class="col-md-6"> 
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <div class="card text-white bg-success">
                                    <div class="card-body pb-0">
                                        <div class="text-value blur" onmouseover="loadNum(this,'product')">xxxx</div>
                                        <div>{{ __('cruds.product.extra.published_products')}}</div>
                                        <br />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card text-white bg-primary">
                                    <div class="card-body pb-0">
                                        <div class="text-value blur" onmouseover="loadNum(this,'category')">xxxx</div>
                                        <div>{{ __('cruds.category.title') }}</div>
                                        <br />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card text-white bg-danger">
                                    <div class="card-body pb-0">
                                        <div class="text-value blur" onmouseover="loadNum(this,'subCategory')">xxxx</div>
                                        <div>{{ __('cruds.subCategory.title') }}</div>
                                        <br />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card text-white bg-dark">
                                    <div class="card-body pb-0">
                                        <div class="text-value blur" onmouseover="loadNum(this,'subSubCategory')">xxxx</div>
                                        <div>{{ __('cruds.subSubCategory.title') }}</div>
                                        <br />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    @parent
    <script>
        function loadNum(element,type){
            // Check if the function has already been executed for this element
            if ($(element).data('loaded')) {
                return; // Exit the function if already loaded
            } 

            // Mark this element as loaded
            $(element).data('loaded', true);

            $(element).removeClass('blur');
            $(element).html('<div class="spinner-border spinner-border-sm text-white" role="status"></div>');
            $.ajax({
                url: '{{ route('admin.widgets.load_num') }}',
                type: 'Post',
                data: {
                    _token: '{{ csrf_token() }}',
                    type: type
                },
                success: function(data){
                    $(element).html(data); 
                } 
            }); 
        }
        
        function loadChart(element,type){  
            $(element).html('<div class="spinner-border spinner-border-sm text-dark" role="status"></div>');

            $.ajax({
                url: '{{ route('admin.widgets.load_chart') }}',
                type: 'Post',
                data: {
                    _token: '{{ csrf_token() }}',
                    type: type
                },
                success: function(data){ 
                    $(element).remove();
                    $('#' + type).after(data); 
                } 
            }); 
        }
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script> 
@endsection
