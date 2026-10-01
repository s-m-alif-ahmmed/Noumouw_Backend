@extends('backend.app')

@section('title', 'Subscription')
@section('title_url')
    <a href="{{ route('subscription.index') }}">Subscription</a>
@endsection
@section('tabName')
    <a href="{{ route('dashboard') }}">Home</a>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@24.7.0/build/css/intlTelInput.css">
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

@section('content')
    <div class="premium-card overflow-hidden mb-8">
        <div class="p-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
                <div>
                    <h2 class="text-xl font-bold text-slate-800">Subscription Plans</h2>
                    <p class="text-slate-500 text-sm">Manage your platform's pricing strategy and membership levels</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('subscription.create') }}"
                        class="inline-flex items-center gap-2 bg-custom-500 text-white px-6 py-2.5 rounded-xl hover:bg-custom-600 transition-all shadow-lg shadow-custom-500/30 font-medium whitespace-nowrap">
                        <span class="text-xl leading-none">+</span>
                        Add Subscription Plan
                    </a>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table id="basic_tables" class="w-full">
                    <thead>
                        <tr>
                            <th class="w-16">#</th>
                            <th>Plan Details</th>
                            <th>Price & Billing</th>
                            <th>Revenue Cart ID</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Create Subscription Plan --}}
    <x-backend.modal id="add-subscription-plan" class="w-full md:w-1/2 max-w-6xl justify-center"
        title="Create Subscription Plan">
        <form id="subscription-plan-form" enctype="multipart/form-data">
            @csrf @method('POST')
            <div class="grid grid-cols-1 gap-4 xl:grid-cols-12">

                {{-- ------------------- Subscription Name ------------- --}}
                <div class="xl:col-span-12">
                    <x-backend.input-ajax type="text" name="name" label="Subscription Name" :required="true" />
                </div>

                {{-- ------------------- Subscription Price ------------- --}}
                <div class="xl:col-span-12">
                    <x-backend.input-ajax type="number" name="price" label="Price" :required="true" />
                </div>

                {{-- ------------------- Subscription Plan Type ------------- --}}
                <div class="xl:col-span-12">
                    <label class="block font-medium text-gray-700 mt-4">Select a Plan Type</label>
                    <div class="mt-2  space-y-2 space-x-3">
                        <label class="inline-flex items-center ">
                            <input type="radio" value="monthly" name="duration" class="form-radio text-blue-600">
                            <span class="ml-1">Monthly</span>
                        </label>
                        <label class="inline-flex  items-center">
                            <input type="radio" value="yearly" name="duration" class="form-radio text-blue-600" checked>
                            <span class="ml-1">Yearly</span>
                        </label>
                    </div>
                    <span id="duration-error-message" class="text-red-500 text-sm error-message"></span>
                </div>

                {{-- ------------------- Subscription Revenue Cart Product ID ------------- --}}
                <div class="xl:col-span-12">
                    <x-backend.input-ajax type="text" name="revenue_cart_product_id" label="Revenue Cart Product ID"
                        :required="true" />
                </div>

            </div>

            <div class="flex justify-end gap-2 mt-4">
                <button type="submit"
                    class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600
                    focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">
                    Add Subscription Plan
                </button>
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

    <script src="{{ asset('backend/js/datatables/datatables.init.js') }}"></script>
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
                    url: "{{ route('subscription.index') }}",
                    type: "get",
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                    },
                    {
                        data: 'name',
                        name: 'name',
                        render: function(data) {
                            return `<div class="flex items-center gap-3">
                                <div class="size-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shadow-sm">
                                    <i data-lucide="crown" class="size-5"></i>
                                </div>
                                <div>
                                    <div class="font-bold text-slate-800">${data}</div>
                                    <div class="text-[10px] text-slate-400 uppercase tracking-wider font-semibold">Premium Access</div>
                                </div>
                            </div>`;
                        }
                    },
                    {
                        data: 'price',
                        name: 'price',
                        render: function(data, type, row) {
                            let durationLabel = row.duration === 'yearly' ? '/ Year' : '/ Month';
                            return `<div>
                                <span class="text-lg font-extrabold text-slate-800">$${data}</span>
                                <span class="text-xs text-slate-400 font-medium">${durationLabel}</span>
                            </div>`;
                        }
                    },
                    {
                        data: 'revenue_cart_product_id',
                        name: 'revenue_cart_product_id',
                        render: function(data) {
                            return `<code class="px-2 py-1 bg-slate-100 rounded text-[11px] font-mono text-slate-600 border border-slate-200 mx-auto">${data || 'N/A'}</code>`;
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
                drawCallback: function() {
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                }
            });
        });

        // Create Subscription
        $(function() {
            $('#subscription-plan-form').on('submit', function(e) {
                e.preventDefault();

                console.log('Submitting Form...'); // ✅ Check if this logs

                let formData = new FormData(this);
                let url = "{{ route('subscription.store') }}";

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
                            clearModal('add-subscription-plan');
                        } else if (resp.errors) {
                            flasher.error(resp.errors[0]);
                        } else {
                            flasher.error(resp.message);
                        }
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText); // ✅ Log server error
                        handleXhrErrors(xhr, 'add-subscription-plan');
                    }
                });
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
            let url = '{{ route('subscription.destroy', ':id') }}';
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
@endpush
