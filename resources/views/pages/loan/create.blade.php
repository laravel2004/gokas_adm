@extends('layouts.master')

@section('title', 'Pengajuan Pinjaman GoKas Admin')

@section('css')

@endsection

@section('pageContent')
    @include('layouts.breadcrumb', ['title' => 'Pengajuan Pinjaman', 'subtitle' => 'List', 'link' => 'admin.employee.index'])

    <div class="container">
        <div class="card">
            <div class="card-body">
                <form id="storeForm">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-4">
                                <label for="account_id" class="form-label">Account</label>
                                <select name="account_id" class="form-select" aria-label="Default select example">
                                    <option selected="">Select Account</option>
                                    @foreach($accounts as $account)
                                        <option value="{{ $account->id }}">{{ $account->employee->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-4">
                                <label for="approval_id" class="form-label">Approval By</label>
                                <select name="approval_id" id="approval_by" class="form-select" readonly>
                                    <option selected="">Select Approval</option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label for="amount" class="form-label">Nominal Pengajuan</label>
                                <input type="number" class="form-control" name="amount" placeholder="Enter Nominal">
                            </div>
                            <div class="mb-4">
                                <label for="tenor" class="form-label">Tenor</label>
                                <select name="tenor" class="form-select" aria-label="Default select example">
                                    <option selected="">Select Tenor</option>
                                    <option value=1>1 Bulan</option>
                                    <option value=2>2 Bulan</option>
                                    <option value=3>3 Bulan</option>
                                    <option value=4>4 Bulan</option>
                                    <option value=5>5 Bulan</option>
                                    <option value=6>6 Bulan</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-4">
                                <label for="instalment" class="form-label">Cicilan per Bulan</label>
                                <input type="number" class="form-control" id="installment" name="instalment" placeholder="Rp 0" readonly>
                            </div>
                            <div class="mb-4">
                                <label for="description" class="form-label">Alasan Meminjam</label>
                                <textarea rows="10" class="form-control" name="description" placeholder="Enter Description"></textarea>
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

                    function calculateInstallment() {
                        let amount = parseFloat($('input[name="amount"]').val()) || 0;
                        let tenor = parseInt($('select[name="tenor"]').val()) || 0;

                        if (amount > 0 && tenor > 0) {
                            let totalWithInterest = amount + (amount * 0.05); // tambah 5%
                            let installment = totalWithInterest / tenor;

                            $('#installment').val(installment);
                        } else {
                            $('#installment').val(0);
                        }
                    }

                    $('input[name="amount"], select[name="tenor"]').on('input change', function () {
                        calculateInstallment();
                    });

                    $('select[name="account_id"]').change(function () {
                        let selectedAccountId = $(this).val();

                        if (selectedAccountId) {
                            $.ajax({
                                url: '/sudut-panel/admin/approval/check/' + selectedAccountId,
                                type: 'GET',
                                success: function (response) {
                                    $('#approval_by').empty()
                                    $('#approval_by').append('<option value="'+response.data.id+'">'+response.data.head_employee.name+'</option>');
                                },
                                error: function (xhr) {
                                    $('#approval_by').empty().append('<option>Select Approval</option>').prop('readonly', true);
                                }
                            });
                        }
                    });


                    $('#storeForm').submit(function (e) {
                        e.preventDefault();
                        let formData = new FormData(this);
                        $.ajax({
                            url: '{{ route('admin.loan.store') }}',
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
                                    window.location.href = '{{ route('admin.loan.index') }}';
                                })
                            },
                            error: function (xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: xhr.responseJSON.message,
                                });
                            }
                        });
                    })
                })
            </script>
            <script src="{{ URL::asset('build/js/vendor.min.js') }}"></script>
            <script src="{{ URL::asset('build/js/dashboards/dashboard.js') }}"></script>
@endsection
