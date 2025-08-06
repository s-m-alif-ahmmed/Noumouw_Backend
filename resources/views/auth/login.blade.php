@extends('auth.layout',['title'=>'Login'])
@section('content')
    <div class="!px-10 !py-12 card-body">
        <a href="#!">
            <img src="{{asset('logo.png')}}" alt="logo" class="h-14 mx-auto">
        </a>

        <div class="mt-6 text-center">
            <h4 class="mb-1 text-custom-500 dark:text-custom-500">Welcome Back !</h4>
            <p class="text-slate-500 dark:text-zink-200">Sign in to continue to {{config('app.name')}}.</p>
        </div>

        <form action="{{route('login')}}" method="POST" class="mt-7" id="signInForm">@csrf
            <div class="mb-3">
                <label for="email" class="inline-block mb-2 text-base font-medium">Email ID</label>
                <input type="email" id="email" name="email"  class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200" placeholder="Enter your email">
                @error('email')
                    <div id="username-error" class="mt-1 text-sm text-red-500">{{$message}}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="password" class="inline-block mb-2 text-base font-medium">Password</label>
                <input type="password" id="password" name="password" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200" placeholder="Enter your password">
                @error('password')
                   <div id="password-error" class="mt-1 text-sm text-red-500">{{$message}}</div>
                @enderror
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <input name="remember" id="remember" class="border rounded-sm appearance-none size-4 bg-slate-100 border-slate-200 dark:bg-zink-600 dark:border-zink-500 checked:bg-custom-500 checked:border-custom-500 dark:checked:bg-custom-500 dark:checked:border-custom-500 checked:disabled:bg-custom-400 checked:disabled:border-custom-400" type="checkbox">
                    <label for="remember" class="inline-block text-base font-medium align-middle cursor-pointer">Remember me</label>
                </div>
                @error('remember')
                    <div id="remember-error" class="mt-1 text-sm text-red-500">{{$message}}</div>
                @enderror
            </div>
            <div class="mt-10">
                <button type="submit" class="w-full text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">Sign In</button>
            </div>
        </form>
    </div>
@endsection
