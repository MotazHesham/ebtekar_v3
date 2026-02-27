<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">سجل مراحل العمل (Workflow Operations)</h5>
        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        @if($operations->count())
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>المرحلة</th>
                            <th>الحالة</th>
                            <th>بداية المرحلة</th>
                            <th>نهاية المرحلة</th>
                            <th>المدة</th>
                            <th>المستخدم</th>
                            <th>الورديه</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($operations as $index => $operation)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $operation->stage }}</td>
                                <td>
                                    @if($operation->status === 'completed')
                                        <span class="badge badge-success">مكتملة</span>
                                    @elseif($operation->status === 'in_progress')
                                        <span class="badge badge-info">قيد التنفيذ</span>
                                    @elseif($operation->status === 'pending')
                                        <span class="badge badge-secondary">قيد الانتظار</span>
                                    @else
                                        <span class="badge badge-light">{{ $operation->status }}</span>
                                    @endif
                                </td>
                                <td>{{ $operation->started_at }}</td>
                                <td>{{ $operation->ended_at ?? '-' }}</td>
                                <td>
                                    @if($operation->started_at && $operation->ended_at)
                                        {{ \Carbon\Carbon::parse($operation->started_at)->diffForHumans(\Carbon\Carbon::parse($operation->ended_at), true) }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ optional($operation->user)->name ?? '-' }}</td>
                                <td>{{ optional($operation->shift)->name ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-center">لا يوجد سجلات مراحل عمل لهذا الطلب حتى الآن.</p>
        @endif
    </div>
</div>

