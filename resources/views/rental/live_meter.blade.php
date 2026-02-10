@include('layouts.app')
@include('layouts.header')

<div class="d-none">
    <div class="bg-primary border-bottom p-3 d-flex align-items-center">
        <a class="toggle togglew toggle-2" href="#"><span></span></a>
        <h4 class="font-weight-bold m-0 text-white">Live Taxi Meter</h4>
    </div>
</div>

<section class="py-4 siddhi-main-body" style="background: #f2f6f9;">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <!-- Live Meter Card -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-success text-white">
                        <h4 class="mb-0">🚕 Live Taxi Meter</h4>
                        <small>Order ID: <span id="order-id-display">{{ $id }}</span></small>
                    </div>
                    <div class="card-body">
                        <!-- Status Badge -->
                        <div class="mb-3">
                            <span class="badge badge-lg" id="trip-status-badge">Waiting...</span>
                        </div>

                        <!-- Live Meter Display -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="meter-box p-4 bg-light rounded text-center">
                                    <h6 class="text-muted mb-2">Yurilgan Masofa</h6>
                                    <h2 class="text-primary mb-0" id="live-distance">0.00 km</h2>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="meter-box p-4 bg-light rounded text-center">
                                    <h6 class="text-muted mb-2">Hozirgi Narx</h6>
                                    <h2 class="text-success mb-0" id="live-fare">0 so'm</h2>
                                </div>
                            </div>
                        </div>

                        <!-- Fare Breakdown -->
                        <div class="fare-details p-3 bg-white border rounded">
                            <h5 class="mb-3">Narx Tafsilotlari</h5>
                            <table class="table table-sm table-borderless mb-0">
                                <tr>
                                    <td>Bazaviy narx:</td>
                                    <td class="text-right"><strong id="base-fare">0 so'm</strong></td>
                                </tr>
                                <tr>
                                    <td>Bepul masofa:</td>
                                    <td class="text-right"><span id="included-distance">0 km</span></td>
                                </tr>
                                <tr>
                                    <td>Qo'shimcha km:</td>
                                    <td class="text-right"><span id="extra-km">0 km</span> × <span id="km-rate">0
                                            so'm</span></td>
                                </tr>
                                <tr class="border-top">
                                    <td><strong>Qo'shimcha to'lov:</strong></td>
                                    <td class="text-right"><strong id="extra-charge">0 so'm</strong></td>
                                </tr>
                            </table>
                        </div>

                        <!-- Trip Info -->
                        <div class="trip-info mt-4">
                            <h5 class="mb-3">Safar Ma'lumotlari</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Boshlanish vaqti:</strong> <span id="start-time">-</span></p>
                                    <p><strong>Boshlanish joyi:</strong> <span id="start-location">-</span></p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Oxirgi yangilanish:</strong> <span id="last-update">-</span></p>
                                    <p><strong>Haydovchi:</strong> <span id="driver-name">-</span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Final Fare (shown when trip ends) -->
                <div class="card shadow-sm d-none" id="final-fare-card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">✅ Safar Yakunlandi</h4>
                    </div>
                    <div class="card-body text-center">
                        <h3 class="text-muted mb-2">Jami To'lov</h3>
                        <h1 class="text-success mb-3" id="final-fare-amount">0 so'm</h1>
                        <p class="mb-0">Jami masofa: <strong id="final-distance">0 km</strong></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .meter-box {
        transition: all 0.3s ease;
    }

    .meter-box:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .badge-lg {
        font-size: 1rem;
        padding: 0.5rem 1rem;
    }
</style>

<script src="https://www.gstatic.com/firebasejs/7.16.0/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/7.16.0/firebase-firestore.js"></script>
<script src="https://www.gstatic.com/firebasejs/7.16.0/firebase-auth.js"></script>
<script src="https://www.gstatic.com/firebasejs/7.16.0/firebase-storage.js"></script>
<script src="{{ asset('js/crypto-js.js') }}"></script>
<script src="{{ asset('js/jquery.cookie.js') }}"></script>
<script src="{{ asset('js/jquery.validate.js') }}"></script>

<script type="text/javascript">
    var database = firebase.firestore();
    var orderId = "{{ $id }}";
    var userId = "{{ $user_id }}";

    var currentCurrency = '';
    var currencyAtRight = false;
    var decimal_degits = 0;
    var refCurrency = database.collection('currencies').where('isActive', '==', true);

    // Load currency settings
    refCurrency.get().then(async function (snapshots) {
        var currencyData = snapshots.docs[0].data();
        currentCurrency = currencyData.symbol;
        currencyAtRight = currencyData.symbolAtRight;
        if (currencyData.decimal_degits) {
            decimal_degits = currencyData.decimal_degits;
        }
    });

    // Haversine formula for distance calculation
    function calculateDistance(lat1, lon1, lat2, lon2) {
        const R = 6371; // Radius of Earth in kilometers
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLon = (lon2 - lon1) * Math.PI / 180;
        const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
            Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
            Math.sin(dLon / 2) * Math.sin(dLon / 2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        const distance = R * c;
        return distance; // in kilometers
    }

    // Format currency
    function formatCurrency(amount) {
        const formatted = parseFloat(amount).toFixed(decimal_degits);
        if (currencyAtRight) {
            return formatted + ' ' + currentCurrency;
        } else {
            return currentCurrency + ' ' + formatted;
        }
    }

    // Format date/time
    function formatDateTime(timestamp) {
        if (!timestamp) return '-';
        const date = timestamp.toDate();
        return date.toLocaleString('uz-UZ');
    }

    // Real-time listener for order updates
    database.collection('rental_orders').doc(orderId)
        .onSnapshot((doc) => {
            if (!doc.exists) {
                console.error('Order not found!');
                return;
            }

            const order = doc.data();
            console.log('Order update:', order);

            // Update status badge
            const statusBadge = document.getElementById('trip-status-badge');
            if (order.status === 'In Transit') {
                statusBadge.textContent = '🚗 Safar Davom Etmoqda';
                statusBadge.className = 'badge badge-lg badge-success';
            } else if (order.status === 'Completed') {
                statusBadge.textContent = '✅ Safar Yakunlandi';
                statusBadge.className = 'badge badge-lg badge-primary';

                // Show final fare card
                document.getElementById('final-fare-card').classList.remove('d-none');
                document.getElementById('final-fare-amount').textContent = formatCurrency(order.finalFare || 0);
                document.getElementById('final-distance').textContent = (order.finalDistance || 0).toFixed(2) + ' km';
            } else {
                statusBadge.textContent = '⏳ Kutilmoqda';
                statusBadge.className = 'badge badge-lg badge-warning';
            }

            // Update trip info
            if (order.startTime) {
                document.getElementById('start-time').textContent = formatDateTime(order.startTime);
            }
            if (order.lastUpdateTime) {
                document.getElementById('last-update').textContent = formatDateTime(order.lastUpdateTime);
            }
            if (order.driver && order.driver.name) {
                document.getElementById('driver-name').textContent = order.driver.name;
            }

            // Update package info
            if (order.rentalPackageModel) {
                const pkg = order.rentalPackageModel;
                document.getElementById('base-fare').textContent = formatCurrency(pkg.baseFare || 0);
                document.getElementById('included-distance').textContent = (pkg.includedDistance || 0) + ' km';
                document.getElementById('km-rate').textContent = formatCurrency(pkg.extraKmFare || 0);
            }

            // Update live distance and fare (only if tracking)
            if (order.status === 'In Transit' && order.accumulatedDistance !== undefined) {
                const distance = order.accumulatedDistance || 0;
                document.getElementById('live-distance').textContent = distance.toFixed(2) + ' km';

                // Calculate fare
                const baseFare = order.rentalPackageModel?.baseFare || 0;
                const includedDistance = order.rentalPackageModel?.includedDistance || 0;
                const extraKmFare = order.rentalPackageModel?.extraKmFare || 0;

                let currentFare = baseFare;
                let extraKm = 0;
                let extraCharge = 0;

                if (distance > includedDistance) {
                    extraKm = distance - includedDistance;
                    extraCharge = extraKm * extraKmFare;
                    currentFare = baseFare + extraCharge;
                }

                document.getElementById('live-fare').textContent = formatCurrency(currentFare);
                document.getElementById('extra-km').textContent = extraKm.toFixed(2) + ' km';
                document.getElementById('extra-charge').textContent = formatCurrency(extraCharge);
            }
        }, (error) => {
            console.error('Error listening to order:', error);
            alert('Xatolik yuz berdi: ' + error.message);
        });

    // Page load
    jQuery(document).ready(function () {
        console.log('Live Meter initialized for order:', orderId);
    });
</script>

@include('layouts.footer')