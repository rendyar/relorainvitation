@extends('app')

@push('additional-css')
<style>
    #reader-checkin, #reader-checkout {
        width: 100%;
        max-width: 420px;
        margin: auto;
        min-height: 300px;
    }

    .camera-placeholder {
        width: 100%;
        max-width: 420px;
        height: 300px;
        margin: auto;
        background: linear-gradient(135deg, #f5f5f5 25%, #e0e0e0 25%, #e0e0e0 50%, #f5f5f5 50%, #f5f5f5 75%, #e0e0e0 75%, #e0e0e0);
        background-size: 20px 20px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        color: #888;
        border: 2px dashed #ddd;
    }

    .camera-placeholder i {
        font-size: 48px;
        margin-bottom: 16px;
        color: #b96bb0;
    }

    .scan-status {
        font-size: 16px;
        font-weight: 600;
    }
    .wedding-header {
        text-align: center;
        margin-bottom: 1rem;
    }
    .wedding-header img {
        width: 80px;
        margin-bottom: 10px;
    }
    .card {
        border-radius: 18px;
        box-shadow: 0 4px 24px rgba(180, 120, 200, 0.08);
    }
    .wedding-title {
        font-family: 'Dancing Script', cursive, sans-serif;
        font-size: 2rem;
        color: #b96bb0;
        letter-spacing: 1px;
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

    #startScan-checkin, #stopScan-checkin, #startScan-checkout, #stopScan-checkout {
        color: #fff !important;
        background-color: #b96bb0 !important;
        border: none !important;
        box-shadow: 0 2px 8px rgba(185, 107, 176, 0.08);
    }
</style>
<link href="https://fonts.googleapis.com/css?family=Dancing+Script:700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
                    <div class="card-header wedding-header">
                        <img src="https://cdn.pixabay.com/photo/2013/07/13/12/46/rose-146916_1280.png" alt="Wedding Flower">
                        <div class="wedding-title">Wedding Check-In</div>
                        <h5 class="mb-0">Scan QR Code – Check In</h5>
                    </div>
                    <div class="card-body text-center">
                        <div id="reader-checkin">
                            <div class="camera-placeholder" id="placeholder-checkin">
                                <i class="fas fa-camera"></i>
                                <span>Klik "Start Scan" untuk memulai</span>
                            </div>
                        </div>
                        <div class="mt-3">
                            <span class="scan-status" id="scanStatus-checkin" style="color: #b96bb0;">
                                Arahkan QR Code ke kamera
                            </span>
                        </div>
                        <div class="mt-4">
                            <button class="btn btn-primary" id="startScan-checkin">Start Scan</button>
                            <button class="btn btn-danger d-none" id="stopScan-checkin">Stop Scan</button>
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
                    <div class="card-header wedding-header">
                        <img src="https://cdn.pixabay.com/photo/2013/07/13/12/46/rose-146916_1280.png" alt="Wedding Flower">
                        <div class="wedding-title">Wedding Check-Out</div>
                        <h5 class="mb-0">Scan QR Code – Check Out</h5>
                    </div>
                    <div class="card-body text-center">
                        <div id="reader-checkout">
                            <div class="camera-placeholder" id="placeholder-checkout">
                                <i class="fas fa-camera"></i>
                                <span>Klik "Start Scan" untuk memulai</span>
                            </div>
                        </div>
                        <div class="mt-3">
                            <span class="scan-status text-muted" id="scanStatus-checkout">
                                Arahkan QR Code ke kamera
                            </span>
                        </div>
                        <div class="mt-4">
                            <button class="btn btn-primary" id="startScan-checkout">Start Scan</button>
                            <button class="btn btn-danger d-none" id="stopScan-checkout">Stop Scan</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('additional-scripts')
<script src="https://unpkg.com/html5-qrcode"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let html5QrCodeCheckin = null;
    let html5QrCodeCheckout = null;
    let isScanningCheckin = false;
    let isScanningCheckout = false;

    $('#startScan-checkin').on('click', function () {
        hidePlaceholder('checkin');
        if (html5QrCodeCheckin) {
            html5QrCodeCheckin.clear();
        }
        html5QrCodeCheckin = new Html5Qrcode("reader-checkin");
        isScanningCheckin = true;
        Html5Qrcode.getCameras().then(devices => {
            if (devices && devices.length) {
                let cameraId = devices.find(d => /back|rear|environment/i.test(d.label))?.id || devices[0].id;
                html5QrCodeCheckin.start(
                    cameraId,
                    { fps: 10, qrbox: 250 },
                    (decodedText) => onScanSuccess(decodedText)
                ).then(() => {
                    setScanStatus('Scanning...', 'info');
                    toggleButtons(true);
                }).catch(err => {
                    setScanStatus(`Gagal mengakses kamera: ${err}`, 'danger');
                    resetScanView('checkin');
                });
            } else {
                setScanStatus('Tidak ada kamera terdeteksi', 'danger');
                resetScanView('checkin');
            }
        }).catch(err => {
            alert(err);
            setScanStatus(`Gagal mendapatkan kamera: ${err}`, 'danger');
            resetScanView('checkin');
        });
    });

    $('#stopScan-checkin').on('click', function () {
        if (html5QrCodeCheckin && isScanningCheckin) {
            html5QrCodeCheckin.stop().then(() => {
                resetScanView('checkin');
                html5QrCodeCheckin.clear();
                html5QrCodeCheckin = null;

                var reader = document.getElementById('reader-checkin');
                reader.innerHTML = `
                    <div class="camera-placeholder" id="placeholder-checkin">
                        <i class="fas fa-camera"></i>
                        <span>Klik "Start Scan" untuk memulai</span>
                    </div>
                `;
            }).catch(() => {
                resetScanView('checkin');

                var reader = document.getElementById('reader-checkin');
                reader.innerHTML = `
                    <div class="camera-placeholder" id="placeholder-checkin">
                        <i class="fas fa-camera"></i>
                        <span>Klik "Start Scan" untuk memulai</span>
                    </div>
                `;
            });
        }
        isScanningCheckin = false;
        toggleButtons(false);
        setScanStatus('Arahkan QR Code ke kamera', 'muted');
    });

    $('#startScan-checkout').on('click', function () {
        hidePlaceholder('checkout');
        if (html5QrCodeCheckout) {
            html5QrCodeCheckout.clear();
        }
        html5QrCodeCheckout = new Html5Qrcode("reader-checkout");
        isScanningCheckout = true;
        Html5Qrcode.getCameras().then(devices => {
            if (devices && devices.length) {
                let cameraId = devices.find(d => /back|rear|environment/i.test(d.label))?.id || devices[0].id;
                html5QrCodeCheckout.start(
                    cameraId,
                    { fps: 10, qrbox: 250 },
                    (decodedText) => onScanSuccessCheckout(decodedText)
                ).then(() => {
                    setScanStatusCheckout('Scanning...', 'info');
                    toggleButtonsCheckout(true);
                }).catch(err => {
                    setScanStatusCheckout(`Gagal mengakses kamera: ${err}`, 'danger');
                    resetScanView('checkout');
                });
            } else {
                setScanStatusCheckout('Tidak ada kamera terdeteksi', 'danger');
                resetScanView('checkout');
            }
        }).catch(err => {
            setScanStatusCheckout(`Gagal mendapatkan kamera: ${err}`, 'danger');
            resetScanView('checkout');
        });
    });

    $('#stopScan-checkout').on('click', function () {
        if (html5QrCodeCheckout && isScanningCheckout) {
            html5QrCodeCheckout.stop().then(() => {
                resetScanView('checkout');
                html5QrCodeCheckout.clear();
                html5QrCodeCheckout = null;

                var reader = document.getElementById('reader-checkout');
                reader.innerHTML = `
                    <div class="camera-placeholder" id="placeholder-checkout">
                        <i class="fas fa-camera"></i>
                        <span>Klik "Start Scan" untuk memulai</span>
                    </div>
                `;
            }).catch(() => {
                resetScanView('checkout');

                var reader = document.getElementById('reader-checkout');
                reader.innerHTML = `
                    <div class="camera-placeholder" id="placeholder-checkout">
                        <i class="fas fa-camera"></i>
                        <span>Klik "Start Scan" untuk memulai</span>
                    </div>
                `;
            });
        }
        isScanningCheckout = false;
        toggleButtonsCheckout(false);
        setScanStatusCheckout('Arahkan QR Code ke kamera', 'muted');
    });

    function setScanStatus(message, type = 'muted') {
        $('#scanStatus-checkin').html(`<span style="color: #b96bb0">${message}</span>`);
    }

    function setScanStatusCheckout(message, type = 'muted') {
        $('#scanStatus-checkout').html(`<span style="color: #b96bb0">${message}</span>`);
    }

    function toggleButtons(scanning) {
        $('#startScan-checkin').toggleClass('d-none', scanning);
        $('#stopScan-checkin').toggleClass('d-none', !scanning);
    }

    function toggleButtonsCheckout(scanning) {
        $('#startScan-checkout').toggleClass('d-none', scanning);
        $('#stopScan-checkout').toggleClass('d-none', !scanning);
    }

    function showPlaceholder(type) {
        var placeholder = document.getElementById(`placeholder-${type}`);
        if (placeholder) {
            placeholder.style.display = 'flex';
        }
    }

    function hidePlaceholder(type) {
        var placeholder = document.getElementById(`placeholder-${type}`);
        if (placeholder) {
            placeholder.style.display = 'none';
        }
    }

    function resetScanView(type) {
        var reader = document.getElementById(`reader-${type}`);
        var videoElements = reader.querySelectorAll('video');
        videoElements.forEach(video => video.remove());

        showPlaceholder(type);

        if (type === 'checkin') {
            setScanStatus('Arahkan QR Code ke kamera', 'muted');
        } else {
            setScanStatusCheckout('Arahkan QR Code ke kamera', 'muted');
        }
    }

    function onScanSuccess(decodedText) {
        if (!isScanningCheckin) return;
        isScanningCheckin = false;
        html5QrCodeCheckin.stop().then(() => {
            resetScanView('checkin');
        });
        setScanStatus('QR Terdeteksi', 'success');
        let url = @json(route('check-in'));
        $.ajax({
            url: url,
            method: "POST",
            data: {
                qr_code: decodedText,
                _token: "{{ csrf_token() }}"
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
                                <div style="display: flex; align-items: center; padding: 10px 0;">
                                    <i class="fas fa-envelope" style="color: #b96bb0; width: 24px; margin-right: 12px;"></i>
                                    <div>
                                        <span style="color: #888; font-size: 12px; display: block;">Jenis Undangan</span>
                                        <span style="color: #333; font-weight: 600; font-size: 15px;">${res.type_invitation ?? '-'}</span>
                                    </div>
                                </div>
                            </div>
                            <div style="background: white; padding: 12px; border-radius: 8px; margin-top: 15px; border-left: 4px solid #b96bb0;">
                                <div style="display: flex; align-items: center;">
                                    <i class="fas fa-info-circle" style="color: #b96bb0; margin-right: 8px;"></i>
                                    <span style="color: #666; font-size: 13px;">Check-in berhasil diproses</span>
                                </div>
                            </div>
                            <div style="text-align: center; margin-top: 15px;">
                                <span style="color: #999; font-size: 12px;">Otomatis tertutup dalam <b id="countdown">10</b> detik</span>
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
                        background: 'linear-gradient(135deg, #fdf4fd 0%, #fff 100%)',
                        timer: 10000,
                        timerProgressBar: true,
                        didOpen: () => {
                            const countdown = Swal.getPopup().querySelector('#countdown');
                            const timerInterval = setInterval(() => {
                                const timeLeft = Math.ceil(Swal.getTimerLeft() / 1000);
                                if (countdown) {
                                    countdown.textContent = timeLeft;
                                }
                            }, 100);
                            Swal.showLoading = () => {
                                clearInterval(timerInterval);
                            }
                        },
                        didClose: () => {
                            // Restart camera after modal closes
                            $('#startScan-checkin').click();
                        }
                    });
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
                        background: 'linear-gradient(135deg, #fff5f5 0%, #fff 100%)',
                        didClose: () => {
                            // Restart camera after modal closes
                            $('#startScan-checkin').click();
                        }
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
                    background: 'linear-gradient(135deg, #fff5f5 0%, #fff 100%)',
                    didClose: () => {
                        // Restart camera after modal closes
                        $('#startScan-checkin').click();
                    }
                });
            },
            complete: function() {
                toggleButtons(false);
            }
        });
    }

    function onScanSuccessCheckout(decodedText) {
        if (!isScanningCheckout) return;
        isScanningCheckout = false;
        html5QrCodeCheckout.stop().then(() => {
            resetScanView('checkout');
        });
        setScanStatusCheckout('QR Terdeteksi', 'success');
        let url = @json(route('check-out'));
        $.ajax({
            url: url,
            method: "POST",
            data: {
                qr_code: decodedText,
                _token: "{{ csrf_token() }}"
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
                            </div>
                            <div style="background: white; padding: 12px; border-radius: 8px; margin-top: 15px; border-left: 4px solid #b96bb0;">
                                <div style="display: flex; align-items: center;">
                                    <i class="fas fa-info-circle" style="color: #b96bb0; margin-right: 8px;"></i>
                                    <span style="color: #666; font-size: 13px;">Check-out berhasil diproses</span>
                                </div>
                            </div>
                            <div style="text-align: center; margin-top: 15px;">
                                <span style="color: #999; font-size: 12px;">Otomatis tertutup dalam <b id="countdown-checkout">10</b> detik</span>
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
                        background: 'linear-gradient(135deg, #fdf4fd 0%, #fff 100%)',
                        timer: 10000,
                        timerProgressBar: true,
                        didOpen: () => {
                            const countdown = Swal.getPopup().querySelector('#countdown-checkout');
                            const timerInterval = setInterval(() => {
                                const timeLeft = Math.ceil(Swal.getTimerLeft() / 1000);
                                if (countdown) {
                                    countdown.textContent = timeLeft;
                                }
                            }, 100);
                            Swal.showLoading = () => {
                                clearInterval(timerInterval);
                            }
                        },
                        didClose: () => {
                            // Restart camera after modal closes
                            $('#startScan-checkout').click();
                        }
                    });
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
                        background: 'linear-gradient(135deg, #fff5f5 0%, #fff 100%)',
                        didClose: () => {
                            // Restart camera after modal closes
                            $('#startScan-checkout').click();
                        }
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
                    background: 'linear-gradient(135deg, #fff5f5 0%, #fff 100%)',
                    didClose: () => {
                        // Restart camera after modal closes
                        $('#startScan-checkout').click();
                    }
                });
            },
            complete: function() {
                toggleButtonsCheckout(false);
            }
        });
    }
</script>
@endpush

