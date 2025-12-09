@extends('layout.app')

@section('title', 'Dashboard')

@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                        Dashboard</h1>
                </div>
            </div>
        </div>

        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body text-center py-20">
                                <div class="mb-10">
                                    <i class="ki-duotone ki-user-tick fs-5tx text-primary">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </i>
                                </div>
                                <h1 class="fw-bold text-gray-900 mb-5">Halo, {{ Auth::user()->name }}</h1>
                                <p class="text-gray-600 fs-4 mb-10">Selamat datang di sistem Pertashop</p>
                                <a href="{{ route('daily-reports.index') }}" class="btn btn-primary">
                                    <i class="ki-duotone ki-document">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                    Input Laporan Harian
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
