/**
 * Page Account Transactions
 */

'use strict';

    $(function () {
    var dtTable = $('.datatables-account-transactions');
    var addForm = $('#addTransactionForm');
    var transactionDate = $('#transactionDate');
    var transactionValue = $('#transactionValue');
    var transactionRate = $('#transactionRate');
    var transactionAmount = $('#transactionAmount');

    function setDefaultDateToToday() {
        if (!transactionDate.length) return;
        if (transactionDate.val()) return;

        var now = new Date();
        var month = String(now.getMonth() + 1).padStart(2, '0');
        var day = String(now.getDate()).padStart(2, '0');
        transactionDate.val(now.getFullYear() + '-' + month + '-' + day);
    }

    function calculateAmount() {
        if (!transactionAmount.length) return;

        var value = parseFloat(transactionValue.val());
        var rate = parseFloat(transactionRate.val());

        if (Number.isFinite(value) && Number.isFinite(rate)) {
        transactionAmount.val((value * rate).toFixed(2));
        return;
        }

        transactionAmount.val('');
    }

    setDefaultDateToToday();
    calculateAmount();

    transactionValue.on('input', calculateAmount);
    transactionRate.on('input', calculateAmount);

    if (dtTable.length) {
        dtTable.DataTable({
        columnDefs: [
            {
            className: 'control',
            searchable: false,
            orderable: false,
            targets: 0,
            render: function () {
                return '';
            }
            },
            {
            targets: 1,
            orderable: false,
            checkboxes: {
                selectAllRender: '<input type="checkbox" class="form-check-input">'
            },
            render: function () {
                return '<input type="checkbox" class="dt-checkboxes form-check-input" >';
            },
            searchable: false
            }
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
            info: 'عرض _START_ إلى _END_ من أصل _TOTAL_ معاملة',
            infoEmpty: 'عرض 0 إلى 0 من أصل 0 معاملة',
            infoFiltered: '(تمت التصفية من إجمالي _MAX_)',
            zeroRecords: 'لم يتم العثور على سجلات مطابقة',
            emptyTable: 'لا توجد بيانات متاحة',
            paginate: {
            next: '<i class="ti ti-chevron-right ti-sm"></i>',
            previous: '<i class="ti ti-chevron-left ti-sm"></i>'
            }
        },
        buttons: [
            {
            extend: 'collection',
            className: 'btn btn-label-secondary dropdown-toggle mx-4 waves-effect waves-light',
            text: '<i class="ti ti-upload me-2 ti-xs"></i>مشاركة',
            buttons: [
                { extend: 'print', text: '<i class="ti ti-printer me-2"></i>Print', className: 'dropdown-item', exportOptions: { columns: [2, 3, 4, 5, 6, 7] } },
                { extend: 'csv', text: '<i class="ti ti-file-text me-2"></i>Csv', className: 'dropdown-item', exportOptions: { columns: [2, 3, 4, 5, 6, 7] } },
                { extend: 'excel', text: '<i class="ti ti-file-spreadsheet me-2"></i>Excel', className: 'dropdown-item', exportOptions: { columns: [2, 3, 4, 5, 6, 7] } },
                { extend: 'pdf', text: '<i class="ti ti-file-code-2 me-2"></i>Pdf', className: 'dropdown-item', exportOptions: { columns: [2, 3, 4, 5, 6, 7] } },
                { extend: 'copy', text: '<i class="ti ti-copy me-2"></i>Copy', className: 'dropdown-item', exportOptions: { columns: [2, 3, 4, 5, 6, 7] } }
            ]
            },
            {
            text: '<i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span class="d-none d-sm-inline-block">إضافة معاملة</span>',
            className: 'btn btn-primary waves-effect waves-light',
            attr: {
                'data-bs-toggle': 'modal',
                'data-bs-target': '#addTransactionModal'
            }
            }
        ],
        responsive: {
            details: {
            display: $.fn.dataTable.Responsive.display.modal({
                header: function () {
                return 'Transaction Details';
                }
            }),
            type: 'column',
            renderer: function (api, rowIdx, columns) {
                var data = $.map(columns, function (col) {
                return col.title !== ''
                    ? '<tr data-dt-row="' +
                        col.rowIndex +
                        '" data-dt-column="' +
                        col.columnIndex +
                        '">' +
                        '<td>' +
                        col.title +
                        ':' +
                        '</td>' +
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
            this.api()
            .columns(5)
            .every(function () {
                var column = this;
                var select = $('<select class="form-select text-capitalize"><option value=""> نوع المعاملة </option></select>')
                .appendTo('.transaction_type')
                .on('change', function () {
                    var val = $(this).val().trim();
                    column.search(val, false, false).draw();
                });

                select.append('<option value="إيداع">إيداع</option>');
                select.append('<option value="سحب">سحب</option>');
            });
        }
        });

        setTimeout(function () {
        $('.dataTables_filter .form-control').removeClass('form-control-sm');
        $('.dataTables_length .form-select').removeClass('form-select-sm');
        }, 300);
    }

    $('#addTransactionModal').on('shown.bs.modal', function () {
        setDefaultDateToToday();
        calculateAmount();
    });

    if (addForm.length && String(addForm.data('has-errors')) === '1') {
        var modalEl = document.getElementById('addTransactionModal');
        if (modalEl && typeof bootstrap !== 'undefined') {
        bootstrap.Modal.getOrCreateInstance(modalEl).show();
        }
    }

    if (addForm.length && typeof FormValidation !== 'undefined') {
        FormValidation.formValidation(addForm[0], {
        fields: {
            date: {
            validators: {
                notEmpty: {
                message: 'اختر التاريخ'
                },
                date: {
                format: 'YYYY-MM-DD',
                message: 'صيغة التاريخ غير صحيحة'
                }
            }
            },
            type: {
            validators: {
                notEmpty: {
                message: 'اختر نوع المعاملة'
                }
            }
            },
            value: {
            validators: {
                notEmpty: {
                message: ' القيمة مطلوبة'
                },
                numeric: {
                message: 'القيمة يجب أن تكون رقمًا'
                },
                greaterThan: {
                min: 0,
                inclusive: false,
                message: 'القيمة يجب أن تكون أكبر من صفر'
                }
            }
            },
            exchange_rate: {
            validators: {
                notEmpty: {
                message: ' سعر الصرف مطلوب'
                },
                numeric: {
                message: 'سعر الصرف يجب أن يكون رقمًا'
                },
                greaterThan: {
                min: 0,
                inclusive: false,
                message: 'سعر الصرف يجب أن يكون أكبر من صفر'
                }
            }
            },
            description: {
            validators: {
                notEmpty: {
                message: ' وصف المعاملة مطلوب'
                }
            }
            }
        },
        plugins: {
            trigger: new FormValidation.plugins.Trigger(),
            bootstrap5: new FormValidation.plugins.Bootstrap5({
            eleValidClass: '',
            rowSelector: function () {
                return '.col-12, .col-12.col-md-6, .col-12.col-md-4, .col-4';
            }
            }),
            submitButton: new FormValidation.plugins.SubmitButton(),
            defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
            autoFocus: new FormValidation.plugins.AutoFocus()
        }
        });
    }
    });
