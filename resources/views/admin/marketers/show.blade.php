@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            View Marketer
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>ID</th>
                        <td>{{ $marketer->id }}</td>
                    </tr>
                    <tr>
                        <th>Name</th>
                        <td>{{ $marketer->name }}</td>
                    </tr>
                    <tr>
                        <th>Referral Code</th>
                        <td>{{ $marketer->code }}</td>
                    </tr>
                    <tr>
                        <th>Referral URL</th>
                        <td>
                            @php
                                $quickReferral = ($marketer->website && $marketer->website->url)
                                    ? rtrim($marketer->website->url, '/') . '/?ref=' . $marketer->code
                                    : url('/?ref=' . $marketer->code);
                            @endphp
                            <a href="{{ $quickReferral }}" target="_blank">{{ $quickReferral }}</a>
                        </td>
                    </tr>
                    <tr>
                        <th>Commission Rate</th>
                        <td>{{ $marketer->commission_rate }}%</td>
                    </tr>
                    <tr>
                        <th>User</th>
                        <td>{{ $marketer->user->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Website</th>
                        <td>{{ $marketer->website->site_name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>{{ $marketer->is_active ? 'Active' : 'Inactive' }}</td>
                    </tr>
                </tbody>
            </table>
            <a class="btn btn-default" href="{{ route('admin.marketers.index') }}">Back to list</a>
        </div>
    </div>
@endsection
