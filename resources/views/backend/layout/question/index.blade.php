@extends('backend.app')

{{-- Title for the Dashboard --}}
@section('title', 'Questions')
@section('title_url')
    <a href="{{ route('question.index') }}">Questions</a>
@endsection
@section('tabName')
    <a href="{{ route('dashboard') }}">Home</a>
@endsection

{{-- Push additional styles --}}
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.0/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="{{ asset('vendor/flasher/flasher.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('backend/js/datatables/jquery-3.7.0.js') }}">

    <style>
        .premium-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
            border: 1px solid #f1f5f9;
        }

        /* Modern Inputs & Selects */
        .modern-input {
            width: 100%;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            padding: 12px 16px;
            outline: none;
            transition: all 0.3s;
        }

        .modern-input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
            background: #fff;
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

        /* Buttons */
        .btn-custom {
            background-color: #3b82f6 !important;
            color: white !important;
            transition: all 0.2s;
        }

        .btn-custom:hover {
            background-color: #2563eb !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .section-header {
            position: relative;
            padding-left: 1rem;
        }

        .section-header::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: #3b82f6;
            border-radius: 4px;
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

@section('content')

    <div class="container-fluid py-6 px-4">
        <div class="flex flex-col gap-6">

            <!-- Left Column: Filter Section -->
            <div class="w-full">
                <div class="premium-card p-6 sticky top-28">
                    <h3 class="text-lg font-bold text-slate-800 mb-6 section-header flex items-center gap-2">
                        <i data-lucide="filter" class="size-5 text-slate-500"></i> Filter Questions
                    </h3>

                    <form id="filterForm">
                        <div class="space-y-4">
                            <div class="space-y-2">
                                <label for="status" class="text-sm font-bold text-slate-700 ml-1">By Evaluation</label>
                                <select id="status" name="evaluation" class="modern-input">
                                    <option selected disabled value="">All Evaluations</option>
                                    @foreach ($data as $evaluation)
                                        <option value="{{ $evaluation->id }}">{{ $evaluation->title }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <button type="submit"
                                class="btn-custom w-full py-3 rounded-xl font-bold flex items-center justify-center gap-2 mt-4">
                                <i data-lucide="search" class="size-4"></i> Apply Filters
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right Column: DataTable Section -->
            <div class="w-full ">
                <div class="premium-card p-8">
                    <div
                        class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4 pb-6 border-b border-slate-100">
                        <div>
                            <h2 class="text-2xl font-black text-slate-800 tracking-tight">Question Directory</h2>
                            <p class="text-slate-500 text-sm font-medium">Manage assessment questions and link references.
                            </p>
                        </div>
                        <button type="button" onclick="openModal('create-question')"
                            class="btn-custom px-6 py-2.5 rounded-xl font-bold flex items-center gap-2 whitespace-nowrap">
                            <i data-lucide="plus-circle" class="size-5"></i> Add Question
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table id="basic_tables" class="w-full whitespace-nowrap">
                            <thead>
                                <tr>
                                    <th class="w-16">#</th>
                                    <th>Title</th>
                                    <th>Expected Answer</th>
                                    {{-- <th>Reference Link</th> --}}
                                    <th>Evaluation Group</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Dynamic Data Rows -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Create Modal --}}
    <x-backend.modal id="create-question" class="w-full md:w-1/2 max-w-4xl" title="Add New Question">
        <form id="create-form">
            @csrf
            <div class="grid gap-6 grid-cols-1 md:grid-cols-2 p-4">

                <div class="col-span-2 space-y-2">
                    <label class="text-sm font-bold text-slate-700 ml-1">Question Title <span
                            class="text-red-500">*</span></label>
                    <textarea name="title" rows="3" class="modern-input" placeholder="Enter Question here.."></textarea>
                    @error('title')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-span-2 space-y-2">
                    <label class="text-sm font-bold text-slate-700 ml-1">Reference URL <span
                            class="text-red-500">*</span></label>
                    <input type="url" name="link" class="modern-input" placeholder="https://..." required>
                    @error('link')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-span-2 sm:col-span-1 space-y-2">
                    <label class="text-sm font-bold text-slate-700 ml-1">Expected Answer <span
                            class="text-red-500">*</span></label>
                    <select name="answer" class="modern-input">
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                    @error('answer')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-span-2 sm:col-span-1 space-y-2">
                    <label class="text-sm font-bold text-slate-700 ml-1">Associated Evaluation <span
                            class="text-red-500">*</span></label>
                    <select name="evaluation_id" class="modern-input" required>
                        <option selected disabled value="">Select an Evaluation</option>
                        @foreach ($data as $evaluation)
                            <option value="{{ $evaluation->id }}">{{ $evaluation->title }}</option>
                        @endforeach
                    </select>
                    @error('evaluation_id')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-span-2 flex justify-end mt-4 pt-4 border-t border-slate-100">
                    <button type="submit" class="btn-custom px-8 py-3 rounded-xl font-bold flex items-center gap-2">
                        <i data-lucide="save" class="size-5"></i> Create Question
                    </button>
                </div>
            </div>
        </form>
    </x-backend.modal>

    {{-- Edit Modal --}}
    <x-backend.modal id="edit-question" class="w-full md:w-1/2 max-w-4xl" title="Edit Question">
        <form id="edit-form">
            @csrf
            <input type="hidden" name="id">

            <div class="grid gap-6 grid-cols-1 md:grid-cols-2 p-4">
                <div class="col-span-2 space-y-2">
                    <label class="text-sm font-bold text-slate-700 ml-1">Question Title <span
                            class="text-red-500">*</span></label>
                    <textarea name="title" rows="3" class="modern-input" placeholder="Enter Question here.."></textarea>
                </div>

                <div class="col-span-2 space-y-2">
                    <label class="text-sm font-bold text-slate-700 ml-1">Reference URL <span
                            class="text-red-500">*</span></label>
                    <input type="url" name="link" class="modern-input" placeholder="https://..." required>
                </div>

                <div class="col-span-2 sm:col-span-1 space-y-2">
                    <label class="text-sm font-bold text-slate-700 ml-1">Expected Answer <span
                            class="text-red-500">*</span></label>
                    <select name="answer" class="modern-input">
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                </div>

                <div class="col-span-2 sm:col-span-1 space-y-2">
                    <label class="text-sm font-bold text-slate-700 ml-1">Associated Evaluation <span
                            class="text-red-500">*</span></label>
                    <select name="evaluation_id" class="modern-input" required>
                        <optgroup label="Select an Evaluation">
                            @foreach ($data as $evaluation)
                                <option value="{{ $evaluation->id }}">{{ $evaluation->title }}</option>
                            @endforeach
                        </optgroup>
                    </select>
                </div>

                <div class="col-span-2 flex justify-end mt-4 pt-4 border-t border-slate-100">
                    <button type="submit" class="btn-custom px-8 py-3 rounded-xl font-bold flex items-center gap-2">
                        <i data-lucide="save" class="size-5"></i> Update Question
                    </button>
                </div>
            </div>
        </form>
    </x-backend.modal>

@endsection

@push('scripts')
    <script src="{{ asset('backend/js/datatables/data-tables.min.js') }}"></script>
    <script src="{{ asset('backend/js/datatables/data-tables.tailwindcss.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.0/dist/sweetalert2.all.min.js"></script>
    <script src="{{ asset('vendor/flasher/flasher.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            let url = "{{ route('question.index') }}";
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
                dom: '<"flex flex-col md:flex-row justify-between items-center mb-4 gap-4"l f>rt<"flex flex-col md:flex-row justify-between items-center mt-6 gap-4"i p>',
                ajax: {
                    url: url,
                    type: "get",
                    data: function(d) {
                        d.evaluation = $('#status').val();
                    }
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
                            if (!data) return '';
                            return `<span class="font-bold text-slate-800">${data.length > 50 ? data.substring(0, 50) + '...' : data}</span>`;
                        }
                    },
                    {
                        data: 'answer',
                        name: 'answer',
                        render: function(data) {
                            let color = data === 'Yes' ?
                                'bg-green-50 text-green-600 border-green-100' :
                                'bg-red-50 text-red-600 border-red-100';
                            return `<span class="px-3 py-1 rounded-lg border text-xs font-bold ${color}">${data}</span>`;
                        }
                    },
                    // {
                    //     data: 'link',
                    //     name: 'link',
                    //     render: function(data) {
                    //         if (!data) return '';
                    //         return `<a href="${data}" target="_blank" class="text-blue-500 hover:text-blue-700 underline text-sm">${data.length > 25 ? data.substring(0, 25) + '...' : data}</a>`;
                    //     }
                    // },
                    {
                        data: 'evaluation_title',
                        name: 'evaluation_title',
                        render: function(data) {
                            if (!data) return '';
                            return `<span class="px-3 py-1 bg-slate-100 text-slate-600 text-xs font-semibold rounded-full">${data.length > 20 ? data.substring(0, 20) + '...' : data}</span>`;
                        }
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    }
                ]
            });

            $('#filterForm').on('submit', function(e) {
                e.preventDefault();
                dTable.draw();
            });
        });

        // Delete Confirm
        function showDeleteConfirm(id) {
            event.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: 'You will not be able to recover this question!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3b82f6',
                cancelButtonColor: '#ef4444',
                confirmButtonText: 'Yes, delete it!',
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteItem(id);
                }
            });
        }

        // Delete Button
        function deleteItem(id) {
            let url = '{{ route('question.destroy', ':id') }}';
            let csrfToken = '{{ csrf_token() }}';
            $.ajax({
                type: "DELETE",
                url: url.replace(':id', id),
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(resp) {
                    $('#basic_tables').DataTable().ajax.reload();
                    if (resp.success === true) {
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

        // Create Modal Submission
        document.addEventListener('DOMContentLoaded', function() {
            $('#create-form').on('submit', function(e) {
                e.preventDefault();
                let formData = $(this).serialize();
                let url = "{{ route('question.store') }}";
                $.ajax({
                    url,
                    method: 'POST',
                    data: formData,
                    success: function(resp) {
                        $('#basic_tables').DataTable().ajax.reload();
                        if (resp.success === true) {
                            flasher.success(resp.message);
                            $('#create-form')[0].reset();
                            closeModal('create-question');
                            $('[data-modal-close="create-question"]').trigger('click');
                        } else {
                            flasher.error(resp.message);
                        }
                    },
                    error: function(xhr) {
                        handleXhrErrors(xhr, 'create-question')
                    }
                });
            });

            // Edit Data Load
            $('body').on('click', '.edit', function() {
                var id = $(this).data('id');
                var url = "{{ route('question.edit', ':id') }}".replace(':id', id);

                $.ajax({
                    url: url,
                    type: 'get',
                    success: function(data) {
                        if (data.success) {
                            let q = data.data;
                            $('#edit-form input[name="id"]').val(q.id);
                            $('#edit-form textarea[name="title"]').val(q.title);
                            $('#edit-form input[name="link"]').val(q.link);
                            $('#edit-form select[name="answer"]').val(q.answer);
                            $('#edit-form select[name="evaluation_id"]').val(q.evaluation_id);

                            // Open Modal
                            openModal('edit-question');
                        } else {
                            flasher.error('Something went wrong.');
                        }
                    },
                    error: function(errors) {
                        flasher.error('Could not load data.');
                    }
                })
            });

            // Update Form Submission
            $('#edit-form').on('submit', function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                formData.append('_token', '{{ csrf_token() }}');
                let id = $(this).find("input[name='id']").val();
                let url = "{{ route('question.update', ':id') }}".replace(':id', id);

                $.ajax({
                    url,
                    method: 'POST',
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: formData,
                    success: function(resp) {
                        $('#basic_tables').DataTable().ajax.reload();
                        if (resp.success === true) {
                            flasher.success(resp.message);
                            $('[data-modal-close="edit-question"]').trigger('click');
                            $('#edit-question').addClass('hidden');
                            $('#edit-question-overlay').addClass('hidden');
                        } else {
                            flasher.error(resp.message);
                        }
                    },
                    error: function(xhr) {
                        handleXhrErrors(xhr, 'edit-question')
                    }
                });
            });

            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }

            // Explicitly bind the custom closeModal function to the close buttons and overlays
            $('[data-modal-close]').on('click', function() {
                closeModal($(this).data('modal-close'));
            });

            $('[id$="-overlay"]').on('click', function() {
                closeModal($(this).attr('id').replace('-overlay', ''));
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

        function handleXhrErrors(xhr, modalId) {
            if (xhr.status === 422) {
                let errors = xhr.responseJSON.errors;
                Object.keys(errors).forEach(key => flasher.error(errors[key][0]));
            } else {
                flasher.error('Something went wrong. Please try again.');
            }
        }
    </script>
@endpush
