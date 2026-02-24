@extends('layouts/layoutMaster')

@section('title', 'ادارة المستخدمين')

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
    @vite('resources/assets/js/app-user-list.js')
@endsection

@section('content')

    <div class="row g-6 mb-6 users-page">
    <!-- Users List Table -->
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
                <h5 class="card-title mb-0">إدارة المستخدمين</h5>
                </div>
        <div class="card-header border-bottom">
            <h5 class="card-title mb-0">الفلترة</h5>
            <div class="row users-filter-row pt-4 g-4">
                <div class="col-12 col-sm-6 col-md-4 user_status"></div>
            </div>
        </div>
        <div class="card-datatable table-responsive">
            <table class="datatables-users table">
                <thead class="border-top">
                    <tr>
                        <th></th>
                        <th></th>
                        <th>#</th>
                        <th>اسم المستخدم</th>
                        <th>البريد الالكتروني</th>
                        <th>رقم الهاتف</th>
                        <th>الحالة</th>
                        <th>الاجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td></td>
                            <td></td>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone }}</td>
                            <td>
                                <span class="{{ $user->is_active == '1' ? 'badge bg-label-success' : 'badge bg-label-danger' }}">
                                    {{ $user->is_active == '1' ? 'مفعل' : 'غير مفعل' }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center user-actions">
                                    <a href="javascript:;"
                                        class="btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill edit-record"
                                        data-bs-toggle="offcanvas"
                                        data-bs-target="#offcanvasAddUser"
                                        data-user='@json($user)'>
                                            <i class="ti ti-edit ti-md"></i>
                                    </a>
                                    {{-- <a href="javascript:;"
                                        class="btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill edit-record"
                                        data-bs-toggle="offcanvas"
                                        data-bs-target="#offcanvasAddUser"
                                        data-user='@json($user)'>
                                            <i class="ti ti-eye ti-md"></i>
                                    </a> --}}
                                    @if (auth()->id() !== $user->id)
                                        <a href="javascript:;"
                                            class="btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill delete-record"
                                            data-id="{{ $user->id }}"
                                            data-url="{{ route('users.destroy', $user) }}">
                                                <i class="ti ti-trash ti-md"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>

                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- Offcanvas to add new user -->
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddUser" aria-labelledby="offcanvasAddUserLabel">
            <div class="offcanvas-header border-bottom">
                <h5 id="offcanvasAddUserLabel" class="offcanvas-title">إضافة مستخدم</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body mx-0 flex-grow-0 p-6 h-100">
                <form class="add-new-user pt-0" id="addNewUserForm" method="POST" action="{{ route('users.store') }}"
                    data-create-action="{{ route('users.store') }}" data-update-action-template="{{ url('/users/__id__') }}"
                    data-has-errors="{{ $errors->any() ? '1' : '0' }}">
                    @csrf
                    <input type="hidden" name="_method" id="userFormMethod" value="POST">
                    <input type="hidden" id="editingUserId" value="">

                    <div class="mb-6">
                        <label class="form-label" for="add-user-name">الاسم</label>
                        <input type="text" class="form-control" id="add-user-name" placeholder="أدخل الاسم" name="name"
                            value="{{ old('name') }}" required />
                    </div>

                    <div class="mb-6">
                        <label class="form-label" for="add-user-email">البريد الإلكتروني</label>
                        <input type="email" id="add-user-email" class="form-control" placeholder="example@email.com"
                            name="email" value="{{ old('email') }}" required />
                    </div>

                    <div class="mb-6">
                        <label class="form-label" for="add-user-phone">رقم الهاتف</label>
                        <input type="text" id="add-user-phone" class="form-control phone-mask" placeholder="09XXXXXXXX"
                            name="phone" value="{{ old('phone') }}" required />
                    </div>

                    <div class="mb-6">
                        <label class="form-label" for="add-user-status">الحالة</label>
                        <select id="add-user-status" class="form-select" name="is_active" required>
                            <option value="" disabled {{ old('is_active', '') === '' ? 'selected' : '' }}>اختر</option>
                            <option value="1" {{ old('is_active', '') === '1' ? 'selected' : '' }}>مفعل</option>
                            <option value="0" {{ old('is_active', '') === '0' ? 'selected' : '' }}>غير مفعل</option>
                        </select>
                    </div>

                    <div class="mb-6">
                        <label class="form-label" for="add-user-password">كلمة المرور</label>
                        <input type="password" id="add-user-password" class="form-control" name="password" minlength="8"
                            placeholder="********" required />
                        <small id="passwordHelpText" class="text-muted d-none">اترك كلمة المرور فارغة إذا لا تريد تغييرها.</small>
                    </div>

                    <div class="mb-6">
                        <label class="form-label" for="add-user-password-confirmation">تأكيد كلمة المرور</label>
                        <input type="password" id="add-user-password-confirmation" class="form-control"
                            name="password_confirmation" minlength="8" placeholder="********" required />
                    </div>

                    <button type="submit" class="btn btn-primary me-3 data-submit" id="userFormSubmitBtn">حفظ</button>
                    <button type="button" class="btn btn-label-danger" data-bs-dismiss="offcanvas">إلغاء</button>
                </form>
            </div>
        </div>
    </div>

@endsection

