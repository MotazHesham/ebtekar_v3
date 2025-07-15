@extends('layouts.admin') 
@section('content')

<!-- Modern Header -->
<div class="modern-header">
    <div class="header-content">
        <div class="header-title">
            <h2 class="mb-0 text-dark">
                <i class="fas fa-receipt me-3"></i>
                {{ __('cruds.receiptSocial.title') }}
            </h2> 
        </div>
        <div class="header-actions">
            
            <!-- Collapsible Secondary Actions -->
            <div class="secondary-actions">
                <button class="btn btn-secondary text-dark btn-sm" onclick="toggleSecondaryActions()" id="secondaryActionsToggle">
                    <i class="fas fa-ellipsis-h me-1"></i>
                    <span id="secondaryActionsText">More Actions</span>
                    <i class="fas fa-chevron-down ms-1 toggle-icon text-dark"></i>
                </button>
                
                <div class="secondary-actions-menu" id="secondaryActionsMenu">
                    <div class="actions-grid">
                        @can('receipt_social_product_access')
                        <a class="btn btn-info btn-sm" href="{{ route('admin.receipt-social-products.index') }}">
                            <i class="fas fa-box me-1"></i>
                            {{ __('cruds.receiptSocialProduct.title') }}
                        </a>
                        @endcan
                        
                        <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#uploadFedexModal">
                            <i class="fas fa-upload me-1"></i>
                            {{ __('global.extra.upload_fedex') }}
                        </button> 
                        
                        @if(isset($deleted))
                            <a class="btn btn-danger btn-sm" href="{{ route('admin.receipt-socials.index') }}">
                                <i class="fas fa-trash me-1"></i>
                                {{ __('global.extra.deleted_receipts') }}
                            </a>
                        @else                        
                            @if(Gate::allows('soft_delete'))
                            <a class="btn btn-danger btn-sm" href="{{ route('admin.receipt-socials.index',['deleted' => 1]) }}">
                                <i class="fas fa-trash me-1"></i>
                                {{ __('global.extra.deleted_receipts') }}
                            </a>
                            @endif
                        @endif
                        
                        <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#productsReportModal">
                            <i class="fas fa-chart-bar me-1"></i>
                            تقارير المنتجات
                        </button> 
                    </div>
                </div>
            </div>

            <div class="primary-actions">
                @can('receipt_social_create')
                    <button class="btn btn-primary" data-toggle="modal" data-target="#phoneModal">
                        <i class="fas fa-plus me-2"></i>
                        {{ __('global.add') }} {{ __('cruds.receiptSocial.title_singular') }}
                    </button>
                @endcan
            </div>
        </div>
    </div>
</div>

<!-- Modern Statistics -->
@include('admin.receiptSocials.partials.statistics_modern')

<!-- Modern Search -->
@include('admin.receiptSocials.partials.search_modern')

<!-- Modern Table -->
@include('admin.receiptSocials.partials.table_modern')

<!-- Phone Modal -->
<div class="modal fade" id="phoneModal" tabindex="-1" aria-labelledby="phoneModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="phoneModalLabel">
                    <i class="fas fa-plus me-2"></i>
                    {{ __('global.add') }} {{ __('cruds.receiptSocial.title_singular') }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.receipt-socials.create') }}">
                    <div class="row mb-3">
                        @foreach($websites as $id => $entry)
                            <div class="form-check form-check-inline col">
                                <input class="form-check-input" type="radio" name="website_setting_id" id="{{$id}}" value="{{$id}}" required @if($id == auth()->user()->website_setting_id) checked @endif>
                                <label class="form-check-label" for="{{$id}}">{{$entry}}</label>
                            </div>
                        @endforeach 
                    </div>
                    <input type="text" name="phone_number" class="form-control" required
                        placeholder="{{ __('cruds.receiptSocial.fields.phone_number') }}"
                        onkeyup="searchByPhone(this)">
                    <div id="table-receipts">
                        {{-- ajax call --}}
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Hold Reason Modal -->
<div class="modal fade" id="holdReasonModal" tabindex="-1" aria-labelledby="holdReasonModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="holdReasonModalLabel">
                    <i class="fas fa-pause me-2"></i>
                    Hold Reason
                </h5>
                <button type="button" class="btn-close btn-close-white" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="holdReason">Please enter reason for hold:</label>
                    <textarea class="form-control" id="holdReason" rows="3" required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancel
                </button>
                <button type="button" class="btn btn-primary" onclick="submitHoldForm()">
                    <i class="fas fa-check me-2"></i>Submit
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.modern-header { 
    color: white;
    padding: 2rem;
    border-radius: 15px;
    margin-bottom: 2rem;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.header-title h2 {
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.header-actions .btn {
    border-radius: 25px;
    padding: 0.75rem 1.5rem;
    font-weight: 600;
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    transition: all 0.3s ease;
}

.header-actions .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.3);
}

/* Secondary Actions Styles */
.header-actions {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.secondary-actions {
    position: relative;
}

.secondary-actions-menu {
    position: absolute;
    top: 100%;
    right: 0;
    background: white;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    padding: 1rem;
    min-width: 300px;
    z-index: 1000;
    opacity: 0;
    visibility: hidden;
    transform: translateY(-10px);
    transition: all 0.3s ease;
    border: 1px solid #e9ecef;
}

.secondary-actions-menu.show {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.actions-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 0.5rem;
}

.actions-grid .btn {
    font-size: 0.8rem;
    padding: 0.5rem 0.75rem;
    border-radius: 8px;
    white-space: nowrap;
    text-align: left;
}

.toggle-icon {
    transition: transform 0.3s ease;
}

.secondary-actions-menu.show + .secondary-actions-toggle .toggle-icon,
#secondaryActionsToggle.collapsed .toggle-icon {
    transform: rotate(180deg);
}

@media (max-width: 768px) {
    .header-content {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .header-title {
        text-align: center;
    }
}
</style>

@endsection

@section('scripts') 
    <!-- Add Bootstrap 5 and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
    
    <script>
        $(document).ready(function() {  
            $('input[type=checkbox]').on('click',function() {     
                return confirm('are you sure?');
            });
            
            // Initialize tooltips properly (Bootstrap 4)
            if (typeof $ !== 'undefined' && $.fn.tooltip) {
                $('[data-toggle="tooltip"]').tooltip({
                    trigger: 'hover'
                });
            }
            
            // Initialize dropdowns properly (Bootstrap 4)
            if (typeof $ !== 'undefined' && $.fn.dropdown) {
                $('.dropdown-toggle').dropdown();
            }
            
            // Bootstrap 5 initialization
            if (typeof bootstrap !== 'undefined') {
                // Initialize tooltips
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
                
                // Initialize dropdowns
                var dropdownElementList = [].slice.call(document.querySelectorAll('[data-bs-toggle="dropdown"]'));
                var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
                    return new bootstrap.Dropdown(dropdownToggleEl);
                });
            }
        });

        function show_qr_code(order_num,bar_code){
            $.post('{{ route('admin.show_qr_code') }}', {
                _token: '{{ csrf_token() }}',
                order_num: order_num,
                bar_code: bar_code,
            }, function(data) {
                $('#AjaxModal .modal-dialog').html(null);
                $('#AjaxModal').modal('show');
                $('#AjaxModal .modal-dialog').html(data); 
            });
        }
        
        function toggleSecondaryActions() {
            const menu = document.getElementById('secondaryActionsMenu');
            const toggle = document.getElementById('secondaryActionsToggle');
            const text = document.getElementById('secondaryActionsText');
            
            if (menu.classList.contains('show')) {
                // Hide menu
                menu.classList.remove('show');
                toggle.classList.remove('collapsed');
                text.textContent = 'More Actions';
            } else {
                // Show menu
                menu.classList.add('show');
                toggle.classList.add('collapsed');
                text.textContent = 'Less Actions';
            }
        }
        
        // Close secondary actions menu when clicking outside
        document.addEventListener('click', function(event) {
            const secondaryActions = document.querySelector('.secondary-actions');
            const menu = document.getElementById('secondaryActionsMenu');
            const toggle = document.getElementById('secondaryActionsToggle');
            
            if (!secondaryActions.contains(event.target) && menu.classList.contains('show')) {
                menu.classList.remove('show');
                toggle.classList.remove('collapsed');
                document.getElementById('secondaryActionsText').textContent = 'More Actions';
            }
        });
    </script>
    <script>
        function add_product(id) {
            $.post('{{ route('admin.receipt-socials.add_product') }}', {
                _token: '{{ csrf_token() }}',
                id: id
            }, function(data) {
                $('#AjaxModal .modal-dialog').html(null);
                $('#AjaxModal').modal('show');
                $('#AjaxModal .modal-dialog').html(data);

                // load the ckeditor to description
                var allEditors = document.querySelectorAll('.ckeditor');
                for (var i = 0; i < allEditors.length; ++i) {
                    ClassicEditor.create(
                        allEditors[i], {
                            extraPlugins: [SimpleUploadAdapter]
                        }
                    );
                }
            });
        }

        function edit_product(id) {
            $.post('{{ route('admin.receipt-socials.edit_product') }}', {
                _token: '{{ csrf_token() }}',
                id: id
            }, function(data) {
                $('#AjaxModal2 .modal-dialog').html(null);
                $('#AjaxModal2').modal('show');
                $('#AjaxModal2 .modal-dialog').html(data);

                // load the ckeditor to description
                var allEditors = document.querySelectorAll('.ckeditor');
                for (var i = 0; i < allEditors.length; ++i) {
                    ClassicEditor.create(
                        allEditors[i], {
                            extraPlugins: [SimpleUploadAdapter]
                        }
                    );
                }
            });
        }

        function view_products(id) {
            $.post('{{ route('admin.receipt-socials.view_products') }}', {
                _token: '{{ csrf_token() }}',
                id: id
            }, function(data) {
                $('#AjaxModal .modal-dialog').html(null);
                $('#AjaxModal').modal('show');
                $('#AjaxModal .modal-dialog').html(data);
            });
        }

        var photo_id = 2;

        function add_more_slider_image() {
            var photoAdd = '<div class="row">';
            photoAdd += '<div class="col-md-2">';
            photoAdd +=
                '<button type="button" onclick="delete_this_row(this)" class="btn btn-danger">{{ __('global.extra.delete_photo') }}</button>';
            photoAdd += '</div>';
            photoAdd += '<div class="col-md-6">';
            photoAdd += '<input type="file" name="photos[][photo]" id="photos-' + photo_id +
                '" class="custom-input-file custom-input-file--4 form-control" data-multiple-caption="{count} files selected" multiple accept="image/*" />';
            photoAdd += '<label for="photos-' + photo_id + '" class="mw-100 mb-3">';
            photoAdd += '<span></span>';
            photoAdd += '</label>';
            photoAdd += '</div>';
            photoAdd += '<div class="col-md-4">';
            photoAdd +=
                '<input type="text" name="photos[][note]" class="form-control" placeholder="{{ __('global.extra.photo_note') }}">';
            photoAdd += '</div>';
            photoAdd += '</div>';
            $('#product-images').append(photoAdd);

            photo_id++;
            imageInputInitialize();
        }

        function delete_this_row(em) {
            $(em).closest('.row').remove();
        }

        function imageInputInitialize() {
            $('.custom-input-file').each(function() {
                var $input = $(this),
                    $label = $input.next('label'),
                    labelVal = $label.html();

                $input.on('change', function(e) {
                    var fileName = '';

                    if (this.files && this.files.length > 1)
                        fileName = (this.getAttribute('data-multiple-caption') || '').replace('{count}',
                            this.files.length);
                    else if (e.target.value)
                        fileName = e.target.value.split('\\').pop();

                    if (fileName)
                        $label.find('span').html(fileName);
                    else
                        $label.html(labelVal);
                });

                // Firefox bug fix
                $input
                    .on('focus', function() {
                        $input.addClass('has-focus');
                    })
                    .on('blur', function() {
                        $input.removeClass('has-focus');
                    });
            });
        }
    </script>
    
    <script>
        function SimpleUploadAdapter(editor) {
            editor.plugins.get('FileRepository').createUploadAdapter = function(loader) {
                return {
                    upload: function() {
                        return loader.file
                            .then(function(file) {
                                return new Promise(function(resolve, reject) {
                                    // Init request
                                    var xhr = new XMLHttpRequest();
                                    xhr.open('POST',
                                        '{{ route('admin.receipt-social-products.storeCKEditorImages') }}',
                                        true);
                                    xhr.setRequestHeader('x-csrf-token', window._token);
                                    xhr.setRequestHeader('Accept', 'application/json');
                                    xhr.responseType = 'json';

                                    // Init listeners
                                    var genericErrorText =
                                        `Couldn't upload file: ${ file.name }.`;
                                    xhr.addEventListener('error', function() {
                                        reject(genericErrorText)
                                    });
                                    xhr.addEventListener('abort', function() {
                                        reject()
                                    });
                                    xhr.addEventListener('load', function() {
                                        var response = xhr.response;

                                        if (!response || xhr.status !== 201) {
                                            return reject(response && response
                                                .message ?
                                                `${genericErrorText}\n${xhr.status} ${response.message}` :
                                                `${genericErrorText}\n ${xhr.status} ${xhr.statusText}`
                                            );
                                        }

                                        $('form').append(
                                            '<input type="hidden" name="ck-media[]" value="' +
                                            response.id + '">');

                                        resolve({
                                            default: response.url
                                        });
                                    });

                                    if (xhr.upload) {
                                        xhr.upload.addEventListener('progress', function(
                                            e) {
                                            if (e.lengthComputable) {
                                                loader.uploadTotal = e.total;
                                                loader.uploaded = e.loaded;
                                            }
                                        });
                                    }

                                    // Send request
                                    var data = new FormData();
                                    data.append('upload', file);
                                    data.append('crud_id', '{{ $product->id ?? 0 }}');
                                    xhr.send(data);
                                });
                            })
                    }
                };
            }
        }
    </script>
    <script>
        $(document).ready(function() {
            @if(session('store_receipt_socail_id') && session('store_receipt_socail_id') != null)
                add_product('{{session("store_receipt_socail_id")}}') 
            @endif
            @if(session('update_receipt_socail_id') && session('update_receipt_socail_id') != null)
                view_products('{{session("update_receipt_socail_id")}}') 
            @endif
        });

        function sort_receipt_social(el) {
            $('#sort_receipt_social').submit();
        }

        function update_statuses(el,type){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('{{ route('admin.receipt-socials.update_statuses') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status, type:type}, function(data){
                if (data['status'] == '1') {
                    showAlert('success', 'Success', data['message']);
                } else if(data['status'] == '2') { 
                    $('#supplied-'+el.value).html(data['first']);
                    showAlert('success', 'Success', data['message']);
                } 
            });
        }
    </script>
    <script>
        let currentReceiptId = null;

        function showHoldModal(receiptId, existingReason) {
            currentReceiptId = receiptId; 
            $('#holdReason').val(existingReason);
            $('#holdReasonModal').modal('show');
        }

        function submitHoldForm() {
            const reason = $('#holdReason').val();
            if (!reason.trim()) {
                alert('Please enter a reason for hold');
                return;
            }
            
            $(`#hold-reason-${currentReceiptId}`).val(reason);
            $(`#hold-form-${currentReceiptId}`).submit();
            $('#holdReasonModal').modal('hide');
        }
    </script>
    <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@endsection 