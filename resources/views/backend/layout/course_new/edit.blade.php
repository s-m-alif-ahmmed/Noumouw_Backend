@extends('backend.app')

@section('title', 'Course')
@section('title_url')
    <a href="{{ route('course.index') }}">Course</a>
@endsection
@section('tabName')
    <a href="{{ route('dashboard') }}">Home</a>
@endsection
@push('styles_top')
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag@3.1.0/dist/css/multi-select-tag.css"> --}}
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag@4.0.1/dist/css/multi-select-tag.min.css">
@endpush

{{-- Push additional styles if needed --}}
@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@24.7.0/build/css/intlTelInput.css">
    <link rel="stylesheet" href="{{ asset('backend/js/datatables/jquery-3.7.0.js') }}">
    <link href="{{ asset('vendor/flasher/flasher.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.css">

    <!-- FilePond CSS Reverted -->
    {{-- <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet">
    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet"> --}}
    <style>
        .premium-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
            border: 1px solid #f1f5f9;
        }

        .form-label {
            font-weight: 600;
            color: #475569;
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
            display: block;
        }

        .section-header {
            border-bottom: 1px solid #f1f5f9;
            padding-bottom: 1rem;
            margin-bottom: 2rem;
        }

        .details-item {
            background: #f8fafc;
            border: 1px solid #e2e8f0 !important;
            border-radius: 16px;
            transition: all 0.3s;
            position: relative;
        }

        .details-item:hover {
            border-color: #cbd5e1 !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .details-item .drag-handle {
            cursor: move;
            color: #94a3b8;
        }

        .multi-select-tag {
            border-radius: 12px !important;
            border: 1.5px solid #e2e8f0 !important;
        }

        .dropify-wrapper {
            border-radius: 16px;
            border: 2.5px dashed #e2e8f0;
        }

        .btn-premium {
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* FilePond Premium Styling */
        .filepond--root {
            font-family: 'Inter', sans-serif;
            margin-bottom: 0;
        }

        .filepond--panel-root {
            background-color: #f8fafc;
            border: 2px dashed #e2e8f0;
            border-radius: 16px;
        }

        .filepond--drop-label {
            color: #64748b;
        }

        .filepond--label-action {
            text-decoration-color: #3b82f6;
            color: #3b82f6;
        }

        /* .filepond--item-panel {
                background-color: #3b82f6;
            } */

        .form-control-modern {
            padding: 0.75rem 1rem;
            border-radius: 12px;
            border: 1.5px solid #e2e8f0;
            width: 100%;
            transition: all 0.2s;
        }

        .form-control-modern:focus {
            border-color: #3b82f6;
            outline: none;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .form-control-modern.is-invalid {
            border-color: #f87171 !important;
            box-shadow: 0 0 0 3px rgba(248, 113, 113, 0.1) !important;
        }

        .form-control.is-invalid {
            border-color: #f87171 !important;
            box-shadow: 0 0 0 3px rgba(248, 113, 113, 0.1) !important;
        }

        .dropify-wrapper.is-invalid {
            border-color: #f87171 !important;
        }

        .multi-select-tag.is-invalid {
            border-color: #f87171 !important;
            box-shadow: 0 0 0 3px rgba(248, 113, 113, 0.1) !important;
        }

        .field-error-msg {
            color: #ef4444 !important;
            font-size: 0.75rem !important;
            margin-top: 0.25rem;
            display: block !important;
            font-weight: 500;
            position: static !important;
            line-height: 1.4;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group>label,
        .content-forms label {
            font-weight: 600;
            color: #475569;
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
            display: block;
        }

        .form-control {
            padding: 0.75rem 1rem;
            border-radius: 12px;
            border: 1.5px solid #e2e8f0;
            width: 100%;
            transition: all 0.2s;
            background: #fff;
        }

        .form-control:focus {
            border-color: #3b82f6;
            outline: none;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .row {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        @media (min-width: 768px) {
            .row {
                grid-template-columns: repeat(12, minmax(0, 1fr));
            }

            .col-md-6 {
                grid-column: span 6 / span 6;
            }

            .col-md-12 {
                grid-column: span 12 / span 12;
            }
        }

        .d-none {
            display: none !important;
        }

        .text-right {
            text-align: right;
        }

        .position-relative {
            position: relative;
        }

        .single-gallery-image {
            position: relative;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: #fff;
            color: #2563eb;
            border: 1px solid #dbeafe;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.05);
        }

        .btn-danger {
            background: #fef2f2;
            color: #ef4444;
            border: 1px solid #fee2e2;
        }
    </style>
@endpush

@section('content')
    <div class="premium-card mb-8">
        <div class="p-8">
            <div class="flex items-center justify-between mb-10 pb-6 border-b border-slate-100">
                <div>
                    <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Edit Course</h1>
                    <p class="text-slate-500 text-sm mt-1">Update the course information, media, and curriculum below</p>
                </div>
                <a href="{{ route('course.index_new') }}"
                    class="btn-premium bg-slate-100 text-slate-600 hover:bg-slate-200">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="m15 18-6-6 6-6" />
                    </svg>
                    Back to List
                </a>
            </div>
            @if ($errors->any())
                <div class="mb-8 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-xl">
                    <div class="font-bold mb-2 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="12" x2="12" y1="8" y2="12" />
                            <line x1="12" x2="12.01" y1="16" y2="16" />
                        </svg>
                        Please fix the following errors:
                    </div>
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form id="courseForm" action="{{ route('course.update_new', $data->id) }}" enctype="multipart/form-data"
                method="POST" novalidate>
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
                    <div class="lg:col-span-7">
                        <div class="section-header">
                            <h3 class="text-lg font-semibold text-slate-700 flex items-center gap-2">
                                <span
                                    class="size-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-sm">1</span>
                                General Information
                            </h3>
                        </div>

                        <div class="space-y-6">
                            <div>
                                <label class="form-label">Course Name <span class="text-red-500">*</span></label>
                                <input type="text" class="form-control-modern" name="name"
                                    value="{{ old('name', $data->name) }}" placeholder="e.g. Master Laravel Development"
                                    required>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="form-label">Category <span class="text-red-500">*</span></label>
                                    <select name="category_id" class="form-control-modern" required>
                                        <option selected disabled value="">Select a Category</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" @if (old('category_id', $data->category_id) == $category->id) selected @endif>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="form-label">Subscription Plan <span class="text-red-500">*</span></label>
                                    <select name="subscription_plans_id" class="form-control-modern" required>
                                        <option selected disabled value="">Select a Plan</option>
                                        @foreach ($subscription as $subscriptions)
                                            <option value="{{ $subscriptions->id }}"
                                                @if (old('subscription_plans_id', $data->subscription_plans_id) == $subscriptions->id) selected @endif>
                                                {{ $subscriptions->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control-modern" rows="4" placeholder="Enter course description...">{{ old('description', $data->description) }}</textarea>
                            </div>

                            <div>
                                <label class="form-label">Tags <span class="text-red-500">*</span></label>
                                <select name="tags[]" id="tags" multiple class="tags-required">
                                    @foreach ($allTags as $tag)
                                        <option value="{{ $tag->id }}" @if (in_array($tag->id, old('tags', $selectedTagIds))) selected @endif>
                                            {{ $tag->title }}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="text-[10px] text-slate-400 mt-2 uppercase font-bold tracking-tighter">Press Enter
                                    or select to add multiple tags</p>
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-5">
                        <div class="section-header">
                            <h3 class="text-lg font-semibold text-slate-700 flex items-center gap-2">
                                <span
                                    class="size-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm">2</span>
                                Course Preview
                            </h3>
                        </div>

                        <div>
                            <label class="form-label">Course Thumbnail <span class="text-red-500">*</span></label>
                            <input type="file" class="dropify" name="thumbnail" data-height="230"
                                data-default-file="{{ asset($data->thumbnail) }}">
                            <p class="text-xs text-slate-400 mt-3 italic text-center">Recommended size: 1280x720px (16:9
                                ratio)</p>
                        </div>
                    </div>
                </div>
                <div class="mt-16">
                    <div class="section-header flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-slate-700 flex items-center gap-2">
                            <span
                                class="size-8 rounded-lg bg-green-50 text-green-600 flex items-center justify-center text-sm">3</span>
                            Curriculum & Content
                        </h3>
                        <p class="text-sm text-slate-400 font-medium">Build your course structure below</p>
                    </div>

                    <div id="details-wrapper" class="space-y-6 min-h-[50px]">
                    @foreach ($content as $index => $item)
                        <div class="details-item p-6 mt-6 border border-slate-200">
                            <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100">
                                <div class="flex items-center gap-3">
                                    <div class="drag-handle p-2 hover:bg-slate-100 rounded-lg transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="9" cy="5" r="1" />
                                            <circle cx="9" cy="12" r="1" />
                                            <circle cx="9" cy="19" r="1" />
                                            <circle cx="15" cy="5" r="1" />
                                            <circle cx="15" cy="12" r="1" />
                                            <circle cx="15" cy="19" r="1" />
                                        </svg>
                                    </div>
                                    <span class="font-bold text-slate-700">Content Block #{{ $index + 1 }}</span>
                                </div>
                            </div>

                            {{-- Content Type --}}
                            <div class="form-group">
                                <label>Content Type <span style="color: red">*</span></label>
                                <select name="contents[{{ $index }}][type]" class="form-control content-type"
                                    required>
                                    <option disabled value="">Select Content Type</option>
                                    <option value="video" {{ $item->type == 'video' ? 'selected' : '' }}>🎥 Video Lecture</option>
                                    <option value="activity" {{ $item->type == 'activity' ? 'selected' : '' }}>📝 Hands-on Activity
                                    </option>
                                    <option value="podcast" {{ $item->type == 'podcast' ? 'selected' : '' }}>🎙️ Podcast/Audio
                                    </option>
                                    <option value="evaluation" {{ $item->type == 'evaluation' ? 'selected' : '' }}>
                                        📊 Knowledge Evaluation</option>
                                </select>
                            </div>

                            <div class="content-forms">

                                {{-- VIDEO --}}
                                <div class="content-form video row {{ $item->type == 'video' ? '' : 'd-none' }}">
                                    <input type="hidden" name="contents[{{ $index }}][id]"
                                        value="{{ $item->contentable_id }}">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Title <span style="color: red">*</span></label>
                                            <input type="text" class="form-control"
                                                name="contents[{{ $index }}][video_title]"
                                                value="{{ $item->contentable->title ?? '' }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Image <span style="color: red">*</span></label>
                                            <input type="file" class="dropify"
                                                name="contents[{{ $index }}][video_image]"
                                                data-default-file="{{ asset($item->contentable->image ?? '') }}">
                                        </div>
                                        <div class="form-group">
                                            <label>Instructor <span style="color: red">*</span></label>
                                            <select name="contents[{{ $index }}][video_instructor_id]"
                                                class="form-control" required>
                                                <option selected disabled value="">Select Instructor</option>
                                                @foreach ($instructors as $instructor)
                                                    <option value="{{ $instructor->id }}"
                                                        @if (old('instructor_id', $item->contentable->instructor_id) == $instructor->id) selected @endif>
                                                        {{ $instructor->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Tags <span style="color: red">*</span></label>
                                            <select name="contents[{{ $index }}][tags][]" multiple
                                                class="content-tags tags-required">
                                                @php
                                                    $selectedTagId =
                                                        $item->contentable?->video_tags?->pluck('tag_id')->toArray() ??
                                                        [];
                                                @endphp
                                                @foreach ($allTags as $tag)
                                                    <option value="{{ $tag->id }}"
                                                        @if (in_array($tag->id, $selectedTagId)) selected @endif>
                                                        {{ $tag->title }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Video Url <span style="color: red">*</span></label>
                                            <input type="hidden" name="contents[{{ $index }}][duration]"
                                                class="video-duration-input"
                                                value="{{ $item->type == 'video' ? $item->contentable->duration ?? '00:00:00' : '00:00:00' }}">
                                            {{-- <input type="file" class="dropify"
                                                name="contents[{{ $index }}][video_url]"
                                                data-default-file="{{ asset($item->contentable->file ?? '') }}"
                                                onchange="calculateVideoDuration(this)"> --}}
                                            {{-- <input type="file" class="video-filepond"
                                                name="contents[{{ $index }}][video_url]"
                                                data-default-file="{{ asset($item->contentable->file ?? '') }}"> --}}
                                            <input type="file" class="dropify video-chunk-input" data-height="120"
                                                onchange="calculateVideoDuration(this)">
                                            <input type="hidden" name="contents[{{ $index }}][video_url]"
                                                class="video-final-path">

                                            <div class="chunk-progress-container hidden mt-2">
                                                <div class="flex justify-between mb-1">
                                                    <span class="text-[10px] font-medium text-blue-700">Uploading...</span>
                                                    <span
                                                        class="text-[10px] font-medium text-blue-700 chunk-upload-percentage">0%</span>
                                                </div>
                                                <div class="w-full bg-gray-200 rounded-full h-1.5">
                                                    <div class="bg-blue-600 h-1.5 rounded-full chunk-upload-bar"
                                                        style="width: 0%"></div>
                                                </div>
                                            </div>
                                            @if (isset($item->contentable->file))
                                                <p class="text-xs text-slate-400 mt-1">Current file:
                                                    {{ basename($item->contentable->file) }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                {{-- activity  --}}

                                <div class="content-form activity row {{ $item->type == 'activity' ? '' : 'd-none' }}">
                                    <input type="hidden" name="contents[{{ $index }}][id]"
                                        value="{{ $item->contentable_id }}">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Activity Title <span style="color: red">*</span></label>
                                            <input class="form-control"
                                                name="contents[{{ $index }}][activity_title]"
                                                value="{{ $item->contentable->title }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="Description">Description <span style="color: red">*</span></label>
                                            <textarea name="contents[{{ $index }}][activity_description]" class="form-control" required>{{ $item->contentable->description }}</textarea>
                                        </div>
                                        <div class="form-group">
                                            <label>Tags <span style="color: red">*</span></label>
                                            <select name="contents[{{ $index }}][tags][]" multiple
                                                class="content-tags tags-required">
                                                @php
                                                    $selectedTagId =
                                                        $item->contentable?->activity_tags
                                                            ?->pluck('tag_id')
                                                            ->toArray() ?? [];
                                                @endphp
                                                @foreach ($allTags as $tag)
                                                    <option value="{{ $tag->id }}"
                                                        @if (in_array($tag->id, $selectedTagId)) selected @endif>
                                                        {{ $tag->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="flex items-center justify-between gap-5">
                                            <label for="gallery_images"
                                                class="inline-block  text-base font-medium">Images</label>
                                            <button type="button"
                                                class="text-white update-add-image bg-green-500 border-green-500 btn hover:bg-green-600 !p-1">
                                                Add image
                                            </button>
                                        </div>
                                        <div class="row activity-images-wrapper">
                                            @php
                                                $images = $item->contentable->images ?? [];
                                            @endphp

                                            @forelse ($images as $imgIndex => $image)
                                                <div class="col-md-6 mt-3 single-gallery-image position-relative">
                                                    <button type="button"
                                                        class="remove-gallery-image absolute -top-2 -right-2 z-10 size-6 bg-red-500 text-white rounded-full flex items-center justify-center shadow-lg hover:bg-red-600 transition-colors">×</button>
                                                    <input type="hidden"
                                                        name="contents[{{ $index }}][old_images][{{ $imgIndex }}]"
                                                        value="{{ $image }}">
                                                    <input type="file"
                                                        name="contents[{{ $index }}][activity_image][{{ $imgIndex }}]"
                                                        class="dropify" data-height="200"
                                                        data-default-file="{{ asset($image) }}">
                                                </div>
                                            @empty
                                                {{-- If no images exist --}}
                                                <div class="col-md-6 mt-3 single-gallery-image position-relative">
                                                    <button type="button"
                                                        class="remove-gallery-image absolute -top-2 -right-2 z-10 size-6 bg-red-500 text-white rounded-full flex items-center justify-center shadow-lg hover:bg-red-600 transition-colors">×</button>
                                                    <input type="file"
                                                        name="contents[{{ $index }}][activity_image][]"
                                                        class="dropify" data-height="200">
                                                </div>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                                {{-- podcast --}}
                                <div class="content-form podcast row {{ $item->type == 'podcast' ? '' : 'd-none' }}">
                                    <input type="hidden" name="contents[{{ $index }}][id]"
                                        value="{{ $item->contentable_id }}">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Title <span style="color: red">*</span></label>
                                            <input type="text" class="form-control"
                                                name="contents[{{ $index }}][podcast_title]"
                                                value="{{ $item->contentable->title }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Instructor <span style="color: red">*</span></label>
                                            <select name="contents[{{ $index }}][podcast_instructor_id]"
                                                class="form-control" required>
                                                <option selected disabled value="">Select Instructor</option>
                                                @foreach ($instructors as $instructor)
                                                    <option value="{{ $instructor->id }}"
                                                        @if (old('instructor_id', $item->contentable->instructor_id) == $instructor->id) selected @endif>
                                                        {{ $instructor->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="Description">Description <span style="color: red">*</span></label>
                                            <textarea name="contents[{{ $index }}][podcast_description]" class="form-control" rows="4" required>{{ $item->contentable->description }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Tags <span style="color: red">*</span></label>
                                            <select name="contents[{{ $index }}][tags][]" multiple
                                                class=" content-tags tags-required">
                                                @php
                                                    $selectedTagId =
                                                        $item->contentable?->podcast_tags
                                                            ?->pluck('tag_id')
                                                            ->toArray() ?? [];
                                                @endphp
                                                @foreach ($allTags as $tag)
                                                    <option value="{{ $tag->id }}"
                                                        @if (in_array($tag->id, $selectedTagId)) selected @endif>
                                                        {{ $tag->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="image">File <span style="color: red">*</span></label>
                                            <input type="file" class="dropify"
                                                name="contents[{{ $index }}][podcast_file]"
                                                data-default-file="{{ asset($item->contentable->file ?? '') }}">
                                        </div>
                                    </div>
                                </div>

                                {{-- EVALUATION --}}
                                <div
                                    class="content-form evaluation row {{ $item->type == 'evaluation' ? '' : 'd-none' }}">
                                    <input type="hidden" name="contents[{{ $index }}][id]"
                                        value="{{ $item->contentable_id }}">
                                    <div class="col-md-6">
                                        <label>Title <span style="color: red">*</span></label>
                                        <input type="text" class="form-control"
                                            name="contents[{{ $index }}][evaluation_title]"
                                            value="{{ $item->contentable->title ?? '' }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Tags <span style="color: red">*</span></label>
                                            <select name="contents[{{ $index }}][tags][]" multiple
                                                class=" content-tags tags-required">
                                                @php
                                                    $selectedTagId =
                                                        $item->contentable?->evaluation_tags
                                                            ?->pluck('tag_id')
                                                            ->toArray() ?? [];
                                                @endphp
                                                @foreach ($allTags as $tag)
                                                    <option value="{{ $tag->id }}"
                                                        @if (in_array($tag->id, $selectedTagId)) selected @endif>
                                                        {{ $tag->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12 questions-wrapper">
                                        @foreach ($item->contentable->questions ?? [] as $qIndex => $question)
                                            <input type="hidden"
                                                name="contents[{{ $index }}][questions][{{ $qIndex }}][id]"
                                                value="{{ $question->id }}">
                                            <div class="row question-item border p-3 mt-4 m-2">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="">Question <span
                                                                style="color: red">*</span></label>
                                                        <textarea name="contents[{{ $index }}][questions][{{ $qIndex }}][question]" class="form-control"
                                                            rows="5" required>{{ $question->title ?? '' }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="">Answer <span
                                                                style="color: red">*</span></label>
                                                        <select
                                                            name="contents[{{ $index }}][questions][{{ $qIndex }}][answer]"
                                                            class="form-control" required>
                                                            <option value="1"
                                                                {{ $question->answer == 1 ? 'selected' : '' }}>Yes</option>
                                                            <option value="0"
                                                                {{ $question->answer == 0 ? 'selected' : '' }}>No</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                        <div class="col-md-12 text-right">
                                            <button type="button" class="btn btn-primary addMore">Add more</button>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <!-- <div class="text-right mt-2">
                                                                            <button type="button" class="btn btn-danger btn-sm remove-item">Remove</button>
                                                                        </div> -->
                        </div>
                    @endforeach
                    </div>
                    <div
                        class="mt-8 flex flex-col md:flex-row items-center justify-between gap-6 p-6 bg-slate-50 rounded-2xl border border-slate-100 border-dashed">
                        <div>
                            <h4 class="font-bold text-slate-700">Add content blocks</h4>
                            <p class="text-sm text-slate-500">Include videos, activities, podcasts or evaluations</p>
                        </div>
                        <button type="button"
                            class="btn-premium bg-white text-blue-600 border border-blue-100 hover:bg-blue-50 shadow-sm"
                            id="addDetailsBtn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M12 5v14M5 12h14" />
                            </svg>
                            Add New Content Block
                        </button>
                    </div>
                </div>

                <div class="mt-16 pt-8 border-t border-slate-100 flex justify-end gap-4">
                    <button type="submit"
                        class="btn-premium bg-custom-500 text-white hover:bg-custom-600 shadow-xl shadow-custom-500/20 px-10">
                        Update Course
                    </button>
                </div>
            </form>

            <script type="text/template" id="details-template">



            <div class="details-item border p-3 mt-4">

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="content_type">Content Type <span style="color: red">*</span></label>
                            <select name="contents[INDEX][type]" class="form-control content-type" required>
                                <option selected disabled value="">Select a Content Type</option>
                                <option value="video">Video</option>
                                <option value="activity">Activity</option>
                                <option value="podcast">Podcast</option>
                                <option value="evaluation">Evaluation</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="content-forms">

                    <div class="content-form d-none video row">
                        <div class="col-md-6" >
                            <div class="form-group">
                                <label>Title <span style="color: red">*</span></label>
                                <input type="text" class="form-control" name="contents[INDEX][video_title]" required>
                            </div>
                            <div class="form-group">
                                <label>Image <span style="color: red">*</span></label>
                                <input type="file" class="dropify" name="contents[INDEX][video_image]" required>
                            </div>
                            <div class="form-group">
                                <label>Instructor <span style="color: red">*</span></label>
                                <select name="contents[INDEX][video_instructor_id]" class="form-control" required>
                                    <option selected disabled value="">Select Instructor</option>
                                    @foreach ($instructors as $instructor)
                                        <option value="{{ $instructor->id}}">{{ $instructor->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tags <span style="color: red">*</span></label>
                                <select name="contents[INDEX][tags][]" multiple class=" content-tags tags-required">
                                    @foreach ($allTags as $tag)
                                        <option value="{{ $tag->id }}">{{ $tag->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Video Url <span style="color: red">*</span></label>
                                <input type="hidden" name="contents[INDEX][duration]" class="video-duration-input"
                                    value="00:00:00">
                                {{-- <input type="file" class="dropify" name="contents[INDEX][video_url]" onchange="calculateVideoDuration(this)" required> --}}
                                {{-- <input type="file" class="video-filepond" name="contents[INDEX][video_url]" required> --}}
                                <input type="file" class="form-control-modern video-chunk-input" onchange="calculateVideoDuration(this)" required>
                                <input type="hidden" name="contents[INDEX][video_url]" class="video-final-path">

                                <div class="chunk-progress-container hidden mt-2">
                                    <div class="flex justify-between mb-1">
                                        <span class="text-[10px] font-medium text-blue-700">Uploading...</span>
                                        <span class="text-[10px] font-medium text-blue-700 chunk-upload-percentage">0%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-1.5">
                                        <div class="bg-blue-600 h-1.5 rounded-full chunk-upload-bar" style="width: 0%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="content-form d-none activity row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Activity Title <span style="color: red">*</span></label>
                                <input class="form-control" name="contents[INDEX][activity_title]" required>
                            </div>
                            <div class="form-group">
                                <label for="Description">Description <span style="color: red">*</span></label>
                                <textarea name="contents[INDEX][activity_description]" class="form-control" required></textarea>
                            </div>
                            <div class="form-group">
                                <label>Tags <span style="color: red">*</span></label>
                                <select name="contents[INDEX][tags][]" multiple class=" content-tags tags-required">
                                    @foreach ($allTags as $tag)
                                        <option value="{{ $tag->id }}">{{ $tag->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="flex items-center justify-between gap-5">
                                <label for="gallery_images" class="inline-block  text-base font-medium">Images</label>
                                <button type="button"  class="btn-premium py-1.5 px-3 text-xs bg-green-50 text-green-600 border border-green-100 hover:bg-green-100 edit-add-image">
                                    Add image
                                </button>
                            </div>
                            <div class="row activity-images-wrapper">
                                <div class="col-md-6 mt-3 single-gallery-image position-relative">
                                    <button type="button"
                                        class="remove-gallery-image absolute -top-2 -right-2 z-10 size-6 bg-red-500 text-white rounded-full flex items-center justify-center shadow-lg hover:bg-red-600 transition-colors">×</button>
                                    <input type="file"
                                        name="contents[INDEX][activity_image][]"
                                        class="dropify"
                                        data-height="200" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="content-form d-none podcast row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Title <span style="color: red">*</span></label>
                                <input type="text" class="form-control" name="contents[INDEX][podcast_title]" required>
                            </div>
                            <div class="form-group">
                                <label>Instructor <span style="color: red">*</span></label>
                                <select name="contents[INDEX][podcast_instructor_id]"  class="form-control" required>
                                    <option selected disabled value="">Select Instructor</option>
                                    @foreach ($instructors as $instructor)
                                        <option value="{{ $instructor->id}}">{{ $instructor->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="Description">Description <span style="color: red">*</span></label>
                                <textarea name="contents[INDEX][podcast_description]" class="form-control" required></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tags <span style="color: red">*</span></label>
                                <select name="contents[INDEX][tags][]" multiple class=" content-tags tags-required">
                                    @foreach ($allTags as $tag)
                                        <option value="{{ $tag->id }}">{{ $tag->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="image">File <span style="color: red">*</span></label>
                                <input type="file" class="dropify" name="contents[INDEX][podcast_file]" required>
                            </div>
                        </div>
                    </div>
                    <div class="content-form d-none evaluation row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Title <span style="color: red">*</span></label>
                                <input class="form-control" name="contents[INDEX][evaluation_title]" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tags <span style="color: red">*</span></label>
                                <select name="contents[INDEX][tags][]" multiple class=" content-tags tags-required">
                                    @foreach ($allTags as $tag)
                                        <option value="{{ $tag->id }}">{{ $tag->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12 questions-wrapper">
                            <div class="row question-item border p-3 mt-4 m-2">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Question <span style="color: red">*</span></label>
                                        <textarea name="contents[INDEX][questions][0][question]" class="form-control" rows="5" required></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Answer <span style="color: red">*</span></label>
                                        <select name="contents[INDEX][questions][0][answer]" class="form-control" required>
                                            <option value="1">Yes</option>
                                            <option value="0">No</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12 text-right">
                                    <button type="button" class="btn btn-primary addMore">Add more</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-right mt-2">
                    <button type="button" class="btn btn-danger btn-sm remove-item">
                        Remove
                    </button>
                </div>
            </div>


            </script>
        </div>
    </div>

@endsection

@push('scripts')
    <script type="text/javascript" src="https://jeremyfagis.github.io/dropify/dist/js/dropify.min.js"></script>

    <!-- FilePond JS Reverted -->
    {{-- <script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
    <script src="https://unpkg.com/filepond/dist/filepond.js"></script>
    <script src="https://unpkg.com/jquery-filepond/filepond.jquery.js"></script> --}}

    <script>
        // Global FilePond Configuration Reverted
        /* FilePond.setOptions({
            server: {
                url: "{{ config('filepond.server.url') }}",
                process: '/',
                revert: '/',
                patch: '/?patch=',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                }
            },
            chunkUploads: true,
            chunkSize: 2000000, // 2MB chunks
            labelIdle: 'Drag & Drop your video or <span class="filepond--label-action">Browse</span>',
            maxFileSize: '512MB'
        }); */

        async function uploadFileInChunks(inputElement, progressContainer, progressBar, progressPercentage, hiddenInput) {
            const file = inputElement.files[0];
            if (!file) return false;

            const chunkSize = 2 * 1024 * 1024; // 2MB
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

                try {
                    const response = await $.ajax({
                        url: "{{ route('video.chunkUpload') }}",
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                            'X-File-Name': fileName
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
                    flasher.error('Chunk upload failed: ' + error.message);
                    return false;
                }
            }
            return false;
        }

        // Function to initialize FilePond on an element (Reverted)
        /* function initVideoFilePond(element, index) {
            const pond = FilePond.create(element, {
                acceptedFileTypes: ['video/mp4', 'video/ogg', 'video/webm'],
                onaddfile: (error, fileItem) => {
                    if (!error) {
                        calculateVideoDurationFromFile(fileItem.file, index);
                    }
                }
            });
            return pond;
        } */

        function calculateVideoDurationFromFile(file, index) {
            if (file && file.type.includes("video")) {
                const videoElement = document.createElement("video");
                videoElement.preload = "metadata";
                videoElement.src = URL.createObjectURL(file);

                videoElement.onloadedmetadata = function() {
                    const hiddenInput = document.querySelector(`input[name="contents[${index}][duration]"]`);
                    if (hiddenInput) {
                        const duration = videoElement.duration;
                        const hours = Math.floor(duration / 3600);
                        const minutes = Math.floor((duration % 3600) / 60);
                        const seconds = Math.floor(duration % 60);

                        const formattedDuration =
                            String(hours).padStart(2, '0') + ":" +
                            String(minutes).padStart(2, '0') + ":" +
                            String(seconds).padStart(2, '0');

                        hiddenInput.value = formattedDuration;
                    }
                    URL.revokeObjectURL(videoElement.src);
                };
            }
        }
    </script>

    <script>
        const dropifyConfig = {
            tpl: {
                message: '<div class="dropify-message"><span class="file-icon"></span> <p style="font-size: 24px;">Upload file here</p></div>'
            }
        };

        $(document).ready(function() {
            $('.dropify').dropify(dropifyConfig);
            $('.video-chunk-input').dropify({
                messages: {
                    'default': 'Upload file here',
                    'replace': 'Drag and drop or click to replace',
                    'remove': 'Remove',
                    'error': 'Ooops, something wrong appended.'
                }
            });
        });
    </script>
    {{-- <script src="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag@3.1.0/dist/js/multi-select-tag.js"></script> --}}
    <script src="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag@4.0.1/dist/js/multi-select-tag.min.js"></script>
    <script>
        $(document).ready(function() {
            let tagIndex = 0;

            $('.content-tags').each(function() {
                if (!$(this).attr('id')) {
                    let id = 'content-tags-existing-' + tagIndex++;
                    $(this).attr('id', id);

                    new MultiSelectTag(id, {
                        rounded: true,
                        shadow: true,
                        placeholder: 'Search',
                        tagColor: {
                            textColor: '#327b2c',
                            borderColor: '#92e681',
                            bgColor: '#eaffe6',
                        }
                    });
                }
            });
        });
    </script>
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
    <script>
        $(document).on('click', '.edit-add-image', function() {
            const wrapper = $(this).closest('.content-form').find('.activity-images-wrapper');
            const contentIndex = $(this).closest('.details-item').find('.content-type').attr('name').match(/\d+/)?.[0] ??
                'INDEX';
            const inputName = wrapper.find('input[type=file]').first().attr('name') ||
                `contents[${contentIndex}][activity_image][]`;
            const input = `
                <div class="col-md-6 mt-3 single-gallery-image position-relative">
                    <button type="button"
                        class="remove-gallery-image absolute -top-2 -right-2 z-10 size-6 bg-red-500 text-white rounded-full flex items-center justify-center shadow-lg hover:bg-red-600 transition-colors">×</button>
                    <input type="file" name="${inputName}" class="dropify" data-height="200">
                </div> `;

            wrapper.append(input);
            wrapper.find('.dropify').last().dropify(dropifyConfig);
        });
        $(document).on('click', '.remove-gallery-image', function() {
            $(this).closest('.single-gallery-image').remove();
        });
        $(document).on('click', '.update-add-image', function() {
            const wrapper = $(this).closest('.content-form').find('.activity-images-wrapper');

            // Find the last index in the current set
            let lastIndex = -1;
            wrapper.find('input[type=file]').each(function() {
                const name = $(this).attr('name');
                const matches = name.match(/\[(\d+)\]$/);
                if (matches && matches[1]) {
                    lastIndex = Math.max(lastIndex, parseInt(matches[1]));
                }
            });

            const newIndex = lastIndex + 1;
            const contentIndex = $(this).closest('.details-item').find('.content-type').attr('name').match(/\d+/)[
                0];

            const input = $(`
                <div class="col-md-6 mt-3 single-gallery-image position-relative">
                    <button type="button"
                        class="remove-gallery-image absolute -top-2 -right-2 z-10 size-6 bg-red-500 text-white rounded-full flex items-center justify-center shadow-lg hover:bg-red-600 transition-colors">×</button>
                    <input type="file" name="contents[${contentIndex}][activity_image][${newIndex}]" class="dropify" data-height="200">
                </div>
            `);

            wrapper.append(input);
            input.find('.dropify').dropify(dropifyConfig);
        });
    </script>

    <script>
        // let contentIndex = 0;
        let contentIndex = {{ count($content) }};
        let tagIndex = 0;

        $('#addDetailsBtn').on('click', function() {
            let template = $('#details-template').html();
            template = template.replace(/INDEX/g, contentIndex);

            let block = $(template);
            $('#details-wrapper').append(block);

            // block.find('.dropify').dropify(dropifyConfig);
            block.find('.dropify:not(.video-chunk-input)').each(function() {
                $(this).dropify(dropifyConfig);
            });

            block.find('.video-chunk-input').each(function() {
                $(this).dropify({
                    messages: {
                        'default': 'Upload file here',
                        'replace': 'Drag and drop or click to replace',
                        'remove': 'Remove',
                        'error': 'Ooops, something wrong appended.'
                    }
                });
            });

            block.find('.video-filepond').each(function() {
                initVideoFilePond(this, contentIndex);
            });

            block.find('.content-tags').each(function() {
                let id = 'content-tags-' + tagIndex++;
                $(this).attr('id', id);

                new MultiSelectTag(id, {
                    rounded: true,
                    shadow: true,
                    placeholder: 'Search',
                    tagColor: {
                        textColor: '#327b2c',
                        borderColor: '#92e681',
                        bgColor: '#eaffe6'
                    }
                });
            });

            // Trigger AFTER MultiSelectTag is initialized so required is never set on tags selects
            block.find('.content-form').find('input, select:not(.tags-required), textarea').prop('required', false);

            contentIndex++;
        });


        $(document).on('change', '.content-type', function() {
            const wrapper = $(this).closest('.details-item');
            const type = $(this).val();

            // Hide all forms and remove required attribute (exclude tags-required to avoid MultiSelectTag issue)
            wrapper.find('.content-form').addClass('d-none').find('input, select:not(.tags-required), textarea')
                .prop('required', false);

            // Show selected form and add required attribute to its non-tags fields
            let activeForm = wrapper.find('.content-form.' + type);
            activeForm.removeClass('d-none').find('input, select:not(.tags-required), textarea').each(function() {
                // If it's a file input and has a default file, don't make it required
                if ($(this).attr('type') === 'file' && $(this).data('default-file')) {
                    $(this).prop('required', false);
                } else {
                    $(this).prop('required', true);
                }
            });
        });
        // 🔥 trigger on page load (EDIT MODE)
        $(document).ready(function() {
            $('.details-item').each(function() {
                const block = $(this);

                // Initial state: remove required from all except the active form (exclude tags-required)
                block.find('.content-form').each(function() {
                    if ($(this).hasClass('d-none')) {
                        $(this).find('input, select:not(.tags-required), textarea').prop('required',
                            false);
                    } else {
                        $(this).find('input, select:not(.tags-required), textarea').each(
                    function() {
                            // If it's a file input and has a default file, don't make it required
                            if ($(this).attr('type') === 'file' && $(this).data(
                                    'default-file')) {
                                $(this).prop('required', false);
                            } else {
                                $(this).prop('required', true);
                            }
                        });
                    }
                });

                // Initialize FilePond for Video uploads (Existing items)
                block.find('.video-filepond').each(function() {
                    const indexMatch = block.find('.content-type').attr('name').match(/\d+/);
                    if (indexMatch) {
                        initVideoFilePond(this, indexMatch[0]);
                    }
                });
            });
        });

        // Remove block
        $(document).on('click', '.remove-item', function() {
            $(this).closest('.details-item').remove();
        });
    </script>

    <script>
        async function calculateVideoDuration(input) {
            return new Promise((resolve) => {
                const file = input.files[0];
                if (!file || !file.type || !file.type.includes("video")) {
                    resolve(null);
                    return;
                }

                const videoElement = document.createElement("video");
                videoElement.preload = "metadata";
                const objectUrl = URL.createObjectURL(file);
                videoElement.src = objectUrl;

                // Set timeout to handle cases where metadata never loads
                const timeoutId = setTimeout(() => {
                    URL.revokeObjectURL(objectUrl);
                    resolve(null);
                    console.warn('Video metadata loading timeout - duration could not be calculated');
                }, 5000); // 5 second timeout

                videoElement.onloadedmetadata = function() {
                    clearTimeout(timeoutId);

                    // Find hidden input in the same details-item block
                    let hiddenInput = $(input).closest('.details-item').find(".video-duration-input")[0];

                    if (hiddenInput && videoElement.duration && videoElement.duration > 0 && isFinite(
                            videoElement.duration)) {
                        const duration = videoElement.duration;
                        const hours = Math.floor(duration / 3600);
                        const minutes = Math.floor((duration % 3600) / 60);
                        const seconds = Math.floor(duration % 60);

                        const formattedDuration =
                            String(hours).padStart(2, '0') + ":" +
                            String(minutes).padStart(2, '0') + ":" +
                            String(seconds).padStart(2, '0');

                        hiddenInput.value = formattedDuration;
                        console.log('Duration calculated:', formattedDuration);
                        URL.revokeObjectURL(objectUrl);
                        resolve(formattedDuration);
                    } else {
                        if (!hiddenInput) {
                            console.warn('Duration input field not found');
                        } else if (!videoElement.duration || videoElement.duration <= 0) {
                            console.warn('Invalid video duration:', videoElement.duration);
                        }
                        URL.revokeObjectURL(objectUrl);
                        resolve(null);
                    }
                };

                videoElement.onerror = function(error) {
                    clearTimeout(timeoutId);
                    console.error('Video loading error:', error);
                    URL.revokeObjectURL(objectUrl);
                    resolve(null);
                };

                // Handle edge case where metadata loads before handler is attached
                if (videoElement.readyState >= 1) {
                    videoElement.onloadedmetadata();
                }
            });
        }
    </script>
    <script>
        $(document).on('click', '.addMore', function() {

            const evaluation = $(this).closest('.evaluation');
            const wrapper = evaluation.find('.questions-wrapper');
            let index = wrapper.find('.question-item').length;

            let template = `
                <div class="col-md-12 question-item ">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Question <span style="color: red">*</span></label>
                                <textarea name="contents[INDEX][questions][${index}][question]" class="form-control" rows="5" required></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Answer <span style="color: red">*</span></label>
                                <select name="contents[INDEX][questions][${index}][answer]" class="form-control" required>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                            <div class="text-right">
                                <button type="button" class="btn btn-danger btn-sm remove-question">
                                    Remove
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            const contentIndex = evaluation.closest('.details-item').find('.content-type')
                .attr('name').match(/\d+/)[0];

            template = template.replace(/INDEX/g, contentIndex);

            wrapper.find('.addMore').parent().after(template);
        });

        $(document).on('click', '.remove-question', function() {
            $(this).closest('.question-item').remove();
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new Sortable(document.getElementById('details-wrapper'), {
                animation: 150,
                // handle: '.drag-handle',
                ghostClass: 'sortable-ghost'
            });
        });
    </script>
    <script>
        function clearFormErrors() {
            $('.field-error-msg').remove();
            $('.form-control-modern').removeClass('is-invalid');
            $('.form-control').removeClass('is-invalid');
            $('.dropify-wrapper').removeClass('is-invalid');
            $('.multi-select-tag').removeClass('is-invalid');
        }

        function showFormErrors(errors) {
            clearFormErrors();
            let firstErrorEl = null;

            $.each(errors, function(key, messages) {
                const msg = messages[0];

                if (key === 'tags' || key.startsWith('tags.')) {
                    const tagsSelect = $('#tags');
                    const wrapper = tagsSelect.next('.multi-select-tag').length ? tagsSelect.next(
                        '.multi-select-tag') : $('.multi-select-tag').first();
                    wrapper.addClass('is-invalid');
                    if (wrapper.length && !wrapper.next('.field-error-msg').length) {
                        wrapper.after(`<span class="field-error-msg">${msg}</span>`);
                        if (!firstErrorEl) firstErrorEl = wrapper;
                    }
                    return;
                }

                const parts = key.split('.');
                let bracketName = parts[0];
                for (let i = 1; i < parts.length; i++) {
                    bracketName += '[' + parts[i] + ']';
                }

                let fieldEl = $(`[name="${bracketName}"]`).first();
                if (!fieldEl.length) fieldEl = $(`[name="${bracketName}[]"]`).first();

                if (!fieldEl.length) return;

                if (key.match(/^contents\.\d+\.tags(\.|$)/)) {
                    const wrapper = fieldEl.next('.multi-select-tag');
                    const errorAnchor = wrapper.length ? wrapper : fieldEl;
                    wrapper.addClass('is-invalid');
                    if (!errorAnchor.next('.field-error-msg').length) {
                        errorAnchor.after(`<span class="field-error-msg">${msg}</span>`);
                    }
                    if (!firstErrorEl) firstErrorEl = errorAnchor;
                    return;
                }

                if (fieldEl.hasClass('video-final-path')) {
                    const block = fieldEl.closest('.details-item');
                    const videoInput = block.find('.video-chunk-input').first();
                    const dropifyWrapper = videoInput.closest('.dropify-wrapper');
                    const errorAnchor = dropifyWrapper.length ? dropifyWrapper : videoInput;
                    dropifyWrapper.addClass('is-invalid');
                    if (!errorAnchor.next('.field-error-msg').length) {
                        errorAnchor.after(`<span class="field-error-msg">${msg}</span>`);
                    }
                    if (!firstErrorEl) firstErrorEl = errorAnchor;
                    return;
                }

                fieldEl.addClass('is-invalid');

                if (fieldEl.hasClass('dropify')) {
                    const dropifyWrapper = fieldEl.closest('.dropify-wrapper');
                    dropifyWrapper.addClass('is-invalid');
                    if (!dropifyWrapper.next('.field-error-msg').length) {
                        dropifyWrapper.after(`<span class="field-error-msg">${msg}</span>`);
                    }
                    if (!firstErrorEl) firstErrorEl = dropifyWrapper;
                    return;
                }

                if (!fieldEl.next('.field-error-msg').length) {
                    fieldEl.after(`<span class="field-error-msg">${msg}</span>`);
                }

                if (!firstErrorEl) firstErrorEl = fieldEl;
            });

            if (firstErrorEl && firstErrorEl.length) {
                setTimeout(function() {
                    $('html, body').animate({
                        scrollTop: firstErrorEl.offset().top - 120
                    }, 400);
                }, 50);
            }
        }

        $('#courseForm').on('submit', async function(e) {
            e.preventDefault();
            clearFormErrors();

            const submitBtn = $(this).find('button[type="submit"]');
            const originalBtnText = submitBtn.text();

            submitBtn.prop('disabled', true).text('Processing...');

            $('.multi-select-tag input').prop('required', false);
            let isValid = true;
            let firstInvalid = null;

            // Manual validation for multi-select tags
            $('.tags-required').each(function() {
                if ($(this).val() === null || $(this).val().length === 0) {
                    if (!$(this).closest('.content-form').hasClass('d-none') || $(this).attr('id') ===
                        'tags') {
                        isValid = false;
                        const wrapper = $(this).next('.multi-select-tag').length ? $(this).next(
                            '.multi-select-tag') : $(this).closest('.form-group').find('.multi-select-tag');
                        wrapper.addClass('is-invalid');
                        if (!wrapper.next('.field-error-msg').length) {
                            wrapper.after(`<span class="field-error-msg">Please select at least one tag.</span>`);
                        }
                        if (!firstInvalid) firstInvalid = wrapper;
                    }
                } else {
                    const wrapper = $(this).next('.multi-select-tag').length ? $(this).next(
                        '.multi-select-tag') : $(this).closest('.form-group').find('.multi-select-tag');
                    wrapper.removeClass('is-invalid');
                }
            });

            if (!isValid) {
                submitBtn.prop('disabled', false).text(originalBtnText);
                if (firstInvalid) {
                    $('html, body').animate({
                        scrollTop: firstInvalid.offset().top - 100
                    }, 500);
                }
                return false;
            }

            // Chunk upload logic for all visible video blocks
            const videoInputs = $('.video-chunk-input');
            let allUploaded = true;

            for (let i = 0; i < videoInputs.length; i++) {
                const input = videoInputs[i];
                const block = $(input).closest('.details-item');

                // Only upload if it's a video type and the block is visible
                if (!$(input).closest('.content-form').hasClass('d-none')) {
                    const file = input.files[0];
                    if (file) {
                        submitBtn.prop('disabled', true).text('Uploading Videos...');

                        const progressContainer = block.find('.chunk-progress-container');
                        const progressBar = block.find('.chunk-upload-bar');
                        const progressPercentage = block.find('.chunk-upload-percentage');
                        const hiddenInput = block.find('.video-final-path');

                        // Ensure duration is calculated if it hasn't been yet
                        const durationInput = block.find('.video-duration-input');
                        if (!durationInput.val() || durationInput.val() === '00:00:00') {
                            await calculateVideoDuration(input);
                        }

                        // If still empty, default to 00:00:00 to avoid SQL error
                        if (!durationInput.val()) {
                            durationInput.val('00:00:00');
                        }

                        const success = await uploadFileInChunks(input, progressContainer, progressBar,
                            progressPercentage, hiddenInput);
                        if (!success) {
                            allUploaded = false;
                            break;
                        }
                    } else {
                        const durationInput = block.find('.video-duration-input');
                        if (!durationInput.val()) {
                            durationInput.val('00:00:00');
                        }
                    }
                }
            }

            if (!allUploaded) {
                submitBtn.prop('disabled', false).text(originalBtnText);
                return false;
            }

            // Update names and submit
            $('#details-wrapper .details-item').each(function(newIndex) {
                $(this).find('input, select, textarea').each(function() {
                    let name = $(this).attr('name');
                    if (!name) return;
                    name = name.replace(/contents\[\d+]/, 'contents[' + newIndex + ']');
                    $(this).attr('name', name);
                });
            });

            let formData = new FormData(this);

            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                success: function(resp) {
                    if (resp.success) {
                        flasher.success(resp.message);
                        setTimeout(() => {
                            window.location.href = "{{ route('course.index_new') }}";
                        }, 1000);
                    } else {
                        flasher.error(resp.message || 'Something went wrong');
                        submitBtn.prop('disabled', false).text(originalBtnText);
                    }
                },
                error: function(xhr) {
                    submitBtn.prop('disabled', false).text(originalBtnText);

                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON?.errors;
                        if (errors) {
                            showFormErrors(errors);
                            flasher.error('Please fix the highlighted errors below.');
                        } else {
                            flasher.error('Validation failed. Please check your inputs.');
                        }
                    } else {
                        flasher.error(xhr.responseJSON?.message || 'An error occurred during submission.');
                    }
                }
            });
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
    
