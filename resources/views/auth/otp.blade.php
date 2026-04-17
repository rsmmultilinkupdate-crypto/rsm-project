<!-- resources/views/auth/otp.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">OTP Verification</div>

                <form method="POST" action="{{ route('otp.verify') }}">
					@csrf
					<label for="otp">OTP:</label>
					<input type="text" name="otp" required>
					<button type="submit">Verify OTP</button>
				</form>
				@if($errors->any())
					<div>
						@foreach($errors->all() as $error)
							<p>{{ $error }}</p>
						@endforeach
					</div>
				@endif

            </div>
        </div>
    </div>
</div>
@endsection
