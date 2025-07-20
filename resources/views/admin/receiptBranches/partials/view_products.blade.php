<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">{{ __('global.extra.view_products') }} <b>{{ $receipt->order_num }}</b> </h5>
        &nbsp;&nbsp;&nbsp;
        <a href="{{ route('admin.receipt-branches.index',['cancel_popup' => 1 ]) }}" class="btn btn-danger btn-lg mb-1">أغلاق القائمة</a>
        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <table class=" table table-bordered table-striped table-hover datatable table-responsive-lg table-responsive-md table-responsive-sm">
            <thead>
                <tr> 
                    <th>
                        {{ __('global.extra.description') }}
                    </th>
                    <th>
                        {{ __('global.extra.price') }}
                    </th> 
                    <th>
                        {{ __('global.extra.quantity') }}
                    </th>
                    <th>
                        {{ __('cruds.receiptBranch.fields.total_cost') }}
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
                            <?php echo nl2br($product->description ?? '') ?>
                        </td> 
                        <td> 
                            {{ $product->price ? dashboard_currency($product->price): '' }}
                        </td>
                        <td> 
                            {{ $product->quantity ?? '' }}
                        </td> 
                        <td> 
                            {{ $product->total_cost ? dashboard_currency($product->total_cost): '' }}
                        </td>
                        <td> 
                            @if(!$receipt->done)
                                <div style="display: flex;justify-content: space-evenly">
                                    @can('receipt_branch_edit_product')
                                        <a class="btn btn-info text-white" onclick="edit_product('{{$product->id}}')"> 
                                            <i class="far fa-edit"></i>
                                        </a> 
                                    @endcan 

                                    @can('receipt_branch_delete_product')
                                        <?php $route = route('admin.receipt-branches.destroy_product', $product->id); ?>
                                        <a class="btn btn-danger"
                                            href="#" onclick="deleteConfirmation('{{$route}}')"> 
                                            <i class="fas fa-trash-alt"></i>
                                        </a> 
                                    @endcan 
                                </div>
                            @endif
                        </td> 
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            No data available in table
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>