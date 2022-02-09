
var start_time = 'NULL';
var end_time = 'NULL';
	$('#daterange').daterangepicker({
		    "timePicker": true,
		    "timePicker24Hour": true
	}, function(start, end, label) {
		start_time = start.format('YYYY-MM-DD hh:mm:00');
		end_time = end.format('YYYY-MM-DD hh:mm:00');
		filter_activity_log();
	  	console.log('New date range selected: ' + start.format('YYYY-MM-DD hh:mm:00') + ' to ' + end.format('YYYY-MM-DD hh:mm:00') + ' (predefined range: ' + label + ')');
	});
/* Preview selected image */
var host_name=window.location.hostname;
//base_url=location.protocol+'//'+host_name+'/';
$('.load_more_activities_btn').click(function() {
    
    $('.load_more_activities_btn').hide();
    var is_post =   $(this).data('is_post'); 
    if(is_post=='no'){
        var offset  =   $('tbody.activities_tbody tr').length;

    }
    if(offset<20){
        $('.load_more_activities_btn').hide();
        $('.last_msg').removeClass('hidden');
        return false;
    }
    var is_end =    $('.is_end').html();
    if(is_end=='yes'){
        $('.last_msg').removeClass('hidden');
        return false;
    }
    show_loader();
    if(is_post=='no'){
        load_activities(offset);
    }
});
$('.reset_filter_form').click(function(){
    $('.filter_form')[0].reset();
    $('.reset_filter_form').addClass('hidden');
    filter_activity_log();
});

    function filter_activity_log(auto_list =false){
        $('.is_end').html('no');

        $(".fa_refresh_btn").addClass('fa-spin');
        show_loader();
	    var aa_admin_id     =   $('#admin_filter').val();
	    var aa_entity	 	=   $('#entity_filter').val();
	    var aa_action  		=   $('#action_filter').val();
        if(aa_action || aa_entity || aa_admin_id || start_time!='NULL' || end_time!='NULL'){
            $('.reset_filter_form').removeClass('hidden');
        }
        if(auto_list){
            var last_activity_id = $('.activities_tbody tr').first().data("activity-id");
            //     console.log(last_activity_id);
            // return false;
            $.get(base_url+'admin/admins/filter_activity_log?'+'aa_admin_id='+aa_admin_id+'&aa_entity='+aa_entity+'&aa_action='+aa_action+'&start_time='+start_time+'&end_time='+end_time+'&last_activity_id='+last_activity_id, 
                function(html, status){
                    // console.log(html)
                    if(html != ""){
                        $('.activities_tbody').prepend(html);
                    }
                    var tr_count    =   $('.activities_tbody tr').length;

                    if(tr_count<20){
                        $('.load_more_activities_btn').hide();
                    }else{
                        $('.load_more_activities_btn').show();
                    }
                    $(".fa_refresh_btn").removeClass('fa-spin');

                    hide_loader();
                });
        }else{
            
            $.get(base_url+'admin/admins/filter_activity_log?'+'aa_admin_id='+aa_admin_id+'&aa_entity='+aa_entity+'&aa_action='+aa_action+'&start_time='+start_time+'&end_time='+end_time, function(html, status){
                $('.activities_tbody').html(html);

                var tr_count    =   $('.activities_tbody tr').length;

                if(tr_count<20){
                    $('.load_more_activities_btn').hide();
                }else{
                    $('.load_more_activities_btn').show();
                }


                $(".fa_refresh_btn").removeClass('fa-spin');
                hide_loader();
            });
        }
        
    }

$(document).ready(function(){
    filter_activity_log();    
	setInterval(function(){
        var auto_list = true;
        filter_activity_log(auto_list);
	},30000);
});


function load_activities(offset){
    var aa_admin_id     =   $('#admin_filter').val();
    var aa_entity	 	=   $('#entity_filter').val();
    var aa_action  		=   $('#action_filter').val();
    
    $.get(base_url+'admin/admins/load_more_activities?count='+offset+'&aa_admin_id='+aa_admin_id+'&aa_entity='+aa_entity+'&aa_action='+aa_action+'&start_time='+start_time+'&end_time='+end_time, function(html, status){
        if(html==''){
            hide_loader();
            $('.load_more_activities_btn').hide();
            $('.last_msg').removeClass('hidden');
            $('.is_end').html('yes');
            return false;
        }

        $('.activities_tbody').append(html);
        hide_loader();
        $('.load_more_activities_btn').show();
    });
}

function get_admin_activity_info(id){
    $.get(base_url+'admin/admins/load_admin_activity?id='+id, function(html, status){
        $('#myModal .modal_body_content').html(html);
        $("#myModal .modal_body_content .location_info").parent().addClass("hidden");
        $('#myModal').modal('show');
    });
}

function get_location(ip_address){
 	$.get(base_url+'admin/admins/get_location?'+'ip_address='+ip_address, function(html, status){
            // console.log(html);
            // html = JSON.parse(html);
            $("#myModal .modal_body_content .location_info").parent().removeClass("hidden");
            $("#myModal .modal_body_content .location_info").html(html);
        });
 }