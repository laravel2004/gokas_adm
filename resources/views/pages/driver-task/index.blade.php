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
                        <h4 class="card-title">Driver task Management</h4>
                        <p class="card-subtitle mb-3">
                            This is the list of all driver task. and you can manage them. with the help of this table.
                        </p>
                    </div>
                    <a href="{{ route('admin.driver-task.create') }}" class="btn btn-primary btn-add btn-md align-self-start mx-auto mx-md-0">Add Driver Task</a>
                </div>
                <div class="table-responsive">
                    <table id="zero_config"
                           class="table table-striped table-bordered text-nowrap align-middle">
                        <thead>
                        <!-- start row -->
                        <tr>
                            <th>Driver Name</th>
                            <th>Origin Pick</th>
                            <th>Destination Pick</th>
                            <th>Status</th>
                            <th>Estimation Time</th>
                            <th>Action</th>
                        </tr>
                        <!-- end row -->
                        </thead>
                        <tbody>
                        @foreach($driverTasks as $driverTask)
                            <tr>
                                <td>{{ $driverTask->driver->name }}</td>
                                <td>{{ $driverTask->startPickPoint->name }}</td>
                                <td>{{ $driverTask->endPickPoint->name }}</td>
                                <td>
                                    @if($driverTask->status === "not_started")
                                        <span class="mb-1 badge  bg-danger-subtle text-danger">Not Started</span>
                                    @elseif($driverTask->status === "on_progress")
                                        <span class="mb-1 badge  bg-warning-subtle text-warning">On Progress</span>
                                    @elseif($driverTask->status === "finish")
                                        <span class="mb-1 badge  bg-primary-subtle text-primary">Finish</span>
                                    @endif
                                </td>
                                <td>{{ \Carbon\Carbon::parse($driverTask->end_date)->translatedFormat('l, d F Y') }} - {{ $driverTask->end_time }}</td>
                                <td>
                                    <div class="d-flex gap gap-6">
                                        <a href="/sudut-panel/admin/tracking/{{$driverTask->id}}" class="btn btn-success btn-sm">Tracking</a>
                                        <a href="/sudut-panel/admin/driver-task/{{$driverTask->id}}/edit" class="btn btn-warning btn-edit btn-sm">Edit</a>
                                        <button class="btn btn-danger btn-delete btn-sm" data-id="{{ $driverTask->id }}">Delete</button>
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
            $('.btn-delete').on('click', function () {
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
                            url: "/sudut-panel/admin/driver-task/" + id,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: 'DELETE',
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
                        });
                    }
                })
            });
        });

    </script>
    <script src="{{ URL::asset('build/js/vendor.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/dashboards/dashboard.js') }}"></script>
    <script src="{{ URL::asset('build/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/datatable/datatable-basic.init.js') }}"></script>
@endsection
