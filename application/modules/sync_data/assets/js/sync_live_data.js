function ajaxRequest(url, ele, page_number){
	var last_sync = $(ele).data('last_sync');
	var data_type = $(ele).data('type');
	var sync_date = $('#sync_date').val();	
	$.ajax({
		url: url +'/'+page_number,
		type:'GET',
		data: {data_type:data_type,sync_date:sync_date},
		//async:false,
		success: function(response, textStatus, jqXHR){
			if(response){
				if(response.status == 'success'){
					if(response.process == 'continue'){
						$(ele).closest('tr').find('td.response').html(response.message);
						ajaxRequest(url, ele, response.next_page);					
					}else{
						$(ele).closest('tr').find('td.response').html(response.message);
						$(ele).find('i').removeClass('fa-spin');
						$(ele).removeClass('btn-danger sync_table');
						$(ele).addClass('btn-success');
						
						if(!$('#show_all_data').prop('checked')){
							$('.initiate_sync_data').trigger('click');
						}											
					}
				}else if(response.status === false){
					var msg = response.error || response.message;
					$(ele).closest('tr').find('td.response').html(msg);
					$(ele).find('i').removeClass('fa-spin');
					$(ele).removeClass('btn-danger disabled').addClass('btn-primary');
				}else{
					$(ele).closest('tr').find('td.response').html(response.message);
					$(ele).find('i').removeClass('fa-spin');
					$(ele).removeClass('btn-danger disabled').addClass('btn-primary');
				}
			}else{
				alert('Server not responding!');
				$(ele).find('i').removeClass('fa-spin');
				$(ele).removeClass('btn-danger disabled').addClass('btn-primary');
			}
			hide_loader($('#sync_panel'));
			console.log(response);
		},
		error:function(response, textStatus, jqXHR){
			alert(jqXHR);
			$(ele).find('i').removeClass('fa-spin');
			$(ele).removeClass('disabled btn-danger').addClass('btn-primary');
			hide_loader($('#sync_panel'));
		}		
	});
}

function init_ajax(ele){
	var url = site_url + $(ele).data('url');
	var records = $(ele).data('records');		
	if(Math.floor(records) == records && $.isNumeric(records) && records > 0){
		show_loader($('#sync_panel'));
		$(ele).addClass('disabled btn-danger').removeClass('btn-primary');		
		$(ele).find('i').addClass('fa-spin');
		var pageno = 1;
		ajaxRequest(url, ele, pageno);
	}
}

function load_data(e){
	var url = site_url + $(e).data('url');
	var dtype = $(e).data('type');
	var sync_date = $('#sync_date').val();	
	$.ajax({
		url:  url,
		type:'GET',
		data:{data_type:dtype, sync_date:sync_date},
		success: function(response, textStatus, jqXHR){
			if(response){
				$('#response_data').html(response.data);
			}else{
				alert('Server not responding!');
			}
			hide_loader($('#sync_panel'));
		},
		error:function(response, textStatus, jqXHR){
			alert(jqXHR);			
			hide_loader($('#sync_panel'));
		}
	});
}

$(document).ready(function(e) {
	$('.datepicker').datepicker({
		format: 'dd/mm/yyyy',
		autoclose: true,
		todayHighlight: true
	});
	
	$('#master_data').click(function() {
		if($(this).hasClass('active')){
			return false;
		}else{
			show_loader($('#sync_panel'));
			load_data(this);
			$(this).addClass('active');
			$('#local_data').removeClass('active');
		}		
    });
	
	$('#local_data').click(function() {
		if($(this).hasClass('active')){
			return false;
		}else{
			show_loader($('#sync_panel'));
			load_data(this);
			$(this).addClass('active');
			$('#master_data').removeClass('active');
		}		
    });
	
	$('#show_all_data').change(function() {
		var target = $(this).data('target');
		if($(this).prop('checked')){
			$(target).find('.sync_table').removeClass('disabled');
			$('.initiate_sync_data').addClass('disabled');
		}else{
			$(target).find('.sync_table').addClass('disabled');
			$('.initiate_sync_data').removeClass('disabled');
		}
    });
	
    $(document).on('click', '.sync_table', function(ele){
		if($('#show_all_data').prop('checked')){
			init_ajax(this);
		}else{
			return false;
		}
	});
	
	$('.initiate_sync_data').click(function(ele){
		//show_loader($('#sync_panel'));
		var allTables = $('.sync_table');
		if(allTables.length){
			init_ajax(allTables[0]);
		}else{
			return false;
		}
	});
	
	
});