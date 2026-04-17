@extends('admin/layouts.app')

@section('custom_css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/select/1.2.5/css/select.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.1/css/buttons.dataTables.min.css">
@endsection

@section('content')
<div class="card">
    <div class="card-header">All Enquiries</div>

    <div class="card-body">
        <div class="table-responsive">
            <table id="dataTable" class="table table-striped table-hover table-bordered" width="100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Product Name</th>
                        <th>Message</th>
                        <th>Email Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
				@foreach($data as $enquery)
				<tr>
                <td>{{$enquery->id}}</td>
				<td>{{$enquery->name}}</td>
				<td>{{$enquery->email}}</td>
				<td>{{$enquery->phone}}</td>
				<td>{{$enquery->product_name ?? 'N/A'}}</td>
				<td>{{Str::limit($enquery->message, 50)}}</td>
				<td>
                    <span class="badge badge-{{$enquery->status_badge}}">
                        {{$enquery->status_label}}
                    </span>
                    @if($enquery->status == 'sent')
                        <br><small class="text-muted">Sent to: {{ implode(', ', $activeEmails) }}</small>
                    @endif
                    @if($enquery->status == 'failed' && $enquery->email_error)
                        <br><small class="text-danger" title="{{$enquery->email_error}}">Error: {{Str::limit($enquery->email_error, 30)}}</small>
                    @endif
                </td>
				<td>{{$enquery->created_at->format('d M Y, h:i A')}}</td>
				</tr>
				@endforeach 
				
                </tbody>
               
            </table>
        </div>
    </div>
</div>

<form action="#" method="post" id="deletItemForm" display: none;>
    @csrf
    {{ method_field('DELETE') }}
</form>
@endsection


