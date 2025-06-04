@extends('layouts.master')

@section('title', 'Invoice List Admin')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('build/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" />
@endsection

@section('pageContent')
    @include('layouts.breadcrumb', ['title' => 'Invoice List', 'subtitle' => 'Home', 'link' => 'admin.dashboard'])
    <div class="datatables">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-column flex-md-row justify-content-between mb-2">
                    <div>
                        <h4 class="card-title">Invoice Management</h4>
                        <p class="card-subtitle mb-3">
                            This is the list of all invoices. and you can manage them. with the help of this table.
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
                            <th>Position</th>
                            <th>Department</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                        <!-- end row -->
                        </thead>
                        <tbody>
                        @foreach($invoices as $invoice)
                            <tr>
                                <td>{{ $invoice->account->employee->name }}</td>
                                <td>{{ $invoice->account->employee->position }}</td>
                                <td>{{ $invoice->account->employee->department }}</td>
                                <td>
                                    @if($invoice->type == 'paylater')
                                        <span class="badge bg-primary">Paylater</span>
                                    @elseif($invoice->type == 'loan')
                                        <span class="badge bg-success">Loan</span>
                                    @elseif($invoice->type == 'credit')
                                        <span class="badge bg-danger">Credit</span>
                                    @endif
                                </td>
                                <td><span class="badge bg-secondary">{{ $invoice->status }}</span></td>
                                <td>{{ $invoice->created_at->format('d F Y') }}</td>
                                <td class="d-flex gap-2">
                                    <a href="{{ route('admin.invoice.download', $invoice->id) }}" class="btn btn-warning btn-sm">
                                        <i class="ti ti-download"></i>
                                    </a>
                                    <a href="{{ route('admin.invoice.show', $invoice->id) }}" class="btn btn-success btn-sm">
                                        <i class="ti ti-eye"></i>
                                    </a>
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
