@extends('layouts.admin')
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
                    <div class="{{ $settings1['column_class'] }}">
                        <div class="card text-white bg-success">
                            <div class="card-body pb-0">
                                <div class="text-value">{{ number_format($settings1['total_number']) }}</div>
                                <div>{{ $settings1['chart_title'] }}</div>
                                <br />
                            </div>
                        </div>
                    </div>
                    <div class="{{ $settings2['column_class'] }}">
                        <div class="card text-white bg-danger">
                            <div class="card-body pb-0">
                                <div class="text-value">{{ number_format($settings2['total_number']) }}</div>
                                <div>{{ $settings2['chart_title'] }}</div>
                                <br />
                            </div>
                        </div>
                    </div>
                    <div class="{{ $settings3['column_class'] }}">
                        <div class="card text-white bg-info">
                            <div class="card-body pb-0">
                                <div class="text-value">{{ number_format($settings3['total_number']) }}</div>
                                <div>{{ $settings3['chart_title'] }}</div>
                                <br />
                            </div>
                        </div>
                    </div>
                    <div class="{{ $settings4['column_class'] }}">
                        <div class="card text-white bg-warning">
                            <div class="card-body pb-0">
                                <div class="text-value">{{ number_format($settings4['total_number']) }}</div>
                                <div>{{ $settings4['chart_title'] }}</div>
                                <br />
                            </div>
                        </div>
                    </div>
                    <div class="{{ $chart6->options['column_class'] }}">
                        <div class="card">
                            <div class="card-body">
                                <h5>{!! $chart6->options['chart_title'] !!}</h5>
                                {!! $chart6->renderHtml() !!}
                            </div>
                        </div>
                    </div>
                    <div class="{{ $chart5->options['column_class'] }}">
                        <div class="card">
                            <div class="card-body">
                                <h5>{!! $chart5->options['chart_title'] !!}</h5>
                                {!! $chart5->renderHtml() !!}
                            </div>
                        </div>
                    </div>
                    <div class="{{ $chart05->options['column_class'] }}">
                        <div class="card">
                            <div class="card-body">
                                <h5>{!! $chart05->options['chart_title'] !!}</h5>
                                {!! $chart05->renderHtml() !!} 
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
                        <div class="row">
                            @foreach(\Cache::get('currency_rates') as $symbole => $value)
                                <div class="col-md-3">
                                    <div class="card text-white bg-primary">
                                        <div class="card-body pb-0">
                                            <div class="text-value">{{ $symbole }}</div>
                                            <div>{{ $value }}</div>
                                            <br />
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="row mt-2">
                            <div class="{{ $settings7['column_class'] }}">
                                <div class="card text-white bg-success">
                                    <div class="card-body pb-0">
                                        <div class="text-value">{{ number_format($settings7['total_number']) }}</div>
                                        <div>{{ $settings7['chart_title'] }}</div>
                                        <br />
                                    </div>
                                </div>
                            </div>
                            <div class="{{ $settings07['column_class'] }}">
                                <div class="card text-white bg-primary">
                                    <div class="card-body pb-0">
                                        <div class="text-value">{{ number_format($settings07['total_number']) }}</div>
                                        <div>{{ $settings07['chart_title'] }}</div>
                                        <br />
                                    </div>
                                </div>
                            </div>
                            <div class="{{ $settings8['column_class'] }}">
                                <div class="card text-white bg-danger">
                                    <div class="card-body pb-0">
                                        <div class="text-value">{{ number_format($settings8['total_number']) }}</div>
                                        <div>{{ $settings8['chart_title'] }}</div>
                                        <br />
                                    </div>
                                </div>
                            </div>
                            <div class="{{ $settings9['column_class'] }}">
                                <div class="card text-white bg-dark">
                                    <div class="card-body pb-0">
                                        <div class="text-value">{{ number_format($settings9['total_number']) }}</div>
                                        <div>{{ $settings9['chart_title'] }}</div>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>{!! $chart5->renderJs() !!}{!! $chart6->renderJs() !!} {!! $chart05->renderJs() !!}
@endsection
