@extends('backend.app')

@section('title', 'Course')
@section('title_url')
    <a href="{{ route('course.index') }}">Course</a>
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
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@24.7.0/build/css/intlTelInput.css">
    <style>
        .dropify-wrapper {
            height: 185px;
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
                <h1 class="text-xl">Create a Course</h1>
                <a href="{{ route('course.index') }}"
                   class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600
                    focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100
                     dark:ring-custom-400/20">Back</a>
            </div>

            <form action="{{ route('course.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="flex w-full gap-5 mb-5">
                    <div class="w-1/2">
                        <x-backend.input type="text" name="name" label="Course Name" :required="true" />
                    </div>
                    <div class="w-1/2">
                        <label for="tags" class="inline-block mb-2 text-base font-medium">Tags<span
                                style="color: red">*</span></label>
                        <select name="tags[]" id="tags" multiple
                                class="border border-gray-300 rounded-lg mt-1 p-2 w-1">
                            {{-- <option disabled>Select a Tag</option> --}}
                            @foreach ($data as $tag)
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

                <div class="flex gap-5 mb-5">
                    <div class="w-1/2    ">
                        <x-backend.dropify type="file" name="thumbnail" label="Thumbnail" :required="true" />
                    </div>

                    <div class="w-1/2">
                        <x-backend.select2-single name="subscription_plans_id" label="Subscription Plan " :required="true">
                            <option selected disabled>Select a Plan Type</option>
                            @foreach ($subscription as $plan)
                                <option value="{{ $plan->id }}" @if (old('subscription_plans_id') == $plan->id) selected @endif>
                                    {{ $plan->name }}
                                </option>
                            @endforeach
                        </x-backend.select2-single>
                    </div>

                </div>
                {{-- ------------------- Form Buttons ------------- --}}
                <div class="flex justify-start mt-6 gap-x-4">
                    <button type="submit"
                            class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white
                    focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600
                    active:ring active:ring-custom-100 dark:ring-custom-400/20">Submit</button>
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
