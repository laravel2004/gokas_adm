@extends('layouts.master')

@section('title', 'GPS Tracking Admin')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('build/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
@endsection

@section('pageContent')
    <div class="datatables">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-column flex-md-row justify-content-between mb-2">
                    <div>
                        <h4 class="card-title">Pick point Management</h4>
                        <p class="card-subtitle mb-3">
                            This is the list of all pick points. and you can manage them. with the help of this table.
                        </p>
                    </div>
                    <button data-bs-toggle="modal" data-bs-target="#addModalUI"
                            class="btn btn-primary btn-add btn-md align-self-start mx-auto mx-md-0">Add Pick Point</button>
                </div>
                <div class="table-responsive">
                    <table id="zero_config"
                           class="table table-striped table-bordered text-nowrap align-middle">
                        <thead>
                        <!-- start row -->
                        <tr>
                            <th>Name</th>
                            <th>Address</th>
                            <th>Longitude</th>
                            <th>Latitude</th>
                            <th>Action</th>
                        </tr>
                        <!-- end row -->
                        </thead>
                        <tbody>
                        @foreach($pickPoints as $pickPoint)
                            <tr>
                                <td>{{ $pickPoint->name }}</td>
                                <td>{{ $pickPoint->address }}</td>
                                <td>{{ $pickPoint->longitude }}</td>
                                <td>{{ $pickPoint->latitude }}</td>
                                <td>
                                    <div class="d-flex gap gap-6">
                                        <button  class="btn btn-warning btn-edit btn-sm"
                                                 data-id="{{ $pickPoint->id }}"
                                                 data-name="{{ $pickPoint->name }}"
                                                 data-longitude="{{ $pickPoint->longitude }}"
                                                 data-latitude="{{ $pickPoint->latitude }}"
                                                 data-address="{{ $pickPoint->address }}"
                                                 data-bs-toggle="modal"
                                                 data-bs-target="#addModalUI"
                                        >Edit</button>
                                        <button class="btn btn-danger btn-delete btn-sm" data-id="{{ $pickPoint->id }}">Delete</button>
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

    <div class="modal fade" id="addModalUI" tabindex="-1"
         aria-labelledby="bs-example-modal-lg" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center">
                    <h4 class="modal-title" id="myLargeModalLabel">
                        Add Pick Point
                    </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formAddPickPoint">
                        @csrf
                        <div id="map" class="mb-3" style="height: 200px;"></div>
                        <div class="row">
                            <div class="form-floating mb-3 col-6">
                                <input name="longitude" type="text" class="form-control" readonly id="tb-long" placeholder="Enter Name longitude" />
                                <label for="tb-long">Longitude</label>
                            </div>
                            <div class="form-floating mb-3 col-6">
                                <input name="latitude" type="text" class="form-control" readonly id="tb-lat" placeholder="Enter Name latitude" />
                                <label for="tb-lat">Latitude</label>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="form-floating mb-3">
                                <input name="name" type="text" class="form-control" id="tb-fname" placeholder="Enter Name here" />
                                <label for="tb-fname">Name</label>
                            </div>
                        </div>
                        <div class="form-floating mb-3">
                            <textarea name="address" class="form-control" id="tb-textarea" placeholder="Enter text here" style="height: 150px;"></textarea>
                            <label for="tb-textarea">Address</label>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Submit</button>
                    </form>
                    <form id="formEditPickPoint">
                        @csrf
                        <div id="mapEdit" class="mb-3" style="height: 200px;"></div>
                        <input type="hidden" name="id" id="idEdit" />
                        <div class="row">
                            <div class="form-floating mb-3 col-6">
                                <input name="longitude" type="text" class="form-control" readonly id="tb-longEdit" placeholder="Enter Name longitude" />
                                <label for="tb-longEdit">Longitude</label>
                            </div>
                            <div class="form-floating mb-3 col-6">
                                <input name="latitude" type="text" class="form-control" readonly id="tb-latEdit" placeholder="Enter Name latitude" />
                                <label for="tb-latEdit">Latitude</label>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="form-floating mb-3">
                                <input name="name" type="text" class="form-control" id="tb-fnameEdit" placeholder="Enter Name here" />
                                <label for="tb-fnameEdit">Name</label>
                            </div>
                        </div>
                        <div class="form-floating mb-3">
                            <textarea name="address" class="form-control" id="tb-textareaEdit" placeholder="Enter text here" style="height: 150px;"></textarea>
                            <label for="tb-textareaEdit">Address</label>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Submit</button>
                    </form>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

@endsection

@section('scripts')
    <script>

        $(document).ready(function () {
            $('#formAddPickPoint').submit(function (e) {
                e.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    url: '{{ route('admin.pick-point.store') }}',
                    type: 'POST',
                    data: formData,
                    success : function (data) {
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
                    cache: false,
                    contentType: false,
                    processData: false
                });
            });

            $('#formEditPickPoint').submit(function (e) {
                e.preventDefault()
                const formData = new FormData(this);
                formData.append('_method', 'PUT');
                $.ajax({
                    url: "/sudut-panel/admin/pick-point/" + formData.get('id'),
                    type: 'POST',
                    data: formData,
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
                    cache: false,
                    contentType: false,
                    processData: false
                });
            })

            $('.btn-add').on('click', function () {
                $('#formAddPickPoint').show()
                $('#formEditPickPoint').hide()
                $('#myLargeModalLabel').text('Add Pick Point')
            })

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
                            url: "/sudut-panel/admin/pick-point/" + id,
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

            let mapEdit;

            $('.btn-edit').on('click', function () {
                $('#formAddPickPoint').hide();
                $('#formEditPickPoint').show();
                $('#myLargeModalLabel').text('Edit Pick Point');

                const longitude = $(this).data('longitude');
                const latitude = $(this).data('latitude');

                $('#idEdit').val($(this).data('id'));
                $('#tb-longEdit').val(longitude);
                $('#tb-latEdit').val(latitude);
                $('#tb-fnameEdit').val($(this).data('name'));
                $('#tb-textareaEdit').val($(this).data('address'));

                if (mapEdit) {
                    mapEdit.remove();
                }

                mapEdit = L.map('mapEdit').setView([latitude, longitude], 13);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(mapEdit);

                let marker = L.marker([latitude, longitude], { draggable: true }).addTo(mapEdit);

                marker.on('dragend', function (e) {
                    var lat = marker.getLatLng().lat;
                    var lng = marker.getLatLng().lng;

                    $('#tb-latEdit').val(lat);
                    $('#tb-longEdit').val(lng);
                });

                setTimeout(() => {
                    mapEdit.invalidateSize();
                }, 200);
            });

        });

        var map = L.map('map').setView([-7.2575, 112.7521], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        var marker;

        map.on('click', function (e) {
            var lat = e.latlng.lat;
            var lng = e.latlng.lng;

            if (!marker) {
                marker = L.marker([lat, lng], { draggable: true }).addTo(map);
            } else {
                marker.setLatLng([lat, lng]);
            }

            $('#tb-lat').val(lat);
            $('#tb-long').val(lng);
        });

        $(document).on('dragend', function (e) {
            var lat = marker.getLatLng().lat;
            var lng = marker.getLatLng().lng;

            $('#tb-lat').val(lat);
            $('#tb-long').val(lng);
        });
    </script>
    <script src="{{ URL::asset('build/js/vendor.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/dashboards/dashboard.js') }}"></script>
    <script src="{{ URL::asset('build/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/datatable/datatable-basic.init.js') }}"></script>
@endsection
