@extends('backend.app')

@section('title', 'Profile Settings')
@section('title_url')
    <a href="{{ route('setting.system.index') }}">Settings</a>
@endsection
@section('tabName')
    Profile
@endsection

@push('styles')
    <style>
        .premium-card { background: #ffffff; border-radius: 24px; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.04), 0 4px 6px -2px rgba(0, 0, 0, 0.02); border: 1px solid #f1f5f9; }
        
        /* Form Modernization */
        .btn-custom { background-color: #3b82f6 !important; color: white !important; transition: all 0.2s; }
        .btn-custom:hover { background-color: #2563eb !important; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3); }

        .btn-success-custom { background-color: #10b981 !important; color: white !important; transition: all 0.2s; }
        .btn-success-custom:hover { background-color: #059669 !important; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3); }

        /* Custom Tabs */
        .custom-tab-btn { position: relative; transition: all 0.3s ease; color: #64748b; font-weight: 600; padding: 12px 24px; border-radius: 12px; }
        .custom-tab-btn:hover { background: #f8fafc; color: #3b82f6; }
        .custom-tab-btn.active { color: #3b82f6; }
        
        .profile-img-container {
            position: relative;
            display: inline-block;
            border-radius: 50%;
            padding: 4px;
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid py-10 px-6">
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-10">
            <!-- Left Column: User Profile Overview -->
            <div class="xl:col-span-4">
                <div class="premium-card p-10 text-center relative overflow-hidden shadow-xl">
                    <div class="absolute top-0 left-0 right-0 h-32 bg-gradient-to-r from-blue-50 to-indigo-50"></div>
                    
                    <div class="relative z-10">
                        <form action="{{ route('setting.profile.picture') }}" method="POST" enctype="multipart/form-data" id="profile-picture-form">
                            @csrf
                            <div class="profile-img-container shadow-xl mb-6 mt-4 mx-auto w-max">
                                <img src="{{ asset(auth()->user()->avatar ? auth()->user()->avatar : './backend/images/user.png') }}"
                                    alt="Profile Picture"
                                    class="size-32 object-cover rounded-full border-4 border-white bg-white">
                                
                                <label for="profile-img-file-input"
                                    class="absolute bottom-2 right-0 flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white rounded-full shadow-lg cursor-pointer size-10 transition-all hover:scale-110 border-2 border-white">
                                    <i data-lucide="camera" class="size-5"></i>
                                    <input id="profile-img-file-input" type="file" name="profile_picture" class="hidden" onchange="document.getElementById('profile-picture-form').submit();">
                                </label>
                            </div>
                        </form>
                        
                        <h2 class="text-2xl font-black text-slate-800 tracking-tight flex items-center justify-center gap-2">
                            {{ auth()->user()->name }}
                            <i data-lucide="badge-check" class="size-5 text-blue-500 fill-blue-50"></i>
                        </h2>
                        
                        <div class="inline-flex items-center gap-2 mt-3 px-4 py-1.5 rounded-full bg-slate-50 text-slate-600 text-sm font-bold uppercase tracking-widest border border-slate-100">
                            <i data-lucide="shield" class="size-4 text-slate-400"></i>
                            {{ ucfirst(auth()->user()->role) }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Settings Tabs -->
            <div class="xl:col-span-8">
                <div class="premium-card overflow-hidden mb-8">
                    
                    <!-- Tabs Header -->
                    <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                        <ul class="flex flex-wrap gap-2 text-sm font-medium text-center nav-tabs" id="profile-tabs">
                            <li class="group">
                                <button data-tab-target="personalTabs" class="custom-tab-btn active flex items-center gap-2">
                                    <i data-lucide="user" class="size-4"></i> Personal Information
                                </button>
                            </li>
                            <li class="group">
                                <button data-tab-target="changePasswordTabs" class="custom-tab-btn flex items-center gap-2">
                                    <i data-lucide="lock" class="size-4"></i> Security & Password
                                </button>
                            </li>
                        </ul>
                    </div>

                    <div class="p-10 tab-content">
                        <!-- Personal Info Tab -->
                        <div class="tab-pane block" id="personalTabs">
                            <div class="mb-8">
                                <h3 class="text-xl font-bold text-slate-800 mb-1">My Profile</h3>
                                <p class="text-slate-500 text-sm">Update your personal details and contact information.</p>
                            </div>

                            <form action="{{ route('setting.profile.update') }}" method="POST" class="space-y-6">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-2 md:col-span-2">
                                        <label for="full_name" class="text-sm font-bold text-slate-700 ml-1">Full Name <span class="text-red-500">*</span></label>
                                        <input type="text" name="full_name" id="full_name"
                                            class="w-full rounded-xl border-slate-200 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 px-4 py-3 bg-slate-50 focus:bg-white transition-all"
                                            placeholder="e.g. John Doe" value="{{ old('full_name', $user->name ?? '') }}">
                                        @error('full_name')
                                            <div class="text-red-500 text-sm mt-1 font-semibold">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="space-y-2 md:col-span-2">
                                        <label for="email" class="text-sm font-bold text-slate-700 ml-1">Email Address <span class="text-red-500">*</span></label>
                                        <input type="email" name="email" id="email"
                                            class="w-full rounded-xl border-slate-200 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 px-4 py-3 bg-slate-50 focus:bg-white transition-all"
                                            placeholder="e.g. john@example.com"
                                            value="{{ old('email', $user->email ?? '') }}">
                                        @error('email')
                                            <div class="text-red-500 text-sm mt-1 font-semibold">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="pt-4">
                                    <button type="submit" class="btn-custom px-8 py-3 rounded-xl font-bold flex items-center justify-center gap-2 w-max">
                                        <i data-lucide="save" class="size-5"></i>
                                        Save Changes
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Change Password Tab -->
                        <div class="tab-pane hidden" id="changePasswordTabs">
                            <div class="mb-8">
                                <h3 class="text-xl font-bold text-slate-800 mb-1">Account Security</h3>
                                <p class="text-slate-500 text-sm">Ensure your account is using a long, random password to stay secure.</p>
                            </div>

                            <form action="{{ route('setting.profile.password') }}" method="POST" class="space-y-6"> 
                                @csrf
                                <div class="space-y-6 max-w-2xl">
                                    <div class="space-y-2">
                                        <label for="old_password" class="text-sm font-bold text-slate-700 ml-1">Current Password <span class="text-red-500">*</span></label>
                                        <input type="password" name="old_password" id="old_password"
                                            class="w-full rounded-xl border-slate-200 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 px-4 py-3 bg-slate-50 focus:bg-white transition-all"
                                            placeholder="Enter your current password">
                                        @error('old_password')
                                            <div class="text-red-500 text-sm mt-1 font-semibold">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div class="space-y-2">
                                            <label for="new_password" class="text-sm font-bold text-slate-700 ml-1">New Password <span class="text-red-500">*</span></label>
                                            <input type="password" name="new_password" id="new_password"
                                                class="w-full rounded-xl border-slate-200 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 px-4 py-3 bg-slate-50 focus:bg-white transition-all"
                                                placeholder="New password">
                                            @error('new_password')
                                                <div class="text-red-500 text-sm mt-1 font-semibold">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="space-y-2">
                                            <label for="new_password_confirmation" class="text-sm font-bold text-slate-700 ml-1">Confirm Password <span class="text-red-500">*</span></label>
                                            <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                                                class="w-full rounded-xl border-slate-200 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 px-4 py-3 bg-slate-50 focus:bg-white transition-all"
                                                placeholder="Confirm new password">
                                        </div>
                                    </div>
                                </div>

                                <div class="pt-4">
                                    <button type="submit" class="btn-success-custom px-8 py-3 rounded-xl font-bold flex items-center justify-center gap-2 w-max">
                                        <i data-lucide="shield-check" class="size-5"></i>
                                        Update Password
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Custom Tab Logic
        document.addEventListener('DOMContentLoaded', function() {
            const tabButtons = document.querySelectorAll('.custom-tab-btn');
            const tabPanes = document.querySelectorAll('.tab-pane');

            tabButtons.forEach(button => {
                button.addEventListener('click', () => {
                    tabButtons.forEach(btn => btn.classList.remove('active'));
                    tabPanes.forEach(pane => {
                        pane.classList.remove('block');
                        pane.classList.add('hidden');
                    });

                    button.classList.add('active');
                    const targetId = button.getAttribute('data-tab-target');
                    const targetPane = document.getElementById(targetId);
                    
                    if(targetPane) {
                        targetPane.classList.remove('hidden');
                        targetPane.classList.add('block');
                    }
                });
            });

            @if($errors->has('old_password') || $errors->has('new_password'))
                const passwordTabBtn = document.querySelector('[data-tab-target="changePasswordTabs"]');
                if(passwordTabBtn) passwordTabBtn.click();
            @endif
        });
    </script>
    <script src="{{ asset('backend/js/pages/pages-account-setting.init.js') }}"></script>
@endpush
