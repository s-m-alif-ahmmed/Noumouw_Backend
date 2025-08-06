@extends('backend.app')

{{-- Title for the User Dashboard --}}
@section('title', 'Privacy Policy Page')
@section('title_url')
    <a href="{{ route('privacy-policy.index') }}">Privacy Policy Page</a>
@endsection
@section('tabName')
    Update
@endsection

{{-- Push additional styles if needed --}}
@push('styles')
    {{-- Add any specific styles for the User Dashboard page here --}}
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.0/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.css">
    {{-- CKEditor CDN --}}
    <script src="https://cdn.ckeditor.com/ckeditor5/23.0.0/classic/ckeditor.js"></script>

    <style>
        .text-center {
            text-align: end;
        }

        .table-topbar {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
        }

        .ck-editor__editable[role="textbox"] {
            min-height: 150px;
        }
    </style>
@endpush



{{-- Main content of the User Dashboard page --}}
@section('content')
    <div class="card">
        <div class="card-body max-sm:overflow-scroll">
            <h1>Update Dynamic Page</h1>
            <div class="flex justify-end mb-6">
                <a href="{{ route('privacy-policy.index') }}"
                    class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">Back</a>
            </div>
            <form action="{{ route('privacy-policy.update', $data->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 gap-5 xl:grid-cols-12">

                    {{-- ------------------- Page Title Input Field ------------- --}}
                    <div class="xl:col-span-12">
                        <label for="title" class="inline-block mb-2 text-base font-medium">Page Title<span
                                style="color: red">*</span></label>
                        <input type="text" name="title" id="title"
                            class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200 @error('title') is-invalid @enderror"
                            placeholder="Enter Page Title Here" value="{{ old('title', $data->title) }}">
                        @error('title')
                            <div style="color: red">{{ $message }}</div>
                        @enderror
                    </div><!--end col-->
                    {{-- ------------------- Content Input Field ------------- --}}
                    <div class="xl:col-span-12">
                        <label for="description" class="inline-block mb-2 text-base font-medium">Page Content<span
                                style="color: red">*</span> </label>
                        <textarea name="description" id="description"
                            class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                            rows="3" placeholder="Enter Page Content Here">{{ old('description', $data->description) }} </textarea>
                        @error('description')
                            <div style="color: red">{{ $message }}</div>
                        @enderror
                    </div><!--end col-->

                </div><!--end grid-->

                {{-- ------------------- Form Buttons ------------- --}}
                <div class="flex justify-start mt-6 gap-x-4">
                    <button type="submit"
                        class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">Submit</button>
                </div>
            </form><!--end form-->
        </div>

    </div>
@endsection




@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>


    <script>
        ClassicEditor
            .create(document.querySelector('#description'), {
                removePlugins: ['CKFinderUploadAdapter', 'CKFinder', 'EasyImage', 'Image', 'ImageCaption', 'ImageStyle',
                    'ImageToolbar', 'ImageUpload', 'MediaEmbed'
                ],
                height: '500px'
            })
            .catch(error => {
                console.error(error);
            });
    </script>
@endpush
