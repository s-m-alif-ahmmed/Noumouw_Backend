
@extends('backend.app')
@section('title', 'activity')
@section('title_url')
    <a href="{{ route('activity.index') }}">activity</a>
@endsection
@section('tabName')
    <a href="{{ route('dashboard') }}">Home</a>
@endsection

@push('styles_top')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag@3.1.0/dist/css/multi-select-tag.css">
@endpush
{{-- Push additional styles if needed --}}
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.0/dist/sweetalert2.min.css" rel="stylesheet">
    {{-- <link href="{{ asset('vendor/flasher/flasher.min.css') }}" rel="stylesheet"> --}}
    {{-- dropify --}}
    <link href="{{ asset('//dropify.min.css') }}">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@24.7.0/build/css/intlTelInput.css">
    <style>
        .dropify-wrapper {
            height: 400px;
        }

        .dropify-wrapper .dropify-preview .dropify-render img {
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        .dropify-wrapper .dropify-message .file-icon p {
            font-size: 23px;
        }

        .text-center {
            text-align: end;
        }

        .table-topbar {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
        }

        .dataTables_info {
            margin-top: 20px;
        }

        .form-input {
            border: 2px solid #f0f3f7;
            border-radius: 6px;
        }
    </style>
@endpush

{{-- Main content of the News Dashboard page --}}
@section('content')
    <div class="card">
        <div class="card-body max-sm:overflow-scroll">
            <div class="flex justify-between mb-6">
                <h1 class="text-xl">Edit activity</h1>
                <a href="{{ route('activity.index') }}"
                    class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600
                    focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100
                     dark:ring-custom-400/20">Back</a>
            </div>

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('activity.update',$data->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="flex w-full gap-5 mb-5">
                    <div class="w-1/2">
                        <x-backend.input-ajax type="text" name="title" label="activity Name" value="{{ old('title', $data->title) }}" :required="true" />
                        <div class="error-message-section"></div>
                        @error('title')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                         @enderror
                    </div>

                    <div class="w-1/2">
                        <x-backend.select2-single name="course_id" label="Course" :required="true">
                            <option value="">Select a Course</option>
                            @foreach ($courses as $course)
                                <option value="{{ $course->id }}"
                                        @if (old('course_id',$data->content?->course?->id) == $course->id) selected @endif>
                                    {{ $course->name }}
                                </option>
                            @endforeach
                        </x-backend.select2-single>
                    </div>
                </div>

                <div class="flex gap-3 mb-5">
                    <div class="w-1/2 mt-2">
                        <x-backend.text-area input-ajax :ajax="true" name="description" value="{{ old('description' , $data->description) }}" label="Description"
                                             :required="true" placeholder="Enter Bio here"> </x-backend.text-area>
                        @error('description')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="w-1/2 mt-5">
                        <label for="tags" class="block text-sm font-medium text-gray-700">Tags</label>
                        <select name="tags[]" id="tags" multiple class="w-full border border-gray-300 rounded-lg mt-3 p-2">
                            @foreach ($allTags as $tag)
                                <option value="{{ $tag->id }}" @if (in_array($tag->id, $selectedTagIds)) selected @endif>{{ $tag->title }}</option>
                            @endforeach
                        </select>
                        @error('tags')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="xl:col-span-12 gallery-images">
                    <div class="flex items-center justify-between gap-5 mt-5 mb-3">
                        <label for="gallery_images" class="inline-block text-base font-medium">Activity Images</label>
                        <button type="button" id="add-gallery-image"
                                class="text-white bg-green-500 border-green-500 btn hover:bg-green-600 !p-1">
                            Add image
                        </button>
                    </div>
                    <div id="gallery-images-section" class="grid grid-cols-12 gap-3">
                        @foreach($data->images as $key => $image)
                            <div class="col-span-4 relative single-gallery-image">
                                <!-- Remove Button -->
                                <button type="button"
                                        class="remove-gallery-section p-1 rounded-full z-[10002] bg-red-600 text-white absolute right-2 top-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                    </svg>
                                </button>
                                <!-- Image Input -->
                                <input type="file" name="images[]" id="gallery_{{ $key }}"
                                       class="dropify form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100"
                                       data-height="200"
                                       data-default-file="{{ asset($image) }}"/>
                            </div>
                        @endforeach
                    </div>
                    <div class="alert alert-danger">
                        @error('images')
                        <div class="text-red-600 text-sm font-semibold">{{ $message }}</div>
                        @enderror
                        @foreach ($errors->get('images.*') as $messages)
                            @foreach ($messages as $message)
                                <div class="text-red-600 text-sm font-semibold">{{ $message }}</div>
                            @endforeach
                        @endforeach
                    </div>
                </div>

                {{-- ------------------- Form Buttons ------------- --}}
                <div class="flex justify-start mt-6 gap-x-4">
                    <button type="submit"
                        class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white
                    focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600
                    active:ring active:ring-custom-100 dark:ring-custom-400/20">Update</button>
                </div>
            </form><!--end form-->
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript" src="https://jeremyfagis.github.io/dropify/dist/js/dropify.min.js"></script>
    <script>
      $(document).ready(function() {
        // Initialize Dropify
        $('.dropify').dropify();

        // Add Gallery Image
        let imageSectionCount = {{ count($data->images) }};
        $('#add-gallery-image').click(function() {
            imageSectionCount++;
            $('#gallery-images-section').append(`
                <div class="col-span-4 relative single-gallery-image">
                    <button type="button" class="remove-gallery-section p-1 rounded-full z-[10002] bg-red-600 text-white absolute right-2 top-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </button>
                    <input type="file" name="images[]" id="gallery_${imageSectionCount}"
                        class="dropify form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200"
                        data-height="200" />
                </div>
            `);
            $('.dropify').dropify();
        });

        // Remove Gallery Image
        $(document).on('click', '.remove-gallery-section', function() {
            $(this).parent().remove();
        });
    });
    </script>
    <script src="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag@3.1.0/dist/js/multi-select-tag.js"></script>
    <script>
        // new MultiSelectTag('tags')
        new MultiSelectTag('tags', {
            rounded: true, // default true
            shadow: true, // default false
            placeholder: 'Search', // default Search...
            tagColor: {
                textColor: '#327b2c',
                borderColor: '#92e681',
                bgColor: '#eaffe6',
            },
            onChange: function(values) {
                console.log(values)
            }
        })
    </script>
@endpush
