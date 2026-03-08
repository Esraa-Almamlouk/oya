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
  var isAllTransactionsPage = $('.transaction_account').length > 0;
  var isAccountTransactionsPage = !isAllTransactionsPage;

  function formatNumber(value) {
    return Number(value || 0).toLocaleString('en-US', {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2
    });
  }

  function parseNumber(value) {
    var text = String(value == null ? '' : value);
    var cleaned = text.replace(/[^0-9.-]/g, '');
    var parsed = parseFloat(cleaned);
    return Number.isFinite(parsed) ? parsed : 0;
  }

  function setDefaultDateToToday() {
    if (!transactionDate.length || transactionDate.val()) return;

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

  function addColumnFilter(containerSelector, column, placeholder, options) {
    var container = $(containerSelector);
    if (!container.length) return;

    var select = $('<select class="form-select text-capitalize"><option value=""></option></select>');
    select.find('option').first().text(placeholder);

    options.forEach(function (option) {
      var optionConfig = typeof option === 'string' ? { value: option, label: option, search: option } : option;
      var optionEl = $('<option></option>')
        .val(optionConfig.value)
        .text(optionConfig.label || optionConfig.value)
        .attr('data-search', optionConfig.search || optionConfig.value);
      select.append(optionEl);
    });

    select.appendTo(container).on('change', function () {
      var selected = $(this).find('option:selected');
      var val = (selected.data('search') || '').toString().trim();
      column.search(val, false, false).draw();
    });
  }

  setDefaultDateToToday();
  calculateAmount();

  transactionValue.on('input', calculateAmount);
  transactionRate.on('input', calculateAmount);

  if (dtTable.length) {
    var showAddButton = String(dtTable.data('show-add-button')) !== '0';
    var exportTitle = 'Transactions Report';
    var exportDate = new Date().toLocaleString();
    var accountPdfUrl = dtTable.data('pdf-url') || '';
    var headerCount = dtTable.find('thead th').length;
    var exportColumns = [];
    for (var colIdx = 2; colIdx < headerCount - 1; colIdx++) {
      exportColumns.push(colIdx);
    }

    var buttons = [];

    if (isAccountTransactionsPage) {
      buttons.push({
        extend: 'collection',
        className: 'btn btn-label-secondary dropdown-toggle mx-4 waves-effect waves-light',
        text: '<i class="ti ti-upload me-2 ti-xs"></i>مشاركة',
        buttons: [
          {
            extend: 'excelHtml5',
            text: '<i class="ti ti-file-spreadsheet me-2"></i>Excel',
            className: 'dropdown-item',
            title: exportTitle,
            filename: 'transactions-report',
            sheetName: 'Transactions',
            exportOptions: { columns: exportColumns }
          },
          {
            extend: 'pdfHtml5',
            text: '<i class="ti ti-file-code-2 me-2"></i>Pdf',
            className: 'dropdown-item',
            title: '',
            filename: 'transactions-report',
            orientation: 'landscape',
            pageSize: 'A4',
            exportOptions: { columns: exportColumns },
            action: function (e, dt, button, config) {
              if (isAccountTransactionsPage && accountPdfUrl) {
                window.open(accountPdfUrl, '_blank');
                return;
              }

              $.fn.dataTable.ext.buttons.pdfHtml5.action.call(this, e, dt, button, config);
            },
            customize: function (doc) {
              var tableNode = doc.content.find(function (item) {
                return item.table;
              });
              var generatedAt = new Date().toLocaleString();
              var accountTitle = $('.card-title').first().text().trim();
              var accountName = accountTitle.replace(/^معاملات\s*/, '').trim();

              doc.pageMargins = [24, 62, 24, 28];
              doc.defaultStyle.fontSize = 9;
              doc.defaultStyle.alignment = 'right';
              doc.styles.tableHeader = {
                fillColor: '#f3f4f6',
                color: '#111827',
                fontSize: 10,
                bold: true,
                alignment: 'center'
              };

              doc.header = function () {
                return {
                  margin: [24, 18, 24, 0],
                  text: ''
                };
              };

              doc.footer = function (currentPage, pageCount) {
                return {
                  margin: [24, 0, 24, 12],
                  columns: [
                    { text: 'Page ' + currentPage + '/' + pageCount, alignment: 'left', fontSize: 8, color: '#6b7280' },
                    { text: generatedAt, alignment: 'right', fontSize: 8, color: '#6b7280' }
                  ]
                };
              };

              if (tableNode) {
                if (isAccountTransactionsPage) {
                  var originalBody = tableNode.table.body || [];
                  var dataRows = originalBody.slice(1);
                  var totalCredit = 0;
                  var totalDebit = 0;
                  var lastBalance = 0;

                  var statementBody = [
                    [
                      { text: 'التفاصيل', style: 'tableHeader' },
                      { text: 'له', style: 'tableHeader' },
                      { text: 'عليه', style: 'tableHeader' },
                      { text: 'الرصيد', style: 'tableHeader' },
                      { text: 'تاريخ', style: 'tableHeader' }
                    ]
                  ];

                  dataRows.forEach(function (row) {
                    var dateText = String(row[1] || '');
                    var detailsText = String(row[2] || '');
                    var amountValue = parseNumber(row[3]);
                    var typeText = String(row[4] || '');
                    var balanceValue = parseNumber(row[5]);
                    var isCredit = typeText.indexOf('▲') !== -1 || typeText.indexOf('إيداع') !== -1;

                    if (isCredit) {
                      totalCredit += amountValue;
                    } else {
                      totalDebit += amountValue;
                    }
                    lastBalance = balanceValue;

                    statementBody.push([
                      { text: detailsText, alignment: 'right' },
                      {
                        text: isCredit ? formatNumber(amountValue) : '',
                        alignment: 'center',
                        color: '#198754',
                        bold: isCredit
                      },
                      {
                        text: !isCredit ? formatNumber(amountValue) : '',
                        alignment: 'center',
                        color: '#dc3545',
                        bold: !isCredit
                      },
                      { text: formatNumber(balanceValue), alignment: 'center' },
                      { text: dateText, alignment: 'center' }
                    ]);
                  });

                  statementBody.push([
                    { text: '', border: [true, true, true, true] },
                    { text: formatNumber(totalCredit), alignment: 'center', color: '#198754', bold: true },
                    { text: formatNumber(totalDebit), alignment: 'center', color: '#dc3545', bold: true },
                    { text: 'إجمالي العمليات', colSpan: 2, alignment: 'right', color: '#198754', bold: true },
                    {}
                  ]);

                  statementBody.push([
                    { text: '', border: [true, true, true, true] },
                    { text: '', border: [true, true, true, true] },
                    { text: '', border: [true, true, true, true] },
                    { text: formatNumber(lastBalance), alignment: 'center', color: '#198754', bold: true },
                    { text: 'إجمالي الرصيد', alignment: 'right', color: '#198754', bold: true }
                  ]);

                  doc.content = [
                    {
                      columns: [
                        {
                          width: '45%',
                          stack: [
                            { text: 'Name: OYA', alignment: 'left' },
                            { text: 'Address: Tripoli - aldahra', alignment: 'left' },
                            { text: 'Phone number: 0921000897', alignment: 'left' }
                          ]
                        },
                        {
                          width: '10%',
                          text: 'OYA',
                          alignment: 'center',
                          bold: true,
                          margin: [0, 18, 0, 0]
                        },
                        {
                          width: '45%',
                          stack: [
                            { text: 'الاسم: اويا للصرافة والحوالات المالية', alignment: 'right' },
                            { text: 'العنوان: طرابلس - الظهرة', alignment: 'right' },
                            { text: 'رقم الهاتف: 0921000897', alignment: 'right' }
                          ]
                        }
                      ],
                      margin: [0, 0, 0, 10]
                    },
                    {
                      text: 'كشف حساب ' + (accountName || ''),
                      alignment: 'center',
                      bold: true,
                      color: '#ffffff',
                      fillColor: '#dc3545',
                      margin: [0, 0, 0, 10]
                    },
                    {
                      table: {
                        headerRows: 1,
                        widths: ['*', 60, 60, 80, 95],
                        body: statementBody
                      },
                      layout: {
                        fillColor: function (rowIndex) {
                          if (rowIndex === 0) return '#e6f2ff';
                          return null;
                        },
                        hLineColor: function () {
                          return '#a8b0b8';
                        },
                        vLineColor: function () {
                          return '#a8b0b8';
                        },
                        hLineWidth: function () {
                          return 0.8;
                        },
                        vLineWidth: function () {
                          return 0.8;
                        },
                        paddingLeft: function () {
                          return 5;
                        },
                        paddingRight: function () {
                          return 5;
                        },
                        paddingTop: function () {
                          return 3;
                        },
                        paddingBottom: function () {
                          return 3;
                        }
                      }
                    }
                  ];
                  return;
                }

                tableNode.layout = {
                  hLineColor: function () {
                    return '#e5e7eb';
                  },
                  vLineColor: function () {
                    return '#e5e7eb';
                  },
                  hLineWidth: function () {
                    return 0.8;
                  },
                  vLineWidth: function () {
                    return 0.8;
                  },
                  paddingLeft: function () {
                    return 6;
                  },
                  paddingRight: function () {
                    return 6;
                  },
                  paddingTop: function () {
                    return 4;
                  },
                  paddingBottom: function () {
                    return 4;
                  }
                };
                tableNode.table.widths = Array(tableNode.table.body[0].length).fill('*');
              }
            }
          }
        ]
      });
    }

    if (showAddButton) {
      buttons.push({
        text: '<i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span class="d-none d-sm-inline-block">إضافة معاملة</span>',
        className: 'btn btn-primary waves-effect waves-light',
        attr: {
          'data-bs-toggle': 'modal',
          'data-bs-target': '#addTransactionModal'
        }
      });
    }

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
      order: [[3, 'desc']],
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
      buttons: buttons,
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
        var api = this.api();
        addColumnFilter('.transaction_type', api.column(6), 'نوع المعاملة', [
          { value: 'credit', label: '▲ إيداع', search: '▲' },
          { value: 'debit', label: '▼ سحب', search: '▼' }
        ]);

        var accountOptions = $('.transaction_account').data('options') || [];
        if (Array.isArray(accountOptions) && accountOptions.length) {
          addColumnFilter('.transaction_account', api.column(4), 'الحساب', accountOptions);
        }

        var currencyOptions = $('.transaction_currency').data('options') || [];
        if (Array.isArray(currencyOptions) && currencyOptions.length) {
          addColumnFilter('.transaction_currency', api.column(9), 'العملة', currencyOptions);
        }
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
    var fields = {
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
            message: 'القيمة مطلوبة'
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
            message: 'سعر الصرف مطلوب'
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
            message: 'وصف المعاملة مطلوب'
          }
        }
      }
    };

    if ($('#transactionAccount').length) {
      fields.account_id = {
        validators: {
          notEmpty: {
            message: 'اختر الحساب'
          }
        }
      };
    }

    FormValidation.formValidation(addForm[0], {
      fields: fields,
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
