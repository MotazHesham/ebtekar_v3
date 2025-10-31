<div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>#</th>
            <th>{{ __('global.comment') }}</th>
            <th>{{ __('global.files') }}</th>
            <th>{{ __('global.created_by') }}</th>
            <th>{{ __('global.created_at') }}</th>
            <th>{{ __('global.actions') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($receipt->followups()->with('created_by')->latest()->get() as $f)
            <tr>
                <td>{{ $f->id }}</td>
                <td style="white-space: pre-wrap">{{ $f->comment }}</td>
                <td>
                    @foreach($f->files as $media)
                        <a href="{{ $media->getUrl() }}" target="_blank">{{ trans('global.file') }}</a><br>
                    @endforeach
                </td>
                <td>{{ optional($f->created_by)->name }}</td>
                <td>{{ $f->created_at }}</td>
                <td>
                    <button type="button" class="btn btn-xs btn-info" onclick="edit_followup({{ $f->id }})">{{ __('global.edit') }}</button>
                    <button type="button" class="btn btn-xs btn-danger" onclick="delete_followup({{ $f->id }})">{{ __('global.delete') }}</button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<form id="followup-create-form" onsubmit="return false;" style="margin-top:10px">
    <input type="hidden" name="receipt_social_id" value="{{ $receipt->id }}">
    <div class="form-group">
        <label>{{ __('global.comment') }}</label>
        <textarea name="comment" class="form-control" rows="3"></textarea>
    </div>
    <div class="form-group">
        <label>{{ __('global.files') }}</label>
        <div class="needsclick dropzone" id="followup-files-dropzone"></div>
    </div>
    <button type="button" class="btn btn-primary" onclick="submit_followup_create()">{{ __('global.save') }}</button>
</form>

<script>
if (typeof window.followupUploadedFiles === 'undefined') {
    window.followupUploadedFiles = [];
}
function init_followup_dropzone(){
    if(window.Dropzone === undefined){return;}
    Dropzone.autoDiscover = false;
    var el = document.querySelector('#followup-files-dropzone');
    if(!el){ return; }
    if(el.dropzone){
        try { el.dropzone.destroy(); } catch(e) {}
    }
    var dz = new Dropzone('#followup-files-dropzone', {
        url: '{{ route('admin.users.storeMedia') }}'.replace('users','users'),
        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
        paramName: 'file',
        maxFilesize: 20,
        addRemoveLinks: true,
        init: function(){},
    });
    dz.on('success', function(file, response){
        followupUploadedFiles.push(response.name);
    });
    dz.on('removedfile', function(file){
        // optional cleanup
    });
}
init_followup_dropzone();

function submit_followup_create(){
    const data = $("#followup-create-form").serializeArray();
    const payload = {};
    data.forEach(x=>payload[x.name]=x.value);
    payload['files'] = followupUploadedFiles;

    var $btn = $('#followup-create-form button[type="button"]');
    var originalHtml = $btn.html();
    $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" style="margin-Right:5px" role="status" aria-hidden="true"></span>{{ __('global.saving') ?? 'Saving' }}');

    $.ajax({
        url: '{{ route('admin.receipt-social-followups.store') }}',
        method: 'POST',
        data: payload,
        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
        success: function(){
            try { showFollowupToast('success', '{{ __('global.saved_successfully') ?? 'Saved successfully' }}'); } catch(e){}
            load_followups({{ $receipt->id }});
        },
        error: function(xhr){
            var msg = (xhr && xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : '{{ __('global.something_went_wrong') ?? 'Something went wrong' }}';
            try { showFollowupToast('error', msg); } catch(e){ alert(msg); }
        },
        complete: function(){
            $btn.prop('disabled', false).html(originalHtml);
        }
    });
}

function edit_followup(id){
    var activeEl = document.activeElement;
    var $btn = $(activeEl && activeEl.tagName === 'BUTTON' ? activeEl : null);
    var originalHtml = $btn && $btn.length ? $btn.html() : null;
    if($btn && $btn.length){
        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" style="margin-Right:5px" role="status" aria-hidden="true"></span>{{ __('global.loading') ?? 'Loading' }}');
    }
    $.ajax({
        url: '{{ url('admin/receipt-social-followups') }}/'+id+'/edit',
        method: 'GET',
        success: function(html){
            $('#followups-modal .modal-body').html(html);
        },
        error: function(){
            try { showFollowupToast('error', '{{ __('global.failed_to_load') ?? 'Failed to load' }}'); } catch(e){}
        },
        complete: function(){
            if($btn && $btn.length){ $btn.prop('disabled', false).html(originalHtml); }
        }
    });
}

function delete_followup(id){
    var activeEl = document.activeElement;
    var $btn = $(activeEl && activeEl.tagName === 'BUTTON' ? activeEl : null);
    var originalHtml = $btn && $btn.length ? $btn.html() : null;
    if($btn && $btn.length){
        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" style="margin-Right:5px" role="status" aria-hidden="true"></span>{{ __('global.deleting') ?? 'Deleting' }}');
    }
    $.ajax({
        url: '{{ url('admin/receipt-social-followups') }}/'+id,
        type: 'DELETE',
        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
        success: function(){
            try { showFollowupToast('success', '{{ __('global.deleted_successfully') ?? 'Deleted successfully' }}'); } catch(e){}
            load_followups({{ $receipt->id }});
        },
        error: function(xhr){
            var msg = (xhr && xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : '{{ __('global.something_went_wrong') ?? 'Something went wrong' }}';
            try { showFollowupToast('error', msg); } catch(e){ alert(msg); }
        },
        complete: function(){
            if($btn && $btn.length){ $btn.prop('disabled', false).html(originalHtml); }
        }
    });
}

// simple toast helper with graceful fallback
function showFollowupToast(type, message){
    if(window.toastr && typeof window.toastr[type] === 'function'){
        window.toastr[type](message);
        return;
    }
    if(type === 'error'){
        alert(message);
    } else {
        // non-blocking minimal fallback
        console.log(message);
    }
}
</script>

