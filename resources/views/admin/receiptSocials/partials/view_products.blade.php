<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">{{ __('global.extra.view_products') }} <b>{{ $receipt->order_num }}</b> </h5>
        &nbsp;&nbsp;&nbsp;
        <a href="{{ route('admin.receipt-socials.index',['cancel_popup' => 1 ]) }}" class="btn btn-danger btn-lg mb-1">أغلاق القائمة</a>
        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <table class=" table table-bordered table-striped table-hover datatable table-responsive-lg table-responsive-md table-responsive-sm">
            <thead>
                <tr>
                    <th>
                        {{ __('cruds.receiptSocialProduct.fields.name') }}
                    </th>
                    <th>
                        {{ __('global.extra.description') }}
                    </th>
                    <th>
                        {{ __('cruds.receiptSocialProduct.fields.price') }}
                    </th>
                    <th>
                        {{ __('cruds.receiptSocial.fields.extra_commission') }}
                    </th>
                    <th>
                        {{ __('global.extra.quantity') }}
                    </th>
                    <th>
                        {{ __('cruds.receiptSocial.fields.total_cost') }}
                    </th>
                    <th>
                        {{ __('cruds.receiptSocial.fields.commission') }}
                    </th>
                    <th>
                        {{ __('cruds.receiptSocialProduct.fields.photos') }}
                    </th>
                    <th>
                        &nbsp;
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    <tr data-entry-id="{{ $product->id }}">
                        <td>  
                            {{ $product->title ?? '' }}
                        </td> 
                        <td> 
                            <?php echo nl2br($product->description ?? '') ?>
                        </td> 
                        <td> 
                            {{ $product->price ? dashboard_currency($product->price): '' }}
                        </td> 
                        <td> 
                            {{ $product->extra_commission ? dashboard_currency($product->extra_commission): '' }}
                        </td> 
                        <td> 
                            {{ $product->quantity ?? '' }}
                        </td> 
                        <td> 
                            {{ $product->total_cost ? dashboard_currency($product->total_cost): '' }}
                        </td> 
                        <td> 
                            {{ $product->commission ? dashboard_currency($product->commission): '' }}
                        </td> 
                        <td>  
                            @if($product->photos)
                                @foreach(json_decode($product->photos) as $photo)
                                    <div>
                                        <a href="{{ asset($photo->photo) }}" target="_blanc">
                                            <img src="{{ asset($photo->photo) }}" width="50" height="50" alt="">
                                        </a>
                                        <span>{{ $photo->note }}</span>
                                    </div>
                                @endforeach
                            @endif 
                        </td> 
                        <td> 
                            <div style="display: flex;justify-content: space-between">
                                @can('receipt_social_edit_product')
                                    <a class="btn btn-info text-white" onclick="edit_product('{{$product->id}}')"> 
                                        <i class="far fa-edit"></i>
                                    </a>
                                @endcan

                                @can('receipt_social_delete_product')
                                    <?php $route = route('admin.receipt-socials.destroy_product', $product->id); ?>
                                    <a class="btn btn-danger"
                                        href="#" onclick="deleteConfirmation('{{$route}}')"> 
                                        <i class="fas fa-trash-alt"></i>
                                    </a> 
                                @endcan 
                            </div>
                        </td> 
                    </tr>
                @empty
                    <tr>
                        <td colspan="9">
                            No data available in table
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table> 
    </div>
</div>