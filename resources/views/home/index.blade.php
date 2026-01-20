@extends('app')

@section('content')

<div class="row">
    <div class="col-md-3">
        <div class="card bg-primary" style="opacity: 0.8;">
            <div class="card-body">
                <div class="d-flex justify-content-center align-items-center mb-2">
                    <i class="bx bx-user bx-sm me-2 text-white"></i>
                    <h5 class="card-title text-white mb-0">Total Guest</h5>
                </div>
                <h2 class="mb-0 py-2 text-center text-white" id="totalGuest"></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success" style="opacity: 0.8;">
            <div class="card-body">
                <div class="d-flex justify-content-center align-items-center mb-2">
                    <i class="bx bx-user bx-sm me-2 text-white"></i>
                    <h5 class="card-title text-white mb-0">Checkin</h5>
                </div>
                <h2 class="mb-0 py-2 text-center text-white" id="totalCheckIn"></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning" style="opacity: 0.8;">
            <div class="card-body">
                <div class="d-flex justify-content-center align-items-center mb-2">
                    <i class="bx bx-user bx-sm me-2 text-white"></i>
                    <h5 class="card-title text-white mb-0">Still Inside</h5>
                </div>
                <h2 class="mb-0 py-2 text-center text-white" id="totalInside"></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-danger" style="opacity: 0.8;">
            <div class="card-body">
                <div class="d-flex justify-content-center align-items-center mb-2">
                    <i class="bx bx-user bx-sm me-2 text-white"></i>
                    <h5 class="card-title text-white mb-0">Checkout</h5>
                </div>
                <h2 class="mb-0 py-2 text-center text-white" id="totalCheckOut"></h2>
            </div>
        </div>
    </div>
</div>

<div class="row pt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 style="font-size: 16px; color: #b96bb0; font-weight: bold;">Live Activity Feed</h5>
                <div class="mt-3" id="liveActivity" style="min-height: 230px; max-height: 230px; overflow-y: auto;">

                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 style="font-size: 16px;">Souvenir Stock</h5>
                <div class="mt-3" id="souvenirStock" style="min-height: 230px;">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('additional-scripts')
<script>
    $(document).ready(function() {
        guestCount();
        setInterval(function() {
            reloadPage();
        }, 10000);
    });

    function reloadPage() {
       guestCount();
    }

    function guestCount() {
        $.ajax({
            url: "{{ route('data.total-guests') }}",
            type: "GET",
            dataType: "json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#totalGuest').text(response.total_guests);
                $('#totalCheckIn').text(response.total_check_in);
                $('#totalInside').text(response.total_inside);
                $('#totalCheckOut').text(response.total_check_out);

                if (response.live_activities && response.live_activities.length > 0) {
                    let activityHtml = '';
                    response.live_activities.slice().reverse().forEach(function(activity) {
                        activityHtml += '<div class="d-flex my-2 align-items-center">' +
                            '<b style="min-width: 60px;">' + activity.time + '</b>' +
                            '<span style="min-width: 90px;">' + activity.status + '</span>' +
                            '<b style="min-width: 110px; text-align: center;">' + activity.type_invitation + '</b>' +
                            '<span style="min-width: 150px; display: inline-block;">"' + activity.name + '"</span>' +
                        '</div>';
                    });
                    $('#liveActivity').html(activityHtml);
                } else {
                    $('#liveActivity').html('<div class="text-center" style="margin-top: 100px; color: #b96bb0;"><p>No Data</p></div>');
                }

                if (response.souvenir_data && response.souvenir_data.length > 0) {
                    let souvenirHtml = '';
                    response.souvenir_data.forEach(function(souvenir) {
                        let redeemed = souvenir.quantity - souvenir.current_stock;
                        let percentage = souvenir.quantity > 0 ? (redeemed / souvenir.quantity) * 100 : 0;
                        
                        let bgClass = '';
                        if (percentage >= 100) {
                            bgClass = 'bg-success';
                        } else if (percentage >= 75) {
                            bgClass = 'bg-danger';
                        } else if (percentage >= 50) {
                            bgClass = 'bg-warning';
                        } else if (percentage >= 25) {
                            bgClass = 'bg-info';
                        } else {
                            bgClass = 'bg-secondary';
                        }
                        
                        souvenirHtml += '<div class="mb-2">' +
                            '<div class="d-flex justify-content-between mb-1">' +
                            '<span>' + souvenir.name + '</span>' +
                            '<span><small>Redeemed: ' + redeemed + ' / Total: ' + souvenir.quantity + '</small></span>' +
                            '</div>' +
                            '<div class="progress" style="height: 10px;">' +
                            '<div class="progress-bar ' + bgClass + '" role="progressbar" style="width: ' + percentage + '%;" aria-valuenow="' + percentage + '" aria-valuemin="0" aria-valuemax="100"></div>' +
                            '</div>' +
                            '<small class="text-muted">Remaining: ' + souvenir.current_stock + '</small>' +
                            '</div>';
                    });
                    $('#souvenirStock').html(souvenirHtml);
                } else {
                    $('#souvenirStock').html('<div class="text-center" style="margin-top: 100px;"><p>No Data</p></div>');
                }
            },
            error: function(xhr, status, error) {
                console.error("Error fetching total guest:", error);
                if (xhr.status === 401) {
                    console.error("Authentication failed. Please check your login status.");
                }
            }
        });
    }
</script>
@endpush
