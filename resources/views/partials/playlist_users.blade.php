
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
    </div>
</div>
