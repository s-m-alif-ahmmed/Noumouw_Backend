@extends('backend.app')

@section('title', 'Categories')

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
        #category-table_wrapper .dataTables_length select {
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            padding: 4px 8px;
            outline: none;
        }

        #category-table_wrapper .dataTables_filter input {
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            padding: 8px 16px;
            outline: none;
            width: 250px;
            transition: all 0.3s;
        }

        #category-table_wrapper .dataTables_filter input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        #category-table {
            border-collapse: separate !important;
            border-spacing: 0 12px !important;
            width: 100% !important;
            border: none !important;
        }

        #category-table thead th {
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.05em;
            padding: 16px !important;
            border: none !important;
        }

        #category-table tbody tr {
            background: #ffffff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
            transition: all 0.3s;
        }

        #category-table tbody tr:hover {
            /* transform: scale(1.005); */
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            background: #fdfdfd;
        }

        #category-table tbody td {
            padding: 16px !important;
            border: none !important;
            vertical-align: middle;
        }

        #category-table tbody tr td:first-child {
            border-radius: 12px 0 0 12px;
        }

        #category-table tbody tr td:last-child {
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
    </style>
@endpush

@section('content')
    <div class="premium-card overflow-hidden mb-8">
        <div class="p-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
                <div>
                    <h2 class="text-xl font-bold text-slate-800">Category Management</h2>
                    <p class="text-slate-500 text-sm">Organize and manage your platform's course categories</p>
                </div>
                <div class="flex items-center gap-3">
                    <button onclick="openCreateModal()"
                        class="inline-flex items-center gap-2 bg-custom-500 text-white px-6 py-2.5 rounded-xl hover:bg-custom-600 transition-all shadow-lg shadow-custom-500/30 font-medium whitespace-nowrap">
                        <span class="text-xl leading-none">+</span>
                        Add New Category
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table id="basic_tables" class="w-full">
                    <thead>
                    <tr>
                        <th class="w-16">#</th>
                        <th>Category Info</th>
                        <th>Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

        </div>
    </div>

    {{-- Category Modal --}}
    <x-backend.modal id="category-modal" title="Category Details">
        <form id="category-form" enctype="multipart/form-data">
            @csrf
            <input type="hidden" id="category_id" name="category_id">
            <div class="space-y-6">
                <div>
                    <x-backend.input type="text" name="name" id="name" label="Category Name" :required="true" />
                </div>
                <div>
                    <x-backend.dropify name="image" id="image" label="Category Icon/Image" />
                </div>
            </div>
            <div class="mt-8 flex justify-end gap-3">
                <button type="button" onclick="closeModal('category-modal')"
                    class="px-6 py-2.5 text-slate-600 hover:bg-slate-50 rounded-xl transition-colors font-medium">Cancel</button>
                <button type="submit"
                    class="px-8 py-2.5 bg-custom-500 text-white rounded-xl hover:bg-custom-600 shadow-lg shadow-custom-500/30 transition-all font-medium">Save
                    Category</button>
            </div>
        </form>
    </x-backend.modal>
@endsection

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
        let table;
        $(document).ready(function() {
            $('.dropify').dropify({
                tpl: {
                    message: '<div class="dropify-message"><span class="file-icon"></span> <p style="font-size: 18px;">Upload icon here</p></div>'
                }
            });

            table = $('#basic_tables').DataTable({
                order: [],
                ordering: false,
                destroy: true,
                autoWidth: false,
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                processing: true,
                serverSide: true,
                dom: '<"flex flex-col md:flex-row justify-between items-center mb-4 gap-4"l f>rt<"flex flex-col md:flex-row justify-between items-center mt-6 gap-4"i p>',
                ajax: "{{ route('category.index') }}",
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
                            let imgPath = row.image ? `/${row.image}` :
                                '/uploads/course/default.png';
                            return `<div class="flex items-center gap-4">
                                <img src="${imgPath}" class="size-12 rounded-lg object-cover shadow-sm border border-slate-100">
                                <div>
                                    <div class="font-bold text-slate-800">${data}</div>
                                    <div class="text-xs text-slate-400">Slug: ${row.slug}</div>
                                </div>
                            </div>`;
                        }
                    },
                    // {
                    //     data: 'slug',
                    //     name: 'slug',
                    //     render: function(data) {
                    //         return `<span class="px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-xs font-semibold">${data}</span>`;
                    //     }
                    // },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        className: 'text-center'
                    }
                ]
            });

            $('#category-form').on('submit', function(e) {
                e.preventDefault();
                let id = $('#category_id').val();
                let url = id ? "{{ url('category') }}/" + id : "{{ route('category.store') }}";

                let formData = new FormData(this);
                if (id) formData.append('_method', 'PUT');

                $.ajax({
                    url: url,
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    success: function(resp) {
                        if (resp.success) {
                            flasher.success(resp.message);
                            closeModal('category-modal');
                            table.ajax.reload();
                        } else {
                            flasher.error(resp.message);
                        }
                    },
                    error: function(xhr) {
                        handleXhrErrors(xhr, 'category-modal');
                    }
                });
            });

            // Explicitly bind the custom closeModal function to the close buttons and overlays
            $('[data-modal-close]').on('click', function() {
                closeModal($(this).data('modal-close'));
            });

            $('[id$="-overlay"]').on('click', function() {
                closeModal($(this).attr('id').replace('-overlay', ''));
            });
        });

        function openCreateModal() {
            $('#category_id').val('');
            $('#category-form')[0].reset();
            let drEvent = $('.dropify').dropify();
            drEvent = drEvent.data('dropify');
            drEvent.resetPreview();
            drEvent.clearElement();
            openModal('category-modal');
        }

        function editCategory(id) {
            $.get("{{ url('category') }}/" + id + "/edit", function(data) {
                $('#category_id').val(data.id);
                $('#name').val(data.name);

                let drEvent = $('.dropify').dropify();
                drEvent = drEvent.data('dropify');
                drEvent.resetPreview();
                drEvent.clearElement();
                if (data.image) {
                    drEvent.settings.defaultFile = `/${data.image}`;
                    drEvent.destroy();
                    drEvent.init();
                }

                openModal('category-modal');
            });
        }

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
                    $.post("{{ url('categories/status') }}/" + id, {
                        _token: "{{ csrf_token() }}"
                    }, function(resp) {
                        if (resp.success) {
                            flasher.success(resp.message);
                            table.ajax.reload();
                        }
                    });
                }
            });
        }

        function showDeleteConfirm(id) {
            event.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('category') }}/" + id,
                        type: 'DELETE',
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(resp) {
                            if (resp.success) {
                                flasher.success(resp.message);
                                table.ajax.reload();
                            }
                        }
                    });
                }
            })
        }

        function handleXhrErrors(xhr, modalId) {
            if (xhr.status === 422) {
                let errors = xhr.responseJSON.errors;
                Object.keys(errors).forEach(key => flasher.error(errors[key][0]));
            } else {
                flasher.error('Something went wrong. Please try again.');
            }
        }

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
