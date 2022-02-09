
$(document).on("click", '.view_note', function () {
    var note_id = $(this).data('note');
    $('#loadData').load(base_url + 'admin/notes/view/' + note_id);
    $('#myModalLabel').text("User Note");
});
function is_image(img = false) {
    var fileExtension = ['jpeg', 'jpg', 'gif', 'png', 'flv', 'bmp', 'tiff', 'svg+xml'];
    if ($.inArray($.trim(img.split('.').pop().toLowerCase()), fileExtension) == -1) {
        return false;
    }
    return true;
}


$('.add_payment_method').click(function () {
    var user_id = $('.add_payment_method').data('user_id');
    $('#loadData').load(base_url + 'admin/users/add_payment_method/?user_id=' + user_id);
});




function notify_popup(text, time = '3000') {
    if (text == '') {
        return false;
    }
    $('.pop_alert').fadeIn(1000);
    $('.pop_alert').addClass('pop_show').text(text);

    setTimeout(function () {
        $('.pop_alert').removeClass('pop_show').css('display', 'none');
    }, time);


}



$('.decimal').keyup(function () {
    var val = $(this).val();
    if (isNaN(val)) {
        val = val.replace(/[^0-9\.]/g, '');
        if (val.split('.').length > 2)
            val = val.replace(/\.+$/, "");
    }
    $(this).val(val);
});


function validate_edit(el, output) {
    $("." + output).html("<i class='fa fa-spinner fa-pulse fa-fw'></i> Please wait...");
    var data_val = $('#' + el).val();
    var comp_id = $('#comp_id').val();

    if (el == 'company_name') {
        _type = 'name';
    }
    if (el == 'company_phone') {
        _type = 'phone';
    }
    if (el == 'company_vat') {
        _type = 'vat';
    }
    if (el == 'company_website') {
        _type = 'website';
    }
    if (el == 'company_email') {
        _type = 'email';
    }
    if (!_type || _type == '') {
        return false;
    }
    data = 'value=' + data_val;
    URL = base_url + 'admin/company/validate/' + _type + '/' + comp_id;
    $.ajax({
        type: "GET",
        url: URL,
        data: data,
        success: function (html) {
            $('.msg').show(100);
            $("." + output).html(html);
        },
        failure: function (html) {

        }
    });
};

function add_comp_city() {

    var country = $('#signup_country').val();
    var state = $('#signup_state').val();
    // console.log(country+' '+state);
    if (country == 0 || state == 0 || country == null || state == null) {
        return false;
    }
    var add_city = "<a href='" + base_url + "admin/country?country=" + country + "&state=" + state + "' target='_blank'>Add city</a>";

    $('.add_city').show(100);
    $('.add_city').html(add_city);
};



//user infinite load and onkeyup search


function show_loader() {
    $('.feed_loader').removeClass('hidden');
    $('.last_msg').addClass('hidden');
    $('.load_more_btn').hide();
}
function hide_loader() {
    $('.feed_loader').addClass('hidden');

}



//user mass delete

$('.del_select').click(function () {
    var allVals = [];
    $('.sel_del:checked').each(function () {
        allVals.push($(this).val());
    });
    var allVals_count = allVals.length;
    if (allVals_count < 1) {
        alert('Please select any post to delete.');
        return false;
    }
    var delete_post = confirm('You are about to delete ' + allVals_count + ' Users. Are you sure you want to delete ?');
    if (delete_post) {
        $.ajax({
            type: "POST",
            url: base_url,
            data: { "user_ids": allVals },
            success: function (data) {
                alert(allVals_count + " Users deleted successfully.");
                var loc = window.location;
                window.open(loc, "_self");
            },
            failure: function (data) {
                return false;
            }
        });
    }
});
$('.toggle_confirm').click(function () {
    if ($(this).attr('href')) {
        if ($(this).data('check-status') == 'on') {
            return confirm('Are you sure you want to suspend this user?');
        } else {
            return confirm('Are you sure you want to active this user?');
        }

    }
});



/*$('.edit_admin').click(function () {
    var admin_id = $(this).data('cat');
    var url = base_url + "admin/edit/";
    $('#loadData').load(url + admin_id);
    $('#myModalLabel').text("Edit Admin");
});*/


$(document).on("change", ".is_parent", function () {
    $this = $(this);
    $(".add_html").addClass("hidden");
    $(".space_div").addClass("hidden");

    if ($.trim($this.val()) == "1") {
        $html = $(".find_html").html();
        $("#level_text").text("Sub Category *");
        $(".add_html").removeClass("hidden");
        $(".space_div").removeClass("hidden");
        $(".add_html").html($html);
    } else {
        $("#level_text").text("Category *");
        $(".add_html").html(" ");
    }

    // console.log($this.val());
});

/**
 * 
 * Call this function on change of element
 * data will be loaded in element id passed in argument 
 * @argument source_val is source_element_id fromw which value has to be picked
 * @argument target_el is target element id
 * 
 * @exception notify_popup upon failure
 *  */
async function load_subcategory(source_val, target_el) {
    if (!source_val || !target_el) {
        console.warn('Invalid argument passed');
        return false;
    }
    var selected_value = $('#' + source_val).val();

    if (selected_value == '') {
        notify_popup('Please select value again');
        return false;
    }

    $.ajax({
        type: "get",
        url: base_url + 'service-provider/fetch_subcategory/' + selected_value,
        success: function (response) {
            $('#' + target_el).html(response);

        }
    });


}



$(document).on('click', '.select_uploader', function () {
    var target_input = $(this).data('target-input');
    $('#' + target_input).click();
});

$(document).ready(function () {
    fetch_stats();
});

function fetch_stats() {
    $.ajax({
        type: "get",
        url: base_url + "dashboard/load_stats",
        success: function (response) {
            response = JSON.parse(response);
            $('.show_count.inventory').text(response.inventory);
            $('.show_count.invoices').text(response.invoices);
            $('.show_count.rma').text(response.rma);
            $('.show_count.supplier').text(response.supplier);
            $('.show_count.category').text(response.category);
            $('.show_count.sales').text(response.sales);
            $('.show_count.company').text(response.companies);

        }
    });
}

$('.toggle_filter').click(function () {
    $('.filter-form, .show_filter, .hide_filter').toggle();
});

function highlight(text) {
    var inputText = $('.table');
    var innerHTML = inputText.innerHTML;
    var index = innerHTML.indexOf(text);
    if (index >= 0) {
        innerHTML = innerHTML.substring(0, index) + "<span class='highlight'>" + innerHTML.substring(index, index + text.length) + "</span>" + innerHTML.substring(index + text.length);
        inputText.innerHTML = innerHTML;
    }
}

