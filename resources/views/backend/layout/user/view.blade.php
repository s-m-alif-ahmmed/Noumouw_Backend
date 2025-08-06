@extends('backend.app')

@section('title', 'User Details')
@section('title_url')
    <a href="{{ route('user.index') }}">User Details</a>
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
            position: relative;
            /* Enable absolute positioning for children */
            display: flex;
            gap: 20px;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .back-button {
            position: absolute;
            /* Position the button at top-right */
            top: 20px;
            right: 20px;
        }

        .back-button:hover {
            background-color: #4a5568;
            /* Darker shade on hover */
        }

        .thumbnail-container {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .thumbnail {
            width: 100%;
            max-width: 350px;
            height: auto;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
        }

        .course-info {
            flex: 2;
            display: flex;
            flex-direction: column;
            gap: 12px;
            justify-content: center;
        }

        .course-title {
            font-size: 1.75rem;
            font-weight: 600;
            color: #333;
        }

        .course-description {
            font-size: 1rem;
            color: #555;
            line-height: 1.6;
        }

        .status-container {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .status-label {
            font-weight: bold;
            color: #333;
        }

        .status-badge {
            padding: 4px 16px;
            font-size: 0.9rem;
            font-weight: 600;
            color: #fff;
            border-radius: 20px;
            background-color: #e2e8f0;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .status-badge.active {
            background-color: #48bb78;
            /* Green for Active */
        }

        .status-badge.inactive {
            background-color: #f56565;
            /* Red for Inactive */
        }

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
    </style>
@endpush

@section('content')

    <div class="bg-white rounded py-5">
        <div class="card-container">
            {{-- Back Button at Top Right --}}
            <a href="{{ route('user.index') }}"
                class="back-button text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600
                    focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100
                     dark:ring-custom-400/20">
                Back
            </a>

            {{-- Thumbnail Section --}}
            <div class="thumbnail-container">
                <img class="thumbnail"
                    src="{{ $data->thumbnail ? asset($data->thumbnail) : asset('/backend/user.png') }}"
                    alt="Course Thumbnail">
            </div>

            {{-- Course Information Section --}}
            <div class="course-info">
                {{-- Course Name --}}
                <h2 class="course-title"><strong>User Name: </strong>{{ $data->name ?? 'N/A' }}</h2>
                <p><strong>Parent Role: </strong>{{ $data->profile->parent_role ?? 'N/A' }}</p>
                <p><strong>Parent Birth Date: </strong>{{ \Carbon\Carbon::parse(optional($data->profile)->birth_date)->format('d, M Y') ?? 'N/A' }}</p>
                <p><strong>Country: </strong>{{ $data->profile->country ?? 'N/A' }}</p>
                <p><strong>Parent Role: </strong>{{ $data->profile->parent_role ?? 'N/A' }}</p>

                {{-- Course Status --}}
                <div class="status-container">
                    <span class="status-label">Status:</span>
                    <span class="status-badge {{ $data->status == 'active' ? 'active' : 'inactive' }}">
                        {{ $data->status == 'active' ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>
        </div>
    </div>


    <div class="w-full p-5 mt-8 shadow-2xl">
        <h2 class="text-xl py-2">Children's</h2>
        <table id="user-children" class="display stripe group table-responsive">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Children Name</th>
                    <th>Children Birth Date</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
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
                        orderable: true,
                        searchable: false
                    },
                    {
                        data: 'birth_date',
                        name: 'birth_date',
                        orderable: true,
                        searchable: true
                    }
                ],

            });
        });
    </script>
@endpush
