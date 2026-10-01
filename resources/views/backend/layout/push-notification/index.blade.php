@extends('backend.app')
@section('title', 'Push Notifications')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@24.7.0/build/css/intlTelInput.css">
    <link rel="stylesheet" href="{{ asset('backend/js/datatables/jquery-3.7.0.js') }}">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.0/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="{{ asset('vendor/flasher/flasher.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.css">

    <style>
        .dropify-wrapper {
            height: 185px;
            border-radius: 20px;
            border: 2px dashed #cbd5e1;
            background-color: #f8fafc;
            transition: all 0.3s;
        }

        .dropify-wrapper:hover {
            border-color: #3b82f6;
            background-color: #f1f5f9;
        }

        .premium-card {
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.04), 0 4px 6px -2px rgba(0, 0, 0, 0.02);
            border: 1px solid #f1f5f9;
        }

        /* Modern Datatable Styling */
        #userTable_wrapper .dataTables_length select {
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            padding: 6px 12px;
            outline: none;
        }

        #userTable_wrapper .dataTables_filter input {
            border-radius: 14px;
            border: 1px solid #e2e8f0;
            padding: 10px 20px;
            outline: none;
            width: 280px;
            transition: all 0.3s;
        }

        #userTable_wrapper .dataTables_filter input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.08);
            background: #fff;
        }

        #userTable {
            border-collapse: separate !important;
            border-spacing: 0 12px !important;
            width: 100% !important;
            border: none !important;
        }

        #userTable thead th {
            color: #64748b;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.1em;
            padding: 20px !important;
            border: none !important;
        }

        #userTable tbody tr {
            background: #ffffff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.01);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        #userTable tbody tr:hover {
            transform: translateY(-2px) scale(1.01);
            box-shadow: 0 12px 20px -5px rgba(0, 0, 0, 0.05);
            background: #fff;
            z-index: 10;
            position: relative;
        }

        #userTable tbody td {
            padding: 20px !important;
            border: none !important;
            vertical-align: middle;
        }

        #userTable tbody tr td:first-child {
            border-radius: 16px 0 0 16px;
        }

        #userTable tbody tr td:last-child {
            border-radius: 0 16px 16px 0;
        }

        /* Custom Checkbox Styling */
        .user-checkbox {
            width: 1.5rem;
            height: 1.5rem;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .user-checkbox:checked {
            background-color: #3b82f6 !important;
            border-color: #3b82f6 !important;
            background-image: url("data:image/svg+xml,%3csvg viewBox='0 0 16 16' fill='white' xmlns='http://www.w3.org/2000/svg'%3e%3cpath d='M12.207 4.793a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0l-2-2a1 1 0 011.414-1.414L6.5 9.086l4.293-4.293a1 1 0 011.414 0z'/%3e%3c/svg%3e");
        }

        /* Form Modernization */
        .form-section-header {
            position: relative;
            padding-left: 1rem;
        }

        .form-section-header::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: #3b82f6;
            border-radius: 2px;
        }

        /* Color Fix for Custom Buttons */
        .btn-custom {
            background-color: #3b82f6 !important;
            color: white !important;
        }

        .btn-custom:hover {
            background-color: #2563eb !important;
            transform: translateY(-1px);
        }

        /* Pagination Styling */
        .dataTables_paginate .paginate_button {
            border-radius: 12px !important;
            border: 1px solid #e2e8f0 !important;
            margin: 0 4px !important;
            padding: 8px 16px !important;
            transition: all 0.3s !important;
            background: white !important;
            font-weight: 600 !important;
        }

        .dataTables_paginate .paginate_button.current {
            background: #3b82f6 !important;
            color: white !important;
            border-color: #3b82f6 !important;
            box-shadow: 0 4px 10px rgba(59, 130, 246, 0.3) !important;
        }

        .dataTables_paginate .paginate_button:hover:not(.current) {
            background: #f8fafc !important;
            border-color: #cbd5e1 !important;
        }

        /* #basic_tables_wrapper .dataTables_scrollBody thead tr {
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
        } */
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-10">
            <!-- Left Column: Notification Form -->
            <div class="xl:col-span-5">
                <div class="premium-card p-10 sticky top-28 shadow-2xl">
                    <div class="flex items-center gap-4 mb-10">
                        <div
                            class="size-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center shadow-sm">
                            <i data-lucide="bell-ring" class="size-7"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-black text-slate-800 tracking-tight">Create Notification</h2>
                            <p class="text-slate-500 text-sm font-medium">Broadcast your message to the community</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('send.notification') }}" enctype="multipart/form-data"
                        id="notification-form" class="space-y-8">
                        @csrf

                        <!-- Target Selection -->
                        <div class="space-y-4">
                            <h3 class="text-sm font-bold text-slate-700 uppercase tracking-widest flex items-center gap-2">
                                <i data-lucide="users" class="size-4"></i> Target Audience
                            </h3>
                            <div class="grid grid-cols-2 gap-4">
                                <label class="cursor-pointer group">
                                    <input type="radio" name="user_selection_type" value="all" id="for_all_users"
                                        class="sr-only peer">
                                    <div
                                        class="h-full p-4 rounded-2xl border-2 border-slate-100 bg-slate-50 peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-700 transition-all group-hover:bg-white flex flex-col items-center justify-center text-center gap-2">
                                        <div
                                            class="size-10 rounded-full bg-white flex items-center justify-center shadow-sm group-hover:shadow-md transition-shadow">
                                            <i data-lucide="globe" class="size-5"></i>
                                        </div>
                                        <span class="font-bold text-sm">All Users</span>
                                    </div>
                                </label>
                                <label class="cursor-pointer group">
                                    <input type="radio" checked name="user_selection_type" value="specific"
                                        id="for_specific_users" class="sr-only peer">
                                    <div
                                        class="h-full p-4 rounded-2xl border-2 border-slate-100 bg-slate-50 peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-700 transition-all group-hover:bg-white flex flex-col items-center justify-center text-center gap-2">
                                        <div
                                            class="size-10 rounded-full bg-white flex items-center justify-center shadow-sm group-hover:shadow-md transition-shadow">
                                            <i data-lucide="user-check" class="size-5"></i>
                                        </div>
                                        <span class="font-bold text-sm">Specific Users</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Form Fields -->
                        <div class="space-y-6">
                            <div class="space-y-2">
                                <x-backend.input type="text" name="title" label="Notification Title" :required="true"
                                    placeholder="Enter an engaging title..."
                                    class="rounded-xl border-slate-200 focus:ring-4 focus:ring-blue-500/10" />
                            </div>

                            <div class="space-y-2">
                                <x-backend.text-area name="description" label="Message Content" :required="true"
                                    placeholder="What would you like to tell your users?" rows="5"
                                    class="rounded-xl border-slate-200 focus:ring-4 focus:ring-blue-500/10" />
                            </div>

                            {{-- <div class="space-y-2">
                                <x-backend.dropify name="image" label="Visual Media (Optional)" />
                            </div> --}}
                        </div>

                        <div class="pt-6">
                            <button type="submit"
                                class="w-full btn-custom py-5 rounded-2xl shadow-xl shadow-blue-500/30 font-black text-lg flex items-center justify-center gap-3 transition-all active:scale-95">
                                <span>Send Notification</span>
                                <i data-lucide="arrow-right-circle" class="size-6"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right Column: User Selection -->
            <div class="xl:col-span-7 transition-all duration-500" id="user_selection">
                <div class="premium-card p-10 h-full">
                    <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-6">
                        <div>
                            <h2 class="text-2xl font-black text-slate-800 tracking-tight">Select Recipients</h2>
                            <p class="text-slate-500 font-medium">Choose users from the directory below</p>
                        </div>
                        <div class="flex items-center gap-4">
                            <button type="button" id="select-all"
                                class="flex items-center gap-2 px-6 py-3 rounded-xl bg-emerald-50 text-emerald-700 font-bold text-sm border-2 border-emerald-100 hover:bg-emerald-100 hover:border-emerald-200 transition-all">
                                <i data-lucide="check-circle" class="size-4"></i>
                                Select All
                            </button>
                            <button type="button" id="deselect-all"
                                class="flex items-center gap-2 px-6 py-3 rounded-xl bg-rose-50 text-rose-700 font-bold text-sm border-2 border-rose-100 hover:bg-rose-100 hover:border-rose-200 transition-all">
                                <i data-lucide="x-circle" class="size-4"></i>
                                Deselect All
                            </button>
                        </div>
                    </div>

                    <div class="overflow-x-auto -mx-2 px-2">
                        <table id="userTable" class="w-full">
                            <thead>
                                <tr>
                                    <th class="w-16">#</th>
                                    <th>Recipient</th>
                                    <th>Email Details</th>
                                    <th class="text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('backend/js/datatables/jquery-3.7.0.js') }}"></script>
    <script src="{{ asset('backend/js/datatables/data-tables.min.js') }}"></script>
    <script src="{{ asset('backend/js/datatables/data-tables.tailwindcss.min.js') }}"></script>
    <script type="text/javascript" src="https://jeremyfagis.github.io/dropify/dist/js/dropify.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.0/dist/sweetalert2.all.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize Dropify
            $('.dropify').dropify({
                tpl: {
                    message: '<div class="dropify-message"><span class="file-icon"></span> <p style="font-size: 16px; font-weight: 600; color: #64748b;">Drop your image here or click to browse</p></div>'
                }
            });

            // Initialize DataTable
            let table = $('#userTable').DataTable({
                order: [],
                ordering: false,
                destroy: true,
                // scrollX: true,
                // scrollCollapse: true,
                autoWidth: false,
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                processing: true,
                serverSide: true,
                dom: '<"flex flex-col lg:flex-row justify-between items-center gap-6"l f>rt<"flex flex-col lg:flex-row justify-between items-center mt-10 gap-6"i p>',
                ajax: "{{ route('push-notification.index') }}",
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
                            return `<div class="flex items-center gap-5">
                                <div class="relative">
                                    ${row.avatar}
                                    <div class="absolute -bottom-1 -right-1 size-4 bg-emerald-500 border-2 border-white rounded-full"></div>
                                </div>
                                <div>
                                    <div class="font-black text-slate-800 text-base mb-0.5">${data}</div>
                                    <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">User ID: #${row.id}</div>
                                </div>
                            </div>`;
                        }
                    },
                    {
                        data: 'email',
                        name: 'email',
                        render: function(data) {
                            return `<div class="flex items-center gap-2">
                                <div class="size-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400">
                                    <i data-lucide="mail" class="size-4"></i>
                                </div>
                                <span class="text-slate-600 font-semibold text-sm">${data}</span>
                            </div>`;
                        }
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-right'
                    },
                ],
                drawCallback: function() {
                    // Re-bind Lucide icons after draw
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }

                    // Re-bind Select/Deselect events
                    $('#select-all').off('click').on('click', function() {
                        $('.user-checkbox').prop('checked', true).closest('tr').addClass(
                            'bg-blue-50/50');
                    });
                    $('#deselect-all').off('click').on('click', function() {
                        $('.user-checkbox').prop('checked', false).closest('tr').removeClass(
                            'bg-blue-50/50');
                    });

                    // Add visual feedback on click
                    $('.user-checkbox').on('change', function() {
                        if ($(this).is(':checked')) {
                            $(this).closest('tr').addClass('bg-blue-50/50');
                        } else {
                            $(this).closest('tr').removeClass('bg-blue-50/50');
                        }
                    });
                }
            });

            // Handle Form Submission
            $('#notification-form').on('submit', function(e) {
                let selectionType = $("input[name='user_selection_type']:checked").val();

                if (selectionType === 'specific') {
                    let selectedCheckboxes = $('.user-checkbox:checked');

                    if (selectedCheckboxes.length === 0) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'error',
                            title: 'No Recipients!',
                            text: 'You must select at least one user from the list to send a specific notification.',
                            confirmButtonText: 'Go to Selection',
                            confirmButtonColor: '#3b82f6',
                            background: '#fff',
                            color: '#1e293b'
                        });
                        return;
                    }

                    $(this).find('input[name="user_ids[]"]').remove();
                    selectedCheckboxes.each(function() {
                        $('<input>').attr({
                            type: 'hidden',
                            name: 'user_ids[]',
                            value: $(this).val()
                        }).appendTo('#notification-form');
                    });
                }
            });

            // Toggle Visibility of User Selection
            function toggleUserSelection() {
                let isAll = $("input[name='user_selection_type']:checked").val() === 'all';
                if (isAll) {
                    $("#user_selection").addClass('opacity-30 pointer-events-none scale-95 origin-right');
                } else {
                    $("#user_selection").removeClass('opacity-30 pointer-events-none scale-95 origin-right');
                }
            }

            toggleUserSelection();
            $("input[name='user_selection_type']").change(toggleUserSelection);
        });
    </script>
@endpush
