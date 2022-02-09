/* Preview selected image */

var host_name = window.location.hostname;

if (host_name == 'app.mobi-hub.com') {
    ENV = 'production';
} else {
    ENV = 'development';
}

$('#myModal').on('show.bs.modal', function (e) {
    $('#loadData').html('<h4 align="center"><i class="fa fa-spinner fa-pulse"></i></h4><h4 align="center">Please wait...</h4>');
})

$(document).on('keypress', '.txtOnly', function (e) {
    var key = e.keyCode;
    if (key >= 48 && key <= 57) {
        e.preventDefault();
    }
});

$(document).ready(function () {

    if (!are_cookies_enabled()) {
        notify_popup('Cookies are disabled or blocked by your browser, Website functionality is limited. Please enable cookies');
        $('.alert_message').html('<p class="text-danger"><i class="fa fa-warning fa-lg"></i> We could not detect cookies on your browser, Website will be working in limited functionality. Please enable cookies.</p>');
    }

});

function isNumberKey(evt) {
    var charCode = evt.which ? evt.which : evt.keyCode;
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) return false;
    return true;
}

$(document).on('keyup', '.decimal', function () {
    var val = $(this).val();
    if (isNaN(val)) {
        val = val.replace(/[^0-9\.]/g, '');
        if (val.split('.').length > 2) val = val.replace(/\.+$/, '');
    }
    $(this).val(val);
});

$(document).on('keypress', '.numeric', function (e) {
    var key = e.keyCode;
    if (key > 57 || key < 48) {
        $(this).addClass('is-invalid');
        alert('Please enter only numeric values 0-9 only');
        e.preventDefault();
    } else {
        $(this).removeClass('is-invalid');
    }

});

$(document).on('keyup', '.banned', function (e) {
    var text = $(this).val();
    for (var x = 0; x < banned.length; x++) {
        if (text.search(banned[x]) !== -1) {
            alert('Alert : Word ' + banned[x] + ' is not allowed or banned by Mobi-Hub!');
        }
        var regExp = new RegExp(banned[x]);
        text = text.replace(regExp, '');
    }
    $(this).val(text);
});

function readURL(input, preview) {

    if (input.files && input.files[0]) {
        Image_file = input.files[0];
        fileType = Image_file["type"];
        ValidImageTypes = ["image/gif", "image/jpeg", "image/png"];
        if ($.inArray(fileType, ValidImageTypes) < 0) {
            //alert('Selected file is not an image, please select valid image type');
            notify_popup('Error! Selected file is not an image, please select valid image type', 5000, 'danger');
            remove_image(input);
            $('#' + preview).attr('src', base_url + 'static/front/images/upload-image.png');


            $(input).val('');


            return false;
        }


        var reader = new FileReader();
        reader.onload = function (e) {

            $('#' + preview).attr('src', e.target.result);
            $('#' + preview).parent().removeClass("hidden");
            $('#' + preview).parent().addClass("visible");
            //divx.scrollTop      =    divx.scrollHeight;
        }
        reader.readAsDataURL(input.files[0]);
    }
}


//  open close full screen popup
function hide_div(data) {
    $(data).addClass('hidden');
}

function fs_popup(url) {
    if (url == '') {
        return false;
    }
    $('.fs-model').removeClass('hidden');
    $('#fs-model-content').html("<p class=\"text-center\" style=\"margin:100px auto;\"><i class=\"fa fa-spinner fa-pulse fa-3x fa-fw\"></i><br>Loading...</p>");
    $('#fs-model-content').load(url);
};

$(document).on("keyup", ".search_input", function () {
    target_area = $(this).data('search-from');
    var value = $(this).val().toLowerCase();
    $("." + target_area).filter(function () {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
});



function stop_ajax() {
    $.xhrPool = [];
    $.xhrPool.abortAll = function () {
        $(this).each(function (i, jqXHR) {   //  cycle through list of recorded connection
            jqXHR.abort();  //  aborts connection
            $.xhrPool.splice(i, 1); //  removes from list by index
        });
    }
    $.ajaxSetup({
        beforeSend: function (jqXHR) { $.xhrPool.push(jqXHR); }, //  annd connection to list
        complete: function (jqXHR) {
            var i = $.xhrPool.indexOf(jqXHR);   //  get index for current connection completed
            if (i > -1) $.xhrPool.splice(i, 1); //  removes from list by index
        }
    });
};

function zoom_image(image_class) {
    $('#img_preview').removeClass('hidden');
    img_src = $(image_class).attr('src');
    $('.chat_img_fs').attr('src', img_src);
}


$('#img_preview').click(function (e) {
    if (e.target.className !== 'chat_img_fs') {
        $('#img_preview').addClass('hidden');
    }
});


function isScrolledIntoView(el) {
    var elemTop = el.getBoundingClientRect().top;
    var elemBottom = el.getBoundingClientRect().bottom;
    var isVisible = elemTop >= 0 && elemBottom <= window.innerHeight;
    return isVisible;
}

function changeurl(url, title) {
    var new_url = '/' + url;
    window.history.pushState('data', 'Title', new_url);
    document.title = title;
}

/* Loading bar on top */
$('a').click(function () {
    ref_attrib = $(this).attr('href');
    if (ref_attrib == '' || ref_attrib == undefined || ref_attrib.charAt(0) == '#' || ref_attrib.charAt(0) == 'javascript:void') {

    } else {
        show_navigation_loader();
    }
});

//full screen loader
$('form').submit(function () {
    show_navigation_loader();
});

function show_navigation_loader() {
    $('.progress').css('opacity', '1');
    $('.progress div').addClass('loader');
    setTimeout(function () {
        $('.progress').css('opacity', '0');
    }, 10000);
}

$('nav.mobilemenu li').click(function () {
    $('.overlay-close').click();
});


function notify_popup(text, time = '3000', type = '') {
    $('.pop_alert').removeClass("pop_danger pop_success");
    if (text == '') {
        return false;
    }
    pop_class = 'pop_show';
    if (type == 'success') {
        pop_class = 'pop_show pop_success';
    }

    if (type == 'danger') {
        pop_class = 'pop_show pop_danger';
    }
    $('.pop_alert').fadeIn(1000);
    $('.pop_alert').addClass(pop_class).text(text);

    setTimeout(function () {
        $('.pop_alert').removeClass('pop_show').css('display', 'none');
    }, time);
}



$(document).ready(function () {
    $.getScript('//cdnjs.cloudflare.com/ajax/libs/jquery.lazy/1.7.6/jquery.lazy.min.js', function () {
        $.getScript('//cdnjs.cloudflare.com/ajax/libs/jquery.lazy/1.7.6/jquery.lazy.plugins.min.js', function () {
            $('.lazyload').Lazy().css({ 'min-height': 'auto' });
        });
    });
});


$(document).ready(function () {
    $('.fix').affix({ offset: { top: 50 } });
});
/*Image validation */
function is_image(img = false, fileExtension = false) {

    if (fileExtension == false) {
        var fileExtension = ['jpeg', 'jpg', 'gif', 'png', 'flv', 'bmp', 'tiff', 'svg+xml'];
    }

    if ($.inArray($.trim(img.split('.').pop().toLowerCase()), fileExtension) == -1) {
        return false;
    }
    return true;
}

$('.preview').click(function () {
    var src = $(this).attr('src');

    $('<div>').css({
        background: 'RGBA(0,0,0,.5) url(' + src + ') no-repeat center',
        backgroundSize: 'contain',
        width: '100%', height: '100%',
        position: 'fixed',
        zIndex: '10000',
        top: '0', left: '0',
        cursor: 'zoom-out'
    }).click(function () {
        $(this).remove();
    }).appendTo('body');
});


$(document).on("keyup blur", '.text_only', function () {
    // Allow controls such as backspace, tab etc.
    var node = $(this);
    node.val(node.val().replace(/[^A-Za-z_\s]/, ''));
    // var arr = [8,9,16,17,20,35,36,37,38,39,40,45,46];

});

$(document).on('click', '.confirmation', function () {
    context = $(this).data('action');
    if (!context || context == '') {
        context = 'proceed';
    };


    return confirm('Are you sure you want to ' + context);
});

$(document).on('submit', 'form', function () {
    loading_text = $('form button.loading').data('loading-text');


    if (!(loading_text) || loading_text == '') {
        loading_text = 'Please wait...';
    }

    $('form button.loading').html('<i class="fa fa-spinner fa-spin"></i> ' + loading_text);

});


/**
 * 
 * to display alert message on specific div
 * @param string msg Message to display on alert section
 * @param context type of alert/message, valid values are 
 * 
 * - success
 * - warning
 * - danger
 * - info
 * @param target_class where this message should appear, default value is ```error_msg```
 * 
 *  */
function display_msg(msg = 'Something important here', context = 'success', target_class = 'error_msg') {

    if (context == 'success') {
        icon = '<i class="fa fa-check-circle fa-fw"></i>';
    }

    if (context == 'warning') {
        icon = '<i class="fa fa-warning fa-fw"></i>';
    }

    if (context == 'danger') {
        icon = '<i class="fa fa-times-circle fa-fw"></i>';
    }

    if (context == 'info') {
        icon = '<i class="fa fa-info-circle fa-fw"></i>';
    }

    $('.' + target_class).html(icon + ' ' + msg).addClass('alert alert-' + context);
}

function imgError() {
    user_name = $(image).data('uname');
    img_src = "https://ui-avatars.com/api/?name=" + user_name + "&size=128&rounded=&color=007fbd";
    image.src = img_src;
    return true;
}

function PostimgError(image) {
  image.onerror = "";

  img_src = base_url + "static/front/images/placeholder/post_placeholder.jpg";
  image.src = img_src;
  return true;
}

function are_cookies_enabled() {
    var cookieEnabled = (navigator.cookieEnabled) ? true : false;

    if (typeof navigator.cookieEnabled == "undefined" && !cookieEnabled) {
        document.cookie = "mh_check_cookies";
        cookieEnabled = (document.cookie.indexOf("mh_check_cookies") != -1) ? true : false;
    }
    return (cookieEnabled);
}



function call_region(element_class) {

    var parent_region = $('.' + element_class).val();

    var output_select = $(this).data('output-select');

    var _type = $(this).data('region-request');

    URL = base_url + 'service-provider/region_data/' + _type + '/' + parent_region;

    $.ajax({
        type: "GET",
        url: URL,
        data: '',
        success: function (html) {
            $("." + output_select).html(html);
        },
        failure: function (html) {

        }
    });
}
function validate_field(el, output) {
    $("#" + output).html("<i class='fa fa-spinner fa-pulse fa-fw'></i> Please wait...");
    var data_val = $('#' + el).val();
    var user_id = "<?php echo $user_id;?>";
    if (el == 'signup_email') {
        _type = 'email';
    } else {
        _type = 'username';
    }
    if (el == 'user_phone') {
        _type = 'mobile';
    }
    if (el == 'company_name') {
        _type = 'company';
    }
    data = 'value=' + data_val + '&user=' + user_id;
    URL = base_url + 'service-provider/validate_field/' + _type + '/' + 'admin';
    $.ajax({
        type: "GET",
        url: URL,
        data: data,
        dataType: 'json',
        success: function (response) {
            if (response.error == 'danger') {

                $("#" + output).html("<span class='text-" + response.error + "' data-error='" + response.error + "'><i class='fa fa-times-circle fa-fw'></i>" + response.msg + "</small>");
                $('#request_company_id').val('');
            } if (response.error == 'warning') {

                $("#" + output).html("<span class='text-" + response.error + "' data-error='" + response.error + "'><i class='fa fa-warning fa-fw'></i>" + response.msg + "</small>");
                $('#request_company_id').val(response.company_id);
            } else if (response.error == 'success') {
                $('#request_company_id').val('');
                $("#" + output).html("<span class='text-success' data-error='" + response.error + "'><i class='fa fa-check-circle fa-fw'></i>" + response.msg + "</small>");

            }
            check_field_error();
        },
        failure: function (html) {
        }
    });
}


$('.coming-soon').click(function () {
    notify_popup('This feature not available at the moment, will be activated shortly');
});

$('.select_file').click(function () {
    target_input = $(this).data('target-input');
    $('#' + target_input).click();
});

function copy_to_clipboard(target_input) {




}


$(document).on('focus', '.image_url_input', function () {

    $(this).select();

});

function copyStringToClipboard(str) {
    // Create new element
    var el = document.createElement('textarea');
    // Set value (string to be copied)
    el.value = str;
    // Set non-editable to avoid focus and move outside of view
    el.setAttribute('readonly', '');
    el.style = { position: 'absolute', left: '-9999px' };
    document.body.appendChild(el);
    // Select text inside element
    el.select();
    // Copy text to clipboard
    document.execCommand('copy');
    // Remove temporary element
    document.body.removeChild(el);
}
function check_permission(el) {
    $.ajax({
        type: "POST",
        url: base_url + 'dashboard/permission_alert',
        data: $(this).serialize(),
        success: function (response) {

            let msg = "<div id='fading_divs' class='animated flash delay-3s alert alert-danger'><i class='fa fa-info-circle fa-lg'></i>";
            let msg2 = "<span id='msg' onclick='hide_alert(this)' class='pull-right ' style='cursor:pointer'><i class='fa fa-times'></i></span>";
            let msg3 = "</div>";
            $('#fading_div').hide();
            $('#p_alert_msg').html(msg + response + msg2 + msg3);

        }
    })
}
function hide_alert(el) {
    let click_id = "#" + el.id;
    $(click_id).parent().fadeOut('slow');
}