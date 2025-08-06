@extends('backend.app')

@section('title', 'Course Details')
@section('title_url')
    <a href="{{ route('course.index') }}">Course Details</a>
@endsection
@section('tabName')
    Home
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('backend/js/datatables/jquery-3.7.0.js') }}">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.0/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="{{ asset('vendor/flasher/flasher.min.css') }}" rel="stylesheet">

    <style>
        .card-container {
            position: relative;
            /* Enable absolute positioning for children */
            display: flex;
            gap: 20px;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .back-button {
            position: absolute;
            /* Position the button at top-right */
            top: 20px;
            right: 20px;
        }

        .back-button:hover {
            background-color: #4a5568;
            /* Darker shade on hover */
        }

        .thumbnail-container {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .thumbnail {
            width: 100%;
            max-width: 350px;
            height: auto;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
        }

        .course-info {
            flex: 2;
            display: flex;
            flex-direction: column;
            gap: 12px;
            justify-content: center;
        }

        .course-title {
            font-size: 1.75rem;
            font-weight: 600;
            color: #333;
        }

        @media screen and (max-width: 768px) {
            .course-title {
                font-size: 1.5rem;
            }
        }

        .course-description {
            font-size: 1rem;
            color: #555;
            line-height: 1.6;
        }

        .status-container {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .status-label {
            font-weight: bold;
            color: #333;
        }

        .status-badge {
            padding: 4px 16px;
            font-size: 0.9rem;
            font-weight: 600;
            color: #fff;
            border-radius: 20px;
            background-color: #e2e8f0;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .status-badge.active {
            background-color: #48bb78;
            /* Green for Active */
        }

        .status-badge.inactive {
            background-color: #f56565;
            /* Red for Inactive */
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

        .dropify-wrapper {
            height: 200px;
        }

        .edit-btn {
            padding: 5px 10px;
            background-color: #f1f1f1;
            border: 1px solid #ccc;
            cursor: pointer;
        }
    </style>
@endpush

@section('content')

    {{-- Content view modal --}}
    <x-backend.modal id="edit-question" title="Course Content Details" class="hidden">
        <div class="p-6 bg-white rounded-lg shadow-lg">
            <div class="space-y-6">

                <!-- Title Section -->
                <div>
                    <h4 class="text-lg font-semibold text-gray-700">Title:</h4>
                    <p id="modal-title" class="text-gray-600">Sample Title</p>
                </div>

                <!-- Description Section -->
                <div>
                    <h5 class="text-lg font-semibold text-gray-700">Description:</h5>
                    <p id="modal-description" class="text-gray-600">Sample description goes here.</p>
                </div>

                <!-- Media Section -->
                <div class="space-y-4">
                    <!-- Audio Section -->
                    <div>
                        <h5 class="text-lg font-semibold text-gray-700">Audio:</h5>
                        <audio src="" id="modal-audio" controls class="w-full bg-gray-100 rounded-md"></audio>
                    </div>

                    <!-- Video Section -->
                    <div>
                        <h5 class="text-lg font-semibold text-gray-700">Video:</h5>
                        <video src="" id="modal-video" controls class="w-full bg-gray-100 rounded-md"></video>
                    </div>
                </div>

            </div>
        </div>
    </x-backend.modal>

    {{-- Course show --}}
    <div class="bg-white shadow rounded-lg p-6 space-y-6">
        {{-- Top Section: Thumbnail + Info --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Thumbnail --}}
            <div>
                <div class="overflow-hidden rounded-lg">
                    <img class="w-full h-auto object-cover"
                         src="{{ $data->thumbnail ? asset($data->thumbnail) : asset('/backend/no-image.jpg') }}"
                         alt="Course Thumbnail">
                </div>
            </div>

            {{-- Course Info --}}
            <div class="space-y-4">
                <p class="text-lg font-semibold">
                    <strong>Course Name: </strong>{{ $data->name }}
                </p>
                <p>
                    <strong>Description:</strong> {{ $data->description ?? 'N/A' }}
                </p>
                <div>
                    <span class="font-medium">Status:</span>
                    <span class="inline-block px-3 py-1 rounded-full text-white text-sm
                    {{ $data->status == 'active' ? 'bg-green-600' : 'bg-red-500' }}">
                    {{ ucfirst($data->status) }}
                </span>
                </div>
            </div>
        </div>

        {{-- Button Section --}}
        <div class="grid grid-cols-2 md:flex md:justify-end gap-4 pt-4">
            <button data-modal-open="add-video" class="btn bg-green-600 text-white">Add Video</button>
            <button data-modal-open="add-activity" class="btn bg-amber-600 text-white">Add Activity</button>
            <button data-modal-open="add-podcast" class="btn bg-cyan-600 text-white">Add Podcast</button>
            <button data-modal-open="add-evaluation" class="btn bg-lime-600 text-white">Add Evaluation</button>
        </div>
    </div>

    {{-- Course Content Table --}}
    <div class="w-full p-5 mt-8 shadow-2xl">
        <h2 class="text-xl py-2">Course Content</h2>
        <table id="course_details" class="display stripe group table-responsive">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Content Title</th>
                    <th>Content Type</th>
                    <th>Description</th>
                    <th>Audio</th>
                    <th>Video</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody class="sortable">
            </tbody>
        </table>
    </div>

   {{-- Crete Video modal --}}
   <x-backend.modal id="add-video" class="w-full md:w-1/2 max-w-6xl justify-center" title="Create Video">
       <form id="video_upload" enctype="multipart/form-data">
           @csrf @method('POST')
           <div class="grid grid-cols-1 gap-4 xl:grid-cols-12">

               <input type="hidden" name="duration">

               {{--------------------- Title ---------------}}
               <div class="xl:col-span-12">
                   <x-backend.input-ajax type="text" name="title" label="Title" :required="true"
                                         placeholder="Enter Title here" />
               </div>

               {{--------------------- Instructor  ---------------}}
               <div class="xl:col-span-12">
                   <x-backend.select2-single name="instructor_id" label="Instructor" :required="true">
                       <option value="">Select an Instructor</option>
                       @foreach ($instructors as $instructor)
                           <option value="{{ $instructor->id }}"
                                   @if (old('instructor_id') == $instructor->id) selected @endif>
                               {{ $instructor->name }}
                           </option>
                       @endforeach
                   </x-backend.select2-single>
               </div>

               {{--------------------- Coures  ---------------}}
               <div class="xl:col-span-12">
                   <x-backend.select2-single name="course_id" label="Course" :required="true" >
                       <option value="">Select an Course</option>
                       @foreach ($courses as $course)
                           <option value="{{ $course->id }}"
                                   @if (old('course_id') == $course->id || (isset($data) && $data->id == $course->id)) selected @endif>
                               {{ $course->name }}
                           </option>
                       @endforeach
                   </x-backend.select2-single>
               </div>

               {{--------------------- Tags  ---------------}}
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
                   @error('tags')
                   <span class="text-red-500 text-sm">{{ $message }}</span>
                   @enderror
                   @error('tags.*')
                   <span class="text-red-500 text-sm">{{ $message }}</span>
                   @enderror
               </div>

               {{--------------------- Images  ---------------}}
               <div class="xl:col-span-12">
                   <x-backend.dropify type="file" name="file" label="Video" :required="true"
                                      onchange="calculateVideoDuration(this)" />
               </div>


           </div>

           <div class="flex justify-end gap-2 mt-4">
               <button type="submit"
                       class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600
                    focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">
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

                {{--------------------- Title ---------------}}
                <div class="xl:col-span-12">
                    <x-backend.input type="text" id="name" name="title" label="Activity Name"
                                     :required="true" />
                </div>

                {{--------------------- Coures  ---------------}}
                <div class="xl:col-span-12">
                    <x-backend.select2-single name="course_id" label="Select an Course" :required="true" >
                        <option value="">Select an Course</option>
                        @foreach ($courses as $course)
                            <option value="{{ $course->id }}"
                                    @if (old('course_id') == $course->id || (isset($data) && $data->id == $course->id)) selected @endif>
                                {{ $course->name }}
                            </option>
                        @endforeach
                    </x-backend.select2-single>
                </div>

                {{--------------------- Description  ---------------}}
                <div class="xl:col-span-12">
                    <x-backend.text-area name="description" label="Description" :required="true"
                                         placeholder="Enter Description here"> </x-backend.text-area>
                </div>

                {{--------------------- Tags  ---------------}}
                <div class="xl:col-span-12">
                    <label for="activity-tags" class="inline-block mb-2 text-base font-medium">Tags<span
                            style="color: red">*</span></label>
                    <select name="tags[]" id="activity-tags" multiple class="border border-gray-300 rounded-lg mt-1 p-2">
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

                {{--------------------- Images  ---------------}}
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


                {{--------------------- Title ---------------}}
                <div class="xl:col-span-12">
                    <x-backend.input type="text" name="title" label="Title" :required="true" />
                </div>

                {{--------------------- Instructor  ---------------}}
                <div class="xl:col-span-12">

                    @php
                        $selectedCourse = null;
                    @endphp
                    <x-backend.select2-single name="instructor_id" label="Instructor" :required="true">
                        <option value="">Select an Instructor</option>
                        @foreach ($instructors as $instructor)
                            <option value="{{ $instructor->id }}"
                                    @if (old('instructor_id') == $instructor->id || (isset($data) && $data->id == $instructor->id)) selected @endif>
                                {{ $instructor->name }}
                            </option>
                        @endforeach
                    </x-backend.select2-single>
                </div>

                {{--------------------- Coures  ---------------}}
                <div class="xl:col-span-12">
                    <x-backend.select2-single name="course_id" label="Select an Course" :required="true" >
                        <option value="">Select an Course</option>
                        @foreach ($courses as $course)
                            <option value="{{ $course->id }}"
                                    @if (old('course_id') == $course->id || (isset($data) && $data->id == $course->id)) selected @endif>
                                {{ $course->name }}
                            </option>
                        @endforeach
                    </x-backend.select2-single>
                </div>

                {{--------------------- Tags  ---------------}}
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
                    @error('tags')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                    @error('tags.*')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                {{--------------------- Description  ---------------}}
                <div class="xl:col-span-12">
                    <x-backend.text-area name="description" label="Description" :required="true"
                                         placeholder="Enter Description here"> </x-backend.text-area>
                </div>



                {{--------------------- Images  ---------------}}
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

                {{--------------------- Title ---------------}}
                <div class="xl:col-span-12">
                    <x-backend.input type="text" name="title" label="Title" :required="true" />
                </div>

                {{--------------------- Coures  ---------------}}
                <div class="xl:col-span-12">
                    <x-backend.select2-single name="course_id" label="Select an Course" :required="true" >
                        <option value="">Select an Course</option>
                        @foreach ($courses as $course)
                            <option value="{{ $course->id }}"
                                    @if (old('course_id') == $course->id || (isset($data) && $data->id == $course->id)) selected @endif>
                                {{ $course->name }}
                            </option>
                        @endforeach
                    </x-backend.select2-single>
                </div>

                {{--------------------- Tags  ---------------}}
                <div class="xl:col-span-12">
                    <label for="evaluation-tags" class="inline-block mb-2 text-base font-medium">Tags<span
                            style="color: red">*</span></label>
                    <select name="tags[]" id="evaluation-tags" multiple class="border border-gray-300 rounded-lg mt-1 p-2">
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
    <x-backend.modal id="edit-video" class="w-full md:w-1/2 max-w-6xl justify-center" title="Edit Video">
        <form id="video_update" method="POST" action="#" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 gap-4 xl:grid-cols-12">

                <input type="hidden" name="duration">

                {{--------------------- Title ---------------}}
                <div class="xl:col-span-12">
                    <x-backend.input-ajax type="text" name="title" label="Title" :required="true"
                                          placeholder="Enter Title here" />
                </div>

                {{--------------------- Instructor  ---------------}}
                <div class="xl:col-span-12">
                    <x-backend.select2-single name="instructor_id" label="Instructor" :required="true">
                        <option value="">Select an Instructor</option>
                        @foreach ($instructors as $instructor)
                            <option value="{{ $instructor->id }}"
                                    @if (old('instructor_id') == $instructor->id) selected @endif>
                                {{ $instructor->name }}
                            </option>
                        @endforeach
                    </x-backend.select2-single>
                </div>

                {{--------------------- Coures  ---------------}}
                <div class="xl:col-span-12">
                    <x-backend.select2-single name="course_id" label="Course" :required="true" >
                        <option value="">Select an Course</option>
                        @foreach ($courses as $course)
                            <option value="{{ $course->id }}"
                                    @if (old('course_id') == $course->id || (isset($data) && $data->id == $course->id)) selected @endif>
                                {{ $course->name }}
                            </option>
                        @endforeach
                    </x-backend.select2-single>
                </div>

                {{--------------------- Tags  ---------------}}
                <div class="xl:col-span-12">
                    <label for="tags" class="inline-block mb-2 text-base font-medium">Tags<span
                            style="color: red">*</span></label>
                    <select name="tags[]" id="edit-video-tags" multiple class="border border-gray-300 rounded-lg mt-1 p-2">
                        <option disabled>Select a Tag</option>
                        @foreach ($tags as $tag)
                            <option value="{{ $tag->id }}"  >
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

                {{--------------------- Video File  ---------------}}
                <div class="xl:col-span-12">
                    <x-backend.dropify type="file" name="file" label="Video" :required="true"
                                       onchange="calculateVideoDuration(this)" />
                </div>

                {{--------------------- Old Video File  ---------------}}
                <div class="xl:col-span-12">
                    <div id="video-audio"></div>
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

                {{--------------------- Title ---------------}}
                <div class="xl:col-span-12">
                    <x-backend.input type="text" id="name" name="title" label="Activity Name"
                                     :required="true" />
                </div>

                {{--------------------- Coures  ---------------}}
                <div class="xl:col-span-12">
                    <x-backend.select2-single name="course_id" label="Select an Course" :required="true" >
                        <option value="">Select an Course</option>
                        @foreach ($courses as $course)
                            <option value="{{ $course->id }}"
                                    @if (old('course_id') == $course->id || (isset($data) && $data->id == $course->id)) selected @endif>
                                {{ $course->name }}
                            </option>
                        @endforeach
                    </x-backend.select2-single>
                </div>

                {{--------------------- Description  ---------------}}
                <div class="xl:col-span-12">
                    <x-backend.text-area name="description" label="Description" :required="true"
                                         placeholder="Enter Description here"> </x-backend.text-area>
                </div>


                {{--------------------- Tags  ---------------}}
                <div class="xl:col-span-12">
                    <label for="activity-tags" class="inline-block mb-2 text-base font-medium">Tags<span
                            style="color: red">*</span></label>
                    <select name="tags[]" id="edit-activity-tags" multiple class="border border-gray-300 rounded-lg mt-1 p-2">
                        <option disabled>Select a Tag</option>
                        @foreach ($tags as $tag)
                            <option value="{{ $tag->id }}" @if (in_array($tag->id, old('tags', []))) selected @endif>
                                {{ $tag->title }}</option>
                        @endforeach
                    </select>
                </div>

                {{--------------------- Images  ---------------}}
                <div class="xl:col-span-12">
                    <div class="flex items-center justify-between gap-5 mt-5 mb-3">
                        <label for="gallery_images" class="inline-block  text-base font-medium">Activity Images</label>
                        <button type="button" id="edit-add-image"
                                class="text-white bg-green-500 border-green-500 btn hover:bg-green-600 !p-1">
                            Add image
                        </button>
                    </div>
                    <div class="grid grid-cols-12 gap-3" id="add-images-edit">
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

                {{--------------------- Old Video File  ---------------}}
                <div class="xl:col-span-12">
                    <div id="old-activity-images"  class="flex gap-3"></div>
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

                {{--------------------- Title ---------------}}
                <div class="xl:col-span-12">
                    <x-backend.input type="text" name="title" label="Title" :required="true" />
                </div>

                {{--------------------- Instructor  ---------------}}
                <div class="xl:col-span-12">

                    @php
                        $selectedCourse = null;
                    @endphp
                    <x-backend.select2-single name="instructor_id" label="Instructor" :required="true">
                        <option value="">Select an Instructor</option>
                        @foreach ($instructors as $instructor)
                            <option value="{{ $instructor->id }}"
                                    @if (old('instructor_id') == $instructor->id || (isset($data) && $data->id == $instructor->id)) selected @endif>
                                {{ $instructor->name }}
                            </option>
                        @endforeach
                    </x-backend.select2-single>
                </div>

                {{--------------------- Coures  ---------------}}
                <div class="xl:col-span-12">
                    <x-backend.select2-single name="course_id" label="Select an Course" :required="true" >
                        <option value="">Select an Course</option>
                        @foreach ($courses as $course)
                            <option value="{{ $course->id }}"
                                    @if (old('course_id') == $course->id || (isset($data) && $data->id == $course->id)) selected @endif>
                                {{ $course->name }}
                            </option>
                        @endforeach
                    </x-backend.select2-single>
                </div>

                {{--------------------- Tags  ---------------}}
                <div class="xl:col-span-12">
                    <label for="activity-tags" class="inline-block mb-2 text-base font-medium">Tags<span
                            style="color: red">*</span></label>
                    <select name="tags[]" id="edit-podcast-tags" multiple class="border border-gray-300 rounded-lg mt-1 p-2">
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

                {{--------------------- Description  ---------------}}
                <div class="xl:col-span-12">
                    <x-backend.text-area name="description" label="Description" :required="true"
                                         placeholder="Enter Description here"> </x-backend.text-area>
                </div>



                {{--------------------- Audio File  ---------------}}
                <div class="xl:col-span-12">
                    <x-backend.dropify type="file" name="file" label="File" :required="true" />
                </div>

                {{--------------------- Old audio File  ---------------}}
                <div class="xl:col-span-12">
                    <div id="old-audio"></div>
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

                {{--------------------- Title ---------------}}
                <div class="xl:col-span-12">
                    <x-backend.input type="text" name="title" label="Title" :required="true" />
                </div>

                {{--------------------- Coures  ---------------}}
                <div class="xl:col-span-12">
                    <x-backend.select2-single name="course_id" label="Select an Course" :required="true" >
                        <option value="">Select an Course</option>
                        @foreach ($courses as $course)
                            <option value="{{ $course->id }}"
                                    @if (old('course_id') == $course->id || (isset($data) && $data->id == $course->id)) selected @endif>
                                {{ $course->name }}
                            </option>
                        @endforeach
                    </x-backend.select2-single>
                </div>

                {{--------------------- Tags  ---------------}}
                <div class="xl:col-span-12">
                    <label for="evaluation-tags" class="inline-block mb-2 text-base font-medium">Tags<span
                            style="color: red">*</span></label>
                    <select name="tags[]" id="edit-evaluation-tags" multiple class="border border-gray-300 rounded-lg mt-1 p-2">
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

            <div class="flex justify-end gap-2 mt-4">
                <button type="submit"
                        class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600
                    focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">
                    Update Evaluation
                </button>
            </div>
        </form>
    </x-backend.modal>

@endsection


@push('scripts')
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
    <script src="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag@3.1.0/dist/js/multi-select-tag.js"></script>
    {{-- Drofify Image --}}
    <script type="text/javascript" src="https://jeremyfagis.github.io/dropify/dist/js/dropify.min.js"></script>

    <script>
        // Table list
        $(document).ready(function() {
            let table = $('#course_details').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('course.show', $data->id) }}",
                    type: 'GET'
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'title', name: 'title', orderable: true, searchable: false },
                    { data: 'type', name: 'type', orderable: true, searchable: true },
                    {
                        data: 'description', name: 'description', orderable: false, searchable: false,
                        render: function(data) {
                            return data.length > 30 ? data.substring(0, 30) + '...' : data;
                        }
                    },
                    { data: 'audio', name: 'audio', orderable: false, searchable: false },
                    { data: 'video', name: 'video', orderable: false, searchable: false },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ],
                rowId: 'id'  // Ensure each row has a unique identifier
            });

            // Make rows sortable
            $("#course_details tbody").sortable({
                items: "tr",
                cursor: "move",
                opacity: 0.6,
                update: function() {
                    let order = [];
                    $("#course_details tbody tr").each(function(index) {
                        let id = $(this).attr("id");  // Fetch row ID
                        if (id) {
                            order.push({ id: id, position: index + 1 });
                        }
                    });

                    // Send the new order to the server
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


            function openEditModalById(modalId, data) {
                $('#' + modalId).removeClass('hidden').addClass('open');


                $('#modal-title').text(data.title);
                $('#modal-description').text(data.description);


                $('#modal-audio').text(data.audio ?? 'N/A');
                $('#modal-video').text(data.video ?? 'N/A');
            }

            $('.close-modal').on('click', function() {
                $('#edit-question').removeClass('open').addClass('hidden');
            });

        });
    </script>

    {{-- Edit Modal Show by Video, Podcast, Evaluation, Activity Here --}}
    <script>
        // List all your modal IDs here
        const allModals = ['edit-video', 'edit-podcast', 'edit-evaluation', 'edit-activity'];
        console.log(allModals)
        function openModal(modalId) {
            // First close all modals
            allModals.forEach(id => {
                document.getElementById(id)?.classList.add('hidden');
            });

            // Then open the requested modal
            document.getElementById(modalId)?.classList.remove('hidden');
        }

        function closeModal(modalId) {
            document.getElementById(modalId)?.classList.add('hidden');
        }

        // Handle click on modal edit buttons
        document.addEventListener('click', function (e) {
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

    {{---------------------------- Video Script Start Here --------------------------------------}}
    {{-- Store --}}
    <script>
        $(function () {
            $('#video_upload').on('submit', function (e) {
                e.preventDefault();

                console.log('Submitting Form...'); // ✅ Check if this logs

                let formData = new FormData(this);
                let url = "{{ route('video.ajax.store') }}";

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
                    success: function (resp) {
                        console.log('Response:', resp); // ✅ Inspect the response

                        $('#course_details').DataTable().ajax.reload();

                        if (resp.success === true) {
                            flasher.success(resp.message);
                            clearModal('add-video');
                        } else if (resp.errors) {
                            flasher.error(resp.errors[0]);
                        } else {
                            flasher.error(resp.message);
                        }
                    },
                    error: function (xhr) {
                        console.log(xhr.responseText); // ✅ Log server error
                        handleXhrErrors(xhr, 'add-video');
                    }
                });
            });
        });
    </script>

    {{-- Edit --}}
    <script>
        $(document).on('click', '.edit-video-btn', function () {
            let videoId = $(this).data('id');
            let url = "{{ route('video.editData', ':id') }}".replace(':id', videoId);

            $.get(url, function (response) {
                if (response.success) {
                    const video = response.data;

                    console.log(video.tags);


                    // Populate form
                    $('#edit-video input[name="title"]').val(video.title);
                    $('#edit-video select[name="instructor_id"]').val(video.instructor_id).trigger('change');
                    $('#edit-video select[name="course_id"]').val(video.course_id).trigger('change');
                    $('#edit-video-tags').val(video.tags).trigger('change');

                    // Populate tags
                    $('#edit-video-tags select[name="tags[]"]').val(video.tags.map(String)).trigger('change');

                    // Show video preview (optional)
                    if (video.file_url) {
                        const preview = `<video controls width="400" class=""><source src="${video.file_url}" type="video/mp4"></video>`;
                        $('#video-preview').remove(); // Remove any old preview
                        $('#edit-video input[name="file"]');
                        $('#video-audio').html(preview);
                    }

                    // Set form action
                    $('#video_update').attr('action', "{{ route('video.update', ':id') }}".replace(':id', videoId));
                    $('#video_update').find('input[name="_method"]').val('PUT');

                }
            });
        });
    </script>

    {{-- Update --}}
    <script>
        $(function () {
            $('#video_update').on('submit', function (e) {
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
                    success: function (resp) {
                        console.log(resp)
                        $('#course_details').DataTable().ajax.reload();

                        if (resp.success === true) {
                            flasher.success(resp.message);
                            clearModal('edit-video');
                        } else if (resp.errors) {
                            flasher.error(resp.errors[0]);
                        } else {
                            flasher.error(resp.message);
                        }
                    },
                    error: function (xhr) {
                        console.log(xhr.responseText);
                        handleXhrErrors(xhr, 'edit-video');
                    }
                });
            });
        });
    </script>
    {{---------------------------- Video Script End Here --------------------------------------}}



    {{---------------------------- Activity Script Start Here --------------------------------------}}

    {{-- Store --}}
    <script>

        {{-- Create Activity--}}
        $(function () {
            $('#activity-form').on('submit', function (e) {
                e.preventDefault();

                console.log('Submitting Form...'); // ✅ Check if this logs

                let formData    = new FormData(this);
                let url         = "{{ route('activity.store') }}";

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
                    success: function (resp) {
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
                    error: function (xhr) {
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
            $('.dropify').dropify();
        })

        //remove image
        $(document).on('click', '.remove-gallery-section', function() {
            $(this).parent().remove()
        })
    </script>

    {{-- Edit --}}
    <script>
        $(document).on('click', '.edit-video-btn', function () {
            let activityId = $(this).data('id');
            let url = "{{ route('activity.editData', ':id') }}".replace(':id', activityId);

            $.get(url, function (response) {
                if (response.success) {
                    console.log(response);
                    const activity = response.data;

                    // Populate form
                    $('#edit-activity input[name="title"]').val(activity.title);
                    $('#edit-activity select[name="course_id"]').val(activity.course_id).trigger('change');
                    $('#edit-activity textarea[name="description"]').val(activity.description);
                    $('#edit-activity-tags').val(activity.tags).trigger('change');


                    // Show video preview (optional)
                    if (Array.isArray(activity.images) && activity.images.length > 0) {
                        let previewHtml = '';
                        const baseUrl = "{{ url('/') }}/";

                        activity.images.forEach(function(imageUrl) {
                            previewHtml += `<img alt="image" class="d-flex" src="${baseUrl + imageUrl}" width="200"  class="mr-2 mb-2 rounded shadow" style="height: 100px">`;
                        });

                        $('#old-activity-images').html(previewHtml);
                    } else {
                        $('#old-activity-images').html('<p class="text-sm text-gray-500">No images found.</p>');
                    }


                    // Set form action
                    $('#activity-update').attr('action', "{{ route('activity.update', ':id') }}".replace(':id', activityId));
                    $('#activity-update').find('input[name="_method"]').val('PUT');
                }
            });
        });
    </script>
    {{-- Update --}}

    <script>
        $(function () {
            $('#activity-update').on('submit', function (e) {
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
                    success: function (resp) {
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
                    error: function (xhr) {
                        console.log(xhr.responseText);
                        handleXhrErrors(xhr, 'edit-activity');
                    }
                });
            });
        });
    </script>

    {{-- Add image for edit activity   --}}
    <script>
        $(document).ready(function () {
            let imageSectionCount = 1;

            // Add image button click
            $(document).on('click', '#edit-add-image', function () {
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
                $('#gallery_edit_' + imageSectionCount).dropify();
            });

            // Remove image section
            $(document).on('click', '.remove-gallery-section-edit', function () {
                $(this).closest('.single-gallery-image').remove();
            });
        });
    </script>


    {{---------------------------- Activity Script End Here --------------------------------------}}


    {{---------------------------- Podcast Script Start Here --------------------------------------}}

    {{-- Store --}}
    <script>
        $(function () {
            $('#podcast-form').on('submit', function (e) {
                e.preventDefault();

                console.log('Submitting Form...'); // ✅ Check if this logs

                let formData    = new FormData(this);
                let url         = "{{ route('podcast.store') }}";

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
                    success: function (resp) {
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
                    error: function (xhr) {
                        console.log(xhr.responseText); // ✅ Log server error
                        handleXhrErrors(xhr, 'add-podcast');
                    }
                });
            });
        });

    </script>

    {{-- Edit --}}
    <script>
        $(document).on('click', '.edit-video-btn', function () {
            let podcastId = $(this).data('id');
            let url = "{{ route('podcast.editData', ':id') }}".replace(':id', podcastId);

            $.get(url, function (response) {
                if (response.success) {
                    const podcast = response.data;

                    // Populate form
                    $('#edit-podcast input[name="title"]').val(podcast.title);
                    $('#edit-podcast select[name="instructor_id"]').val(podcast.instructor_id).trigger('change');
                    $('#edit-podcast select[name="course_id"]').val(podcast.course_id).trigger('change');
                    $('#edit-podcast textarea[name="description"]').val(podcast.description);
                    $('#edit-podcast-tags').val(podcast.tags).trigger('change');


                    // Show video preview (optional)
                    if (podcast.file_url) {
                        const preview = `<audio controls class=""><source src="${podcast.file_url}" type="audio/mp3"></audio>`;
                        $('#audio-preview').remove(); // Remove any old preview
                        // $('#edit-podcast input[name="file"]').after(`<div id="audio-preview">${preview}</div>`);
                         $('#edit-podcast input[name="file"]');
                        $('#old-audio').html(preview);
                    }

                    // Set form action
                    $('#podcast-update').attr('action', "{{ route('podcast.update', ':id') }}".replace(':id', podcastId));
                    $('#podcast-update').find('input[name="_method"]').val('PUT');

                }
            });
        });
    </script>

    {{-- Update --}}
    <script>
        $(function () {
            $('#podcast-update').on('submit', function (e) {
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
                    success: function (resp) {
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
                    error: function (xhr) {
                        console.log(xhr.responseText);
                        handleXhrErrors(xhr, 'edit-podcast');
                    }
                });
            });
        });
    </script>

    {{---------------------------- Podcast Script End Here --------------------------------------}}


    {{---------------------------- Evaluation Script Start Here --------------------------------------}}

    {{-- Store --}}
    <script>
        $(function () {
            $('#evaluation-form').on('submit', function (e) {
                e.preventDefault();

                console.log('Submitting Form...'); // ✅ Check if this logs

                let formData    = new FormData(this);
                let url         = "{{ route('evaluation.store') }}";


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

                    success: function (resp) {
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
                    error: function (xhr) {
                        console.log(xhr.responseText); // ✅ Log server error
                        handleXhrErrors(xhr, 'add-evaluation');
                    }
                });
            });
        });

    </script>

    {{-- Edit --}}
    <script>
        $(document).on('click', '.edit-video-btn', function () {
           let evaluationId = $(this).data('id');
           let url = "{{ route('evaluation.editData', ':id') }}".replace(':id', evaluationId)

            $.get(url, function (response){
                console.log(response)
               if (response.success) {
                   const evaluation = response.data;

                   $('#edit-evaluation input[name="title"]').val(evaluation.title);
                   $('#edit-evaluation select[name="course_id"]').val(evaluation.course_id).trigger('change');
                   $('#edit-evaluation-tags').val(evaluation.tags).trigger('change');


                   // Set form action
                   $('#evaluation-update').attr('action', "{{ route('evaluation.update', ':id') }}".replace(':id', evaluationId));
                   $('#evaluation-update').find('input[name="_method"]').val('PUT');

               }
            });
        });
    </script>

    {{-- Update --}}
    <script>
        $(function () {
            $('#evaluation-update').on('submit', function (e) {
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
                    success: function (resp) {
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
                    error: function (xhr) {
                        console.log(xhr.responseText);
                        handleXhrErrors(xhr, 'edit-evaluation');
                    }
                });
            });
        });
    </script>

    {{---------------------------- Evaluation Script End Here --------------------------------------}}

    {{-- Drofify Image here --}}
    <script>
        $(document).ready(function() {
            $('.dropify').dropify();
        })

        function calculateVideoDuration(input) {
            const file = input.files[0];
            if (file && file.type.includes("video")) {
                const videoElement = document.createElement("video");
                videoElement.preload = "metadata";

                // Set the video source to the selected file
                videoElement.src = URL.createObjectURL(file);

                videoElement.onloadedmetadata = function() {
                    // Once metadata is loaded, you can access the video duration
                    $("input[name='duration']").val(videoElement.duration);
                };
            } else {
                flasher.error('Invalid Video');
            }
        }
    </script>

    {{-- Multiple Tags Here--}}
    <script>

        // create video tags
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
        });

        // edit video tags
        new MultiSelectTag('edit-video-tags', {
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

        // create activity tags
        new MultiSelectTag('activity-tags', {
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
        });

        // edit activity tags
        new MultiSelectTag('edit-activity-tags', {
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
        });

        // Create podcast tags
        new MultiSelectTag('podcast-tags', {
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

        // edit podcast tags
        new MultiSelectTag('edit-podcast-tags', {
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

        // Create evaluation tags
        new MultiSelectTag('evaluation-tags', {
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

        // Edit evaluation tags
        new MultiSelectTag('edit-evaluation-tags', {
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
