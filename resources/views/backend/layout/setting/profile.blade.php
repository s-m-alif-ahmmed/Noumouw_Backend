@extends('backend.app')

@section('title', 'Settings')
@section('title_url')
    <a href="{{ route('setting.system.index') }}">Settings</a>
@endsection
@section('tabName')
    Profile
@endsection

@section('content')
    <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">

        <div class="card">
            <div class="card-body">
                <div class="grid grid-cols-1 gap-5 lg:grid-cols-12 2xl:grid-cols-12">
                    <div class="lg:col-span-2 2xl:col-span-1">

                        <div
                            class="relative inline-block rounded-full shadow-md size-20 bg-slate-100 profile-user xl:size-28">
                            <form action="{{ route('setting.profile.picture') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                {{-- profile image here start-------------- --}}
                                <img  src="{{ asset(auth()->user()->avatar ? auth()->user()->avatar : './backend/images/user.png') }}"
                                    alt=""
                                    class="object-cover border-0 rounded-full img-thumbnail user-profile-image">
                                <div
                                    class="absolute bottom-0 flex items-center justify-center rounded-full size-8 ltr:right-0 rtl:left-0 profile-photo-edit">
                                    <input id="profile-img-file-input" type="file" name="profile_picture"
                                        class="hidden profile-img-file-input">
                                    <label for="profile-img-file-input"
                                        class="flex items-center justify-center bg-white rounded-full shadow-lg cursor-pointer size-8 dark:bg-zink-600 profile-photo-edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" data-lucide="image-plus"
                                            class="lucide lucide-image-plus size-4 text-slate-500 dark:text-zink-200 fill-slate-100 dark:fill-zink-500">
                                            <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h7"></path>
                                            <line x1="16" x2="22" y1="5" y2="5"></line>
                                            <line x1="19" x2="19" y1="2" y2="8"></line>
                                            <circle cx="9" cy="9" r="2"></circle>
                                            <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"></path>
                                        </svg>
                                    </label>
                                </div>
                                <div class="flex justify-start mt-10 gap-x-4">
                                    <button type="submit"
                                    class="text-white  btn border-custom-500 hover:text-white focus:text-white active:text-white
                                           focus:ring focus:ring-custom-100 active:ring active:ring-custom-100
                                           dark:ring-custom-400/20 px-2 py-1 text-sm"
                                    style="background-color: #aec671; border-color: #aec671; hover:bg-[#9dbb63];">
                                    Update
                                </button>


                                </div>
                            </form>
                            {{-- profile image here End-------------- --}}
                        </div>
                    </div><!--end col-->
                    <div class="lg:col-span-10 2xl:col-span-9">
                        <h5 class="mb-1">{{ auth()->user()->name }} <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" data-lucide="badge-check"
                                class="lucide lucide-badge-check inline-block size-4 text-sky-500 fill-sky-100 dark:fill-custom-500/20">
                                <path
                                    d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z">
                                </path>
                                <path d="m9 12 2 2 4-4"></path>
                            </svg></h5>
                        <div class="flex gap-3 mb-4">
                            <p class="text-slate-500 dark:text-zink-200"><svg xmlns="http://www.w3.org/2000/svg"
                                    width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    data-lucide="user-circle"
                                    class="lucide lucide-user-circle inline-block size-4 ltr:mr-1 rtl:ml-1 text-slate-500 dark:text-zink-200 fill-slate-100 dark:fill-zink-500">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <circle cx="12" cy="10" r="3"></circle>
                                    <path d="M7 20.662V19a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v1.662"></path>
                                </svg>{{ ucfirst(auth()->user()->role) }}</p>
                        </div>
                    </div>
                </div><!--end grid-->
            </div>

            {{-- -----------------changing tab profile or pass change option----------------------  --}}
            <div class="card-body !py-0">
                <ul class="flex flex-wrap w-full text-sm font-medium text-center nav-tabs">
                    <li class="group active">
                        <a href="javascript:void(0);" data-tab-toggle="" data-target="personalTabs"
                            class="inline-block px-4 py-2 text-base transition-all duration-300 ease-linear rounded-t-md text-slate-500 dark:text-zink-200 border-b border-transparent group-[.active]:text-custom-500 dark:group-[.active]:text-custom-500 group-[.active]:border-b-custom-500 hover:text-custom-500 dark:hover:text-custom-500 active:text-custom-500 dark:active:text-custom-500 -mb-[1px]">Personal
                            Info</a>
                    </li>
                    <li class="group">
                        <a href="javascript:void(0);" data-tab-toggle="" data-target="changePasswordTabs"
                            class="inline-block px-4 py-2 text-base transition-all duration-300 ease-linear rounded-t-md text-slate-500 dark:text-zink-200 border-b border-transparent group-[.active]:text-custom-500 dark:group-[.active]:text-custom-500 group-[.active]:border-b-custom-500 hover:text-custom-500 dark:hover:text-custom-500 active:text-custom-500 dark:active:text-custom-500 -mb-[1px]">Change
                            Password</a>
                    </li>
                </ul>
            </div>
        </div><!--end card-->

        <div class="tab-content">
            <div class="tab-pane block" id="personalTabs">
                <div class="card">
                    <div class="card-body">
                        <h6 class="mb-1 text-15">My Profile</h6>
                        <p class="mb-4 text-slate-500 dark:text-zink-200">Update your photo and personal details here
                            easily.</p>
                        <form action="{{ route('setting.profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="grid grid-cols-1 gap-5 xl:grid-cols-12">
                                {{-- -------------------Full name input field-------------  --}}
                                <div class="xl:col-span-6">
                                    <label for="full_name" class="inline-block mb-2 text-base font-medium">Full
                                        Name</label>
                                    <input type="text" name="full_name" id="full_name"
                                        class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                        placeholder="Enter your value" value="{{ old('full_name', $user->name ?? '') }}">
                                    @error('full_name')
                                        <div style="color: red">{{ $message }}</div>
                                    @enderror
                                </div><!--end col-->

{{--                                --}}{{-- -------------------User Name input field-------------  --}}
{{--                                <div class="xl:col-span-6">--}}
{{--                                    <label for="user_name" class="inline-block mb-2 text-base font-medium">User--}}
{{--                                        Name</label>--}}
{{--                                    <input type="text" name="user_name" id="user_name"--}}
{{--                                        class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"--}}
{{--                                        placeholder="Enter your User Name"--}}
{{--                                        value="{{ old('user_name', $user->user_name ?? '') }}">--}}
{{--                                    @error('user_name')--}}
{{--                                        <div style="color: red">{{ $message }}</div>--}}
{{--                                    @enderror--}}
{{--                                </div><!--end col-->--}}

{{--                                --}}{{-- -------------------Phone number input field-------------  --}}
{{--                                <div class="xl:col-span-6">--}}
{{--                                    <label for="phone_number" class="inline-block mb-2 text-base font-medium">Phone--}}
{{--                                        Number</label>--}}
{{--                                    <input type="text" name="phone_number" id="phone_number"--}}
{{--                                        class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"--}}
{{--                                        placeholder="+214 8456 8459 23"--}}
{{--                                        value="{{ old('phone_number', $user->phone ?? '') }}">--}}
{{--                                    @error('phone_number')--}}
{{--                                        <div style="color: red">{{ $message }}</div>--}}
{{--                                    @enderror--}}
{{--                                </div><!--end col-->--}}

                                {{-- -------------------Email input field-------------  --}}
                                <div class="xl:col-span-6">
                                    <label for="email" class="inline-block mb-2 text-base font-medium">Email
                                        Address</label>
                                    <input type="email" name="email" id="email"
                                        class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                        placeholder="Enter your email address"
                                        value="{{ old('email', $user->email ?? '') }}">
                                    @error('email')
                                        <div style="color: red">{{ $message }}</div>
                                    @enderror
                                </div><!--end col-->

                            </div><!--end grid-->

                            <div class="flex justify-start mt-6 gap-x-4">
                                <button type="submit"
                                    class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">
                                    Update
                                </button>
                            </div>
                        </form><!--end form-->

                    </div>
                </div>
            </div>

            <div class="tab-pane hidden" id="changePasswordTabs">
                <div class="card">
                    <div class="card-body">
                        <h6 class="mb-4 text-15">Changes Password</h6>
                        <form action="{{ route('setting.profile.password') }}" method="POST"> @csrf
                            <div class="grid grid-cols-1 gap-5 xl:grid-cols-12">
                                <div class="xl:col-span-4">
                                    <label for="old_password" class="inline-block mb-2 text-base font-medium">Old
                                        Password*</label>
                                    <div class="relative">
                                        <input type="password" name="old_password"
                                            class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                            id="old_password" placeholder="Enter current password">
                                    </div>
                                    @error('old_password')
                                        <div style="color: red">{{ $message }}</div>
                                    @enderror
                                </div><!--end col-->
                                <div class="xl:col-span-4">
                                    <label for="new_password" class="inline-block mb-2 text-base font-medium">New
                                        Password*</label>
                                    <div class="relative">
                                        <input type="password" name="new_password"
                                            class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                            id="new_password" placeholder="Enter new password">
                                    </div>
                                    @error('new_password')
                                        <div style="color: red">{{ $message }}</div>
                                    @enderror
                                </div><!--end col-->
                                <div class="xl:col-span-4">
                                    <label for="new_password_confirmation"
                                        class="inline-block mb-2 text-base font-medium">Confirm Password*</label>
                                    <div class="relative">
                                        <input type="password" name="new_password_confirmation"
                                            class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                            id="new_password_confirmation" placeholder="Confirm password">
                                    </div>
                                </div><!--end col-->

                                <div class="flex justify-start xl:col-span-6">
                                    <button type="submit"
                                        class="text-white bg-green-500 border-green-500 btn hover:text-white hover:bg-green-600 hover:border-green-600 focus:text-white focus:bg-green-600 focus:border-green-600 focus:ring focus:ring-green-100 active:text-white active:bg-green-600 active:border-green-600 active:ring active:ring-green-100 dark:ring-green-400/10">Change
                                        Password</button>
                                </div>
                            </div><!--end grid-->
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @push('scripts')
        <script src="{{ asset('backend/js/pages/pages-account-setting.init.js') }}"></script>
    @endpush
