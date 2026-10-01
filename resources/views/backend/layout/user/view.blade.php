@extends('backend.app')

@section('title', 'User Details')
@section('title_url')
    <a href="{{ route('user.index') }}">
        User Details</a>
@endsection
@section('tabName')
    Home
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('backend/js/datatables/jquery-3.7.0.js') }}">

    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.0/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="{{ asset('vendor/flasher/flasher.min.css') }}" rel="stylesheet">
    <style>
        .card-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0;
        }

        .user-card {
            position: relative;
            background: #fff;
            border-radius: 12px;
            padding: 22px 24px;
            box-shadow: 0 6px 18px rgba(14, 30, 37, 0.06);
            display: block;
        }

        .user-card-header {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-bottom: 8px;
        }

        .user-actions a {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 12px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
        }

        .btn-edit {
            background: #2969EB;
            color: #fff;
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .btn-back {
            background: #ffffff;
            color: #374151;
            border: 1px solid #e5e7eb;
        }

        .user-card-body {
            display: grid;
            grid-template-columns: 120px 1fr 320px;
            gap: 18px;
            align-items: center;
        }

        .user-avatar {
            width: 120px;
            height: 120px;
            border-radius: 9999px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f3f4f6;
            box-shadow: 0 4px 12px rgba(2, 6, 23, 0.06);
        }

        .user-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .user-main {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .user-name {
            font-size: 1.25rem;
            font-weight: 700;
            color: #0f172a;
        }

        .user-meta {
            font-size: 0.95rem;
            color: #374151;
            display: grid;
            gap: 8px;
        }

        .badges {
            display: flex;
            gap: 8px;
            align-items: center;
            margin-top: 4px;
        }

        .badge {
            padding: 4px 10px;
            font-size: 0.75rem;
            border-radius: 9999px;
            font-weight: 700;
            color: #fff;
        }

        .badge.role {
            background: #374151;
        }

        .badge.active {
            background: #059669;
        }

        .badge.unverified {
            background: #ef4444;
        }

        .user-details {
            display: grid;
            gap: 8px;
            font-size: 0.95rem;
            color: #374151;
        }

        .muted {
            color: #6b7280;
            font-size: 0.9rem;
        }

        @media (max-width: 900px) {
            .user-card-body {
                grid-template-columns: 100px 1fr;
            }

            .user-details {
                grid-column: 1 / -1;
                order: 3;
            }
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

        #user-children_wrapper .dataTables_filter input {
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            padding: 8px 16px;
            outline: none;
            width: 250px;
            transition: all 0.3s;
        }

        #user-children_wrapper .dataTables_filter input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        #user-children {
            border-collapse: separate !important;
            border-spacing: 0 12px !important;
            width: 100% !important;
            border: none !important;
        }

        #user-children thead th {
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.05em;
            padding: 16px !important;
            border: none !important;
        }

        #user-children tbody tr {
            background: #ffffff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
            transition: all 0.3s;
        }

        #user-children tbody tr:hover {
            /* transform: scale(1.005); */
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            background: #fdfdfd;

        }

        #user-children tbody td {
            padding: 16px !important;
            border: none !important;
            vertical-align: middle;
            text-align: center;
        }

        #user-children tbody tr td:first-child {
            border-radius: 12px 0 0 12px;
            text-align: center;
        }

        #user-children tbody tr td:last-child {
            border-radius: 0 12px 12px 0;
            text-align: center;
        }

        .dataTables_paginate {
            margin-right: 10px !important;
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
    </style>
@endpush

@section('content')

    <div class="bg-white rounded p-[30px] mb-5 premium-card">
        <div class="user-card w-full">
            <div class="w-full">
                <div class="flex flex-row-reverse justify-between mb-4">
                    <div class="flex w-full" style="justify-content:space-between; align-items:center; margin-bottom:20px">
                        <h2 style="font-weight:800"></h2>
                        <div class="flex gap-2" style="justify-content:flex-end; align-items:center;">
                            <a href="{{ route('user.edit', $data->id) }}" class="btn-edit "
                                style="padding: 5px 15px; border-radius: 0.375rem;">Edit Profile</a>
                            <a href="{{ route('user.index') }}" class="btn-back"
                                style="padding: 5px 15px; border-radius: 0.375rem;">Back</a>
                        </div>
                    </div>
                    <div class="flex justify-start items-center gap-4">
                        <div class="user-avatar">
                            <img src="{{ $data->avatar ? asset($data->avatar) : asset('/backend/user.png') }}"
                                alt="User avatar">
                        </div>

                        <div class="user-main">
                            <div style="display:flex; flex-direction:column;">
                                <span class="user-name">{{ $data->name ?? 'N/A' }}</span>
                                <span class="muted">{{ $data->email ?? '' }}</span>
                            </div>

                            <div class="badges">
                                <span class="badge role">{{ $data->role ?? 'User' }}</span>
                                <span
                                    class="badge {{ $data->status == 'active' ? 'active' : 'inactive' }}">{{ $data->status == 'active' ? 'Active' : ucfirst($data->status ?? 'Inactive') }}</span>
                                @if (empty($data->email_verified_at))
                                    <span class="badge unverified">Unverified</span>
                                @endif
                            </div>

                            <div class="muted" style="margin-top:6px">{{ optional($data->profile)->bio ?? '' }}</div>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">

                    <div class="user-details">
                        <div><strong>Date of birth:</strong>
                            {{ \Carbon\Carbon::parse(optional($data->profile)->birth_date)->format('d F Y') ?? 'N/A' }}
                        </div>
                        <div><strong>Country:</strong> {{ optional($data->profile)->country ?? 'N/A' }}</div>
                        <div><strong>Joined:</strong> {{ \Carbon\Carbon::parse($data->created_at)->format('d M Y') }}</div>
                    </div>

                    <div class="user-details">
                        <div><strong>Role:</strong> {{ $data->role ?? 'user' }}</div>
                        <div><strong>Last Updated:</strong> {{ \Carbon\Carbon::parse($data->updated_at)->format('d M Y') }}
                        </div>
                        {{-- {{ dd($data->children[0]->name) }} --}}
                        <div>
                            @if ($data->profile?->parent_role != null)
                                <strong> {{ $data->profile->parent_role == 'father' ? 'Father' : 'Mother' }} of: </strong>
                                {{ count($data->children) }} Children
                            @else
                                <strong> Parent role : Not Submitted </strong>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="premium-card overflow-hidden mb-8">
        <div class="p-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
                <div>
                    <h2 class="text-xl font-bold text-slate-800">Children's</h2>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table id="user-children" class="w-full">
                    <thead>
                        <tr>
                            <th class="w-16">#</th>
                            <th class='text-center'>Children Name</th>
                            <th class='text-center'>Children Birth Date</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>


@endsection


@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="{{ asset('backend/js/datatables/jquery-3.7.0.js') }}"></script>

    <script src="{{ asset('backend/js/datatables/data-tables.min.js') }}"></script>
    <script src="{{ asset('backend/js/datatables/data-tables.tailwindcss.min.js') }}"></script>
    <!--buttons dataTables-->
    <script src="{{ asset('backend/js/datatables/datatables.buttons.min.js') }}"></script>
    <script src="{{ asset('backend/js/datatables/jszip.min.js') }}"></script>
    <script src="{{ asset('backend/js/datatables/pdfmake.min.js') }}"></script>
    <script src="{{ asset('backend/js/datatables/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('backend/js/datatables/buttons.print.min.js') }}"></script>

    {{-- <script src="{{ asset('backend/js/datatables/datatables.init.js') }}"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.0/dist/sweetalert2.all.min.js"
        integrity="sha256-BpyIV7Y3e2pnqy8TQGXxsmOiQ4jXNDTOTBGL2TEJeDY=" crossorigin="anonymous"></script>

    <script>
        $(document).ready(function() {
            $('#user-children').DataTable({
                processing: true,
                serverSide: true,
                order: [],
                ajax: {
                    url: "{{ route('user.show.children', $data->id) }}",
                    type: 'GET'
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
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'birth_date',
                        name: 'birth_date',
                        orderable: false,
                        searchable: false
                    }
                ],

            });
        });
    </script>
@endpush
