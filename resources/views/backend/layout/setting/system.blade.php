@extends('backend.app')

@section('title','Settings')
@section('title_url')
<a href="{{ route('setting.system.index') }}">Settings</a>
@endsection
@section('tabName')
System
@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.css">
<style>
    .form-input {
        border: 2px solid #f0f3f7;
        border-radius: 6px;
    }
    .dropify-wrapper {
        height: 400px;
    }
    .dropify-wrapper .dropify-preview .dropify-render img {
        display: block;
    margin-left: auto;
    margin-right: auto;
    }
</style>
@endpush

@section('content')
<div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">

    <div class="card">
    <div class="tab-content">
        <div class="tab-pane block" id="personalTabs">
            <div class="card">
                <div class="card-body">
                    <p class="mb-4 text-slate-500 dark:text-zink-200">Update your web personal details here easily.</p>
                    <form action="{{ route('setting.system.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="grid grid-cols-1 gap-5 xl:grid-cols-12">
                    
                            {{-- ------------------- Title Input Field ------------- --}}
                            <div class="xl:col-span-6">
                                <label for="title" class="inline-block mb-2 text-base font-medium">Title</label>
                                <input type="text" name="title" id="title" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200 @error('title') is-invalid @enderror" placeholder="kamandaalcindor" value="{{ old('title', $setting->title ?? 'kamandaalcindor') }}">
                                @error('title')
                                    <div style="color: red">{{ $message }}</div>
                                @enderror
                            </div><!--end col-->
                    
                            {{-- ------------------- System Name Input Field ------------- --}}
                            <div class="xl:col-span-6">
                                <label for="system_name" class="inline-block mb-2 text-base font-medium">System Name</label>
                                <input type="text" name="system_name" id="system_name" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200 @error('system_name') is-invalid @enderror" placeholder="kamandaalcindor" value="{{ old('system_name', $setting->system_name ?? 'kamandaalcindor') }}">
                                @error('system_name')
                                    <div style="color: red">{{ $message }}</div>
                                @enderror
                            </div><!--end col-->
                    
                            {{-- ------------------- Email Input Field ------------- --}}
                            <div class="xl:col-span-6">
                                <label for="email" class="inline-block mb-2 text-base font-medium">Email</label>
                                <input type="email" name="email" id="email" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200 @error('email') is-invalid @enderror" placeholder="Enter your value" value="{{ old('email', $setting->email ?? 'example@email.com') }}">
                                @error('email')
                                    <div style="color: red">{{ $message }}</div>
                                @enderror
                            </div><!--end col-->
                    
                            {{-- ------------------- Contact Number Input Field ------------- --}}
                            <div class="xl:col-span-6">
                                <label for="contact_number" class="inline-block mb-2 text-base font-medium">Contact Number</label>
                                <input type="text" name="contact_number" id="contact_number" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200 @error('contact_number') is-invalid @enderror" placeholder="Enter your Contact Number" value="{{ old('contact_number', $setting->contact_number ?? '+0000000000') }}">
                                @error('contact_number')
                                    <div style="color: red">{{ $message }}</div>
                                @enderror
                            </div><!--end col-->
                    
                            {{-- ------------------- Company Open Hour Input Field ------------- --}}
                            <div class="xl:col-span-6">
                                <label for="company_open_hour" class="inline-block mb-2 text-base font-medium">Company Open Hour</label>
                                <input type="text" name="company_open_hour" id="company_open_hour" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200 @error('company_open_hour') is-invalid @enderror" placeholder="e.g., 9:00 AM - 5:00 PM" value="{{ old('company_open_hour', $setting->company_open_hour ?? '9:00 AM - 5:00 PM') }}">
                                @error('company_open_hour')
                                    <div style="color: red">{{ $message }}</div>
                                @enderror
                            </div><!--end col-->
                    
                            {{-- ------------------- Copyright Text Input Field ------------- --}}
                            <div class="xl:col-span-6">
                                <label for="copyright_text" class="inline-block mb-2 text-base font-medium">Copyright Text</label>
                                <input type="text" name="copyright_text" id="copyright_text" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200 @error('copyright_text') is-invalid @enderror" placeholder="2024 © ABC" value="{{ old('copyright_text', $setting->copyright_text ?? '2024 © ABC.') }}">
                                @error('copyright_text')
                                    <div style="color: red">{{ $message }}</div>
                                @enderror
                            </div><!--end col-->
                    
                            {{-- ------------------- Address Input Field ------------- --}}
                            <div class="xl:col-span-6">
                                <label for="address" class="inline-block mb-2 text-base font-medium">Address</label>
                                <input type="text" name="address" id="address" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200 @error('address') is-invalid @enderror" placeholder="Enter Address Here" value="{{ old('address', $setting->address ?? '26985 Brighton Lane, Lake Forest, CA 92630') }}">
                                @error('address')
                                    <div style="color: red">{{ $message }}</div>
                                @enderror
                            </div><!--end col-->
                    
                            {{-- ------------------- Description Input Field ------------- --}}
                            <div class="xl:col-span-6">
                                <label for="description" class="inline-block mb-2 text-base font-medium">Description</label>
                                <input type="text" name="description" id="description" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200 @error('description') is-invalid @enderror" placeholder="Enter Description Here" value="{{ old('description', $setting->description ?? 'Lorem all here') }}">
                                @error('description')
                                    <div style="color: red">{{ $message }}</div>
                                @enderror
                            </div><!--end col-->
                    
                            {{-- ------------------- Logo Upload Field ------------- --}}
                            <div class="xl:col-span-6">
                                <label for="logo" class="inline-block mb-2 text-base font-medium">Logo</label>
                                <input type="file" name="logo" id="logo" class="dropify form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200" data-default-file="{{ asset($setting->logo ?? 'backend/images/delivery-2.png') }}" />
                            </div><!--end col-->
                    
                            {{-- ------------------- Favicon Upload Field ------------- --}}
                            <div class="xl:col-span-6">
                                <label for="favicon" class="inline-block mb-2 text-base font-medium">Favicon</label>
                                <input type="file" name="favicon" id="favicon" class="dropify form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200" data-default-file="{{ asset($setting->favicon ?? 'backend/images/img-08.png') }}" />
                            </div><!--end col-->
                    
                        </div><!--end grid-->
                    
                        {{-- ------------------- Form Buttons ------------- --}}
                        <div class="flex justify-start mt-6 gap-x-4">
                            <button type="submit" class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">Update</button>
                        </div>
                    </form><!--end form-->
                    
                    
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://jeremyfagis.github.io/dropify/dist/js/dropify.min.js"></script>
<script>
     $(document).ready(function(){
        $('.dropify').dropify();
     })
</script>
@endpush