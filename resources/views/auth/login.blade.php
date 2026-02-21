@php
$customizerHidden = 'customizer-hide';
@endphp

@extends('layouts/blankLayout')

@section('title', 'تسجيل الدخول')

@section('vendor-style')
    @vite([
    'resources/assets/vendor/libs/@form-validation/form-validation.scss'
])
@endsection

@section('page-style')
    @vite([
    'resources/assets/vendor/scss/pages/page-auth.scss'
])
@endsection

@section('vendor-script')
    @vite([
    'resources/assets/vendor/libs/@form-validation/popular.js',
    'resources/assets/vendor/libs/@form-validation/bootstrap5.js',
    'resources/assets/vendor/libs/@form-validation/auto-focus.js'
])
@endsection

@section('page-script')
    @vite([
    'resources/assets/js/pages-auth.js'
])
@endsection

@section('content')
    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner py-6">
                <!-- Login -->
                <div class="card">
                    <div class="card-body">
                        <!-- Logo -->
                        <div class="app-brand justify-content-center">
                            <a href="{{url('/')}}" class="app-brand-link">
                                <img src="{{ asset('assets/img/logo.png') }}"
                                    alt="OYA Logo"
                                    class="app-brand-logo demo"
                                    style="height: 15rem; width: auto;">
                            </a>
                        </div>
                        <!-- /Logo -->

                        <!-- error message -->
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0 mx-0">
                                    @foreach ($errors->all() as $error)
                                        <li style="list-style: none">{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <!-- /error message -->
                        <form id="formAuthentication" class="mb-4" action="{{route('auth.login')}}" method="POST">
                            @csrf
                            <div class="mb-6">
                                <label for="email" class="form-label">البريد الالكتروني</label>
                                <input type="text" class="form-control" id="email" name="email"
                                    placeholder="example@email.com" autofocus>
                            </div>
                            <div class="mb-6 form-password-toggle">
                                <label class="form-label" for="password">كلمة المرور</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password" class="form-control" name="password"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        aria-describedby="password" />
                                    <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                                </div>
                            </div>
                            <div class="my-8">
                                <div class="d-flex justify-content-between">
                                    <div class="form-check mb-0 ms-2">
                                        <input class="form-check-input" type="checkbox" id="remember-me">
                                        <label class="form-check-label" for="remember-me">
                                            تذكرني
                                        </label>
                                    </div>
                                    <a href="{{url('auth/forgot-password-basic')}}">
                                        <p class="mb-0">هل نسيت كلمة المرور؟</p>
                                    </a>
                                </div>
                            </div>
                            <div class="mb-6">
                                <button class="btn btn-primary d-grid w-100" type="submit">تسجيل الدخول</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- /Login -->
            </div>
        </div>
    </div>
@endsection
