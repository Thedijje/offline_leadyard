
$(document).on("click",'.request_lang',function(){
    file_name     =   $(this).data('file_name');
    $('#loadData').load(base_url+'developers/language/request/'+file_name);
});


$(document).on("keyup","#search", function() {
    var value = $(this).val().toLowerCase();
    $(".searchable tr").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
});

$('.test_email').click(function(){
    fun 		=	$(this).data('key-fun');
    class_name	=	$(this).data('key-class');
    $('#myModal').modal('show');
    $('#loadData').load(base_url+'developers/email/show/'+class_name+'/'+fun);
})


$('.show_fun_detail').click(function(){
    fun_name 	=	$(this).data('fun-name');
    if($(this).children('span.detail-action-text').text()=='Show detail'){
        $('.describe-function-'+fun_name).removeClass('hidden');
        $(this).children('span.detail-action-text').text('Hide detail');
    }else{
        $('.describe-function-'+fun_name).addClass('hidden');
        $(this).children('span.detail-action-text').text('Show detail');
    }
});

$('.view-email').click(function(){
    $('#myModal').modal('show');
    email_log_id    =   $(this).data('mail-log-id');
    $('#loadData').load(base_url+'developers/email-log/view/'+email_log_id);
});


function api_info(api_id){
    $('.request_info').html('<p class=\"text-center\"><i class=\"fa fa-spinner fa-pulse fa-3x fa-fw\"></i><br>Please wait..........</p>');
    $('.request_info').load(base_url+'developers/api/info/'+api_id);
}

$(document).on('click', '.api-list li', function(){
    $(this).removeClass('font-w-700');
    $(this).addClass('font-w-700');
});



$(document).ready(function(){
    $('.reload_api_log').html("<i class='fa fa-refresh fa-spin'></i> Please wait");
    reload_api_log();
});


$('.reload_api_log').click(function(){
    $('.reload_api_log').html("<i class='fa fa-refresh fa-spin'></i> Please wait..");
    reload_api_log();
});

function reload_api_log(){
    
    $('.api-list ul').html('<li class="text-center">Please wait...</li>');
    $('.api-list ul').load(base_url+'developers/api/load_api_log', function(){
        $('.reload_api_log').html("<i class='fa fa-refresh'></i> Refresh");
    });
    
}


$(document).on('click', '.api_methods_list', function(){
    endpoint        =   $(this).data('api-endpoint');
    method          =   $(this).data('api-function');
    $('.api_methods_list').removeClass('font-w-700');
    
    $('.method_info').html("<i class='fa fa-refresh fa-spin'></i> Please wait..");
    $(this).addClass('font-w-700');
    $('.method_info').load(base_url+'developers/documentation/method_info/'+endpoint+'/'+method, function(){
        console.log('working on rendering');
        var md = new Remarkable();

        md_content  =   $('.method_info').find('.api_explaination').text();
        output  =   md.render(md_content);
        console.log(md_content);
        $('.method_info.api_explaination').html(output);
        });
    
    
});