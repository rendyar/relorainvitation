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
                    <h5 style="font-size: 16px;">List type invitation</h5>
                    <button class="btn btn-primary btn-sm" id="btnAdd">
                        <i class="fas fa-plus"></i> Add Type Invitation
                    </button>
                </div>
                <div class="table-responsive text-nowrap">
                    <table class="table" id="typeInvitationTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Name</th>
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
<div class="modal fade" id="typeInvitationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Add Type Invitation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="typeInvitationForm">
                <div class="modal-body">
                    <input type="hidden" id="type_invitation_id">
                    <div class="row">
                        <div class="col mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" id="name" class="form-control" placeholder="Enter Name" required />
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
@endsection

@push('additional-scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function () {
        var table = $('#typeInvitationTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("type-invitation.data") }}',
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
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            }
            ]
        });

        $('#btnAdd').click(function () {
            $('#typeInvitationForm')[0].reset();
            $('#type_invitation_id').val('');
            $('#modalTitle').text('Add Type Invitation');
            $('#typeInvitationModal').modal('show');
        });

        $('#typeInvitationForm').submit(function (e) {
            e.preventDefault();
            let id = $('#type_invitation_id').val();
            let formData = {
                name: $('#name').val(),
                _token: '{{ csrf_token() }}'
            };

            if (id) {
                formData.id = id;
            }

            $.ajax({
                url: '{{ route("type-invitation.store") }}',
                method: 'POST',
                data: formData,
                success: function (response) {
                    $('#typeInvitationModal').modal('hide');
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
            $.get('{{ url("type-invitation") }}/edit/' + id, function (data) {
                $('#type_invitation_id').val(data.id);
                $('#name').val(data.name);
                $('#modalTitle').text('Edit Type Invitation');
                $('#typeInvitationModal').modal('show');
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
                        url: '{{ url("type-invitation/delete") }}/' + id,
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
