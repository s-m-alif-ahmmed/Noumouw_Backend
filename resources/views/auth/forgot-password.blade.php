@extends('auth.app', ['title' => 'Forgot Password'])

@section('content')
<form method="POST" action="{{ route('password.email') }}">
    @csrf
    <div class="form-group">
        <input type="text" class="form-control" placeholder="Enter your username" name="email" value="{{ old('email') }}">
        @error('email')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div><!-- form-group -->
    <button type="submit" class="btn btn-info btn-block">Send Password Reset Link</button>
</form>
@endsection