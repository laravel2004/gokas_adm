@extends('layouts.master')

@section('title', 'Detail Invoice List Admin')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('build/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" />
    <style>
        .watermark {
            position: absolute;
            top: 100%;
            left: 60%;
            transform: translate(-50%, -50%);
            font-size: 200px; /* Ukuran besar */
            color: rgba(169, 169, 169, 0.3); /* Warna abu-abu dengan transparansi */
            font-weight: bold;
            letter-spacing: 10px;
            white-space: nowrap;
            pointer-events: none;
            z-index: 1;
        }
    </style>
@endsection

@section('pageContent')
    <!-- Watermark -->
    <div class="watermark">{{ strtoupper($invoice->status) }}</div>

    @include('layouts.breadcrumb', ['title' => 'Detail Invoice', 'subtitle' => 'Invoice', 'link' => 'admin.invoice.index'])
    <div class="datatables">
        <div class="card">
            <div class="card-body">
                <div style="border-bottom: 2px solid black; padding-bottom: 10px;" class="d-flex flex-column flex-md-row justify-content-between mb-2">
                    <div>
                        <img src="{{ URL::asset('build/images/logos/koperasi.png') }}" class="dark-logo" alt="Logo-Dark" />
                    </div>
                    <div class="text-center">
                        <h4>KOPERASI KONSUMEN</h4>
                        <h4>KARYAWAN REJOSO MANIS INDO</h4>
                        <span class="mt-3">No. AHU – 0002089.AH.01.26.Tahun 2020</span>
                        <br/>
                        <span>Jl. Raya Rejoso RT. 002/ RW. 003 Ds. Rejoso Kec. Binangun Kab. Blitar</span>
                        <br/>
                        <span>Jawa Timur</span>
                    </div>
                    <div>
                        <img src="{{ URL::asset('build/images/logos/koperasi.png') }}" class="dark-logo" alt="Logo-Dark" />
                    </div>
                </div>
                <br/>
                <div class="text-center">
                    <h1>INVOICE</h1>
                </div>
                <br/><br/>
                <div class="row mt-6 justify-content-between">
                    <div class="col-6">
                        <h5>KOPERASI KARYAWAN REJOSO MANIS INDO</h5>
                        <p>Kab. Blitar, Provinsi Jawa Timur</p>
                        <h6>Ditujukan Kepada : {{ $invoice->account->employee->name }}</h6>
                    </div>
                    <div class="col-4"></div>
                    <div class="col-2">
                        <h6>No : INV-110{{ $invoice->id }}</h6>
                        <h6>Tanggal : {{ $invoice->created_at->format('d F Y') }}</h6>
                        <h6>PO. No : -</h6>
                        <h6>Qou. No : -</h6>
                    </div>
                </div>
                <div>
                    <table class="table table-bordered mt-4">
                        <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Nominal</th>
                            <th>Bunga</th>
                            <th>Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($content->unit as $unit)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($unit->date)->format('d F Y') }}</td>
                                <td>{{ formatRupiah($unit->nominal) }}</td>
                                <td>{{ formatRupiah($unit->interest) }}</td>
                                <td>{{ formatRupiah($unit->amount) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="row mt-5 align-items-center">
                    <div class="col-6">
                        <h5>Terbilang :</h5>
                        <h6 class="mt-3">{{ terbilang($content->total) }}</h6>
                        <br/>
                        <h5>Keterangan :</h5>
                        <h6 class="mt-3">Pembayaran {{ $invoice->type }}</h6>
                    </div>
                    <div class="col-4"></div>
                    <div class="col-2">
                        <h6 class="text-center">KOPERASI KARYAWAN REJOSO MANIS INDO</h6>
                        <img src="{{ URL::asset('build/images/logos/qr.jpg') }}" class="dark-logo" alt="Logo-Dark" width="150" height="150" />
                        <br/><br/>
                        <h6 class="text-center">Koord. Sie Usaha</h6>
                    </div>
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
