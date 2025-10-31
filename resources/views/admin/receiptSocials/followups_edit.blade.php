<form id="followup-edit-form" onsubmit="return false;">
    <div class="form-group">
        <label>{{ __('global.comment') }}</label>
        <textarea name="comment" class="form-control" rows="3">{{ $followup->comment }}</textarea>
    </div>
    <div class="form-group">
        <label>{{ __('global.files') }}</label>
        <div class="mb-2">
            @foreach($followup->files as $media)
                <a href="{{ $media->getUrl() }}" target="_blank">{{ $media->file_name }}</a><br>
            @endforeach
        </div>
        <div class="needsclick dropzone" id="followup-files-dropzone-edit"></div>
    </div>
    <button type="button" class="btn btn-primary" onclick="submit_followup_update({{ $followup->id }})">{{ __('global.update') }}</button>
</form>

<script>
let followupEditUploadedFiles = {!! json_encode($followup->files->pluck('file_name')->values()) !!};
function init_followup_dropzone_edit(){
    if(window.Dropzone === undefined){return;}
    Dropzone.autoDiscover = false;
    const el = document.querySelector('#followup-files-dropzone-edit');
    if(!el){ return; }
    if(el.dropzone){
        try { el.dropzone.destroy(); } catch(e) {}
    }
    const dz2 = new Dropzone('#followup-files-dropzone-edit', {
        url: '{{ route('admin.users.storeMedia') }}'.replace('users','users'),
        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
        paramName: 'file',
        maxFilesize: 20,
        addRemoveLinks: true,
        init: function(){},
    });
    dz2.on('success', function(file, response){
        followupEditUploadedFiles.push(response.name);
    });
}
init_followup_dropzone_edit();

function submit_followup_update(id){
    const data = $("#followup-edit-form").serializeArray();
    const payload = {};
    data.forEach(x=>payload[x.name]=x.value);
    payload['files'] = followupEditUploadedFiles;
    $.post({
        url: '{{ url('admin/receipt-social-followups') }}/'+id+'/update',
        data: payload,
        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
        success: function(){
            $('#followups-modal').modal('hide');
        }
    });
}
</script>

