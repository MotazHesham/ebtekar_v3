@if ($receipt->branch)
    @if($receipt->done)
        @if ($receipt->branch->payment_type == 'permissions' || $receipt->branch->payment_type == 'permissions_parts')
            <form action="{{ route('admin.receipt-branches.permission_status') }}" method="POST">
                @csrf
                <input type="hidden" name="id" value="{{ $receipt->id }}">
                <div class="mt-1">
                    @foreach ($receipt->incomes as $income)
                        <span class="badge text-bg-light">{{ $income->amount }} <i class="far fa-check-circle"
                                style="padding: 5px; font-size: 10px; color: green;"></i></span>
                    @endforeach
                </div>
                @if ($receipt->permission_status == 'deliverd' || $receipt->permission_status == null)
                    <button type="submit" name="receive_premission" class="btn btn-primary"
                        onclick="return confirm('Are you sure?');">أستلام الأذن</button>
                @elseif($receipt->permission_status == 'receive_premission')
                    @if($receipt->branch->payment_type == 'permissions_parts')
                        <button type="submit" name="permission_complete_2" class="btn btn-danger"
                            onclick="return confirm('Are you sure?');">تسليم الأذن</button>
                    @else
                        <button type="button" class="btn btn-info" onclick="add_income('{{ $receipt->id }}')">تجزئة
                            الأذن</button>
                        <button type="submit" name="permission_complete" class="btn btn-danger"
                            onclick="return confirm('Are you sure?');">صرف الأذن</button>
                    @endif
                @elseif($receipt->permission_status == 'permission_segment')
                    <button type="button" class="btn btn-info" onclick="add_income('{{ $receipt->id }}')">تكملة صرف
                        الأذن</button>
                @elseif($receipt->permission_status == 'permission_complete')
                    <span class="badge text-bg-light">تم صرف الأذن<i class="far fa-check-circle"
                            style="padding: 5px; font-size: 10px; color: green;"></i></span>
                @elseif($receipt->permission_status == 'permission_complete_2')
                    <span class="badge text-bg-light">تم تسليم الأذن<i class="far fa-check-circle"
                            style="padding: 5px; font-size: 10px; color: green;"></i></span>
                @endif
            </form>
        @endif
    @endif
@endif
