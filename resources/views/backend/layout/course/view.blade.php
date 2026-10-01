@extends('backend.app')

@section('title', 'Course Details')
@section('title_url')
    <a href="{{ route('course.index') }}">Course Details</a>
@endsection
@section('tabName')
    Home
@endsection

@push('styles')
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.0/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="{{ asset('vendor/flasher/flasher.min.css') }}" rel="stylesheet">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag@4.0.1/dist/css/multi-select-tag.min.css">

    <!-- FilePond CSS -->
    <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet">
    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet">

    <style>
        .dropify-wrapper {
            height: 120px !important;
            border-radius: 12px;
            border: 2px dashed #e2e8f0;
        }

        .premium-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
            border: 1px solid #f1f5f9;
        }

        /* Modern Datatable Styling */
        #course_details_wrapper .dataTables_length select {
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            padding: 4px 8px;
            outline: none;
        }

        #course_details_wrapper .dataTables_filter input {
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            padding: 8px 16px;
            outline: none;
            width: 250px;
            transition: all 0.3s;
        }

        #course_details_wrapper .dataTables_filter input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        #course_details {
            border-collapse: separate !important;
            border-spacing: 0 12px !important;
            width: 100% !important;
            min-width: 760px;
            border: none !important;
        }

        #course_details thead th {
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.05em;
            padding: 16px !important;
            border: none !important;
        }

        #course_details tbody tr {
            background: #ffffff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
            transition: all 0.3s;
        }

        #course_details tbody tr:hover {
            /* transform: scale(1.005); */
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            background: #fdfdfd;
        }

        #course_details tbody td {
            padding: 12px !important;
            border: none !important;
            vertical-align: middle;
        }

        #course_details tbody tr td:first-child {
            border-radius: 12px 0 0 12px;
        }

        #course_details tbody tr td:last-child {
            border-radius: 0 12px 12px 0;
        }

        #course_details th:last-child,
        #course_details td:last-child {
            text-align: right;
            width: 150px;
        }

        #course_details td:last-child>div {
            justify-content: flex-end;
        }

        .dataTables_paginate .paginate_button:hover:not(.current) {
            background: #f1f5f9 !important;
        }

        .course-table-scroll {
            width: 100%;
            overflow: visible;
            padding-bottom: 2px;
        }

        #course_details_wrapper .dataTables_scroll {
            width: 100%;
        }

        #course_details_wrapper .dataTables_scrollBody {
            width: 100% !important;
            overflow-x: auto !important;
            overflow-y: hidden !important;
            -webkit-overflow-scrolling: touch;
        }

        #course_details_wrapper .dataTables_scrollBody table,
        #course_details_wrapper .dataTables_scrollHead table {
            min-width: 760px !important;
        }

        #course_details_wrapper .dataTables_scrollBody thead {
            display: none;
        }

        #course_details_wrapper .dataTables_scrollBody::-webkit-scrollbar {
            height: 8px;
        }

        #course_details_wrapper .dataTables_scrollBody::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 999px;
        }

        @media (max-width: 767px) {
            #main-content-wrapper {
                padding-left: 0.75rem !important;
                padding-right: 0.75rem !important;
            }

            .premium-card {
                border-radius: 14px;
            }

            .course-table-scroll {
                margin-left: -0.5rem;
                margin-right: -0.5rem;
            }

            #course_details_wrapper .dataTables_filter,
            #course_details_wrapper .dataTables_length {
                width: 100%;
            }

            #course_details_wrapper .dataTables_filter input {
                width: 100%;
            }

            #course_details {
                min-width: 760px;
            }

            #course_details_wrapper .dataTables_scrollBody table,
            #course_details_wrapper .dataTables_scrollHead table {
                min-width: 760px !important;
            }

            #course_details thead th,
            #course_details tbody td {
                padding: 10px !important;
            }
        }

        /* FilePond Custom Styling */
        .filepond--root {
            margin-bottom: 0;
            cursor: pointer;
            min-height: 120px;
        }

        .filepond--panel-root {
            background-color: #f8fafc;
            border: 2px dashed #e2e8f0;
            border-radius: 12px;
        }

        .filepond--drop-label {
            color: #64748b;
        }

        .filepond--drop-label label {
            font-size: 0.85rem;
            font-weight: 500;
        }

        .filepond--label-action {
            color: #3b82f6;
            text-decoration: none;
            font-weight: 600;
        }

        .filepond--item {
            width: calc(100% - 1em);
        }

        .filepond--credits {
            display: none;
        }

        /* Dropify custom height */
        .dropify-wrapper {
            height: 120px !important;
            border-radius: 12px !important;
        }

        #edit-question {
            top: 1rem !important;
            width: min(92vw, 42rem) !important;
            max-height: calc(100vh - 2rem);
        }

        #edit-question>div {
            width: 100% !important;
        }

        #edit-question>div>div:last-child {
            max-height: calc(100vh - 6rem);
            overflow-y: auto;
        }

        #modal-description,
        #modal-title,
        #modal-instructor,
        #modal-duration {
            overflow-wrap: anywhere;
        }

        @media (min-width: 768px) {
            #edit-question {
                top: 5rem !important;
                width: min(50vw, 42rem) !important;
            }
        }
    </style>
@endpush

@section('content')

    {{-- Content view modal --}}
    <x-backend.modal id="edit-question" title="Course Content Details" class="hidden w-1/2">
        <div class="p-3 sm:p-5 bg-white rounded-lg">
            <div class="space-y-4 sm:space-y-5">

                <!-- Header Info -->
                <div class="flex justify-between items-start border-b pb-4">
                    <div class="flex-1">
                        <h4 class="text-lg sm:text-xl font-bold text-gray-800" id="modal-title">Sample Title</h4>
                        <span id="modal-type-badge"
                            class="mt-2 inline-block px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                            Video
                        </span>
                    </div>
                </div>

                <div id="modal-meta-section" class="hidden grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div id="modal-instructor-wrap" class="hidden rounded-lg border border-slate-100 bg-slate-50 p-3 sm:p-4">
                        <h5 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Instructor</h5>
                        <p id="modal-instructor" class="text-sm font-semibold text-slate-700"></p>
                    </div>
                    <div id="modal-duration-wrap" class="hidden rounded-lg border border-slate-100 bg-slate-50 p-3 sm:p-4">
                        <h5 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Duration</h5>
                        <p id="modal-duration" class="text-sm font-semibold text-slate-700"></p>
                    </div>
                </div>

                <div id="modal-tags-section" class="hidden">
                    <h5 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-2">Tags:</h5>
                    <div id="modal-tags-list" class="flex flex-wrap gap-2"></div>
                </div>

                <!-- Description Section -->
                <div id="modal-description-section">
                    <h5 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-2">Description:</h5>
                    <p id="modal-description" class="text-gray-600 leading-relaxed text-sm sm:text-base"></p>
                </div>

                <div id="modal-thumbnail-section" class="hidden">
                    <h5 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-2">Thumbnail:</h5>
                    <img src="" id="modal-thumbnail" class="w-40 h-24 object-cover rounded-lg border border-slate-200"
                        alt="Content thumbnail">
                </div>

                <!-- Media Section (Video) -->
                <div id="modal-video-section" class="hidden">
                    <h5 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-2">Video:</h5>
                    <div class="rounded-lg overflow-hidden shadow-md max-w-md">
                        <video src="" id="modal-video" controls class="w-full max-h-56 bg-black"></video>
                    </div>
                </div>

                <!-- Media Section (Audio) -->
                <div id="modal-audio-section" class="hidden">
                    <h5 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-2">Audio:</h5>
                    <audio src="" id="modal-audio" controls class="w-full max-w-md"></audio>
                </div>

                <!-- Gallery Section (Activity) -->
                <div id="modal-gallery-section" class="hidden">
                    <h5 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-2">Activity Images:</h5>
                    <div id="modal-images-grid" class="flex flex-wrap gap-3">
                        <!-- Images dynamically injected -->
                    </div>
                </div>

                <!-- Evaluation Section -->
                <div id="modal-evaluation-section" class="hidden">
                    <h5 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-2">Evaluation Questions:</h5>
                    <div class="overflow-x-auto border rounded-lg">
                        <table class="w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Question
                                    </th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Answer</th>
                                    {{-- <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Link</th> --}}
                                </tr>
                            </thead>
                            <tbody id="modal-questions-list" class="divide-y divide-gray-200 text-sm">
                                <!-- Questions dynamically injected -->
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </x-backend.modal>

    {{-- Course show --}}
    <div class="premium-card overflow-hidden mb-8">
        <div class="p-4 sm:p-6 lg:p-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 lg:gap-8 items-start">
                {{-- Thumbnail --}}
                <div class="lg:col-span-4">
                    <div class="relative group">
                        <img class="w-full aspect-video object-cover rounded-2xl shadow-lg border-4 border-white transition-transform duration-300 group-hover:scale-[1.02]"
                            src="{{ $data->thumbnail ? asset($data->thumbnail) : asset('/backend/no-image.jpg') }}"
                            alt="Course Thumbnail">
                        <div class="absolute inset-0 rounded-2xl bg-black/5 group-hover:bg-transparent transition-colors">
                        </div>
                    </div>
                </div>

                {{-- Course Info --}}
                <div class="lg:col-span-8 flex flex-col h-full justify-between">
                    <div>
                        {{-- <div class="flex items-center gap-3 mb-2">
                            <span
                                class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-blue-50 text-blue-600 border border-blue-100">
                                Course ID: #{{ $data->id }}
                            </span>
                            <span
                                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold {{ $data->status == 'active' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-rose-50 text-rose-600 border border-rose-100' }}">
                                <span
                                    class="size-1.5 rounded-full {{ $data->status == 'active' ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                {{ ucfirst($data->status) }}
                            </span>
                        </div> --}}
                        <h1 class="text-3xl font-extrabold text-slate-800 mb-4">{{ $data->name }}</h1>
                        <div class="bg-slate-50 rounded-xl p-4 border border-slate-100 mb-6">
                            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Description</h4>
                            <p class="text-slate-600 leading-relaxed text-sm">
                                {{ $data->description ?? 'No description provided for this course.' }}
                            </p>
                        </div>
                    </div>

                    {{-- Button Section --}}
                    <div class="flex flex-wrap gap-2 sm:gap-3 mt-auto">
                        <button data-modal-open="add-video"
                            class="inline-flex items-center gap-2 bg-green-500 text-white px-5 py-2.5 rounded-xl hover:bg-green-600 transition-all shadow-lg shadow-green-500/20 font-medium text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="m22 8-6 4 6 4V8Z" />
                                <rect width="14" height="12" x="2" y="6" rx="2" ry="2" />
                            </svg>
                            Add Video
                        </button>
                        <button data-modal-open="add-activity"
                            class="inline-flex items-center gap-2 bg-orange-500 text-white px-5 py-2.5 rounded-xl hover:bg-orange-600 transition-all shadow-lg shadow-orange-500/20 font-medium text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z" />
                                <polyline points="14 2 14 8 20 8" />
                            </svg>
                            Add Activity
                        </button>
                        <button data-modal-open="add-podcast"
                            class="inline-flex items-center gap-2 bg-sky-500 text-white px-5 py-2.5 rounded-xl hover:bg-sky-600 transition-all shadow-lg shadow-sky-500/20 font-medium text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M12 2a3 3 0 0 0-3 3v7a3 3 0 0 0 6 0V5a3 3 0 0 0-3-3Z" />
                                <path d="M19 10v2a7 7 0 0 1-14 0v-2" />
                                <line x1="12" x2="12" y1="19" y2="22" />
                            </svg>
                            Add Podcast
                        </button>
                        <button data-modal-open="add-evaluation"
                            class="inline-flex items-center gap-2 bg-indigo-500 text-white px-5 py-2.5 rounded-xl hover:bg-indigo-600 transition-all shadow-lg shadow-indigo-500/20 font-medium text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10Z" />
                                <path d="m9 12 2 2 4-4" />
                            </svg>
                            Add Evaluation
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Course Content Table --}}
    <div class="premium-card overflow-hidden mb-8">
        <div class="p-4 sm:p-6 lg:p-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6 lg:mb-8">
                <div>
                    <h2 class="text-xl font-bold text-slate-800">Course Content</h2>
                    <p class="text-slate-500 text-sm">Manage lessons, activities, and evaluations for this course</p>
                </div>
                <div class="bg-slate-50 px-4 py-2 rounded-xl border border-slate-100 self-start sm:self-auto">
                    <span class="text-xs font-bold text-slate-400 uppercase">Total Items: </span>
                    <span id="total-content-count" class="text-sm font-bold text-slate-700">Loading...</span>
                </div>
            </div>

            <div class="course-table-scroll">
                <table id="course_details" class="w-full">
                    <thead>
                        <tr>
                            <th class="w-16">#</th>
                            <th>Content Title</th>
                            <th>Type</th>
                            <th>Details</th>
                            <th>Tags</th>
                            <th>Asset</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="sortable">
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Crete Video modal --}}
    <x-backend.modal id="add-video" class="w-full md:w-[60rem]" title="Create Video">
        <form id="video_upload" enctype="multipart/form-data" class="p-4">
            @csrf @method('POST')
            <div class="grid grid-cols-1 gap-4 xl:grid-cols-12">

                <input type="hidden" name="duration" class="video-duration-input">

                {{-- ------------------- Title ------------- --}}
                <div class="xl:col-span-12">
                    <x-backend.input-ajax type="text" name="title" label="Title" :required="true"
                        placeholder="Enter Title here" />
                </div>

                {{-- ------------------- Image ------------- --}}
                <div class="xl:col-span-12">
                    <label for="image" class="inline-block mb-2 text-base font-medium">Thumbnail <span
                            class="text-red-500">*</span></label>
                    <input type="file" name="image" id="image" class="dropify" data-height="120" required>
                    <span id="image-error-message" class="text-red-500 text-sm error-message"></span>
                </div>

                {{-- ------------------- Instructor  ------------- --}}
                <div class="xl:col-span-12">
                    <x-backend.select2-single name="instructor_id" label="Instructor" :required="true" :ajax="true">
                        <option value="">Select an Instructor</option>
                        @foreach ($instructors as $instructor)
                            <option value="{{ $instructor->id }}" @if (old('instructor_id') == $instructor->id) selected @endif>
                                {{ $instructor->name }}
                            </option>
                        @endforeach
                    </x-backend.select2-single>
                </div>

                {{-- ------------------- Coures  ------------- --}}
                <div class="xl:col-span-12">
                    <x-backend.select2-single name="course_id" label="Course" :required="true" :ajax="true">
                        <option value="">Select an Course</option>
                        @foreach ($courses as $course)
                            <option value="{{ $course->id }}" @if (old('course_id') == $course->id || (isset($data) && $data->id == $course->id)) selected @endif>
                                {{ $course->name }}
                            </option>
                        @endforeach
                    </x-backend.select2-single>
                </div>

                {{-- ------------------- Tags  ------------- --}}
                <div class="xl:col-span-12">
                    <label for="tags" class="inline-block mb-2 text-base font-medium">Tags<span
                            style="color: red">*</span></label>
                    <select name="tags[]" id="tags" multiple class="border border-gray-300 rounded-lg mt-1 p-2">
                        <option disabled>Select a Tag</option>
                        @foreach ($tags as $tag)
                            <option value="{{ $tag->id }}" @if (in_array($tag->id, old('tags', []))) selected @endif>
                                {{ $tag->title }}</option>
                        @endforeach
                    </select>
                    <span id="tags-error-message" class="text-red-500 text-sm error-message"></span>
                    @error('tags')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                    @error('tags.*')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                {{-- ------------------- Video  ------------- --}}
                <div class="xl:col-span-12">
                    <label for="video_file" class="inline-block mb-2 text-base font-medium">Video Lecture <span
                            class="text-red-500">*</span></label>
                    {{-- <input type="file" id="video_file" name="file" class="filepond" data-allow-reorder="true" data-max-file-size="512MB" required> --}}
                    <input type="file" id="video_file" class="dropify video-chunk-input" data-height="120"
                        onchange="handleVideoSelection(this, '#add-video .video-duration-input')" required>
                    <input type="hidden" name="file_path" id="add_video_file_path">
                    <span id="file_path-error-message" class="text-red-500 text-sm error-message"></span>

                    <div id="add_video_progress_container" class="hidden mt-4">
                        <div class="flex justify-between mb-1">
                            <span class="text-sm font-medium text-blue-700">Uploading Video...</span>
                            <span class="text-sm font-medium text-blue-700" id="add_upload_percentage">0%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-blue-600 h-2.5 rounded-full" id="add_upload_bar" style="width: 0%"></div>
                        </div>
                    </div>
                </div>


            </div>

            <div class="flex justify-end gap-2 mt-6">
                <button type="submit"
                    class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600
                    focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20 px-8">
                    Add Video
                </button>
            </div>
        </form>

    </x-backend.modal>

    {{-- Crete Activity modal --}}
    <x-backend.modal id="add-activity" class="w-full md:w-1/2 max-w-6xl justify-center" title="Create Activity">
        <form id="activity-form" enctype="multipart/form-data">
            @csrf @method('POST')
            <div class="grid grid-cols-1 gap-4 xl:grid-cols-12">

                <input type="hidden" name="duration">

                {{-- ------------------- Title ------------- --}}
                <div class="xl:col-span-12">
                    <x-backend.input-ajax type="text" id="name" name="title" label="Activity Name"
                        :required="true" placeholder="Enter Activity Name here" />
                </div>

                {{-- ------------------- Coures  ------------- --}}
                <div class="xl:col-span-12">
                    <x-backend.select2-single name="course_id" label="Select an Course" :required="true"
                        :ajax="true">
                        <option value="">Select an Course</option>
                        @foreach ($courses as $course)
                            <option value="{{ $course->id }}" @if (old('course_id') == $course->id || (isset($data) && $data->id == $course->id)) selected @endif>
                                {{ $course->name }}
                            </option>
                        @endforeach
                    </x-backend.select2-single>
                </div>

                {{-- ------------------- Description  ------------- --}}
                <div class="xl:col-span-12">
                    <x-backend.text-area name="description" label="Description" :required="true" :ajax="true"
                        placeholder="Enter Description here"> </x-backend.text-area>
                </div>

                {{-- ------------------- Tags  ------------- --}}
                <div class="xl:col-span-12">
                    <label for="activity-tags" class="inline-block mb-2 text-base font-medium">Tags<span
                            style="color: red">*</span></label>
                    <select name="tags[]" id="activity-tags" multiple
                        class="border border-gray-300 rounded-lg mt-1 p-2">
                        <option disabled>Select a Tag</option>
                        @foreach ($tags as $tag)
                            <option value="{{ $tag->id }}" @if (in_array($tag->id, old('tags', []))) selected @endif>
                                {{ $tag->title }}</option>
                        @endforeach
                    </select>
                    <span id="tags-error-message" class="text-red-500 text-sm error-message"></span>
                    @error('tags')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                    @error('tags.*')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                {{-- ------------------- Images  ------------- --}}
                <div class="xl:col-span-12">
                    <div class="flex items-center justify-between gap-5 mt-5 mb-3">
                        <label for="gallery_images" class="inline-block  text-base font-medium">Activity Images</label>
                        <button type="button" id="add-gallery-image"
                            class="text-white bg-green-500 border-green-500 btn hover:bg-green-600 !p-1">
                            Add image
                        </button>
                    </div>
                    <div class="grid grid-cols-12 gap-3" id="gallery-images-section">
                        <div class="col-span-4 single-gallery-image">
                            <input type="file" name="images[]" id="gallery_0"
                                class="dropify form-input border-slate-200
                                dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300
                                dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800
                                placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                data-height="200" />
                            @error('gallery_images')
                                <div class="text-red-600 text-sm font-semibold">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="alert alert-danger">
                        <span id="images-error-message" class="text-red-500 text-sm error-message"></span>
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


            </div>

            <div class="flex justify-end gap-2 mt-4">
                <button type="submit"
                    class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600
                    focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">
                    Add Activity
                </button>
            </div>
        </form>
    </x-backend.modal>

    {{-- Crete Podcast modal --}}
    <x-backend.modal id="add-podcast" class="w-full md:w-1/2 max-w-6xl justify-center" title="Create Podcast">

        <form id="podcast-form" enctype="multipart/form-data">
            @csrf @method('POST')
            <div class="grid grid-cols-1 gap-4 xl:grid-cols-12">


                {{-- ------------------- Title ------------- --}}
                <div class="xl:col-span-12">
                    <x-backend.input-ajax type="text" name="title" label="Title" :required="true"
                        placeholder="Enter Title here" />
                </div>

                {{-- ------------------- Instructor  ------------- --}}
                <div class="xl:col-span-12">

                    @php
                        $selectedCourse = null;
                    @endphp
                    <x-backend.select2-single name="instructor_id" label="Instructor" :required="true"
                        :ajax="true">
                        <option value="">Select an Instructor</option>
                        @foreach ($instructors as $instructor)
                            <option value="{{ $instructor->id }}" @if (old('instructor_id') == $instructor->id || (isset($data) && $data->id == $instructor->id)) selected @endif>
                                {{ $instructor->name }}
                            </option>
                        @endforeach
                    </x-backend.select2-single>
                </div>

                {{-- ------------------- Coures  ------------- --}}
                <div class="xl:col-span-12">
                    <x-backend.select2-single name="course_id" label="Select an Course" :required="true"
                        :ajax="true">
                        <option value="">Select an Course</option>
                        @foreach ($courses as $course)
                            <option value="{{ $course->id }}" @if (old('course_id') == $course->id || (isset($data) && $data->id == $course->id)) selected @endif>
                                {{ $course->name }}
                            </option>
                        @endforeach
                    </x-backend.select2-single>
                </div>

                {{-- ------------------- Tags  ------------- --}}
                <div class="xl:col-span-12">
                    <label for="activity-tags" class="inline-block mb-2 text-base font-medium">Tags<span
                            style="color: red">*</span></label>
                    <select name="tags[]" id="podcast-tags" multiple class="border border-gray-300 rounded-lg mt-1 p-2">
                        <option disabled>Select a Tag</option>
                        @foreach ($tags as $tag)
                            <option value="{{ $tag->id }}" @if (in_array($tag->id, old('tags', []))) selected @endif>
                                {{ $tag->title }}</option>
                        @endforeach
                    </select>
                    <span id="tags-error-message" class="text-red-500 text-sm error-message"></span>
                    @error('tags')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                    @error('tags.*')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                {{-- ------------------- Description  ------------- --}}
                <div class="xl:col-span-12">
                    <x-backend.text-area name="description" label="Description" :required="true" :ajax="true"
                        placeholder="Enter Description here"> </x-backend.text-area>
                </div>



                {{-- ------------------- Images  ------------- --}}
                <div class="xl:col-span-12">
                    <x-backend.dropify type="file" name="file" label="File" :required="true" />
                </div>


            </div>

            <div class="flex justify-end gap-2 mt-4">
                <button type="submit"
                    class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600
                    focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">
                    Add Podcast
                </button>
            </div>
        </form>
    </x-backend.modal>

    {{-- Crete Evaluation modal --}}
    <x-backend.modal id="add-evaluation" class="w-full md:w-1/2 max-w-6xl justify-center" title="Create Evaluation">
        <form id="evaluation-form" enctype="multipart/form-data">
            @csrf @method('POST')
            <div class="grid grid-cols-1 gap-4 xl:grid-cols-12">

                {{-- ------------------- Title ------------- --}}
                <div class="xl:col-span-12">
                    <x-backend.input-ajax type="text" name="title" label="Title" :required="true"
                        placeholder="Enter Title here" />
                </div>

                {{-- ------------------- Coures  ------------- --}}
                <div class="xl:col-span-12">
                    <x-backend.select2-single name="course_id" label="Select an Course" :required="true"
                        :ajax="true">
                        <option value="">Select an Course</option>
                        @foreach ($courses as $course)
                            <option value="{{ $course->id }}" @if (old('course_id') == $course->id || (isset($data) && $data->id == $course->id)) selected @endif>
                                {{ $course->name }}
                            </option>
                        @endforeach
                    </x-backend.select2-single>
                </div>

                {{-- ------------------- Tags  ------------- --}}
                <div class="xl:col-span-12">
                    <label for="evaluation-tags" class="inline-block mb-2 text-base font-medium">Tags<span
                            style="color: red">*</span></label>
                    <select name="tags[]" id="evaluation-tags" multiple
                        class="border border-gray-300 rounded-lg mt-1 p-2">
                        <option disabled>Select a Tag</option>
                        @foreach ($tags as $tag)
                            <option value="{{ $tag->id }}" @if (in_array($tag->id, old('tags', []))) selected @endif>
                                {{ $tag->title }}</option>
                        @endforeach
                    </select>
                    <span id="tags-error-message" class="text-red-500 text-sm error-message"></span>
                    @error('tags')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                    @error('tags.*')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                {{-- ------------------- Question Block  ------------- --}}
                <div class="xl:col-span-12">

                    <div id="questions-container" class="space-y-4">
                        <!-- Questions will be added here -->
                    </div>
                    <span id="questions-error-message" class="text-red-500 text-sm error-message"></span>

                    <div class="flex items-center justify-between gap-5 mb-4">
                        <label class="inline-block text-base font-medium">Questions<span
                                class="text-red-500">*</span></label>
                        <button type="button" id="add-question-btn"
                            class="text-white bg-green-500 border-green-500 btn hover:bg-green-600 !p-1">
                            Add Question
                        </button>
                    </div>
                </div>


            </div>

            <div class="flex justify-end gap-2 mt-4">
                <button type="submit"
                    class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600
                    focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">
                    Add Evaluation
                </button>
            </div>
        </form>
    </x-backend.modal>


    {{-- Edit Video modal --}}
    <x-backend.modal id="edit-video" class="w-full md:w-[60rem]" title="Edit Video">
        <form id="video_update" method="POST" action="#" enctype="multipart/form-data" class="p-4">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 gap-4 xl:grid-cols-12">

                <input type="hidden" name="duration" class="video-duration-input">

                {{-- ------------------- Title ------------- --}}
                <div class="xl:col-span-12">
                    <x-backend.input-ajax type="text" name="title" label="Title" :required="true"
                        placeholder="Enter Title here" />
                </div>

                {{-- ------------------- New Image ------------- --}}
                <div class="xl:col-span-12">
                    <label for="oldImage" class="inline-block mb-2 text-base font-medium">Thumbnail <span
                            class="text-red-500">*</span></label>
                    <input type="file" name="image" id="oldImage" class="dropify" data-height="120">
                </div>
                {{-- ------------------- Old Image   ------------- --}}
                <div class="xl:col-span-12">
                    <input type="hidden" name="remove_image" id="remove_image_flag" value="0">
                    <div id="old-image" class="relative inline-block group">
                        <!-- Image preview will be here -->
                    </div>
                </div>

                {{-- ------------------- Instructor  ------------- --}}
                <div class="xl:col-span-12">
                    <x-backend.select2-single name="instructor_id" label="Instructor" :required="true">
                        <option value="">Select an Instructor</option>
                        @foreach ($instructors as $instructor)
                            <option value="{{ $instructor->id }}" @if (old('instructor_id') == $instructor->id) selected @endif>
                                {{ $instructor->name }}
                            </option>
                        @endforeach
                    </x-backend.select2-single>
                </div>

                {{-- ------------------- Coures  ------------- --}}
                <div class="xl:col-span-12">
                    <x-backend.select2-single name="course_id" label="Course" :required="true">
                        <option value="">Select an Course</option>
                        @foreach ($courses as $course)
                            <option value="{{ $course->id }}" @if (old('course_id') == $course->id || (isset($data) && $data->id == $course->id)) selected @endif>
                                {{ $course->name }}
                            </option>
                        @endforeach
                    </x-backend.select2-single>
                </div>

                {{-- ------------------- Tags  ------------- --}}
                <div class="xl:col-span-12">
                    <label for="tags" class="inline-block mb-2 text-base font-medium">Tags<span
                            style="color: red">*</span></label>
                    <select name="tags[]" id="edit-video-tags" multiple
                        class="border border-gray-300 rounded-lg mt-1 p-2">
                        <option disabled>Select a Tag</option>
                        @foreach ($tags as $tag)
                            <option value="{{ $tag->id }}">
                                {{ $tag->title }}
                            </option>
                        @endforeach
                    </select>
                    @error('tags')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                    @error('tags.*')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="xl:col-span-12">
                    <label for="edit_video_file" class="inline-block mb-2 text-base font-medium">Video Lecture <span
                            class="text-red-500">*</span></label>
                    {{-- <input type="file" id="edit_video_file" name="file" class="filepond" data-allow-reorder="true" data-max-file-size="512MB"> --}}
                    <input type="file" id="edit_video_file" class="dropify video-chunk-input" data-height="120"
                        onchange="handleVideoSelection(this, '#edit-video .video-duration-input')">
                    <input type="hidden" name="file_path" id="edit_video_file_path">

                    <div id="edit_video_progress_container" class="hidden mt-4">
                        <div class="flex justify-between mb-1">
                            <span class="text-sm font-medium text-blue-700">Uploading Video...</span>
                            <span class="text-sm font-medium text-blue-700" id="edit_upload_percentage">0%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-blue-600 h-2.5 rounded-full" id="edit_upload_bar" style="width: 0%"></div>
                        </div>
                    </div>
                </div>

                {{-- ------------------- Old Video File  ------------- --}}
                <div class="xl:col-span-12">
                    <input type="hidden" name="remove_file" id="remove_video_flag" value="0">
                    <div id="video-audio" class="relative inline-block group"></div>
                </div>


            </div>

            <div class="flex justify-end gap-2 mt-4">
                <button type="submit"
                    class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600
                    focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">
                    Update Video
                </button>
            </div>
        </form>
    </x-backend.modal>

    {{-- Edit Activity modal --}}
    <x-backend.modal id="edit-activity" class="w-full md:w-1/2 max-w-6xl justify-center" title="Edit Activity">
        <form id="activity-update" method="POST" action="#" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 gap-4 xl:grid-cols-12">

                <input type="hidden" name="duration">

                {{-- ------------------- Title ------------- --}}
                <div class="xl:col-span-12">
                    <x-backend.input type="text" id="name" name="title" label="Activity Name"
                        :required="true" />
                </div>

                {{-- ------------------- Coures  ------------- --}}
                <div class="xl:col-span-12">
                    <x-backend.select2-single name="course_id" label="Select an Course" :required="true">
                        <option value="">Select an Course</option>
                        @foreach ($courses as $course)
                            <option value="{{ $course->id }}" @if (old('course_id') == $course->id || (isset($data) && $data->id == $course->id)) selected @endif>
                                {{ $course->name }}
                            </option>
                        @endforeach
                    </x-backend.select2-single>
                </div>

                {{-- ------------------- Description  ------------- --}}
                <div class="xl:col-span-12">
                    <x-backend.text-area name="description" label="Description" :required="true"
                        placeholder="Enter Description here"> </x-backend.text-area>
                </div>


                {{-- ------------------- Tags  ------------- --}}
                <div class="xl:col-span-12">
                    <label for="activity-tags" class="inline-block mb-2 text-base font-medium">Tags<span
                            style="color: red">*</span></label>
                    <select name="tags[]" id="edit-activity-tags" multiple
                        class="border border-gray-300 rounded-lg mt-1 p-2">
                        <option disabled>Select a Tag</option>
                        @foreach ($tags as $tag)
                            <option value="{{ $tag->id }}" @if (in_array($tag->id, old('tags', []))) selected @endif>
                                {{ $tag->title }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- ------------------- Images  ------------- --}}
                <div class="xl:col-span-12">
                    <div class="flex items-center justify-between gap-5 mt-5 mb-3">
                        <label for="gallery_images" class="inline-block  text-base font-medium">Activity Images</label>
                        <button type="button" id="edit-add-image"
                            class="text-white bg-green-500 border-green-500 btn hover:bg-green-600 !p-1">
                            Add image
                        </button>
                    </div>
                    <div class="" id="add-images-edit">
                        <div class="col-span-4 single-gallery-image">
                            <input type="file" name="images[]" id="gallery_edit_0"
                                class="dropify form-input border-slate-200
                                dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300
                                dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800
                                placeholder:text-slate-400 dark:placeholder:text-zink-200"
                                data-height="200" />
                        </div>
                    </div>
                </div>

                {{-- ------------------- Old Activity Images  ------------- --}}
                <div class="xl:col-span-12">
                    <div id="removed-images-container"></div>
                    <div id="old-activity-images" class="flex flex-wrap gap-3"></div>
                </div>
            </div>

            <div class="flex justify-end gap-2 mt-4">
                <button type="submit"
                    class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600
                    focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">
                    Update Activity
                </button>
            </div>
        </form>
    </x-backend.modal>

    {{-- Edit Podcast modal --}}
    <x-backend.modal id="edit-podcast" class="w-full md:w-1/2 max-w-6xl justify-center" title="Edit Podcast">
        <form id="podcast-update" method="POST" action="#" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 gap-4 xl:grid-cols-12">

                {{-- ------------------- Title ------------- --}}
                <div class="xl:col-span-12">
                    <x-backend.input type="text" name="title" label="Title" :required="true" />
                </div>

                {{-- ------------------- Instructor  ------------- --}}
                <div class="xl:col-span-12">

                    @php
                        $selectedCourse = null;
                    @endphp
                    <x-backend.select2-single name="instructor_id" label="Instructor" :required="true">
                        <option value="">Select an Instructor</option>
                        @foreach ($instructors as $instructor)
                            <option value="{{ $instructor->id }}" @if (old('instructor_id') == $instructor->id || (isset($data) && $data->id == $instructor->id)) selected @endif>
                                {{ $instructor->name }}
                            </option>
                        @endforeach
                    </x-backend.select2-single>
                </div>

                {{-- ------------------- Coures  ------------- --}}
                <div class="xl:col-span-12">
                    <x-backend.select2-single name="course_id" label="Select an Course" :required="true">
                        <option value="">Select an Course</option>
                        @foreach ($courses as $course)
                            <option value="{{ $course->id }}" @if (old('course_id') == $course->id || (isset($data) && $data->id == $course->id)) selected @endif>
                                {{ $course->name }}
                            </option>
                        @endforeach
                    </x-backend.select2-single>
                </div>

                {{-- ------------------- Tags  ------------- --}}
                <div class="xl:col-span-12">
                    <label for="activity-tags" class="inline-block mb-2 text-base font-medium">Tags<span
                            style="color: red">*</span></label>
                    <select name="tags[]" id="edit-podcast-tags" multiple
                        class="border border-gray-300 rounded-lg mt-1 p-2">
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

                {{-- ------------------- Description  ------------- --}}
                <div class="xl:col-span-12">
                    <x-backend.text-area name="description" label="Description" :required="true"
                        placeholder="Enter Description here"> </x-backend.text-area>
                </div>



                {{-- ------------------- Audio File  ------------- --}}
                <div class="xl:col-span-12">
                    <x-backend.dropify type="file" name="file" label="File" :required="true" />
                </div>

                {{-- ------------------- Old audio File  ------------- --}}
                <div class="xl:col-span-12">
                    <input type="hidden" name="remove_file" id="remove_podcast_flag" value="0">
                    <div id="old-audio" class="relative inline-block group"></div>
                </div>

            </div>

            <div class="flex justify-end gap-2 mt-4">
                <button type="submit"
                    class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600
                    focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">
                    Update Podcast
                </button>
            </div>
        </form>
    </x-backend.modal>

    {{-- Edit Evaluation modal --}}
    <x-backend.modal id="edit-evaluation" class="w-full md:w-1/2 max-w-6xl justify-center" title="Edit Evaluation">
        <form id="evaluation-update" method="POST" action="#">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-4 xl:grid-cols-12">

                {{-- ------------------- Title ------------- --}}
                <div class="xl:col-span-12">
                    <x-backend.input type="text" name="title" label="Title" :required="true" />
                </div>

                {{-- ------------------- Coures  ------------- --}}
                <div class="xl:col-span-12">
                    <x-backend.select2-single name="course_id" label="Select an Course" :required="true">
                        <option value="">Select an Course</option>
                        @foreach ($courses as $course)
                            <option value="{{ $course->id }}" @if (old('course_id') == $course->id || (isset($data) && $data->id == $course->id)) selected @endif>
                                {{ $course->name }}
                            </option>
                        @endforeach
                    </x-backend.select2-single>
                </div>

                {{-- ------------------- Tags  ------------- --}}
                <div class="xl:col-span-12">
                    <label for="evaluation-tags" class="inline-block mb-2 text-base font-medium">Tags<span
                            style="color: red">*</span></label>
                    <select name="tags[]" id="edit-evaluation-tags" multiple
                        class="border border-gray-300 rounded-lg mt-1 p-2">
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
                    {{-- ------------------- Question Block  ------------- --}}
                    <div class="xl:col-span-12">
                        <div class="flex items-center justify-between gap-5 mb-4 mt-2">
                            <label class="inline-block text-base font-medium">Questions<span
                                    class="text-red-500">*</span></label>
                            <button type="button" id="edit-add-question-btn"
                                class="text-white bg-green-500 border-green-500 btn hover:bg-green-600 !p-1">
                                Add Question
                            </button>
                        </div>
                        <div id="edit-questions-container" class="space-y-4">
                            <!-- Questions will be added here -->
                        </div>
                    </div>

                </div>

                {{-- <div class="flex justify-end gap-2 mt-4">
                <button type="submit"
                        class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600
                    focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">
                    Update Evaluation
                </button>
            </div> --}}
                <div class="xl:col-span-12 mt-4 flex justify-end">
                    <button type="submit"
                        class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600
                    focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">Update Evaluation</button>
                </div>
            </div>
        </form>
    </x-backend.modal>

@endsection


@push('scripts')
    <!-- FilePond JS -->
    <script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
    <script src="https://unpkg.com/filepond/dist/filepond.js"></script>

    <script>
        // Register FilePond plugins
        /* FilePond.registerPlugin(
            FilePondPluginFileValidateType,
            FilePondPluginFileValidateSize,
            FilePondPluginImagePreview
        );

        // Global FilePond configuration
        FilePond.setOptions({
            server: {
                url: "{{ config('filepond.server.url') }}",
                process: '',
                revert: '',
                patch: '?patch=',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            },
            chunkUploads: true,
            chunkSize: 2 * 1024 * 1024, // 2MB chunks
        }); */
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>


    <script src="{{ asset('backend/js/datatables/data-tables.min.js') }}"></script>
    <script src="{{ asset('backend/js/datatables/data-tables.tailwindcss.min.js') }}"></script>
    <!--buttons dataTables-->
    <script src="{{ asset('backend/js/datatables/datatables.buttons.min.js') }}"></script>
    <script src="{{ asset('backend/js/datatables/jszip.min.js') }}"></script>
    <script src="{{ asset('backend/js/datatables/pdfmake.min.js') }}"></script>
    <script src="{{ asset('backend/js/datatables/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('backend/js/datatables/buttons.print.min.js') }}"></script>

    {{-- <script src="{{ asset('backend/js/datatables/datatables.init.js') }}"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.0/dist/sweetalert2.all.min.js"
        integrity="sha256-BpyIV7Y3e2pnqy8TQGXxsmOiQ4jXNDTOTBGL2TEJeDY=" crossorigin="anonymous"></script>

    {{-- Tags --}}
    {{-- <script src="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag@3.1.0/dist/js/multi-select-tag.js"></script> --}}
    <script src="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag@4.0.1/dist/js/multi-select-tag.min.js"></script>
    {{-- Drofify Image --}}
    <script type="text/javascript" src="https://jeremyfagis.github.io/dropify/dist/js/dropify.min.js"></script>

    <script>
        // Table list
        // Table list
        $(document).ready(function() {
            let table = $('#course_details').DataTable({
                processing: true,
                serverSide: true,
                order: [],
                scrollX: true,
                scrollCollapse: true,
                autoWidth: false,
                dom: '<"flex flex-col md:flex-row justify-between items-center mb-4 gap-4"l f>rt<"flex flex-col md:flex-row justify-between items-center mt-6 gap-4"i p>',
                ajax: {
                    url: "{{ route('course.show', $data->id) }}",
                    type: 'GET'
                },
                drawCallback: function(settings) {
                    $('#total-content-count').text(settings._iRecordsTotal);
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'title',
                        name: 'title',
                        orderable: true,
                        searchable: false,
                    },
                    {
                        data: 'type',
                        name: 'type',
                        orderable: true,
                        searchable: true,
                        render: function(data) {
                            let colors = {
                                'Video': 'bg-green-50 text-green-600 border-green-100',
                                'Activity': 'bg-orange-50 text-orange-600 border-orange-100',
                                'Podcast': 'bg-blue-50 text-blue-600 border-blue-100',
                                'Evaluation': 'bg-indigo-50 text-indigo-600 border-indigo-100'
                            };
                            let colorClass = colors[data] ||
                                'bg-slate-50 text-slate-600 border-slate-100';
                            return `<span class="px-2.5 py-1 rounded-lg border text-[11px] font-bold uppercase tracking-wider ${colorClass}">${data}</span>`;
                        }
                    },
                    {
                        data: 'summary',
                        name: 'summary',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'tags_data',
                        name: 'tags_data',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'asset_summary',
                        name: 'asset_summary',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-right'
                    },
                ],
                rowId: 'id'
            });

            // Make rows sortable
            $("#course_details tbody").sortable({
                items: "tr",
                cursor: "move",
                opacity: 0.6,
                update: function() {
                    let order = [];
                    $("#course_details tbody tr").each(function(index) {
                        let id = $(this).attr("id");
                        if (id) {
                            order.push({
                                id: id,
                                position: index + 1
                            });
                        }
                    });

                    $.ajax({
                        url: "{{ route('contents.updateOrder') }}",
                        method: "POST",
                        data: {
                            order: order,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            if (response.success) {
                                flasher.success(response.message);
                                table.ajax.reload(null, false);
                            } else {
                                flasher.error("Reordering failed.");
                            }
                        },
                        error: function() {
                            flasher.error("An error occurred while updating the order.");
                        }
                    });
                }
            });
        });


        // Delete Confirm
        function showDeleteConfirm(id) {
            event.preventDefault();
            Swal.fire({
                title: 'Are you sure you want to delete this record?',
                text: 'If you delete this, it will be gone forever.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteItem(id);
                }
            });
        }

        // Delete Button
        function deleteItem(id) {
            let url = '{{ route('course.content.destroy', ':id') }}';

            let csrfToken = '{{ csrf_token() }}';
            $.ajax({
                type: "DELETE",
                url: url.replace(':id', id),
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(resp) {
                    // Reload DataTable
                    $('#basic_tables').DataTable().ajax.reload();
                    if (resp.success === true) {
                        // show toast message
                        $('#course_details').DataTable().ajax.reload(null, false);
                        flasher.success(resp.message);
                    } else if (resp.errors) {
                        flasher.error(resp.errors[0]);
                    } else {
                        flasher.error(resp.message);
                    }
                },
                error: function(error) {
                    flasher.error(error.responseJSON.message);
                }
            });
        }

        // Edit Button
        $(document).ready(function() {

            $('body').on('click', '.edit', function() {
                var id = $(this).data('id');
                var url = "{{ route('course.content.details', ':id') }}".replace(':id', id);
                $.ajax({
                    url: url,
                    type: 'get',
                    success: function(data) {
                        NProgress.done();
                        if (data.success) {

                            openEditModalById('edit-question', data.data);
                        } else {
                            flasher.error('Somethings went wrong... Try again later.');
                        }
                    },
                    error: function(errors) {
                        flasher.error(errors.responseJSON.message);
                    }
                });
            });


            function escapeHtml(value) {
                return $('<div>').text(value ?? '').html();
            }

            function openEditModalById(modalId, data) {
                const modal = $('#' + modalId);
                modal.removeClass('hidden').addClass('open');
                stopModalMedia(modalId);

                // Clear previous data
                $('#modal-meta-section, #modal-instructor-wrap, #modal-duration-wrap, #modal-tags-section, #modal-description-section, #modal-thumbnail-section, #modal-video-section, #modal-audio-section, #modal-gallery-section, #modal-evaluation-section')
                    .addClass('hidden');
                $('#modal-images-grid, #modal-questions-list, #modal-tags-list').empty();
                $('#modal-video, #modal-audio').attr('src', '');
                $('#modal-thumbnail').attr('src', '');
                $('#modal-instructor, #modal-duration, #modal-description').text('');

                // Populate Basic Info
                $('#modal-title').text(data.title || 'N/A');
                $('#modal-type-badge').text(data.type || 'N/A');

                if (data.instructor) {
                    $('#modal-meta-section, #modal-instructor-wrap').removeClass('hidden');
                    $('#modal-instructor').text(data.instructor);
                }

                if (data.duration) {
                    $('#modal-meta-section, #modal-duration-wrap').removeClass('hidden');
                    $('#modal-duration').text(data.duration);
                }

                if (data.tags && data.tags.length > 0) {
                    $('#modal-tags-section').removeClass('hidden');
                    data.tags.forEach(tag => {
                        $('#modal-tags-list').append(`
                            <span class="inline-flex px-2.5 py-1 rounded-lg bg-slate-100 text-slate-600 text-xs font-semibold">
                                ${escapeHtml(tag)}
                            </span>
                        `);
                    });
                }

                if (data.description) {
                    $('#modal-description-section').removeClass('hidden');
                    $('#modal-description').text(data.description);
                }

                // Populate Content Based on Type
                const type = (data.type || '').toLowerCase();
                if (type === 'video') {
                    if (data.video_image) {
                        $('#modal-thumbnail-section').removeClass('hidden');
                        $('#modal-thumbnail').attr('src', data.video_image);
                    }
                    $('#modal-video-section').removeClass('hidden');
                    if (data.video_url) {
                        $('#modal-video').attr('src', data.video_url);
                    }
                } else if (type === 'podcast') {
                    $('#modal-audio-section').removeClass('hidden');
                    if (data.podcast_file) {
                        $('#modal-audio').attr('src', data.podcast_file);
                    }
                } else if (type === 'activity') {
                    $('#modal-gallery-section').removeClass('hidden');
                    if (data.images && data.images.length > 0) {
                        data.images.forEach(img => {
                            $('#modal-images-grid').append(`
                                <div class="w-20 h-20 sm:w-24 sm:h-24 rounded overflow-hidden shadow-sm border">
                                    <img src="${escapeHtml(img)}" class="w-full h-full object-cover" alt="Activity image">
                                </div>
                            `);
                        });
                    } else {
                        $('#modal-images-grid').append('<p class="col-span-full text-sm text-slate-400 italic">No activity images uploaded.</p>');
                    }
                } else if (type === 'evaluation') {
                    $('#modal-evaluation-section').removeClass('hidden');
                    if (data.questions && data.questions.length > 0) {
                        data.questions.forEach(q => {
                            $('#modal-questions-list').append(`
                                <tr>
                                    <td class="px-4 py-2 border-b font-medium text-gray-700">${escapeHtml(q.title)}</td>
                                    <td class="px-4 py-2 border-b">
                                        <span class="px-2 py-0.5 rounded text-xs ${q.answer === 'Yes' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                                            ${escapeHtml(q.answer)}
                                        </span>
                                    </td>
                                    {{-- <td class="px-4 py-2 border-b">
                                        <a href="${q.link}" target="_blank" class="text-blue-600 hover:underline">View Link</a>
                                    </td> --}}
                                </tr>
                            `);
                        });
                    } else {
                        $('#modal-questions-list').append(`
                            <tr>
                                <td colspan="2" class="px-4 py-3 text-sm text-slate-400 italic">No questions added.</td>
                            </tr>
                        `);
                    }
                }
            }

            $('.close-modal').on('click', function() {
                stopModalMedia('edit-question');
                $('#edit-question').removeClass('open').addClass('hidden');
            });

        });
    </script>

    {{-- Edit Modal Show by Video, Podcast, Evaluation, Activity Here --}}
    <script>
        // List all your modal IDs here
        const allModals = [
            'edit-question',
            'add-video',
            'add-activity',
            'add-podcast',
            'add-evaluation',
            'edit-video',
            'edit-podcast',
            'edit-evaluation',
            'edit-activity'
        ];

        function openModal(modalId) {
            // First close all modals
            allModals.forEach(id => {
                stopModalMedia(id);
                document.getElementById(id)?.classList.add('hidden');
                document.getElementById(id + '-overlay')?.classList.add('hidden');
            });

            // Then open the requested modal
            document.getElementById(modalId)?.classList.remove('hidden');
            document.getElementById(modalId + '-overlay')?.classList.remove('hidden');
        }

        function closeModal(modalId) {
            stopModalMedia(modalId);
            document.getElementById(modalId)?.classList.add('hidden');
            document.getElementById(modalId + '-overlay')?.classList.add('hidden');
        }

        function stopModalMedia(modalId) {
            const modal = document.getElementById(modalId);
            if (!modal) return;

            modal.querySelectorAll('audio, video').forEach(media => {
                media.pause();
                media.currentTime = 0;
                media.removeAttribute('src');
                media.querySelectorAll('source').forEach(source => source.removeAttribute('src'));
                media.load();
            });
        }

        function getModalForm(targetId) {
            const target = document.getElementById(targetId);
            if (!target) return null;
            return target.matches('form') ? target : target.querySelector('form');
        }

        function clearInlineErrors(targetId) {
            const form = getModalForm(targetId);
            if (!form) return;
            form.querySelectorAll('.error-message').forEach(element => {
                element.textContent = '';
            });
            form.querySelectorAll('.is-invalid, .border-red-500').forEach(element => {
                element.classList.remove('is-invalid', 'border-red-500');
            });
        }

        function getFieldNames(field) {
            const parts = field.split('.');
            const bracketName = parts.length > 1 ?
                parts[0] + parts.slice(1).map(part => `[${part}]`).join('') :
                field;
            const baseField = parts[0];

            return {
                bracketName,
                baseField
            };
        }

        function findFieldInput(form, field) {
            const {
                bracketName,
                baseField
            } = getFieldNames(field);
            return form.querySelector(
                `[name="${field}"], [name="${bracketName}"], [name="${baseField}"], [name="${baseField}[]"]`);
        }

        function findErrorElement(form, field) {
            const exactDataElement = Array.from(form.querySelectorAll('[data-error-for]'))
                .find(element => element.dataset.errorFor === field);

            if (exactDataElement) return exactDataElement;

            const baseField = field.split('.')[0];
            const baseElement = form.querySelector(`#${baseField}-error-message`);
            if (baseElement) return baseElement;

            const input = findFieldInput(form, field);
            if (!input) return null;

            const errorElement = document.createElement('span');
            errorElement.id = `${baseField}-error-message`;
            errorElement.className = 'block mt-1 text-red-500 text-sm error-message';

            const dropifyWrapper = input.closest('.dropify-wrapper');
            const multiSelectWrapper = input.nextElementSibling?.classList?.contains('multi-select-tag') ?
                input.nextElementSibling :
                null;
            (dropifyWrapper || multiSelectWrapper || input).insertAdjacentElement('afterend', errorElement);

            return errorElement;
        }

        function markFieldInvalid(form, field) {
            const input = findFieldInput(form, field);

            if (input) {
                input.classList.add('is-invalid', 'border-red-500');
                input.closest('.dropify-wrapper')?.classList.add('is-invalid', 'border-red-500');
            }
        }

        function nameToDotNotation(name) {
            return name.replace(/\]/g, '').replace(/\[/g, '.').replace(/\.$/, '');
        }

        function clearFieldInlineError(target) {
            const form = target.closest('form');
            if (!form) return;

            const name = target.getAttribute('name');
            if (!name) return;

            const baseField = name.replace(/\[\]$/, '').split('[')[0];
            const dotField = nameToDotNotation(name);
            const baseErrorElement = form.querySelector(`#${baseField}-error-message`);
            const exactErrorElement = Array.from(form.querySelectorAll('[data-error-for]'))
                .find(element => element.dataset.errorFor === dotField);

            if (baseErrorElement) baseErrorElement.textContent = '';
            if (exactErrorElement) exactErrorElement.textContent = '';
            target.classList.remove('is-invalid', 'border-red-500');
            target.closest('.dropify-wrapper')?.classList.remove('is-invalid', 'border-red-500');
        }

        window.handleXhrErrors = function(xhr, targetId) {
            clearInlineErrors(targetId);

            if (xhr.status === 422 && xhr.responseJSON?.errors) {
                const form = getModalForm(targetId);
                if (!form) return;

                Object.entries(xhr.responseJSON.errors).forEach(([field, messages]) => {
                    const errorElement = findErrorElement(form, field);
                    if (errorElement) {
                        errorElement.textContent = Array.isArray(messages) ? messages[0] : messages;
                    }
                    markFieldInvalid(form, field);
                });

                return;
            }

            if (xhr.responseJSON?.message) {
                flasher.error(xhr.responseJSON.message);
            } else {
                flasher.error('Somethings went wrongs.');
            }
        }

        document.addEventListener('click', function(e) {
            const openButton = e.target.closest('[data-modal-open]');
            if (openButton) {
                e.preventDefault();
                openModal(openButton.getAttribute('data-modal-open'));
                clearInlineErrors(openButton.getAttribute('data-modal-open'));
                return;
            }

            const closeButton = e.target.closest('[data-modal-close]');
            if (closeButton) {
                e.preventDefault();
                const modalId = closeButton.getAttribute('data-modal-close');
                stopModalMedia(modalId);
                if (typeof clearModal === 'function') {
                    clearModal(modalId);
                } else {
                    closeModal(modalId);
                }
            }
        });

        document.addEventListener('input', function(e) {
            clearFieldInlineError(e.target);
        });

        document.addEventListener('change', function(e) {
            clearFieldInlineError(e.target);
        });

        document.querySelectorAll('[id$="-overlay"]').forEach(overlay => {
            overlay.addEventListener('click', function() {
                const modalId = this.id.replace('-overlay', '');
                stopModalMedia(modalId);
                if (typeof clearModal === 'function') {
                    clearModal(modalId);
                } else {
                    closeModal(modalId);
                }
            });
        });

        // Handle click on modal edit buttons
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.edit-video-btn');
            if (btn) {
                const type = btn.getAttribute('data-type');

                if (type === 'Video') {
                    openModal('edit-video');
                } else if (type === 'Podcast') {
                    openModal('edit-podcast');
                } else if (type === 'Evaluation') {
                    openModal('edit-evaluation');
                } else if (type === 'Activity') {
                    openModal('edit-activity');
                } else {
                    alert('Unknown type: ' + type);
                }
            }
        });
    </script>
    {{-- Edit Modal Show by Video, Podcast, Evaluation, Activity Here --}}

    {{-- -------------------------- Video Script Start Here ------------------------------------ --}}
    <script>
        /* async function initVideoFilePond() {
                const addInput = document.querySelector('#video_file');
                const editInput = document.querySelector('#edit_video_file');

                if (addInput) {
                    addVideoPond = FilePond.create(addInput, {
                        labelIdle: 'Drag & Drop your video or <span class="filepond--label-action">Browse</span>',
                        acceptedFileTypes: ['video/mp4', 'video/ogg', 'video/webm'],
                        maxFileSize: '512MB',
                    });

                    addVideoPond.on('addfile', async (error, file) => {
                        if (!error) {
                            await calculateVideoDuration(file.file, '#add-video input[name="duration"]');
                        }
                    });

                    addVideoPond.on('removefile', () => {
                        $('#add-video input[name="duration"]').val('');
                    });
                }

                if (editInput) {
                    editVideoPond = FilePond.create(editInput, {
                        labelIdle: 'Drag & Drop your video or <span class="filepond--label-action">Browse</span>',
                        acceptedFileTypes: ['video/mp4', 'video/ogg', 'video/webm'],
                        maxFileSize: '512MB',
                    });

                    editVideoPond.on('addfile', async (error, file) => {
                        if (!error) {
                            await calculateVideoDuration(file.file, '#edit-video input[name="duration"]');
                        }
                    });
                }
            } */

        function resetChunkUploadProgress(progressContainer, progressBar, progressPercentage) {
            $(progressBar).css('width', '0%');
            $(progressPercentage).text('0%');
            $(progressContainer).addClass('hidden');
        }

        function getChunkUploadErrorMessage(error) {
            if (error?.responseJSON?.message) {
                return error.responseJSON.message;
            }

            if (error?.responseJSON?.errors) {
                const firstField = Object.keys(error.responseJSON.errors)[0];
                return error.responseJSON.errors[firstField]?.[0] || 'Chunk upload failed';
            }

            if (error?.responseText) {
                return error.responseText;
            }

            return error?.message || error?.statusText || 'Chunk upload failed';
        }

        function buildVideoFormData(form) {
            const formData = new FormData(form);
            formData.delete('file');
            return formData;
        }

        async function uploadFileInChunks(inputElement, progressContainer, progressBar, progressPercentage, hiddenInput) {
            const file = inputElement.files[0];
            if (!file) return false;

            const chunkSize = 2 * 1024 * 1024; // 2MB to match previous setting
            const totalChunks = Math.ceil(file.size / chunkSize);
            const tempId = crypto.randomUUID();
            const fileName = file.name;

            $(progressContainer).removeClass('hidden');

            for (let i = 0; i < totalChunks; i++) {
                const start = i * chunkSize;
                const end = Math.min(start + chunkSize, file.size);
                const chunk = file.slice(start, end);

                const formData = new FormData();
                formData.append('file', chunk);
                formData.append('index', i + 1);
                formData.append('total_chunks', totalChunks);
                formData.append('temp_id', tempId);
                formData.append('file_name', fileName);

                try {
                    const response = await $.ajax({
                        url: "{{ route('video.chunkUpload') }}",
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                        global: false
                    });

                    if (response.success) {
                        const progress = Math.round(((i + 1) / totalChunks) * 100);
                        $(progressBar).css('width', progress + '%');
                        $(progressPercentage).text(progress + '%');

                        if (response.is_completed) {
                            $(hiddenInput).val(response.file_path);
                            $(inputElement).val(
                            ''); // Clear file input to prevent PostTooLargeException on final submit
                            return true;
                        }
                    } else {
                        throw new Error(response.message || 'Chunk upload failed');
                    }
                } catch (error) {
                    console.error(error);
                    resetChunkUploadProgress(progressContainer, progressBar, progressPercentage);
                    $(hiddenInput).val('');
                    flasher.error('Chunk upload failed: ' + getChunkUploadErrorMessage(error));
                    return false;
                }
            }
            return false;
        }

        async function handleVideoSelection(input, targetSelector) {
            const file = input.files[0];
            if (file) {
                await calculateVideoDuration(file, targetSelector);
            }
        }

        async function calculateVideoDuration(file, targetSelector) {
            return new Promise((resolve) => {
                if (file && file.type && file.type.includes("video")) {
                    const videoElement = document.createElement("video");
                    videoElement.preload = "metadata";
                    videoElement.src = URL.createObjectURL(file);

                    videoElement.onloadedmetadata = function() {
                        const duration = videoElement.duration;
                        const hours = Math.floor(duration / 3600);
                        const minutes = Math.floor((duration % 3600) / 60);
                        const seconds = Math.floor(duration % 60);

                        const formattedDuration =
                            String(hours).padStart(2, '0') + ":" +
                            String(minutes).padStart(2, '0') + ":" +
                            String(seconds).padStart(2, '0');

                        $(targetSelector).val(formattedDuration);
                        URL.revokeObjectURL(videoElement.src);
                        resolve(formattedDuration);
                    };

                    videoElement.onerror = function() {
                        if (typeof flasher !== 'undefined') flasher.error(
                        'Error processing video metadata');
                        resolve(null);
                    };
                } else {
                    resolve(null);
                }
            });
        }

        $(document).ready(function() {
            // initVideoFilePond();
            $('.video-chunk-input').dropify({
                messages: {
                    'default': 'Upload file here',
                    'replace': 'Drag and drop or click to replace',
                    'remove': 'Remove',
                    'error': 'Ooops, something wrong appended.'
                }
            });
        });

        {{-- Store --}}
        $(function() {
            $('#video_upload').on('submit', async function(e) {
                e.preventDefault();

                const form = this;

                // Chunk upload logic
                const fileInput = document.querySelector('#video_file');
                const file = fileInput.files[0];

                if (file) {
                    const submitBtn = $(this).find('button[type="submit"]');
                    submitBtn.prop('disabled', true).text('Uploading...');

                    // Ensure duration is calculated
                    const durationInput = $('#add-video .video-duration-input');
                    if (!durationInput.val() || durationInput.val() === '00:00:00') {
                        await calculateVideoDuration(file, '#add-video .video-duration-input');
                    }
                    if (!durationInput.val()) durationInput.val('00:00:00');

                    uploadFileInChunks(fileInput, '#add_video_progress_container', '#add_upload_bar',
                            '#add_upload_percentage', '#add_video_file_path')
                        .then(success => {
                            if (success) {
                                // Re-get formData because file_path was updated
                                let finalData = buildVideoFormData(form);
                                submitAjax(finalData);
                            } else {
                                submitBtn.prop('disabled', false).text('Add Video');
                            }
                        });
                } else {
                    submitAjax(buildVideoFormData(form));
                }

                function submitAjax(data) {
                    $.ajax({
                        url: "{{ route('video.ajax.store') }}",
                        method: 'POST',
                        data: data,
                        cache: false,
                        contentType: false,
                        processData: false,
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                        success: function(resp) {
                            if (resp.success === true) {
                                flasher.success(resp.message);
                                $('#course_details').DataTable().ajax.reload();
                                clearModal('add-video');
                                $('#video_upload')[0].reset();
                                $('#add_video_progress_container').addClass('hidden');
                                $('#add_upload_bar').css('width', '0%');
                                $('#add_upload_percentage').text('0%');
                            } else {
                                flasher.error(resp.message || (resp.errors ? resp.errors[
                                    0] : 'Unknown error'));
                            }
                        },
                        error: function(xhr) {
                            handleXhrErrors(xhr, 'add-video');
                        },
                        complete: function() {
                            $(e.target).find('button[type="submit"]').prop('disabled',
                                false).text('Add Video');
                        }
                    });
                }
            });
        });

        {{-- Edit --}}
        $(document).on('click', '.edit-video-btn', function() {
            let type = $(this).data('type');
            if (type !== 'Video') return;

            let videoId = $(this).data('id');
            let url = "{{ route('video.editData', ':id') }}".replace(':id', videoId);

            $.get(url, function(response) {
                if (response.success) {
                    const video = response.data;
                    $('#edit-video input[name="title"]').val(video.title);
                    $('#edit-video select[name="instructor_id"]').val(video.instructor_id).trigger(
                    'change');
                    $('#edit-video select[name="course_id"]').val(video.course_id).trigger('change');
                    refreshMultiSelect('edit-video-tags', video.tags);

                    $('#remove_video_flag').val('0');
                    $('#remove_image_flag').val('0');

                    if (video.file_url) {
                        const preview = `
                            <div class="relative group">
                                <video controls width="400" class="rounded-lg shadow-sm border border-slate-200">
                                    <source src="${video.file_url}" type="video/mp4">
                                </video>
                                <button type="button" class="remove-preview-btn absolute -top-3 -right-3 bg-red-500 text-white p-1.5 rounded-full shadow-lg hover:bg-red-600 transition-all opacity-0 group-hover:opacity-100" data-target="#remove_video_flag">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                                </button>
                            </div>`;
                        $('#video-audio').html(preview);
                    }

                    if (video.image) {
                        const preview = `
                            <div class="relative group">
                                <img src="${video.image}" width="200" class="rounded-lg border border-slate-200 shadow-sm" style="height:118px"/>
                                <button type="button" class="remove-preview-btn absolute -top-2 -right-2 bg-red-500 text-white p-1 rounded-full shadow-lg hover:bg-red-600 transition-all opacity-0 group-hover:opacity-100" data-target="#remove_image_flag">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                                </button>
                            </div>`;
                        $('#old-image').html(preview);
                    } else {
                        $('#old-image').html('<p class="text-gray-500">No image available</p>');
                    }

                    $('#video_update').attr('action', "{{ route('video.update', ':id') }}".replace(':id',
                        videoId));
                    $('#video_update').find('input[name="_method"]').val('PUT');

                    if (editVideoPond) editVideoPond.removeFiles();
                    openModal('edit-video');
                }
            });
        });

        {{-- Update --}}
        $(function() {
            $('#video_update').on('submit', async function(e) {
                e.preventDefault();

                let form = $(this);
                const formElement = this;
                let url = form.attr('action');

                // Chunk upload logic
                const fileInput = document.querySelector('#edit_video_file');
                const file = fileInput.files[0];

                if (file) {
                    const submitBtn = $(this).find('button[type="submit"]');
                    submitBtn.prop('disabled', true).text('Uploading...');

                    // Ensure duration is calculated
                    const durationInput = $('#edit-video .video-duration-input');
                    if (!durationInput.val() || durationInput.val() === '00:00:00') {
                        await calculateVideoDuration(file, '#edit-video .video-duration-input');
                    }
                    if (!durationInput.val()) durationInput.val('00:00:00');

                    uploadFileInChunks(fileInput, '#edit_video_progress_container', '#edit_upload_bar',
                            '#edit_upload_percentage', '#edit_video_file_path')
                        .then(success => {
                            if (success) {
                                // Re-get formData because file_path was updated
                                let finalData = buildVideoFormData(formElement);
                                submitAjax(finalData);
                            } else {
                                submitBtn.prop('disabled', false).text('Update Video');
                            }
                        });
                } else {
                    submitAjax(buildVideoFormData(formElement));
                }

                function submitAjax(data) {
                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: data,
                        cache: false,
                        contentType: false,
                        processData: false,
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                        success: function(resp) {
                            if (resp.success === true) {
                                flasher.success(resp.message);
                                $('#course_details').DataTable().ajax.reload();
                                clearModal('edit-video');
                                $('#video_update')[0].reset();
                                $('#edit_video_progress_container').addClass('hidden');
                                $('#edit_upload_bar').css('width', '0%');
                                $('#edit_upload_percentage').text('0%');
                            } else {
                                flasher.error(resp.message || (resp.errors ? resp.errors[
                                    0] : 'Unknown error'));
                            }
                        },
                        error: function(xhr) {
                            handleXhrErrors(xhr, 'edit-video');
                        },
                        complete: function() {
                            $(e.target).find('button[type="submit"]').prop('disabled',
                                false).text('Update Video');
                        }
                    });
                }
            });
        });
    </script>
    {{-- -------------------------- Video Script End Here ------------------------------------ --}}



    {{-- -------------------------- Activity Script Start Here ------------------------------------ --}}

    {{-- Store --}}
    <script>
        {{-- Create Activity --}}
        $(function() {
            $('#activity-form').on('submit', function(e) {
                e.preventDefault();

                console.log('Submitting Form...'); // ✅ Check if this logs

                let formData = new FormData(this);
                let url = "{{ route('activity.store') }}";

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    success: function(resp) {
                        console.log('Response:', resp); // ✅ Inspect the response

                        $('#course_details').DataTable().ajax.reload();

                        if (resp.success === true) {
                            flasher.success(resp.message);
                            clearModal('add-activity');
                        } else if (resp.errors) {
                            flasher.error(resp.errors[0]);
                        } else {
                            flasher.error(resp.message);
                        }
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText); // ✅ Log server error
                        handleXhrErrors(xhr, 'add-activity');
                    }
                });
            });
        });

        //add image
        let imageSectionCount = 0
        $('#add-gallery-image').click(function() {
            imageSectionCount++
            $('#gallery-images-section').append(`<div class="col-span-4 relative single-gallery-image">
             <button type="button" class="remove-gallery-section p-1 rounded-full z-[10002] bg-red-600 text-white absolute right-2 top-2">
                 <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                   <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                 </svg>
             </button>
             <input type="file" name="images[]" id="gallery_${imageSectionCount}"
                    class="dropify form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200" data-height="200" />
         </div>`)
            $('.dropify').dropify(dropifyConfig);
        })

        //remove image
        $(document).on('click', '.remove-gallery-section', function() {
            $(this).parent().remove()
        })
    </script>

    {{-- Edit --}}
    <script>
        $(document).on('click', '.edit-video-btn', function() {
            let type = $(this).data('type');
            if (type !== 'Activity') return;

            let activityId = $(this).data('id');
            let url = "{{ route('activity.editData', ':id') }}".replace(':id', activityId);

            $.get(url, function(response) {
                if (response.success) {
                    console.log(response);
                    const activity = response.data;

                    // Populate form
                    $('#edit-activity input[name="title"]').val(activity.title);
                    $('#edit-activity select[name="course_id"]').val(activity.course_id).trigger('change');
                    $('#edit-activity textarea[name="description"]').val(activity.description);

                    // Populate tags
                    refreshMultiSelect('edit-activity-tags', activity.tags);

                    // Clear previous removals
                    $('#removed-images-container').empty();

                    // Show activity images
                    if (Array.isArray(activity.images) && activity.images.length > 0) {
                        let previewHtml = '';
                        const baseUrl = "{{ url('/') }}/";

                        activity.images.forEach(function(imageUrl) {
                            previewHtml += `
                                <div class="relative group mb-3 overflow-hidden rounded-xl border border-slate-200 shadow-sm bg-slate-50 p-1">
                                    <img src="${baseUrl + imageUrl}" class="object-cover rounded-lg" style="width: 150px; height: 100px">
                                    <div class="flex items-center justify-center gap-2 mt-2 pb-1">
                                        <a href="${baseUrl + imageUrl}" target="_blank" class="p-1.5 bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white rounded-lg transition-all" title="View Image">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                        </a>
                                        <button type="button" class="remove-activity-image-btn p-1.5 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-lg transition-all" data-path="${imageUrl}" title="Remove Image">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                        </button>
                                    </div>
                                </div>`;
                        });

                        $('#old-activity-images').html(previewHtml);
                    } else {
                        $('#old-activity-images').html(
                            '<p class="text-sm text-gray-500">No images found.</p>');
                    }


                    // Set form action
                    $('#activity-update').attr('action', "{{ route('activity.update', ':id') }}".replace(
                        ':id', activityId));
                    $('#activity-update').find('input[name="_method"]').val('PUT');
                }
            });
        });
    </script>

    {{-- Update --}}

    <script>
        $(function() {
            // Handle activity image removal
            $(document).on('click', '.remove-activity-image-btn', function() {
                const path = $(this).data('path');
                $('#removed-images-container').append(
                    `<input type="hidden" name="removed_images[]" value="${path}">`);
                $(this).closest('.group').fadeOut();
            });

            $('#activity-update').on('submit', function(e) {
                e.preventDefault();

                let form = $(this);
                let formData = new FormData(this);
                let url = form.attr('action');

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    success: function(resp) {
                        console.log(resp)
                        $('#course_details').DataTable().ajax.reload();

                        if (resp.success === true) {
                            flasher.success(resp.message);
                            clearModal('edit-activity');

                        } else if (resp.errors) {
                            flasher.error(resp.errors[0]);
                        } else {
                            flasher.error(resp.message);
                        }
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                        handleXhrErrors(xhr, 'edit-activity');
                    }
                });
            });
        });
    </script>

    {{-- Add image for edit activity   --}}
    <script>
        $(document).ready(function() {
            let imageSectionCount = 1;

            // Add image button click
            $(document).on('click', '#edit-add-image', function() {
                imageSectionCount++;

                const newImageInput = `
                <div class="col-span-4 relative single-gallery-image">
                    <button type="button" class="remove-gallery-section-edit p-1 rounded-full z-[10002] bg-red-600 text-white absolute right-2 top-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                          <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </button>
                    <input type="file" name="images[]" id="gallery_edit_${imageSectionCount}"
                           class="dropify form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500
                           disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500
                           dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800
                           placeholder:text-slate-400 dark:placeholder:text-zink-200" data-height="200" />
                </div>`;

                $('#add-images-edit').append(newImageInput);

                // Reinitialize dropify on new input
                $('#gallery_edit_' + imageSectionCount).dropify(dropifyConfig);
            });

            // Remove image section
            $(document).on('click', '.remove-gallery-section-edit', function() {
                $(this).closest('.single-gallery-image').remove();
            });
        });
    </script>


    {{-- -------------------------- Activity Script End Here ------------------------------------ --}}


    {{-- -------------------------- Podcast Script Start Here ------------------------------------ --}}

    {{-- Store --}}
    <script>
        $(function() {
            $('#podcast-form').on('submit', function(e) {
                e.preventDefault();

                console.log('Submitting Form...'); // ✅ Check if this logs

                let formData = new FormData(this);
                let url = "{{ route('podcast.store') }}";

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    success: function(resp) {
                        console.log('Response:', resp); // ✅ Inspect the response

                        $('#course_details').DataTable().ajax.reload();

                        if (resp.success === true) {
                            flasher.success(resp.message);
                            clearModal('add-podcast');
                        } else if (resp.errors) {
                            flasher.error(resp.errors[0]);
                        } else {
                            flasher.error(resp.message);
                        }
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText); // ✅ Log server error
                        handleXhrErrors(xhr, 'add-podcast');
                    }
                });
            });
        });
    </script>

    {{-- Edit --}}
    <script>
        $(document).on('click', '.edit-video-btn', function() {
            let type = $(this).data('type');
            if (type !== 'Podcast') return;

            let podcastId = $(this).data('id');
            let url = "{{ route('podcast.editData', ':id') }}".replace(':id', podcastId);

            $.get(url, function(response) {
                if (response.success) {
                    const podcast = response.data;

                    // Populate form
                    $('#edit-podcast input[name="title"]').val(podcast.title);
                    $('#edit-podcast select[name="instructor_id"]').val(podcast.instructor_id).trigger(
                        'change');
                    $('#edit-podcast select[name="course_id"]').val(podcast.course_id).trigger('change');
                    $('#edit-podcast textarea[name="description"]').val(podcast.description);

                    // Populate tags
                    refreshMultiSelect('edit-podcast-tags', podcast.tags);


                    // Show video preview (optional)
                    // Reset flag
                    $('#remove_podcast_flag').val('0');

                    // Show audio preview
                    if (podcast.file_url) {
                        const preview = `
                            <div class="relative group">
                                <audio controls class="rounded-lg shadow-sm">
                                    <source src="${podcast.file_url}" type="audio/mp3">
                                </audio>
                                <button type="button" class="remove-preview-btn absolute -top-3 -right-3 bg-red-500 text-white p-1.5 rounded-full shadow-lg hover:bg-red-600 transition-all opacity-0 group-hover:opacity-100" data-target="#remove_podcast_flag">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                                </button>
                            </div>`;
                        $('#old-audio').html(preview);
                    }

                    // Set form action
                    $('#podcast-update').attr('action', "{{ route('podcast.update', ':id') }}".replace(
                        ':id', podcastId));
                    $('#podcast-update').find('input[name="_method"]').val('PUT');

                }
            });
        });
    </script>

    {{-- Update Podcast --}}
    <script>
        $(function() {
            $('#podcast-update').on('submit', function(e) {
                e.preventDefault();

                let form = $(this);
                let formData = new FormData(this);
                let url = form.attr('action');

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    success: function(resp) {
                        console.log(resp)
                        $('#course_details').DataTable().ajax.reload();

                        if (resp.success === true) {
                            flasher.success(resp.message);
                            clearModal('edit-podcast');
                        } else if (resp.errors) {
                            flasher.error(resp.errors[0]);
                        } else {
                            flasher.error(resp.message);
                        }
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                        handleXhrErrors(xhr, 'edit-podcast');
                    }
                });
            });
        });
    </script>

    {{-- -------------------------- Podcast Script End Here ------------------------------------ --}}


    {{-- -------------------------- Evaluation Script Start Here ------------------------------------ --}}

    {{-- Store --}}
    <script>
        $(function() {
            $('#evaluation-form').on('submit', function(e) {
                e.preventDefault();

                console.log('Submitting Form...'); // ✅ Check if this logs

                let formData = new FormData(this);
                let url = "{{ route('evaluation.store') }}";


                $.ajax({
                    url: url,
                    method: 'POST',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },

                    success: function(resp) {
                        console.log('Response:', resp); // ✅ Inspect the response



                        $('#course_details').DataTable().ajax.reload();

                        if (resp.success === true) {
                            flasher.success(resp.message);
                            clearModal('add-evaluation');
                        } else if (resp.errors) {
                            flasher.error(resp.errors[0]);
                        } else {
                            flasher.error(resp.message);
                        }
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText); // ✅ Log server error
                        handleXhrErrors(xhr, 'add-evaluation');
                    }
                });
            });
        });
    </script>

    {{-- Edit --}}
    <script>
        $(document).on('click', '.edit-video-btn', function() {
            let type = $(this).data('type');
            if (type !== 'Evaluation') return;

            let evaluationId = $(this).data('id');
            let url = "{{ route('evaluation.editData', ':id') }}".replace(':id', evaluationId)

            $.get(url, function(response) {
                console.log(response)
                if (response.success) {
                    const evaluation = response.data;

                    $('#edit-evaluation input[name="title"]').val(evaluation.title);
                    $('#edit-evaluation select[name="course_id"]').val(evaluation.course_id).trigger(
                        'change');

                    // Populate tags
                    refreshMultiSelect('edit-evaluation-tags', evaluation.tags);

                    // Populate questions
                    if (window.populateEvaluationQuestions) {
                        window.populateEvaluationQuestions(evaluation.questions);
                    }

                    // Set form action
                    $('#evaluation-update').attr('action', "{{ route('evaluation.update', ':id') }}"
                        .replace(':id', evaluationId));
                    $('#evaluation-update').find('input[name="_method"]').val('PUT');

                }
            });
        });
    </script>

    {{-- Update --}}
    <script>
        $(function() {
            $('#evaluation-update').on('submit', function(e) {
                e.preventDefault();

                let form = $(this);
                let formData = new FormData(this);
                let url = form.attr('action');

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    success: function(resp) {
                        console.log(resp)
                        $('#course_details').DataTable().ajax.reload();

                        if (resp.success === true) {
                            flasher.success(resp.message);
                            clearModal('edit-evaluation');
                        } else if (resp.errors) {
                            flasher.error(resp.errors[0]);
                        } else {
                            flasher.error(resp.message);
                        }
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                        handleXhrErrors(xhr, 'edit-evaluation');
                    }
                });
            });
        });
    </script>

    {{-- -------------------------- Evaluation Script End Here ------------------------------------ --}}

    <script>
        $(document).ready(function() {
            function getQuestionHtml(index, prefix = 'questions') {
                return `
                    <div class="question-item border p-4 rounded-md relative bg-slate-50 dark:bg-zink-600 mb-4">
                        <button type="button" class="remove-question absolute top-2 right-2 text-red-500 hover:text-red-700">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"></path><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path></svg>
                        </button>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block mb-2 text-sm font-medium text-slate-700 dark:text-zink-200">Question <span class="text-red-500">*</span></label>
                                <textarea name="${prefix}[${index}][question]" class="form-input w-full border-slate-200 dark:border-zink-500 rounded p-2" rows="3" required></textarea>
                                <span data-error-for="${prefix}.${index}.question" class="text-red-500 text-sm error-message"></span>
                            </div>
                            <div class="space-y-4">

                                <div>
                                    <label class="block mb-2 text-sm font-medium text-slate-700 dark:text-zink-200">Answer <span class="text-red-500">*</span></label>
                                    <select name="${prefix}[${index}][answer]" class="form-select w-full border-slate-200 dark:border-zink-500 rounded p-2" required>
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                    <span data-error-for="${prefix}.${index}.answer" class="text-red-500 text-sm error-message"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }

            // Add question for creation
            $('#add-question-btn').on('click', function() {
                let index = $('#questions-container .question-item').length;
                $('#questions-container').append(getQuestionHtml(index));
            });

            // Add question for editing
            $('#edit-add-question-btn').on('click', function() {
                let index = $('#edit-questions-container .question-item').length;
                $('#edit-questions-container').append(getQuestionHtml(index));
            });

            // Remove question
            $(document).on('click', '.remove-question', function() {
                $(this).closest('.question-item').remove();
            });

            // Expose population function
            window.populateEvaluationQuestions = function(questions) {
                $('#edit-questions-container').empty();
                if (questions && questions.length > 0) {
                    questions.forEach((q, index) => {
                        let html = getQuestionHtml(index);
                        let block = $(html);
                        block.find('textarea').val(q.title);
                        block.find('input').val(q.link);
                        block.find('select').val(q.answer);
                        $('#edit-questions-container').append(block);
                    });
                } else {
                    // Add one empty question by default if none exist
                    $('#edit-questions-container').append(getQuestionHtml(0));
                }
            };

            // Add one empty question by default to add modal
            $('#add-question-btn').click();
        });
    </script>

    {{-- Dropify Image here --}}
    <script>
        const dropifyConfig = {
            tpl: {
                message: '<div class="dropify-message"><span class="file-icon"></span> <p style="font-size: 24px;">Upload file here</p></div>'
            }
        };

        $(document).ready(function() {
            $('.dropify').dropify(dropifyConfig);
        })

        /* 
        // Old calculateVideoDuration (Dropify based) - Replaced by async version for FilePond
        function calculateVideoDuration(input) {
            ...
        }
        */
    </script>

    {{-- Multiple Tags Here --}}
    <script>
        const tagConfig = {
            rounded: true,
            shadow: true,
            placeholder: 'Search',
            tagColor: {
                textColor: '#327b2c',
                borderColor: '#92e681',
                bgColor: '#eaffe6',
            }
        };

        function initAllMultiSelects() {
            const ids = [
                'tags', 'edit-video-tags', 'activity-tags', 'edit-activity-tags',
                'podcast-tags', 'edit-podcast-tags', 'evaluation-tags', 'edit-evaluation-tags'
            ];
            ids.forEach(id => {
                if (document.getElementById(id)) {
                    new MultiSelectTag(id, tagConfig);
                }
            });
        }

        function refreshMultiSelect(id, values) {
            const select = document.getElementById(id);
            if (!select) return;

            // Set values on original select
            $(select).val(values);

            // Remove existing MultiSelectTag UI wrapper
            // The library creates a div with class 'multi-select-tag' next to the select
            $(select).nextAll('.multi-select-tag').remove();
            $(select).nextAll('.multiselect-dropdown').remove();

            // Re-initialize
            new MultiSelectTag(id, tagConfig);
        }

        $(document).ready(function() {
            initAllMultiSelects();
        });
    </script>
    <script>
        $(document).on('focus', '.multi-select-tag input', function() {
            $(this).removeAttr('required');
        });
    </script>
    <script>
        // Remove required from all MultiSelectTag inputs after render
        setTimeout(() => {
            $('.multi-select-tag input').removeAttr('required');
        }, 300);
    </script>
@endpush
