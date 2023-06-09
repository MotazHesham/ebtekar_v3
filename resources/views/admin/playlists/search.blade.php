<div class="card">
    <div class="card-header">
        <div style="display: flex;justify-content: space-between">
            <div>{{ trans('global.search') }} {{ trans('global.list') }} {{ trans('cruds.playlist.menu.'.$type) }}</div>
            <div><a class="btn btn-success btn-rounded" href="{{route('admin.qr_scanner',$type)}}">Qr Scanner</a></div>
        </div> 
    </div>
    <div class="card-body">  
        <form action="" method="GET" id="sort_playlist">

            <div class="row"> 
                <div class="col-md-3">  
                    <input type="text" class="form-control mb-2 @isset($order_num) isset @endisset" id="order_num" name="order_num" 
                        @isset($order_num) value="{{ $order_num }}" @endisset placeholder="{{ trans('cruds.playlist.fields.order_num') }}">  
                            
                </div> 
                <div class="col-md-3">
                    <select class="form-control mb-2 @isset($staff_id) isset @endisset select2" name="staff_id" id="staff_id" onchange="sort_playlist()">
                        <option value="">أختر الموظف</option>
                        @foreach ($staffs as $staff)
                            <option value="{{ $staff->id }}" @isset($staff_id) @if ($staff_id == $staff->id) selected @endif @endisset>
                                {{ $staff->name }}
                            </option>
                        @endforeach
                    </select>
                </div> 
                <div class="col-md-3">
                    <select class="form-control mb-2 @isset($view) isset @endisset" name="view" id="view" onchange="sort_playlist()"> 
                        <option value="all" @if($view == 'all') selected @endif>all</option>
                        <option value="by_date" @if($view == 'by_date') selected @endif>By Date</option>
                    </select>

                </div> 
                <div class="col-md-3">
                    <input type="text" class="form-control mb-2 @isset($description) isset @endisset" id="description" name="description" 
                        @isset($description) value="{{ $description }}" @endisset placeholder="{{ trans('global.extra.description') }}"> 
                </div>   
            </div>
                

            <div class="row justify-content-md-center">
                <div class="col-md-3">
                    <input type="submit" value="{{ trans('global.search') }}" name="search" class="btn btn-success btn-rounded btn-block">
                </div>
                <div class="col-md-3">
                    <a class="btn btn-warning btn-rounded btn-block"  href="{{ route('admin.playlists.index',$type) }}">{{ trans('global.reset') }}</a>
                </div> 
            </div> 
        </form>

    </div>
</div>
