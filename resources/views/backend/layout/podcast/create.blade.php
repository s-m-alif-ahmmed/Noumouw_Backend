@extends('backend.app')
@section('title', 'Podcast')
@section('title_url')
    <a href="{{ route('podcast.index') }}">Podcast</a>
@endsection
@section('tabName')
    <a href="{{ route('dashboard') }}">Home</a>
@endsection

@push('styles_top')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag@3.1.0/dist/css/multi-select-tag.css">
@endpush

{{-- Push additional styles if needed --}}
@push('styles')
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@24.7.0/build/css/intlTelInput.css">
    <link rel="stylesheet" href="{{ asset('backend/js/datatables/jquery-3.7.0.js') }}">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.0/dist/sweetalert2.min.css" rel="stylesheet">

    <style>
       .dropify-wrapper {
            height: 188px;
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


@section('content')

    <div class="card">
        <div class="card-body max-sm:overflow-scroll">
            <div class="flex justify-between mb-6">
                <h1 class="text-xl">Create a Podcast</h1>
                <a href="{{ route('course.index') }}"
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

                <form action="{{ route('podcast.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                     <div class="flex mt-5 gap-x-8  ">
                        <div class="w-1/2">
                            <x-backend.input type="text" name="title" label="Title" :required="true" />
                        </div>

                        <div class="w-1/2">
                            <x-backend.select2-single name="instructor_id" label="Instructor Name" :required="true">
                                <option selected disabled value="">Select an Instructor</option>
                                @foreach ($data as $instructor)
                                    <option value="{{ $instructor->id }}"
                                        @if (old('instructor_id') == $instructor->id) selected @elseif ($selectedCourse && $selectedCourse->id == $instructor->id) selected @endif>
                                        {{ $instructor->name }}
                                    </option>
                                @endforeach
                            </x-backend.select2-single>
                        </div>

                    </div>

                    <div class="flex w-full gap-5 mb-5">
                        <div class="w-1/2">
                            <x-backend.select2-single name="course_id" label="Course Name" :required="true">
                                <option selected disabled>Select a Course</option>
                                @foreach ($courses as $course)
                                    <option value="{{ $course->id }}"
                                            @if (old('course_id') == $course->id) selected @endif>
                                        {{ $course->name }}
                                    </option>
                                @endforeach
                            </x-backend.select2-single>
                        </div>
                        <div class="w-1/2">
                            <label for="tags" class="inline-block mb-2 text-base font-medium">Tags<span
                                    style="color: red">*</span></label>
                            <select name="tags[]" id="tags" multiple class="border border-gray-300 rounded-lg mt-1 p-2 w-1">
                                <option disabled>Select a Tag</option>
                                @foreach ($tags as $tag)
                                    <option value="{{ $tag->id }}" @if (in_array($tag->id, old('tags', []))) selected @endif>
                                        {{ $tag->title }}</option>
                                @endforeach
                            </select>
                            @error('tags')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                            @error('tags.*')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="flex  gap-x-8">

                        <div class="w-1/2 mt-6">
                            <x-backend.text-area  name="description" label="Description"
                                :required="true" placeholder="Enter Bio here"> </x-backend.text-area>
                        </div>

                        <div class="w-1/2 mt-6">
                            <x-backend.dropify type="file" name="file" label="File" :required="true" />
                        </div>
                    </div>

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
    <script type="text/javascript" src="https://jeremyfagis.github.io/dropify/dist/js/dropify.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.dropify').dropify({
                tpl: {
                    message: '<div class="dropify-message"><span class="file-icon"></span> <p style="font-size: 24px;">Upload file here</p></div>'
                }
            });
        })
    </script>
    <script src="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag@3.1.0/dist/js/multi-select-tag.js"></script>
    <script>
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
