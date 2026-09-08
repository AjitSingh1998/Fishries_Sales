function calculate_total(){
	var total_qty = 0.00;
	var total_wt = 0.00;
	var grand_total = 0.00;
	
	$('.si_qty').each(function(index, element) {
		total_qty = parseFloat(total_qty) + parseFloat($(element).val());
    });
	
	$('.si_wt').each(function(index, element) {
		total_wt = parseFloat(total_wt) + parseFloat($(element).val());
    });
	
	$('input[name="total_qty"]').val(total_qty.toFixed(2));
	$('input[name="total_wt"]').val(total_wt.toFixed(2));
}

$(document).ready(function(e) {
	show_loader($('#panel_container'));
	
	$(document).on('change', '.market_category', function(){
		$this = $(this);
		var market_category = $this.val();
		if(market_category != '' && market_category != 'undefined'){
			var datatosend = {'market_category' : market_category};
			datatosend[csrf_token_name] = csrf_token_value;
			$.ajax({
				url : site_url+'dailysale/ajax_get_market_places', 
				type : "POST",
				beforeSend: function(){show_loader($('#panel_container'));},
				data : datatosend
			}).done(function(response){
				$('.market_id').prop('disabled', false);
				$('.market_id').val('').trigger("change");
				$('.market_id').html(response.data);
				hide_loader($('#panel_container'));
			});
		}else{
			$('.market_id').val('').trigger("change");
			$('.market_id').html('<option value="" selected="selected">Select Market</option>');
			$('.market_id').prop('disabled', true);
		}
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
	
	$(document).on('change', 'input[name="customer_type"]', function(){
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
	
	$(document).on('click', '.save_free_sale_data',  function(){
		var datatosend = $('#free_sale_form').serialize();
		$.ajax({
			url : site_url+'dailysale/ajax_free_sale_data', 
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
							document.location.href = site_url+'dailysale/free/edit/'+response.data;
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
	
	$('.fs_edit_mode').on('click', function(){
		var urll = $(this).data('url');
		var datatosend = {};
		datatosend[csrf_token_name] = csrf_token_value;
		$.ajax({
			url : urll, 
			type : "POST",
			beforeSend: function(){show_loader($('#panel_container'));},
			data : datatosend,
			success: function(response, status, xhr){
				if(status == "success"){
					var ct = xhr.getResponseHeader("content-type") || "";
					if (ct.indexOf('json') > -1) {
						//If response is json
						
					  	if(response.status == 'success'){
							$('.panel-heading').html(response.data);
							hide_loader($('#panel_container'));
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
	
	//Save row
	$('.save_free_item').on('click', function(){
		var tr 				= $('#tr_sale_item');
		var item_form 		= $('#freesale_item_form');
		var fish_code 		= $('#fish_code').val();
		var fish_weight 	= $('#fish_weight').val();
		
		if(fish_code && fish_weight > 0){
			var datatosend = {};
			datatosend['action_mode'] 		= $('#action_mode').val();
			datatosend['free_sale_id'] 		= $('#free_sale_id').val();
			datatosend['free_sale_date']	= $('#free_sale_date').val();
			datatosend['market_category'] 	= $('#market_category').val();
			datatosend['market_id'] 		= $('#market_id').val();
			datatosend['fish_name'] 		= $('#fish_code option:selected').text();
			datatosend['fish_code'] 		= $('#fish_code').val();
			datatosend['fish_quantity'] 	= $('#fish_quantity').val();
			datatosend['fish_weight'] 		= $('#fish_weight').val();
			datatosend['total_amount'] 		= $('#total_amount').val();
			datatosend['stock_type'] 		= $('#stock_type').val();	
			datatosend['remark'] 			= $('#remark').val();
			datatosend['s_no_count'] 		= $('.tbody_sale_item tr').length;			
			datatosend[csrf_token_name] 	= csrf_token_value;
			$.ajax({
				url : site_url+'dailysale/ajax_save_free_sale_item', 
				type : "POST",
				beforeSend: function(){$('.ajax-response').html(''); show_loader($('#panel_container'));},
				data : datatosend,
				success: function(response, status, xhr){
					if(status == "success"){
						var ct = xhr.getResponseHeader("content-type") || "";
						if (ct.indexOf('json') > -1){
							//If response is json
							hide_loader($('#panel_container'));
							tr.removeClass('alert-danger');
							if(response.status == 'success'){
								$('.ajax_item_reponse').html('');
								$.toaster({settings:{timeout:10000,toast:{template:'<div class="alert alert-success"><button data-dismiss="alert" class="close">×</button> '+response.message+'</div>'}}, message:''});
								var formEl = document.getElementById("freesale_item_form");
								if(formEl) { formEl.reset(); }
								$('#fish_code').val('');
								$('#fish_quantity').val('');
								$('#fish_weight').val('');
								$('#stock_type').val('');
								$('.tbody_sale_item').append(response.data);
								calculate_total();
							}else{
								$('.ajax-response').html(response.message);
								$.toaster({settings:{timeout:10000,toast:{template:'<div class="alert alert-danger"><button data-dismiss="alert" class="close">×</button> '+response.message+'</div>'}}, message:''});
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
				error: function(xhr, status, error){
					hide_loader($('#panel_container'));
					var msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'There is some error, Please reload the page and try again.';
					alert(msg);
				}		
			});
		}else{
			alert('Please insert valid required data first.');
			tr.addClass('alert-danger');
		}
	});
	
	$(document).on('click', '.delete_sale_item', function(){
		var confi = confirm('Are you sure you want to delete');
		if(confi){
			var datatosend = {};
			
			var free_sale_id 	= $(this).data('free_sale_id');
			var sale_item_id 	= $(this).data('sale_item_id');
			
			datatosend['free_sale_id'] 	= free_sale_id;
			datatosend['sale_item_id'] 	= sale_item_id;
			datatosend[csrf_token_name] = csrf_token_value;
			
			$.ajax({
				url : site_url+'dailysale/ajax_delete_free_sale_item', 
				type : "POST",
				beforeSend: function(){$('.ajax-response').html(''); show_loader($('#panel_container'));},
				data : datatosend,
				success: function(response, status, xhr){
					if(status == "success"){
						var ct = xhr.getResponseHeader("content-type") || "";
						if (ct.indexOf('json') > -1){
							//If response is json
							hide_loader($('#panel_container'));
							if(response.status == 'success'){
								window.location.href = response.data;
							}else{
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
			
		}
	});	
	
	setTimeout(function(){ calculate_total();}, 500);
	setTimeout(function(){ hide_loader($('#panel_container'));}, 800);
});