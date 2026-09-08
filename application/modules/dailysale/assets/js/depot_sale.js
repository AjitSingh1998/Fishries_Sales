function calculate_total(){
	var total_qty 	= 0.00;
	var total_wt 	= 0.00;
	var sub_total 	= 0.00;
	
	$('.si_qty').each(function(index, element) {
		total_qty = parseFloat(total_qty) + parseFloat($(element).val());
    });
	
	$('.si_wt').each(function(index, element) {
		total_wt = parseFloat(total_wt) + parseFloat($(element).val());
    });
	
	$('.si_amt').each(function(index, element) {
		sub_total = parseFloat(sub_total) + parseFloat($(element).val());
    });
	
	$('input[name="total_qty"]').val(total_qty.toFixed(2));
	$('input[name="total_wt"]').val(total_wt.toFixed(2));
	$('input[name="sub_total"]').val(sub_total.toFixed(2));
	calculate_grand_total();
}

function calculate_grand_total(){
	var sub_total 			= parseFloat($('input[name="sub_total"]').val());
	var ice_amount 			= parseFloat($('input[name="ice_amount"]').val());
	var destroyed_amount 	= parseFloat($('input[name="destroyed_amount"]').val());
	var discount_amount 	= parseFloat($('input[name="discount_amount"]').val());
	var old_remaining  		= parseFloat($('input[name="old_remaining"]').val());
	
	if(isNaN(sub_total)){sub_total = parseFloat(0.00);}
	if(isNaN(ice_amount)){ice_amount = parseFloat(0.00);}
	if(isNaN(destroyed_amount)){destroyed_amount = parseFloat(0.00);}
	if(isNaN(discount_amount)){discount_amount = parseFloat(0.00);}
	if(isNaN(old_remaining)){old_remaining = parseFloat(0.00);}
	
	var grand_total = sub_total+ice_amount+destroyed_amount-discount_amount;
	$('input[name="grand_total"]').val(grand_total.toFixed(2));
	
	var grand_n_old = grand_total+old_remaining
	$('#grand_n_old').val(grand_n_old.toFixed(2));
	$('input[name="received_amt"]').trigger('change');
}

$(document).ready(function(e) {
	show_loader($('#panel_container'));
	
	$(document).on('change', '#market_id', function(){
		var mp_code = $(this).find('option:selected').data('code');
		$('#mp_code').val(mp_code);
	});
	
	$('#fish_weight').on('change', function(){
		var fish_weight = parseFloat($(this).val());
		var fish_rate 	= parseFloat($('#fish_rate').val());

		if(isNaN(fish_rate)){fish_rate = 0.00;}
		if(isNaN(fish_weight)){fish_weight = 0.00;}
		
		$('#total_amount').val(parseFloat(fish_rate*fish_weight).toFixed(2));
	});
	
	$('#fish_rate').on('change', function(){
		var fish_rate 	= parseFloat($(this).val());
		var fish_weight = parseFloat($('#fish_weight').val());
		
		if(isNaN(fish_rate)){fish_rate = 0.00;}
		if(isNaN(fish_weight)){fish_weight = 0.00;}
		
		$('#total_amount').val(parseFloat(fish_rate*fish_weight).toFixed(2));
	});
	
	$('input[name="ice_weight"]').on('change', function(){
		var ice_weight 	= parseFloat($(this).val());
		var ice_rate 	= parseFloat($('input[name="ice_rate"]').val());

		if(isNaN(ice_weight) || ice_weight == ""){ice_weight = parseFloat(0.00); $('input[name="ice_weight"]').val("0.00");}
		if(isNaN(ice_rate)){ice_rate = parseFloat(0.00);}
		
		$('input[name="ice_amount"]').val((parseFloat(ice_rate)*parseFloat(ice_weight)).toFixed(2));
		calculate_grand_total();
	});
	
	$('input[name="ice_rate"]').on('change', function(){
		var ice_rate 	= parseFloat($(this).val());
		var ice_weight 	= parseFloat($('input[name="ice_weight"]').val());

		if(isNaN(ice_weight) || ice_weight == ""){ice_weight = parseFloat(0.00); $('input[name="ice_rate"]').val("0.00");}
		if(isNaN(ice_rate)){ice_rate = parseFloat(0.00);}
		
		$('input[name="ice_amount"]').val((parseFloat(ice_rate)*parseFloat(ice_weight)).toFixed(2));
		calculate_grand_total();
	});
	
	$('input[name="destroyed_weight"]').on('change', function(){
		var destroyed_weight 	= parseFloat($(this).val());
		var destroyed_rate 		= parseFloat($('input[name="destroyed_rate"]').val());

		if(isNaN(destroyed_weight) || destroyed_weight == ""){destroyed_weight = parseFloat(0.00); $('input[name="destroyed_weight"]').val("0.00");}
		if(isNaN(destroyed_rate)){destroyed_rate = parseFloat(0.00);}
		
		$('input[name="destroyed_amount"]').val((parseFloat(destroyed_rate)*parseFloat(destroyed_weight)).toFixed(2));
		calculate_grand_total();
	});
	
	$('input[name="destroyed_rate"]').on('change', function(){
		var destroyed_rate 		= parseFloat($(this).val());
		var destroyed_weight 	= parseFloat($('input[name="destroyed_weight"]').val());

		if(isNaN(destroyed_rate) || destroyed_rate == ""){destroyed_rate = parseFloat(0.00); $('input[name="destroyed_rate"]').val("0.00");}
		if(isNaN(destroyed_weight)){destroyed_weight = parseFloat(0.00);}
		
		$('input[name="destroyed_amount"]').val((parseFloat(destroyed_rate)*parseFloat(destroyed_weight)).toFixed(2));
		calculate_grand_total();
	});
	
	$('input[name="discount_perc"]').on('change', function(){
		var discount_perc 	= parseFloat($(this).val());
		var sub_total 		= parseFloat($('input[name="sub_total"]').val());

		if(isNaN(discount_perc) || discount_perc == ""){discount_perc = parseFloat(0.00); $('input[name="discount_perc"]').val("0.00");}
		if(isNaN(sub_total)){sub_total = parseFloat(0.00);}
		
		discount_amount = parseFloat((discount_perc*sub_total)/100);
		
		$('input[name="discount_amount"]').val(discount_amount.toFixed(2));
		calculate_grand_total();
	});
	
	$('input[name="received_amt"]').on('change', function(){
		var received_amt 	= parseFloat($(this).val());
		var grand_n_old 	= parseFloat($('#grand_n_old').val());
		var by_remition 	= parseFloat($('#by_remition').val());
		if(isNaN(received_amt) || received_amt == ""){received_amt = parseFloat(0.00); $('input[name="received_amt"]').val("0.00");}
		if(isNaN(grand_n_old)){grand_n_old = parseFloat(0.00);}
		if(isNaN(by_remition)){by_remition = parseFloat(0.00);}
		remaining_amt = parseFloat((grand_n_old - (received_amt + by_remition)));
		$('input[name="remaining_amt"]').val(remaining_amt.toFixed(2));
	});
	
	var customer_data = '';
	$('.select-customer').select2({
	  placeholder: "Select Customer",
	  ajax: {
		url: site_url+'dailysale/get_customers/1',//$market_type : Local Market = 1, Outside Market = 2
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
	
	$(document).on('click', '.save_depot_sale_data',  function(){
		var datatosend = $('#depot_sale_form').serialize();
		$.ajax({
			url : site_url+'dailysale/ajax_depot_sale_data', 
			type : "POST",
			beforeSend: function(){show_loader($('#panel_container'));},
			data : datatosend,
			success: function(response, status, xhr){
				if(status == "success"){
					var ct = xhr.getResponseHeader("content-type") || "";
					if (ct.indexOf('json') > -1) {
						//If response is json
					  	if(response.status == 'success'){
							document.location.href = site_url+'dailysale/depot/edit/'+response.data;
						}else{
							hide_loader($('#panel_container'));
							$('.sale-response').html(response.message);
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
	
	$('.depot_edit_mode').on('click', function(){
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
							$('.panel-sale').html(response.data);
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
	
	$('#fish_code').on('change', function(){
		var values = $(this).val();
		var fish_code_rate = values.split('___');
		$('#fish_rate').val(fish_code_rate[1]);
	});
	
	//Save row
	$('.save_depot_item').on('click', function(){
		$this = $(this)
		$this.prop('disabled', true);
		var tr 				= $('#tr_sale_item');
		var item_form 		= $('#depotsale_item_form');
		var fish_code 		= $('#fish_code').val();
		var fish_weight 	= $('#fish_weight').val();
		var fish_rate 		= $('#fish_rate').val();
		var stock_type 		= $('#stock_type').val();	
		if(fish_code && stock_type && fish_weight > 0 && fish_rate > 0){
			var datatosend = {};
			datatosend['action_mode'] 	= $('#action_mode').val();
			datatosend['sale_id'] 		= $('#sale_id').val();
			datatosend['client_id'] 	= $('#client_id').val();
			datatosend['sale_date'] 	= $('#sale_date').val();
			datatosend['market_id'] 	= $('#market_id').val();
			datatosend['fish_code'] 	= fish_code;
			datatosend['fish_name'] 	= $('#fish_code option:selected').text();
			datatosend['fish_quantity'] = $('#fish_quantity').val();
			datatosend['fish_weight'] 	= fish_weight;
			datatosend['fish_rate'] 	= fish_rate;
			datatosend['total_amount'] 	= $('#total_amount').val();
			datatosend['remark'] 		= $('#remark').val();
			datatosend['stock_type'] 	= stock_type;
			datatosend['s_no_count'] 	= $('.tbody_sale_item tr').length;
			datatosend[csrf_token_name] = csrf_token_value;
			$.ajax({
				url : site_url+'dailysale/ajax_save_depot_item', 
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
								//$('.ajax_item_reponse').html('');
								$.toaster({settings:{timeout:10000,toast:{template:'<div class="alert alert-success"><button data-dismiss="alert" class="close">×</button> '+response.message+'</div>'}}, message:''});
								document.getElementById("sale_item_form").reset();
								$('.tbody_sale_item').append(response.data);
								calculate_total();
								$this.prop('disabled', false);
							}else{
								$this.prop('disabled', false);
								$('.sale-response').html(response.message);
							}
						}else{
							//If response is not json
							$this.prop('disabled', false);
							hide_loader($('#panel_container'));
							alert('Invalid response type, Please reload the page and try again.');
						}
					}else{
						$this.prop('disabled', false);
						hide_loader($('#panel_container'));
						alert('Response failed, Please reload the page and try again.');
					}
				},
				error: function(){
					$this.prop('disabled', false);
					hide_loader($('#panel_container'));
					alert('There is some error, Please reload the page and try again.');
				}		
			});
		}else{
			$this.prop('disabled', false);
			alert('Please insert valid required data first.');
			tr.addClass('alert-danger');
		}
	});
	
	$(document).on('click', '.edit_sale_item', function(){
		var sale_id 		= $(this).data('sale_id');
		var sale_item_id 	= $(this).data('sale_item_id');
		var carret_number 	= $(this).data('carret_number');
		
		
		var $this = $(this);
		event.preventDefault();
		var sale_id 		= $(this).data('sale_id');
		var sale_item_id 	= $(this).data('sale_item_id');
		var carret_number 	= $(this).data('carret_number');
		var urll = site_url+'dailysale/ajax_dp_item_edit_mode/'+sale_id+'/'+sale_item_id+'/'+carret_number;
		$('#common-modal-asset').removeClass('horizontal right').addClass('vertical bottom');
		$('#common-modal-asset .modal-body').empty();
		$('#common-modal-asset').modal('show');
		$.getJSON(urll, null, function (response) {
			$('#common-modal-asset .modal-title').addClass('text-center').html(response.page_title);
			$('#common-modal-asset .modal-body').html(response.setup_form);
			$('#common-modal-asset .modal-body').css('max-height', 500);
			$('#common-modal-asset .modal-footer').hide();
		});
	});
	
	$(document).on('click', '.update_rate', function(){
		var ajax_url = $(this).data('url');		
		
		var $this = $(this);
		event.preventDefault();
		$('#common-modal-asset').removeClass('horizontal right').addClass('vertical bottom');
		$('#common-modal-asset .modal-body').empty();
		$('#common-modal-asset').modal('show');
		$.getJSON(ajax_url, null, function (response) {
			$('#common-modal-asset .modal-title').addClass('text-center').html(response.page_title);
			$('#common-modal-asset .modal-body').html(response.setup_form);
			$('#common-modal-asset .modal-body').css('max-height', 500);
			$('#common-modal-asset .modal-footer').hide();
		});
	});
	
	$(document).on('click', '.delete_sale_item', function(){
		var confi = confirm('Are you sure you want to delete');
		if(confi){
			var datatosend = {};
			
			var sale_id 		= $(this).data('sale_id');
			var sale_item_id 	= $(this).data('sale_item_id');
			var carret_number 	= $(this).data('carret_number');
			
			datatosend['sale_id'] 		= sale_id;
			datatosend['sale_item_id'] 	= sale_item_id;
			datatosend['carret_number'] = carret_number;
			datatosend[csrf_token_name] = csrf_token_value;
			
			$.ajax({
				url : site_url+'dailysale/ajax_delete_depot_item', 
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
	
	$('.save_n_back').on('click', function(){
		$this = $(this)
		$this.prop('disabled', true);
		var conf = confirm('Do you want to send SMS to customer?');
		var send_sms = 'No';
		if(conf){
			send_sms = 'Yes';
		}
		var datatosend = {};
		datatosend['send_sms'] 			= send_sms;
		datatosend['action_mode'] 		= $('#action_mode').val();
		datatosend['sale_id'] 			= $('#sale_id').val();
		datatosend['invoice_number'] 	= $('#invoice_number').val();
		datatosend['client_id'] 		= $('#client_id').val();
		
		//Sub totals
		datatosend['total_qty'] 		= $('input[name="total_qty"]').val();
		datatosend['total_wt'] 			= $('input[name="total_wt"]').val();
		datatosend['sub_total'] 		= $('input[name="sub_total"]').val();
		
		//ice, destroyed and discount
		datatosend['ice_weight']		= $('input[name="ice_weight"]').val();
		datatosend['ice_rate'] 			= $('input[name="ice_rate"]').val();
		datatosend['ice_amount'] 		= $('input[name="ice_amount"]').val();
		datatosend['destroyed_weight']	= $('input[name="destroyed_weight"]').val();
		datatosend['destroyed_rate'] 	= $('input[name="destroyed_rate"]').val();
		datatosend['destroyed_amount'] 	= $('input[name="destroyed_amount"]').val();
		datatosend['discount_perc']		= $('input[name="discount_perc"]').val();
		datatosend['discount_amount']	= $('input[name="discount_amount"]').val();
		
		//totals
		datatosend['grand_total']		= $('input[name="grand_total"]').val();
		datatosend['old_remaining']		= $('input[name="old_remaining"]').val();
		datatosend['received_amt']		= $('input[name="received_amt"]').val();
		datatosend['remaining_amt']		= $('input[name="remaining_amt"]').val();
		datatosend['old_received_amt']	= $('input[name="old_received_amt"]').val();	
			
		datatosend[csrf_token_name] 	= csrf_token_value;
		$.ajax({
			url : site_url+'dailysale/ajax_save_depot_summary', 
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
							//$('.ajax_item_reponse').html('');
							$.toaster({settings:{timeout:10000,toast:{template:'<div class="alert alert-success"><button data-dismiss="alert" class="close">×</button> '+response.message+'</div>'}}, message:''});
							setTimeout(function(){ window.location.href = response.data; }, 1000);
						}else{
							$this.prop('disabled', false);
							$('.summary-response').html(response.message);
						}
					}else{
						//If response is not json
						$this.prop('disabled', false);
						hide_loader($('#panel_container'));
						alert('Invalid response type, Please reload the page and try again.');
					}
				}else{
					$this.prop('disabled', false);
					hide_loader($('#panel_container'));
					alert('Response failed, Please reload the page and try again.');
				}
			},
			error: function(){
				$this.prop('disabled', false);
				hide_loader($('#panel_container'));
				alert('There is some error, Please reload the page and try again.');
			}		
		});
	});
	
	var datatosend = {};
	datatosend[csrf_token_name] = csrf_token_value;
	$('.customer').typeahead({
		//fitToElement: true,
		delay: 3,
		source: function (query, process){
				  var cust_type = $('select[name="customer_type"]').val();
				  if(cust_type == 'regular'){
					datatosend['search_type'] = $(this.$element[0]).attr('id');
					datatosend['search_key'] = query;
					return $.post(site_url + "dailysale/typeahead_get_customers/1", datatosend, function (data) {
						return process(data.result);
					});
				  }
				},
		afterSelect: function(item){
			$('#customer_code').val(item.code).prop('readonly', true).addClass('disabled');
			$('#customer_name').val(item.company_name).prop('readonly', true).addClass('disabled');
			$('#customer_mobile').val(item.contact_number).prop('readonly', true).addClass('disabled');
			$('#customer_email').val(item.email).prop('readonly', true).addClass('disabled');	
									
		}
	});
	
	$(document).on('click', '.change_customer', function(){
		$('#customer_code').val('').prop('readonly', false).removeClass('disabled');
		$('#customer_name').val('').prop('readonly', false).removeClass('disabled');
		$('#customer_mobile').val('').prop('readonly', false).removeClass('disabled');
		$('#customer_email').val('').prop('readonly', false).removeClass('disabled');
	});
	
	setTimeout(function(){ calculate_total();}, 500);
	setTimeout(function(){ calculate_grand_total();}, 700);
	setTimeout(function(){ hide_loader($('#panel_container'));}, 1000);
});