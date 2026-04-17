@extends('admin/layouts.app')

@section('custom_css')
<style>
    .badge-enquiry  { background-color: #17a2b8; }
    .badge-otp      { background-color: #6f42c1; }
    .badge-both     { background-color: #28a745; }
    .status-active  { color: #28a745; font-weight: 600; }
    .status-inactive{ color: #dc3545; font-weight: 600; }
    .mail-status-box{ background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 6px; padding: 16px 20px; }
</style>
@endsection

@section('content')

{{-- Success / Error alerts --}}
@if(session('custom_success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i> {{ session('custom_success') }}
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>
@endif
@if(session('custom_error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle"></i> {{ session('custom_error') }}
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>
@endif
@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif

{{-- ===== EMAIL LIST ===== --}}
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-envelope"></i> Notification Email List</span>
    </div>
    <div class="card-body p-0">
        <table class="table table-bordered table-hover mb-0">
            <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>Email Address</th>
                    <th>Label</th>
                    <th>Receives</th>
                    <th>Status</th>
                    <th style="width:220px">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($emails as $i => $em)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td><strong>{{ $em->email }}</strong></td>
                    <td>{{ $em->label }}</td>
                    <td>
                        @if($em->type == 'enquiry')
                            <span class="badge badge-enquiry text-white px-2 py-1">Enquiry Only</span>
                        @elseif($em->type == 'otp')
                            <span class="badge badge-otp text-white px-2 py-1">OTP Only</span>
                        @else
                            <span class="badge badge-both text-white px-2 py-1">Enquiry + OTP</span>
                        @endif
                    </td>
                    <td>
                        @if($em->is_active)
                            <span class="status-active"><i class="fas fa-circle"></i> Active</span>
                        @else
                            <span class="status-inactive"><i class="fas fa-circle"></i> Inactive</span>
                        @endif
                    </td>
                    <td>
                        {{-- Toggle Status --}}
                        <a href="{{ route('email-settings.toggle', $em->id) }}"
                           class="btn btn-sm {{ $em->is_active ? 'btn-warning' : 'btn-success' }}"
                           onclick="return confirm('{{ $em->is_active ? 'Deactivate' : 'Activate' }} this email?')">
                            <i class="fas fa-{{ $em->is_active ? 'ban' : 'check' }}"></i>
                            {{ $em->is_active ? 'Deactivate' : 'Activate' }}
                        </a>

                        {{-- Edit --}}
                        <button class="btn btn-sm btn-info"
                            data-toggle="modal" data-target="#editModal{{ $em->id }}">
                            <i class="fas fa-edit"></i>
                        </button>

                        {{-- Delete --}}
                        <form action="{{ route('email-settings.destroy', $em->id) }}" method="POST" style="display:inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"
                                onclick="return confirm('Delete this email permanently?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>

                {{-- Edit Modal --}}
                <div class="modal fade" id="editModal{{ $em->id }}" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <form action="{{ route('email-settings.update', $em->id) }}" method="POST">
                                @csrf @method('PUT')
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Email</h5>
                                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Email Address <span class="text-danger">*</span></label>
                                        <input type="email" name="email" class="form-control" value="{{ $em->email }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Label <span class="text-danger">*</span></label>
                                        <input type="text" name="label" class="form-control" value="{{ $em->label }}" placeholder="e.g. Admin Email" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Receives <span class="text-danger">*</span></label>
                                        <select name="type" class="form-control">
                                            <option value="both"    {{ $em->type=='both'    ? 'selected' : '' }}>Enquiry + OTP</option>
                                            <option value="enquiry" {{ $em->type=='enquiry' ? 'selected' : '' }}>Enquiry Only</option>
                                            <option value="otp"     {{ $em->type=='otp'     ? 'selected' : '' }}>OTP Only</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                {{-- /Edit Modal --}}

                @empty
                <tr><td colspan="6" class="text-center text-muted py-3">No emails added yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="row">

    {{-- ===== ADD NEW EMAIL ===== --}}
    <div class="col-md-5">
        <div class="card mb-4">
            <div class="card-header"><i class="fas fa-plus-circle"></i> Add New Email</div>
            <div class="card-body">
                <form action="{{ route('email-settings.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Email Address <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" placeholder="example@gmail.com" required>
                    </div>
                    <div class="form-group">
                        <label>Label <span class="text-danger">*</span></label>
                        <input type="text" name="label" class="form-control" placeholder="e.g. Enquiry Email" required>
                    </div>
                    <div class="form-group">
                        <label>Receives <span class="text-danger">*</span></label>
                        <select name="type" class="form-control">
                            <option value="both">Enquiry + OTP</option>
                            <option value="enquiry">Enquiry Only</option>
                            <option value="otp">OTP Only</option>
                        </select>
                        <small class="text-muted">
                            <b>Enquiry Only</b> – contact form & enquiry modal mails<br>
                            <b>OTP Only</b> – admin login OTP mails<br>
                            <b>Enquiry + OTP</b> – both types
                        </small>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-plus"></i> Add Email
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- ===== TEST MAIL ===== --}}
    <div class="col-md-7">
        <div class="card mb-4">
            <div class="card-header"><i class="fas fa-paper-plane"></i> Test Mail Status</div>
            <div class="card-body">
                <p class="text-muted mb-3">Send a test email to verify your SMTP configuration is working correctly.</p>
                <form action="{{ route('email-settings.test-mail') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Send Test Email To <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="email" name="test_email" class="form-control"
                                placeholder="Enter any email to test" required>
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-paper-plane"></i> Send Test
                                </button>
                            </div>
                        </div>
                        <small class="text-muted">A test email will be sent to verify SMTP is working.</small>
                    </div>
                </form>

                <hr>

                {{-- Current SMTP Config Info --}}
                <div class="mail-status-box">
                    <h6 class="mb-3"><i class="fas fa-cog"></i> Current Mail Configuration</h6>
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td class="text-muted" style="width:140px">Driver</td>
                            <td>
                                <span class="badge {{ config('mail.default') == 'smtp' ? 'badge-success' : 'badge-warning' }} text-white">
                                    {{ strtoupper(config('mail.default')) }}
                                </span>
                                @if(config('mail.default') != 'smtp')
                                    <small class="text-warning ml-1"><i class="fas fa-exclamation-triangle"></i> Emails not being sent (log/null mode)</small>
                                @else
                                    <small class="text-success ml-1"><i class="fas fa-check-circle"></i> Live SMTP active</small>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Host</td>
                            <td>{{ config('mail.mailers.smtp.host') ?: 'Not set' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Port</td>
                            <td>{{ config('mail.mailers.smtp.port') ?: 'Not set' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">From Email</td>
                            <td>{{ config('mail.from.address') ?: 'Not set' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Encryption</td>
                            <td>{{ config('mail.mailers.smtp.encryption') ?: 'None' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Active Emails</td>
                            <td><strong>{{ $emails->where('is_active', 1)->count() }}</strong> of {{ $emails->count() }} total</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection

@section('custom_js')
@endsection
