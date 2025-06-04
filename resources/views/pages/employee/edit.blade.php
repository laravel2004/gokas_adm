@extends('layouts.master')

@section('title', 'Dashboard GoKas Admin')

@section('css')

@endsection

@section('pageContent')
    @include('layouts.breadcrumb', ['title' => 'Edit Employee', 'subtitle' => 'List', 'link' => 'admin.employee.index'])

    <div class="container">
        <div class="card">
            <div class="card-body">
                <form id="updateForm">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-4">
                                <label for="name" class="form-label">Name</label>
                                <input value="{{$employee->name}}" type="text" class="form-control" name="name" placeholder="Enter Name">
                            </div>
                            <div class="mb-4">
                                <label for="position" class="form-label">Position</label>
                                <select name="position" class="form-select" aria-label="Default select example">
                                    <option value="" disabled {{ $employee->position ? '' : 'selected' }}>Select Position</option>
                                    @foreach($positions as $position)
                                        <option value="{{ $position->name }}" {{ $employee->position == $position->name ? 'selected' : '' }}>{{ $position->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-4">
                                <label for="role" class="form-label">Role</label>
                                <select name="role" class="form-select" aria-label="Default select example">
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
                                <input type="email" class="form-control" value="{{ $employee->email }}" name="email" placeholder="Enter Email">
                            </div>
                            <div class="mb-4">
                                <label for="department" class="form-label">Department</label>
                                <input type="text" class="form-control" value="{{ $employee->department }}" name="department" placeholder="Enter Department">
                            </div>
                            <div class="mb-4">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" name="password" placeholder="Enter Password">
                                <small class="form-text text-muted"><span class="text-danger">*</span>Leave this field blank if you don't want to change the password.</small>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex align-items-center gap-3">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <a href="{{ route('admin.employee.index') }}" class="btn bg-danger-subtle text-danger">Cancel</a>
                            </div>
                        </div>
                    </div>
                </form>
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
                            url: '{{ route('admin.employee.update', $employee->id) }}',
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
                                    window.location.href = '{{ route('admin.employee.index') }}';
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
