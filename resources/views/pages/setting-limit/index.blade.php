@extends('layouts.master')

@section('title', 'Setting Limit List Admin')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('build/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" />
@endsection

@section('pageContent')
    @include('layouts.breadcrumb', ['title' => 'Setting Limit', 'subtitle' => 'Home', 'link' => 'admin.dashboard'])
    <div class="datatables">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-column flex-md-row justify-content-between mb-2">
                    <div>
                        <h4 class="card-title">Setting Limit Management</h4>
                        <p class="card-subtitle mb-3">
                            This is the list of all limit settings. and you can manage them. with the help of this table.
                        </p>
                    </div>
                    <button data-bs-toggle="modal" data-bs-target="#bs-example-modal-lg" class="btn btn-primary btn-add btn-md align-self-start mx-auto mx-md-0">Add New Limit</button>
                </div>
                <div class="table-responsive">
                    <table id="zero_config"
                           class="table table-striped table-bordered align-middle">
                        <thead>
                        <!-- start row -->
                        <tr>
                            <th>Position</th>
                            <th>Limit Paylater</th>
                            <th>Limit Credit</th>
                            <th>Limit Loan</th>
                            <th>Action</th>
                        </tr>
                        <!-- end row -->
                        </thead>
                        <tbody>
                            @foreach($settingLimits as $settingLimit)
                                <tr>
                                    <td>{{ $settingLimit->position }}</td>
                                    <td>{{ formatRupiah($settingLimit->limit_paylater) }}</td>
                                    <td>{{ formatRupiah($settingLimit->limit_credit) }}</td>
                                    <td>{{ formatRupiah($settingLimit->limit_loan) }}</td>
                                    <td>
                                        <div class="d-flex gap gap-6">
                                            <button data-bs-toggle="modal"
                                                    data-id="{{ $settingLimit->id }}"
                                                    data-position="{{ $settingLimit->position }}"
                                                    data-paylater="{{ $settingLimit->limit_paylater }}"
                                                    data-loan="{{ $settingLimit->limit_loan }}"
                                                    data-credit="{{ $settingLimit->limit_credit }}"
                                                    data-bs-target="#bs-example-modal-lg"
                                                    class="btn btn-warning btn-edit btn-sm" >
                                                <i class="ti ti-edit "></i>
                                            </button>
                                            <button class="btn btn-danger btn-delete btn-sm" data-id="{{ $settingLimit->id }}" data-url="{{ route('admin.setting-limit.destroy', ['id' => '__id__']) }}">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="bs-example-modal-lg" tabindex="-1" aria-labelledby="bs-example-modal-lg" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="storeForm">
                    @csrf
                    <div class="modal-header d-flex align-items-center">
                        <h4 class="modal-title" id="myLargeModalLabel">
                            Create New Limit
                        </h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-4">
                                    <label for="position" class="form-label">Position</label>
                                    <select name="position" class="form-select" aria-label="Default select example">
                                        <option selected="">Select Position</option>
                                        @foreach($positions as $position)
                                            <option value="{{ $position->name }}">{{ $position->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-4">
                                    <label for="limit_paylater" class="form-label ">Limit Paylater</label>
                                    <input type="number" class="form-control" name="limit_paylater" placeholder="Enter Limit Paylater">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-4">
                                    <label for="limit_credit" class="form-label ">Limit Credit</label>
                                    <input type="number" class="form-control" name="limit_credit" placeholder="Enter Limit Credit">
                                </div>
                                <div class="mb-4">
                                    <label for="limit_loan" class="form-label ">Limit Loan</label>
                                    <input type="number" class="form-control" name="limit_loan" placeholder="Enter Limit Loan">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">
                            Submit
                        </button>
                        <button type="button" class="btn bg-danger-subtle text-danger  waves-effect text-start" data-bs-dismiss="modal">
                            Close
                        </button>
                    </div>
                </form>
                <form id="editForm">
                    @csrf
                    <div class="modal-header d-flex align-items-center">
                        <h4 class="modal-title" id="myLargeModalLabel">
                            Edit Limit
                        </h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <input id="edit-id" hidden name="id" />
                                <div class="mb-4">
                                    <label for="position" class="form-label">Position</label>
                                    <select name="position" class="form-select" aria-label="Default select example">
                                        <option selected="">Select Position</option>
                                        @foreach($positions as $position)
                                            <option value="{{ $position->name }}">{{ $position->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-4">
                                    <label for="limit_paylater" class="form-label ">Limit Paylater</label>
                                    <input type="number" class="form-control" name="limit_paylater" placeholder="Enter Limit Paylater">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-4">
                                    <label for="limit_credit" class="form-label ">Limit Credit</label>
                                    <input type="number" class="form-control" name="limit_credit" placeholder="Enter Limit Credit">
                                </div>
                                <div class="mb-4">
                                    <label for="limit_loan" class="form-label ">Limit Loan</label>
                                    <input type="number" class="form-control" name="limit_loan" placeholder="Enter Limit Loan">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">
                            Submit
                        </button>
                        <button type="button" class="btn bg-danger-subtle text-danger  waves-effect text-start" data-bs-dismiss="modal">
                            Close
                        </button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            $('#zero_config').DataTable();

            $('.btn-add').click(function () {
                const storeForm = $('#storeForm');
                const editForm = $('#editForm');
                storeForm.trigger('reset');
                editForm.hide();
                storeForm.show();

            });

            $('.btn-edit').click(function () {
                const storeForm = $('#storeForm');
                const editForm = $('#editForm');
                storeForm.hide();
                editForm.trigger('reset');
                const id = $(this).data('id');
                const position = $(this).data('position');
                const paylater = $(this).data('paylater');
                const loan = $(this).data('loan');
                const credit = $(this).data('credit');

                editForm.find('select[name="position"]').val(position);
                editForm.find('input[name="limit_paylater"]').val(paylater);
                editForm.find('input[name="limit_loan"]').val(loan);
                editForm.find('input[name="limit_credit"]').val(credit);
                editForm.find('input[name="id"]').val(id)
                editForm.show();
            })

            $('#editForm').submit(function (e) {
                e.preventDefault();
                let formData = new FormData(this);
                const id = $('#edit-id').val();
                console.log(id)
                formData.append('_method', 'PUT');
                $.ajax({
                    url: `{{ route('admin.setting-limit.update', ':id') }}`.replace(':id', id),
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message,
                        }).then(() => {
                            window.location.href = '{{ route('admin.setting-limit.index') }}';
                        })
                    },
                    error: function (xhr) {
                        let errors = xhr.responseJSON.errors;
                        let errorMessage = '';
                        $.each(errors, function (key, value) {
                            errorMessage += value[0] + '\n';
                        });
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: errorMessage,
                        });
                    }
                });

            })

            $('#storeForm').submit(function (e) {
                e.preventDefault();
                let formData = new FormData(this);
                $.ajax({
                    url: '{{ route('admin.setting-limit.store') }}',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message,
                        }).then(() => {
                            window.location.href = '{{ route('admin.setting-limit.index') }}';
                        })
                    },
                    error: function (xhr) {
                        let errors = xhr.responseJSON.errors;
                        let errorMessage = '';
                        $.each(errors, function (key, value) {
                            errorMessage += value[0] + '\n';
                        });
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: errorMessage,
                        });
                    }
                });
            })

            $('.btn-delete').click(function () {
                const id = $(this).data('id');
                const urlTemplate = $(this).data('url');
                const url = urlTemplate.replace('__id__', id);
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
                        $.ajax({
                            url : url,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: 'DELETE',
                            success: function (data) {
                                Swal.fire(
                                    'Deleted!',
                                    data.message,
                                    'success'
                                ).then(() => {
                                    location.reload();
                                });
                            },
                            error: function (xhr, status, error) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: xhr.responseJSON.message,
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>

    <script src="{{ URL::asset('build/js/vendor.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/dashboards/dashboard.js') }}"></script>
    <script src="{{ URL::asset('build/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/datatable/datatable-basic.init.js') }}"></script>
@endsection
