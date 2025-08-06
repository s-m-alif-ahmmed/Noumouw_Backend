@extends('backend.app')

{{-- Title for the News Dashboard --}}
@section('title', 'Question')
@section('title_url')
    <a href="{{ route('question.index') }}">Question</a>
@endsection
@section('tabName')
    <a href="{{ route('dashboard') }}">Home</a>
@endsection

{{-- Push additional styles if needed --}}
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.0/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="{{ asset('vendor/flasher/flasher.min.css') }}" rel="stylesheet">
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

{{-- Main content of the News Dashboard page --}}
@section('content')

    <div class="mx-auto py-6 px-4">
        <div class="flex flex-col lg:flex-row gap-6">

            <!-- Search/Filter Section -->
            <div class="bg-white p-6 shadow-lg rounded-lg w-full lg:w-1/5">
                <h3 class="text-2xl pb-2 font-semibold">Filter By Evaluation</h3>
                <form id="filterForm">
                    <!-- Filter Options -->
                    <div class="mb-4">
                        <select id="status" name="evaluation"
                                class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                            <option selected disabled value="">Select An Evaluation</option>
                            @foreach ($data as $evaluation)
                                <option value="{{ $evaluation->id }}">{{ $evaluation->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Submit Button -->
                    <div class="mt-6 text-end">
                        <button type="submit"
                                class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 w-1/6 lg:w-auto">
                            Apply Filters
                        </button>
                    </div>
                </form>
            </div>

            <!-- DataTable Section -->
            <div class="bg-white p-6 shadow-lg rounded-lg w-full lg:w-4/5">
                <div class="flex justify-between items-center mb-4 flex-wrap gap-2">
                    <h3 class="text-2xl font-semibold">Questions</h3>
                    <button data-modal-open="create-question"
                            class="text-white bg-custom-500 px-4 py-2 rounded-md hover:bg-custom-600 focus:ring focus:ring-custom-100">
                        Add Evaluation
                    </button>
                </div>

                <div class="overflow-auto">
                    <table id="basic_tables" class="min-w-full border border-gray-300 rounded-lg text-sm">
                        <thead>
                        <tr class="bg-gray-100 text-left">
                            <th class="p-2 border">#</th>
                            <th class="p-2 border">Title</th>
                            <th class="p-2 border">Answer</th>
                            <th class="p-2 border">Link</th>
                            <th class="p-2 border">Evaluation Title</th>
                            <th class="p-2 border">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <!-- Dynamic Data Rows -->
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>


    {{-- Create modal --}}
    <x-backend.modal id="create-question" class="w-full md:w-1/2 max-w-4xl" title="Add Question">
        <form id="create-form">
            @csrf
            <div class="grid gap-4 grid-cols-1 md:grid-cols-2">
                {{-- Name Input Field --}}
                <div class="col-span-2">
                    <x-backend.text-area input-ajax :ajax="true" name="title" label="Title" :required="true"
                        placeholder="Enter Question here.."></x-backend.text-area>
                    @error('title')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-span-2">
                    <x-backend.input-ajax input-ajax :ajax="true" name="link" label="URL" :required="true"
                        placeholder="Url" />
                    @error('link')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Answer Selection --}}
                <div class="col-span-2 sm:col-span-1 mt-1">
                    <label for="answer" class="block text-lg font-medium text-gray-600">Answer</label>
                    <select name="answer" id="answer" class="w-full rounded-md shadow-sm border-gray-300">
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                    @error('answer')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Evaluation Selection --}}
                <div class="col-span-2 sm:col-span-1">
                    <x-backend.select2-single name="evaluation_id" label="Evaluation" :required="true">
                        <option selected disabled label="Select an Evaluation"></option>
                        @foreach ($data as $evaluation)
                            <option value="{{ $evaluation->id }}">
                                {{ $evaluation->title }}
                            </option>
                        @endforeach
                    </x-backend.select2-single>
                    @error('evaluation_id')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="justify-end mt-5">
                    <button type="submit"
                        class="px-6 py-2 text-white bg-custom-500 rounded-md shadow-sm border-custom-500 hover:bg-custom-600 focus:ring focus:ring-custom-100 active:bg-custom-700">
                        Add Question
                    </button>
                </div>
            </div>
        </form>
    </x-backend.modal>

    {{-- Edit modal --}}
    <x-backend.modal id="edit-question" class="w-full md:w-1/2 max-w-4xl" title="Edit Question">
        <form id="edit-form">
            @csrf
            <input type="hidden" name="id">

            <div class="grid gap-4 grid-cols-1 md:grid-cols-2">
                {{-- Title Input Field --}}
                <div class="col-span-2">
                    <x-backend.text-area input-ajax :ajax="true" name="title" label="Title" :required="true"
                        placeholder="Enter Question here.."></x-backend.text-area>
                    @error('title')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                {{-- URL Field --}}
                <div class="col-span-2">
                    <x-backend.input-ajax input-ajax :ajax="true" name="link" label="URL" :required="true"
                        placeholder="Url" />
                    @error('link')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Answer Selection --}}
                <div class="col-span-1">
                    <label for="answer" class="block text-lg font-medium text-gray-700">Answer</label>
                    <select name="answer" id="answer" class="w-full rounded-md shadow-sm border-gray-300">
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                    @error('answer')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Evaluation Selection --}}
                <div class="col-span-1">
                    <x-backend.select2-single name="evaluation_id" label="Evaluation" :required="true">
                        <optgroup label="Select an Evaluation">
                            @foreach ($data as $evaluation)
                                <option value="{{ $evaluation->id }}">{{ $evaluation->title }}</option>
                            @endforeach
                        </optgroup>
                    </x-backend.select2-single>
                    @error('evaluation_id')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="flex justify-end mt-6">
                <button type="submit"
                    class="px-6 py-2 text-white bg-custom-500 rounded-md shadow-sm border-custom-500 hover:bg-custom-600 focus:ring focus:ring-custom-100 active:bg-custom-700">
                    Update Question
                </button>
            </div>
        </form>
    </x-backend.modal>

@endsection

{{-- Push additional scripts if needed --}}
@push('scripts')
    <script src="{{ asset('backend/js/datatables/jquery-3.7.0.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="{{ asset('backend/js/datatables/data-tables.min.js') }}"></script>
    <script src="{{ asset('backend/js/datatables/data-tables.tailwindcss.min.js') }}"></script>
    <!--buttons dataTables-->
    <script src="{{ asset('backend/js/datatables/datatables.buttons.min.js') }}"></script>
    <script src="{{ asset('backend/js/datatables/jszip.min.js') }}"></script>
    <script src="{{ asset('backend/js/datatables/pdfmake.min.js') }}"></script>
    <script src="{{ asset('backend/js/datatables/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('backend/js/datatables/buttons.print.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


    {{-- <script src="{{ asset('backend/js/datatables/datatables.init.js') }}"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.0/dist/sweetalert2.all.min.js"
        integrity="sha256-BpyIV7Y3e2pnqy8TQGXxsmOiQ4jXNDTOTBGL2TEJeDY=" crossorigin="anonymous"></script>
    <script src="{{ asset('vendor/flasher/flasher.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            let url = "{{ route('question.index') }}";
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
                    data: function(d) {
                        d.evaluation = $('#status').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'title',
                        name: 'title',
                        orderable: true,
                        searchable: true,
                        render: function(data, type, row) {
                            return data.length > 20 ? data.substring(0, 20) + '...' : data;
                        }
                    },
                    {
                        data: 'answer',
                        name: 'answer',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'link',
                        name: 'link',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return data.length > 20 ? data.substring(0, 20) + '...' : data;
                        }
                    },
                    {
                        data: 'evaluation_title',
                        name: 'evaluation_title',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return data.length > 20 ? data.substring(0, 20) + '...' : data;
                        }
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            $('#filterForm').on('submit', function(e) {
                e.preventDefault();
                dTable.draw();
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
            let url = '{{ route('question.destroy', ':id') }}';
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


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('#create-form').on('submit', function(e) {
                NProgress.start();
                e.preventDefault();
                let formData = $(this).serialize();
                let url = "{{ route('question.store') }}";
                $.ajax({
                    url,
                    method: 'POST',
                    data: formData,
                    success: function(resp) {
                        NProgress.done();
                        // Reload DataTable
                        $('#basic_tables').DataTable().ajax.reload();
                        if (resp.success === true) {
                            // show toast message
                            flasher.success(resp.message);
                            clearModal('create-question')
                        } else if (resp.errors) {
                            flasher.error(resp.errors[0]);
                        } else {
                            flasher.error(resp.message);
                        }
                    },
                    error: function(xhr) {
                        NProgress.done();
                        handleXhrErrors(xhr, 'create-question')
                    }
                });
            });



            //edit Message
            $('body').on('click', '.edit', function() {
                var id = $(this).data('id');

                var url = "{{ route('question.edit', ':id') }}".replace(':id', id);


                $.ajax({
                    url: url,
                    type: 'get',
                    success: function(data) {
                        NProgress.done();
                        if (data.success) {
                            console.log('ok')
                            openEditModalById('edit-question', data.data)
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
                clearError('edit-question')
                e.preventDefault();
                let formData = new FormData(this);
                formData.append('_token', '{{ csrf_token() }}');
                let url = "{{ route('question.update', ':id') }}".replace(':id', $(this).find(
                    "input[name='id']").val());
                $.ajax({
                    url,
                    method: 'POST',
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: formData,
                    success: function(resp) {
                        // Reload DataTable
                        $('#basic_tables').DataTable().ajax.reload();
                        if (resp.success === true) {
                            // show toast message
                            flasher.success(resp.message);
                            clearModal('edit-question')
                        } else if (resp.errors) {
                            flasher.error(resp.errors[0]);
                        } else {
                            flasher.error(resp.message);
                        }
                    },
                    error: function(xhr) {
                        handleXhrErrors(xhr, 'edit-question')
                    }
                });
            });
        });
    </script>
@endpush
