@extends('layouts.master')

@section('title', 'Employee List Admin')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('build/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" />
@endsection

@section('pageContent')
    @include('layouts.breadcrumb', ['title' => 'Setting Approval List', 'subtitle' => 'Home', 'link' => 'admin.dashboard'])
    <div class="datatables">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-column flex-md-row justify-content-between mb-2">
                    <div>
                        <h4 class="card-title">Setting Approval Management</h4>
                        <p class="card-subtitle mb-3">
                            This is the list of all setting of approval. and you can manage them. with the help of this table.
                        </p>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="zero_config"
                           class="table table-striped table-bordered align-middle">
                        <thead>
                        <!-- start row -->
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Position</th>
                            <th>Department</th>
                            <th>Role</th>
                            <th>Join at</th>
                            <th>Action</th>
                        </tr>
                        <!-- end row -->
                        </thead>
                        <tbody>
                        @foreach($employees as $employee)
                            <tr>
                                <td>{{ $employee->name }}</td>
                                <td>{{ $employee->email }}</td>
                                <td>{{ $employee->position }}</td>
                                <td>{{ $employee->department }}</td>
                                <td>
                                    @if($employee->role == 'approval')
                                        <span class="badge bg-success">Approval</span>
                                    @elseif($employee->role == 'user')
                                        <span class="badge bg-primary">User</span>
                                    @elseif($employee->role == 'canteen')
                                        <span class="badge bg-danger">Canteen</span>
                                    @endif
                                </td>
                                <td>{{ $employee->created_at->format('d F Y') }}</td>
                                <td>
                                    <div class="d-flex gap gap-6">
                                        <a  class="btn btn-success btn-edit btn-sm" href="{{ route('admin.setting-approval.show', $employee->id) }}">
                                            <i class="ti ti-eye"></i>
                                        </a>
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

@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            $('#zero_config').DataTable();
        });
    </script>

    <script src="{{ URL::asset('build/js/vendor.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/dashboards/dashboard.js') }}"></script>
    <script src="{{ URL::asset('build/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/datatable/datatable-basic.init.js') }}"></script>
@endsection
