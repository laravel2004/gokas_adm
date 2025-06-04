@extends('layouts.master')

@section('title', 'GPS Tracking Admin')

@section('css')

@endsection

@section('pageContent')

    <div class="col-12">
        <form id="addEditDriverTask">
            @csrf
            <div class="card">
                <div class="px-4 py-3 border-bottom">
                    <h4 class="card-title mb-0">Driver Task Manager</h4>
                </div>
                <div class="card-body p-4 border-bottom">
                    <h5 class="fs-4 fw-semibold mb-4">Task Detail</h5>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-4">
                                <label class="form-label">Driver Name</label>
                                <select id="driverId" name="driver_id" class="form-select" aria-label="Default select example">
                                    <option selected="">Select Driver</option>
                                    @foreach($driverNames as $driverName)
                                        <option value="{{ $driverName->id }}" >{{ $driverName->name  }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="form-label">Origin Pick</label>
                                <select id="originPick" name="start_pick_point_id" class="form-select" aria-label="Default select example">
                                    <option selected="">Select Origin Pick Point</option>
                                    @foreach($pickPoints as $pickPoint)
                                        <option value="{{ $pickPoint->id }}" >{{ $pickPoint->name }} [{{ $pickPoint->address }}]</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-4">
                                <label class="form-label">Progress</label>
                                <select id="status" name="status" class="form-select" aria-label="Default select example">
                                    <option selected="">Select Progress</option>
                                    <option value="not_started">Not Started</option>
                                    <option value="on_progress">On Progress</option>
                                    <option value="finish">Finish</option>
                                </select>
                            </div>
                            <div>
                                <label class="form-label">Destination Pick</label>
                                <select id="destinationPick" name="end_pick_point_id" class="form-select" aria-label="Default select example">
                                    <option selected="">Select Destination Pick Point</option>
                                    @foreach($pickPoints as $pickPoint)
                                        <option value="{{ $pickPoint->id }}" >{{ $pickPoint->name }} [{{ $pickPoint->address }}]</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <h5 class="fs-4 fw-semibold mb-4">Time & Date</h5>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-4">
                                <label for="exampleInputfirstname4" class="form-label">Start Date</label>
                                <input value="{{ \Carbon\Carbon::parse($driverTask->start_date)->format('Y-m-d') }}" type="date" name="start_date" class="form-control" id="exampleInputfirstname4" />
                            </div>
                            <div class="mb-4">
                                <label for="exampleInputfirstname4" class="form-label">Start Time</label>
                                <input value="{{ \Carbon\Carbon::parse($driverTask->start_time)->format('H:i') }}"
                                       type="time" name="start_time" class="form-control" id="exampleInputfirstname4" />
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-4">
                                <label for="exampleInputfirstname4" class="form-label">End Date</label>
                                <input value="{{ \Carbon\Carbon::parse($driverTask->end_date)->format('Y-m-d') }}"  type="date" name="end_date" class="form-control" id="exampleInputfirstname4" />
                            </div>
                            <div class="mb-4">
                                <label for="exampleInputfirstname4" class="form-label">End Time</label>
                                <input value="{{ \Carbon\Carbon::parse($driverTask->start_time)->format('H:i') }}" type="time" name="end_time" class="form-control" id="exampleInputfirstname4" />
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex align-items-center gap-3">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <a href="{{ route('admin.driver-task') }}" class="btn bg-danger-subtle text-danger">Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

@endsection

@section('scripts')
    <script>
        $(document).ready(function () {

            $('#driverId').val('{{ $driverTask->driver_id }}');
            $('#originPick').val('{{ $driverTask->start_pick_point_id }}');
            $('#status').val('{{ $driverTask->status }}');
            $('#destinationPick').val('{{ $driverTask->end_pick_point_id }}');

            $('#addEditDriverTask').submit(function (e) {
                e.preventDefault();
                let formData = new FormData(this);
                $.ajax({
                    url: '{{ route('admin.driver-task.store') }}',
                    type: 'POST',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = '{{ route('admin.driver-task') }}';
                            }
                        });
                    },
                    error: function (response) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.responseJSON.message,
                        });
                    }
                });
            });
        });
    </script>
    <script src="{{ URL::asset('build/js/vendor.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/dashboards/dashboard.js') }}"></script>
@endsection
