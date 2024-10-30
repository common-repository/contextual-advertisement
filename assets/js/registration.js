(function (win, $, reCaptchaToken, RequestFactory) {
    var registrationData = {
        token: null
    };

    var form = document.getElementById('form');

    /**
     * @return {string}
     */
    function getCategoriesForApi() {
        var targetsEl = $('#targets');
        var optionsToBeSent = targetsEl.find('.option');
        var categoriesToBeSent = optionsToBeSent.toArray().map(function (item) {
            return $(item).data('option');
        });
        return categoriesToBeSent.join();

    }

    // Notices library - show
    var notices = (function () {
        return {
            showSuccess: function () {
                $('.notice-success').show();
            },
            hideSuccess: function () {
                $('.notice-success').hide();
            },
            showError: function () {
                $('.notice-error').show();
            },
            hideError: function () {
                $('.notice-error').hide();
            },
            hideAll: function () {
                $('.context360-hiddenByDefault').hide();
            }
        }
    })();

    notices.hideAll();

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        grecaptcha.ready(function () {
            grecaptcha.execute(reCaptchaToken, {action: 'login'}).then(function (token) {
                registrationData.token = token;

                var request = RequestFactory();

                registrationData.categories = getCategoriesForApi();
                registrationData.host = location.protocol + '//' + location.hostname;
                registrationData.email = document.getElementById('email').value;
                // required by wordpress
                registrationData.action = 'context360_register';

                request.onSuccess(function (status, text) {
                    var responseObject = JSON.parse(text);
                    if (responseObject.success) {
                        notices.hideError();
                        notices.showSuccess();
                    } else {
                        notices.showError(responseObject.message);
                        notices.hideSuccess();
                    }
                });

                request.onError(function () {
                    notices.showError('Pojawił się nieoczekiwany błąd rejestracji');
                    notices.hideSuccess();
                });

                request.post(ajaxurl, {}, registrationData);
            });
        });
    });


    var options = $('#sources').find('.option:not(.disabled)');

    function createNewOption(label, value) {
        var optionEl = document.createElement('div');
        optionEl.dataset.option = value;
        optionEl.className = 'option';
        optionEl.appendChild(document.createTextNode(label));
        return optionEl;
    }

    options.on('click', function (e) {
        var value = $(e.target).data('option');
        var label = $(e.target).text();

        var targetsEl = $('#targets');

        if (targetsEl.find('.option[data-option=' + value + ']').length > 0) {
            return; // break, it has value already
        }

        var option = createNewOption(label, value);
        $(option).on('click', function () {
            $(option).remove();
        });
        targetsEl.append(option);
    });

})(window, jQuery, reCaptchaToken, Context360RequestFactory);


