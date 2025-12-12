@extends('layout.app')

@section('title', 'Payment - Duitku')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">Pembayaran Subscription</h3>
                </div>
                <div class="card-body text-center py-10">
                    <div class="mb-5">
                        <i class="ki-outline ki-wallet fs-5x text-primary mb-5"></i>
                        <h4 class="fw-bold text-gray-800 mb-3">
                            {{ ucfirst($subscription->plan_name) }} Plan
                        </h4>
                        <div class="fs-1 fw-bold text-primary mb-5">
                            Rp {{ number_format($amount, 0, ',', '.') }}
                        </div>
                        <p class="text-muted mb-0">
                            Klik tombol di bawah untuk memilih metode pembayaran
                        </p>
                    </div>

                    <button id="pay-button" class="btn btn-lg btn-primary px-10">
                        <i class="ki-outline ki-credit-cart fs-3"></i>
                        Bayar Sekarang
                    </button>

                    <div class="mt-8">
                        <a href="{{ route('subscriptions.index') }}" class="text-muted">
                            <i class="ki-outline ki-arrow-left fs-5"></i>
                            Kembali ke My Subscription
                        </a>
                    </div>
                </div>
            </div>

            <!-- Payment Status Info -->
            <div class="card mt-5">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <i class="ki-outline ki-information-5 fs-2x text-info me-4"></i>
                        <div>
                            <h6 class="fw-bold mb-1">Metode Pembayaran</h6>
                            <p class="text-muted mb-0 fs-7">
                                Anda dapat memilih berbagai metode pembayaran: Virtual Account (BCA, BNI, Mandiri, BRI),
                                QRIS, E-Wallet (OVO, Dana, ShopeePay), dan lainnya.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Duitku Checkout JS -->
    <script src="{{ $checkoutUrl }}"
        data-environment="{{ config('app.env') === 'production' ? 'production' : 'sandbox' }}"></script>

    <script>
        document.getElementById('pay-button').addEventListener('click', function() {
            checkout.process('{{ $reference }}', {
                defaultLanguage: 'id',
                successEvent: function(result) {
                    console.log('Payment success:', result);
                    window.location.href = '{{ route('subscriptions.index') }}?payment=success';
                },
                pendingEvent: function(result) {
                    console.log('Payment pending:', result);
                    window.location.href = '{{ route('subscriptions.index') }}?payment=pending';
                },
                errorEvent: function(result) {
                    console.log('Payment error:', result);
                    alert('Pembayaran gagal: ' + (result.message || 'Unknown error'));
                },
                closeEvent: function(result) {
                    console.log('Payment closed:', result);
                    // User closed payment popup
                }
            });
        });
    </script>
@endpush
