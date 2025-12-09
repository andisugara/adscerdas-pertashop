@extends('layout.app')

@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                        Laporan</h1>
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('dashboard') }}" class="text-muted text-hover-primary">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-500 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">Laporan</li>
                    </ul>
                </div>
            </div>
        </div>

        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">
                <!-- Filter Form -->
                <div class="card mb-5">
                    <div class="card-body">
                        <form method="GET" action="{{ route('reports.index') }}" class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Tipe Laporan</label>
                                <select name="filter" class="form-select" id="filterType">
                                    <option value="daily" {{ $filter == 'daily' ? 'selected' : '' }}>Harian</option>
                                    <option value="monthly" {{ $filter == 'monthly' ? 'selected' : '' }}>Bulanan</option>
                                    <option value="yearly" {{ $filter == 'yearly' ? 'selected' : '' }}>Tahunan</option>
                                </select>
                            </div>

                            <div class="col-md-4" id="dateField" style="{{ $filter == 'daily' ? '' : 'display: none;' }}">
                                <label class="form-label">Tanggal</label>
                                <input type="date" name="date" class="form-control"
                                    value="{{ request('date', date('Y-m-d')) }}">
                            </div>

                            <div class="col-md-4" id="monthField"
                                style="{{ $filter == 'monthly' ? '' : 'display: none;' }}">
                                <label class="form-label">Bulan</label>
                                <input type="month" name="month" class="form-control"
                                    value="{{ request('month', date('Y-m')) }}">
                            </div>

                            <div class="col-md-4" id="yearField" style="{{ $filter == 'yearly' ? '' : 'display: none;' }}">
                                <label class="form-label">Tahun</label>
                                <input type="number" name="year" class="form-control"
                                    value="{{ request('year', date('Y')) }}" min="2020" max="2100">
                            </div>

                            <div class="col-md-4 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ki-duotone ki-search-list fs-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </i>
                                    Tampilkan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Report Content -->
                @if ($filter == 'daily')
                    @include('dashboard.partials.daily-report')
                @elseif($filter == 'monthly')
                    @include('dashboard.partials.monthly-report')
                @elseif($filter == 'yearly')
                    @include('dashboard.partials.yearly-report')
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.getElementById('filterType').addEventListener('change', function() {
                const filter = this.value;
                document.getElementById('dateField').style.display = filter === 'daily' ? '' : 'none';
                document.getElementById('monthField').style.display = filter === 'monthly' ? '' : 'none';
                document.getElementById('yearField').style.display = filter === 'yearly' ? '' : 'none';
            });
        </script>
    @endpush
@endsection
