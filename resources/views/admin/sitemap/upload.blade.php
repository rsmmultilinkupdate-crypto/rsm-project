@extends('admin/layouts.app')

@section('custom_css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/select/1.2.5/css/select.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.1/css/buttons.dataTables.min.css">
@endsection

@section('content')
<div class="card">
    <div class="card-header">Upload Sitemap</div>

    <div class="card-body">
        @if (session('success'))
        <div>
            {{ session('success') }}
            <br>
            <a href="/sitemap.xml">View Sitemap</a>
        </div>
    @endif

    @if ($errors->any())
        <div>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('sitemap.upload') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div>
            <label for="sitemap">Upload Sitemap:</label>
            <input type="file" name="sitemap" id="sitemap" required>
        </div>
        <div>
            <button type="submit">Upload</button>
        </div>
    </form>
	</div>
</div>


@endsection


