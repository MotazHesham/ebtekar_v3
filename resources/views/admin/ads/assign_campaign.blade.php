@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header">
        {{ trans('Unassigned Campaigns') }}
        <div class="float-right">
            <a class="btn btn-default btn-sm" href="{{ route('admin.ads.accounts.index') }}"><i class="fa fa-arrow-left"></i> {{ trans('Back to Accounts') }}</a>
            <a class="btn btn-success btn-sm" href="{{ route('admin.ads.accounts.details.create-standalone') }}"><i class="fa fa-plus"></i> {{ trans('Add Campaign') }}</a>
        </div>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>{{ trans('Name') }}</th>
                        <th>{{ trans('UTM Key') }}</th>
                        <th>{{ trans('Type') }}</th>
                        <th>{{ trans('Assign to Account') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($unassignedDetails ?? [] as $detail)
                        <tr>
                            <td>{{ $detail->name }}</td>
                            <td>{{ $detail->utm_key }}</td>
                            <td>{{ ucfirst($detail->type ?? '') }}</td>
                            <td>
                                <form class="form-inline" method="POST" action="{{ route('admin.ads.accounts.assign-detail') }}">
                                    @csrf
                                    <input type="hidden" name="detail_id" value="{{ $detail->id }}">
                                    <select class="form-control form-control-sm mr-2" name="account_id" required style="min-width: 180px;">
                                        <option value="">{{ trans('Select account') }}</option>
                                        @foreach($accounts ?? [] as $acc)
                                            <option value="{{ $acc->id }}">{{ $acc->name }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="btn btn-primary btn-sm">{{ trans('Assign') }}</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                {{ trans('No unassigned campaigns.') }}
                                <a href="{{ route('admin.ads.accounts.details.create-standalone') }}">{{ trans('Add one') }}</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
