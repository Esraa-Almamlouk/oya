@php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
$containerNav = ($configData['contentLayout'] === 'compact') ? 'container-xxl' : 'container-fluid';
$navbarDetached = ($navbarDetached ?? '');
@endphp

<!-- Navbar -->
@if(isset($navbarDetached) && $navbarDetached == 'navbar-detached')
<nav class="layout-navbar {{$containerNav}} navbar navbar-expand-xl {{$navbarDetached}} align-items-center bg-navbar-theme" id="layout-navbar">
  @endif
  @if(isset($navbarDetached) && $navbarDetached == '')
  <nav class="layout-navbar navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar">
    <div class="{{$containerNav}}">
      @endif

      <!--  Brand demo (display only for navbar-full and hide on below xl) -->
      @if(isset($navbarFull))
        <div class="navbar-brand app-brand demo d-none d-xl-flex py-0 me-4">
          <a href="{{url('/')}}" class="app-brand-link">
            <span class="app-brand-logo demo">@include('_partials.macros',["height"=>20])</span>
            <span class="app-brand-text demo menu-text fw-bold">{{config('variables.templateName')}}</span>
          </a>
          @if(isset($menuHorizontal))
            <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-xl-none">
              <i class="ti ti-x ti-md align-middle"></i>
            </a>
          @endif
        </div>
      @endif

      <!-- ! Not required for layout-without-menu -->
      @if(!isset($navbarHideToggle))
        <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0{{ isset($menuHorizontal) ? ' d-xl-none ' : '' }} {{ isset($contentNavbar) ?' d-xl-none ' : '' }}">
          <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="ti ti-menu-2 ti-md"></i>
          </a>
        </div>
      @endif

      <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">

        @if(!isset($menuHorizontal))
        <!-- Search -->
        <div class="navbar-nav align-items-center">
          <div class="nav-item navbar-search-wrapper mb-0">
            <a class="nav-item nav-link search-toggler d-flex align-items-center px-0" href="javascript:void(0);">
              <i class="ti ti-search ti-md me-2 me-lg-4 ti-lg"></i>
              <span class="d-none d-md-inline-block text-muted fw-normal">البحث</span>
            </a>
          </div>
        </div>
        <!-- /Search -->
        @endif

       <ul class="navbar-nav flex-row align-items-center ms-auto">
          @if(isset($menuHorizontal))
            <!-- Search -->
            <li class="nav-item navbar-search-wrapper">
              <a class="nav-link btn btn-text-secondary btn-icon rounded-pill search-toggler" href="javascript:void(0);">
                <i class="ti ti-search ti-md"></i>
              </a>
            </li>
            <!-- /Search -->
          @endif

          <!-- Notification -->
          <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-2">
            <a class="nav-link btn btn-text-secondary btn-icon rounded-pill dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
              <span class="position-relative">
                <i class="ti ti-bell ti-md"></i>
                <span class="badge rounded-pill bg-danger badge-dot badge-notifications border"></span>
              </span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end p-0">
              <li class="dropdown-menu-header border-bottom">
                <div class="dropdown-header d-flex align-items-center py-3">
                  <h6 class="mb-0 me-auto">Notification</h6>
                  <div class="d-flex align-items-center h6 mb-0">
                    <span class="badge bg-label-primary me-2">8 New</span>
                    <a href="javascript:void(0)" class="btn btn-text-secondary rounded-pill btn-icon dropdown-notifications-all" data-bs-toggle="tooltip" data-bs-placement="top" title="Mark all as read"><i class="ti ti-mail-opened text-heading"></i></a>
                  </div>
                </div>
              </li>
              <li class="dropdown-notifications-list scrollable-container">
                <ul class="list-group list-group-flush">
                  <li class="list-group-item list-group-item-action dropdown-notifications-item">
                    <div class="d-flex">
                      <div class="flex-shrink-0 me-3">
                        <div class="avatar">
                          <img src="{{asset('assets/img/avatars/1.png')}}" alt class="rounded-circle">
                        </div>
                      </div>
                      <div class="flex-grow-1">
                        <h6 class="small mb-1">Congratulation Lettie 🎉</h6>
                        <small class="mb-1 d-block text-body">Won the monthly best seller gold badge</small>
                        <small class="text-muted">1h ago</small>
                      </div>
                      <div class="flex-shrink-0 dropdown-notifications-actions">
                        <a href="javascript:void(0)" class="dropdown-notifications-read"><span class="badge badge-dot"></span></a>
                        <a href="javascript:void(0)" class="dropdown-notifications-archive"><span class="ti ti-x"></span></a>
                      </div>
                    </div>
                  </li>
                  <li class="list-group-item list-group-item-action dropdown-notifications-item">
                    <div class="d-flex">
                      <div class="flex-shrink-0 me-3">
                        <div class="avatar">
                          <span class="avatar-initial rounded-circle bg-label-danger">CF</span>
                        </div>
                      </div>
                      <div class="flex-grow-1">
                        <h6 class="mb-1 small">Charles Franklin</h6>
                        <small class="mb-1 d-block text-body">Accepted your connection</small>
                        <small class="text-muted">12hr ago</small>
                      </div>
                      <div class="flex-shrink-0 dropdown-notifications-actions">
                        <a href="javascript:void(0)" class="dropdown-notifications-read"><span class="badge badge-dot"></span></a>
                        <a href="javascript:void(0)" class="dropdown-notifications-archive"><span class="ti ti-x"></span></a>
                      </div>
                    </div>
                  </li>
                  <li class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
                    <div class="d-flex">
                      <div class="flex-shrink-0 me-3">
                        <div class="avatar">
                          <img src="{{asset('assets/img/avatars/2.png')}}" alt class="rounded-circle">
                        </div>
                      </div>
                      <div class="flex-grow-1">
                        <h6 class="mb-1 small">New Message ✉️</h6>
                        <small class="mb-1 d-block text-body">You have new message from Natalie</small>
                        <small class="text-muted">1h ago</small>
                      </div>
                      <div class="flex-shrink-0 dropdown-notifications-actions">
                        <a href="javascript:void(0)" class="dropdown-notifications-read"><span class="badge badge-dot"></span></a>
                        <a href="javascript:void(0)" class="dropdown-notifications-archive"><span class="ti ti-x"></span></a>
                      </div>
                    </div>
                  </li>
                  <li class="list-group-item list-group-item-action dropdown-notifications-item">
                    <div class="d-flex">
                      <div class="flex-shrink-0 me-3">
                        <div class="avatar">
                          <span class="avatar-initial rounded-circle bg-label-success"><i class="ti ti-shopping-cart"></i></span>
                        </div>
                      </div>
                      <div class="flex-grow-1">
                        <h6 class="mb-1 small">Whoo! You have new order 🛒 </h6>
                        <small class="mb-1 d-block text-body">ACME Inc. made new order $1,154</small>
                        <small class="text-muted">1 day ago</small>
                      </div>
                      <div class="flex-shrink-0 dropdown-notifications-actions">
                        <a href="javascript:void(0)" class="dropdown-notifications-read"><span class="badge badge-dot"></span></a>
                        <a href="javascript:void(0)" class="dropdown-notifications-archive"><span class="ti ti-x"></span></a>
                      </div>
                    </div>
                  </li>
                  <li class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
                    <div class="d-flex">
                      <div class="flex-shrink-0 me-3">
                        <div class="avatar">
                          <img src="{{asset('assets/img/avatars/9.png')}}" alt class="rounded-circle">
                        </div>
                      </div>
                      <div class="flex-grow-1">
                        <h6 class="mb-1 small">Application has been approved 🚀 </h6>
                        <small class="mb-1 d-block text-body">Your ABC project application has been approved.</small>
                        <small class="text-muted">2 days ago</small>
                      </div>
                      <div class="flex-shrink-0 dropdown-notifications-actions">
                        <a href="javascript:void(0)" class="dropdown-notifications-read"><span class="badge badge-dot"></span></a>
                        <a href="javascript:void(0)" class="dropdown-notifications-archive"><span class="ti ti-x"></span></a>
                      </div>
                    </div>
                  </li>
                  <li class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
                    <div class="d-flex">
                      <div class="flex-shrink-0 me-3">
                        <div class="avatar">
                          <span class="avatar-initial rounded-circle bg-label-success"><i class="ti ti-chart-pie"></i></span>
                        </div>
                      </div>
                      <div class="flex-grow-1">
                        <h6 class="mb-1 small">Monthly report is generated</h6>
                        <small class="mb-1 d-block text-body">July monthly financial report is generated </small>
                        <small class="text-muted">3 days ago</small>
                      </div>
                      <div class="flex-shrink-0 dropdown-notifications-actions">
                        <a href="javascript:void(0)" class="dropdown-notifications-read"><span class="badge badge-dot"></span></a>
                        <a href="javascript:void(0)" class="dropdown-notifications-archive"><span class="ti ti-x"></span></a>
                      </div>
                    </div>
                  </li>
                  <li class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
                    <div class="d-flex">
                      <div class="flex-shrink-0 me-3">
                        <div class="avatar">
                          <img src="{{asset('assets/img/avatars/5.png')}}" alt class="rounded-circle">
                        </div>
                      </div>
                      <div class="flex-grow-1">
                        <h6 class="mb-1 small">Send connection request</h6>
                        <small class="mb-1 d-block text-body">Peter sent you connection request</small>
                        <small class="text-muted">4 days ago</small>
                      </div>
                      <div class="flex-shrink-0 dropdown-notifications-actions">
                        <a href="javascript:void(0)" class="dropdown-notifications-read"><span class="badge badge-dot"></span></a>
                        <a href="javascript:void(0)" class="dropdown-notifications-archive"><span class="ti ti-x"></span></a>
                      </div>
                    </div>
                  </li>
                  <li class="list-group-item list-group-item-action dropdown-notifications-item">
                    <div class="d-flex">
                      <div class="flex-shrink-0 me-3">
                        <div class="avatar">
                          <img src="{{asset('assets/img/avatars/6.png')}}" alt class="rounded-circle">
                        </div>
                      </div>
                      <div class="flex-grow-1">
                        <h6 class="mb-1 small">New message from Jane</h6>
                        <small class="mb-1 d-block text-body">Your have new message from Jane</small>
                        <small class="text-muted">5 days ago</small>
                      </div>
                      <div class="flex-shrink-0 dropdown-notifications-actions">
                        <a href="javascript:void(0)" class="dropdown-notifications-read"><span class="badge badge-dot"></span></a>
                        <a href="javascript:void(0)" class="dropdown-notifications-archive"><span class="ti ti-x"></span></a>
                      </div>
                    </div>
                  </li>
                  <li class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
                    <div class="d-flex">
                      <div class="flex-shrink-0 me-3">
                        <div class="avatar">
                          <span class="avatar-initial rounded-circle bg-label-warning"><i class="ti ti-alert-triangle"></i></span>
                        </div>
                      </div>
                      <div class="flex-grow-1">
                        <h6 class="mb-1 small">CPU is running high</h6>
                        <small class="mb-1 d-block text-body">CPU Utilization Percent is currently at 88.63%,</small>
                        <small class="text-muted">5 days ago</small>
                      </div>
                      <div class="flex-shrink-0 dropdown-notifications-actions">
                        <a href="javascript:void(0)" class="dropdown-notifications-read"><span class="badge badge-dot"></span></a>
                        <a href="javascript:void(0)" class="dropdown-notifications-archive"><span class="ti ti-x"></span></a>
                      </div>
                    </div>
                  </li>
                </ul>
              </li>
              <li class="border-top">
                <div class="d-grid p-4">
                  <a class="btn btn-primary btn-sm d-flex" href="javascript:void(0);">
                    <small class="align-middle">View all notifications</small>
                  </a>
                </div>
              </li>
            </ul>
          </li>
          <!--/ Notification -->

          <!-- User -->
          <li class="nav-item navbar-dropdown dropdown-user dropdown">
            <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);" data-bs-toggle="dropdown">
              <div class="avatar avatar-online">
                <img src="{{asset('assets/img/user.png')}}" alt class="rounded-circle">
              </div>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li>
                <a class="dropdown-item mt-0" href="{{ Route::has('profile.show') ? route('profile.show') : url('pages/profile-user') }}">
                  <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 me-2">
                      <div class="avatar avatar-online">
                        <img src="{{ asset('assets/img/user.png') }}" alt class="rounded-circle">
                      </div>
                    </div>
                    <div class="flex-grow-1">
                      <h6 class="mb-0">
                        @if (Auth::check())
                          {{ Auth::user()->name }}
                        @else
                          مستخدم
                        @endif
                      </h6>
                    </div>
                  </div>
                </a>
              </li>
              <li>
                <div class="dropdown-divider my-1 mx-n2"></div>
              </li>
              <li>
                <a class="dropdown-item" href="{{ Route::has('profile.show') ? route('profile.show') : url('pages/profile-user') }}">
                  <i class="ti ti-user me-3 ti-md"></i><span class="align-middle">الملف الشخصي</span>
                </a>
              </li>


              <li>
                <div class="dropdown-divider my-1 mx-n2"></div>
              </li>
              @if (Auth::check())
                <li>
                  <div class="d-grid px-2 pt-2 pb-1">
                    <a class="btn btn-sm btn-danger d-flex" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                      <small class="align-middle">تسجيل الخروج</small>
                      <i class="ti ti-logout ms-2 ti-14px"></i>
                    </a>
                  </div>
                </li>
                <form method="POST" id="logout-form" action="{{ route('logout') }}">
                  @csrf
                </form>
              @else
                <li>
                  <div class="d-grid px-2 pt-2 pb-1">
                    <a class="btn btn-sm btn-danger d-flex" href="{{ Route::has('login') ? route('login') : url('auth/login-basic') }}">
                      <small class="align-middle">Login</small>
                      <i class="ti ti-login ms-2 ti-14px"></i>
                    </a>
                  </div>
                </li>
              @endif
            </ul>
          </li>
          <!--/ User -->
        </ul>
      </div>

      <!-- Search Small Screens -->
      <div class="navbar-search-wrapper search-input-wrapper {{ isset($menuHorizontal) ? $containerNav : '' }} d-none">
        <input type="text" class="form-control search-input {{ isset($menuHorizontal) ? '' : $containerNav }} border-0" placeholder="Search..." aria-label="Search...">
        <i class="ti ti-x search-toggler cursor-pointer"></i>
      </div>
      <!--/ Search Small Screens -->
      @if(isset($navbarDetached) && $navbarDetached == '')
    </div>
    @endif
  </nav>
  <!-- / Navbar -->
