
$(document).ready(function(e) {
	
	$(document).on('change', '.market_id, .dispatch_date', function () {
		var $this 			= $(this);
		var dispatch_from 	= $('.market_id').val();
		var dispatch_date 	= $('.dispatch_date').val();
		
		if(dispatch_from && dispatch_date){			
			show_loader($('#report_panel'));
			var datatosend = {'dispatch_from' : dispatch_from, 'dispatch_date' : dispatch_date};
			datatosend[csrf_token_name] = csrf_token_value;
			$.ajax({
				url : site_url+'reports/ajax_get_vehicle_numbers', 
				type : "POST",
				data : datatosend,
				error: function (xhr, ajaxOptions, thrownError) {
					alert('Response - '+ xhr.status+':  '+thrownError+' Please contact to development department.');
					hide_loader($('#report_panel'));
				},
				success: function( response, textStatus, jqXHR ){
					hide_loader($('#report_panel'));
					$('#vehicle_number').html(response.option_html);
				}
			})
		}else{
			$('#vehicle_number').html('<option value="" selected="selected">Select Vehicle</option>');
		}
	});	
});


									 
		

