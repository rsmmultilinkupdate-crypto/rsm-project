@extends('admin/layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>Email Recipients Management</span>
        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addEmailModal">
            <i class="fa fa-plus"></i> Add Email
        </button>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif

        <div class="alert alert-info">
            <strong>Info:</strong> All active emails will receive enquiry and contact form submissions.
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Email</th>
                        <th>Name/Label</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recipients as $recipient)
                    <tr>
                        <td>{{ $recipient->email }}</td>
                        <td>{{ $recipient->name ?? 'N/A' }}</td>
                        <td>
                            @if($recipient->is_active)
                                <span class="badge badge-success">Active</span>
                            @else
                                <span class="badge badge-secondary">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#editEmailModal{{ $recipient->id }}">
                                <i class="fa fa-edit"></i> Edit
                            </button>
                            <a href="{{ route('email-recipients.toggle', $recipient->id) }}" 
                               class="btn btn-sm btn-{{ $recipient->is_active ? 'warning' : 'success' }}"
                               onclick="return confirm('Are you sure?')">
                                <i class="fa fa-power-off"></i> {{ $recipient->is_active ? 'Deactivate' : 'Activate' }}
                            </a>
                            <form action="{{ route('email-recipients.destroy', $recipient->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this email?')">
                                    <i class="fa fa-trash"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="editEmailModal{{ $recipient->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route('email-recipients.update', $recipient->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Email Recipient</h5>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label>Email Address *</label>
                                            <input type="email" name="email" class="form-control" value="{{ $recipient->email }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Name/Label</label>
                                            <input type="text" name="name" class="form-control" value="{{ $recipient->name }}" placeholder="e.g., Admin Email">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Update</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">No email recipients found. Add one to start receiving enquiries.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Email Modal -->
<div class="modal fade" id="addEmailModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('email-recipients.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Email Recipient</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Email Address *</label>
                        <input type="email" name="email" class="form-control" required placeholder="email@example.com">
                    </div>
                    <div class="form-group">
                        <label>Name/Label</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g., Sales Team">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Email</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
