@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Email Logs</h3>
                    <div class="card-tools">
                        <form action="{{ route('email-logs.clear') }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete logs older than 30 days?');">
                            @csrf
                            <input type="hidden" name="days" value="30">
                            <button type="submit" class="btn btn-sm btn-danger">Clear Old Logs (30+ days)</button>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('custom_success'))
                        <div class="alert alert-success">{{ session('custom_success') }}</div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Recipient</th>
                                    <th>Subject</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Method</th>
                                    <th>Date/Time</th>
                                    <th>Error</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($logs as $log)
                                <tr class="{{ $log->status === 'failed' ? 'table-danger' : 'table-success' }}">
                                    <td>{{ $log->id }}</td>
                                    <td>{{ $log->recipient }}</td>
                                    <td>{{ $log->subject }}</td>
                                    <td><span class="badge badge-info">{{ strtoupper($log->type) }}</span></td>
                                    <td>
                                        @if($log->status === 'sent')
                                            <span class="badge badge-success">✓ SENT</span>
                                        @else
                                            <span class="badge badge-danger">✗ FAILED</span>
                                        @endif
                                    </td>
                                    <td><span class="badge badge-secondary">{{ strtoupper($log->method) }}</span></td>
                                    <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                                    <td>{{ $log->error ?? '-' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">No email logs found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $logs->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
