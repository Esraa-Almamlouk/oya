/**
 *  Pages Authentication
 */

'use strict';
const formAuthentication = document.querySelector('#formAuthentication');

document.addEventListener('DOMContentLoaded', function (e) {
    (function () {
        // Form validation for Add new record
        if (formAuthentication) {
            const fv = FormValidation.formValidation(formAuthentication, {
                fields: {
                email: {
                    validators: {
                    notEmpty: {
                        message: 'الرجاء ادخال البريد الالكتروني '
                    },
                    emailAddress: {
                        message: 'الرجاء ادخال بريد الكتروني صالح '
                    }
                    }
                },
                password: {
                    validators: {
                    notEmpty: {
                        message: 'الرجاء ادخال كلمة المرور '
                    },
                    stringLength: {
                        min: 6,
                        message: 'كلمة المرور يجب ان تتكون من 6 احرف على الاقل'
                    }
                    }
                }
                },
                plugins: {
                trigger: new FormValidation.plugins.Trigger(),
                bootstrap5: new FormValidation.plugins.Bootstrap5({
                    eleValidClass: '',
                    rowSelector: '.mb-6'
                }),
                submitButton: new FormValidation.plugins.SubmitButton(),

                defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
                autoFocus: new FormValidation.plugins.AutoFocus()
                },
                init: instance => {
                instance.on('plugins.message.placed', function (e) {
                    if (e.element.parentElement.classList.contains('input-group')) {
                    e.element.parentElement.insertAdjacentElement('afterend', e.messageElement);
                    }
                });
                }
            });
        }

        //  Two Steps Verification
        const numeralMask = document.querySelectorAll('.numeral-mask');

        // Verification masking
        if (numeralMask.length) {
            numeralMask.forEach(e => {
                new Cleave(e, {
                numeral: true
                });
            });
            }
        })();
});
