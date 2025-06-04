@extends('layouts.master')

@section('title', 'Loan List Admin')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('build/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" />
@endsection

@section('pageContent')
    @include('layouts.breadcrumb', ['title' => 'Loan List', 'subtitle' => 'Home', 'link' => 'admin.dashboard'])
    <div class="datatables">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-column flex-md-row justify-content-between mb-2">
                    <div>
                        <h4 class="card-title">Loan Management</h4>
                        <p class="card-subtitle mb-3">
                            This is the list of all loans. and you can manage them. with the help of this table.
                        </p>
                    </div>
                    <a href="{{ route('admin.loan.create') }}" class="btn btn-primary btn-add btn-md align-self-start mx-auto mx-md-0">Add New Loan</a>
                </div>
                <div class="table-responsive">
                    <table id="zero_config"
                           class="table table-striped table-bordered align-middle">
                        <thead>
                        <!-- start row -->
                        <tr>
                            <th>Name</th>
                            <th>Position</th>
                            <th>Department</th>
                            <th>Tenor</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        <!-- end row -->
                        </thead>
                        <tbody>
                            @foreach($loans as $loan)
                                <tr>
                                    <td>{{ $loan->account->employee->name }}</td>
                                    <td>{{ $loan->account->employee->position }}</td>
                                    <td>{{ $loan->account->employee->department }}</td>
                                    <td>{{ $loan->tenor }} months</td>
                                    <td>{{ formatRupiah($loan->amount) }}</td>
                                    <td class="d-flex flex-wrap gap-2">
                                        @if(is_array(json_decode($loan->status)))
                                            @foreach(json_decode($loan->status) as $stat)
                                                <span class="badge bg-secondary " >{{ $stat }}</span>
                                            @endforeach
                                        @else
                                            {{ $loan->status }}
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap gap-6">
                                            <a href="{{ route('admin.loan.show', $loan->id) }}" class="btn btn-primary d-flex justify-content-center align-items-center"><i class="ti ti-eye"></i></a>
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

            $('.btn-delete').click(function () {
                const id = $(this).data('id');
                const urlTemplate = $(this).data('url'); // e.g., /admin/employee/__id__
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
