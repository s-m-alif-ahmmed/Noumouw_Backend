@extends('backend.app')

@section('title','Settings')
@section('title_url')
<a href="{{ route('setting.system.index') }}">Settings</a>
@endsection
@section('tabName')
Configuration
@endsection

@section('content')
    <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">

        <div class="card">
            {{-- -----------------changing tab profile or pass change option----------------------  --}}
            <div class="card-body !py-0">
                <ul class="flex flex-wrap w-full text-sm font-medium text-center nav-tabs">
                    <li class="group active">
                        <a href="javascript:void(0);" data-tab-toggle="" data-target="mailTabs"
                            class="inline-block px-4 py-2 text-base transition-all duration-300 ease-linear rounded-t-md text-slate-500 dark:text-zink-200 border-b border-transparent group-[.active]:text-custom-500 dark:group-[.active]:text-custom-500 group-[.active]:border-b-custom-500 hover:text-custom-500 dark:hover:text-custom-500 active:text-custom-500 dark:active:text-custom-500 -mb-[1px]">Mail
                            Configuration</a>
                    </li>
                    <li class="group">
                        <a href="javascript:void(0);" data-tab-toggle="" data-target="paymentTabs"
                            class="inline-block px-4 py-2 text-base transition-all duration-300 ease-linear rounded-t-md text-slate-500 dark:text-zink-200 border-b border-transparent group-[.active]:text-custom-500 dark:group-[.active]:text-custom-500 group-[.active]:border-b-custom-500 hover:text-custom-500 dark:hover:text-custom-500 active:text-custom-500 dark:active:text-custom-500 -mb-[1px]">Payment
                            Configuration</a>
                    </li>
                    <li class="group">
                        <a href="javascript:void(0);" data-tab-toggle="" data-target="socialLoginTabs"
                            class="inline-block px-4 py-2 text-base transition-all duration-300 ease-linear rounded-t-md text-slate-500 dark:text-zink-200 border-b border-transparent group-[.active]:text-custom-500 dark:group-[.active]:text-custom-500 group-[.active]:border-b-custom-500 hover:text-custom-500 dark:hover:text-custom-500 active:text-custom-500 dark:active:text-custom-500 -mb-[1px]">Social
                            Login Configuration</a>
                    </li>
                </ul>
            </div>
        </div><!--end card-->

        <div class="tab-content">
            {{-- ------------------- Mail Information Tab ------------- --}}
            <div class="tab-pane block" id="mailTabs">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('setting.configuration.mail') }}" method="POST">
                            @csrf
                            <div class="grid grid-cols-1 gap-5 xl:grid-cols-12">

                                <div class="xl:col-span-6">
                                    <label for="mail_mailer" class="inline-block mb-2 text-base font-medium">Mail
                                        Mailer</label>
                                    <input type="text" name="mail_mailer" id="mail_mailer"
                                        class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                        placeholder="Enter Mail Mailer address"
                                        value="{{ old('mail_mailer', env('MAIL_MAILER')) }}">
                                    @error('mail_mailer')
                                        <div style="color: red">{{ $message }}</div>
                                    @enderror
                                </div><!--end col-->

                                <div class="xl:col-span-6">
                                    <label for="mail_host" class="inline-block mb-2 text-base font-medium">MAIL HOST</label>
                                    <input type="text" name="mail_host" id="mail_host"
                                        class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                        placeholder="Enter MAIL HOST" value="{{ old('mail_host', env('MAIL_HOST')) }}">
                                    @error('mail_host')
                                        <div style="color: red">{{ $message }}</div>
                                    @enderror
                                </div><!--end col-->

                                <div class="xl:col-span-6">
                                    <label for="mail_port" class="inline-block mb-2 text-base font-medium">MAIL PORT</label>
                                    <input type="text" name="mail_port" id="mail_port"
                                        class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                        placeholder="Enter MAIL PORT" value="{{ old('mail_port', env('MAIL_PORT')) }}">
                                    @error('mail_port')
                                        <div style="color: red">{{ $message }}</div>
                                    @enderror
                                </div><!--end col-->

                                <div class="xl:col-span-6">
                                    <label for="mail_username" class="inline-block mb-2 text-base font-medium">MAIL USER
                                        NAME</label>
                                    <input type="text" name="mail_username" id="mail_username"
                                        class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                        placeholder="Enter MAIL USER NAME"
                                        value="{{ old('mail_username', env('MAIL_USERNAME')) }}">
                                    @error('mail_username')
                                        <div style="color: red">{{ $message }}</div>
                                    @enderror
                                </div><!--end col-->

                                <div class="xl:col-span-6">
                                    <label for="mail_password" class="inline-block mb-2 text-base font-medium">Mail
                                        Password</label>
                                    <input type="password" name="mail_password" id="mail_password"
                                        class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                        placeholder="Enter MAIL PASSWORD" value="{{ old('mail_password') }}">
                                    @error('mail_password')
                                        <div style="color: red">{{ $message }}</div>
                                    @enderror
                                </div><!--end col-->

                                <div class="xl:col-span-6">
                                    <label for="mail_encryption" class="inline-block mb-2 text-base font-medium">Mail
                                        Encryption</label>
                                    <input type="text" name="mail_encryption" id="mail_encryption"
                                        class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                        placeholder="Enter encryption method"
                                        value="{{ old('mail_encryption', env('MAIL_ENCRYPTION')) }}">
                                    @error('mail_encryption')
                                        <div style="color: red">{{ $message }}</div>
                                    @enderror
                                </div><!--end col-->

                                <div class="xl:col-span-6">
                                    <label for="mail_from_address" class="inline-block mb-2 text-base font-medium">MAIL FROM
                                        Address</label>
                                    <input type="text" name="mail_from_address" id="mail_from_address"
                                        class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                        placeholder="Enter mail from address"
                                        value="{{ old('mail_from_address', env('MAIL_FROM_ADDRESS')) }}">
                                    @error('mail_from_address')
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

            {{-- ------------------- Payments Information Tab ------------- --}}
            <div class="tab-pane hidden" id="paymentTabs">
                <div class="card">
                    <div class="card-body">
                        <h6 class="mb-4 text-15">Payment</h6>
                        <form action="{{route('setting.configuration.payment')}}" method="POST">
                            @csrf
                            <div class="grid grid-cols-1 gap-5 xl:grid-cols-12">

                                <div class="xl:col-span-6">
                                    <label for="stripe_key" class="inline-block mb-2 text-base font-medium">STRIPE SECRET KEY</label>
                                    <input type="text" name="stripe_key" id="stripe_key"
                                        class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                        placeholder="Enter STRIPE KEY address"
                                        value="{{ old('stripe_key', env('STRIPE_SECRET_KEY')) }}">
                                    @error('stripe_key')
                                        <div style="color: red">{{ $message }}</div>
                                    @enderror
                                </div><!--end col-->

                                <div class="xl:col-span-6">
                                    <label for="stripe_secret" class="inline-block mb-2 text-base font-medium">STRIPE PUBLIC KEY</label>
                                    <input type="text" name="stripe_secret" id="stripe_secret"
                                        class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                        placeholder="Enter STRIPE KEY address"
                                        value="{{ old('stripe_secret', env('STRIPE_PUBLIC_KEY')) }}">
                                    @error('stripe_secret')
                                        <div style="color: red">{{ $message }}</div>
                                    @enderror
                                </div><!--end col-->
                                <div class="xl:col-span-6">
                                    <label for="stripe_webhook_secret" class="inline-block mb-2 text-base font-medium">STRIPE WEBHOOK SECRET</label>
                                    <input type="text" name="stripe_webhook_secret" id="stripe_webhook_secret"
                                        class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                        placeholder="Enter Webhook secret address"
                                        value="{{ old('stripe_webhook_secret', env('STRIPE_WEBHOOK_SECRET')) }}">
                                    @error('stripe_webhook_secret')
                                        <div style="color: red">{{ $message }}</div>
                                    @enderror
                                </div><!--end col-->

                            </div><!--end grid-->

                            <div class="flex justify-end mt-6 gap-x-4">
                                <button type="submit"
                                    class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">
                                    Update
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- ------------------- Mail Information Tab ------------- --}}
            <div class="tab-pane hidden" id="socialLoginTabs">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('setting.configuration.social') }}" method="POST">
                            @csrf
                            <div class="grid grid-cols-1 gap-5 xl:grid-cols-12">

                                <!-- Google Credentials -->
                                <div class="xl:col-span-4">
                                    <label for="google_client_id" class="inline-block mb-2 text-base font-medium">Google Client ID</label>
                                    <input type="text" name="google_client_id" id="google_client_id"
                                        class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                        placeholder="Enter Google Client ID"
                                        value="{{ old('google_client_id', env('GOOGLE_CLIENT_ID')) }}">
                                    @error('google_client_id')
                                        <div style="color: red">{{ $message }}</div>
                                    @enderror
                                </div><!--end col-->

                                <div class="xl:col-span-4">
                                    <label for="google_client_secret" class="inline-block mb-2 text-base font-medium">Google Client Secret</label>
                                    <input type="text" name="google_client_secret" id="google_client_secret"
                                        class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                        placeholder="Enter Google Client Secret"
                                        value="{{ old('google_client_secret', env('GOOGLE_CLIENT_SECRET')) }}">
                                    @error('google_client_secret')
                                        <div style="color: red">{{ $message }}</div>
                                    @enderror
                                </div><!--end col-->

                                <div class="xl:col-span-4">
                                    <label for="google_redirect_uri" class="inline-block mb-2 text-base font-medium">Google Redirect URI</label>
                                    <input type="text" name="google_redirect_uri" id="google_redirect_uri"
                                        class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                        placeholder="Enter Google Redirect URI"
                                        value="{{ old('google_redirect_uri', env('GOOGLE_REDIRECT_URI')) }}">
                                    @error('google_redirect_uri')
                                        <div style="color: red">{{ $message }}</div>
                                    @enderror
                                </div><!--end col-->

                                <!-- Facebook Credentials -->
                                <div class="xl:col-span-4">
                                    <label for="facebook_client_id" class="inline-block mb-2 text-base font-medium">Facebook Client ID</label>
                                    <input type="text" name="facebook_client_id" id="facebook_client_id"
                                        class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                        placeholder="Enter Facebook Client ID"
                                        value="{{ old('facebook_client_id', env('FACEBOOK_CLIENT_ID')) }}">
                                    @error('facebook_client_id')
                                        <div style="color: red">{{ $message }}</div>
                                    @enderror
                                </div><!--end col-->

                                <div class="xl:col-span-4">
                                    <label for="facebook_client_secret" class="inline-block mb-2 text-base font-medium">Facebook Client Secret</label>
                                    <input type="text" name="facebook_client_secret" id="facebook_client_secret"
                                        class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                        placeholder="Enter Facebook Client Secret"
                                        value="{{ old('facebook_client_secret', env('FACEBOOK_CLIENT_SECRET')) }}">
                                    @error('facebook_client_secret')
                                        <div style="color: red">{{ $message }}</div>
                                    @enderror
                                </div><!--end col-->

                                <div class="xl:col-span-4">
                                    <label for="facebook_redirect_uri" class="inline-block mb-2 text-base font-medium">Facebook Redirect URI</label>
                                    <input type="text" name="facebook_redirect_uri" id="facebook_redirect_uri"
                                        class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                        placeholder="Enter Facebook Redirect URI"
                                        value="{{ old('facebook_redirect_uri', env('FACEBOOK_REDIRECT_URI')) }}">
                                    @error('facebook_redirect_uri')
                                        <div style="color: red">{{ $message }}</div>
                                    @enderror
                                </div><!--end col-->

                                <!-- Apple Credentials -->
                                <div class="xl:col-span-4">
                                    <label for="apple_client_id" class="inline-block mb-2 text-base font-medium">Apple Client ID</label>
                                    <input type="text" name="apple_client_id" id="apple_client_id"
                                        class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                        placeholder="Enter Apple Client ID"
                                        value="{{ old('apple_client_id', env('APPLE_CLIENT_ID')) }}">
                                    @error('apple_client_id')
                                        <div style="color: red">{{ $message }}</div>
                                    @enderror
                                </div><!--end col-->

                                <div class="xl:col-span-4">
                                    <label for="apple_client_secret" class="inline-block mb-2 text-base font-medium">Apple Client Secret</label>
                                    <input type="text" name="apple_client_secret" id="apple_client_secret"
                                        class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                        placeholder="Enter Apple Client Secret"
                                        value="{{ old('apple_client_secret', env('APPLE_CLIENT_SECRET')) }}">
                                    @error('apple_client_secret')
                                        <div style="color: red">{{ $message }}</div>
                                    @enderror
                                </div><!--end col-->

                                <div class="xl:col-span-4">
                                    <label for="apple_redirect_uri" class="inline-block mb-2 text-base font-medium">Apple Redirect URI</label>
                                    <input type="text" name="apple_redirect_uri" id="apple_redirect_uri"
                                        class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                        placeholder="Enter Apple Redirect URI"
                                        value="{{ old('apple_redirect_uri', env('APPLE_REDIRECT_URI')) }}">
                                    @error('apple_redirect_uri')
                                        <div style="color: red">{{ $message }}</div>
                                    @enderror
                                </div><!--end col-->

                            </div><!--end grid-->

                            <div class="flex justify-end mt-6 gap-x-4">
                                <button type="submit"
                                    class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">
                                    Submit
                                </button>
                            </div>
                        </form><!--end form-->


                    </div>
                </div>
            </div>


        </div>

    @endsection

    @push('scripts')
        <script src="{{ asset('backend') }}/js/pages/pages-account-setting.init.js"></script>
    @endpush
