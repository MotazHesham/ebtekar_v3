@if ($receipt->branch)
    @if ($receipt->branch->payment_type == 'permissions' && $receipt->done)
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
                <button type="button" class="btn btn-info" onclick="add_income('{{ $receipt->id }}')">تجزئة
                    الأذن</button>
                <button type="submit" name="permission_complete" class="btn btn-danger"
                    onclick="return confirm('Are you sure?');">صرف الأذن</button>
            @elseif($receipt->permission_status == 'permission_segment')
                <button type="button" class="btn btn-info" onclick="add_income('{{ $receipt->id }}')">تكملة صرف
                    الأذن</button>
            @elseif($receipt->permission_status == 'permission_complete')
                <span class="badge text-bg-light">تم صرف الأذن<i class="far fa-check-circle"
                        style="padding: 5px; font-size: 10px; color: green;"></i></span>
            @endif
        </form>
    @endif
@endif
