@extends('backend.app')

@section('title', 'Support Tickets')
@section('title_url')
    <a href="{{ route('support.tickets') }}">Support</a>
@endsection
@section('tabName')
    Tickets
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('backend/js/datatables/jquery-3.7.0.js') }}">
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
        #support_table_wrapper .dataTables_length select {
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            padding: 4px 8px;
            outline: none;
        }

        #support_table_wrapper .dataTables_filter input {
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            padding: 8px 16px;
            outline: none;
            width: 250px;
            transition: all 0.3s;
        }

        #support_table_wrapper .dataTables_filter input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        #support_table {
            border-collapse: separate !important;
            border-spacing: 0 12px !important;
            width: 100% !important;
            border: none !important;
        }

        #support_table thead th {
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.05em;
            padding: 16px !important;
            border: none !important;
        }

        #support_table tbody tr {
            background: #ffffff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
            transition: all 0.3s;
        }

        #support_table tbody tr:hover {
            /* transform: scale(1.005); */
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            background: #fdfdfd;
        }

        #support_table tbody td {
            padding: 16px !important;
            border: none !important;
            vertical-align: middle;
        }

        #support_table tbody tr td:first-child {
            border-radius: 12px 0 0 12px;
        }

        #support_table tbody tr td:last-child {
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
                    <h2 class="text-xl font-bold text-slate-800">Support Tickets</h2>
                    <p class="text-slate-500 text-sm">Review, manage, and respond to user inquiries.</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table id="support_table" class="w-full">
                    <thead>
                        <tr>
                            <th class="w-16">#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Country</th>
                            <th style="width: 20%;">Message</th>
                            <th class="text-center">Status</th>
                            <th class="">Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- View Record Modal --}}
    <x-backend.modal id="view-record-modal" class="w-full md:w-1/2 max-w-4xl justify-center" title="Support Ticket Details">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-slate-600 dark:text-zink-200">
            <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                <p class="font-bold text-slate-400 text-xs uppercase mb-1">Name</p>
                <p id="view-name" class="text-slate-800 font-semibold"></p>
            </div>
            <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                <p class="font-bold text-slate-400 text-xs uppercase mb-1">Email</p>
                <p id="view-email" class="text-slate-800 font-semibold"></p>
            </div>
            <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                <p class="font-bold text-slate-400 text-xs uppercase mb-1">Country</p>
                <p id="view-country" class="text-slate-800 font-semibold"></p>
            </div>
            <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                <p class="font-bold text-slate-400 text-xs uppercase mb-1">Status</p>
                <p id="view-status" class="text-slate-800 font-semibold"></p>
            </div>
            <div class="md:col-span-2 bg-slate-50 p-4 rounded-xl border border-slate-100">
                <p class="font-bold text-slate-400 text-xs uppercase mb-1">Submitted At</p>
                <p id="view-created-at" class="text-slate-800 font-semibold"></p>
            </div>
            <div class="md:col-span-2">
                <p class="font-bold text-slate-400 text-xs uppercase mb-2">Message Content</p>
                <div id="view-message"
                    class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm whitespace-pre-wrap text-slate-700 leading-relaxed">
                </div>
            </div>
        </div>
    </x-backend.modal>
@endsection

@push('scripts')
    <script src="{{ asset('backend/js/datatables/jquery-3.7.0.js') }}"></script>
    <script src="{{ asset('backend/js/datatables/data-tables.min.js') }}"></script>
    <script src="{{ asset('backend/js/datatables/data-tables.tailwindcss.min.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.0/dist/sweetalert2.all.min.js"></script>

    <script>
        $(document).ready(function() {
            let dTable = $('#support_table').DataTable({
                order: [],
                ordering: false,
                destroy: true,
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                processing: true,
                serverSide: true,
                dom: '<"flex flex-col md:flex-row justify-between items-center mb-4 gap-4"l f>rt<"flex flex-col md:flex-row justify-between items-center mt-6 gap-4"i p>',
                ajax: {
                    url: "{{ route('support.tickets') }}",
                    type: "GET",
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
                        data: 'country',
                        name: 'country'
                    },
                    {
                        data: 'message',
                        name: 'message',
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

        function viewDetails(id) {
            let url = "{{ route('support.tickets.show', ':id') }}".replace(':id', id);
            $.ajax({
                url: url,
                type: 'GET',
                success: function(resp) {
                    if (resp.success) {
                        let data = resp.data;
                        $('#view-name').text(data.name);
                        $('#view-email').text(data.email);
                        $('#view-country').text(data.country || 'N/A');

                        // Status badge logic for modal
                        let statusColor = data.status === 'pending' ? 'text-amber-600 bg-amber-50' :
                            data.status === 'resolved' ? 'text-green-600 bg-green-50' :
                            'text-slate-600 bg-slate-100';
                        $('#view-status').html(
                            `<span class="px-3 py-1 rounded-full text-xs font-bold ${statusColor}">${data.status.charAt(0).toUpperCase() + data.status.slice(1)}</span>`
                            );

                        $('#view-message').text(data.message);
                        $('#view-created-at').text(new Date(data.created_at).toLocaleString());

                        $('#view-record-modal').removeClass('hidden');
                        $('#view-record-modal-overlay').removeClass('hidden');
                    } else {
                        flasher.error(resp.message);
                    }
                },
                error: function() {
                    flasher.error('Something went wrong');
                }
            });
        }

        // Close modal listeners
        $(document).on('click', '[data-modal-close="view-record-modal"]', function() {
            $('#view-record-modal').addClass('hidden');
            $('#view-record-modal-overlay').addClass('hidden');
        });

        function changeStatus(selectElement) {
            let id = $(selectElement).data('id');
            let status = $(selectElement).val();
            let url = "{{ route('support.tickets.status', ':id') }}".replace(':id', id);

            $.ajax({
                url: url,
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    status: status
                },
                success: function(resp) {
                    if (resp.success) {
                        flasher.success(resp.message);
                    } else {
                        flasher.error(resp.message);
                    }
                },
                error: function(xhr) {
                    flasher.error('Something went wrong');
                }
            });
        }

        function showDeleteConfirm(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteItem(id);
                }
            })
        }

        function deleteItem(id) {
            let url = "{{ route('support.tickets.destroy', ':id') }}".replace(':id', id);
            $.ajax({
                url: url,
                type: 'DELETE',
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(resp) {
                    if (resp.success) {
                        flasher.success(resp.message);
                        $('#support_table').DataTable().ajax.reload();
                    } else {
                        flasher.error(resp.message);
                    }
                },
                error: function(xhr) {
                    flasher.error('Something went wrong');
                }
            });
        }
    </script>
@endpush
