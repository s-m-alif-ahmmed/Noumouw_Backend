@extends('backend.app')

{{-- Title for the News Dashboard --}}
@section('title', 'Instructor')
@section('title_url')
    <a href="{{ route('instructor.index') }}">Instructor</a>
@endsection
@section('tabName')
    <a href="{{ route('dashboard') }}">Home</a>
@endsection

{{-- Push additional styles if needed --}}
@push('styles')
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@24.7.0/build/css/intlTelInput.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.0/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="{{ asset('vendor/flasher/flasher.min.css') }}" rel="stylesheet">
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
            font-size: 18px;
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

        .iti.iti--allow-dropdown {
            width: 100% !important;
        }
    </style>
@endpush

{{-- Main content of the News Dashboard page --}}
@section('content')
    <div class="card">
        <div class="card-body max-sm:overflow-scroll">
            <div class="flex justify-end mb-6">
                <button data-modal-open="create-instructor"
                    class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 focus:ring
                 focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">Add
                    Instructor</button>
            </div>
            <table id="basic_tables" class="display stripe group table-responsive">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Avatar</th>
                        <th>Bio</th>
                        <th>Phone</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Create modal --}}
    <x-backend.modal id="create-instructor" class="w-full md:w-1/2 max-w-4xl" title="Add Message">
        <form id="create-form" enctype="multipart/form-data">
            @csrf @method('POST')
            <div class="grid grid-cols-1 gap-4 xl:grid-cols-12">

                {{-- ------------------- Name Input Field ------------- --}}
                <div class="xl:col-span-6">
                    <x-backend.input-ajax type="text" name="name" label="Name" :required="true"
                        placeholder="Enter Name here" />
                </div>

                {{-- ------------------- Email Input Field ------------- --}}
                <div class="xl:col-span-6">
                    <x-backend.input-ajax type="email" name="email" label="Email" :required="true"
                        placeholder="Enter Email here" />
                </div>

                {{-- ------------------- Phone Input Field ------------- --}}
                <div class="xl:col-span-6">
                    <x-backend.input-ajax type="text" name="phone" label="Phone" id="phone" :required="true"
                                          placeholder="" />
                </div>

                {{-- ------------------- Country Input Field ------------- --}}
                <div class="xl:col-span-6">
                    <x-backend.input-ajax type="text" name="country" label="Country" :required="true"
                                          placeholder="Enter Country here" />
                </div>

                {{-- ------------------- Address Input Field ------------- --}}
                <div class="xl:col-span-6">
                    <x-backend.input-ajax type="text" name="address" label="Address" :required="true"
                                          placeholder="Enter Address here" />
                </div>

                {{-- ------------------- Role Input Field ------------- --}}
                <div class="xl:col-span-6">
                    <x-backend.input-ajax type="text" name="role" label="Role" :required="true"
                                          placeholder="Enter Role here" />
                </div>

                {{-- ------------------- Designation Input Field ------------- --}}
                <div class="xl:col-span-6">
                    <x-backend.input-ajax type="text" name="designation" label="Designation" :required="true"
                                          placeholder="Enter Designation here" />
                </div>

                {{-- ------------------- Services Input Field ------------- --}}
                <div class="xl:col-span-6">
                    <x-backend.input-ajax type="text" name="services" label="Services" :required="true"
                                          placeholder="Enter Services here" />
                </div>

                {{-- ------------------- Bio Text Area Field ------------- --}}
                <div class="xl:col-span-12">
                    <x-backend.text-area :ajax="true" name="bio" label="Bio" :required="true"
                        placeholder="Enter Bio here"></x-backend.text-area>
                </div>

                {{-- ------------------- Avatar File Upload Field ------------- --}}
                <div class="xl:col-span-12">
                    <x-backend.dropify type="file" name="avatar" label="Avatar" :required="true" accept="image/*" />
                </div>
            </div>

            <div class="flex justify-end gap-2 mt-4">
                <button type="submit"
                    class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600
                    focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">
                    Add Instructor
                </button>
            </div>
        </form>

    </x-backend.modal>

    {{-- edit modal --}}
    <x-backend.modal id="edit-instructor" title="Edit Message">
        <form id="edit-form" enctype="multipart/form-data">

            @csrf
            @method('POST')
            <input type="hidden" name="id">
            {{-- <input type="hidden" name="_token" value="{{ csrf_token() }}"> --}}
            <div class="grid grid-cols-1 gap-4 xl:grid-cols-12">

                {{-- ------------------- Name Input Field ------------- --}}
                <div class="xl:col-span-6">
                    <x-backend.input-ajax type="text" name="name" label="Name" :required="true"
                        placeholder="Enter name here" />
                </div>

                {{-- ------------------- Email Input Field ------------- --}}
                <div class="xl:col-span-6">
                    <x-backend.input-ajax type="email" name="email" label="Email" :required="true"
                        placeholder="Enter email here" />
                </div>

                {{-- ------------------- Phone Input Field ------------- --}}
                <div class="xl:col-span-6">
                    <x-backend.input-ajax type="text" id="edit-phone" name="phone" label="Phone" :required="true" />
                </div>

                {{-- ------------------- Country Input Field ------------- --}}
                <div class="xl:col-span-6">
                    <x-backend.input-ajax type="text" name="country" label="Country" :required="true"
                        placeholder="Enter country here" />
                </div>

                {{-- ------------------- Address Input Field ------------- --}}
                <div class="xl:col-span-6">
                    <x-backend.input-ajax type="text" name="address" label="Address" :required="true"
                        placeholder="Enter address here" />
                </div>

                {{-- ------------------- Role Input Field ------------- --}}
                <div class="xl:col-span-6">
                    <x-backend.input-ajax type="text" name="role" label="Role" :required="true"
                        placeholder="Enter role here" />
                </div>

                {{-- ------------------- Designation Input Field ------------- --}}
                <div class="xl:col-span-6">
                    <x-backend.input-ajax type="text" name="designation" label="Designation" :required="true"
                        placeholder="Enter designation here" />
                </div>

                {{-- ------------------- Services Input Field ------------- --}}
                <div class="xl:col-span-6">
                    <x-backend.input-ajax type="text" name="services" label="Services" :required="true"
                        placeholder="Enter services here" />
                </div>

                {{-- ------------------- Bio Input Field ------------- --}}
                <div class="xl:col-span-12">
                    <x-backend.text-area input-ajax :ajax="true" name="bio" label="Bio" :required="true"
                        placeholder="Enter Bio here"> </x-backend.text-area>
                </div>

                {{-- ------------------- Avatar Input Feild ------------- --}}
                <div class="xl:col-span-12">
                    <x-backend.dropify type="file" :ajax="true" name="avatar" label="Avatar" :required="true" />
                </div>

            </div>
            <div class="flex justify-end gap-2 mt-4">
                <button type="submit"
                    class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600
                     focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">Update
                    Instructor</button>
            </div>
        </form>
    </x-backend.modal>


@endsection

{{-- Push additional scripts if needed --}}
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@24.6.0/build/js/intlTelInput.min.js"></script>
    {{-- <script src="{{ asset('backend/js/datatables/datatables.init.js') }}"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.0/dist/sweetalert2.all.min.js"
        integrity="sha256-BpyIV7Y3e2pnqy8TQGXxsmOiQ4jXNDTOTBGL2TEJeDY=" crossorigin="anonymous"></script>
    <script src="{{ asset('vendor/flasher/flasher.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            let url = "{{ route('instructor.index') }}";
            let dTable = $('#basic_tables').DataTable({
                order: [],
                destroy: true,
                lengthMenu: [
                    [25, 50, 100, 200, 500, -1],
                    [25, 50, 100, 200, 500, "All"]
                ],
                processing: true,
                responsive: true,
                serverSide: true,
                language: {
                    processing: `<div class="text-center">
                    <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                    <span class="visually-hidden">Loading...</span>
                  </div>
                    </div>`
                },
                scroller: {
                    loadingIndicator: false
                },
                dom: "<'row justify-content-between table-topbar'<'col-md-2 col-sm-4 px-0'l><'col-md-2 col-sm-4 px-0'f>>tipr",
                ajax: {
                    url: url,
                    type: "get",
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
                        searchable: true,
                        render: function(data, type, row) {
                            if (data.length > 100) {
                                return data.substring(0, 100) + '...';
                            } else {
                                return data;
                            }
                        }
                    },
                    {
                        data: 'avatar',
                        name: 'avatar',
                        orderable: true,
                        searchable: false,
                    },
                    {
                        data: 'bio',
                        name: 'bio',
                        orderable: true,
                        searchable: false,
                        render: function(data, type, row) {
                            if (data.length > 100) {
                                return data.substring(0, 100) + '...';
                            } else {
                                return data;
                            }
                        }
                    },

                    {
                        data: 'phone',
                        name: 'phone',
                        orderable: true,
                        searchable: false,
                    },

                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                initComplete: function() {
                    initOpenModal()
                }

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
            let url = '{{ route('instructor.destroy', ':id') }}';
            $.ajax({
                type: "DELETE",
                url: url.replace(':id', id),
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
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


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.querySelector("#phone");
            const edit_input = document.querySelector("#edit-phone");
            let phoneInput = window.intlTelInput(input, {
                loadUtilsOnInit: "https://cdn.jsdelivr.net/npm/intl-tel-input@24.6.0/build/js/utils.js",
                initialCountry: "US",
            });
            let editPhoneInput = window.intlTelInput(edit_input, {
                loadUtilsOnInit: "https://cdn.jsdelivr.net/npm/intl-tel-input@24.6.0/build/js/utils.js",
            });
            $('#create-form').on('submit', function(e) {
                clearError('create-instructor')
                e.preventDefault();
                document.querySelector("#phone").value = phoneInput.getNumber();
                const formElement = document.querySelector("#create-form");
                let formData = new FormData(formElement);
                let url = "{{ route('instructor.store') }}";
                $.ajax({
                    url,
                    method: 'POST',
                    cache: false,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    data: formData,
                    success: function(resp) {
                        // Reload DataTable
                        $('#basic_tables').DataTable().ajax.reload();
                        if (resp.success === true) {
                            // show toast message
                            flasher.success(resp.message);
                            clearModal('create-instructor')
                        } else if (resp.errors) {
                            flasher.error(resp.errors[0]);
                        } else {
                            flasher.error(resp.message);
                        }
                    },
                    error: function(xhr) {
                        handleXhrErrors(xhr, 'create-instructor')
                    }
                });
            });


            //edit Message
            $('body').on('click', '.edit', function() {
                var id = $(this).data('id');
                var url = "{{ route('instructor.edit', ':id') }}".replace(':id', id);
                $.ajax({
                    url: url,
                    type: 'get',
                    success: function(data) {
                        if (data.success) {
                            openEditModalById('edit-instructor', data.data)
                        } else {
                            flasher.error('Somethings went wrong. Try again later.')
                        }
                    },
                    error: function(errors) {
                        flasher.error(error.responseJSON.message);
                    }
                })
            });

            $('#edit-form').on('submit', function(e) {
                clearError('edit-instructor')
                e.preventDefault();
                document.querySelector("#edit-phone").value = editPhoneInput.getNumber();
                let formData = new FormData(this);
                formData.append('_token', '{{ csrf_token() }}');
                let url = "{{ route('instructor.update', ':id') }}".replace(':id', $(this).find(
                    "input[name='id']").val());
                $.ajax({
                    url,
                    method: 'POST',
                    cache: false,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    data: formData,
                    success: function(resp) {
                        // Reload DataTable
                        $('#basic_tables').DataTable().ajax.reload();
                        if (resp.success === true) {
                            // show toast message
                            flasher.success(resp.message);
                            clearModal('edit-instructor')
                        } else if (resp.errors) {
                            flasher.error(resp.errors[0]);
                        } else {
                            flasher.error(resp.message);
                        }
                    },
                    error: function(xhr) {
                        handleXhrErrors(xhr, 'edit-instructor')
                    }
                });
            });
        });
    </script>

    <script type="text/javascript" src="https://jeremyfagis.github.io/dropify/dist/js/dropify.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.dropify').dropify();
        })
    </script>
@endpush
