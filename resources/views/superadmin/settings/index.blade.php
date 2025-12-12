@extends('superadmin.layout')

@section('title', 'System Settings')

@section('content')
    @if (session('success'))
        <div class="alert alert-success d-flex align-items-center p-5 mb-10">
            <i class="ki-duotone ki-shield-tick fs-2hx text-success me-4">
                <span class="path1"></span>
                <span class="path2"></span>
            </i>
            <div class="d-flex flex-column">
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <form action="{{ route('superadmin.settings.update') }}" method="POST">
        @csrf

        <div class="row g-5">
            <!-- Subscription Settings -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Pengaturan Subscription</h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-7">
                            <label class="form-label required">Payment Gateway</label>
                            <select name="payment_gateway" class="form-select">
                                <option value="manual"
                                    {{ ($settings['payment_gateway']->value ?? 'manual') === 'manual' ? 'selected' : '' }}>
                                    Manual Approval
                                </option>
                                <option value="duitku"
                                    {{ ($settings['payment_gateway']->value ?? '') === 'duitku' ? 'selected' : '' }}>
                                    Duitku
                                </option>
                            </select>
                            @error('payment_gateway')
                                <div class="text-danger fs-7 mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-7">
                            <label class="form-label required">Masa Trial (Hari)</label>
                            <input type="number" name="trial_days" value="{{ $settings['trial_days']->value ?? 14 }}"
                                class="form-control" min="0">
                            @error('trial_days')
                                <div class="text-danger fs-7 mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-7">
                            <label class="form-label required">Harga Paket Bulanan</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="monthly_price"
                                    value="{{ $settings['monthly_price']->value ?? 100000 }}" class="form-control"
                                    min="0">
                            </div>
                            @error('monthly_price')
                                <div class="text-danger fs-7 mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-0">
                            <label class="form-label required">Harga Paket Tahunan</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="yearly_price"
                                    value="{{ $settings['yearly_price']->value ?? 1000000 }}" class="form-control"
                                    min="0">
                            </div>
                            @error('yearly_price')
                                <div class="text-danger fs-7 mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Duitku Settings -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Pengaturan Duitku</h3>
                        <div class="card-toolbar">
                            <span class="badge badge-light-warning">Opsional</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-7">
                            <label class="form-label">Merchant Code</label>
                            <input type="text" name="duitku_merchant_code"
                                value="{{ $settings['duitku_merchant_code']->value ?? '' }}" class="form-control"
                                placeholder="D1234">
                            @error('duitku_merchant_code')
                                <div class="text-danger fs-7 mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-7">
                            <label class="form-label">API Key</label>
                            <input type="text" name="duitku_api_key"
                                value="{{ $settings['duitku_api_key']->value ?? '' }}" class="form-control"
                                placeholder="abcdef123456">
                            @error('duitku_api_key')
                                <div class="text-danger fs-7 mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-0">
                            <label class="form-label">Callback URL</label>
                            <input type="url" name="duitku_callback_url"
                                value="{{ $settings['duitku_callback_url']->value ?? '' }}" class="form-control"
                                placeholder="https://your-domain.com/callback/duitku">
                            <div class="form-text">URL untuk menerima notifikasi pembayaran dari Duitku</div>
                            @error('duitku_callback_url')
                                <div class="text-danger fs-7 mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bank Transfer Settings -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Pengaturan Transfer Manual</h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-7">
                            <label class="form-label required">Nama Bank</label>
                            <input type="text" name="bank_name"
                                value="{{ $settings['bank_name']->value ?? 'Bank BCA' }}" class="form-control"
                                placeholder="Bank BCA">
                            @error('bank_name')
                                <div class="text-danger fs-7 mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-7">
                            <label class="form-label required">Nomor Rekening</label>
                            <input type="text" name="bank_account_number"
                                value="{{ $settings['bank_account_number']->value ?? '1234567890' }}" class="form-control"
                                placeholder="1234567890">
                            @error('bank_account_number')
                                <div class="text-danger fs-7 mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-0">
                            <label class="form-label required">Nama Penerima</label>
                            <input type="text" name="bank_account_name"
                                value="{{ $settings['bank_account_name']->value ?? 'PT Pertashop Indonesia' }}"
                                class="form-control" placeholder="PT Pertashop Indonesia">
                            @error('bank_account_name')
                                <div class="text-danger fs-7 mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end mt-5">
            <button type="submit" class="btn btn-primary">
                <i class="ki-outline ki-check fs-4 me-2"></i>
                Simpan Pengaturan
            </button>
        </div>
    </form>
@endsection
