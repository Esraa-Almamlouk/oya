@extends('layouts/layoutMaster')

@section('title', 'إدارة الحسابات')

@section('vendor-style')
    @vite([
    'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
    'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
    'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss',
    'resources/assets/vendor/libs/select2/select2.scss',
    'resources/assets/vendor/libs/@form-validation/form-validation.scss',
    'resources/assets/vendor/libs/animate-css/animate.scss',
    'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss',
])
@endsection

@section('vendor-script')
    @vite([
    'resources/assets/vendor/libs/moment/moment.js',
    'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js',
    'resources/assets/vendor/libs/sweetalert2/sweetalert2.js',
    'resources/assets/vendor/libs/select2/select2.js',
    'resources/assets/vendor/libs/@form-validation/popular.js',
    'resources/assets/vendor/libs/@form-validation/bootstrap5.js',
    'resources/assets/vendor/libs/@form-validation/auto-focus.js',
    'resources/assets/vendor/libs/cleavejs/cleave.js',
    'resources/assets/vendor/libs/cleavejs/cleave-phone.js'
])
@endsection

@section('page-script')
    @vite('resources/assets/js/app-account-list.js')
@endsection

@section('content')

    <div class="row g-6 mb-6 accounts-page">
    <div class="card">
        <div class="m-4">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
        </div>
        <div class="card-header border-bottom">
                <h5 class="card-title mb-0">إدارة الحسابات</h5>
                </div>
        <div class="card-header border-bottom">
            <h5 class="card-title mb-0">الفلترة</h5>
            <div class="row accounts-filter-row pt-4 g-4 justify-content-between align-items-end">
                <div class="col-12 col-sm-6 col-md-4 account_category"
                    data-options='@json(collect($categories)->map(fn($category) => $category->label())->values())'></div>
                <div class="col-12 col-sm-6 col-md-4 ms-md-auto account_currency"
                    data-options='@json(collect($currencies)->map(fn($currency) => $currency->label() . " (" . $currency->value . ")")->values())'></div>
            </div>
        </div>
        <div class="card-datatable table-responsive">
            <table class="datatables-accounts table">
                <thead class="border-top">
                    <tr>
                        <th></th>
                        <th></th>
                        <th>اسم الحساب</th>
                        <th>التصنيف</th>
                        <th>العملة</th>
                        <th>المصرف</th>
                        <th>رقم الهاتف</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($accounts as $account)
                        <tr>
                            <td></td>
                            <td></td>
                            <td>{{ $account->name }}</td>
                            <td>{{ $account->category?->label() }}</td>
                            <td>{{ $account->currency?->label() }} ({{ $account->currency?->value }})</td>
                            <td>{{ $account->bank }}</td>
                            <td>{{ $account->phone }}</td>
                            <td>
                                <div class="d-flex align-items-center account-actions">
                                    <a href="javascript:;"
                                        class="btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill edit-record"
                                        data-bs-toggle="offcanvas"
                                        data-bs-target="#offcanvasAddAccount"
                                        data-id="{{ $account->id }}"
                                        data-name="{{ $account->name }}"
                                        data-category="{{ $account->category->value }}"
                                        data-currency="{{ $account->currency->value }}"
                                        data-bank="{{ $account->bank }}"
                                        data-phone="{{ $account->phone }}"
                                        >
                                            <i class="ti ti-edit ti-md"></i>
                                    </a>
                                    <a href="javascript:;"
                                        class="btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill delete-record"
                                        data-id="{{ $account->id }}"
                                        data-url="{{ route('accounts.destroy', $account) }}">
                                            <i class="ti ti-trash ti-md"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>

                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddAccount" aria-labelledby="offcanvasAddAccountLabel">
            <div class="offcanvas-header border-bottom">
                <h5 id="offcanvasAddAccountLabel" class="offcanvas-title">إضافة حساب</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body mx-0 flex-grow-0 p-6 h-100">
                <form class="add-new-account pt-0" id="addNewAccountForm" method="POST" action="{{ route('accounts.store') }}"
                    data-create-action="{{ route('accounts.store') }}" data-update-action-template="{{ url('/accounts/__id__') }}"
                    data-has-errors="{{ $errors->any() ? '1' : '0' }}">
                    @csrf
                    <input type="hidden" name="_method" id="accountFormMethod" value="POST">
                    <input type="hidden" id="editingAccountId" value="">

                    <div class="mb-6">
                        <label class="form-label" for="add-account-name">اسم الحساب</label>
                        <input type="text" class="form-control" id="add-account-name" placeholder="أدخل اسم الحساب" name="name"
                            value="{{ old('name') }}" required />
                    </div>

                    <div class="mb-6">
                        <label class="form-label" for="add-account-category">التصنيف</label>
                        <select id="add-account-category" class="form-select" name="category" required>
                            <option value="" disabled {{ old('category', '') === '' ? 'selected' : '' }}>اختر</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->value }}" {{ old('category', '') === $category->value ? 'selected' : '' }}>
                                    {{ $category->label() }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-6">
                        <label class="form-label" for="add-account-currency">العملة</label>
                        <select id="add-account-currency" class="form-select" name="currency" required>
                            <option value="" disabled {{ old('currency', '') === '' ? 'selected' : '' }}>اختر</option>
                            @foreach ($currencies as $currency)
                                <option value="{{ $currency->value }}" {{ old('currency', '') === $currency->value ? 'selected' : '' }}>
                                    {{ $currency->label() }} ({{ $currency->value }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-6">
                        <label class="form-label" for="add-account-bank">المصرف</label>
                        <input type="text" id="add-account-bank" class="form-control" placeholder="أدخل اسم المصرف"
                            name="bank" value="{{ old('bank') }}" required />
                    </div>

                    <div class="mb-6">
                        <label class="form-label" for="add-account-phone">رقم الهاتف</label>
                        <input type="text" id="add-account-phone" class="form-control phone-mask" placeholder="09XXXXXXXX"
                            name="phone" value="{{ old('phone') }}" required />
                    </div>

                    <button type="submit" class="btn btn-primary me-3 data-submit" id="accountFormSubmitBtn">حفظ</button>
                    <button type="button" class="btn btn-label-danger" data-bs-dismiss="offcanvas">إلغاء</button>
                </form>
            </div>
        </div>
    </div>

@endsection
