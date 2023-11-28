@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">

            </div>

            <div class="card-body text-center">

                <section class="container" id="cam-content"> 
                    <div class="row">
                        <div class="col-md-6">
                            <label for="">Scanned Code</label>
                            <input class="form-control " type="text" autofocus name="bar_code" id="bar_code" >
                        </div>
                        <div class="col-md-6"> 
                            <label for=""> </label>
                            <div @if($type != 'shipment') style="display:none" @endif>
                                <select class="form-control demo-select2" name="delivery_man_id" id="delivery_man_id"   >
                                    <option value="0">{{__('Select Delivery Man ...')}}</option>
                                    @foreach($delivery_mans as $user)
                                            <option value="{{$user->id}}">{{$user->email}}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="text-center">
            <h3>Scann Results</h3>
            <div  id="order_scanned"></div>
        </div>
    </div> 
</div>

@endsection

@section('scripts') 
<script type="text/javascript">
    $('#bar_code').on('change',function(){ 
        var bar_code = $(this).val();

        @if($type == 'shipment')
            if($('#delivery_man_id').val() == 0){
                alert('برجاء اختيار المندوب'); 
                return 0;
            }
        @endif

        $.post('{{ route('admin.bar_code_output') }}', {
            _token: '{{ csrf_token() }}',
            code: bar_code,
            type: '{{$type}}',
            delivery_man_id : $('#delivery_man_id').val()
        }, function(data) {
            console.log(data); 
            $('#order_scanned').prepend(data.message); 
            if (data.status == 1) {
                showAlert('success','تم الأرسال');
            } else {
                showAlert('error','لم يتم الأرسال');
            }
            $('#bar_code').val(null);
            $('#bar_code').focus();
        });
    })
</script>

@endsection
