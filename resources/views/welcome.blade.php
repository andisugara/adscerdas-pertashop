<!DOCTYPE html>
<html lang="id">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Pertashop - Sistem Manajemen SPBU Modern</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
            rel="stylesheet">
        <style>
            * {
                font-family: 'Poppins', sans-serif;
            }

            body {
                overflow-x: hidden;
            }

            /* Navbar */
            .navbar {
                padding: 20px 0;
                background: rgba(255, 255, 255, 0.98);
                box-shadow: 0 2px 20px rgba(0, 0, 0, 0.05);
                backdrop-filter: blur(10px);
            }

            .navbar-brand {
                font-size: 28px;
                font-weight: 800;
                background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
            }

            .navbar-brand img {
                height: 35px;
                margin-right: 10px;
            }

            /* Hero Section */
            .hero-section {
                background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
                padding: 120px 0 100px;
                color: white;
                position: relative;
                overflow: hidden;
            }

            .hero-section::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
                opacity: 0.3;
            }

            .hero-section h1 {
                font-size: 3.5rem;
                font-weight: 800;
                margin-bottom: 25px;
                line-height: 1.2;
            }

            .hero-section .lead {
                font-size: 1.3rem;
                margin-bottom: 40px;
                opacity: 0.95;
                font-weight: 300;
            }

            .btn-hero {
                padding: 15px 40px;
                font-size: 18px;
                font-weight: 600;
                border-radius: 50px;
                transition: all 0.3s;
                text-decoration: none;
                display: inline-block;
            }

            .btn-hero-primary {
                background: white;
                color: #dc3545;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            }

            .btn-hero-primary:hover {
                transform: translateY(-3px);
                box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
                color: #dc3545;
            }

            .btn-hero-outline {
                background: transparent;
                color: white;
                border: 2px solid white;
            }

            .btn-hero-outline:hover {
                background: white;
                color: #dc3545;
                transform: translateY(-3px);
            }

            /* Hero Image */
            .hero-image {
                position: relative;
                animation: float 3s ease-in-out infinite;
            }

            @keyframes float {

                0%,
                100% {
                    transform: translateY(0px);
                }

                50% {
                    transform: translateY(-20px);
                }
            }

            .mockup-card {
                background: white;
                border-radius: 20px;
                padding: 30px;
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            }

            .mockup-bar {
                height: 60px;
                background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
                border-radius: 10px;
                margin-bottom: 20px;
            }

            .mockup-content {
                height: 15px;
                background: #e0e0e0;
                border-radius: 5px;
                margin-bottom: 12px;
            }

            .mockup-content:nth-child(2) {
                width: 90%;
            }

            .mockup-content:nth-child(3) {
                width: 75%;
            }

            .mockup-content:nth-child(4) {
                width: 85%;
            }

            /* Features */
            .features-section {
                padding: 100px 0;
                background: #f8f9fa;
            }

            .feature-card {
                background: white;
                padding: 40px 30px;
                border-radius: 20px;
                text-align: center;
                transition: all 0.3s;
                border: 1px solid #e0e0e0;
                height: 100%;
            }

            .feature-card:hover {
                transform: translateY(-10px);
                box-shadow: 0 20px 40px rgba(220, 53, 69, 0.2);
                border-color: #dc3545;
            }

            .feature-icon {
                width: 80px;
                height: 80px;
                background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
                border-radius: 20px;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 25px;
                font-size: 35px;
                color: white;
            }

            .feature-card h4 {
                font-weight: 700;
                margin-bottom: 15px;
                color: #333;
            }

            .feature-card p {
                color: #666;
                line-height: 1.8;
                margin: 0;
            }

            /* Pricing */
            .pricing-section {
                padding: 100px 0;
                background: white;
            }

            .section-title {
                text-align: center;
                margin-bottom: 60px;
            }

            .section-title h2 {
                font-size: 2.5rem;
                font-weight: 800;
                margin-bottom: 15px;
                color: #333;
            }

            .section-title p {
                font-size: 1.2rem;
                color: #666;
            }

            .pricing-card {
                background: white;
                border-radius: 20px;
                padding: 50px 40px;
                text-align: center;
                border: 2px solid #e0e0e0;
                transition: all 0.3s;
                height: 100%;
                position: relative;
            }

            .pricing-card:hover {
                transform: translateY(-10px);
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            }

            .pricing-card.featured {
                background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
                color: white;
                border-color: #dc3545;
                transform: scale(1.05);
            }

            .pricing-card.featured:hover {
                transform: scale(1.08) translateY(-10px);
            }

            .pricing-badge {
                position: absolute;
                top: -15px;
                left: 50%;
                transform: translateX(-50%);
                background: #ff6b6b;
                color: white;
                padding: 8px 25px;
                border-radius: 50px;
                font-weight: 600;
                font-size: 14px;
            }

            .pricing-card h3 {
                font-size: 24px;
                font-weight: 700;
                margin-bottom: 20px;
            }

            .pricing-price {
                font-size: 48px;
                font-weight: 800;
                margin: 20px 0;
            }

            .pricing-card.featured .pricing-price {
                color: white;
            }

            .pricing-duration {
                font-size: 16px;
                opacity: 0.8;
                margin-bottom: 30px;
            }

            .pricing-features {
                list-style: none;
                padding: 0;
                margin: 30px 0;
                text-align: left;
            }

            .pricing-features li {
                padding: 12px 0;
                border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            }

            .pricing-card.featured .pricing-features li {
                border-bottom-color: rgba(255, 255, 255, 0.2);
            }

            .pricing-features i {
                color: #dc3545;
                margin-right: 10px;
                font-size: 18px;
            }

            .pricing-card.featured .pricing-features i {
                color: #ffd700;
            }

            .btn-pricing {
                width: 100%;
                padding: 15px;
                font-weight: 600;
                border-radius: 50px;
                border: none;
                transition: all 0.3s;
                margin-top: 20px;
            }

            .btn-pricing-default {
                background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
                color: white;
            }

            .btn-pricing-default:hover {
                transform: scale(1.05);
                box-shadow: 0 10px 30px rgba(220, 53, 69, 0.4);
            }

            .btn-pricing-featured {
                background: white;
                color: #dc3545;
            }

            .btn-pricing-featured:hover {
                transform: scale(1.05);
                box-shadow: 0 10px 30px rgba(255, 255, 255, 0.3);
            }

            /* CTA Section */
            .cta-section {
                background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
                padding: 80px 0;
                color: white;
                text-align: center;
            }

            .cta-section h2 {
                font-size: 2.5rem;
                font-weight: 800;
                margin-bottom: 20px;
            }

            .cta-section p {
                font-size: 1.2rem;
                margin-bottom: 40px;
                opacity: 0.95;
            }

            /* Footer */
            .footer {
                background: #2d3748;
                color: white;
                padding: 60px 0 30px;
            }

            .footer h5 {
                font-weight: 700;
                margin-bottom: 20px;
            }

            .footer a {
                color: rgba(255, 255, 255, 0.7);
                text-decoration: none;
                display: block;
                padding: 8px 0;
                transition: all 0.3s;
            }

            .footer a:hover {
                color: white;
                padding-left: 5px;
            }

            .footer-bottom {
                border-top: 1px solid rgba(255, 255, 255, 0.1);
                margin-top: 40px;
                padding-top: 30px;
                text-align: center;
                opacity: 0.7;
            }

            @media (max-width: 768px) {
                .hero-section h1 {
                    font-size: 2.5rem;
                }

                .hero-section .lead {
                    font-size: 1.1rem;
                }

                .pricing-card.featured {
                    transform: scale(1);
                    margin-bottom: 30px;
                }
            }
        </style>
    </head>

    <body>
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg fixed-top">
            <div class="container">
                <a class="navbar-brand" href="#">
                    <img src="{{ asset('images/logo.png') }}" alt="Pertashop Logo"
                        style="height: 35px; margin-right: 10px;">
                    Pertashop
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto align-items-center">
                        <li class="nav-item"><a class="nav-link" href="#features">Fitur</a></li>
                        <li class="nav-item"><a class="nav-link" href="#pricing">Harga</a></li>
                        @auth
                            @if (auth()->user()->isSuperadmin())
                                <li class="nav-item ms-3">
                                    <a href="{{ route('superadmin.dashboard') }}"
                                        class="btn btn-outline-primary rounded-pill px-4">Dashboard Superadmin</a>
                                </li>
                            @else
                                <li class="nav-item ms-3">
                                    <a href="{{ route('dashboard') }}"
                                        class="btn btn-outline-primary rounded-pill px-4">Dashboard</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item ms-3">
                                <a href="{{ route('login') }}" class="btn btn-outline-primary rounded-pill px-4">Login</a>
                            </li>
                            <li class="nav-item ms-2">
                                <a href="{{ route('owner.register.form') }}"
                                    class="btn btn-primary rounded-pill px-4">Daftar Gratis</a>
                            </li>
                        @endauth
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="hero-section">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6 mb-5 mb-lg-0">
                        <h1 class="animate__animated animate__fadeInUp">Kelola SPBU Anda dengan Lebih Mudah & Efisien
                        </h1>
                        <p class="lead animate__animated animate__fadeInUp animate__delay-1s">Platform SaaS modern untuk
                            manajemen operasional SPBU. Kelola multi outlet, monitor real-time, dan tingkatkan
                            produktivitas dalam satu sistem terpadu.</p>
                        <div class="d-flex gap-3 animate__animated animate__fadeInUp animate__delay-2s">
                            <a href="{{ route('owner.register.form') }}" class="btn-hero btn-hero-primary">
                                <i class="bi bi-rocket-takeoff me-2"></i>Coba Gratis 14 Hari
                            </a>
                            <a href="#features" class="btn-hero btn-hero-outline">
                                Lihat Demo
                            </a>
                        </div>
                        <p class="mt-4 mb-0" style="opacity: 0.8;">
                            <i class="bi bi-check-circle-fill me-2"></i>Tanpa Kartu Kredit
                            <i class="bi bi-check-circle-fill ms-3 me-2"></i>Setup 5 Menit
                            <i class="bi bi-check-circle-fill ms-3 me-2"></i>Support 24/7
                        </p>
                    </div>
                    <div class="col-lg-6">
                        <div class="hero-image">
                            <div class="mockup-card">
                                <div class="mockup-bar"></div>
                                <div class="row g-3">
                                    <div class="col-4">
                                        <div
                                            style="height: 100px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 10px;">
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div
                                            style="height: 100px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 10px;">
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div
                                            style="height: 100px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border-radius: 10px;">
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <div class="mockup-content"></div>
                                    <div class="mockup-content"></div>
                                    <div class="mockup-content"></div>
                                    <div class="mockup-content"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="features-section" id="features">
            <div class="container">
                <div class="section-title">
                    <h2>Fitur Unggulan Pertashop</h2>
                    <p>Semua yang Anda butuhkan untuk mengelola SPBU modern</p>
                </div>
                <div class="row g-4">
                    <div class="col-lg-4 col-md-6">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="bi bi-graph-up-arrow"></i>
                            </div>
                            <h4>Laporan Real-time</h4>
                            <p>Pantau penjualan, stok BBM, dan kinerja operator secara real-time dari mana saja dengan
                                dashboard interaktif.</p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="bi bi-building"></i>
                            </div>
                            <h4>Multi Outlet</h4>
                            <p>Kelola banyak SPBU dalam satu akun dengan mudah. Switch antar outlet dengan sekali klik.
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="bi bi-people-fill"></i>
                            </div>
                            <h4>Manajemen Shift</h4>
                            <p>Atur jadwal shift operator, tracking kehadiran, dan monitoring produktivitas karyawan
                                secara otomatis.</p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="bi bi-shield-check"></i>
                            </div>
                            <h4>Keamanan Data</h4>
                            <p>Data Anda aman dengan enkripsi SSL 256-bit dan backup otomatis setiap hari.</p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="bi bi-phone"></i>
                            </div>
                            <h4>Mobile Friendly</h4>
                            <p>Akses dari smartphone, tablet, atau komputer kapanpun dan dimanapun Anda berada.</p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="bi bi-headset"></i>
                            </div>
                            <h4>Support 24/7</h4>
                            <p>Tim support kami siap membantu Anda via chat, email, atau telepon kapan saja.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Pricing Section -->
        <section class="pricing-section" id="pricing">
            <div class="container">
                <div class="section-title">
                    <h2>Pilih Paket yang Tepat untuk Anda</h2>
                    <p>Mulai dengan trial gratis, upgrade kapan saja</p>
                </div>
                <div class="row g-4 justify-content-center">
                    <div class="col-lg-4 col-md-6">
                        <div class="pricing-card">
                            <h3>Trial</h3>
                            <div class="pricing-price">GRATIS</div>
                            <div class="pricing-duration">14 hari penuh akses</div>
                            <ul class="pricing-features">
                                <li><i class="bi bi-check-circle-fill"></i> Semua fitur lengkap</li>
                                <li><i class="bi bi-check-circle-fill"></i> Multi outlet unlimited</li>
                                <li><i class="bi bi-check-circle-fill"></i> Laporan real-time</li>
                                <li><i class="bi bi-check-circle-fill"></i> Support email</li>
                                <li><i class="bi bi-check-circle-fill"></i> Tanpa kartu kredit</li>
                            </ul>
                            <a href="{{ route('owner.register.form') }}"
                                class="btn btn-pricing btn-pricing-default">Mulai Trial Gratis</a>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="pricing-card featured">
                            <div class="pricing-badge">PALING POPULER</div>
                            <h3>Monthly</h3>
                            <div class="pricing-price">Rp 100rb</div>
                            <div class="pricing-duration">Per bulan</div>
                            <ul class="pricing-features">
                                <li><i class="bi bi-check-circle-fill"></i> Semua fitur lengkap</li>
                                <li><i class="bi bi-check-circle-fill"></i> Multi outlet unlimited</li>
                                <li><i class="bi bi-check-circle-fill"></i> Laporan advanced</li>
                                <li><i class="bi bi-check-circle-fill"></i> Support prioritas 24/7</li>
                                <li><i class="bi bi-check-circle-fill"></i> Update fitur gratis</li>
                                <li><i class="bi bi-check-circle-fill"></i> Training & onboarding</li>
                            </ul>
                            <a href="{{ route('owner.register.form') }}"
                                class="btn btn-pricing btn-pricing-featured">Daftar Sekarang</a>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="pricing-card">
                            <div class="pricing-badge" style="background: #10b981;">HEMAT 17%</div>
                            <h3>Yearly</h3>
                            <div class="pricing-price">Rp 1jt</div>
                            <div class="pricing-duration">Per tahun</div>
                            <ul class="pricing-features">
                                <li><i class="bi bi-check-circle-fill"></i> Semua fitur lengkap</li>
                                <li><i class="bi bi-check-circle-fill"></i> Hemat Rp 200rb/tahun</li>
                                <li><i class="bi bi-check-circle-fill"></i> Priority support 24/7</li>
                                <li><i class="bi bi-check-circle-fill"></i> Training on-site gratis</li>
                                <li><i class="bi bi-check-circle-fill"></i> Dedicated account manager</li>
                                <li><i class="bi bi-check-circle-fill"></i> Custom reporting</li>
                            </ul>
                            <a href="{{ route('owner.register.form') }}"
                                class="btn btn-pricing btn-pricing-default">Daftar Sekarang</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="cta-section">
            <div class="container">
                <h2>Siap Tingkatkan Efisiensi SPBU Anda?</h2>
                <p>Bergabung dengan ratusan pemilik SPBU yang sudah merasakan kemudahan Pertashop</p>
                <a href="{{ route('owner.register.form') }}" class="btn-hero btn-hero-primary">
                    <i class="bi bi-rocket-takeoff me-2"></i>Mulai Trial Gratis 14 Hari
                </a>
            </div>
        </section>

        <!-- Footer -->
        <footer class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 mb-4 mb-lg-0">
                        <h5><i class="bi bi-fuel-pump-fill me-2"></i>Pertashop</h5>
                        <p class="mb-0" style="opacity: 0.7;">Platform manajemen SPBU modern yang membantu Anda
                            mengelola bisnis dengan lebih efisien.</p>
                    </div>
                    <div class="col-lg-2 col-md-3 col-6 mb-4 mb-lg-0">
                        <h5>Produk</h5>
                        <a href="#features">Fitur</a>
                        <a href="#pricing">Harga</a>
                        <a href="#">Demo</a>
                    </div>
                    <div class="col-lg-2 col-md-3 col-6 mb-4 mb-lg-0">
                        <h5>Perusahaan</h5>
                        <a href="#">Tentang Kami</a>
                        <a href="#">Kontak</a>
                        <a href="#">Karir</a>
                    </div>
                    <div class="col-lg-2 col-md-3 col-6 mb-4 mb-lg-0">
                        <h5>Legal</h5>
                        <a href="#">Privacy Policy</a>
                        <a href="#">Terms of Service</a>
                    </div>
                    <div class="col-lg-2 col-md-3 col-6">
                        <h5>Hubungi Kami</h5>
                        <a href="mailto:hello@pertashop.com"><i
                                class="bi bi-envelope me-2"></i>hello@pertashop.com</a>
                        <a href="tel:+628123456789"><i class="bi bi-telephone me-2"></i>+62 812-3456-789</a>
                    </div>
                </div>
                <div class="footer-bottom">
                    <p class="mb-0">&copy; {{ date('Y') }} Pertashop. All rights reserved.</p>
                </div>
            </div>
        </footer>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>

</html>
