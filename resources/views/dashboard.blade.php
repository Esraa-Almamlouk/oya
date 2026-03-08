@extends('layouts/layoutMaster')

@section('title', 'الرئيسية')

@section('vendor-style')
    @vite([
    'resources/assets/vendor/libs/apex-charts/apex-charts.scss',
    'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
    'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss'
])
@endsection

@section('page-style')
    @vite('resources/assets/vendor/scss/pages/app-logistics-dashboard.scss')
@endsection

@section('vendor-script')
    @vite([
    'resources/assets/vendor/libs/apex-charts/apexcharts.js',
    'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js'
])
@endsection

@section('page-script')
    @vite([
    'resources/assets/js/app-logistics-dashboard.js',
    'resources/assets/js/dashboards-crm.js'
])
@endsection

@section('content')
            <div class="row g-6">
                <!-- Card Border Shadow -->
                <div class="col-lg-3 col-sm-6">
                    <div class="card card-border-shadow-primary h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar me-4">
                                    <span class="avatar-initial rounded bg-label-primary">
                                        <i class='ti ti-users ti-28px'></i>
                                    </span>
                                </div>
                                <div>
                                    <h4 class="mb-0">{{ $usersCount }}</h4>
                                    <p class="my-0">مستخدم</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-sm-6">
                    <div class="card card-border-shadow-warning h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-2">
                                <div class="avatar me-4">
                                    <span class="avatar-initial rounded bg-label-warning">
                                        <i class='ti ti-wallet ti-28px'></i>
                                    </span>
                                </div>
                                <div>
                                    <h4 class="mb-0">{{ $accountsCount }}</h4>
                                    <p class="mb-1">حساب</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-sm-6">
                    <div class="card card-border-shadow-danger h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-2">
                                <div class="avatar me-4">
                                    <span class="avatar-initial rounded bg-label-danger">
                                        <i class='ti ti-invoice ti-28px'></i>
                                    </span>
                                </div>
                                <div>
                                    <h4 class="mb-0">{{ $transactionsCount }}</h4>
                                    <p class="mb-1">اجمالي المعاملات</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-sm-6">
                    <div class="card card-border-shadow-info h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-2">
                                <div class="avatar me-4">
                                    <span class="avatar-initial rounded bg-label-info">
                                        <i class='ti ti-calendar-event ti-28px'></i>
                                    </span>
                                </div>
                                <div>
                                    <h4 class="mb-0">{{ $todayTransactionsCount }}</h4>
                                    <p class="mb-1">معاملات اليوم</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/ Card Border Shadow -->



                @php($netCashflow = $totalIncoming - $totalOutgoing)
                <div class="col-xxl-6 col-md-6">
            <div class="card h-100">
              <div class="card-header d-flex justify-content-between">
                <h5 class="mb-0 card-title">التدفق النقدي الاسبوعي</h5>
              </div>
              <div class="card-body">
                <div class="d-flex align-items-start">
                  <div class="d-flex justify-content-between w-100 gap-2 align-items-center">
                  </div>
                </div>
                <div id="projectStatusChart"
                     data-labels='@json($projectStatusLabels)'
                     data-incoming='@json($projectStatusIncoming)'
                     data-outgoing='@json($projectStatusOutgoing)'></div>
                <div class="d-flex justify-content-between mb-4">
                  <h6 class="mb-0">ايداع</h6>
                  <div class="d-flex">
                    <p class="mb-0 me-4">{{ number_format($totalIncoming, 2) }}</p>
                  </div>
                </div>
                <div class="d-flex justify-content-between">
                  <h6 class="mb-0">سحب</h6>
                  <div class="d-flex">
                    <p class="mb-0 me-4">{{ number_format($totalOutgoing, 2) }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Last Transaction -->
      <div class="col-xl-6">
        <div class="card h-100">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title m-0 me-2">آخر المعاملات</h5>

          </div>
          <div class="table-responsive">
            <table class="table table-borderless border-top">
              <thead class="border-bottom">
                <tr>
                  <th>الحساب</th>
                  <th>التاريخ</th>
                  <th>النوع</th>
                  <th>المبلغ</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($recentTransactions as $transaction)
                  <tr>
                    <td>
                      <div class="d-flex flex-column">
                        <p class="mb-0 text-heading">{{ $transaction->account->name ?? 'غير محدد' }}</p>
                        <small class="text-body">{{ $transaction->reference ?? '-' }}</small>
                      </div>
                    </td>
                    <td>
                      <p class="mb-0 text-heading">{{ \Carbon\Carbon::parse($transaction->date)->format('Y-m-d') }}</p>
                    </td>
                    <td>
                      @if ($transaction->type === 'credit')
                        <span class="badge bg-label-success">ايداع</span>
                      @else
                        <span class="badge bg-label-danger">سحب</span>
                      @endif
                    </td>
                    <td>
                      <p class="mb-0 text-heading {{ $transaction->type === 'credit' ? 'text-success' : 'text-danger' }}">
                        {{ $transaction->type === 'credit' ? '+' : '-' }}{{ number_format((float) $transaction->amount) }}
                      </p>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="4" class="text-center text-body py-6">لا توجد معاملات حتى الآن</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>

            </div>
@endsection
