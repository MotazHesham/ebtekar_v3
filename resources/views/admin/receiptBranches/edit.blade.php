@extends('layouts.admin')
@section('content')

<div class="form-group">
    <a class="btn btn-dark" href="{{ route('admin.receipt-branches.index') }}">
        {{ __('global.back_to_list') }}
    </a>
</div>

<div class="card">
    <div class="card-header">
        {{ __('global.edit') }} {{ __('cruds.receiptBranch.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.receipt-branches.update", [$receiptBranch->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="row">
                <div class="form-group col-md-4">
                    <label for="date_of_receiving_order">{{ __('cruds.receiptBranch.fields.date_of_receiving_order') }}</label>
                    <input class="form-control date {{ $errors->has('date_of_receiving_order') ? 'is-invalid' : '' }}" type="text" name="date_of_receiving_order" id="date_of_receiving_order" value="{{ old('date_of_receiving_order', $receiptBranch->date_of_receiving_order) }}">
                    @if($errors->has('date_of_receiving_order'))
                        <div class="invalid-feedback">
                            {{ $errors->first('date_of_receiving_order') }}
                        </div>
                    @endif
                    <span class="help-block">{{ __('cruds.receiptBranch.fields.date_of_receiving_order_helper') }}</span>
                </div> 
                <div class="form-group col-md-4">
                    <label class="required" for="r_client_id">{{ __('cruds.receiptBranch.fields.r_client_id') }}</label>
                    <select class="form-control select2 {{ $errors->has('r_client_id') ? 'is-invalid' : '' }}" name="r_client_id" id="r_client_id" required onchange="branches()">
                        @foreach($rclients as $id => $entry)
                            <option value="{{ $id }}" {{ old('r_client_id',$receiptBranch->branch->r_client_id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('r_client_id'))
                        <div class="invalid-feedback">
                            {{ $errors->first('r_client_id') }}
                        </div>
                    @endif 
                </div>
                <div class="form-group col-md-4" > 
                    <label class="required" for="r_branch_id">{{ __('cruds.receiptBranch.fields.r_branch_id') }}</label>
                    <select class="form-control select2 {{ $errors->has('r_branch_id') ? 'is-invalid' : '' }}" name="r_branch_id" id="r_branch_id" required> 
                        <option value="{{ $receiptBranch->r_branch_id }}">{{ $receiptBranch->branch->name ?? '' }}</option>
                    </select>
                    @if($errors->has('r_branch_id'))
                        <div class="invalid-feedback">
                            {{ $errors->first('r_branch_id') }}
                        </div>
                    @endif 
                </div>
                <div class="form-group col-md-4">
                    <label for="deposit">{{ __('cruds.receiptBranch.fields.deposit') }}</label>
                    <input class="form-control {{ $errors->has('deposit') ? 'is-invalid' : '' }}" type="number" name="deposit" id="deposit" value="{{ old('deposit', $receiptBranch->deposit) }}" step="0.01" required>
                    @if($errors->has('deposit'))
                        <div class="invalid-feedback">
                            {{ $errors->first('deposit') }}
                        </div>
                    @endif
                    <span class="help-block">{{ __('cruds.receiptBranch.fields.deposit_helper') }}</span>
                </div>
                <div class="form-group col-md-4">
                    <label for="discount">{{ __('cruds.receiptBranch.fields.discount') }}</label>
                    <input class="form-control {{ $errors->has('discount') ? 'is-invalid' : '' }}" type="number" name="discount" id="discount" value="{{ old('discount', $receiptBranch->discount) }}" step="0.01">
                    @if($errors->has('discount'))
                        <div class="invalid-feedback">
                            {{ $errors->first('discount') }}
                        </div>
                    @endif
                    <span class="help-block">{{ __('cruds.receiptBranch.fields.discount_helper') }}</span>
                </div>
                <div class="form-group col-md-4">
                    <label for="note">{{ __('cruds.receiptBranch.fields.note') }}</label>
                    <textarea class="form-control {{ $errors->has('note') ? 'is-invalid' : '' }}" name="note" id="note">{{ old('note', $receiptBranch->note) }}</textarea>
                    @if($errors->has('note'))
                        <div class="invalid-feedback">
                            {{ $errors->first('note') }}
                        </div>
                    @endif
                    <span class="help-block">{{ __('cruds.receiptBranch.fields.note_helper') }}</span>
                </div>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ __('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection
@section('scripts')
    @parent 
    <script>
        function branches(){
            var id = $('#r_client_id').val();
            $.post('{{ route('admin.receipt-branches.branches') }}', {
                _token: '{{ csrf_token() }}',
                id: id, 
            }, function(data) { 
                $('#r_branch_id').html(data);
            });
        }
    </script>
@endsection