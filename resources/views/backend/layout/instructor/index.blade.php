@extends('backend.app')

{{-- Title for the News Dashboard --}}
@section('title', 'Instructor')
@section('title_url')
    <a href="{{ route('instructor.index') }}">Instructor</a>
@endsection
@section('tabName')
    <a href="{{ route('dashboard') }}">Home</a>
@endsection

{{-- Push additional styles if needed --}}
@push('styles')
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.0/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="{{ asset('vendor/flasher/flasher.min.css') }}" rel="stylesheet">
    <style>
        .dropify-wrapper {
            height: 200px;
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
        #basic_tables_wrapper .dataTables_length select {
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            padding: 4px 8px;
            outline: none;
        }

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
                    <h2 class="text-xl font-bold text-slate-800 tracking-tight">Instructor Directory</h2>
                    <p class="text-slate-500 text-sm">Manage and organize your platform's expert teaching staff</p>
                </div>
                <div class="flex items-center gap-3">
                    <button type="button" onclick="openModal('create-instructor')"
                        class="inline-flex items-center gap-2 bg-custom-500 text-white px-6 py-2.5 rounded-xl hover:bg-custom-600 transition-all shadow-lg shadow-custom-500/30 font-medium whitespace-nowrap">
                        <span class="text-xl leading-none">+</span>
                        Add New Instructor
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table id="basic_tables" class="w-full">
                    <thead>
                        <tr>
                            <th class="w-16">#</th>
                            <th>Instructor Profile</th>
                            <th>Designation</th>
                            <th>Contact Info</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Create modal --}}
    <x-backend.modal id="create-instructor" class="w-full md:w-1/2 max-w-4xl" title="Add Instructor">
        <form id="create-form" enctype="multipart/form-data">
            @csrf @method('POST')
            <div class="grid grid-cols-1 gap-4 xl:grid-cols-12">

                {{-- ------------------- Name Input Field ------------- --}}
                <div class="xl:col-span-6">
                    <x-backend.input-ajax type="text" name="name" label="Name" :required="true"
                        placeholder="Enter Name here" />
                </div>

                {{-- ------------------- Email Input Field ------------- --}}
                <div class="xl:col-span-6">
                    <x-backend.input-ajax type="email" name="email" label="Email" :required="true"
                        placeholder="Enter Email here" />
                </div>

                {{-- ------------------- Phone Input Field ------------- --}}
                <div class="xl:col-span-6">
                    <x-backend.input-ajax type="text" name="phone" label="Phone" id="phone" :required="true"
                        placeholder="Enter Phone here" />
                </div>

                {{-- ------------------- Country Input Field ------------- --}}
                <div class="xl:col-span-6">
                    <x-backend.input-ajax type="text" name="country" label="Country" :required="true"
                        placeholder="Enter Country here" />
                </div>

                {{-- ------------------- Address Input Field ------------- --}}
                <div class="xl:col-span-6">
                    <x-backend.input-ajax type="text" name="address" label="Address" :required="true"
                        placeholder="Enter Address here" />
                </div>

                {{-- ------------------- Role Input Field ------------- --}}
                <div class="xl:col-span-6">
                    <x-backend.input-ajax type="text" name="role" label="Role" :required="true"
                        placeholder="Enter Role here" />
                </div>

                {{-- ------------------- Designation Input Field ------------- --}}
                <div class="xl:col-span-6">
                    <x-backend.input-ajax type="text" name="designation" label="Designation" :required="true"
                        placeholder="Enter Designation here" />
                </div>

                {{-- ------------------- Services Input Field ------------- --}}
                <div class="xl:col-span-6">
                    <x-backend.input-ajax type="text" name="services" label="Services" :required="true"
                        placeholder="Enter Services here" />
                </div>

                {{-- ------------------- Bio Text Area Field ------------- --}}
                <div class="xl:col-span-12">
                    <x-backend.text-area :ajax="true" name="bio" label="Bio" :required="true"
                        placeholder="Enter Bio here"></x-backend.text-area>
                </div>

                {{-- ------------------- Avatar File Upload Field ------------- --}}
                <div class="xl:col-span-12">
                    <x-backend.dropify type="file" :ajax="true" name="avatar" label="Avatar" :required="true"
                        accept="image/*" />
                </div>
            </div>

            <div class="flex justify-end gap-2 mt-4">
                <button type="submit"
                    class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600
                    focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">
                    Add Instructor
                </button>
            </div>
        </form>

    </x-backend.modal>

    {{-- edit modal --}}
    <x-backend.modal id="edit-instructor" title="Edit Instructor">
        <form id="edit-form" enctype="multipart/form-data">

            @csrf
            @method('POST')
            <input type="hidden" name="id">
            {{-- <input type="hidden" name="_token" value="{{ csrf_token() }}"> --}}
            <div class="grid grid-cols-1 gap-4 xl:grid-cols-12">

                {{-- ------------------- Name Input Field ------------- --}}
                <div class="xl:col-span-6">
                    <x-backend.input-ajax type="text" name="name" label="Name" :required="true"
                        placeholder="Enter name here" />
                </div>

                {{-- ------------------- Email Input Field ------------- --}}
                <div class="xl:col-span-6">
                    <x-backend.input-ajax type="email" name="email" label="Email" :required="true"
                        placeholder="Enter email here" />
                </div>

                {{-- ------------------- Phone Input Field ------------- --}}
                <div class="xl:col-span-6">
                    <x-backend.input-ajax type="text" id="edit-phone" name="phone" label="Phone"
                        :required="true" />
                </div>

                {{-- ------------------- Country Input Field ------------- --}}
                <div class="xl:col-span-6">
                    <x-backend.input-ajax type="text" name="country" label="Country" :required="true"
                        placeholder="Enter country here" />
                </div>

                {{-- ------------------- Address Input Field ------------- --}}
                <div class="xl:col-span-6">
                    <x-backend.input-ajax type="text" name="address" label="Address" :required="true"
                        placeholder="Enter address here" />
                </div>

                {{-- ------------------- Role Input Field ------------- --}}
                <div class="xl:col-span-6">
                    <x-backend.input-ajax type="text" name="role" label="Role" :required="true"
                        placeholder="Enter role here" />
                </div>

                {{-- ------------------- Designation Input Field ------------- --}}
                <div class="xl:col-span-6">
                    <x-backend.input-ajax type="text" name="designation" label="Designation" :required="true"
                        placeholder="Enter designation here" />
                </div>

                {{-- ------------------- Services Input Field ------------- --}}
                <div class="xl:col-span-6">
                    <x-backend.input-ajax type="text" name="services" label="Services" :required="true"
                        placeholder="Enter services here" />
                </div>

                {{-- ------------------- Bio Input Field ------------- --}}
                <div class="xl:col-span-12">
                    <x-backend.text-area input-ajax :ajax="true" name="bio" label="Bio" :required="true"
                        placeholder="Enter Bio here"> </x-backend.text-area>
                </div>

                {{-- ------------------- Avatar Preview ------------- --}}
                <div class="xl:col-span-12 flex flex-col items-center gap-2">
                    <p class="text-xs font-medium text-slate-500 self-start">Current Avatar</p>
                    <img id="edit-avatar-preview" src="/backend/images/user.png" alt="Current Avatar"
                        class="w-50 h-50 object-cover border-4 border-slate-100 shadow-md" />
                </div>

                {{-- ------------------- Avatar Input Feild ------------- --}}
                <div class="xl:col-span-12">
                    <x-backend.dropify type="file" :ajax="true" name="avatar" label="Avatar"
                        :required="false" />
                </div>

            </div>
            <div class="flex justify-end gap-2 mt-4">
                <button type="submit"
                    class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600
                     focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">Update
                    Instructor</button>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    {{-- <script src="{{ asset('backend/js/datatables/datatables.init.js') }}"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.0/dist/sweetalert2.all.min.js"
        integrity="sha256-BpyIV7Y3e2pnqy8TQGXxsmOiQ4jXNDTOTBGL2TEJeDY=" crossorigin="anonymous"></script>
    <script src="{{ asset('vendor/flasher/flasher.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            let url = "{{ route('instructor.index') }}";
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
                    url: url,
                    type: "get",
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                    },
                    {
                        data: 'name',
                        name: 'name',
                        render: function(data, type, row) {
                            return `<div class="flex items-center gap-4">
                                <img src="${row.avatar_url || '/backend/images/user.png'}" class="size-12 rounded-full object-cover shadow-sm border border-slate-100">
                                <div>
                                    <div class="font-bold text-slate-800">${data}</div>
                                    <div class="text-xs text-slate-400">${row.email}</div>
                                </div>
                            </div>`;
                        }
                    },
                    {
                        data: 'designation',
                        name: 'designation',
                        render: function(data) {
                            return `<span class="px-3 py-1 rounded-lg bg-blue-50 text-blue-600 border border-blue-100 text-xs font-bold">${data || 'Instructor'}</span>`;
                        }
                    },
                    {
                        data: 'phone',
                        name: 'phone',
                        render: function(data, type, row) {
                            return `<div class="text-sm font-medium text-slate-700">${data}</div>
                                    <div class="text-[10px] text-slate-400 uppercase tracking-tighter">${row.country || 'N/A'}</div>`;
                        }
                    },
                    {
                        data: 'status',
                        name: 'status',
                        render: function(data) {
                            let color = data === 'active' ? 'bg-green-100 text-green-700' :
                                'bg-slate-100 text-slate-500';
                            return `<span class="px-2 py-1 rounded-md ${color} text-[10px] font-bold uppercase">${data || 'Active'}</span>`;
                        }
                    },
                    {
                        data: 'action',
                        name: 'action',
                        className: 'text-center'
                    },
                ],
                initComplete: function() {
                    initOpenModal()
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
            let url = '{{ route('instructor.destroy', ':id') }}';
            $.ajax({
                type: "DELETE",
                url: url.replace(':id', id),
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
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
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('#create-form').on('submit', function(e) {
                clearError('create-instructor')
                e.preventDefault();
                const formElement = document.querySelector("#create-form");
                let formData = new FormData(formElement);
                let url = "{{ route('instructor.store') }}";
                $.ajax({
                    url,
                    method: 'POST',
                    cache: false,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    data: formData,
                    success: function(resp) {
                        // Reload DataTable
                        $('#basic_tables').DataTable().ajax.reload();
                        if (resp.success === true) {
                            // show toast message
                            flasher.success(resp.message);

                            // Explicitly clear the form and reset the Dropify preview
                            $('#create-form')[0].reset();
                            $('#create-form .dropify-clear').click();

                            closeModal('create-instructor');
                        } else if (resp.errors) {
                            flasher.error(resp.errors[0]);
                        } else {
                            flasher.error(resp.message);
                        }
                    },
                    error: function(xhr) {
                        handleXhrErrors(xhr, 'create-instructor')
                    }
                });
            });


            //edit Message
            $('body').on('click', '.edit', function() {
                var id = $(this).data('id');
                var url = "{{ route('instructor.edit', ':id') }}".replace(':id', id);
                $.ajax({
                    url: url,
                    type: 'get',
                    success: function(data) {
                        if (data.success) {
                            openEditModalById('edit-instructor', data.data)
                            // Populate avatar preview
                            let avatarUrl = data.data.avatar ?
                                window.location.origin + '/' + data.data.avatar :
                                '/backend/images/user.png';
                            document.querySelector('#edit-avatar-preview').src = avatarUrl;
                        } else {
                            flasher.error('Somethings went wrong. Try again later.')
                        }
                    },
                    error: function(errors) {
                        flasher.error(error.responseJSON.message);
                    }
                })
            });

            // Explicitly bind the custom closeModal function to the close buttons and overlays
            $('[data-modal-close]').on('click', function() {
                closeModal($(this).data('modal-close'));
            });

            $('[id$="-overlay"]').on('click', function() {
                closeModal($(this).attr('id').replace('-overlay', ''));
            });

            $('#edit-form').on('submit', function(e) {
                clearError('edit-instructor')
                e.preventDefault();
                let formData = new FormData(this);
                formData.append('_token', '{{ csrf_token() }}');
                let url = "{{ route('instructor.update', ':id') }}".replace(':id', $(this).find(
                    "input[name='id']").val());
                $.ajax({
                    url,
                    method: 'POST',
                    cache: false,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    data: formData,
                    success: function(resp) {
                        // Reload DataTable
                        $('#basic_tables').DataTable().ajax.reload();
                        if (resp.success === true) {
                            // show toast message
                            flasher.success(resp.message);
                            clearModal('edit-instructor')
                        } else if (resp.errors) {
                            flasher.error(resp.errors[0]);
                        } else {
                            flasher.error(resp.message);
                        }
                    },
                    error: function(xhr) {
                        handleXhrErrors(xhr, 'edit-instructor')
                    }
                });
            });
        });

        function openModal(modalId) {
            $(`#${modalId}`).removeClass('hidden');
            $(`#${modalId}-overlay`).removeClass('hidden');
            $('body').addClass('overflow-hidden');
        }

        function closeModal(modalId) {
            $(`#${modalId}`).addClass('hidden').removeClass('flex');
            $(`#${modalId}-overlay`).addClass('hidden');
            $('body').removeClass('overflow-hidden');
        }
    </script>

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
@endpush
