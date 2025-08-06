@extends('backend.app')

@section('title', 'subscription')
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
    <div class="card">
        <div class="card-body max-sm:overflow-scroll">
            <div class="flex justify-end mb-6">
                <button data-modal-open="add-subscription-plan"  class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 focus:ring
                    focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">Add Subscription Plan</button>
            </div>

            <table id="basic_tables" class="display stripe group table-responsive">
                <thead>
                    <tr class="m-auto">
                        <th>#</th>
                        <th>Name</th>
                        <th>Duration</th>
                        <th>Price</th>
                        <th>Revenue Cart Product ID</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Create Subscription Plan --}}
    <x-backend.modal id="add-subscription-plan" class="w-full md:w-1/2 max-w-6xl justify-center" title="Create Subscription Plan">
        <form id="subscription-plan-form" enctype="multipart/form-data">
            @csrf @method('POST')
            <div class="grid grid-cols-1 gap-4 xl:grid-cols-12">

                {{--------------------- Subscription Name ---------------}}
                <div class="xl:col-span-12">
                    <x-backend.input type="text" name="name" label="Subscription Name" :required="true" />
                </div>

                {{--------------------- Subscription Price ---------------}}
                <div class="xl:col-span-12">
                    <x-backend.input type="number" name="price" label="Price" :required="true" />
                </div>

                {{--------------------- Subscription Plan Type ---------------}}
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
                </div>

                {{--------------------- Subscription Revenue Cart Product ID ---------------}}
                <div class="xl:col-span-12">
                    <x-backend.input type="text" name="revenue_cart_product_id"  label="Revenue Cart Product ID" :required="true" />
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
                    url: "{{ route('subscription.index') }}",
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
                        data: 'duration',
                        name: 'duration',
                        orderable: true,
                        searchable: false,
                    },
                    {
                        data: 'price',
                        name: 'price',
                        orderable: true,
                        searchable: false,
                    },
                    {
                        data: 'revenue_cart_product_id',
                        name: 'revenue_cart_product_id',
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
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
            });
        });

        // Create Subscription
        $(function () {
            $('#subscription-plan-form').on('submit', function (e) {
                e.preventDefault();

                console.log('Submitting Form...'); // ✅ Check if this logs

                let formData    = new FormData(this);
                let url         = "{{ route('subscription.store') }}";

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
                    success: function (resp) {
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
                    error: function (xhr) {
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
