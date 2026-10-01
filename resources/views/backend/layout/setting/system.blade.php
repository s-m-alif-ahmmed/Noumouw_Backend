@extends('backend.app')

@section('title', 'System Settings')
@section('title_url')
    <a href="{{ route('setting.system.index') }}">Settings</a>
@endsection
@section('tabName')
    System
@endsection

@push('styles')
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.css">
    <style>
        .premium-card { background: #ffffff; border-radius: 24px; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.04), 0 4px 6px -2px rgba(0, 0, 0, 0.02); border: 1px solid #f1f5f9; }
        
        /* Modern Inputs */
        .modern-input { width: 100%; border-radius: 12px; border: 1px solid #e2e8f0; padding: 12px 16px; outline: none; transition: all 0.3s; }
        .modern-input:focus { border-color: #3b82f6; box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1); background: #fff; }
        
        /* Dropify Styling */
        .dropify-wrapper { height: 250px !important; border-radius: 16px !important; border: 2px dashed #cbd5e1 !important; background-color: #f8fafc !important; transition: all 0.3s !important; }
        .dropify-wrapper:hover { border-color: #3b82f6 !important; background-color: #f1f5f9 !important; }
        .dropify-wrapper .dropify-preview .dropify-render img { display: block; margin-left: auto; margin-right: auto; }
        
        /* Form Modernization */
        .btn-custom { background-color: #3b82f6 !important; color: white !important; transition: all 0.2s; }
        .btn-custom:hover { background-color: #2563eb !important; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3); }

        .section-header { position: relative; padding-left: 1rem; }
        .section-header::before { content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 4px; background: #3b82f6; border-radius: 4px; }
    </style>
@endpush

@section('content')
    <div class="container-fluid pb-6 px-6">
        
        <div class="premium-card p-10 relative overflow-hidden shadow-xl">
            <!-- Header Section -->
            <div class="flex items-center gap-4 mb-10 pb-6 border-b border-slate-100">
                <div class="size-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center shadow-sm">
                    <i data-lucide="settings-2" class="size-7"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-black text-slate-800 tracking-tight">System Configuration</h2>
                    <p class="text-slate-500 text-sm font-medium">Manage global website settings, branding, and contact details.</p>
                </div>
            </div>

            <form action="{{ route('setting.system.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf
                
                <!-- Branding Section -->
                <div class="bg-slate-50 rounded-2xl p-8 border border-slate-100">
                    <h3 class="text-lg font-bold text-slate-800 mb-6 section-header flex items-center gap-2">
                        <i data-lucide="image" class="size-5 text-slate-500"></i> Visual Branding
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label for="logo" class="text-sm font-bold text-slate-700 ml-1">Main Application Logo</label>
                            <input type="file" name="logo" id="logo" class="dropify" data-default-file="{{ asset($setting->logo ?? 'backend/images/delivery-2.png') }}" />
                        </div>
                        
                        <div class="space-y-2">
                            <label for="favicon" class="text-sm font-bold text-slate-700 ml-1">Website Favicon (Browser Tab Icon)</label>
                            <input type="file" name="favicon" id="favicon" class="dropify" data-default-file="{{ asset($setting->favicon ?? 'backend/images/img-08.png') }}" />
                        </div>
                    </div>
                </div>

                <!-- General Information Section -->
                <div>
                    <h3 class="text-lg font-bold text-slate-800 mb-6 section-header flex items-center gap-2">
                        <i data-lucide="info" class="size-5 text-slate-500"></i> General Information
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- System Name -->
                        <div class="space-y-2">
                            <label for="system_name" class="text-sm font-bold text-slate-700 ml-1">System Name <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="text" name="system_name" id="system_name" class="modern-input pl-[6rem]" placeholder="e.g. MyPlatform" value="{{ old('system_name', $setting->system_name ?? 'kamandaalcindor') }}">
                            </div>
                            @error('system_name') <div class="text-red-500 text-sm mt-1 font-semibold">{{ $message }}</div> @enderror
                        </div>

                        <!-- Title -->
                        <div class="space-y-2">
                            <label for="title" class="text-sm font-bold text-slate-700 ml-1">Website Title <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="text" name="title" id="title" class="modern-input pl-[6rem]" placeholder="e.g. MyPlatform - The Best Choice" value="{{ old('title', $setting->title ?? 'kamandaalcindor') }}">
                            </div>
                            @error('title') <div class="text-red-500 text-sm mt-1 font-semibold">{{ $message }}</div> @enderror
                        </div>

                        <!-- Copyright Text -->
                        <div class="space-y-2 md:col-span-2">
                            <label for="copyright_text" class="text-sm font-bold text-slate-700 ml-1">Copyright Text <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="text" name="copyright_text" id="copyright_text" class="modern-input pl-[6rem]" placeholder="e.g. 2024 © MyPlatform" value="{{ old('copyright_text', $setting->copyright_text ?? '2024 © ABC.') }}">
                            </div>
                            @error('copyright_text') <div class="text-red-500 text-sm mt-1 font-semibold">{{ $message }}</div> @enderror
                        </div>

                        <!-- Description -->
                        <div class="space-y-2 md:col-span-2">
                            <label for="description" class="text-sm font-bold text-slate-700 ml-1">System Description <span class="text-red-500">*</span></label>
                            <textarea name="description" id="description" rows="3" class="modern-input" placeholder="A brief description of your platform for SEO purposes...">{{ old('description', $setting->description ?? 'Lorem all here') }}</textarea>
                            @error('description') <div class="text-red-500 text-sm mt-1 font-semibold">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                <hr class="border-slate-100 border-t-2 border-dashed my-8">

                <!-- Contact & Support Section -->
                <div>
                    <h3 class="text-lg font-bold text-slate-800 mb-6 section-header flex items-center gap-2">
                        <i data-lucide="phone-call" class="size-5 text-slate-500"></i> Contact & Support
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Email -->
                        <div class="space-y-2">
                            <label for="email" class="text-sm font-bold text-slate-700 ml-1">Support Email <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="email" name="email" id="email" class="modern-input pl-[6rem]" placeholder="support@example.com" value="{{ old('email', $setting->email ?? 'example@email.com') }}">
                            </div>
                            @error('email') <div class="text-red-500 text-sm mt-1 font-semibold">{{ $message }}</div> @enderror
                        </div>

                        <!-- Contact Number -->
                        <div class="space-y-2">
                            <label for="contact_number" class="text-sm font-bold text-slate-700 ml-1">Contact Number <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="text" name="contact_number" id="contact_number" class="modern-input pl-[6rem]" placeholder="+1 234 567 8900" value="{{ old('contact_number', $setting->contact_number ?? '+0000000000') }}">
                            </div>
                            @error('contact_number') <div class="text-red-500 text-sm mt-1 font-semibold">{{ $message }}</div> @enderror
                        </div>

                        <!-- Open Hours -->
                        <div class="space-y-2">
                            <label for="company_open_hour" class="text-sm font-bold text-slate-700 ml-1">Operating Hours <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="text" name="company_open_hour" id="company_open_hour" class="modern-input pl-[6rem]" placeholder="Mon-Fri, 9:00 AM - 5:00 PM" value="{{ old('company_open_hour', $setting->company_open_hour ?? '9:00 AM - 5:00 PM') }}">
                            </div>
                            @error('company_open_hour') <div class="text-red-500 text-sm mt-1 font-semibold">{{ $message }}</div> @enderror
                        </div>

                        <!-- Address -->
                        <div class="space-y-2">
                            <label for="address" class="text-sm font-bold text-slate-700 ml-1">Physical Address <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="text" name="address" id="address" class="modern-input pl-[6rem]" placeholder="123 Main St, City, Country" value="{{ old('address', $setting->address ?? '26985 Brighton Lane, Lake Forest, CA 92630') }}">
                            </div>
                            @error('address') <div class="text-red-500 text-sm mt-1 font-semibold">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                <div class="pt-8">
                    <button type="submit" class="btn-custom px-10 py-4 rounded-xl font-black flex items-center justify-center gap-3 w-full md:w-max transition-all hover:-translate-y-1">
                        <i data-lucide="save" class="size-5"></i>
                        Save System Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://jeremyfagis.github.io/dropify/dist/js/dropify.min.js"></script>
    <script>
        $(document).ready(function(){
            $('.dropify').dropify({
                tpl: {
                    message: '<div class="dropify-message"><span class="file-icon"></span> <p style="font-size: 18px; font-weight: 600; color: #64748b;">Drop your image here</p></div>'
                }
            });

            // Initialize Lucide icons
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>
@endpush
