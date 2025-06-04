@extends('layouts.master')

@section('title', 'Detail Loan List Admin')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('build/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" />
@endsection

@section('pageContent')
    @include('layouts.breadcrumb', ['title' => 'Detail Loan List', 'subtitle' => 'List Loan', 'link' => 'admin.loan.index'])
    <div class="card">
        <div class="card-body">
            <div class="d-flex flex-column flex-md-row justify-content-between mb-2">
                <div>
                    <h4 class="card-title">Loan of {{ $loan->account->employee->name }}</h4>
                    <p class="card-subtitle mb-3">
                        This is the list of all loan of {{ $loan->account->employee->name }}. and you can manage them. with the help of this table.
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="mb-4">
                        <label for="name" class="form-label">Name Account</label>
                        <input type="text" class="form-control" name="name" value="{{ $loan->account->employee->name }}" readonly>
                    </div>
                    <div class="mb-4">
                        <label for="name" class="form-label">Principal Loan</label>
                        <input type="text" class="form-control" name="name" value="{{ formatRupiah($loan->amount) }}" readonly>
                    </div>
                    <div class="mb-4">
                        <label for="name" class="form-label">Interest</label>
                        <input type="text" class="form-control" name="name" value="{{ formatRupiah($loan->amount * 0.05) }}" readonly>
                    </div>
                    <div class="mb-4">
                        <label for="name" class="form-label">Total Loan</label>
                        <input type="text" class="form-control" name="name" value="{{ formatRupiah($loan->amount + ($loan->amount * 0.05)) }}" readonly>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="mb-4">
                        <label for="name" class="form-label">Tenor</label>
                        <input type="text" class="form-control" name="name" value="{{ $loan->tenor }} Month" readonly>
                    </div>
                    <div class="mb-4">
                        <label for="name" class="form-label">Tenor Paid</label>
                        <input type="text" class="form-control" name="name" value="{{ $loan->paid_tenor }} Month" readonly>
                    </div>
                    <div class="mb-4">
                        <label for="name" class="form-label">Instalment</label>
                        <input type="text" class="form-control" name="name" value="{{ formatRupiah($loan->instalment) }} / Month" readonly>
                    </div>
                    <div class="mb-4">
                        <label for="name" class="form-label">Approver</label>
                        <input type="text" class="form-control" name="name" value="{{ $loan->approval->headEmployee->name }}" readonly>
                    </div>
                </div>
            </div>
            <hr class="my-4">
            <h5 class="mb-3">Loan Progress</h5>
            <div class="row text-center">
                @if(is_array(json_decode($loan->status)))
                    @foreach(json_decode($loan->status) as $stat)
                        <div class="col">
                            <div class="position-relative mb-2">
                                <div class="bg-success rounded-circle mx-auto" style="width: 20px; height: 20px;"></div>
                            </div>
                            <p class="mb-0">{{ $stat }}</p>
                        </div>

                    @endforeach
                @endif
            </div>
            <hr class="my-4">
            <br/>
            <div class="row">
                @if(!$loan->is_approved_admin && !$loan->is_paid_off)
                    <div class="col-md-4">
                        <button data-url="{{ route('admin.loan.reject', ['id' => '__id__']) }}" data-id="{{ $loan->id }}" class="btn btn-danger btn-reject">Tolak Pengajuan</button>
                    </div>
                @endif
                @if(!$loan->is_approved && !$loan->is_paid_off)
                        <div class="col-md-4">
                            <button data-url="{{ route('admin.loan.approve', ['id' => '__id__']) }}" data-id="{{ $loan->id }}" class="btn btn-bypass btn-warning">Bypass Dari Approver</button>
                        </div>
                    @elseif(!$loan->is_paid_off && !$loan->is_approved_admin)
                        <div class="col-md-4">
                            <button data-url="{{ route('admin.loan.cashout', ['id' => '__id__']) }}" data-id="{{ $loan->id }}" class="btn btn-success btn-cashout">Pencarian Dana</button>
                        </div>
                @endif
                @if($loan->is_approved && $loan->is_approved_admin && !($loan->paid_tenor == $loan->tenor))
                        <div class="col-md-4">
                            <button data-url="{{ route('admin.loan.pay', ['id' => '__id__']) }}" data-id="{{ $loan->id }}" class="btn btn-pay btn-primary">Bayar Cicilan</button>
                        </div>
                @endif
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            $('#zero_config').DataTable();

            $('.btn-pay').click(function () {
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
                    confirmButtonText: 'Yes, Pay loan!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url : url,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: 'POST',
                            success: function (data) {
                                Swal.fire(
                                    'Pay Success!',
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

            $('.btn-cashout').click(function () {
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
                    confirmButtonText: 'Yes, Cashout loan!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url : url,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: 'POST',
                            success: function (data) {
                                Swal.fire(
                                    'Cashout Success!',
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

            $('.btn-bypass').click(function () {
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
                    confirmButtonText: 'Yes, Bypass Aproval!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url : url,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: 'POST',
                            success: function (data) {
                                Swal.fire(
                                    'Bypass Approval Success!',
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

            $('.btn-reject').click(function () {
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
                    confirmButtonText: 'Yes, reject loan it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url : url,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: 'POST',
                            success: function (data) {
                                Swal.fire(
                                    'Rejected!',
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
