
<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title"> <b>{{ $raw->order_num }}</b> </h5>
        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <form action="{{ route('admin.playlists.update_playlist_users') }}" method="POST">
            @csrf
            <input type="hidden" name="id" value="{{$id}}">
            <input type="hidden" name="model_type" value="{{$model_type}}">
            <div class="row">
                <div class="col-md-6">
                    <span>&nbsp;</span>
                    <div class="" style="min-width: 160px;margin-bottom: 10px">
                        <select class="form-control" name="designer_id" id="designer_id" required>
                            <option value="">أختر الديزاينر</option>
                            @foreach($staffs as $staff)
                                <option value="{{$staff->id}}"
                                        @if($raw->designer_id)
                                            @if($raw->designer_id == $staff->id)
                                                selected 
                                            @endif
                                        @else 
                                            @if($site_settings->designer_id == $staff->id)
                                                selected
                                            @endif
                                        @endif>
                                        {{$staff->name}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <span>&nbsp;</span>
                    <div class="" style="min-width: 160px;margin-bottom: 10px">
                        <select class="form-control" name="manufacturer_id" id="manufacturer_id" required>
                            <option value="">اختر المصنع</option>
                            @foreach($staffs as $staff)
                            <option value="{{$staff->id}}"
                                    @if($raw->manufacturer_id)
                                        @if($raw->manufacturer_id == $staff->id)
                                            selected 
                                        @endif
                                    @else 
                                        @if($site_settings->manufacturer_id == $staff->id)
                                            selected
                                        @endif
                                    @endif>
                                        {{$staff->name}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <span>&nbsp;</span>
                    <div class="" style="min-width: 160px;margin-bottom: 10px">
                        <select class="form-control" name="preparer_id" id="preparer_id" required>
                            <option value="">اختر المجهز</option>
                            @foreach($staffs as $staff)
                            <option value="{{$staff->id}}"
                                    @if($raw->preparer_id)
                                        @if($raw->preparer_id == $staff->id)
                                            selected 
                                        @endif
                                    @else 
                                        @if($site_settings->preparer_id == $staff->id)
                                            selected
                                        @endif
                                    @endif>
                                        {{$staff->name}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <span>&nbsp;</span>
                    <div class="" style="min-width: 160px;margin-bottom: 10px">
                        <select class="form-control" name="shipmenter_id" id="shipmenter_id" required>
                            <option value="">اختر المرسل للشحن</option>
                            @foreach($staffs as $staff)
                            <option value="{{$staff->id}}"
                                    @if($raw->shipmenter_id)
                                        @if($raw->shipmenter_id == $staff->id)
                                            selected 
                                        @endif
                                    @else 
                                        @if($site_settings->shipmenter_id == $staff->id)
                                            selected
                                        @endif
                                    @endif>
                                        {{$staff->name}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>

        @if($histories->count())
            <div class="table-responsive mt-3">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>التاريخ</th>
                            <th>من مرحلة</th>
                            <th>إلى مرحلة</th>
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
                                <td>{{ $history->from_status ? \App\Models\ViewPlaylistData::PLAYLIST_STATUS_SELECT[$history->from_status] : '-' }}</td>
                                <td>{{ $history->to_status ? \App\Models\ViewPlaylistData::PLAYLIST_STATUS_SELECT[$history->to_status] : '-' }}</td>
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
