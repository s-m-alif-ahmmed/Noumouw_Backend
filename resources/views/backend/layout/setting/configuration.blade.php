@extends('backend.app')

@section('title', 'System Configuration')
@section('title_url')
    <a href="{{ route('setting.system.index') }}">Settings</a>
@endsection
@section('tabName')
    Configuration
@endsection

@push('styles')
    <style>
        .premium-card {
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.04), 0 4px 6px -2px rgba(0, 0, 0, 0.02);
            border: 1px solid #f1f5f9;
        }

        /* Modern Inputs */
        .modern-input {
            width: 100%;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            padding: 12px 16px;
            outline: none;
            transition: all 0.3s;
        }

        .modern-input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
            background: #fff;
        }

        /* Form Buttons */
        .btn-custom {
            background-color: #3b82f6 !important;
            color: white !important;
            transition: all 0.2s;
        }

        .btn-custom:hover {
            background-color: #2563eb !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        /* Custom Tabs */
        .custom-tab-btn {
            position: relative;
            transition: all 0.3s ease;
            color: #64748b;
            font-weight: 600;
            padding: 14px 24px;
            border-radius: 16px;
            border: 1px solid transparent;
        }

        .custom-tab-btn:hover {
            background: #f8fafc;
            color: #3b82f6;
        }

        .custom-tab-btn.active {
            color: #3b82f6;
            box-shadow: 0 4px 10px rgba(59, 130, 246, 0.05);
        }

        .section-header {
            position: relative;
            padding-left: 1rem;
        }

        .section-header::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: #3b82f6;
            border-radius: 4px;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid px-6">

        <!-- Page Header -->
        <div class="mb-8 flex items-center gap-4">
            <div
                class="size-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center shadow-sm border border-indigo-100">
                <i data-lucide="blocks" class="size-7"></i>
            </div>
            <div>
                <h2 class="text-2xl font-black text-slate-800 tracking-tight">System Configuration</h2>
                <p class="text-slate-500 text-sm font-medium">Manage Mail servers, Payment gateways, and Social Login
                    credentials.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-12 gap-8">

            <!-- Left Column: Navigation Tabs -->
            <div class="xl:col-span-3">
                <div class="premium-card p-4 sticky top-28">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4 px-4">Integrations</h3>
                    <ul class="flex flex-col gap-2 nav-tabs" id="config-tabs">
                        <li>
                            <button data-tab-target="mailTabs"
                                class="custom-tab-btn active w-full flex items-center gap-3 text-left">
                                <i data-lucide="mail" class="size-5"></i>
                                <span>Mail Server Configuration</span>
                            </button>
                        </li>
                        <li>
                            <button data-tab-target="paymentTabs"
                                class="custom-tab-btn w-full flex items-center gap-3 text-left">
                                <i data-lucide="credit-card" class="size-5"></i>
                                <span>Payment Gateway</span>
                            </button>
                        </li>
                        <li>
                            <button data-tab-target="socialLoginTabs"
                                class="custom-tab-btn w-full flex items-center gap-3 text-left">
                                <i data-lucide="share-2" class="size-5"></i>
                                <span>Social Login Providers</span>
                            </button>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Right Column: Settings Content -->
            <div class="xl:col-span-9">
                <div class="premium-card p-10 tab-content min-h-[600px] mb-8">

                    <!-- Mail Configuration Tab -->
                    <div class="tab-pane block" id="mailTabs">
                        <div class="mb-8 pb-6 border-b border-slate-100">
                            <h3 class="text-xl font-bold text-slate-800 mb-2 section-header">SMTP Mail Settings</h3>
                            <p class="text-slate-500 text-sm">Configure your mail server to enable the system to send emails
                                to users.</p>
                        </div>

                        <form action="{{ route('setting.configuration.mail') }}" method="POST">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label for="mail_mailer" class="text-sm font-bold text-slate-700 ml-1">Mail Mailer <span
                                            class="text-red-500">*</span></label>
                                    <input type="text" name="mail_mailer" id="mail_mailer" class="modern-input"
                                        placeholder="e.g. smtp" value="{{ old('mail_mailer', env('MAIL_MAILER')) }}"
                                        required>
                                    @error('mail_mailer')
                                        <div class="text-red-500 text-sm mt-1 font-semibold">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="space-y-2">
                                    <label for="mail_host" class="text-sm font-bold text-slate-700 ml-1">Mail Host <span
                                            class="text-red-500">*</span></label>
                                    <input type="text" name="mail_host" id="mail_host" class="modern-input"
                                        placeholder="e.g. smtp.mailtrap.io" value="{{ old('mail_host', env('MAIL_HOST')) }}"
                                        required>
                                    @error('mail_host')
                                        <div class="text-red-500 text-sm mt-1 font-semibold">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="space-y-2">
                                    <label for="mail_port" class="text-sm font-bold text-slate-700 ml-1">Mail Port <span
                                            class="text-red-500">*</span></label>
                                    <input type="text" name="mail_port" id="mail_port" class="modern-input"
                                        placeholder="e.g. 2525" value="{{ old('mail_port', env('MAIL_PORT')) }}" required>
                                    @error('mail_port')
                                        <div class="text-red-500 text-sm mt-1 font-semibold">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="space-y-2">
                                    <label for="mail_encryption" class="text-sm font-bold text-slate-700 ml-1">Mail Encryption</label>
                                    <input type="text" name="mail_encryption" id="mail_encryption" class="modern-input"
                                        placeholder="e.g. tls" value="{{ old('mail_encryption', env('MAIL_ENCRYPTION')) }}"
                                        required>
                                    @error('mail_encryption')
                                        <div class="text-red-500 text-sm mt-1 font-semibold">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="space-y-2">
                                    <label for="mail_username" class="text-sm font-bold text-slate-700 ml-1">Mail
                                        Username</label>
                                    <input type="text" name="mail_username" id="mail_username" class="modern-input"
                                        placeholder="Your SMTP username"
                                        value="{{ old('mail_username', env('MAIL_USERNAME')) }}" required>
                                    @error('mail_username')
                                        <div class="text-red-500 text-sm mt-1 font-semibold">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="space-y-2">
                                    <label for="mail_password" class="text-sm font-bold text-slate-700 ml-1">Mail
                                        Password</label>
                                    <input type="password" name="mail_password" id="mail_password" class="modern-input"
                                        placeholder="Your SMTP password" required>
                                    @error('mail_password')
                                        <div class="text-red-500 text-sm mt-1 font-semibold">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="space-y-2 md:col-span-2">
                                    <label for="mail_from_address" class="text-sm font-bold text-slate-700 ml-1">Mail From
                                        Address <span class="text-red-500">*</span></label>
                                    <input type="text" name="mail_from_address" id="mail_from_address"
                                        class="modern-input" placeholder="e.g. no-reply@example.com"
                                        value="{{ old('mail_from_address', env('MAIL_FROM_ADDRESS')) }}" required>
                                    @error('mail_from_address')
                                        <div class="text-red-500 text-sm mt-1 font-semibold">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="pt-8">
                                <button type="submit"
                                    class="btn-custom px-8 py-3 rounded-xl font-bold flex items-center justify-center gap-2">
                                    <i data-lucide="save" class="size-5"></i> Save Mail Settings
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Payment Configuration Tab -->
                    <div class="tab-pane hidden" id="paymentTabs">
                        <div class="mb-8 pb-6 border-b border-slate-100">
                            <h3 class="text-xl font-bold text-slate-800 mb-2 section-header">Stripe API Keys</h3>
                            <p class="text-slate-500 text-sm">Integrate your Stripe account to process payments securely.
                            </p>
                        </div>

                        <form action="{{ route('setting.configuration.payment') }}" method="POST">
                            @csrf
                            <div class="space-y-6">
                                <div class="space-y-2">
                                    <label for="stripe_key" class="text-sm font-bold text-slate-700 ml-1">Stripe Secret
                                        Key <span class="text-red-500">*</span></label>
                                    <input type="text" name="stripe_key" id="stripe_key" class="modern-input"
                                        placeholder="sk_test_..."
                                        value="{{ old('stripe_key', env('STRIPE_SECRET_KEY')) }}" required>
                                    @error('stripe_key')
                                        <div class="text-red-500 text-sm mt-1 font-semibold">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="space-y-2">
                                    <label for="stripe_secret" class="text-sm font-bold text-slate-700 ml-1">Stripe Public
                                        Key <span class="text-red-500">*</span></label>
                                    <input type="text" name="stripe_secret" id="stripe_secret" class="modern-input"
                                        placeholder="pk_test_..."
                                        value="{{ old('stripe_secret', env('STRIPE_PUBLIC_KEY')) }}" required>
                                    @error('stripe_secret')
                                        <div class="text-red-500 text-sm mt-1 font-semibold">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="space-y-2">
                                    <label for="stripe_webhook_secret"
                                        class="text-sm font-bold text-slate-700 ml-1">Stripe Webhook Secret <span
                                            class="text-red-500">*</span></label>
                                    <input type="text" name="stripe_webhook_secret" id="stripe_webhook_secret"
                                        class="modern-input" placeholder="whsec_..."
                                        value="{{ old('stripe_webhook_secret', env('STRIPE_WEBHOOK_SECRET')) }}" required>
                                    @error('stripe_webhook_secret')
                                        <div class="text-red-500 text-sm mt-1 font-semibold">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="pt-8">
                                <button type="submit"
                                    class="btn-custom px-8 py-3 rounded-xl font-bold flex items-center justify-center gap-2">
                                    <i data-lucide="save" class="size-5"></i> Save Payment Settings
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Social Login Tab -->
                    <div class="tab-pane hidden" id="socialLoginTabs">
                        <div class="mb-8 pb-6 border-b border-slate-100">
                            <h3 class="text-xl font-bold text-slate-800 mb-2 section-header">Social OAuth Credentials</h3>
                            <p class="text-slate-500 text-sm">Configure authentication providers to allow users to sign in
                                with social accounts.</p>
                        </div>

                        <form action="{{ route('setting.configuration.social') }}" method="POST">
                            @csrf
                            <div class="space-y-10">

                                <!-- Google Config -->
                                <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100">
                                    <h4 class="text-base font-bold text-slate-800 mb-4 flex items-center gap-2">
                                        <i data-lucide="chrome" class="size-5 text-red-500"></i> Google Authentication
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                                        <div class="space-y-2">
                                            <label
                                                class="text-xs font-bold text-slate-600 uppercase tracking-wider ml-1">Client
                                                ID</label>
                                            <input type="text" name="google_client_id" class="modern-input"
                                                value="{{ old('google_client_id', env('GOOGLE_CLIENT_ID')) }}" required>
                                            @error('google_client_id')
                                                <div class="text-red-500 text-sm mt-1 font-semibold">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="space-y-2">
                                            <label
                                                class="text-xs font-bold text-slate-600 uppercase tracking-wider ml-1">Client
                                                Secret</label>
                                            <input type="text" name="google_client_secret" class="modern-input"
                                                value="{{ old('google_client_secret', env('GOOGLE_CLIENT_SECRET')) }}"
                                                required>
                                            @error('google_client_secret')
                                                <div class="text-red-500 text-sm mt-1 font-semibold">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="space-y-2">
                                            <label
                                                class="text-xs font-bold text-slate-600 uppercase tracking-wider ml-1">Redirect
                                                URI</label>
                                            <input type="text" name="google_redirect_uri" class="modern-input"
                                                value="{{ old('google_redirect_uri', env('GOOGLE_REDIRECT_URI')) }}"
                                                required>
                                            @error('google_redirect_uri')
                                                <div class="text-red-500 text-sm mt-1 font-semibold">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Facebook Config -->
                                <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100">
                                    <h4 class="text-base font-bold text-slate-800 mb-4 flex items-center gap-2">
                                        <i data-lucide="facebook" class="size-5 text-blue-600"></i> Facebook
                                        Authentication
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                                        <div class="space-y-2">
                                            <label
                                                class="text-xs font-bold text-slate-600 uppercase tracking-wider ml-1">Client
                                                ID</label>
                                            <input type="text" name="facebook_client_id" class="modern-input"
                                                value="{{ old('facebook_client_id', env('FACEBOOK_CLIENT_ID')) }}"
                                                required>
                                            @error('facebook_client_id')
                                                <div class="text-red-500 text-sm mt-1 font-semibold">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="space-y-2">
                                            <label
                                                class="text-xs font-bold text-slate-600 uppercase tracking-wider ml-1">Client
                                                Secret</label>
                                            <input type="text" name="facebook_client_secret" class="modern-input"
                                                value="{{ old('facebook_client_secret', env('FACEBOOK_CLIENT_SECRET')) }}"
                                                required>
                                            @error('facebook_client_secret')
                                                <div class="text-red-500 text-sm mt-1 font-semibold">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="space-y-2">
                                            <label
                                                class="text-xs font-bold text-slate-600 uppercase tracking-wider ml-1">Redirect
                                                URI</label>
                                            <input type="text" name="facebook_redirect_uri" class="modern-input"
                                                value="{{ old('facebook_redirect_uri', env('FACEBOOK_REDIRECT_URI')) }}"
                                                required>
                                            @error('facebook_redirect_uri')
                                                <div class="text-red-500 text-sm mt-1 font-semibold">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Apple Config -->
                                <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100">
                                    <h4 class="text-base font-bold text-slate-800 mb-4 flex items-center gap-2">
                                        <i data-lucide="apple" class="size-5 text-slate-800"></i> Apple Authentication
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                                        <div class="space-y-2">
                                            <label
                                                class="text-xs font-bold text-slate-600 uppercase tracking-wider ml-1">Client
                                                ID</label>
                                            <input type="text" name="apple_client_id" class="modern-input"
                                                value="{{ old('apple_client_id', env('APPLE_CLIENT_ID')) }}" required>
                                            @error('apple_client_id')
                                                <div class="text-red-500 text-sm mt-1 font-semibold">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="space-y-2">
                                            <label
                                                class="text-xs font-bold text-slate-600 uppercase tracking-wider ml-1">Client
                                                Secret</label>
                                            <input type="text" name="apple_client_secret" class="modern-input"
                                                value="{{ old('apple_client_secret', env('APPLE_CLIENT_SECRET')) }}"
                                                required>
                                            @error('apple_client_secret')
                                                <div class="text-red-500 text-sm mt-1 font-semibold">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="space-y-2">
                                            <label
                                                class="text-xs font-bold text-slate-600 uppercase tracking-wider ml-1">Redirect
                                                URI</label>
                                            <input type="text" name="apple_redirect_uri" class="modern-input"
                                                value="{{ old('apple_redirect_uri', env('APPLE_REDIRECT_URI')) }}"
                                                required>
                                            @error('apple_redirect_uri')
                                                <div class="text-red-500 text-sm mt-1 font-semibold">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="pt-8">
                                <button type="submit"
                                    class="btn-custom px-8 py-3 rounded-xl font-bold flex items-center justify-center gap-2">
                                    <i data-lucide="save" class="size-5"></i> Save Social Login Settings
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Setup Tab Navigation Logic
            const tabButtons = document.querySelectorAll('.custom-tab-btn');
            const tabPanes = document.querySelectorAll('.tab-pane');

            tabButtons.forEach(button => {
                button.addEventListener('click', () => {
                    // Remove active classes
                    tabButtons.forEach(btn => btn.classList.remove('active'));
                    tabPanes.forEach(pane => {
                        pane.classList.remove('block');
                        pane.classList.add('hidden');
                    });

                    // Add active classes to selected
                    button.classList.add('active');
                    const targetId = button.getAttribute('data-tab-target');
                    const targetPane = document.getElementById(targetId);

                    if (targetPane) {
                        targetPane.classList.remove('hidden');
                        targetPane.classList.add('block');
                    }
                });
            });

            // Re-initialize Lucide Icons if available
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>
@endpush
