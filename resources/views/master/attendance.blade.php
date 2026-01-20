@extends('app')

@push('additional-css')
<style>
    .card {
        border-radius: 18px;
        box-shadow: 0 4px 24px rgba(180, 120, 200, 0.08);
    }
    .nav-tabs .nav-link.active {
        color: #fff !important;
        background-color: #b96bb0 !important;
        border-color: #b96bb0 #b96bb0 #fff !important;
        box-shadow: 0 4px 16px rgba(185, 107, 176, 0.15);
    }
    .nav-tabs .nav-link {
        color: #b96bb0 !important;
        background-color: #fff !important;
        border: none !important;
        box-shadow: 0 2px 8px rgba(185, 107, 176, 0.08);
    }
</style>
<link href="https://fonts.googleapis.com/css?family=Dancing+Script:700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush

@push('additional-css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
@endpush

@section('content')
<ul class="nav nav-tabs mb-3" id="checkinTabs" role="tablist" style="border-radius: 16px; overflow: hidden; padding: 6px; border-bottom: none;">
    <li class="nav-item" role="presentation" style="margin-right: 10px;">
        <button class="nav-link active" id="checkin-tab" data-bs-toggle="tab" data-bs-target="#checkin" type="button" role="tab" aria-controls="checkin" aria-selected="true" style="border-radius: 12px; padding: 10px 24px;">
            Check In
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="checkout-tab" data-bs-toggle="tab" data-bs-target="#checkout" type="button" role="tab" aria-controls="checkout" aria-selected="false" style="border-radius: 12px; padding: 10px 24px;">
            Check Out
        </button>
    </li>
</ul>

<div class="tab-content" id="checkinTabsContent" style="padding: 0;">

    <div class="tab-pane fade show active" id="checkin" role="tabpanel" aria-labelledby="checkin-tab">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 style="font-size: 16px;">List attendance check-in</h5>
                        </div>
                        <div class="table-responsive text-nowrap">
                            <table class="table" id="attendanceCheckInTable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Name</th>
                                        <th>Type Invitation</th>
                                        <th>Check In</th>
                                        <th>Check Out</th>
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
    </div>

    <div class="tab-pane fade" id="checkout" role="tabpanel" aria-labelledby="checkout-tab">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 style="font-size: 16px;">List attendance check-out</h5>
                        </div>
                        <div class="table-responsive text-nowrap">
                            <table class="table" id="attendanceCheckOutTable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Name</th>
                                        <th>Type Invitation</th>
                                        <th>Check In</th>
                                        <th>Check Out</th>
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
        var tableCheckIn = $('#attendanceCheckInTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("attendance.data-check-in") }}',
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
                    data: 'guest_name',
                    name: 'guest_name'
                },
                {
                    data: 'type_invitation_name',
                    name: 'type_invitation_name',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'check_in_at',
                    name: 'check_in_at',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'check_out_at',
                    name: 'check_out_at',
                    orderable: false,
                    searchable: false
                }
            ]
        });

        var tableCheckOut = $('#attendanceCheckOutTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("attendance.data-check-out") }}',
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
                    data: 'guest_name',
                    name: 'guest_name'
                },
                {
                    data: 'type_invitation_name',
                    name: 'type_invitation_name',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'check_in_at',
                    name: 'check_in_at',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'check_out_at',
                    name: 'check_out_at',
                    orderable: false,
                    searchable: false
                }
            ]
        });

        $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
            $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
        });
    });
</script>
@endpush
