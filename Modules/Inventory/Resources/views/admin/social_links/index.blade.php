@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">ربط منتجات فواتير السوشيال</div>
        <div class="card-body">
            <form method="GET" class="form-row mb-3">
                <div class="col-md-4">
                    <input class="form-control" name="search" value="{{ request('search') }}" placeholder="اسم المنتج">
                </div>
                <div class="col-md-3">
                    <select class="form-control" name="website_setting_id">
                        <option value="">كل المواقع</option>
                        @foreach ($websites as $website)
                            <option value="{{ $website->id }}" @selected((string) request('website_setting_id') === (string) $website->id)>{{ $website->site_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="mt-2"><input type="checkbox" name="unlinked" value="1" @checked(request('unlinked'))> غير مربوط فقط</label>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary" type="submit">تصفية</button>
                </div>
            </form>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>المنتج</th>
                        <th>الموقع</th>
                        <th>كارت المخزون</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        @php $link = $links->get($product->id); @endphp
                        <tr>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->website->site_name ?? '' }}</td>
                            <td colspan="2">
                                @can('inventory_edit')
                                    <form method="POST" action="{{ route('admin.inventory.social-links.store') }}" class="form-inline">
                                        @csrf
                                        <input type="hidden" name="receipt_social_product_id" value="{{ $product->id }}">
                                        <select class="form-control searchable-field mr-2" name="inv_item_id" style="min-width: 260px;">
                                            <option value="">بدون ربط</option>
                                            @foreach ($items as $item)
                                                <option value="{{ $item->id }}" @selected($link && (int) $link->inv_item_id === (int) $item->id)>
                                                    {{ $item->name }} @if($item->sku) ({{ $item->sku }}) @endif
                                                </option>
                                            @endforeach
                                        </select>
                                        <button class="btn btn-sm btn-success" type="submit">حفظ</button>
                                    </form>
                                @else
                                    {{ $link->item->name ?? 'غير مربوط' }}
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4">لا توجد منتجات.</td></tr>
                    @endforelse
                </tbody>
            </table>
            {{ $products->links() }}
        </div>
    </div>
@endsection
