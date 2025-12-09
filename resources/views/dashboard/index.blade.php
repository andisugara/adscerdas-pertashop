@extends('layout.app')

@section('title', 'Dashboard')

@section('content')
    <div class="row g-5 g-xl-10 mb-5">
        <!-- Filter -->
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('dashboard') }}" method="GET" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Filter</label>
                            <select name="filter" class="form-select" id="filterType">
                                <option value="daily" {{ $filter == 'daily' ? 'selected' : '' }}>Harian</option>
                                <option value="monthly" {{ $filter == 'monthly' ? 'selected' : '' }}>Bulanan</option>
                                <option value="yearly" {{ $filter == 'yearly' ? 'selected' : '' }}>Tahunan</option>
                            </select>
                        </div>
                        <div class="col-md-3 filter-daily" style="display: {{ $filter == 'daily' ? 'block' : 'none' }}">
                            <label class="form-label">Tanggal</label>
                            <input type="date" name="date" class="form-control" value="{{ $date }}">
                        </div>
                        <div class="col-md-3 filter-monthly" style="display: {{ $filter == 'monthly' ? 'block' : 'none' }}">
                            <label class="form-label">Bulan</label>
                            <input type="month" name="month" class="form-control" value="{{ $month }}">
                        </div>
                        <div class="col-md-3 filter-yearly" style="display: {{ $filter == 'yearly' ? 'block' : 'none' }}">
                            <label class="form-label">Tahun</label>
                            <input type="number" name="year" class="form-control" value="{{ $year }}"
                                min="2020" max="2099">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <button type="submit" class="btn btn-primary d-block">
                                <i class="ki-outline ki-filter"></i> Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if ($filter == 'daily')
        @include('dashboard.partials.daily-report')
    @elseif($filter == 'monthly')
        @include('dashboard.partials.monthly-report')
    @elseif($filter == 'yearly')
        @include('dashboard.partials.yearly-report')
    @endif
@endsection

@push('scripts')
    <script>
        $('#filterType').on('change', function() {
            var filter = $(this).val();
            $('.filter-daily, .filter-monthly, .filter-yearly').hide();
            if (filter == 'daily') {
                $('.filter-daily').show();
            } else if (filter == 'monthly') {
                $('.filter-monthly').show();
            } else if (filter == 'yearly') {
                $('.filter-yearly').show();
            }
        });
    </script>
@endpush
