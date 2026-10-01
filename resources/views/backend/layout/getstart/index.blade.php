@extends('backend.app')

{{-- Title for the News Dashboard --}}
@section('title', 'Get Start')
@section('title_url')
    <a href="{{ route('get-start.index') }}">Get Start</a>
@endsection
@section('tabName')
    <a href="{{ route('dashboard') }}">Home</a>
@endsection

{{-- Push additional styles if needed --}}
@push('styles')
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

        .premium-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
            border: 1px solid #f1f5f9;
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

        .dataTables_paginate {
            margin: 10px !important;
        }

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
                    <h2 class="text-xl font-bold text-slate-800">Get Start Messages</h2>
                    <p class="text-slate-500 text-sm">Manage the welcome messages shown to new users.</p>
                </div>
                <div class="flex items-center gap-3">
                    <button data-modal-open="create-get-start"
                        class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">
                        Add Message
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table id="basic_tables" class="w-full">
                    <thead>
                        <tr>
                            <th class="w-16">#</th>
                            <th>Message</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Create modal --}}
    <x-backend.modal id="create-get-start" title="Add Message">
        <form id="create-form">
            @csrf
            <div class="grid grid-cols-1 gap-4 xl:grid-cols-12">
                {{-- ------------------- Name Input Field ------------- --}}
                <div class="xl:col-span-12">
                    <x-backend.text-area :ajax="true" name="description" label="Content" :required="true"
                        placeholder="Enter content here"></x-backend.text-area>
                </div>
            </div>
            <div class="flex justify-end gap-2 mt-4">
                <button type="submit"
                    class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600
                    focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">Add
                    Message</button>
            </div>
        </form>
    </x-backend.modal>

    {{-- edit modal --}}
    <x-backend.modal id="edit-get-start" title="Edit Message">
        <form id="edit-form">
            @csrf @method('PATCH')
            <input type="hidden" name="id">
            <div class="grid grid-cols-1 gap-4 xl:grid-cols-12">
                <div class="xl:col-span-12">
                    <x-backend.text-area :ajax="true" name="description" id="edit-description" label="Content"
                        :required="true" placeholder="Enter content here"></x-backend.text-area>
                </div>
            </div>
            <div class="flex justify-end gap-2 mt-4">
                <button type="submit"
                    class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600
                     focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">Update Message</button>
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
            let url = "{{ route('get-start.index') }}";
            let dTable = $('#basic_tables').DataTable({
                order: [],
                ordering: false,
                destroy: true,
                      scrollX: true,
                scrollCollapse: true,
                autoWidth: false,
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
                        data: 'description',
                        name: 'description',
                        orderable: true,
                        searchable: true,
                        render: function(data, type, row) {
                            if (data.length > 200) {
                                return data.substring(0, 200) + '...';
                            } else {
                                return data;
                            }
                        }
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
                initComplete: function() {
                    initOpenModal()
                }

            });
        });


        // Status Change
        function showStatusChangeAlert(id) {
            let url = '{{ route('get-start.status', ':id') }}';
            $.ajax({
                url: url.replace(':id', id),
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                },
                success: function(response) {
                    // Reload DataTable
                    $('#basic_tables').DataTable().ajax.reload();
                    if (response.success) {
                        flasher.success(response.message);
                    } else {
                        flasher.error(response.message);
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
            let url = '{{ route('get-start.destroy', ':id') }}';
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
        function hideGetStartModalBackdrops() {
            document.getElementById('backDropDiv')?.classList.add('hidden');
            document.querySelectorAll('.backdrop-overlay, .modal-backdrop').forEach(function(backdrop) {
                backdrop.classList.add('hidden');
            });
            document.body.classList.remove('overflow-hidden', 'modal-open');
        }

        function closeGetStartModal(modalId) {
            document.getElementById(modalId)?.classList.add('hidden');
            document.getElementById(modalId)?.classList.remove('flex', 'show');
            document.getElementById(modalId + '-overlay')?.classList.add('hidden');
            hideGetStartModalBackdrops();
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('[data-modal-close="create-get-start"], [data-modal-close="edit-get-start"]')
                .forEach(function(button) {
                    button.addEventListener('click', function() {
                        closeGetStartModal(this.getAttribute('data-modal-close'));
                        setTimeout(hideGetStartModalBackdrops, 0);
                        setTimeout(hideGetStartModalBackdrops, 250);
                    });
                });

            $('#create-form').on('submit', function(e) {
                NProgress.start();
                e.preventDefault();
                let formData = $(this).serialize();
                let url = "{{ route('get-start.store') }}";
                $.ajax({
                    url,
                    method: 'POST',
                    data: formData,
                    success: function(resp) {
                        // Reload DataTable
                        $('#basic_tables').DataTable().ajax.reload();
                        if (resp.success === true) {
                            // show toast message
                            NProgress.done();
                            flasher.success(resp.message);
                            closeGetStartModal('create-get-start')
                        } else if (resp.errors) {
                            flasher.error(resp.errors[0]);
                            NProgress.done();
                            closeGetStartModal('create-get-start')
                        } else {
                            flasher.error(resp.message);
                            NProgress.done();
                            closeGetStartModal('create-get-start')
                        }
                    },
                    error: function(xhr) {
                        handleXhrErrors(xhr, 'create-get-start')
                        NProgress.done();
                        closeGetStartModal('create-get-start')
                    }
                });
            });

            //open edit modal
            //edit Message
            $('body').on('click', '.edit', function() {
                var id = $(this).data('id');
                var url = "{{ route('get-start.edit', ':id') }}".replace(':id', id);
                $.ajax({
                    url: url,
                    type: 'get',
                    success: function(data) {
                        if (data.success) {
                            openEditModalById('edit-get-start', data.data)
                        } else {
                            flasher.error('Somethings went wrong. Try again later.')
                        }
                    },
                    error: function(errors) {
                        flasher.error(error.responseJSON.message);
                    }
                })
            });

            $('#edit-form').on('submit', function(e) {
                e.preventDefault();
                let formData = $(this).serialize();
                let url = "{{ route('get-start.update', ':id') }}".replace(':id', $(this).find(
                    "input[name='id']").val());
                $.ajax({
                    url,
                    method: 'POST',
                    data: formData,
                    success: function(resp) {
                        // Reload DataTable
                        $('#basic_tables').DataTable().ajax.reload();
                        if (resp.success === true) {
                            // show toast message
                            flasher.success(resp.message);
                            closeGetStartModal('edit-get-start')
                        } else if (resp.errors) {
                            flasher.error(resp.errors[0]);
                        } else {
                            flasher.error(resp.message);
                        }
                    },
                    error: function(xhr) {
                        handleXhrErrors(xhr, 'edit-get-start')
                    }
                });
            });
        });
    </script>
@endpush
