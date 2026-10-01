@extends('backend.app')

{{-- Title for the News Dashboard --}}
@section('title', 'Tag')
@section('title_url')
    <a href="{{ route('tag.index') }}">Tag</a>
@endsection
@section('tabName')
    <a href="{{ route('dashboard') }}">Home</a>
@endsection

{{-- Push additional styles if needed --}}
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.0/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="{{ asset('vendor/flasher/flasher.min.css') }}" rel="stylesheet">
    <style>
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
                    <h2 class="text-xl font-bold text-slate-800 tracking-tight">Tag Directory</h2>
                    <p class="text-slate-500 text-sm">Organize and manage content tags for your platform</p>
                </div>
                <div class="flex items-center gap-3">
                    <button type="button" onclick="openModal('create-tag')"
                        class="inline-flex items-center gap-2 bg-custom-500 text-white px-6 py-2.5 rounded-xl hover:bg-custom-600 transition-all shadow-lg shadow-custom-500/30 font-medium whitespace-nowrap">
                        <span class="text-xl leading-none">+</span>
                        Add New Tag
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table id="basic_tables" class="w-full">
                    <thead>
                        <tr>
                            <th class="w-16">#</th>
                            <th>Tag Name</th>
                            <th>Created Date</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Create modal --}}
    <x-backend.modal id="create-tag" title="Add Tag">
        <form id="create-form">
            @csrf
            <div class="grid grid-cols-1 gap-4 xl:grid-cols-12">
                {{-- ------------------- Name Input Field ------------- --}}
                <div class="xl:col-span-12">
                    <x-backend.input-ajax type="text" name="title" label="Content" :required="true"
                        placeholder="Enter content here" />
                </div>
            </div>
            <div class="flex justify-end gap-2 mt-4">
                <button type="submit"
                    class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600
                    focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">Add
                    Tag</button>
            </div>
        </form>
    </x-backend.modal>

    {{-- edit modal --}}
    <x-backend.modal id="edit-tag" title="Edit Message">
        <form id="edit-form">
            @csrf
            <input type="hidden" name="id">
            <div class="grid grid-cols-1 gap-4 xl:grid-cols-12">
                <div class="xl:col-span-12">
                    <x-backend.input-ajax type="text" name="title" id="edit-description" label="Content"
                        :required="true" placeholder="Enter content here" />
                </div>
            </div>
            <div class="flex justify-end gap-2 mt-4">
                <button type="submit"
                    class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600
                     focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">Update
                    Tag</button>
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
            let url = "{{ route('tag.index') }}";
            let dTable = $('#basic_tables').DataTable({
                order: [],
                destroy: true,
                ordering: false,
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
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'title',
                        name: 'title',
                        render: function(data) {
                            return `<div class="flex items-center gap-3">
                                <span class="font-bold text-slate-800">${data}</span>
                            </div>`;
                        }
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        render: function(data) {
                            return `<span class="text-slate-500 text-sm">${data || 'N/A'}</span>`;
                        }
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                ],
                initComplete: function() {
                    initOpenModal()
                }
            });

            // Explicitly bind the custom closeModal function to the close buttons and overlays
            $('[data-modal-close]').on('click', function() {
                closeModal($(this).data('modal-close'));
            });

            $('[id$="-overlay"]').on('click', function() {
                closeModal($(this).attr('id').replace('-overlay', ''));
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
            let url = '{{ route('tag.destroy', ':id') }}';
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
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('#create-form').on('submit', function(e) {
                NProgress.start();
                e.preventDefault();
                let formData = $(this).serialize();
                let url = "{{ route('tag.store') }}";
                $.ajax({
                    url,
                    method: 'POST',
                    data: formData,
                    success: function(resp) {
                        NProgress.done();
                        // Reload DataTable
                        $('#basic_tables').DataTable().ajax.reload();
                        if (resp.success === true) {
                            // show toast message
                            flasher.success(resp.message);
                            closeModal('create-tag');
                        } else if (resp.errors) {
                            flasher.error(resp.errors[0]);
                        } else {
                            flasher.error(resp.message);
                        }
                    },
                    error: function(xhr) {
                        NProgress.done();
                        handleXhrErrors(xhr, 'create-tag')
                    }
                });
            });

            //open edit modal
            //edit Message
            $('body').on('click', '.edit', function() {
                NProgress.start();
                var id = $(this).data('id');
                var url = "{{ route('tag.edit', ':id') }}".replace(':id', id);
                $.ajax({
                    url: url,
                    type: 'get',
                    success: function(data) {
                        NProgress.done();
                        if (data.success) {
                            openEditModalById('edit-tag', data.data)
                        } else {
                            flasher.error('Somethings went wrong. Try again later.')
                        }
                    },
                    error: function(errors) {
                        NProgress.done();
                        flasher.error(error.responseJSON.message);
                    }
                })
            });

            $('#edit-form').on('submit', function(e) {
                NProgress.start();
                e.preventDefault();
                let formData = $(this).serialize();
                let url = "{{ route('tag.update', ':id') }}".replace(':id', $(this).find(
                    "input[name='id']").val());
                $.ajax({
                    url,
                    method: 'PATCH',
                    data: formData,
                    success: function(resp) {
                        NProgress.done();
                        // Reload DataTable
                        $('#basic_tables').DataTable().ajax.reload();
                        if (resp.success === true) {
                            // show toast message
                            flasher.success(resp.message);
                            closeModal('edit-tag');
                        } else if (resp.errors) {
                            flasher.error(resp.errors[0]);
                        } else {
                            flasher.error(resp.message);
                        }
                    },
                    error: function(xhr) {
                        NProgress.done();
                        handleXhrErrors(xhr, 'edit-tag')
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
@endpush
