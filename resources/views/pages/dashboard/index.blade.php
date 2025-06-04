@extends('layouts.master')

@section('title', 'Dashboard GoKas Superadmin')

@section('css')

@endsection

@section('pageContent')

    <div class="toast toast-onload align-items-center text-bg-primary border-0" role="alert" aria-live="assertive"
         aria-atomic="true">
        <div class="toast-body hstack align-items-start gap-6">
            <i class="ti ti-alert-circle fs-6"></i>
            <div>
                <h5 class="text-white fs-3 mb-1">Welcome to Admin Panel</h5>
                <h6 class="text-white fs-2 mb-0">Easy to Manage Apps!!!</h6>
            </div>
            <button type="button" class="btn-close btn-close-white fs-2 m-0 ms-auto shadow-none" data-bs-dismiss="toast"
                    aria-label="Close"></button>
        </div>
    </div>

    <div class="container">
        <div class="row g-3">
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-success text-white d-flex align-items-center justify-content-center rounded-3" style="width: 100px; height: 100px;">
                                <i class="ti ti-shopping-cart" style="font-size: 64px;"></i>
                            </div>
                            <div>
                                <h5 class="card-title mb-1">Credit Active</h5>
                                <p class="card-text text-muted">Rp. 18.500.000</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-warning text-white d-flex align-items-center justify-content-center rounded-3" style="width: 100px; height: 100px;">
                                <i class="ti ti-moneybag" style="font-size: 64px;"></i>
                            </div>
                            <div>
                                <h5 class="card-title mb-1">Loan Active</h5>
                                <p class="card-text text-muted">Rp. 23.000.000</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-danger text-white d-flex align-items-center justify-content-center rounded-3" style="width: 100px; height: 100px;">
                                <i class="ti ti-brand-cashapp" style="font-size: 64px;"></i>
                            </div>
                            <div>
                                <h5 class="card-title mb-1">Paylater Active</h5>
                                <p class="card-text text-muted">Rp. 10.000.000</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="card-title">Trend Shopping</h5>
                        <div id="visitorChart" style="height: 350px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            {{--const dates = @json(array_column($visitorStats, 'date'));--}}
            {{--const counts = @json(array_column($visitorStats, 'count'));--}}

            const dates = ['2023-10-01', '2023-10-02', '2023-10-03', '2023-10-04', '2023-10-05'];
            const counts = [10, 50, 30, 40, 5];

            const options = {
                chart: {
                    type: 'line',
                    height: 350,
                    toolbar: { show: false }
                },
                series: [{
                    name: 'Visitors',
                    data: counts
                }],
                xaxis: {
                    categories: dates,
                    title: {
                        text: 'Tanggal'
                    }
                },
                yaxis: {
                    title: {
                        text: 'Payment with member'
                    }
                },
                stroke: {
                    curve: 'smooth'
                },
                colors: ['#007bff'],
                markers: {
                    size: 4
                },
                tooltip: {
                    x: {
                        format: 'yyyy-MM-dd'
                    }
                }
            };

            const chart = new ApexCharts(document.querySelector("#visitorChart"), options);
            chart.render();
        });

    </script>
    <script src="{{ URL::asset('build/js/vendor.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/owl.carousel/dist/owl.carousel.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/apexcharts/dist/apexcharts.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/dashboards/dashboard.js') }}"></script>
@endsection
