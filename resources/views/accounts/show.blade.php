@extends('layouts/layoutMaster')

@section('title', 'معاملات الحساب')

@section('vendor-style')
    @vite([
    'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
    'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
    'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss',
    'resources/assets/vendor/libs/@form-validation/form-validation.scss',
])
@endsection

@section('vendor-script')
    @vite([
    'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js',
    'resources/assets/vendor/libs/@form-validation/popular.js',
    'resources/assets/vendor/libs/@form-validation/bootstrap5.js',
    'resources/assets/vendor/libs/@form-validation/auto-focus.js',
])
@endsection

@section('page-script')
    @vite('resources/assets/js/app-account-transactions.js')
@endsection

@section('content')
    <div class="row g-6 mb-6 account-transactions-page">
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
            </div>

            <div class="card-header border-bottom">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5 class="card-title mb-0">
                        معاملات {{ $account->name }}
                    </h5>
                    <a href="{{ route('accounts.index') }}" class="btn btn-label-secondary waves-effect waves-light">
                        الرجوع
                    </a>
                </div>
            </div>

            <div class="card-header border-bottom">
                <h5 class="card-title mb-0">الفلترة</h5>
                <div class="row pt-4 g-4">
                    <div class="col-12 col-sm-6 col-md-4 transaction_type"></div>
                </div>
            </div>

            <div class="card-datatable table-responsive">
                <table class="datatables-account-transactions table">
                    <thead class="border-top">
                        <tr>
                            <th></th>
                            <th></th>
                            <th>رقم المعاملة</th>
                            <th>التاريخ</th>
                            <th>الوصف</th>
                            <th>القيمة</th>
                            <th>نوع المعاملة</th>
                            <th>رصيد الحساب</th>
                            <th>مرفق</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transactions as $transaction)
                            <tr>
                                <td></td>
                                <td></td>
                                <td>{{ $transaction->reference ?? sprintf('TRX-%s-%04d', \Illuminate\Support\Carbon::parse($transaction->date)->format('Ymd'), $transaction->id) }}</td>
                                <td>{{ $transaction->date }}</td>
                                <td>{{ $transaction->description }}</td>
                                <td>{{ number_format((float) $transaction->amount, 2) }}</td>
                                <td>
                                    <span class="badge {{ $transaction->type === 'credit' ? 'bg-label-success' : 'bg-label-danger' }}">
                                        {{ $transaction->type === 'credit' ? 'إيداع' : 'سحب' }}
                                    </span>
                                </td>
                                <td>{{ number_format((float) $transaction->balance_after, 2) }}</td>
                                <td>
                                    @if ($transaction->attachment)
                                        <a href="{{ asset('storage/' . $transaction->attachment) }}"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill"
                                            title="عرض المرفق">
                                            <i class="ti ti-paperclip ti-md"></i>
                                        </a>
                                    @else
                                        <span class="btn btn-icon btn-text-secondary rounded-pill" title="لا يوجد مرفق">
                                            <i class="ti ti-ban ti-md"></i>
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addTransactionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-simple">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                    <div class="text-center mb-6">
                        <h4 class="mb-2">معاملة جديدة</h4>
                    </div>

                    <form id="addTransactionForm"
                        class="row g-6"
                        method="POST"
                        action="{{ route('accounts.transactions.store', $account) }}"
                        enctype="multipart/form-data"
                        data-has-errors="{{ $errors->any() ? '1' : '0' }}">
                        @csrf
                        <input type="hidden" name="account_id" value="{{ $account->id }}">

                        <div class="col-12 col-md-6">
                            <label class="form-label" for="transactionDate">التاريخ</label>
                            <input type="date" id="transactionDate" name="date" class="form-control" value="{{ old('date') }}" />
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label" for="transactionType">نوع المعاملة</label>
                            <select id="transactionType" name="type" class="form-select">
                                <option value="" disabled {{ old('type') ? '' : 'selected' }}>اختر</option>
                                <option value="credit" {{ old('type') === 'credit' ? 'selected' : '' }}>إيداع (Credit)</option>
                                <option value="debit" {{ old('type') === 'debit' ? 'selected' : '' }}>سحب (Debit)</option>
                            </select>
                        </div>

                        <div class="col-12 col-md-4">
                            <label class="form-label" for="transactionValue">القيمة</label>
                            <input type="number" step="0.01" min="0" id="transactionValue" name="value" class="form-control" placeholder="0.00" value="{{ old('value') }}" />
                        </div>

                        <div class="col-12 col-md-4">
                            <label class="form-label" for="transactionRate">سعر الصرف</label>
                            <input type="number" step="0.0001" min="0" id="transactionRate" name="exchange_rate" class="form-control" placeholder="1.0000" value="{{ old('exchange_rate') }}" />
                        </div>

                        <div class="col-4">
                            <label class="form-label" for="transactionAmount">المبلغ النهائي</label>
                            <input type="number" step="0.01" min="0" id="transactionAmount" name="amount" class="form-control" placeholder="0.00" readonly />
                        </div>

                        <div class="col-12">
                            <label class="form-label" for="transactionDescription">الوصف</label>
                            <textarea id="transactionDescription" name="description" class="form-control" rows="3" placeholder="اكتب وصف المعاملة">{{ old('description') }}</textarea>
                        </div>

                        <div class="col-12">
                            <label class="form-label" for="transactionAttachment">مرفق</label>
                            <input type="file" id="transactionAttachment" name="attachment" class="form-control" />
                        </div>

                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-primary me-3">حفظ</button>
                            <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">إلغاء</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
