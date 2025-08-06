@extends('backend.app')
@section('title', 'PushNotification')
@section('content')
    @push('styles')
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.css">
        <link href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css" rel="stylesheet">
        <style>

.dropify-wrapper {
            height: 200px;
        }

        .dropify-wrapper .dropify-preview .dropify-render img {
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        .dropify-wrapper .dropify-message .file-icon p {
            font-size: 30px;
        }

            .dataTables_wrapper .dataTables_length select {
                 width: 66px;
            }
        </style>
    @endpush



    <div class="container mx-auto py-8">
        <div class="flex gap-x-5">
            <div class="bg-white p-6 shadow-lg rounded-lg col-span-3 w-5/12">
                <form method="POST" action="{{ route('send.notification') }}" enctype="multipart/form-data">
                    <div class="flex justify-end gap-x-2">
                       <label for="for_all_users"><input type="radio" name="user_selection_type" value="all" id="for_all_users"> All Users</label>
                        <label for="for_specific_users"><input type="radio" checked  name="user_selection_type" value="specific" id="for_specific_users"> Specific Users</label>
                    </div>
                    <h3 class="text-2xl font-semibold mb-6">Send Notification</h3>
                    @csrf
                    <input type="hidden" name="user_ids[]" id="user_ids">
                    <div class="mb-4">
                        <x-backend.input type="text" name="title" label="Title" :required="true" />
                    </div>

                    <div class="mb-4">
                        <x-backend.text-area name="description" label="Description" :required="true" />
                    </div>

                    <div class="mb-4">
                       <x-backend.dropify name="image" label="Image" :required="false" />
                    </div>

                    <div class="mt-10">
                        <button type="submit"
                            class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Send
                            Notification</button>
                    </div>
                </form>
            </div>
            <div class="bg-white p-6 shadow-lg rounded-lg col-span-2 w-7/12" id="user_selection">
                <div class="flex justify-between items-center mb-4">
                    <button type="button" class="text-white bg-gradient-to-r from-green-400 via-green-500 to-green-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-green-300
                        dark:focus:ring-green-800 shadow-lg shadow-green-500/50 dark:shadow-lg dark:shadow-green-800/80 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2"
                        id="select-all">Select All</button>
                    <button type="button" class="text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 shadow-lg
                                shadow-red-500/50 dark:shadow-lg dark:shadow-red-800/80 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2"
                        id="deselect-all">Deselect All</button>
                </div>

                <div id="user-list">
                    <table id="userTable">
                        <thead>
                            <tr>
                                <th>Avatar</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>




@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('backend/js/datatables/datatables.buttons.min.js') }}"></script>
    <script src="{{ asset('backend/js/datatables/jszip.min.js') }}"></script>
    <script src="{{ asset('backend/js/datatables/pdfmake.min.js') }}"></script>
    <script src="{{ asset('backend/js/datatables/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('backend/js/datatables/buttons.print.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.0/dist/sweetalert2.all.min.js"></script>

    <script>
        $(document).ready(function() {
            let table = $('#userTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('push-notification.index') }}",
                columns: [{
                        data: 'avatar',
                        name: 'avatar',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                dom: "<'top'fl>rt<'bottom'p>",
                drawCallback: function() {
                    $('#select-all').on('click', function() {
                        $('.user-checkbox').prop('checked', true);
                    });
                    $('#deselect-all').on('click', function() {
                        $('.user-checkbox').prop('checked', false);
                    });
                },
            });


            $('form').on('submit', function(e) {
                if($("input[name='user_selection_type']:checked").val() !== 'all'){
                    let isAnyUserSelected = $('.user-checkbox:checked').length > 0;

                    if (!isAnyUserSelected) {

                        e.preventDefault();
                        Swal.fire({
                            icon: 'warning',
                            title: 'No User Selected',
                            text: 'Please select at least one user to send the notification.',
                            confirmButtonText: 'OK'
                        });
                    }
                }
            });

            //for toggle user selection
            // Run the function initially
            toggleUserSelection();

            // Attach event listener for changes
            $("input[name='user_selection_type']").change(function() {
                toggleUserSelection();
            });

            // Function to toggle visibility of #user_selection
            function toggleUserSelection() {
                if ($("input[name='user_selection_type']:checked").val() === 'all') {
                    $("#user_selection").hide();
                } else {
                    $("#user_selection").show();
                }
            }
        });
    </script>

    <script type="text/javascript" src="https://jeremyfagis.github.io/dropify/dist/js/dropify.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.dropify').dropify();
        })
    </script>
@endpush
