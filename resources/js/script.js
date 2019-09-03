// require('./toastr');

$(document).ready(function () {
    $('#countdown').timeTo({
        timeTo: new Date(new Date('Dec 31 2019 23:59:59')),
        theme: "black",
        displayCaptions: true,
        fontSize: 48,
        captionSize: 14,
        seconds: 0,
        lang: 'ru'
    });
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(".toggle-password").click(function () {

        $(this).toggleClass("close_eye");
        var selector = $($(this).attr("toggle"));
        var input = $(this).closest('.form-group').find(selector).first();
        if (input.attr("type") == "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }
    });
    $('#clip').on('click', function (e) {
        e.preventDefault();
        $(this).closest('form').find('#uploadimage').click();
    });
    $('.submit-auth').on('click', function () {
        var form = $(this).closest('.modal-content').find('form').first();
        form.find('.is-invalid').removeClass('is-invalid');
        var data = {
            email: form.find('input[name="email"]').first().val(),
            password: form.find('input[name="password"]').first().val(),
        };
        $.ajax({
            url: form.attr('action'), //url страницы (action_ajax_form.php)
            type: "POST", //метод отправки
            dataType: "json", //формат данных
            data: data,  // Сеарилизуем объект
            success: function (response) { //Данные отправлены успешно
                toastr.success(response["message"]);
                $('#AuthModal').modal('hide');
                location.reload();
            },
            error: function (response) { // Данные не отправлены
                setFormErrors(form, response["responseJSON"]["errors"]);
                // toastr.error(setErrors(response["responseJSON"]["errors"]));
            }
        });
    });
    $('#ThanksModal').on('hidden.bs.modal', function (e) {
        location.reload();
    });

    $('#changeFormToRegister').on('click', function () {
        $('#AuthModal').modal('hide');
        $('#RegisterModal').modal('show');
    });
    $('#changeFormVoteToRegister').on('click', function () {
        $('#VoteModal').modal('hide');
        $('#RegisterModal').modal('show');
    });
    $('#changeFormVoteToAuth').on('click', function () {
        $('#VoteModal').modal('hide');
        $('#AuthModal').modal('show');
    });
    $('#changeFormProfileToPassword').on('click', function () {
        $('#ProfileModal').modal('hide');
        $('#SwitchPassword').modal('show');
    });
    $('#changeFormToReset').on('click', function (e) {
        e.preventDefault();
        $('#AuthModal').modal('hide');
        $('#ResetModal').modal('show');
    });
    $('.open-hide-modal').on('click', function (e) {
        e.preventDefault();
        $($(this).attr('data-hide')).modal('hide');
        $($(this).attr('data-open')).modal('show');
    });
    $('.submit-job').on('click', function () {
        var $input = $("#uploadimage");
        var fd = new FormData;
        var form = $(this).closest('.modal').find('form.job').first();
        form.find('.is-invalid').removeClass('is-invalid');
        fd.append('img', $input.prop('files')[0]);
        fd.append('title', form.find('input[name="title"]').first().val());
        fd.append('privacy', form.find('input[name="privacy"]').first().is(':checked'));
        fd.append('description', form.find('textarea[name="description"]').first().val());
        $.ajax({
            url: 'job', //url страницы (action_ajax_form.php)
            type: "POST", //метод отправки
            data: fd,  // Сеарилизуем объект
            processData: false,
            contentType: false,
            success: function (response) { //Данные отправлены успешно
                $('#JobModal').modal('hide');
                $('#ThanksModal').modal('show');
            },
            error: function (response) { // Данные не отправлены
                setFormErrors(form, response["responseJSON"]["errors"]);
                // toastr.error(setErrors(response["responseJSON"]["errors"]));
            }
        });
    });
    $('.reset-submit').on('click', function () {
        var fd = new FormData;
        var form = $(this).closest('.modal').find('form.reset').first();
        form.find('.is-invalid').removeClass('is-invalid');
        fd.append('email', form.find('input[name="email"]').first().val());
        $.ajax({
            url: 'reset', //url страницы (action_ajax_form.php)
            type: "POST", //метод отправки
            data: fd,  // Сеарилизуем объект
            processData: false,
            contentType: false,
            success: function (response) { //Данные отправлены успешно
                $('#ResetModal').modal('hide');
                $('#SuccessResetModal').modal('show');
            },
            error: function (response) { // Данные не отправлены
                setFormErrors(form, response["responseJSON"]["errors"]);
                // toastr.error(setErrors(response["responseJSON"]["errors"]));
            }
        });
    });

    $('.switch-submit').on('click', function () {
        var fd = new FormData;
        var form = $(this).closest('.modal').find('form.reset').first();
        form.find('.is-invalid').removeClass('is-invalid');
        fd.append('password', form.find('input[name="password"]').first().val());
        fd.append('confirm_password', form.find('input[name="confirm_password"]').first().val());
        $.ajax({
            url: 'switch_password', //url страницы (action_ajax_form.php)
            type: "POST", //метод отправки
            data: fd,  // Сеарилизуем объект
            processData: false,
            contentType: false,
            success: function (response) { //Данные отправлены успешно
                toastr.success(response["message"]);
                $('#SwitchPassword').modal('hide');
                $('#ProfileModal').modal('show');
            },
            error: function (response) { // Данные не отправлены
                setFormErrors(form, response["responseJSON"]["errors"]);
                // toastr.error(setErrors(response["responseJSON"]["errors"]));
            }
        });
    });
    $('.reset-by-email-submit').on('click', function () {
        var fd = new FormData;
        var form = $(this).closest('.modal').find('form.reset-by-email').first();
        form.find('.is-invalid').removeClass('is-invalid');
        fd.append('password', form.find('input[name="password"]').first().val());
        fd.append('confirm_password', form.find('input[name="confirm_password"]').first().val());
        fd.append('token', form.find('input[name="token"]').first().val());
        fd.append('email', form.find('input[name="email"]').first().val());
        $.ajax({
            url: 'reset_by_email', //url страницы (action_ajax_form.php)
            type: "POST", //метод отправки
            data: fd,  // Сеарилизуем объект
            processData: false,
            contentType: false,
            success: function (response) { //Данные отправлены успешно
                toastr.success(response["message"]);
                $('#ResetByEmailModal').modal('hide');
                $('#SuccessResetFinalModal').modal('show').on('hidden.bs.modal', function (e) {
                    location.reload();
                });
            },
            error: function (response) { // Данные не отправлены
                setFormErrors(form, response["responseJSON"]["errors"]);
                // toastr.error(setErrors(response["responseJSON"]["errors"]));
            }
        });
    });
    $('.submit-profile').on('click', function () {
        var fd = new FormData;
        var form = $(this).closest('.modal').find('form.profile').first();
        form.find('.is-invalid').removeClass('is-invalid');
        fd.append('first_name', form.find('input[name="first_name"]').first().val());
        fd.append('second_name', form.find('input[name="second_name"]').first().val());
        fd.append('email', form.find('input[name="email"]').first().val());
        fd.append('newsletter', form.find('input[name="newsletter"]').first().is(':checked'));
        fd.append('gender', form.find('input[name="gender"]:checked').val());
        fd.append('year', form.find('input[name="year"]').first().val());
        fd.append('month', form.find('input[name="month"]').first().val());
        fd.append('day', form.find('input[name="day"]').first().val());
        fd.append('_method', 'patch');
        $.ajax({
            url: 'profile', //url страницы (action_ajax_form.php)
            type: "POST", //метод отправки
            data: fd,  // Сеарилизуем объект
            processData: false,
            contentType: false,
            success: function (response) { //Данные отправлены успешно
                toastr.success(response["message"]);
                $('#ProfileModal').modal('hide');
            },
            error: function (response) { // Данные не отправлены
                setFormErrors(form, response["responseJSON"]["errors"]);
                // toastr.error(setErrors(response["responseJSON"]["errors"]));
            }
        });
    });
    $('.submit-register').on('click', function () {
        var fd = new FormData;
        var form = $(this).closest('.modal').find('form.register').first();
        form.find('.is-invalid').removeClass('is-invalid');
        fd.append('first_name', form.find('input[name="first_name"]').first().val());
        fd.append('second_name', form.find('input[name="second_name"]').first().val());
        fd.append('email', form.find('input[name="email"]').first().val());
        fd.append('password', form.find('input[name="password"]').first().val());
        fd.append('newsletter', form.find('input[name="newsletter"]').first().is(':checked'));

        fd.append('gender', form.find('input[name="gender"]:checked').val());

        fd.append('year', form.find('input[name="year"]').first().val());
        fd.append('month', form.find('input[name="month"]').first().val());
        fd.append('day', form.find('input[name="day"]').first().val());
        $.ajax({
            url: 'register', //url страницы (action_ajax_form.php)
            type: "POST", //метод отправки
            data: fd,  // Сеарилизуем объект
            processData: false,
            contentType: false,
            success: function (response) { //Данные отправлены успешно
                toastr.success(response["message"]);
                $('#RegisterModal').modal('hide');
                location.reload();
            },
            error: function (response) { // Данные не отправлены
                setFormErrors(form, response["responseJSON"]["errors"]);
                // toastr.error(setErrors(response["responseJSON"]["errors"]));
            }
        });
    });
    $('.submit-admin-job').on('click', function (e) {
        e.preventDefault();
        var fd = new FormData;
        var form = $(this).closest('form.admin-job');
        var $input = $("#uploadimage");
        if (typeof $input.prop('files')[0] != "undefined") {
            fd.append('img', $input.prop('files')[0]);
        }
        fd.append('id', form.find('input[name="id"]').first().val());
        fd.append('title', form.find('input[name="title"]').first().val());
        fd.append('created_at', form.find('input[name="created_at"]').first().val());
        fd.append('description', form.find('textarea[name="description"]').first().val());
        fd.append('status', form.find('select[name="status"]').first().val());
        fd.append('comment', form.find('textarea[name="comment"]').first().val());
        fd.append('type', 'admin');
        fd.append('_method', 'patch');
        $.ajax({
            url: '/admin/job', //url страницы (action_ajax_form.php)
            type: "POST", //метод отправки
            data: fd,  // Сеарилизуем объект
            processData: false,
            contentType: false,
            success: function (response) { //Данные отправлены успешно
                toastr.success(response["message"]);
                location.reload();
            },
            error: function (response) { // Данные не отправлены
                toastr.error(setErrors(response["responseJSON"]["errors"]));
            }
        });
    });
    $('.submit-edit-job').on('click', function (e) {
        e.preventDefault();
        var fd = new FormData;
        var form = $(this).closest('form.edit-job');
        form.find('.is-invalid').removeClass('is-invalid');
        var $input = $("#uploadimage");
        if (typeof $input.prop('files')[0] != "undefined") {
            fd.append('img', $input.prop('files')[0]);
        }
        fd.append('title', form.find('input[name="title"]').first().val());
        fd.append('description', form.find('textarea[name="description"]').first().val());
        fd.append('privacy', form.find('input[name="privacy"]').first().is(':checked'));
        fd.append('_method', 'patch');
        fd.append('type', 'edit');
        $.ajax({
            url: '/job', //url страницы (action_ajax_form.php)
            type: "POST", //метод отправки
            data: fd,  // Сеарилизуем объект
            processData: false,
            contentType: false,
            success: function (response) { //Данные отправлены успешно
                toastr.success(response["message"]);
                location.reload();
            },
            error: function (response) { // Данные не отправлены
                setFormErrors(form, response["responseJSON"]["errors"]);
                // toastr.error(setErrors(response["responseJSON"]["errors"]));
            }
        });
    });
    $('#logout').on('click', function (e) {
        e.preventDefault();
        $.ajax({
            url: '/logout', //url страницы (action_ajax_form.php)
            type: "POST", //метод отправки
            processData: false,
            contentType: false,
            success: function (response) { //Данные отправлены успешно
                toastr.success(response["message"]);
                location.reload();
            },
            error: function (response) { // Данные не отправлены
                toastr.error(setErrors(response["responseJSON"]["errors"]));
            }
        });
    });
    $('tr[data-href]').on("click", function () {
        document.location = $(this).data('href');
    });

    $('.like-job').on('click', function () {
        var obj = $(this);
        var job = obj.attr('job');
        var data = {
            job_id: job
        };
        $.ajax({
            url: 'like', //url страницы (action_ajax_form.php)
            type: "POST", //метод отправки
            data: data,  // Сеарилизуем объект
            success: function (response) { //Данные отправлены успешно
                obj.closest('.job-likes').find('.like-count').text(response.likes);
                obj.toggleClass('active');
            },
            error: function (response) { // Данные не отправлены
                toastr.error(setErrors(response["responseJSON"]["errors"]));
            }
        });
    });

    $('.to_register').on('click', function (e) {
        e.preventDefault();
        $('#auth').toggleClass('d-none');
        $('#register').toggleClass('d-none');
    });
    $('.share-button').on('click', function (k) {
        k.preventDefault();
        var obj = $(this);
        var job = obj.attr('data-job');
        var provider = obj.attr('data-provider');
        var data = {
            job_id: job,
            provider: provider
        };
        e = $(this).attr('data-url');
        var h = 500,
            w = 500;
        $.ajax({
            url: 'share', //url страницы (action_ajax_form.php)
            type: "POST", //метод отправки
            data: data,  // Сеарилизуем объект
        });
        window.open(e, '', 'scrollbars=1,height=' + Math.min(h, screen.availHeight) + ',width=' + Math.min(w, screen.availWidth) + ',left=' + Math.max(0, (screen.availWidth - w) / 2) + ',top=' + Math.max(0, (screen.availHeight - h) / 2));
    });
    $('.image-rotate').on('click', function (k) {
        k.preventDefault();
        var obj = $(this);
        var degree = obj.attr('data-degree');
        var job = obj.attr('data-job');
        var data = {
            job_id: job,
            degree: degree
        };
        $.ajax({
            url: '/rotate', //url страницы (action_ajax_form.php)
            type: "POST", //метод отправки
            data: data,  // Сеарилизуем объект
            success: function (response) { //Данные отправлены успешно
                toastr.success(response["message"]);
                $('.job-admin-image').attr('src', $('.job-admin-image').attr('src') + "?time=" + new Date());
            },
            error: function (response) { // Данные не отправлены
                toastr.error(setErrors(response["responseJSON"]["errors"]));
            }
        });
    });
    $('.sort-trigger').on('click', function (k) {
        k.preventDefault();
        var obj = $(this);
        var sort = obj.attr('data-sort');
        var page = obj.attr('data-page');
        if (obj.hasClass('active')) {
            $('.sort-trigger').removeClass('active');
        } else {
            $('.sort-trigger').removeClass('active');
            obj.toggleClass('active');
        }
        var data = {
            sort: sort,
            open_page: page,
            on: obj.hasClass('active')
        };
        $.ajax({
            url: 'jobs', //url страницы (action_ajax_form.php)
            type: "POST", //метод отправки
            data: data,  // Сеарилизуем объект
            success: function (response) { //Данные отправлены успешно
                $('.all-jobs-row').html(response);
                $('.like-job').on('click', function () {
                    var obj = $(this);
                    var job = obj.attr('job');
                    var data = {
                        job_id: job
                    };
                    $.ajax({
                        url: 'like', //url страницы (action_ajax_form.php)
                        type: "POST", //метод отправки
                        data: data,  // Сеарилизуем объект
                        success: function (response) { //Данные отправлены успешно
                            obj.closest('.job-likes').find('.like-count').text(response.likes);
                            obj.toggleClass('active');
                        },
                        error: function (response) { // Данные не отправлены
                            toastr.error(setErrors(response["responseJSON"]["errors"]));
                        }
                    });
                });
                $('.share-button').on('click', function (k) {
                    k.preventDefault();
                    var obj = $(this);
                    var job = obj.attr('data-job');
                    var provider = obj.attr('data-provider');
                    var data = {
                        job_id: job,
                        provider: provider
                    };
                    e = $(this).attr('data-url');
                    var h = 500,
                        w = 500;
                    $.ajax({
                        url: 'share', //url страницы (action_ajax_form.php)
                        type: "POST", //метод отправки
                        data: data,  // Сеарилизуем объект
                    });
                    window.open(e, '', 'scrollbars=1,height=' + Math.min(h, screen.availHeight) + ',width=' + Math.min(w, screen.availWidth) + ',left=' + Math.max(0, (screen.availWidth - w) / 2) + ',top=' + Math.max(0, (screen.availHeight - h) / 2));
                });
            },
        });
    });
    $('.load-more').on('click', function (k) {
        k.preventDefault();
        var obj = $(this);
        var page = parseInt(obj.attr('data-page'));
        page = page + 1;
        var data = {
            page: page,
        };
        $.ajax({
            url: 'load-more', //url страницы (action_ajax_form.php)
            type: "POST", //метод отправки
            data: data,  // Сеарилизуем объект
            success: function (response) { //Данные отправлены успешно
                if (response) {
                    $('.all-jobs-row').append(response);
                    $('.sort-trigger').attr('data-page', page);
                    obj.attr('data-page', page);
                } else {
                    obj.remove();
                }
            },
        });
    });

    function setErrors(errors) {
        message = '';
        var k = 1;
        $.each(errors, function (item, error) {
            message = message + (k) + ') ' + error + '<br/>';
            k++
        });
        return message;
    }

    $('.is-invalid').focus(function () {
        $(this).removeClass('is-invalid');
    });

    function setFormErrors(obj, errors) {
        $.each(errors, function (item) {
            var invalidObj = obj.find('[name="' + item + '"]').first();
            invalidObj.addClass('is-invalid');
            invalidObj.closest('div').find('.invalid-feedback').text(errors[item][0]);
        });
    }

    $(document).on("click", '[data-toggle="lightbox"]', function (event) {
        event.preventDefault();
        $(this).ekkoLightbox();
    });
    $('#datetimepicker1').datetimepicker({format: 'YYYY-MM-DD HH:mm'});
    // $('#ProfileModal input[name="day"]').inputmask("dd");  //static mask
    // $('#ProfileModal input[name="month"]').inputmask("mm");  //static mask
    // $('#ProfileModal input[name="year"]').inputmask("yyyy");  //static mask
})
;
