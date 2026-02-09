<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">سجل حركة الفاتورة في قائمة التشغيل</h5>
        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        @if($histories->count())
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>التاريخ</th>
                            <th>نوع الإجراء</th>
                            <th>من مرحلة</th>
                            <th>إلى مرحلة</th>
                            <th>التعيين</th>
                            <th>تم الإرجاع؟</th>
                            <th>المستخدم</th>
                            <th>سبب الإرجاع</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($histories as $index => $history)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $history->created_at }}</td>
                                <td>
                                    @if($history->action_type == 'assignment')
                                        <span class="badge badge-info">تعيين</span>
                                    @else
                                        <span class="badge badge-primary">تغيير حالة</span>
                                    @endif
                                </td>
                                <td>{{ $history->from_status ? \App\Models\ViewPlaylistData::PLAYLIST_STATUS_SELECT[$history->from_status] : '-' }}</td>
                                <td>{{ $history->to_status ? \App\Models\ViewPlaylistData::PLAYLIST_STATUS_SELECT[$history->to_status] : '-' }}</td>
                                <td>
                                    @if($history->action_type == 'assignment' && $history->assigned_to_user_id)
                                        @php
                                            $assignmentLabels = [
                                                'designer' => 'ديزاينر',
                                                'manufacturer' => 'مصنع',
                                                'preparer' => 'مجهز',
                                                'shipmenter' => 'شاحن'
                                            ];
                                            $assignmentLabel = $assignmentLabels[$history->assignment_type] ?? $history->assignment_type;
                                        @endphp
                                        <span class="badge badge-success">
                                            {{ $assignmentLabel }}: {{ optional($history->assignedToUser)->name ?? '-' }}
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($history->is_return)
                                        <span class="badge badge-danger">نعم (إرجاع)</span> 
                                    @endif
                                </td>
                                <td>{{ optional($history->user)->name ?? '-' }}</td>
                                <td>{{ $history->reason ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-center">لا يوجد سجل حركات لهذا الطلب حتى الآن.</p>
        @endif
    </div>
</div>

