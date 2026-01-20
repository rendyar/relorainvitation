@extends('app')

@push('additional-css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
@endpush

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 style="font-size: 16px;">List invitation</h5>

                    <div id="btn" class="d-flex">
                        <button class="btn btn-secondary btn-sm" id="btnExport" style="margin-right: 5px;">
                            <i class='bx bx-export' style="padding: 2px;"></i> Export
                        </button>
                        <button class="btn btn-success btn-sm" id="btnUpload" style="margin-right: 5px;">
                            <i class='bx bx-import' style="padding: 2px;"></i>Upload
                        </button>
                        <button class="btn btn-primary btn-sm" id="btnAdd">
                            <i class='bx bx-plus' style="padding: 2px;"></i> Add
                        </button>
                    </div>
                </div>
                <div class="table-responsive text-nowrap">
                    <table class="table" id="invitationTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Type Invitation</th>
                                <th>Souvenir</th>
                                <th>QR Code</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="invitationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Add Type Invitation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="invitationForm">
                <div class="modal-body">
                    <input type="hidden" id="invitation_id">
                    <div class="row">
                        <div class="col mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" id="name" class="form-control" placeholder="Enter Name" required />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <label class="form-label">Type Invitation</label>
                            <select id="type_invitation_id" class="form-control" required>
                                <option value="">Select Type Invitation</option>
                                @foreach ($typeInvitations as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Upload Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="d-flex justify-content-between align-items-center w-100">
                    <h5 class="modal-title" id="modalTitle">Upload Invitations</h5>
                    <div class="d-flex">
                        <a href="{{ route('type-invitation.template') }}" class="btn btn-sm btn-secondary">
                            <i class='bx bx-download' style="padding: 2px;"></i> Template
                        </a>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                </div>
            </div>
            <form id="uploadForm" action="{{ route('type-invitation.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col mb-3">
                            <label class="form-label">Select File</label>
                            <input type="file" name="file" class="form-control" accept=".xlsx, .xls" required />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('additional-scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function () {
        var table = $('#invitationTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("invitation.data") }}',
            searching: false,
            scrollX: false,
            scrollY: false,
            scrollCollapse: false,
            columns: [
            {
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                orderable: false,
                searchable: false
            },
            {
                data: 'name',
                name: 'name'
            },
            {
                data: 'type_invitation_name',
                name: 'type_invitation_name'
            },
            {
                data: 'souvenir_name',
                name: 'souvenir_name'
            },
            {
                data: 'qr_token',
                name: 'qr_token',
                orderable: false,
                searchable: false
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            }
            ]
        });

        $('#btnAdd').click(function () {
            $('#invitationForm')[0].reset();
            $('#name').val('');
            $('#invitation_id').val('');
            $('#type_invitation_id').val('');
            $('#modalTitle').text('Add Invitation');
            $('#invitationModal').modal('show');
        });

        $('#btnUpload').click(function () {
            $('#uploadForm')[0].reset();
            $('#uploadModal').modal('show');
        });

        $('#btnExport').click(function () {
            window.location.href = '{{ route("type-invitation.export") }}';
        });

        $('#invitationForm').submit(function (e) {
            e.preventDefault();
            let id = $('#invitation_id').val();
            let formData = {
                name: $('#name').val(),
                type_invitation_id: $('#type_invitation_id').val(),
                _token: '{{ csrf_token() }}'
            };

            if (id) {
                formData.id = id;
            }

            $.ajax({
                url: '{{ route("invitation.store") }}',
                method: 'POST',
                data: formData,
                success: function (response) {
                    $('#invitationModal').modal('hide');
                    table.ajax.reload();
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Data saved successfully!',
                        confirmButtonText: 'OK'
                    });
                },
                error: function (xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Error saving data!',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });

        $(document).on('click', '.btn-edit', function () {
            let id = $(this).data('id');
            $.get('{{ url("invitation") }}/edit/' + id, function (data) {
                console.log('tets', data);
                $('#invitation_id').val(data.id);
                $('#type_invitation_id').val(data.type_invitation_id).trigger('change');
                $('#name').val(data.name);
                $('#modalTitle').text('Edit Invitation');
                $('#invitationModal').modal('show');
            });
        });

        $(document).on('click', '.btn-delete', function () {
            let id = $(this).data('id');
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
                        url: '{{ url("invitation/delete") }}/' + id,
                        method: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function () {
                            table.ajax.reload();
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: 'Data deleted successfully!',
                                timer: 2000,
                                showConfirmButton: 'OK',
                            });
                        },
                        error: function (xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Error deleting data!',
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                }
            });
        });
    });
</script>
@endpush
