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
        <button class="nav-link active" id="checkin-tab" data-bs-toggle="tab" data-bs-target="#manualCheckin" type="button" role="tab" aria-controls="manualCheckin" aria-selected="true" style="border-radius: 12px; padding: 10px 24px;">
            Check In
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="checkout-tab" data-bs-toggle="tab" data-bs-target="#manualCheckout" type="button" role="tab" aria-controls="manualCheckout" aria-selected="false" style="border-radius: 12px; padding: 10px 24px;">
            Check Out
        </button>
    </li>
</ul>

<div class="tab-content" id="manualCheckinTabsContent" style="padding: 0;">

    <div class="tab-pane fade show active" id="manualCheckin" role="tabpanel" aria-labelledby="checkin-tab">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 style="font-size: 16px;">List manual check-in</h5>
                        </div>
                        <div class="table-responsive text-nowrap">
                            <table class="table" id="manualCheckInTable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Name</th>
                                        <th>Type Invitation</th>
                                        <th>Souvenir</th>
                                        <th>Qr Code</th>
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
    </div>

    <div class="tab-pane fade" id="manualCheckout" role="tabpanel" aria-labelledby="checkout-tab">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 style="font-size: 16px;">List manual check-out</h5>
                        </div>
                        <div class="table-responsive text-nowrap">
                            <table class="table" id="manualCheckOutTable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Name</th>
                                        <th>Type Invitation</th>
                                        <th>Souvenir</th>
                                        <th>Qr Code</th>
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
        var tableCheckIn = $('#manualCheckInTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("check-in-manual.data-check-in") }}',
            searching: true,
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

        var tableCheckOut = $('#manualCheckOutTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("check-in-manual.data-check-out") }}',
            searching: true,
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

        $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
            $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
        });

        $(document).on('click', '.btn-checkin', function () {
            let qrCode = $(this).data('qr-code');
            let name = $(this).data('name');

            Swal.fire({
                title: 'Konfirmasi Check-in',
                html: 'Apakah Anda yakin ingin melakukan check-in tamu atas nama <b style="text-transform: uppercase; color: #b96bb0;">' + name + '</b>?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#b96bb0',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Check-in',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route("check-in") }}',
                        method: 'POST',
                        data: {
                            qr_code: qrCode,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(res) {
                            if (res.success) {
                                let detailHtml = `
                                    <div class="text-start" style="padding: 20px; background: linear-gradient(135deg, #fdf4fd 0%, #fff 100%); border-radius: 12px;">
                                        <div style="text-align: center; margin-bottom: 20px;">
                                            <div style="width: 60px; height: 60px; background: #b96bb0; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 10px;">
                                                <i class="fas fa-check" style="color: white; font-size: 28px;"></i>
                                            </div>
                                        </div>
                                        <div style="background: white; padding: 15px; border-radius: 10px; box-shadow: 0 2px 10px rgba(185, 107, 176, 0.1);">
                                            <div style="display: flex; align-items: center; padding: 10px 0; border-bottom: 1px solid #f0f0f0;">
                                                <i class="fas fa-user" style="color: #b96bb0; width: 24px; margin-right: 12px;"></i>
                                                <div>
                                                    <span style="color: #888; font-size: 12px; display: block;">Nama Tamu</span>
                                                    <span style="color: #333; font-weight: 600; font-size: 15px;">${res.name ?? '-'}</span>
                                                </div>
                                            </div>
                                            <div style="display: flex; align-items: center; padding: 10px 0; border-bottom: 1px solid #f0f0f0;">
                                                <i class="fas fa-envelope" style="color: #b96bb0; width: 24px; margin-right: 12px;"></i>
                                                <div>
                                                    <span style="color: #888; font-size: 12px; display: block;">Jenis Undangan</span>
                                                    <span style="color: #333; font-weight: 600; font-size: 15px;">${res.type_invitation ?? '-'}</span>
                                                </div>
                                            </div>
                                            <div style="display: flex; align-items: center; padding: 10px 0;">
                                                <i class="fas fa-gift" style="color: #b96bb0; width: 24px; margin-right: 12px;"></i>
                                                <div>
                                                    <span style="color: #888; font-size: 12px; display: block;">Souvenir</span>
                                                    <span style="color: #333; font-weight: 600; font-size: 15px;">${res.type_souvenir ?? '-'}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div style="background: white; padding: 12px; border-radius: 8px; margin-top: 15px; border-left: 4px solid #b96bb0;">
                                            <div style="display: flex; align-items: center;">
                                                <i class="fas fa-info-circle" style="color: #b96bb0; margin-right: 8px;"></i>
                                                <span style="color: #666; font-size: 13px;">Check-in berhasil diproses</span>
                                            </div>
                                        </div>
                                    </div>
                                `;
                                Swal.fire({
                                    icon: false,
                                    title: 'Check-in Berhasil',
                                    html: detailHtml,
                                    showConfirmButton: true,
                                    confirmButtonColor: '#b96bb0',
                                    confirmButtonText: 'OK',
                                    customClass: {
                                        popup: 'animated fadeIn'
                                    },
                                    background: 'linear-gradient(135deg, #fdf4fd 0%, #fff 100%)'
                                });
                                tableCheckIn.ajax.reload();
                            } else {
                                let errorHtml = `
                                    <div class="text-start" style="padding: 20px; background: linear-gradient(135deg, #fff5f5 0%, #fff 100%); border-radius: 12px;">
                                        <div style="text-align: center; margin-bottom: 20px;">
                                            <div style="width: 60px; height: 60px; background: #dc3545; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 10px;">
                                                <i class="fas fa-times" style="color: white; font-size: 28px;"></i>
                                            </div>
                                        </div>
                                        <div style="background: white; padding: 15px; border-radius: 10px; box-shadow: 0 2px 10px rgba(220, 53, 69, 0.1);">
                                            <div style="text-align: center; padding: 10px 0;">
                                                <i class="fas fa-exclamation-triangle" style="color: #dc3545; font-size: 24px; margin-bottom: 10px;"></i>
                                                <div>
                                                    <span style="color: #333; font-weight: 600; font-size: 15px; display: block;">${res.message || 'QR tidak valid atau sudah digunakan.'}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div style="background: white; padding: 12px; border-radius: 8px; margin-top: 15px; border-left: 4px solid #dc3545;">
                                            <div style="display: flex; align-items: center;">
                                                <i class="fas fa-info-circle" style="color: #dc3545; margin-right: 8px;"></i>
                                                <span style="color: #666; font-size: 13px;">Silakan coba scan ulang atau hubungi panitia</span>
                                            </div>
                                        </div>
                                    </div>
                                `;
                                Swal.fire({
                                    icon: false,
                                    title: 'Check-in Gagal',
                                    html: errorHtml,
                                    showConfirmButton: true,
                                    confirmButtonColor: '#b96bb0',
                                    confirmButtonText: 'Coba Lagi',
                                    customClass: {
                                        popup: 'animated fadeIn'
                                    },
                                    background: 'linear-gradient(135deg, #fff5f5 0%, #fff 100%)'
                                });
                            }
                        },
                        error: function() {
                            let errorHtml = `
                                <div class="text-start" style="padding: 20px; background: linear-gradient(135deg, #fff5f5 0%, #fff 100%); border-radius: 12px;">
                                    <div style="text-align: center; margin-bottom: 20px;">
                                        <div style="width: 60px; height: 60px; background: #dc3545; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 10px;">
                                            <i class="fas fa-times" style="color: white; font-size: 28px;"></i>
                                        </div>
                                    </div>
                                    <div style="background: white; padding: 15px; border-radius: 10px; box-shadow: 0 2px 10px rgba(220, 53, 69, 0.1);">
                                        <div style="text-align: center; padding: 10px 0;">
                                            <i class="fas fa-wifi" style="color: #dc3545; font-size: 24px; margin-bottom: 10px;"></i>
                                            <div>
                                                <span style="color: #333; font-weight: 600; font-size: 15px; display: block;">Tidak dapat memproses check-in</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div style="background: white; padding: 12px; border-radius: 8px; margin-top: 15px; border-left: 4px solid #dc3545;">
                                        <div style="display: flex; align-items: center;">
                                            <i class="fas fa-info-circle" style="color: #dc3545; margin-right: 8px;"></i>
                                            <span style="color: #666; font-size: 13px;">Periksa koneksi internet dan coba lagi</span>
                                        </div>
                                    </div>
                                </div>
                            `;
                            Swal.fire({
                                icon: false,
                                title: 'Terjadi Kesalahan',
                                html: errorHtml,
                                showConfirmButton: true,
                                confirmButtonColor: '#b96bb0',
                                confirmButtonText: 'Coba Lagi',
                                customClass: {
                                    popup: 'animated fadeIn'
                                },
                                background: 'linear-gradient(135deg, #fff5f5 0%, #fff 100%)'
                            });
                        }
                    });
                }
            });
        });

        $(document).on('click', '.btn-checkout', function () {
            let qrCode = $(this).data('qr-code');
            let name = $(this).data('name');

            Swal.fire({
                title: 'Konfirmasi Check-out',
                html: 'Apakah Anda yakin ingin melakukan check-out tamu atas nama <b style="text-transform: uppercase; color: #b96bb0;">' + name + '</b>?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#b96bb0',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Check-out',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route("check-out") }}',
                        method: 'POST',
                        data: {
                            qr_code: qrCode,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(res) {
                            if (res.success) {
                                let detailHtml = `
                                    <div class="text-start" style="padding: 20px; background: linear-gradient(135deg, #fdf4fd 0%, #fff 100%); border-radius: 12px;">
                                        <div style="text-align: center; margin-bottom: 20px;">
                                            <div style="width: 60px; height: 60px; background: #b96bb0; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 10px;">
                                                <i class="fas fa-sign-out-alt" style="color: white; font-size: 28px;"></i>
                                            </div>
                                        </div>
                                        <div style="background: white; padding: 15px; border-radius: 10px; box-shadow: 0 2px 10px rgba(185, 107, 176, 0.1);">
                                            <div style="display: flex; align-items: center; padding: 10px 0; border-bottom: 1px solid #f0f0f0;">
                                                <i class="fas fa-user" style="color: #b96bb0; width: 24px; margin-right: 12px;"></i>
                                                <div>
                                                    <span style="color: #888; font-size: 12px; display: block;">Nama Tamu</span>
                                                    <span style="color: #333; font-weight: 600; font-size: 15px;">${res.name ?? '-'}</span>
                                                </div>
                                            </div>
                                            <div style="display: flex; align-items: center; padding: 10px 0; border-bottom: 1px solid #f0f0f0;">
                                                <i class="fas fa-envelope" style="color: #b96bb0; width: 24px; margin-right: 12px;"></i>
                                                <div>
                                                    <span style="color: #888; font-size: 12px; display: block;">Jenis Undangan</span>
                                                    <span style="color: #333; font-weight: 600; font-size: 15px;">${res.type_invitation ?? '-'}</span>
                                                </div>
                                            </div>
                                            <div style="display: flex; align-items: center; padding: 10px 0;">
                                                <i class="fas fa-gift" style="color: #b96bb0; width: 24px; margin-right: 12px;"></i>
                                                <div>
                                                    <span style="color: #888; font-size: 12px; display: block;">Souvenir</span>
                                                    <span style="color: #333; font-weight: 600; font-size: 15px;">${res.type_souvenir ?? '-'}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div style="background: white; padding: 12px; border-radius: 8px; margin-top: 15px; border-left: 4px solid #b96bb0;">
                                            <div style="display: flex; align-items: center;">
                                                <i class="fas fa-info-circle" style="color: #b96bb0; margin-right: 8px;"></i>
                                                <span style="color: #666; font-size: 13px;">Check-out berhasil diproses</span>
                                            </div>
                                        </div>
                                    </div>
                                `;
                                Swal.fire({
                                    icon: false,
                                    title: 'Check-out Berhasil',
                                    html: detailHtml,
                                    showConfirmButton: true,
                                    confirmButtonColor: '#b96bb0',
                                    confirmButtonText: 'OK',
                                    customClass: {
                                        popup: 'animated fadeIn'
                                    },
                                    background: 'linear-gradient(135deg, #fdf4fd 0%, #fff 100%)'
                                });
                                tableCheckOut.ajax.reload();
                            } else {
                                let errorHtml = `
                                    <div class="text-start" style="padding: 20px; background: linear-gradient(135deg, #fff5f5 0%, #fff 100%); border-radius: 12px;">
                                        <div style="text-align: center; margin-bottom: 20px;">
                                            <div style="width: 60px; height: 60px; background: #dc3545; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 10px;">
                                                <i class="fas fa-times" style="color: white; font-size: 28px;"></i>
                                            </div>
                                        </div>
                                        <div style="background: white; padding: 15px; border-radius: 10px; box-shadow: 0 2px 10px rgba(220, 53, 69, 0.1);">
                                            <div style="text-align: center; padding: 10px 0;">
                                                <i class="fas fa-exclamation-triangle" style="color: #dc3545; font-size: 24px; margin-bottom: 10px;"></i>
                                                <div>
                                                    <span style="color: #333; font-weight: 600; font-size: 15px; display: block;">${res.message || 'QR tidak valid atau sudah digunakan.'}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div style="background: white; padding: 12px; border-radius: 8px; margin-top: 15px; border-left: 4px solid #dc3545;">
                                            <div style="display: flex; align-items: center;">
                                                <i class="fas fa-info-circle" style="color: #dc3545; margin-right: 8px;"></i>
                                                <span style="color: #666; font-size: 13px;">Silakan coba scan ulang atau hubungi panitia</span>
                                            </div>
                                        </div>
                                    </div>
                                `;
                                Swal.fire({
                                    icon: false,
                                    title: 'Check-out Gagal',
                                    html: errorHtml,
                                    showConfirmButton: true,
                                    confirmButtonColor: '#b96bb0',
                                    confirmButtonText: 'Coba Lagi',
                                    customClass: {
                                        popup: 'animated fadeIn'
                                    },
                                    background: 'linear-gradient(135deg, #fff5f5 0%, #fff 100%)'
                                });
                            }
                        },
                        error: function() {
                            let errorHtml = `
                                <div class="text-start" style="padding: 20px; background: linear-gradient(135deg, #fff5f5 0%, #fff 100%); border-radius: 12px;">
                                    <div style="text-align: center; margin-bottom: 20px;">
                                        <div style="width: 60px; height: 60px; background: #dc3545; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 10px;">
                                            <i class="fas fa-times" style="color: white; font-size: 28px;"></i>
                                        </div>
                                    </div>
                                    <div style="background: white; padding: 15px; border-radius: 10px; box-shadow: 0 2px 10px rgba(220, 53, 69, 0.1);">
                                        <div style="text-align: center; padding: 10px 0;">
                                            <i class="fas fa-wifi" style="color: #dc3545; font-size: 24px; margin-bottom: 10px;"></i>
                                            <div>
                                                <span style="color: #333; font-weight: 600; font-size: 15px; display: block;">Tidak dapat memproses check-out</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div style="background: white; padding: 12px; border-radius: 8px; margin-top: 15px; border-left: 4px solid #dc3545;">
                                        <div style="display: flex; align-items: center;">
                                            <i class="fas fa-info-circle" style="color: #dc3545; margin-right: 8px;"></i>
                                            <span style="color: #666; font-size: 13px;">Periksa koneksi internet dan coba lagi</span>
                                        </div>
                                    </div>
                                </div>
                            `;
                            Swal.fire({
                                icon: false,
                                title: 'Terjadi Kesalahan',
                                html: errorHtml,
                                showConfirmButton: true,
                                confirmButtonColor: '#b96bb0',
                                confirmButtonText: 'Coba Lagi',
                                customClass: {
                                    popup: 'animated fadeIn'
                                },
                                background: 'linear-gradient(135deg, #fff5f5 0%, #fff 100%)'
                            });
                        }
                    });
                }
            });
        });
    });
</script>
@endpush
