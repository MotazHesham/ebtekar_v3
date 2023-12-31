<div class="modal fade" id="manufacturing_items" tabindex="-1" data-keyboard="false" aria-labelledby="manufacturing_itemsLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="AjaxModalLabel">المنتجات المطوب تصنيعها</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">المنتج</th>
                            <th scope="col">الكمية المطلوبة</th> 
                        </tr>
                    </thead>
                    <tbody>
                        @if($items_to_manufacturing)
                            @foreach ($items_to_manufacturing as $key => $item)
                                <tr>
                                    <th scope="row">{{ $key + 1 }}</th>
                                    <td>{{ $item->title }}</td>
                                    <td>{{ $item->quantity}}</td> 
                                </tr> 
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
.
<form action="" method="GET" id="sort_playlist">
    <div class="card">
        <div class="card-header">
            <div style="display: flex;justify-content: space-between">
                <div>{{ trans('global.search') }} {{ trans('global.list') }} {{ trans('cruds.playlist.menu.' . $type) }}
                </div>
                <div><a class="btn btn-success btn-rounded" href="{{ route('admin.qr_scanner', $type) }}">Qr Scanner</a>
                </div>
                @if($type == 'manufacturing')
                    <div><a class="btn btn-dark btn-rounded" href="#"  data-toggle="modal" data-target="#manufacturing_items">Manufacturing
                            Items</a>
                    </div>
                @endif
                <div><a class="btn btn-info btn-rounded" href="{{ route('admin.barcode_scanner', $type) }}">BarCode
                        Scanner</a>
                </div>
                <select class="form-control @isset($website_setting_id) isset @endisset" style="width: 200px"
                    name="website_setting_id" id="website_setting_id" onchange="sort_playlist()">
                    <option value="">أختر الموقع</option>
                    @foreach ($websites as $id => $entry)
                        <option value="{{ $id }}"
                            @isset($website_setting_id) @if ($website_setting_id == $id) selected @endif @endisset>
                            {{ $entry }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="card-body">

            <div class="row">
                <div class="col-md-4">
                    <input type="text" class="form-control mb-2 @isset($order_num) isset @endisset"
                        id="order_num" name="order_num"
                        @isset($order_num) value="{{ $order_num }}" @endisset
                        placeholder="{{ trans('cruds.playlist.fields.order_num') }}">

                </div>
                <div class="col-md-4">
                    <select class="form-control mb-2 @isset($staff_id) isset @endisset select2"
                        name="staff_id" id="staff_id" onchange="sort_playlist()">
                        <option value="">أختر الموظف</option>
                        @foreach ($staffs as $staff)
                            <option value="{{ $staff->id }}"
                                @isset($staff_id) @if ($staff_id == $staff->id) selected @endif @endisset>
                                {{ $staff->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <select class="form-control mb-2 @isset($view) isset @endisset" name="view"
                        id="view" onchange="sort_playlist()">
                        <option value="all" @if ($view == 'all') selected @endif>all</option>
                        <option value="by_date" @if ($view == 'by_date') selected @endif>By Date</option>
                    </select>

                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control mb-2 @isset($description) isset @endisset"
                        id="description" name="description"
                        @isset($description) value="{{ $description }}" @endisset
                        placeholder="{{ trans('global.extra.description') }}">
                </div>
                <div class="col-md-4">
                    <select class="form-control mb-2 @isset($quickly) isset @endisset"
                        name="quickly" id="quickly" onchange="sort_playlist()">
                        <option value="">{{ trans('global.extra.quickly') }}</option>
                        <option value="0"
                            @isset($quickly) @if ($quickly == '0') selected @endif @endisset>
                            {{ trans('global.extra.0_quickly') }}</option>
                        <option value="1"
                            @isset($quickly) @if ($quickly == '1') selected @endif @endisset>
                            {{ trans('global.extra.1_quickly') }}</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <select class="form-control mb-2 @isset($client_review) isset @endisset"
                        name="client_review" id="client_review" onchange="sort_playlist()">
                        <option value="">الكل</option>
                        <option value="0" @if ($client_review == '0') selected @endif>قيد التنفيذ</option>
                        <option value="1" @if ($client_review == '1') selected @endif>تحت المراجعة</option>
                    </select>
                </div>
            </div>


            <div class="row justify-content-md-center">
                <div class="col-md-3">
                    <input type="submit" value="{{ trans('global.search') }}" name="search"
                        class="btn btn-success btn-rounded btn-block">
                </div>
                <div class="col-md-3">
                    <a class="btn btn-warning btn-rounded btn-block"
                        href="{{ route('admin.playlists.index', $type) }}">{{ trans('global.reset') }}</a>
                </div>
            </div>

        </div>
    </div>
</form>
