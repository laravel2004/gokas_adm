@extends('layouts.master')

@section('title', 'Detail Setting Approval List Admin')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('build/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" />
@endsection

@section('pageContent')
    @include('layouts.breadcrumb', ['title' => 'Detail Member List', 'subtitle' => 'Setting Approval', 'link' => 'admin.setting-approval.index'])
    <div class="datatables">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-column flex-md-row justify-content-between mb-2">
                    <div>
                        <h4 class="card-title">Detail Member of {{ $head->name }} Management</h4>
                        <p class="card-subtitle mb-3">
                            This is the list of all detail member head approval. and you can manage them. with the help of this table.
                        </p>
                    </div>
                    <button data-bs-toggle="modal" data-bs-target="#bs-example-modal-lg" class="btn btn-primary btn-add btn-md align-self-start mx-auto mx-md-0">Add New Member</button>
                </div>
                <div class="table-responsive">
                    <table id="zero_config"
                           class="table table-striped table-bordered align-middle">
                        <thead>
                        <!-- start row -->
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Position</th>
                            <th>Department</th>
                            <th>Role</th>
                            <th>Join at</th>
                            <th>Action</th>
                        </tr>
                        <!-- end row -->
                        </thead>
                        <tbody>
                        @foreach($employees as $employee)
                            <tr>
                                <td>{{ $employee->employee->name }}</td>
                                <td>{{ $employee->employee->email }}</td>
                                <td>{{ $employee->employee->position }}</td>
                                <td>{{ $employee->employee->department }}</td>
                                <td>
                                    @if($employee->employee->role == 'approval')
                                        <span class="badge bg-success">Approval</span>
                                    @elseif($employee->employee->role == 'user')
                                        <span class="badge bg-primary">User</span>
                                    @elseif($employee->employee->role == 'canteen')
                                        <span class="badge bg-danger">Canteen</span>
                                    @endif
                                </td>
                                <td>{{ $employee->employee->created_at->format('d F Y') }}</td>
                                <td>
                                    <div class="d-flex gap gap-6">
                                        <button class="btn btn-danger btn-delete btn-sm" data-url="{{ route('admin.setting-approval.destroy', ['id' => '__id__']) }}" data-id="{{ $employee->id }}">
                                            <i class="ti ti-trash"></i>
                                        </button>
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
                <form id="form-add-member">
                    @csrf
                    <div class="modal-header d-flex align-items-center">
                        <h4 class="modal-title" id="myLargeModalLabel">
                            Add New Member for {{ $head->name }}
                        </h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="member-container">
                            <div class="row member-item mb-3">
                                <div class="col-10">
                                    <input hidden name="head_id" value="{{ $head->id }}" />
                                    <select name="members[]" class="form-select" required>
                                        <option selected disabled>Select Member</option>
                                        @foreach($candidates as $candidate)
                                            <option value="{{ $candidate->id }}">{{ $candidate->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-2 d-flex align-items-center">
                                    <button type="button" class="btn btn-success btn-add-member">+</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">
                            Save Members
                        </button>
                        <button type="button" class="btn bg-danger-subtle text-danger" data-bs-dismiss="modal">
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

            $('#form-add-member').submit(function (e) {
                e.preventDefault();
                let formData = new FormData(this);
                $.ajax({
                    url: '{{ route('admin.setting-approval.store') }}',
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
                            window.location.reload();
                        })
                    },
                    error: function (xhr) {
                        let errors = xhr.responseJSON.errors;
                        let errorMessage = '';
                        $.each(errors, function (key, value) {
                            errorMessage += value[0] + '\n';
                        });
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: errorMessage,
                        });
                    }
                });
            })

            $(document).on('click', '.btn-add-member', function() {
                const memberItem = `
                <div class="row member-item mb-3">
                    <div class="col-10">
                        <select name="members[]" class="form-select" required>
                            <option selected disabled>Select Member</option>
                            @foreach($candidates as $candidate)
                <option value="{{ $candidate->id }}">{{ $candidate->name }}</option>
                            @endforeach
                </select>
            </div>
            <div class="col-2 d-flex align-items-center">
                <button type="button" class="btn btn-danger btn-remove-member">-</button>
            </div>
        </div>
`;
                $('#member-container').append(memberItem);
            });

            // Function remove member input
            $(document).on('click', '.btn-remove-member', function() {
                $(this).closest('.member-item').remove();
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
                    confirmButtonText: 'Yes, delete member it!'
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
                                    'Member Deleted!',
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
