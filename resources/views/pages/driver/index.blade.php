@extends('layouts.master')

@section('title', 'GPS Tracking Admin')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('build/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" />
@endsection

@section('pageContent')

    <div class="datatables">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-column flex-md-row justify-content-between mb-2">
                    <div>
                        <h4 class="card-title">Driver Management</h4>
                        <p class="card-subtitle mb-3">
                            This is the list of all drivers. and you can manage them. with the help of this table.
                        </p>
                    </div>
                    <button data-bs-toggle="modal" data-bs-target="#signup-modal"
                            class="btn btn-primary btn-add btn-md align-self-start mx-auto mx-md-0">Add Driver</button>
                </div>
                <div class="table-responsive">
                    <table id="zero_config"
                           class="table table-striped table-bordered text-nowrap align-middle">
                        <thead>
                        <!-- start row -->
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Join at</th>
                            <th>Action</th>
                        </tr>
                        <!-- end row -->
                        </thead>
                        <tbody>
                        @foreach($drivers as $driver)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-6">
                                        <img alt="image-profile" src="{{ URL::asset('build/images/profile/user-5.jpg') }}" width="45"
                                             class="rounded-circle" />
                                        <h6 class="mb-0">{{ $driver->name }}</h6>
                                    </div>

                                </td>
                                <td>{{ $driver->email }}</td>
                                <td>{{ $driver->created_at->format('d F Y') }}</td>
                                <td>
                                    <div class="d-flex gap gap-6">
                                        <button  class="btn btn-warning btn-edit btn-sm"
                                                 data-id="{{ $driver->id }}"
                                                 data-name="{{ $driver->name }}"
                                                 data-email="{{ $driver->email }}"
                                                 data-bs-toggle="modal"
                                                 data-bs-target="#signup-modal"
                                        >Edit</button>
                                        <button class="btn btn-danger btn-delete btn-sm" data-id="{{ $driver->id }}">Delete</button>
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


    <div id="signup-modal" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="text-center mt-2 mb-4">
                        <a href="/main/index" class="text-success">
                                <span id="titleModal">
                                  Register New Driver
                                </span>
                        </a>
                    </div>

                    <form class="ps-3 pr-3" id="formAddModal">
                        @csrf
                        <div class="mb-3">
                            <label for="username">Name</label>
                            <input name="name" class="form-control" type="text" id="username" required=""
                                   placeholder="Michael Zenaty" />
                        </div>

                        <div class="mb-3">
                            <label for="emailaddress">Email address</label>
                            <input name="email" class="form-control" type="email" id="emailaddress" required=""
                                   placeholder="john@deo.com" />
                        </div>

                        <div class="mb-3">
                            <label id="labelPassword" for="password">Password</label>
                            <input name="password" class="form-control" type="password" required="" id="password"
                                   placeholder="Enter your password" />
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="customCheck1" />
                                <label class="form-check-label" for="customCheck1">I accept
                                    <a href="javascript:void(0)">Terms and Conditions</a></label>
                            </div>
                        </div>

                        <div class="mb-3 text-center">
                            <button id="submitForm" class="btn bg-info-subtle text-info " type="submit">
                                Register Now
                            </button>
                        </div>
                    </form>
                    <form class="ps-3 pr-3" id="formEditModal">
                        @csrf
                        <input type="hidden" name="id" id="idEdit" />
                        <div class="mb-3">
                            <label for="usernameEdit">Name</label>
                            <input name="name" class="form-control" type="text" id="usernameEdit" required=""
                                   placeholder="Michael Zenaty" />
                        </div>

                        <div class="mb-3">
                            <label for="emailAddressEdit">Email address</label>
                            <input name="email" class="form-control" type="email" id="emailAddressEdit" required=""
                                   placeholder="john@deo.com" />
                        </div>

                        <div class="mb-3">
                            <label id="labelPassword" for="password">Password (Optional) <small class="text-warning">*if you forgot</small></label>
                            <input name="password" class="form-control" type="password" id="password"
                                   placeholder="Enter your password" />
                        </div>

                        <div class="mb-3 text-center">
                            <button id="submitForm" class="btn bg-info-subtle text-info " type="submit">
                                Updated Now
                            </button>
                        </div>
                    </form>
                </div>
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
                $('#titleModal').text('Register New Driver');
                $('#formAddModal').show();
                $('#formEditModal').hide();
            });

            $('.btn-edit').click(function () {
                $('#titleModal').text('Edit Driver Registered');
                $('#formAddModal').hide();
                $('#formEditModal').show();
                $('#idEdit').val($(this).data('id'));
                $('#usernameEdit').val($(this).data('name'));
                $('#emailAddressEdit').val($(this).data('email'));
            });

            $('#formEditModal').submit(function (e) {
                e.preventDefault();
                const formData = new FormData(this);
                formData.append('_method', 'PUT');
                $.ajax({
                    url: "/sudut-panel/admin/driver/" + formData.get('id'),
                    type: 'POST',
                    data: formData,
                    success: function (data) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: data.message,
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function (xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: xhr.responseJSON.message,
                        });
                    },
                    cache: false,
                    contentType: false,
                    processData: false
                });
            });

            $('#formAddModal').submit(function (e) {
                e.preventDefault();
                const formData = new FormData(this);
                $.ajax({
                    url: "{{ route('admin.driver.store') }}",
                    type: 'POST',
                    data: formData,
                    success: function (data) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: data.message,
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function (xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: xhr.responseJSON.message,
                        });
                    },
                    cache: false,
                    contentType: false,
                    processData: false
                });
            });

            $('.btn-delete').click(function () {
                const id = $(this).data('id');
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
                            url : `/sudut-panel/admin/driver/${id}`,
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
