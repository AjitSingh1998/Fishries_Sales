$(document).ready(function(e) {
	$('.datetimepicker').datetimepicker({
		format: 'DD/MM/YYYY hh:mm a',
		sideBySide: true,
	});
	
	var customer_data = '';
	$('.select-customer').select2({
	  placeholder: "Select Customer",
	  ajax: {
		url: site_url+'dailysale/get_customers/1',
		dataType: 'json',
		data: function (params) {
		  return {
			q: params.term, // search term
		  };
		},
		processResults: function (data) {
		  // Tranforms the top-level key of the response object from 'items' to 'results'
		  customer_data = data.result2;
		  return {
			results: data.result1
		  };
		}
	  },
	  minimumInputLength : 1,
	  allowClear: true
	});
	
	$('.select-customer').on('select2:select', function (e) {
		var data = e.params.data;
		var contact_number = customer_data[data.id]['contact_number'];
		$('input[name="contact_number"]').val(contact_number);
		$('input[name="contact_number"]').prop('readonly', true);
	});
	
	$('.select-customer').on('select2:unselect', function (e) {
		var data = e.params.data;
		$('input[name="contact_number"]').val('');
		$('input[name="contact_number"]').prop('readonly', false);
	});
	
	$('input[name="customer_type"').on('change', function(){
		var customer_type = $(this).val();
		if(customer_type == "new"){
			$('.for_registered').hide();
			$('.for_new').show();
			$('.select-customer').val(null).trigger('change');
		}else if(customer_type == "registered"){
			$('.for_new').hide();
			$('.for_registered').show();
		}
	});
	
	$('.save_point_sale_data').on('click', function(){
		var datatosend = $('#point_sale_form').serialize();
		$.ajax({
			url : site_url+'dailysale/ajax_point_sale_data', 
			type : "POST",
			beforeSend: function(){
							show_loader($('#panel_container'));
						},
			data : datatosend,
			success: function(response, status, xhr){
				if(status == "success"){
					var ct = xhr.getResponseHeader("content-type") || "";
					if (ct.indexOf('json') > -1) {
						//If response is json
					  	if(response.status == 'success'){
							document.location.href = site_url+'dailysale/point/edit/'+response.data;
						}else{
							hide_loader($('#panel_container'));
							$('.ajax-response').html(response.message);
						}
					}else{
						//If response is not json
						hide_loader($('#panel_container'));
						alert('Invalid response type, Please reload the page and try again.');
					}
				}else{
					hide_loader($('#panel_container'));
					alert('Response failed, Please reload the page and try again.');
				}
			},
			error: function(){
				hide_loader($('#panel_container'));
				alert('There is some error, Please reload the page and try again.');
			}		
		});
	});	
});