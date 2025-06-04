@extends('layouts.master')

@section('title', 'Paylater List Admin')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('build/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" />
@endsection

@section('pageContent')
    @include('layouts.breadcrumb', ['title' => 'Detail Paylater Employee', 'subtitle' => 'Paylater list', 'link' => 'admin.paylater.index'])
    <div class="datatables">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-column flex-md-row justify-content-between mb-2">
                    <div>
                        <h4 class="card-title">Details Paylater {{ $account->employee->name }}</h4>
                        <p class="card-subtitle mb-3">
                            This is the list of all paylater. and you can manage them. with the help of this table.
                        </p>
                    </div>
                    @if(request()->get('filter') == 'unpaid' && $paylaters->isNotEmpty())
                        <button data-id="{{ $account->id }}" data-url="{{ route('admin.paylater.bulk-paid-off', ['id' => '__id__']) }}" class="btn btn-bulk btn-warning btn-md align-self-start mx-auto mx-md-0">Bayar Semua</button>
                    @endif
                </div>
                <div class="table-responsive">
                    <table id="zero_config"
                           class="table table-striped table-bordered align-middle">
                        <thead>
                        <!-- start row -->
                        <tr>
                            <th>Total Amount</th>
                            <th>Original Price</th>
                            <th>Interest</th>
                            <th>Paid Off</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                        <!-- end row -->
                        </thead>
                        <tbody>
                        @foreach($paylaters as $paylater)
                            <tr>
                                <td>{{ formatRupiah($paylater->total_amount) }}</td>
                                <td>{{ formatRupiah($paylater->nominal) }}</td>
                                <td>{{ formatRupiah($paylater->interest) }}</td>
                                <td>
                                    @if($paylater->is_paid_off)
                                        <span class="badge bg-success">Paid Off</span>
                                    @else
                                        <span class="badge bg-danger">Not Paid Off</span>
                                    @endif
                                </td>
                                <td class="d-flex flex-wrap gap-2">
                                    @if(is_array(json_decode($paylater->status)))
                                        @foreach(json_decode($paylater->status) as $stat)
                                            <span class="badge bg-secondary " >{{ $stat }}</span>
                                        @endforeach
                                    @else
                                        {{ $paylater->status }}
                                    @endif
                                </td>
                                <td>{{ $paylater->created_at->format('d F Y') }}</td>

                                <td>
                                    <div class="d-flex gap gap-6">
                                        @if($paylater->is_paid_off)
                                            <span class="badge bg-success">Lunas</span>
                                        @else
                                            <button data-id="{{ $paylater->id }}" data-url="{{ route('admin.paylater.paid-off', ['id' => '__id__']) }}" class="btn btn-warning btn-paid btn-sm">
                                                Bayar
                                            </button>
                                        @endif
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

            $('.btn-paid').click(function () {
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
                    confirmButtonText: 'Yes, Paid Off!'
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
                                    'Paid Off!',
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

            $('.btn-bulk').click(function () {
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
                    confirmButtonText: 'Yes, Paid Off All!'
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
                                    'Paid Off All!',
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
