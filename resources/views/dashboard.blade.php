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
                                    <h4 class="mb-0">42</h4>
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
                                    <h4 class="mb-0">8</h4>
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
                                    <h4 class="mb-0">27</h4>
                                    <p class="mb-1">فاتورة</p>
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
                                        <i class='ti ti-invoice ti-28px'></i>
                                    </span>
                                </div>
                                <div>
                                    <h4 class="mb-0">27</h4>
                                    <p class="mb-1">فاتورة</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/ Card Border Shadow -->



                <div class="col-xxl-6 col-md-6">
            <div class="card h-100">
              <div class="card-header d-flex justify-content-between">
                <h5 class="mb-0 card-title">Project Status</h5>
                <div class="dropdown">
                  <button class="btn btn-text-secondary rounded-pill text-muted border-0 p-2 me-n1" type="button" id="projectStatusId" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="ti ti-dots-vertical ti-md text-muted"></i>
                  </button>
                  <div class="dropdown-menu dropdown-menu-end" aria-labelledby="projectStatusId">
                    <a class="dropdown-item" href="javascript:void(0);">View More</a>
                    <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <div class="d-flex align-items-start">
                  <div class="badge rounded bg-label-warning p-2 me-3 rounded"><i class="ti ti-currency-dollar ti-lg"></i></div>
                  <div class="d-flex justify-content-between w-100 gap-2 align-items-center">
                    <div class="me-2">
                      <h6 class="mb-0">$4,3742</h6>
                      <small class="text-body">Your Earnings</small>
                    </div>
                    <h6 class="mb-0 text-success">+10.2%</h6>
                  </div>
                </div>
                <div id="projectStatusChart"></div>
                <div class="d-flex justify-content-between mb-4">
                  <h6 class="mb-0">Donates</h6>
                  <div class="d-flex">
                    <p class="mb-0 me-4">$756.26</p>
                    <p class="mb-0 text-danger">-139.34</p>
                  </div>
                </div>
                <div class="d-flex justify-content-between">
                  <h6 class="mb-0">Podcasts</h6>
                  <div class="d-flex">
                    <p class="mb-0 me-4">$2,207.03</p>
                    <p class="mb-0 text-success">+576.24</p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Last Transaction -->
      <div class="col-xl-6">
        <div class="card h-100">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title m-0 me-2">Last Transaction</h5>
            <div class="dropdown">
              <button class="btn btn-text-secondary rounded-pill text-muted border-0 p-2 me-n1" type="button" id="teamMemberList" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="ti ti-dots-vertical ti-md text-muted"></i>
              </button>
              <div class="dropdown-menu dropdown-menu-end" aria-labelledby="teamMemberList">
                <a class="dropdown-item" href="javascript:void(0);">Download</a>
                <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                <a class="dropdown-item" href="javascript:void(0);">Share</a>
              </div>
            </div>
          </div>
          <div class="table-responsive">
            <table class="table table-borderless border-top">
              <thead class="border-bottom">
                <tr>
                  <th>CARD</th>
                  <th>DATE</th>
                  <th>STATUS</th>
                  <th>TREND</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="pt-5">
                    <div class="d-flex justify-content-start align-items-center">
                      <div class="me-4">
                        <img src="{{asset('assets/img/icons/payments/visa-img.png')}}" alt="Visa" height="30">
                      </div>
                      <div class="d-flex flex-column">
                        <p class="mb-0 text-heading">*4230</p><small class="text-body">Credit</small>
                      </div>
                    </div>
                  </td>
                  <td class="pt-5">
                    <div class="d-flex flex-column">
                      <p class="mb-0 text-heading">Sent</p>
                      <small class="text-body text-nowrap">17 Mar 2022</small>
                    </div>
                  </td>
                  <td class="pt-5"><span class="badge bg-label-success">Verified</span></td>
                  <td class="pt-5">
                    <p class="mb-0 text-heading">+$1,678</p>
                  </td>
                </tr>
                <tr>
                  <td>
                    <div class="d-flex justify-content-start align-items-center">
                      <div class="me-4">
                        <img src="{{asset('assets/img/icons/payments/master-card-img.png')}}" alt="Visa" height="30">
                      </div>
                      <div class="d-flex flex-column">
                        <p class="mb-0 text-heading">*5578</p><small class="text-body">Credit</small>
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="d-flex flex-column">
                      <p class="mb-0 text-heading">Sent</p>
                      <small class="text-body text-nowrap">12 Feb 2022</small>
                    </div>
                  </td>
                  <td><span class="badge bg-label-danger">Rejected</span></td>
                  <td>
                    <p class="mb-0 text-heading">-$839</p>
                  </td>
                </tr>
                <tr>
                  <td>
                    <div class="d-flex justify-content-start align-items-center">
                      <div class="me-4">
                        <img src="{{asset('assets/img/icons/payments/american-express-img.png')}}" alt="Visa" height="30">
                      </div>
                      <div class="d-flex flex-column">
                        <p class="mb-0 text-heading">*4567</p><small class="text-body">ATM</small>
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="d-flex flex-column">
                      <p class="mb-0 text-heading">Sent</p>
                      <small class="text-body text-nowrap">28 Feb 2022</small>
                    </div>
                  </td>
                  <td><span class="badge bg-label-success">Verified</span></td>
                  <td>
                    <p class="mb-0 text-heading">+$435</p>
                  </td>
                </tr>
                <tr>
                  <td>
                    <div class="d-flex justify-content-start align-items-center">
                      <div class="me-4">
                        <img src="{{asset('assets/img/icons/payments/visa-img.png')}}" alt="Visa" height="30">
                      </div>
                      <div class="d-flex flex-column">
                        <p class="mb-0 text-heading">*5699</p><small class="text-body">Credit</small>
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="d-flex flex-column">
                      <p class="mb-0 text-heading">Sent</p>
                      <small class="text-body text-nowrap">8 Jan 2022</small>
                    </div>
                  </td>
                  <td><span class="badge bg-label-secondary">Pending</span></td>
                  <td>
                    <p class="mb-0 text-heading">+$2,345</p>
                  </td>
                </tr>
                <tr>
                  <td>
                    <div class="d-flex justify-content-start align-items-center">
                      <div class="me-4">
                        <img src="{{asset('assets/img/icons/payments/visa-img.png')}}" alt="Visa" height="30">
                      </div>
                      <div class="d-flex flex-column">
                        <p class="mb-0 text-heading">*5699</p><small class="text-body">Credit</small>
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="d-flex flex-column">
                      <p class="mb-0 text-heading">Sent</p>
                      <small class="text-body text-nowrap">8 Jan 2022</small>
                    </div>
                  </td>
                  <td><span class="badge bg-label-danger">Rejected</span></td>
                  <td>
                    <p class="mb-0 text-heading">-$234</p>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
                <!-- On route vehicles Table -->

                <div class="col-12 order-5">
                    <div class="card">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <div class="card-title mb-0">
                                <h5 class="m-0 me-2">On route vehicles</h5>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-text-secondary rounded-pill text-muted border-0 p-2 me-n1" type="button"
                                    id="routeVehicles" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="ti ti-dots-vertical ti-md text-muted"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="routeVehicles">
                                    <a class="dropdown-item" href="javascript:void(0);">Select All</a>
                                    <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                                    <a class="dropdown-item" href="javascript:void(0);">Share</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-datatable table-responsive">
                            <table class="dt-route-vehicles table table-sm">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th>location</th>
                                        <th>starting route</th>
                                        <th>ending route</th>
                                        <th>warnings</th>
                                        <th class="w-20">progress</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>

                <!--/ On route vehicles Table -->
            </div>
@endsection
