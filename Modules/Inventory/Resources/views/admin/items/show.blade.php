@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <span>{{ $item->name }}</span>
            <span>الرصيد: {{ $item->balance->on_hand ?? 0 }}</span>
        </div>
        <div class="card-body">
            @can('inventory_edit')
                <form method="POST" action="{{ route('admin.inventory.items.update', $item) }}" class="mb-4">
                    @csrf
                    @method('PUT')
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>الاسم</label>
                            <input class="form-control" name="name" value="{{ old('name', $item->name) }}" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label>الكود</label>
                            <input class="form-control" name="sku" value="{{ old('sku', $item->sku) }}">
                        </div>
                        <div class="form-group col-md-2 d-flex align-items-end">
                            <button class="btn btn-primary" type="submit">تحديث</button>
                        </div>
                    </div>
                </form>
            @endcan

            @can('inventory_adjust')
                <form method="POST" action="{{ route('admin.inventory.items.adjust', $item) }}" class="mb-4">
                    @csrf
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label>الرصيد الفعلي</label>
                            <input class="form-control" type="number" min="0" name="on_hand" value="{{ old('on_hand', $item->balance->on_hand ?? 0) }}" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>السبب</label>
                            <input class="form-control" name="note" value="{{ old('note') }}" required placeholder="جرد افتتاحي أو تسوية">
                        </div>
                        <div class="form-group col-md-3 d-flex align-items-end">
                            <button class="btn btn-warning" type="submit">حفظ الرصيد</button>
                        </div>
                    </div>
                    <small class="text-muted">أول حفظ من غير حركات سابقة يتسجل رصيد افتتاحي. الحفظ التالي تسوية. الرقم ده هو اللي يتبعت لكل متاجر شوبيفاي المربوطة.</small>
                </form>
            @endcan

            @can('inventory_shopify')
                <form method="POST" action="{{ route('admin.inventory.items.sync', $item) }}" class="mb-4">
                    @csrf
                    <button class="btn btn-outline-primary" type="submit">إرسال الرصيد لشوبيفاي الآن</button>
                    @if ($item->balance?->sync_pending)
                        <span class="badge badge-warning">في إرسال معلّق</span>
                    @endif
                </form>
            @endcan

            <h5>منتجات السوشيال</h5>
            <ul>
                @forelse ($item->socialLinks as $link)
                    <li>{{ $link->product->name ?? ('#'.$link->receipt_social_product_id) }} — {{ $link->product->website->site_name ?? '' }}</li>
                @empty
                    <li>لا يوجد ربط.</li>
                @endforelse
            </ul>

            <h5>متاجر شوبيفاي</h5>
            <ul>
                @forelse ($item->channelLinks as $link)
                    <li>{{ $link->website->site_name ?? '' }} — {{ $link->product_title }} / {{ $link->variant_title }} ({{ $link->shopify_variant_id }})</li>
                @empty
                    <li>لا يوجد ربط.</li>
                @endforelse
            </ul>
        </div>
    </div>

    <div class="card">
        <div class="card-header">دفتر الحركة</div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>التاريخ</th>
                        <th>النوع</th>
                        <th>الفرق</th>
                        <th>الرصيد بعد الحركة</th>
                        <th>المصدر</th>
                        <th>ملاحظة</th>
                        <th>المستخدم</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($movements as $movement)
                        <tr>
                            <td>{{ $movement->created_at }}</td>
                            <td>{{ $movement->type }}</td>
                            <td>{{ $movement->quantity_delta }}</td>
                            <td>{{ $movement->on_hand_after }}</td>
                            <td>{{ $movement->source_type }} {{ $movement->source_id }}</td>
                            <td>{{ $movement->note }}</td>
                            <td>{{ $movement->user->name ?? '' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7">لا توجد حركات.</td></tr>
                    @endforelse
                </tbody>
            </table>
            {{ $movements->links() }}
        </div>
    </div>
@endsection
