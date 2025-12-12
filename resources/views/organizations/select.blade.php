@extends('layout.app')

@section('title', 'Select Organization')

@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Pilih Pertashop</h3>
                            </div>
                            <div class="card-body">
                                <p class="text-gray-600 mb-5">Anda memiliki akses ke beberapa pertashop. Silakan pilih
                                    pertashop yang ingin Anda kelola.</p>

                                <div class="row g-5">
                                    @foreach ($organizations as $org)
                                        <div class="col-md-6">
                                            <div class="card card-bordered hover-elevate-up">
                                                <div class="card-body text-center p-8">
                                                    <i class="ki-duotone ki-shop fs-3x text-primary mb-5">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                        <span class="path3"></span>
                                                        <span class="path4"></span>
                                                        <span class="path5"></span>
                                                    </i>
                                                    <h3 class="fw-bold mb-3">{{ $org->name }}</h3>
                                                    <p class="text-gray-600 mb-5">{{ $org->address ?? 'No address' }}</p>

                                                    @if ($org->isInTrial())
                                                        <span class="badge badge-warning mb-3">Trial</span>
                                                    @elseif($org->hasActiveSubscription())
                                                        <span class="badge badge-success mb-3">Active</span>
                                                    @else
                                                        <span class="badge badge-danger mb-3">Expired</span>
                                                    @endif

                                                    <form action="{{ route('organizations.switch') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="organization_id"
                                                            value="{{ $org->id }}">
                                                        <button type="submit" class="btn btn-primary w-100">
                                                            <i class="ki-duotone ki-arrow-right">
                                                                <span class="path1"></span>
                                                                <span class="path2"></span>
                                                            </i>
                                                            Pilih Pertashop Ini
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
