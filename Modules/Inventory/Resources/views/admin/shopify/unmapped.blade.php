@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">أوردرات شوبيفاي على متغيرات غير مربوطة</div>
        <div class="card-body">
            <p>الفاتورة اتسجلت، والمخزون ما اتخصمش. اربط المتغير من شاشة شوبيفاي، والأوردر الجاي هيتسجل على الكارت.</p>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>التاريخ</th>
                        <th>الموقع</th>
                        <th>المنتج</th>
                        <th>Variant</th>
                        <th>الفاتورة</th>
                        <th>الرسالة</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($events as $event)
                        <tr>
                            <td>{{ $event->created_at }}</td>
                            <td>{{ $event->website->site_name ?? '' }}</td>
                            <td>{{ $event->product_name }}</td>
                            <td>{{ $event->shopify_variant_id }}</td>
                            <td>{{ $event->receipt_social_id }}</td>
                            <td>{{ $event->message }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6">لا توجد صفوف مفتوحة.</td></tr>
                    @endforelse
                </tbody>
            </table>
            {{ $events->links() }}
        </div>
    </div>
@endsection
