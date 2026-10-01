@extends('backend.app')

{{-- Title for the Dashboard --}}
@section('title', 'Users')
@section('title_url')
    <a href="{{ route('user.index') }}">Users</a>
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

        /* .dataTables_paginate .paginate_button:hover:not(.current) {
            background: #f1f5f9 !important;
        } */

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

        /* Force the "Show X entries" label to never wrap */
        .dataTables_length,
        .dataTables_length label {
            white-space: nowrap !important;
            display: inline-flex !important;
            align-items: center;
            gap: 4px;
        }

        /* Keep the select inline and auto width */
        .dataTables_length select {
            width: auto !important;
            display: inline-block !important;
        }

        /* Prevent the length menu container from shrinking */
        .dataTables_length {
            flex-shrink: 0;
        }

        /* Fix: scrollX creates a duplicate hidden header row for column-width
           calculation. Without the official DataTables CSS, it shows as an
           empty row. This collapses it properly. */
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
    <div class="premium-card overflow-hidden mb-8">
        <div class="p-8">
            <div
                class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4 border-b border-slate-100 pb-6">
                <div>
                    <h2 class="text-2xl font-black text-slate-800 tracking-tight">User Directory</h2>
                    <p class="text-slate-500 text-sm font-medium mt-1">Manage and monitor platform users across all roles.
                    </p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table id="basic_tables" class="w-full min-w-[900px] whitespace-nowrap">
                    <thead>
                        <tr class=''>
                            <th class="w-16 px-4 py-2 text-center">#</th>
                            <th class="px-4 py-2 text-center">Name</th>
                            <th class="px-4 py-2 text-center">Email</th>
                            <th class="px-4 py-2 text-center">Parent Birth Date</th>
                            <th class="px-4 py-2 text-center">Parent Role</th>
                            <th class="px-4 py-2 text-center">Country</th>
                            <th class="px-4 py-2 text-center">Total Children</th>
                            {{-- <th class="px-4 py-2">Role</th> --}}
                            <th class="px-4 py-2 text-center">Avatar</th>
                            <th class="px-4 py-2 text-center">Status</th>
                            <th class="px-4 py-2 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="text-center"></tbody>
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
            let url = "{{ route('user.index') }}";
            let dTable = $('#basic_tables').DataTable({
                order: [],
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
                // dom: '<"flex flex-col lg:flex-row justify-between items-center mb-6 gap-4"<"flex items-center gap-4"l B> f>rt<"flex flex-col md:flex-row justify-between items-center mt-6 gap-4"i p>',
                // dom: '<"flex flex-col lg:flex-row justify-between items-center mb-6 gap-2"<"flex flex-nowrap items-center gap-2 whitespace-nowrap"l B> f>rt<"flex flex-col md:flex-row justify-between items-center mt-6 gap-4"i p>',
                // ajax: {
                //     url: url,
                //     type: "get",
                // },
                // buttons: [{
                //         extend: 'excelHtml5',
                //         text: '<span class="flex items-center gap-2"><svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg> Excel</span>',
                //         className: 'dt-button',
                //         exportOptions: {
                //             columns: [0, 1, 2, 3, 4, 5, 6, 7]
                //         }
                //     },
                //     {
                //         extend: 'csvHtml5',
                //         text: '<span class="flex items-center gap-2"><svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg> CSV</span>',
                //         className: 'dt-button',
                //         exportOptions: {
                //             columns: [0, 1, 2, 3, 4, 5, 6, 7]
                //         }
                //     }
                // ],
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name',
                        render: function(data) {
                            return `<span class="font-bold text-slate-800">${data}</span>`;
                        }
                    },
                    {
                        data: 'email',
                        name: 'email',
                        render: function(data) {
                            return `<span class="text-slate-500">${data}</span>`;
                        }
                    },
                    {
                        data: 'birth_date',
                        name: 'birth_date',
                        visible: false
                    },
                    {
                        data: 'parent_role',
                        name: 'parent_role',
                        visible: false
                    },
                    {
                        data: 'country',
                        name: 'country',
                        visible: false
                    },
                    {
                        data: 'children_count',
                        name: 'children_count',
                        visible: false
                    },
                    // {
                    //     data: 'role',
                    //     name: 'role',
                    //     render: function(data) {
                    //         let color = data.toLowerCase() === 'admin' ?
                    //             'bg-purple-50 text-purple-600 border-purple-100' :
                    //             data.toLowerCase() === 'instructor' ?
                    //             'bg-blue-50 text-blue-600 border-blue-100' :
                    //             'bg-slate-50 text-slate-600 border-slate-200';
                    //         return `<span class="px-3 py-1 rounded-full border text-xs font-bold uppercase tracking-wide ${color}">${data}</span>`;
                    //     }
                    // },
                    {
                        data: 'avatar',
                        name: 'avatar',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
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
            let url = '{{ route('user.status', ':id') }}';
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
                text: 'You will not be able to recover this user!',
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
            let url = '{{ route('user.destroy', ':id') }}';
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
    </script>
@endpush
