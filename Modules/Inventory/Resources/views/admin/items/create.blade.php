@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">كارت صنف جديد</div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.inventory.items.store') }}">
                @csrf
                <div class="form-group">
                    <label for="name">الاسم</label>
                    <input class="form-control" type="text" name="name" id="name" value="{{ old('name') }}" required>
                </div>
                <div class="form-group">
                    <label for="sku">كود داخلي</label>
                    <input class="form-control" type="text" name="sku" id="sku" value="{{ old('sku') }}">
                </div>
                <button class="btn btn-danger" type="submit">حفظ</button>
            </form>
        </div>
    </div>
@endsection
