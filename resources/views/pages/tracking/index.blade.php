@extends('layouts.master')

@section('title', 'GPS Tracking Admin')

@section('css')
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
@endsection

@section('pageContent')

    <div class="toast toast-onload align-items-center text-bg-primary border-0" role="alert" aria-live="assertive"
         aria-atomic="true">
        <div class="toast-body hstack align-items-start gap-6">
            <i class="ti ti-alert-circle fs-6"></i>
            <div>
                <h5 class="text-white fs-3 mb-1">Welcome to Admin Panel</h5>
                <h6 class="text-white fs-2 mb-0">Easy to Manage Tracking Apps!!!</h6>
            </div>
            <button type="button" class="btn-close btn-close-white fs-2 m-0 ms-auto shadow-none" data-bs-dismiss="toast"
                    aria-label="Close"></button>
        </div>
    </div>

    <div id="map" style="height: 500px; width: 100%; margin-top: 20px; position: relative; z-index: 1;"></div>

@endsection
@section('scripts')
    <script>
        $(document).ready(function () {
            const map = L.map('map').setView([-7.2575, 112.7521], 13); // Default view Surabaya
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            let routeCoordinates = [];
            let marker = null;
            const monitoredDriverTaskId = {{ $id }};

            const ws = new WebSocket('ws://localhost:8080');

            ws.onopen = function () {
                console.log('WebSocket connected');
                ws.send(JSON.stringify({ action: "fetch", driver_task_id: monitoredDriverTaskId }));
            };

            ws.onmessage = function (event) {
                const response = JSON.parse(event.data);

                if (response.status === 'success' && response.data.length > 0) {
                    response.data.forEach(({ longitude, latitude }) => {
                        addMarkerAndPolyline(latitude, longitude);
                    });
                } else if (response.action === 'update' && response.data) {
                    const { driver_task_id, longitude, latitude } = response.data;

                    // Periksa apakah driver_task_id sama dengan yang sedang dimonitor
                    if (driver_task_id === monitoredDriverTaskId) {
                        addMarkerAndPolyline(latitude, longitude);
                    }
                }
            };

            function addMarkerAndPolyline(latitude, longitude) {
                if (marker) {
                    marker.setLatLng([latitude, longitude]);
                } else {
                    marker = L.marker([latitude, longitude]).addTo(map);
                }

                routeCoordinates.push([latitude, longitude]);
                L.polyline(routeCoordinates, { color: 'blue' }).addTo(map);

                map.setView([latitude, longitude], 15);
            }

            ws.onerror = function (error) {
                console.error('WebSocket error:', error);
            };

            ws.onclose = function () {
                console.log('WebSocket closed');
            };
        });
    </script>
    <script src="{{ URL::asset('build/js/vendor.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/dashboards/dashboard.js') }}"></script>
@endsection
