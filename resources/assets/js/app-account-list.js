/**
 * Page Account List
 */

'use strict';

$(function () {
  var dt_account_table = $('.datatables-accounts'),
    select2 = $('.select2');

  if (select2.length) {
    var $this = select2;
    $this.wrap('<div class="position-relative"></div>').select2({
      placeholder: 'Select',
      dropdownParent: $this.parent()
    });
  }

  if (dt_account_table.length) {
    var dt_account = dt_account_table.DataTable({
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
        info: 'عرض _START_ إلى _END_ من أصل_TOTAL_ مستخدمين',
        infoEmpty: 'عرض 0 إلى 0 من أصل 0 حسابات',
        infoFiltered: '(تمت التصفية من إجمالي _MAX_ )',
        zeroRecords: 'لم يتم العثور على سجلات مطابقة',
        emptyTable: 'لا توجد بيانات متاحة',
        paginate: {
          next: '<i class="ti ti-chevron-right ti-sm"></i>',
          previous: '<i class="ti ti-chevron-left ti-sm"></i>'
        }
      },
      buttons: [
        {
          text: '<i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span class="d-none d-sm-inline-block">إضافة حساب جديد</span>',
          className: 'add-new btn btn-primary waves-effect waves-light ms-2',
          attr: {
            'data-bs-toggle': 'offcanvas',
            'data-bs-target': '#offcanvasAddAccount'
          }
        }
      ],
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
        var api = this.api();
        var categoryOptions = $('.account_category').data('options') || [];
        var currencyOptions = $('.account_currency').data('options') || [];

        api.columns(3).every(function () {
          var column = this;
          var select = $('<select class="form-select text-capitalize"><option value=""> التصنيف </option></select>')
            .appendTo('.account_category')
            .on('change', function () {
              var val = $(this).val().trim();
              column.search(val, false, false).draw();
            });

          var categories = Array.isArray(categoryOptions) ? categoryOptions.slice() : [];
          if (!categories.length) {
            column.data().unique().sort().each(function (d) {
              if (d && categories.indexOf(d) === -1) categories.push(d);
            });
          }

          categories.forEach(function (category) {
            select.append('<option value="' + category + '" class="text-capitalize">' + category + '</option>');
          });
        });

        api.columns(4).every(function () {
          var column = this;
          var select = $('<select class="form-select text-capitalize"><option value=""> العملة </option></select>')
            .appendTo('.account_currency')
            .on('change', function () {
              var val = $(this).val().trim();
              column.search(val, false, false).draw();
            });

          var currencies = Array.isArray(currencyOptions) ? currencyOptions.slice() : [];
          if (!currencies.length) {
            column.data().unique().sort().each(function (d) {
              if (d && currencies.indexOf(d) === -1) currencies.push(d);
            });
          }

          currencies.forEach(function (currency) {
            select.append('<option value="' + currency + '" class="text-capitalize">' + currency + '</option>');
          });
        });
      }
    });
  }

  var addNewAccountForm = $('#addNewAccountForm');
  var accountFormMethod = $('#accountFormMethod');
  var offcanvasTitle = $('#offcanvasAddAccountLabel');
  var submitBtn = $('#accountFormSubmitBtn');

  function setFormCreateMode() {
    if (!addNewAccountForm.length) return;
    addNewAccountForm[0].reset();
    $('#add-account-category').val('');
    $('#add-account-currency').val('');
    addNewAccountForm.attr('action', addNewAccountForm.data('create-action'));
    accountFormMethod.val('POST');
    $('#editingAccountId').val('');
    offcanvasTitle.text('اضافة حساب');
    submitBtn.text('اضافة');
  }

  $(document).on('click', '.add-new', function () {
    setFormCreateMode();
  });

  $(document).on('click', '.edit-record', function () {
    var $btn = $(this);
    var accountId = $btn.data('id');
    if (!accountId || !addNewAccountForm.length) return;

    var updateAction = addNewAccountForm.data('update-action-template').replace('__id__', accountId);
    addNewAccountForm.attr('action', updateAction);
    accountFormMethod.val('PUT');
    $('#editingAccountId').val(accountId);

    $('#add-account-name').val($btn.data('name') || '');
    $('#add-account-category').val($btn.data('category') || '');
    $('#add-account-currency').val($btn.data('currency') || '');
    $('#add-account-bank').val($btn.data('bank') || '');
    $('#add-account-phone').val($btn.data('phone') || '');

    offcanvasTitle.text('تعديل بيانات حساب');
    submitBtn.text('تحديث');
  });

  if (addNewAccountForm.length && String(addNewAccountForm.data('has-errors')) === '1') {
    var offcanvasEl = document.getElementById('offcanvasAddAccount');
    if (offcanvasEl && typeof bootstrap !== 'undefined') {
      bootstrap.Offcanvas.getOrCreateInstance(offcanvasEl).show();
    }
  } else {
    setFormCreateMode();
  }

  $('.datatables-accounts tbody').on('click', '.delete-record', function () {
    var row = $(this).parents('tr');
    var deleteUrl = $(this).data('url');
    var csrfToken = $('meta[name="csrf-token"]').attr('content');

    var performDelete = function () {
      $.ajax({
        type: 'DELETE',
        url: deleteUrl,
        headers: { 'X-CSRF-TOKEN': csrfToken },
        success: function (response) {
          if (dt_account) {
            dt_account.row(row).remove().draw();
          }

          if (typeof Swal !== 'undefined') {
            Swal.fire({
              icon: 'success',
              text: response.message || 'تمت العملية بنجاح.',
              customClass: { confirmButton: 'btn btn-success waves-effect waves-light' },
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
              customClass: { confirmButton: 'btn btn-danger waves-effect waves-light' },
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
        text: 'هل انت متأكد من حذف هذا الحساب',
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

    if (window.confirm('هل انت متأكد من حذف هذا الحساب')) {
      performDelete();
    }
  });

  setTimeout(function () {
    $('.dataTables_filter .form-control').removeClass('form-control-sm');
    $('.dataTables_length .form-select').removeClass('form-select-sm');
  }, 300);
});

(function () {
  const phoneMaskList = document.querySelectorAll('.phone-mask'),
    addNewAccountForm = document.getElementById('addNewAccountForm');

  if (phoneMaskList) {
    phoneMaskList.forEach(function (phoneMask) {
      new Cleave(phoneMask, {
        phone: true,
        phoneRegionCode: 'US'
      });
    });
  }
  if (!addNewAccountForm) return;

  FormValidation.formValidation(addNewAccountForm, {
    fields: {
      name: { validators: { notEmpty: { message: 'Please enter account name' } } },
      category: { validators: { notEmpty: { message: 'Please select category' } } },
      currency: { validators: { notEmpty: { message: 'Please select currency' } } },
      bank: { validators: { notEmpty: { message: 'Please enter bank name' } } },
      phone: { validators: { notEmpty: { message: 'Please enter phone number' } } }
    },
    plugins: {
      trigger: new FormValidation.plugins.Trigger(),
      bootstrap5: new FormValidation.plugins.Bootstrap5({
        eleValidClass: '',
        rowSelector: function () {
          return '.mb-6';
        }
      }),
      submitButton: new FormValidation.plugins.SubmitButton(),
      defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
      autoFocus: new FormValidation.plugins.AutoFocus()
    }
  });
})();
