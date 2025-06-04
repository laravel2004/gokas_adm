@extends('layouts.master')

@section('title', 'Dashboard GoKas Admin')

@section('css')

@endsection

@section('pageContent')
    @include('layouts.breadcrumb', ['title' => 'Details Account', 'subtitle' => 'List', 'link' => 'admin.account.index'])

    <div class="container">
        <div class="card">
            <div class="card-header">
                <h6 class="text-center">Unedited Field</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="mb-4">
                            <label for="name" class="form-label">Name</label>
                            <input disabled value="{{$employee->name}}" type="text" class="form-control" name="name" placeholder="Enter Name">
                        </div>
                        <div class="mb-4">
                            <label for="position" class="form-label">Position</label>
                            <select disabled name="position" class="form-select" aria-label="Default select example">
                                <option value="" disabled {{ $employee->position ? '' : 'selected' }}>Select Position</option>
                                @foreach($positions as $position)
                                    <option value="{{ $position->name }}" {{ $employee->position == $position->name ? 'selected' : '' }}>{{ $position->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="role" class="form-label">Role</label>
                            <select disabled name="role" class="form-select" aria-label="Default select example">
                                <option value="" disabled {{ $employee->role ? '' : 'selected' }}>Select Role</option>
                                <option value="approval" {{ $employee->role == 'approval' ? 'selected' : '' }}>Approval</option>
                                <option value="user" {{ $employee->role == 'user' ? 'selected' : '' }}>User</option>
                                <option value="canteen" {{ $employee->role == 'canteen' ? 'selected' : '' }}>Canteen</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-4">
                            <label for="email" class="form-label">Email</label>
                            <input disabled type="email" class="form-control" value="{{ $employee->email }}" name="email" placeholder="Enter Email">
                        </div>
                        <div class="mb-4">
                            <label for="department" class="form-label">Department</label>
                            <input disabled type="text" class="form-control" value="{{ $employee->department }}" name="department" placeholder="Enter Department">
                        </div>
                        <div class="mb-4">
                            <label for="password" class="form-label">Password</label>
                            <input disabled type="password" class="form-control" name="password" placeholder="Enter Password">
                            <small class="form-text text-muted"><span class="text-danger">*</span>Leave this field blank if you don't want to change the password.</small>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-flex align-items-center gap-3">
                            <a href="{{ route('admin.employee.edit', $employee->id) }}" class="btn btn-primary">Edit Employee</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h6 class="text-center">Edited Field</h6>
            </div>
            <div class="card-body">
                <form id="updateForm">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-4">
                                <label for="limit_paylater" class="form-label">Limit Paylater</label>
                                <input value="{{ $account->limit_paylater }}" type="number" class="form-control" name="limit_paylater" placeholder="Enter Limit">
                            </div>
                            <div class="mb-4">
                                <label for="limit_loan" class="form-label">Limit Loan</label>
                                <input value="{{ $account->limit_loan }}" type="number" class="form-control" name="limit_loan" placeholder="Enter Limit">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-4">
                                <label for="limit_credit" class="form-label">Limit Credit</label>
                                <input value="{{ $account->limit_credit }}" type="number" class="form-control" name="limit_credit" placeholder="Enter Limit">
                            </div>
                            <div class="mb-4">
                                <label for="point" class="form-label">Point</label>
                                <input value="{{ $account->point }}" type="number" class="form-control" name="point" placeholder="Enter Point">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex align-items-center gap-3">
                                <button type="submit" class="btn btn-warning">Submit</button>
                                <a href="{{ route('admin.account.index') }}" class="btn bg-danger-subtle text-danger">Back</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
        @endsection

        @section('scripts')
            <script>
                $(document).ready(function () {
                    $('#updateForm').submit(function (e) {
                        e.preventDefault();
                        let formData = new FormData(this);
                        formData.append('_method', 'PUT');
                        $.ajax({
                            url: '{{ route('admin.account.update', $account->id) }}',
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
                                    window.location.href = '{{ route('admin.account.index') }}';
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
                })
            </script>
            <script src="{{ URL::asset('build/js/vendor.min.js') }}"></script>
            <script src="{{ URL::asset('build/js/dashboards/dashboard.js') }}"></script>
@endsection
