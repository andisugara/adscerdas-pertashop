@extends('layout.app')

@section('title', 'No Access')

@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body text-center py-20">
                                <i class="ki-duotone ki-information-5 fs-5tx text-warning mb-10">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                                <h1 class="fw-bold mb-5">No Access</h1>
                                <p class="text-gray-600 fs-4 mb-10">
                                    Anda belum memiliki akses ke pertashop manapun.<br>
                                    Silakan hubungi administrator untuk mendapatkan akses.
                                </p>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-primary">
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
