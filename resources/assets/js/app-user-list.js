/**
 * Page User List
 */

'use strict';

$(function () {

    // Variable declaration for table
    var dt_user_table = $('.datatables-users'),
        select2 = $('.select2');


    if (select2.length) {
        var $this = select2;
        $this.wrap('<div class="position-relative"></div>').select2({
        placeholder: 'Select Country',
        dropdownParent: $this.parent()
        });
    }

    // Users datatable
    if (dt_user_table.length) {
        var dt_user = dt_user_table.DataTable({
        columnDefs: [
            {
            // For Responsive Control
            className: 'control',
            searchable: false,
            orderable: false,
            targets: 0,
            render: function () {
                return '';
            }
            },
            {
            // For Checkboxes
            targets: 1,
            orderable: false,
            checkboxes: {
                selectAllRender: '<input type="checkbox" class="form-check-input">'
            },
            render: function () {
                return '<input type="checkbox" class="dt-checkboxes form-check-input" >';
            },
            searchable: false
            },
        ],
        order: [[2, 'desc']],
        dom:
            '<"row"' +
            '<"col-md-2"<"ms-n2"l>>' +
            '<"col-md-10"<"dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-md-row flex-column mb-6 mb-md-0 mt-n6 mt-md-0"fB>>' +
            '>t' +
            '<"row"' +
            '<"col-sm-12 col-md-6"i>' +
            '<"col-sm-12 col-md-6"p>' +
            '>',
        language: {
            sLengthMenu: '_MENU_',
            search: '',
            searchPlaceholder: 'بحث',
            info: 'عرض _START_ إلى _END_ من أصل _TOTAL_ مستخدم',
            infoEmpty: 'عرض 0 إلى 0 من أصل 0 مستخدم',
            infoFiltered: '(تمت التصفية من إجمالي _MAX_)',
            zeroRecords: 'لم يتم العثور على سجلات مطابقة',
            emptyTable: 'لا توجد بيانات متاحة',
            paginate: {
            next: '<i class="ti ti-chevron-right ti-sm"></i>',
            previous: '<i class="ti ti-chevron-left ti-sm"></i>'
            }
        },
        // Buttons with Dropdown
        buttons: [
            {
            text: '<i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span class="d-none d-sm-inline-block">اضافة مستخدم جديد</span>',
            className: 'add-new btn btn-primary waves-effect waves-light ms-2',
            attr: {
                'data-bs-toggle': 'offcanvas',
                'data-bs-target': '#offcanvasAddUser'
            }
            }
        ],
        // For responsive popup
        responsive: {
            details: {
            display: $.fn.dataTable.Responsive.display.modal({
                header: function (row) {
                var data = row.data();
                return 'Details of ' + data['full_name'];
                }
            }),
            type: 'column',
            renderer: function (api, rowIdx, columns) {
                var data = $.map(columns, function (col, i) {
                return col.title !== '' // ? Do not show row in modal popup if title is blank (for check box)
                    ? '<tr data-dt-row="' +
                        col.rowIndex +
                        '" data-dt-column="' +
                        col.columnIndex +
                        '">' +
                        '<td>' +
                        col.title +
                        ':' +
                        '</td> ' +
                        '<td>' +
                        col.data +
                        '</td>' +
                        '</tr>'
                    : '';
                }).join('');

                return data ? $('<table class="table"/><tbody />').append(data) : false;
            }
            }
        },
        initComplete: function () {

            // Adding status filter once table initialized
            this.api()
            .columns(6)
            .every(function () {
                var column = this;
                var select = $(
                '<select id="FilterTransaction" class="form-select text-capitalize"><option value=""> الحالة </option></select>'
                )
                .appendTo('.user_status')
                .on('change', function () {
                    var val = $(this).val().trim();
                    column.search(val, false, false).draw();
                });

                var statuses = ['مفعل', 'غير مفعل'];
                statuses.forEach(function (status) {
                select.append(
                    '<option value="' +
                    status +
                    '" class="text-capitalize">' +
                    status +
                    '</option>'
                );
                });
            });
        }
        });
    }

    var addNewUserForm = $('#addNewUserForm');
    var userFormMethod = $('#userFormMethod');
    var offcanvasTitle = $('#offcanvasAddUserLabel');
    var submitBtn = $('#userFormSubmitBtn');
    var passwordField = $('#add-user-password');
    var passwordConfirmField = $('#add-user-password-confirmation');
    var passwordHelpText = $('#passwordHelpText');

    function setFormCreateMode() {
        if (!addNewUserForm.length) return;
        addNewUserForm[0].reset();
        $('#add-user-status').val('');
        addNewUserForm.attr('action', addNewUserForm.data('create-action'));
        userFormMethod.val('POST');
        $('#editingUserId').val('');
        offcanvasTitle.text('إضافة مستخدم');
        submitBtn.text('إضافة');
        passwordField.prop('required', true);
        passwordConfirmField.prop('required', true);
        passwordHelpText.addClass('d-none');
    }

    $(document).on('click', '.add-new', function () {
        setFormCreateMode();
    });

    $(document).on('click', '.edit-record', function () {
        var user = $(this).data('user');
        if (!user || !addNewUserForm.length) return;

        var updateAction = addNewUserForm.data('update-action-template').replace('__id__', user.id);
        addNewUserForm.attr('action', updateAction);
        userFormMethod.val('PUT');
        $('#editingUserId').val(user.id);

        $('#add-user-name').val(user.name || '');
        $('#add-user-email').val(user.email || '');
        $('#add-user-phone').val(user.phone || '');
        $('#add-user-status').val(user.is_active ? '1' : '0');
        passwordField.val('');
        passwordConfirmField.val('');

        offcanvasTitle.text('تعديل مستخدم');
        submitBtn.text('تحديث');
        passwordField.prop('required', false);
        passwordConfirmField.prop('required', false);
        passwordHelpText.removeClass('d-none');
    });

    if (addNewUserForm.length && String(addNewUserForm.data('has-errors')) === '1') {
        var offcanvasEl = document.getElementById('offcanvasAddUser');
        if (offcanvasEl && typeof bootstrap !== 'undefined') {
            bootstrap.Offcanvas.getOrCreateInstance(offcanvasEl).show();
        }
    } else {
        setFormCreateMode();
    }

    // Delete/Archive Record (with confirmation + backend request)
    $('.datatables-users tbody').on('click', '.delete-record', function () {
        var row = $(this).parents('tr');
        var deleteUrl = $(this).data('url');
        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        var performDelete = function () {
        $.ajax({
            type: 'DELETE',
            url: deleteUrl,
            headers: {
            'X-CSRF-TOKEN': csrfToken
            },
            success: function (response) {
            if (dt_user) {
                dt_user.row(row).remove().draw();
            }

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                icon: 'success',
              text: response.message || 'تمت العملية بنجاح.',
                customClass: {
                    confirmButton: 'btn btn-success waves-effect waves-light'
                },
                buttonsStyling: false
                });
            }
            },
            error: function (xhr) {
          var message = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'حدث خطأ أثناء تنفيذ العملية.';
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                icon: 'error',
                text: message,
                customClass: {
                    confirmButton: 'btn btn-danger waves-effect waves-light'
                },
                buttonsStyling: false
                });
            } else {
                alert(message);
            }
            }
        });
        };

        if (typeof Swal !== 'undefined') {
        Swal.fire({
        text: 'هل انت متأكد من حذف هذا المستخدم؟',
            icon: 'warning',
            showCancelButton: true,
        confirmButtonText: 'نعم',
        cancelButtonText: 'إلغاء',
            customClass: {
            confirmButton: 'btn btn-warning me-3 waves-effect waves-light',
            cancelButton: 'btn btn-label-secondary waves-effect waves-light'
            },
            buttonsStyling: false
        }).then(function (result) {
            if (result.isConfirmed || result.value) {
            performDelete();
            }
        });
        return;
        }

    if (window.confirm('هل انت متأكد من حذف هذا المستخدم؟')) {
        performDelete();
        }
    });

    // Filter form control to default size
    // ? setTimeout used for multilingual table initialization
    setTimeout(() => {
        $('.dataTables_filter .form-control').removeClass('form-control-sm');
        $('.dataTables_length .form-select').removeClass('form-select-sm');
    }, 300);
    });

// Validation & Phone mask
(function () {
    const phoneMaskList = document.querySelectorAll('.phone-mask'),
        addNewUserForm = document.getElementById('addNewUserForm');

    // Phone Number
    if (phoneMaskList) {
        phoneMaskList.forEach(function (phoneMask) {
        new Cleave(phoneMask, {
            numericOnly: true,
            blocks: [10],
            delimiters: ['']
        });
        });
    }
    if (!addNewUserForm) return;

    // Add New User Form Validation
    FormValidation.formValidation(addNewUserForm, {
        fields: {
        name: {
            validators: {
            notEmpty: {
                message: 'الاسم المستخدم مطلوب'
            }
            }
        },
        email: {
            validators: {
            notEmpty: {
                message: 'البريد الإلكتروني مطلوب'
            },
            emailAddress: {
                message: 'البريد الإلكتروني غير صالح'
            }
            }
        },
        phone: {
            validators: {
            notEmpty: {
                message: 'رقم الهاتف مطلوب'
            },
            stringLength: {
                min: 10,
                max: 10,
                message: 'رقم الهاتف يجب أن يكون 10 أرقام'
            },
            callback: {
                message: 'رقم الهاتف يجب أن يبدأ بـ 091 أو 092 أو 093 أو 094',
                callback: function (input) {
                var value = String(input.value || '').trim();
                if (value.length < 3) return true;
                return /^(091|092|093|094)/.test(value);
                }
            }
            }
        },
        is_active: {
            validators: {
            notEmpty: {
                message: 'اختر الحالة'
            }
            }
        },
        password: {
            validators: {
            callback: {
                message: 'كلمة المرور مطلوبة ويجب أن تكون 8 أحرف على الأقل',
                callback: function (input) {
                var methodInput = addNewUserForm.querySelector('#userFormMethod');
                var isCreateMode = !methodInput || methodInput.value === 'POST';
                var value = String(input.value || '').trim();
                if (!isCreateMode && value.length === 0) return true;
                return value.length >= 8;
                }
            }
            }
        },
        password_confirmation: {
            validators: {
            callback: {
                message: 'تأكيد كلمة المرور مطلوب ويجب أن يكون 8 أحرف على الأقل',
                callback: function (input) {
                var methodInput = addNewUserForm.querySelector('#userFormMethod');
                var isCreateMode = !methodInput || methodInput.value === 'POST';
                var passwordValue = String(addNewUserForm.querySelector('[name="password"]').value || '').trim();
                var value = String(input.value || '').trim();
                if (!isCreateMode && passwordValue.length === 0 && value.length === 0) return true;
                return value.length >= 8;
                }
            },
            identical: {
                compare: function () {
                return addNewUserForm.querySelector('[name="password"]').value;
                },
                message: 'كلمة المرور وتأكيدها غير متطابقين'
            }
            }
        }
        },
        plugins: {
        trigger: new FormValidation.plugins.Trigger(),
        bootstrap5: new FormValidation.plugins.Bootstrap5({
            // Use this for enabling/changing valid/invalid class
            eleValidClass: '',
            rowSelector: function (field, ele) {
            // field is the field name & ele is the field element
            return '.mb-6';
            }
        }),
        submitButton: new FormValidation.plugins.SubmitButton(),
        // Submit the form when all fields are valid
        defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
        autoFocus: new FormValidation.plugins.AutoFocus()
        }
    });
})();
