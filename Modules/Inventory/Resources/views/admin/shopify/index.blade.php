@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">ربط متغيرات شوبيفاي</div>
        <div class="card-body">
            <p>نفس كارت المخزون يتبعت رقمه المطلق لكل متجر مربوط. الربط هنا يدوي، واقتراح الاسم للمراجعة فقط.</p>
            @if ($error)
                <div class="alert alert-danger">{{ $error }}</div>
            @endif
            <form method="GET" class="form-inline mb-3">
                <input type="hidden" name="load" value="1">
                <select class="form-control mr-2" name="website_setting_id">
                    @foreach ($websites as $row)
                        <option value="{{ $row->id }}" @selected($website && (int) $website->id === (int) $row->id)>{{ $row->site_name }}</option>
                    @endforeach
                </select>
                <button class="btn btn-primary" type="submit">قراءة المتغيرات</button>
            </form>

            @if ($website)
                <form method="POST" action="{{ route('admin.inventory.shopify.location') }}" class="form-inline mb-4">
                    @csrf
                    <input type="hidden" name="website_setting_id" value="{{ $website->id }}">
                    <label class="mr-2">Location ID الحالي: {{ $website->shopify_location_id ?: 'غير محدد' }}</label>
                    <select class="form-control mr-2" name="shopify_location_id" required>
                        <option value="">اختر موقع التحديث</option>
                        @foreach ($locations as $location)
                            <option value="{{ $location['id'] }}" @selected((string) $website->shopify_location_id === (string) $location['id'])>
                                {{ $location['name'] }} ({{ $location['id'] }})
                            </option>
                        @endforeach
                    </select>
                    @if ($locations !== [])
                        <button class="btn btn-secondary" type="submit">حفظ الموقع</button>
                    @else
                        <span class="text-muted">اقرأ المتغيرات أولاً عشان تظهر المواقع.</span>
                    @endif
                </form>
            @endif

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>المنتج</th>
                        <th>المتغير</th>
                        <th>SKU</th>
                        <th>الربط</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($variants as $variant)
                        @php
                            $link = $existing->get($variant['variant_id']);
                            $selected = $link->inv_item_id ?? ($suggestions[$variant['variant_id']] ?? null);
                        @endphp
                        <tr>
                            <td>{{ $variant['product_title'] }}</td>
                            <td>{{ $variant['variant_title'] }}</td>
                            <td>{{ $variant['sku'] }}</td>
                            <td>
                                <form method="POST" action="{{ route('admin.inventory.shopify.link') }}" class="form-inline">
                                    @csrf
                                    <input type="hidden" name="website_setting_id" value="{{ $website->id }}">
                                    <input type="hidden" name="shopify_variant_id" value="{{ $variant['variant_id'] }}">
                                    <input type="hidden" name="shopify_inventory_item_id" value="{{ $variant['inventory_item_id'] }}">
                                    <input type="hidden" name="shopify_location_id" value="{{ $website->shopify_location_id }}">
                                    <input type="hidden" name="product_title" value="{{ $variant['product_title'] }}">
                                    <input type="hidden" name="variant_title" value="{{ $variant['variant_title'] }}">
                                    <select class="form-control searchable-field mr-2" name="inv_item_id" style="min-width: 240px;">
                                        <option value="">بدون ربط</option>
                                        @foreach ($items as $item)
                                            <option value="{{ $item->id }}" @selected((int) $selected === (int) $item->id)>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                    <button class="btn btn-sm btn-success" type="submit">حفظ</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4">اختر متجراً ثم اقرأ المتغيرات. الحفظ هو اللي يثبت الربط.</td></tr>
                    @endforelse
                </tbody>
            </table>
            @if (! empty($page['has_next']))
                <a class="btn btn-outline-primary" href="{{ route('admin.inventory.shopify.index', ['website_setting_id' => $website->id, 'load' => 1, 'cursor' => $page['cursor']]) }}">الصفحة التالية</a>
            @endif
        </div>
    </div>
@endsection
