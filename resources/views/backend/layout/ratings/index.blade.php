@extends('backend.app')

{{-- Title for the Dashboard --}}
@section('title', 'Course Reviews & Ratings')
@section('title_url')
    <a href="{{ route('ratings.index') }}">Course Reviews & Ratings</a>
@endsection
@section('tabName')
    <a href="{{ route('dashboard') }}">Home</a>
@endsection

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/datatables.net-buttons@2.3.7/css/buttons.dataTables.min.css" rel="stylesheet">
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

        /* Buttons export */
        .dt-buttons .dt-button {
            border-radius: 10px !important;
            color: #475569 !important;
            font-weight: 600 !important;
            padding: 8px 16px !important;
            transition: all 0.2s !important;
        }

        .dt-buttons .dt-button:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        /* Prevent "Show entries" label from breaking across lines */
        .dataTables_length label {
            white-space: nowrap;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            margin-bottom: 0;
        }

        /* Prevent "Show entries" label from wrapping */
        .dataTables_length,
        .dataTables_length label {
            white-space: nowrap !important;
            display: inline-flex !important;
            align-items: center;
            gap: 4px;
        }

        .dataTables_length select {
            width: auto !important;
            display: inline-block !important;
        }

        /* Vertical alignment of top bar controls */
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter,
        .dt-buttons {
            display: inline-flex !important;
            align-items: center !important;
            vertical-align: middle !important;
        }

        .dataTables_length select,
        .dataTables_filter input,
        .dt-button {
            margin: 0 4px !important;
            vertical-align: middle !important;
        }

        .dataTables_wrapper .dataTables_length label,
        .dataTables_wrapper .dataTables_filter label,
        .dt-buttons .dt-button {
            line-height: 38px !important;
        }

        .table .dataTable{
            display:hidden
        }
    </style>
@endpush

@section('content')
    <div class="premium-card overflow-hidden mb-8">
        <div class="p-8">
            <div
                class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4 border-b border-slate-100 pb-6">
                <div>
                    <h2 class="text-2xl font-black text-slate-800 tracking-tight">Course Reviews & Ratings</h2>
                    <p class="text-slate-500 text-sm font-medium mt-1">Manage and moderate student course reviews and
                        ratings.</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table id="basic_tables" class="w-full whitespace-nowrap">
                    <thead>
                        <tr>
                            <th class="w-16">#</th>
                            <th>Course</th>
                            <th>User</th>
                            <th>Rating</th>
                            <th>Review Message</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('backend/js/datatables/jquery-3.7.0.js') }}"></script>
    <script src="{{ asset('backend/js/datatables/data-tables.min.js') }}"></script>
    <script src="{{ asset('backend/js/datatables/data-tables.tailwindcss.min.js') }}"></script>
    <script src="{{ asset('backend/js/datatables/datatables.buttons.min.js') }}"></script>
    <script src="{{ asset('backend/js/datatables/jszip.min.js') }}"></script>
    <script src="{{ asset('backend/js/datatables/pdfmake.min.js') }}"></script>
    <script src="{{ asset('backend/js/datatables/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('backend/js/datatables/buttons.print.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.0/dist/sweetalert2.all.min.js"></script>
    <script src="{{ asset('vendor/flasher/flasher.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            let url = "{{ route('ratings.index') }}";
            let dTable = $('#basic_tables').DataTable({
                order: [],
                ordering: false,
                destroy: true,
                lengthMenu: [
                    [25, 50, 100, 200, 500, -1],
                    [25, 50, 100, 200, 500, "All"]
                ],
                processing: true,
                responsive: true,
                serverSide: true,
                // dom: '<"flex flex-col lg:flex-row justify-between items-center mb-6 gap-2"<"flex flex-wrap items-center gap-2 whitespace-nowrap"l B> f>rt<"flex flex-col md:flex-row justify-between items-center mt-6 gap-4"i p>',
                // dom: '<"flex flex-col lg:flex-row justify-center items-center mb-6 gap-2"<"flex flex-nowrap items-center gap-2 whitespace-nowrap"l B> f>rt<"flex flex-col md:flex-row justify-between items-center mt-6 gap-4"i p>',
                ajax: {
                    url: url,
                    type: "get",
                },
                buttons: [{
                        extend: 'excelHtml5',
                        text: '<span class="flex items-center gap-2"><svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg> Excel</span>',
                        className: 'dt-button',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4]
                        }
                    },
                    {
                        extend: 'csvHtml5',
                        text: '<span class="flex items-center gap-2"><svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg> CSV</span>',
                        className: 'dt-button',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4]
                        }
                    }
                ],
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'course',
                        name: 'course',
                        render: function(data) {
                            return `<span class="font-bold text-slate-800">${data}</span>`;
                        }
                    },
                    {
                        data: 'user',
                        name: 'user',
                        render: function(data) {
                            return `<span class="text-slate-500">${data}</span>`;
                        }
                    },
                    {
                        data: 'rating',
                        name: 'rating',
                    },
                    {
                        data: 'message',
                        name: 'message',
                        render: function(data) {
                            return data ?
                                `<span class="text-slate-600 whitespace-normal break-words inline-block max-w-md">${data}</span>` :
                                `<span class="text-slate-400 italic">No comment provided</span>`;
                        }
                    },
                    {
                        data: 'status',
                        name: 'status',
                    },
                    {
                        data: 'action',
                        name: 'action',
                        className: 'text-center'
                    },
                ]
            });
        });

        // Status Change Confirm Alert
        function showStatusChangeAlert(id) {
            event.preventDefault();

            Swal.fire({
                title: 'Are you sure?',
                text: 'You want to toggle this review\'s visibility?',
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
            let url = '{{ route('ratings.status', ':id') }}';
            let csrfToken = '{{ csrf_token() }}';
            $.ajax({
                type: "POST",
                url: url.replace(':id', id),
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(resp) {
                    $('#basic_tables').DataTable().ajax.reload(null, false);
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

        // Delete Confirm
        function showDeleteConfirm(id) {
            event.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: 'You will not be able to recover this course review!',
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
            let url = '{{ route('ratings.destroy', ':id') }}';
            let csrfToken = '{{ csrf_token() }}';
            $.ajax({
                type: "DELETE",
                url: url.replace(':id', id),
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(resp) {
                    $('#basic_tables').DataTable().ajax.reload(null, false);
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

        // View Review Details Modal
        function showReviewModal(element) {
            event.preventDefault();
            let info = JSON.parse(element.getAttribute('data-info'));
            
            Swal.fire({
                title: 'Review Details',
                html: `
                    <div style="text-align: left; font-size: 15px; line-height: 1.6;">
                        <div style="margin-bottom: 10px;"><strong>User:</strong> <span style="color: #475569;">${info.user}</span></div>
                        <div style="margin-bottom: 10px;"><strong>Course:</strong> <span style="color: #475569;">${info.course}</span></div>
                        <div style="margin-bottom: 10px;"><strong>Rating:</strong> 
                            <span style="display: inline-flex; align-items: center; gap: 4px; background: #fffbeb; color: #d97706; padding: 2px 8px; border-radius: 4px; font-weight: bold; border: 1px solid #fef3c7;">
                                <svg style="width: 14px; height: 14px; fill: #f59e0b;" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                                ${info.rating}
                            </span>
                        </div>
                        <hr style="margin: 15px 0; border-top: 1px solid #e2e8f0;">
                        <div style="margin-bottom: 5px;"><strong>Review Message:</strong></div>
                        <div style="background: #f8fafc; padding: 15px; border-radius: 8px; border: 1px solid #f1f5f9; color: #334155; text-align: justify; word-break: break-word;">
                            ${info.message ? info.message : '<span style="color: #94a3b8; font-style: italic;">No comment provided</span>'}
                        </div>
                    </div>
                `,
                confirmButtonText: 'Close',
                confirmButtonColor: '#3b82f6',
                width: '600px',
                padding: '2em'
            });
        }
    </script>
@endpush
