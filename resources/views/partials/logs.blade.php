
<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title" id="AjaxModalLabel">Logs</h5>
        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        @foreach($logs as $key => $log)
            @php
                $user = \App\Models\User::withTrashed()->find($log->user_id);
            @endphp 
            @if($log->properties != null)
                <div class="card">
                    <div class="card-header" id="heading{{$log->id}}">
                        <h2 class="mb-0">
                            <button class="btn btn-default" type="button" data-toggle="collapse" data-target="#collapse{{$log->id}}" aria-expanded="false" aria-controls="collapse{{$log->id}}">
                                <span>{{$user ? $user->name : ''}}</span> - <span style="color:rebeccapurple"> {{ $log->created_at }} </span>
                            </button>
                        </h2>
                    </div>
                    <div id="collapse{{$log->id}}" class="collapse" aria-labelledby="heading{{$log->id}}" >
                        <div class="card-body" style="direction: rtl;background: #f1f1f1;  padding: 20px; border-radius: 40px;">
                            <h5>
                                <span class="badge badge-warning text-dark">{{ str_replace('App\Models\\','',$log->subject_type) }}</span>
                                <span class="badge badge-danger">{{ $log->description }}</span> 
                            </h5>
                            <div class="row">
                                @foreach(json_decode($log->properties) as $key => $value)
                                    <div class="col-md-4 mb-2">
                                        @if(!is_array($value)) 
                                            <span class="badge badge-info">{{ __('cruds.'.$crud_name.'.fields.'.$key) }}</span> 
                                            {{ $value }}
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        @endforeach
    </div>
</div>