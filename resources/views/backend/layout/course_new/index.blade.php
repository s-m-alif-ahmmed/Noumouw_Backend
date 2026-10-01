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
            border-radius: 16px;
            border: 2px dashed #e2e8f0;
        }

        .premium-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
            border: 1px solid #f1f5f9;
        }

        /* Modern Datatable Styling */

        #basic_tables_wrapper .dataTables_filter input {
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            padding: 8px 16px;
            outline: none;
            width: 250px;
            transition: all 0.3s;
        }

        #basic_tables_wrapper .dataTables_filter input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        #basic_tables {
            border-collapse: separate !important;
            border-spacing: 0 12px !important;
            width: 100% !important;
            border: none !important;
        }

        #basic_tables thead th {
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.05em;
            padding: 16px !important;
            border: none !important;
        }

        #basic_tables tbody tr {
            background: #ffffff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
            transition: all 0.3s;
        }

        #basic_tables tbody tr:hover {
            /* transform: scale(1.005); */
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            background: #fdfdfd;
        }

        #basic_tables tbody td {
            padding: 16px !important;
            border: none !important;
            vertical-align: middle;
        }

        #basic_tables tbody tr td:first-child {
            border-radius: 12px 0 0 12px;
        }

        #basic_tables tbody tr td:last-child {
            border-radius: 0 12px 12px 0;
        }

        /* Pagination Styling */
        .dataTables_paginate .paginate_button {
            border-radius: 8px !important;
            border: 1px solid #e2e8f0 !important;
            margin: 0 2px !important;
            transition: all 0.3s !important;
        }

        .dataTables_paginate .paginate_button.current {
            background: #3b82f6 !important;
            color: white !important;
            border-color: #3b82f6 !important;
        }

        .dataTables_paginate .paginate_button:hover:not(.current) {
            background: #f1f5f9 !important;
        }

        #basic_tables_wrapper .dataTables_scrollBody thead tr {
            height: 0 !important;
        }

        #basic_tables_wrapper .dataTables_scrollBody thead th,
        #basic_tables_wrapper .dataTables_scrollBody thead td {
            padding: 0 !important;
            border: none !important;
            height: 0 !important;
            line-height: 0 !important;
            font-size: 0 !important;
            overflow: hidden !important;
        }
    </style>
@endpush


{{-- Main content of the News Dashboard page --}}
@section('content')
    <div class="premium-card overflow-hidden mb-8">
        <div class="p-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
                <div>
                    <h2 class="text-xl font-bold text-slate-800">Course Directory</h2>
                    <p class="text-slate-500 text-sm">Manage and organize your platform's learning content</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('course.create_new') }}"
                        class="inline-flex items-center gap-2 bg-custom-500 text-white px-6 py-2.5 rounded-xl hover:bg-custom-600 transition-all shadow-lg shadow-custom-500/30 font-medium whitespace-nowrap">
                        <span class="text-xl leading-none">+</span>
                        Add New Course
                    </a>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table id="basic_tables" class="w-full">
                    <thead>
                        <tr>
                            <th class="w-16">#</th>
                            <th>Course Info</th>
                            <th>Category</th>
                            <th>Subscription</th>
                            <th>Tags</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>


    {{-- Create Course --}}
    <x-backend.modal id="add-course" class="w-full md:w-1/2 max-w-6xl justify-center" title="Create Course">
        <form id="course-form" enctype="multipart/form-data">
            @csrf @method('POST')
            <div class="grid grid-cols-1 gap-4 xl:grid-cols-12">

                {{-- ------------------- Title ------------- --}}
                <div class="xl:col-span-12">
                    <x-backend.input type="text" name="name" label="Course Name" :required="true" />
                </div>

                {{-- ------------------- Tags  ------------- --}}
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

                {{-- ------------------- Thumbnil ------------- --}}
                <div class="xl:col-span-12">
                    <x-backend.dropify type="file" name="thumbnail" label="Thumbnail" :required="true" />
                </div>
                {{-- ------------------- Subscription Plan ------------- --}}
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
                ordering: false,
                destroy: true,
                scrollX: true,
                scrollCollapse: true,
                autoWidth: false,
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                processing: true,
                serverSide: true,
                dom: '<"flex flex-col md:flex-row justify-between items-center mb-4 gap-4"l f>rt<"flex flex-col md:flex-row justify-between items-center mt-6 gap-4"i p>',
                ajax: {
                    url: "{{ route('course.index_new') }}",
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
                        render: function(data, type, row) {
                            return `<div class="flex items-center gap-4">
                                <img src="${row.thumbnail_url || '/uploads/course/default.png'}" class="size-12 rounded-lg object-cover shadow-sm border border-slate-100">
                                <div>
                                    <div class="font-bold text-slate-800">${data}</div>
                                    <div class="text-xs text-slate-400">Created: ${row.created_at || 'N/A'}</div>
                                </div>
                            </div>`;
                        }
                    },
                    {
                        data: 'category_name',
                        name: 'category_name',
                        render: function(data) {
                            return `<span class="px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-xs font-semibold uppercase tracking-wider">${data || 'Uncategorized'}</span>`;
                        }
                    },
                    {
                        data: 'subscription_plans_id',
                        name: 'subscription_plans_id',
                        render: function(data) {
                            let color = data.toLowerCase() === 'premium' ?
                                'bg-amber-50 text-amber-600 border-amber-100' :
                                'bg-blue-50 text-blue-600 border-blue-100';
                            return `<span class="px-3 py-1 rounded-lg ${color} border text-xs font-bold">${data}</span>`;
                        }
                    },
                    {
                        data: 'tags_data',
                        name: 'tags_data',
                        render: function(data) {
                            return `<div class="flex -space-x-2 overflow-hidden">${data || '---'}</div>`;
                        }
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
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
            $('.dropify').dropify({
                tpl: {
                    message: '<div class="dropify-message"><span class="file-icon"></span> <p style="font-size: 24px;">Upload file here</p></div>'
                }
            });

        })

        // Course Create
        $(function() {
            $('#course-form').on('submit', function(e) {
                e.preventDefault();

                console.log('Submitting Form...'); // ✅ Check if this logs

                let formData = new FormData(this);
                let url = "{{ route('course.store') }}";

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
                    error: function(xhr) {
                        console.log(xhr.responseText); // ✅ Log server error
                        handleXhrErrors(xhr, 'add-course');
                    }
                });
            });
        });
    </script>
@endpush
