@extends('backend.app')

{{-- Title for the News Dashboard --}}
@section('title', 'Evaluation')
@section('title_url')
    <a href="{{ route('evaluation.index') }}">Evaluation</a>
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

    <div class="card">
        <div class="card-body max-sm:overflow-scroll">
            <div class="flex justify-end mb-6">
                <a href="{{ route('evaluation.create') }}">
                    <button data-modal-open="create-evaluation"
                            class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 focus:ring
             focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">Add
                        Evaluation</button>
                </a>
            </div>
            <table id="basic_tables" class="display stripe group table-responsive">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Evaluation Title</th>
                        <th>Course Name</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Dynamic Data --}}
               </tbody>
            </table>
        </div>
    </div>


{{--     Create modal --}}

{{--    <x-backend.modal id="create-evaluation" title="Add Evaluation">--}}

{{--        <form id="create-form">--}}
{{--            @csrf--}}
{{--            <div class="grid grid-cols-1 gap-4 xl:grid-cols-12">--}}
{{--                --}}{{-- ------------------- Name Input Field ------------- --}}
{{--                <div class="xl:col-span-12">--}}
{{--                    <x-backend.input-ajax type="text" name="title" label="Title" :required="true"--}}
{{--                        placeholder="Enter title here" />--}}
{{--                </div>--}}
{{--                <div class="xl:col-span-12">--}}
{{--                    <x-backend.select2-single name="course_id" label="Course Name" :required="true">--}}
{{--                        <option selected disabled value="">Select a Course</option>--}}
{{--                        @foreach ($courses as $course)--}}
{{--                            <option value="{{ $course->id }}" @if (old('course_id') == $course->id) selected @endif>--}}
{{--                                {{ $course->name }}--}}
{{--                            </option>--}}
{{--                        @endforeach--}}
{{--                    </x-backend.select2-single>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <div class="flex justify-end gap-2 mt-4">--}}
{{--                <button type="submit"--}}
{{--                    class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600--}}
{{--                        focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">Add--}}
{{--                    Evaluation</button>--}}
{{--            </div>--}}
{{--        </form>--}}


{{--    </x-backend.modal>--}}

    {{-- edit modal --}}
{{--    <x-backend.modal id="edit-evaluation" title="Edit Title">--}}
{{--        <form id="edit-form">--}}
{{--            @csrf--}}
{{--            <input type="hidden" name="id">--}}
{{--            <div class="grid grid-cols-1 gap-4 xl:grid-cols-12">--}}
{{--                <div class="xl:col-span-12">--}}
{{--                    <x-backend.input-ajax type="text" name="title" id="edit-description" label="Title"--}}
{{--                        :required="true" placeholder="Enter Title here" />--}}
{{--                </div>--}}

{{--                <div class="xl:col-span-12">--}}
{{--                    <x-backend.select2-single name="course_id" label="Course Name" :required="true">--}}
{{--                        <option selected disabled value="">Select a Course</option>--}}
{{--                        @foreach ($courses as $course)--}}
{{--                            <option value="{{ $course->id }}">--}}
{{--                                {{ $course->name }}--}}
{{--                            </option>--}}
{{--                        @endforeach--}}
{{--                    </x-backend.select2-single>--}}
{{--                </div>--}}

{{--            </div>--}}
{{--            <div class="flex justify-end gap-2 mt-4">--}}
{{--                <button type="submit"--}}
{{--                    class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600--}}
{{--                     focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">Update--}}
{{--                    Evaluation</button>--}}
{{--            </div>--}}
{{--        </form>--}}
{{--    </x-backend.modal>--}}


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


    {{-- <script src="{{ asset('backend/js/datatables/datatables.init.js') }}"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.0/dist/sweetalert2.all.min.js"
        integrity="sha256-BpyIV7Y3e2pnqy8TQGXxsmOiQ4jXNDTOTBGL2TEJeDY=" crossorigin="anonymous"></script>
    <script src="{{ asset('vendor/flasher/flasher.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            let url = "{{ route('evaluation.index') }}";
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
                        data: 'title',
                        name: 'title',
                        orderable: true,
                        searchable: true,
                        render: function(data, type, row) {
                            if (data.length > 200) {
                                return data.substring(0, 200) + '...';
                            } else {
                                return data;
                            }
                        }
                    },
                    {
                        data: 'course_name',
                        name: 'course_name',
                        orderable: true,
                        searchable: true,
                        render: function(data, type, row) {
                            if (data.length > 200) {
                                return data.substring(0, 200) + '...';
                            } else {
                                return data;
                            }
                        }
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
                        searchable: false
                    },
                ],
                initComplete: function() {
                    initOpenModal()
                }

            });
        });



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
            let url = '{{ route('evaluation.status', ':id') }}';
            let csrfToken = '{{ csrf_token() }}';
            $.ajax({
                type: "POST",
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
            let url = '{{ route('evaluation.destroy', ':id') }}';
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
        // document.addEventListener('DOMContentLoaded', function() {
            {{--$('#create-form').on('submit', function(e) {--}}
            {{--    NProgress.start();--}}
            {{--    e.preventDefault();--}}
            {{--    let formData = $(this).serialize();--}}
            {{--    let url = "{{ route('evaluation.store') }}";--}}
            {{--    $.ajax({--}}
            {{--        url,--}}
            {{--        method: 'POST',--}}
            {{--        data: formData,--}}
            {{--        success: function(resp) {--}}
            {{--            NProgress.done();--}}
            {{--            // Reload DataTable--}}
            {{--            $('#basic_tables').DataTable().ajax.reload();--}}
            {{--            if (resp.success === true) {--}}
            {{--                // show toast message--}}
            {{--                flasher.success(resp.message);--}}
            {{--                clearModal('create-evaluation')--}}
            {{--            } else if (resp.errors) {--}}
            {{--                flasher.error(resp.errors[0]);--}}
            {{--            } else {--}}
            {{--                flasher.error(resp.message);--}}
            {{--            }--}}
            {{--        },--}}
            {{--        error: function(xhr) {--}}
            {{--            NProgress.done();--}}
            {{--            handleXhrErrors(xhr, 'create-evaluation')--}}
            {{--        }--}}
            {{--    });--}}
            {{--});--}}

        {{--    //open edit modal--}}
        {{--    //edit Message--}}
        {{--    $('body').on('click', '.edit', function() {--}}
        {{--        var id = $(this).data('id');--}}
        {{--        var url = "{{ route('evaluation.edit', ':id') }}".replace(':id', id);--}}
        {{--        $.ajax({--}}
        {{--            url: url,--}}
        {{--            type: 'get',--}}
        {{--            success: function(data) {--}}
        {{--                if (data.success) {--}}
        {{--                    openEditModalById('edit-evaluation', data.data)--}}
        {{--                } else {--}}
        {{--                    flasher.error('Somethings went wrong. Try again later.')--}}
        {{--                }--}}
        {{--            },--}}
        {{--            error: function(errors) {--}}
        {{--                flasher.error(error.responseJSON.message);--}}
        {{--            }--}}
        {{--        })--}}
        {{--    });--}}

        {{--    $('#edit-form').on('submit', function(e) {--}}
        {{--        clearError('edit-evaluation')--}}
        {{--        e.preventDefault();--}}
        {{--        let formData = new FormData(this);--}}
        {{--        formData.append('_token', '{{ csrf_token() }}');--}}
        {{--        let url = "{{ route('evaluation.update', ':id') }}".replace(':id', $(this).find(--}}
        {{--            "input[name='id']").val());--}}
        {{--        $.ajax({--}}

        {{--            url,--}}
        {{--            method: 'POST',--}}
        {{--            contentType: 'multipart/form-data',--}}
        {{--            cache: false,--}}
        {{--            contentType: false,--}}
        {{--            processData: false,--}}
        {{--            data: formData,--}}
        {{--            success: function(resp) {--}}
        {{--                // Reload DataTable--}}
        {{--                $('#basic_tables').DataTable().ajax.reload();--}}
        {{--                if (resp.success === true) {--}}
        {{--                    // show toast message--}}
        {{--                    flasher.success(resp.message);--}}
        {{--                    clearModal('edit-evaluation')--}}
        {{--                } else if (resp.errors) {--}}
        {{--                    flasher.error(resp.errors[0]);--}}
        {{--                } else {--}}
        {{--                    flasher.error(resp.message);--}}
        {{--                }--}}
        {{--            },--}}
        {{--            error: function(xhr) {--}}
        {{--                handleXhrErrors(xhr, 'edit-evaluation')--}}
        {{--            }--}}
        {{--        });--}}
        {{--    });--}}
        {{--});--}}



        {{--$(document).on('click', '.edit', function() {--}}
        {{--    const id = $(this).data('id');--}}
        {{--    $.get(`/evaluation/${id}/edit`, function(response) {--}}
        {{--        if (response.success) {--}}
        {{--            const data = response.data;--}}
        {{--            $('input[name="id"]').val(data.id);--}}
        {{--            $('input[name="title"]').val(data.title);--}}
        {{--            $('select[name="course_id"]').val(data.course_id).trigger(--}}
        {{--                'change');--}}
        {{--        }--}}
        {{--    });--}}
        {{--});--}}
    </script>
@endpush
