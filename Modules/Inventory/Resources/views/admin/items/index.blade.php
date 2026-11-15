@extends('layouts.admin')
@section('content')
    <div class="row mb-2">
        <div class="col-lg-8">
            <form method="GET" class="form-inline">
                <input class="form-control mr-2" type="text" name="search" value="{{ request('search') }}" placeholder="اسم أو كود">
                <button class="btn btn-primary" type="submit">بحث</button>
            </form>
        </div>
        <div class="col-lg-4 text-left">
            @can('inventory_create')
                <a class="btn btn-success" href="{{ route('admin.inventory.items.create') }}">كارت صنف جديد</a>
            @endcan
        </div>
    </div>
    <div class="card">
        <div class="card-header">كروت المخزون</div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>الصنف</th>
                        <th>الكود</th>
                        <th>الرصيد</th>
                        <th>منتجات سوشيال</th>
                        <th>متاجر شوبيفاي</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->sku }}</td>
                            <td>{{ $item->balance->on_hand ?? 0 }}</td>
                            <td>{{ $item->social_links_count }}</td>
                            <td>{{ $item->channel_links_count }}</td>
                            <td><a href="{{ route('admin.inventory.items.show', $item) }}">فتح</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="7">لا توجد كروت بعد.</td></tr>
                    @endforelse
                </tbody>
            </table>
            {{ $items->links() }}
        </div>
    </div>
@endsection
