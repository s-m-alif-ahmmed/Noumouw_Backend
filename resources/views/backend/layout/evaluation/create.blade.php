@extends('backend.app')
@section('title', 'Evaluation')
@section('title_url')
    <a href="{{ route('evaluation.index') }}">Evaluation</a>
@endsection
@section('tabName')
    <a href="{{ route('dashboard') }}">Home</a>
@endsection

@push('styles_top')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag@3.1.0/dist/css/multi-select-tag.css">
@endpush

{{-- Push additional styles if needed --}}
@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@24.7.0/build/css/intlTelInput.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.0/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="{{ asset('vendor/flasher/flasher.min.css') }}" rel="stylesheet">
    <style>

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
                <h1 class="text-xl">Create Evaluation</h1>
                <a href="{{ route('evaluation.index') }}"
                    class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600
                    focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring
                    active:ring-custom-100 dark:ring-custom-400/20">Back</a>
            </div>
                <form action="{{ route('evaluation.store') }}" id="video_upload" method="POST" enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" name="duration">

                    <div class="w-full">
                        <div class="pb-5 flex gap-x-5">
                            <div class="w-1/2">
                                <x-backend.input type="text" name="title" label="Title" :required="true" />
                            </div>
                            <div class="w-1/2">
                                <x-backend.select2-single name="course_id" label="Course" :required="true">
                                    <option value="">Select a Course</option>
                                    @foreach ($courses as $course)
                                        <option value="{{ $course->id }}"
                                            @if (old('course_id', request('course_id')) == $course->id) selected @elseif ($selectedCourse && $selectedCourse->id == $course->id) selected @endif >
                                            {{ $course->name }}
                                        </option>
                                    @endforeach
                                </x-backend.select2-single>
                            </div>
                        </div>
                        <div class="flex w-full gap-5 mb-5">
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
                    </div>

                    {{-- ------------------- Form Buttons ------------- --}}
                    <div class="flex justify-start mt-6 gap-x-4">
                        <button type="submit"
                            class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <link rel="stylesheet" href="{{ asset('backend/js/datatables/jquery-3.7.0.js') }}">
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
