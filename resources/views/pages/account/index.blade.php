@extends('layouts.master')

@section('title', 'Account List Admin')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('build/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" />
@endsection

@section('pageContent')
    @include('layouts.breadcrumb', ['title' => 'Account List', 'subtitle' => 'Home', 'link' => 'admin.dashboard'])
    <div class="datatables">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-column flex-md-row justify-content-between mb-2">
                    <div>
                        <h4 class="card-title">Account Management</h4>
                        <p class="card-subtitle mb-3">
                            This is the list of all account. and you can manage them. with the help of this table.
                        </p>
                    </div>
                    <div class="flex gap-1">
                        <button data-bs-toggle="modal" data-bs-target="#bs-example-modal-lg" class="btn btn-success btn-add btn-md align-self-start mx-auto mx-md-0">Cash In</button>
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
                            <th>Paylater (Tersedia)</th>
                            <th>Loan (Tersedia)</th>
                            <th>Credit (Tersedia)</th>
                            <th>Point</th>
                            <th>Balance</th>
                            <th>Action</th>
                        </tr>
                        <!-- end row -->
                        </thead>
                        <tbody>
                        @foreach($accounts as $account)
                            <tr>
                                <td>{{ $account->employee->name }}</td>
                                <td>{{ $account->employee->position }}</td>
                                <td>{{ $account->employee->department }}</td>
                                <td>{{ formatRupiah($account->limit_paylater - $account->limit_paylater_used) }}</td>
                                <td>{{ formatRupiah($account->limit_loan - $account->limit_loan_used) }}</td>
                                <td>{{ formatRupiah($account->limit_credit - $account->limit_credit_used) }}</td>
                                <th>{{ $account->point }}</th>
                                <td>{{ formatRupiah($account->balance) }}</td>
                                <td>
                                    <div class="d-flex gap gap-6">
                                        <a  class="btn btn-warning btn-edit btn-sm" href="{{ route('admin.account.edit', $account->id) }}">
                                            <i class="ti ti-edit"></i>
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

    <div class="modal fade" id="bs-example-modal-lg" tabindex="-1" aria-labelledby="bs-example-modal-lg" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="storeForm">
                    @csrf
                    <div class="modal-header d-flex align-items-center">
                        <h4 class="modal-title" id="myLargeModalLabel">
                            Cash In
                        </h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <label for="balance" class="form-label">Balance cash in</label>
                                <input type="number" class="form-control" name="balance" />
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

            $('#storeForm').submit(function (e) {
                e.preventDefault();
                const formData = new FormData(this);
                const url = "{{ route('admin.account.store-balance') }}";
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        Swal.fire(
                            'Success!',
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
            });

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
