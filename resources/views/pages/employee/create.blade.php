@extends('layouts.master')

@section('title', 'Dashboard GoKas Admin')

@section('css')

@endsection

@section('pageContent')
    @include('layouts.breadcrumb', ['title' => 'New Employee', 'subtitle' => 'List', 'link' => 'admin.employee.index'])

    <div class="container">
        <div class="card">
            <div class="card-body">
                <form id="storeForm">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-4">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" name="name" placeholder="Enter Name">
                            </div>
                            <div class="mb-4">
                                <label for="position" class="form-label">Position</label>
                                <select name="position" class="form-select" aria-label="Default select example">
                                    <option selected="">Select Position</option>
                                    <@foreach($positions as $position)
                                        <option value="{{ $position->name }}">{{ $position->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-4">
                                <label for="role" class="form-label">Role</label>
                                <select name="role" class="form-select" aria-label="Default select example">
                                    <option selected="">Select Role</option>
                                    <option value="approval">Approval</option>
                                    <option value="user">User</option>
                                    <option value="canteen">Canteen</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-4">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" placeholder="Enter Email">
                            </div>
                            <div class="mb-4">
                                <label for="department" class="form-label">Department</label>
                                <input type="text" class="form-control" name="department" placeholder="Enter Department">
                            </div>
                            <div class="mb-4">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" name="password" placeholder="Enter Password">
                            </div>
                        </div>
                        <div class="mb-4 col-12">
                            <label for="nik" class="form-label">NIK</label>
                            <input type="text" class="form-control" name="nik" placeholder="Enter NIK">
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
            $('#storeForm').submit(function (e) {
                e.preventDefault();
                let formData = new FormData(this);
                $.ajax({
                    url: '{{ route('admin.employee.store') }}',
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
