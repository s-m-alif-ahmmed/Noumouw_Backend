@extends('backend.app')

@section('title', 'Course')
@section('title_url')
    <a href="{{ route('course.index') }}">Course</a>
@endsection
@section('tabName')
    <a href="{{ route('dashboard') }}">Home</a>
@endsection

{{-- Push additional styles if needed --}}
@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@24.7.0/build/css/intlTelInput.css">
    <link rel="stylesheet" href="{{ asset('backend/js/datatables/jquery-3.7.0.js') }}">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.0/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="{{ asset('vendor/flasher/flasher.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.css">

    <style>
        .dropify-wrapper {
            height: 185px;
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
            <div class="flex justify-end mb-6">
                <button data-modal-open="add-course"  class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 focus:ring
                    focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">Add Course</button>
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
            <table id="basic_tables" class="display stripe group table-responsive">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Subcription Type</th>
                        <th>Tags</th>
                        <th>Thumbnail</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>


    {{-- Create Course --}}
    <x-backend.modal id="add-course" class="w-full md:w-1/2 max-w-6xl justify-center" title="Create Course">
        <form id="course-form" enctype="multipart/form-data">
            @csrf @method('POST')
            <div class="grid grid-cols-1 gap-4 xl:grid-cols-12">

                {{--------------------- Title ---------------}}
                <div class="xl:col-span-12">
                    <x-backend.input type="text" name="name" label="Course Name" :required="true" />
                </div>

                {{--------------------- Tags  ---------------}}
                <div class="xl:col-span-12">
                    <label for="evaluation-tags" class="inline-block mb-2 text-base font-medium">Tags<span
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

                {{--------------------- Thumbnil ---------------}}
                <div class="xl:col-span-12">
                    <x-backend.dropify type="file" name="thumbnail" label="Thumbnail" :required="true" />
                </div>
                {{--------------------- Subscription Plan ---------------}}
                <div class="xl:col-span-12">
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

            <div class="flex justify-end gap-2 mt-4">
                <button type="submit"
                        class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600
                    focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">
                    Add Evaluation
                </button>
            </div>
        </form>
    </x-backend.modal>

@endsection

{{-- Push additional scripts if needed --}}
@push('scripts')
    <script src="{{ asset('backend/js/datatables/jquery-3.7.0.js') }}"></script>
    <script src="{{ asset('backend/js/datatables/data-tables.min.js') }}"></script>
    <script src="{{ asset('backend/js/datatables/data-tables.tailwindcss.min.js') }}"></script>
    <!--buttons dataTables-->
    <script src="{{ asset('backend/js/datatables/datatables.buttons.min.js') }}"></script>
    <script src="{{ asset('backend/js/datatables/jszip.min.js') }}"></script>
    <script src="{{ asset('backend/js/datatables/pdfmake.min.js') }}"></script>
    <script src="{{ asset('backend/js/datatables/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('backend/js/datatables/buttons.print.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/gh/habibmhamadi/multi-select-tag@3.1.0/dist/js/multi-select-tag.js"></script>
    <script type="text/javascript" src="https://jeremyfagis.github.io/dropify/dist/js/dropify.min.js"></script>


    {{-- <script src="{{ asset('backend/js/datatables/datatables.init.js') }}"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.0/dist/sweetalert2.all.min.js"
        integrity="sha256-BpyIV7Y3e2pnqy8TQGXxsmOiQ4jXNDTOTBGL2TEJeDY=" crossorigin="anonymous"></script>

    <script>
        $(document).ready(function() {
            let dTable = $('#basic_tables').DataTable({
                order: [],
                destroy: true,
                lengthMenu: [
                    [25, 50, 100, 200, 500, -1],
                    [25, 50, 100, 200, 500, "All"]
                ],
                processing: true,
                responsive: true,
                serverSide: true,
                language: {
                    processing: `<div class="text-center">
                <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="visually-hidden">Loading...</span>
              </div>
                </div>`
                },
                scroller: {
                    loadingIndicator: false
                },
                dom: "<'row justify-content-between table-topbar'<'col-md-2 col-sm-4 px-0'l><'col-md-2 col-sm-4 px-0'f>>tipr",
                ajax: {
                    url: "{{ route('course.index') }}",
                    type: "get",
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },

                    {
                        data: 'name',
                        name: 'name',
                        orderable: true,
                        searchable: true,
                        render: function(data, type, row) {
                            if (data.length > 20) {
                                return data.substring(0, 20) + '...';
                            } else {
                                return data;
                            }
                        }
                    },
                    {
                        data: 'subscription_plans_id',
                        name: 'subscription_plans_id',
                        orderable: true,
                        searchable: false,
                        render: function(data, type, row) {
                            if (data && data.length > 50) {
                                return data.substring(0, 50) + '...';
                            } else {
                                return data || '---';
                            }
                        }
                    },
                    {
                        data: 'tags_data',
                        name: 'tags_data',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return data || '---';
                        }
                    },
                    {
                        data: 'thumbnail',
                        name: 'thumbnail',
                        orderable: true,
                        searchable: true
                    },

                    {
                        data: 'status',
                        name: 'status',
                        orderable: true,
                        searchable: false,
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
            });
        });

        // Status Change Confirm Alert
        function showStatusChangeAlert(id) {
            event.preventDefault();

            Swal.fire({
                title: 'Are you sure?',
                text: 'You want to update the status?',
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
            }).then((result) => {
                if (result.isConfirmed) {
                    statusChange(id);
                }
            });
        }
        // Status Change
        function statusChange(id) {
            let url = '{{ route('course.status', ':id') }}';
            let csrfToken = '{{ csrf_token() }}';
            $.ajax({
                type: "POST",
                url: url.replace(':id', id),
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },

                success: function(resp) {
                    // Reload DataTable
                    $('#basic_tables').DataTable().ajax.reload();
                    if (resp.success === true) {
                        // show toast message
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
            let url = '{{ route('course.destroy', ':id') }}';
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

        // Tags
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

        //File Drofify
        $(document).ready(function() {
            $('.dropify').dropify();

        })

        // Course Create
        $(function () {
            $('#course-form').on('submit', function (e) {
                e.preventDefault();

                console.log('Submitting Form...'); // ✅ Check if this logs

                let formData    = new FormData(this);
                let url         = "{{ route('course.store') }}";

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

                        $('#basic_tables').DataTable().ajax.reload();

                        if (resp.success === true) {
                            flasher.success(resp.message);
                            clearModal('add-course');
                        } else if (resp.errors) {
                            flasher.error(resp.errors[0]);
                        } else {
                            flasher.error(resp.message);
                        }
                    },
                    error: function (xhr) {
                        console.log(xhr.responseText); // ✅ Log server error
                        handleXhrErrors(xhr, 'add-course');
                    }
                });
            });
        });
    </script>
@endpush
