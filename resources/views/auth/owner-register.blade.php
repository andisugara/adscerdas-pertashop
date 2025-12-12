<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Owner Registration - Pertashop</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
        <style>
            body {
                background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
                min-height: 100vh;
                padding: 40px 0;
            }

            .register-container {
                max-width: 800px;
                margin: 0 auto;
            }

            .card {
                border: none;
                border-radius: 15px;
                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            }

            .card-header {
                background: white;
                color: #333;
                border-radius: 15px 15px 0 0 !important;
                padding: 30px;
                border-bottom: 1px solid #e0e0e0;
            }

            .brand-logo {
                display: flex;
                align-items: center;
                gap: 15px;
                margin-bottom: 15px;
            }

            .brand-logo img {
                height: 40px;
            }

            .brand-logo h2 {
                margin: 0;
                font-size: 28px;
                font-weight: 700;
            }

            .plan-option {
                border: 2px solid #e0e0e0;
                border-radius: 10px;
                padding: 20px;
                cursor: pointer;
                transition: all 0.3s;
                margin-bottom: 15px;
            }

            .plan-option:hover {
                border-color: #dc3545;
                transform: translateY(-2px);
            }

            .plan-option.active {
                border-color: #dc3545;
                background: #fff5f5;
            }

            .plan-option input[type="radio"] {
                display: none;
            }

            .plan-price {
                font-size: 32px;
                font-weight: bold;
                color: #dc3545;
            }

            .btn-primary {
                background: #dc3545;
                border: none;
                padding: 12px 30px;
            }

            .btn-primary:hover {
                background: #c82333;
            }

            .form-label {
                font-weight: 600;
                margin-bottom: 8px;
            }

            .section-title {
                font-size: 18px;
                font-weight: 700;
                color: #333;
                margin: 30px 0 20px;
                padding-bottom: 10px;
                border-bottom: 2px solid #dc3545;
            }
        </style>
    </head>

    <body>
        <div class="register-container">
            <div class="card">
                <div class="card-header">
                    <div class="brand-logo">
                        <img src="{{ asset('images/logo.png') }}" alt="Pertashop Logo">
                        <h2>Daftar Pertashop</h2>
                    </div>
                    <p class="mb-0">Mulai kelola SPBU Anda dengan trial gratis {{ $trialDays }} hari</p>
                </div>
                <div class="card-body p-4 p-md-5">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <h5 class="alert-heading"><i class="bi bi-exclamation-triangle-fill me-2"></i>Terjadi
                                Kesalahan!</h5>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('owner.register') }}" method="POST" id="registerForm">
                        @csrf

                        <!-- Personal Information -->
                        <div class="section-title">
                            <i class="bi bi-person me-2"></i>Informasi Personal
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control form-control-lg"
                                    value="{{ old('name') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control form-control-lg"
                                    value="{{ old('email') }}" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Password <span class="text-danger">*</span></label>
                                <input type="password" name="password" class="form-control form-control-lg" required>
                                <small class="text-muted">Minimal 8 karakter</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                                <input type="password" name="password_confirmation" class="form-control form-control-lg"
                                    required>
                            </div>
                        </div>

                        <!-- Initial Data -->
                        <div class="section-title">
                            <i class="bi bi-database me-2"></i>Data Awal Pertashop
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Stok Awal (Liter) <span class="text-danger">*</span></label>
                                <input type="text" name="stok_awal"
                                    class="form-control form-control-lg decimal-input"
                                    value="{{ old('stok_awal', '0') }}" required placeholder="Contoh: 1000.500">
                                <small class="text-muted">Stok BBM awal. Gunakan titik (.) untuk desimal (3 digit).
                                    Contoh: 1500.750</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Totalisator Awal (Liter) <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="totalisator_awal"
                                    class="form-control form-control-lg decimal-input"
                                    value="{{ old('totalisator_awal', '0') }}" required placeholder="Contoh: 5000.250">
                                <small class="text-muted">Angka totalisator awal. Gunakan titik (.) untuk desimal (3
                                    digit). Contoh: 5000.250</small>
                            </div>
                        </div>

                        <!-- Subscription Plan -->
                        <div class="section-title">
                            <i class="bi bi-credit-card me-2"></i>Pilih Paket Berlangganan
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <label class="plan-option" onclick="selectPlan('trial', this)">
                                    <input type="radio" name="plan" value="trial"
                                        {{ old('plan', 'trial') === 'trial' ? 'checked' : '' }}>
                                    <div class="text-center">
                                        <div class="plan-price">GRATIS</div>
                                        <h5 class="mt-2">Trial</h5>
                                        <p class="text-muted mb-2">{{ $trialDays }} hari</p>
                                        <ul class="list-unstyled text-start small">
                                            <li><i class="bi bi-check-circle-fill text-success me-2"></i>Semua fitur
                                            </li>
                                            <li><i class="bi bi-check-circle-fill text-success me-2"></i>Multi outlet
                                            </li>
                                            <li><i class="bi bi-check-circle-fill text-success me-2"></i>Tanpa CC</li>
                                        </ul>
                                    </div>
                                </label>
                            </div>
                            <div class="col-md-4">
                                <label class="plan-option" onclick="selectPlan('monthly', this)">
                                    <input type="radio" name="plan" value="monthly"
                                        {{ old('plan') === 'monthly' ? 'checked' : '' }}>
                                    <div class="text-center">
                                        <div class="plan-price">Rp 100rb</div>
                                        <h5 class="mt-2">Monthly</h5>
                                        <p class="text-muted mb-2">Per bulan</p>
                                        <ul class="list-unstyled text-start small">
                                            <li><i class="bi bi-check-circle-fill text-success me-2"></i>Semua fitur
                                            </li>
                                            <li><i class="bi bi-check-circle-fill text-success me-2"></i>Unlimited
                                                outlet</li>
                                            <li><i class="bi bi-check-circle-fill text-success me-2"></i>Support
                                                prioritas</li>
                                        </ul>
                                    </div>
                                </label>
                            </div>
                            <div class="col-md-4">
                                <label class="plan-option" onclick="selectPlan('yearly', this)">
                                    <input type="radio" name="plan" value="yearly"
                                        {{ old('plan') === 'yearly' ? 'checked' : '' }}>
                                    <div class="text-center">
                                        <div class="plan-price">Rp 1jt</div>
                                        <h5 class="mt-2">Yearly</h5>
                                        <p class="text-muted mb-2">Per tahun</p>
                                        <ul class="list-unstyled text-start small">
                                            <li><i class="bi bi-check-circle-fill text-success me-2"></i>Hemat 200rb
                                            </li>
                                            <li><i class="bi bi-check-circle-fill text-success me-2"></i>Training
                                                gratis</li>
                                            <li><i class="bi bi-check-circle-fill text-success me-2"></i>Support
                                                prioritas</li>
                                        </ul>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Payment Method (for paid plans) -->
                        <div id="paymentMethodSection" style="display: none;">
                            <div class="section-title">
                                <i class="bi bi-cash-coin me-2"></i>Metode Pembayaran
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="payment_method"
                                            value="manual" id="manualPayment"
                                            {{ old('payment_method', 'manual') === 'manual' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="manualPayment">
                                            <strong>Transfer Manual</strong>
                                            <p class="text-muted small mb-0">Upload bukti transfer, tunggu approval</p>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="payment_method"
                                            value="duitku" id="duitkuPayment"
                                            {{ old('payment_method') === 'duitku' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="duitkuPayment">
                                            <strong>Duitku Payment Gateway</strong>
                                            <p class="text-muted small mb-0">Bayar otomatis, langsung aktif</p>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('login') }}" class="btn btn-link">Sudah punya akun? Login</a>
                            <button type="submit" class="btn btn-primary btn-lg px-5">
                                <i class="bi bi-check-circle me-2"></i>Daftar Sekarang
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            // Auto-convert comma to dot for decimal inputs
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.decimal-input').forEach(input => {
                    input.addEventListener('blur', function() {
                        // Replace comma with dot
                        this.value = this.value.replace(/,/g, '.');

                        // Remove multiple dots, keep only first one
                        const parts = this.value.split('.');
                        if (parts.length > 2) {
                            this.value = parts[0] + '.' + parts.slice(1).join('');
                        }

                        // Ensure valid number
                        if (this.value && !isNaN(this.value)) {
                            this.value = parseFloat(this.value).toString();
                        }
                    });

                    // Only allow numbers, dot, and comma
                    input.addEventListener('keypress', function(e) {
                        const char = String.fromCharCode(e.which);
                        if (!/[\d.,]/.test(char)) {
                            e.preventDefault();
                        }
                    });
                });
            });

            function selectPlan(plan, element) {
                // Remove active class from all plan options
                document.querySelectorAll('.plan-option').forEach(el => el.classList.remove('active'));

                // Add active class to selected plan
                element.classList.add('active');

                // Check the radio button
                element.querySelector('input[type="radio"]').checked = true;

                // Show/hide payment method section
                const paymentSection = document.getElementById('paymentMethodSection');
                if (plan === 'trial') {
                    paymentSection.style.display = 'none';
                } else {
                    paymentSection.style.display = 'block';
                }
            }

            // Initialize on page load
            document.addEventListener('DOMContentLoaded', function() {
                const selectedPlan = document.querySelector('input[name="plan"]:checked');
                if (selectedPlan) {
                    const planOption = selectedPlan.closest('.plan-option');
                    if (planOption) {
                        planOption.classList.add('active');
                    }
                    if (selectedPlan.value !== 'trial') {
                        document.getElementById('paymentMethodSection').style.display = 'block';
                    }
                }
            });
        </script>
    </body>

</html>
