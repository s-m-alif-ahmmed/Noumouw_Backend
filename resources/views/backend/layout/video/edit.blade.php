@extends('backend.app')

@section('title', 'Video')
@section('title_url')
    <a href="{{ route('video.index') }}">Video</a>
@endsection
@section('tabName')
    <a href="{{ route('dashboard') }}">Home</a>
@endsection

{{-- Push additional styles if needed --}}
@push('styles')
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@24.7.0/build/css/intlTelInput.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.0/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="{{ asset('vendor/flasher/flasher.min.css') }}" rel="stylesheet">
    <style>

        .dropify-wrapper {
            height: 300px;
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
                <h1 class="text-xl">Edit  Video</h1>
                <a href="{{ route('video.index') }}"
                    class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600
                    focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring
                    active:ring-custom-100 dark:ring-custom-400/20">Back</a>
            </div>
            <form action="{{ route('video.update',$data->id) }}" id="video_upload" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                <input type="hidden" name="duration">
                <div class="w-full">
                    <div class="pb-5 flex gap-x-5">
                        <div class="w-1/2">
                            <x-backend.input type="text" name="title" label="Title" :value="$data->title" :required="true" />
                        </div>
                        <div class="w-1/2">
                            <x-backend.select2-single name="instructor_id" label="Instructor" :required="true">
                                <option value="">Select an Instructor</option>
                                @foreach ($instructors as $instructor)
                                    <option value="{{ $instructor->id }}"
                                            @if (old('instructor_id',$data->instructor_id) == $instructor->id) selected @endif>
                                        {{ $instructor->name }}
                                    </option>
                                @endforeach
                            </x-backend.select2-single>
                        </div>

                    </div>
                </div>

                <div class="flex gap-3 mb-5">
                    <div class="w-1/2 mt-2">
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

                <div class="w-1/2">
                    <x-backend.dropify type="file" name="file" label="Video" :src="$data->file" :required="true"
                                       onchange="calculateVideoDuration(this)" />
                </div>
                {{-- ------------------- Form Buttons ------------- --}}
                <div class="flex justify-start mt-6 gap-x-4">
                    <button type="submit"
                            class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">Submit</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <link rel="stylesheet" href="{{ asset('backend/js/datatables/jquery-3.7.0.js') }}">
    <script type="text/javascript" src="https://jeremyfagis.github.io/dropify/dist/js/dropify.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.dropify').dropify({
                tpl: {
                    message: '<div class="dropify-message"><span class="file-icon"></span> <p style="font-size: 24px;">Upload file here</p></div>'
                }
            });
        })
        function calculateVideoDuration(input) {
            const file = input.files[0];
            if (file && file.type.includes("video")) {
                const videoElement = document.createElement("video");
                videoElement.preload = "metadata";
                videoElement.src = URL.createObjectURL(file);

                videoElement.onloadedmetadata = function() {
                    // Try to find hidden input in parent (for components) or document (for standalone)
                    let hiddenInput = input.parentElement.querySelector("input[name='duration']");
                    if (!hiddenInput) {
                        hiddenInput = document.querySelector("input[name='duration']");
                    }

                    if (hiddenInput) {
                        const duration = videoElement.duration;
                        const hours = Math.floor(duration / 3600);
                        const minutes = Math.floor((duration % 3600) / 60);
                        const seconds = Math.floor(duration % 60);
                        
                        const formattedDuration = 
                            String(hours).padStart(2, '0') + ":" + 
                            String(minutes).padStart(2, '0') + ":" + 
                            String(seconds).padStart(2, '0');
                            
                        $(hiddenInput).val(formattedDuration);
                    }
                    URL.revokeObjectURL(videoElement.src);
                };
            } else {
                if (typeof flasher !== 'undefined') flasher.error('Invalid Video');
            }
        }
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
