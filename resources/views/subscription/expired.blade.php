@extends('layout.app')

@section('title', 'Subscription Expired')

@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body text-center py-20">
                                <i class="ki-duotone ki-calendar-remove fs-5tx text-danger mb-10">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                    <span class="path5"></span>
                                    <span class="path6"></span>
                                </i>
                                <h1 class="fw-bold mb-5">Subscription Expired</h1>
                                <p class="text-gray-600 fs-4 mb-10">
                                    Langganan pertashop Anda telah berakhir.<br>
                                    Silakan perpanjang langganan untuk melanjutkan.
                                </p>

                                @if (Auth::user()->isOwner())
                                    <a href="{{ route('subscription.plans') }}" class="btn btn-primary me-3">
                                        <i class="ki-duotone ki-credit-cart">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                        Perpanjang Langganan
                                    </a>
                                @endif

                                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-light">
                                        <i class="ki-duotone ki-exit-left">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
