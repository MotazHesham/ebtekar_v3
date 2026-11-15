@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">فشل مزامنة شوبيفاي</div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>التاريخ</th>
                        <th>الصنف</th>
                        <th>الموقع</th>
                        <th>الكمية المرسلة</th>
                        <th>الخطأ</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($failures as $failure)
                        <tr>
                            <td>{{ $failure->created_at }}</td>
                            <td>{{ $failure->item->name ?? ('#'.$failure->inv_item_id) }}</td>
                            <td>{{ $failure->website->site_name ?? '' }}</td>
                            <td>{{ $failure->quantity_sent }}</td>
                            <td>{{ $failure->error }}</td>
                            <td>
                                <form method="POST" action="{{ route('admin.inventory.sync-failures.retry', $failure) }}">
                                    @csrf
                                    <button class="btn btn-sm btn-primary" type="submit">إعادة الإرسال</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6">لا توجد عمليات فاشلة مفتوحة.</td></tr>
                    @endforelse
                </tbody>
            </table>
            {{ $failures->links() }}
        </div>
    </div>
@endsection
