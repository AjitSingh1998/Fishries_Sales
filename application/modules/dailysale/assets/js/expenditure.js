//allow only three places after decimal
function checkDecimal(el){
	var ex = /^\d*(\.\d{0,2})?$/;
	if(ex.test(el.value)==false){
		el.value = parseInt(el.value*100)/100;
	}
}

function calculate_summary(){
	var total_expenses = total_remition = 0;	
	if($(document).find('.exp_amount').length){
		$.each($(document).find('.exp_amount'), function(index, elem){
			total_expenses = parseFloat(total_expenses) + parseFloat($(this).val());		 
		});
	}
	if($(document).find('.rem_amount').length){
		$.each($(document).find('.rem_amount'), function(index, elem){
			total_remition = parseFloat(total_remition) + parseFloat($(this).val());		 
		});
	}
	var gross_sale = $('.gross_sale').val();
	var prev_balance = $('.prev_balance').val();
	var net_sale = (gross_sale - total_expenses);	
	var grand_total = (net_sale) + parseFloat(prev_balance);
	var balance = (grand_total - total_remition);
	
	$('.total_expenses').val(total_expenses.toFixed(2));
	$('.net_sale').val(net_sale.toFixed(2));
	$('.grand_total').val(grand_total.toFixed(2));
	$('.total_remition').val(total_remition.toFixed(2));
	$('.balance').val(balance.toFixed(2));
	
}

function deleteExpenditureItem(elemnt){	
	if( confirm('Are you sure to delete this particular ?')){
		var this_tr 	= $(elemnt).closest('tr');
		var datatosend	= {};
		datatosend['sale_id']		= $('#sale_id').val();
		datatosend['pid'] = $(elemnt).data('pid');
		datatosend[csrf_token_name] = csrf_token_value;			
		if(datatosend['pid'] > 0){
			$.post(site_url+'dailysale/ajax_delete_expenditure', datatosend, function(response, status, xhr){						
				if(status == "success"){
					var ct = xhr.getResponseHeader("content-type") || "";
					if (ct.indexOf('json') > -1) {
						//If response is json
						if(response.status == 'success'){
							this_tr.remove();
						}else{
							this_tr.addClass('alert-danger');
						}
						$.toaster({settings:{timeout:10000,toast:{template:'<div class="alert alert-'+response.status+'"><button data-dismiss="alert" class="close">×</button> '+response.message+'</div>'}}, message:''});
					}else{
						//If response is not json
						alert('Invalid response type, Please reload the page and try again.');
					}
				}else{
					alert('Response failed, Please reload the page and try again.');
				}
				calculate_summary();
			});
		}else{
			alert('Invalid Request.');
		}
	}
}

function deleteRemitionItem(elemnt){	
	if( confirm('Are you sure to delete this Remition ?')){
			var this_tr 	= $(elemnt).closest('tr');
			var datatosend	= {};
			datatosend['sale_id']		= $('#sale_id').val();
			datatosend['rid'] = $(elemnt).data('rid');
			datatosend[csrf_token_name] = csrf_token_value;			
			if(datatosend['rid'] > 0){
				$.post(site_url+'dailysale/ajax_delete_remition_item', datatosend, function(response, status, xhr){						
					if(status == "success"){
						var ct = xhr.getResponseHeader("content-type") || "";
						if (ct.indexOf('json') > -1) {
							//If response is json
							if(response.status == 'success'){
								this_tr.remove();
							}else{
								this_tr.addClass('danger');
							}
							$.toaster({settings:{timeout:10000,toast:{template:'<div class="alert alert-'+response.status+'"><button data-dismiss="alert" class="close">×</button> '+response.message+'</div>'}}, message:''});
						}else{
							//If response is not json
							alert('Invalid response type, Please reload the page and try again.');
						}
					}else{
						alert('Response failed, Please reload the page and try again.');
					}
					calculate_summary();
				});
			}else{
				alert('Invalid Request.');
			}
		}
}

$(document).ready(function(e) {	
	//Strict input box to accept only numeric(with or without floting point) values
	$(document).on("keypress keyup blur", '.strict_numeric', function (event) {
		var t_val = $(this).val($(this).val().replace(/[^0-9\.]/g,''));
		if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
			event.preventDefault();
		}
    });
	
	//Strict input box to accept only integer values
	$(document).on("keypress keyup blur change", '.strict_integer', function (event) {
		$(this).val($(this).val().replace(/[^\d].+/, ""));
		if ((event.which < 48 || event.which > 57)) {
			event.preventDefault();
		}
    });
	$('.datepicker').datepicker();
	
	$(document).on('click', '.print_data', function(){
		var target = $(this).attr('data-target');
		if (typeof target !== typeof undefined && target !== false) {
			$(target).print();
		}else{
			alert('hello');
			$("table.table").print();
		}
	});
	
	var datatosend = {};
	datatosend[csrf_token_name] = csrf_token_value;
    var $input = $('#particular');						
	$input.typeahead({
		fitToElement: true,
		delay: 3,
		source:  function (query, process){
					datatosend['search_key'] = query;
					return $.post(site_url + "dailysale/json_particulars_typehead", datatosend, function (data) {
									return process(data);
								});
		},
		afterSelect: function(item){
			//$('#search_id').val(item.id);
			//$('#search_name').val(item.name);
			//$('#search_email').val(item.email);
			//$('#search_paypal_email').val(item.paypal_email);							
		}
	});
	
	
	$('#add_exp_item').on('click', function(){
		var tr = $(this).closest('tr');
		var particular = $('#particular').val();
		var amount = $('#amount').val();
		var sale_id = $('#sale_id').val();
		if(particular && amount){
			var datatosend = {particular: particular, amount:amount,sale_id:sale_id};
			datatosend[csrf_token_name] = csrf_token_value;			
			$.post(site_url+'dailysale/ajax_save_expenditure_item', datatosend, function(data, txtStatus, jqXHR){
				if(data.status == 'success'){
					var html = $('<tr></tr>');		
					html.append('<td>'+data.particular+'</td>');
					html.append('<td class="no-padding"><input type="text" value="'+data.amount+'" class="exp_amount form-control disabled"></td>');
					html.append('<td class="no-padding text-center"><button type="button" class="btn btn-o btn-danger btn-xs" onclick="deleteExpenditureItem(this);" data-pid="'+data.item_id+'"><i class="fa fa-trash"></i></button></td>');
					html.insertBefore(tr);
					$('#particular').val('');
					$('#amount').val('');
					tr.removeClass('danger');
					calculate_summary();
				}
				$.toaster({settings:{timeout:10000,toast:{template:'<div class="alert alert-'+data.status+'"><button data-dismiss="alert" class="close">×</button> '+data.message+'</div>'}}, message:''});		
			});
		}else{
			alert('Please enter the expenditure particular & amount');
			if(!particular){
				$('#particular').focus();
			}else if(!amount){
				$('#amount').focus();
			}
			tr.addClass('danger');
		}
	
	});
	
	$('#add_remition').on('click', function(){
		var tr = $(this).closest('tr');
		var remition_by = $('#remition_by').val();
		var remition_amount = $('#remition_amount').val();
		var remition_date = $('#remition_date').val();
		var remition_remark = $('#remition_remark').val();
		var sale_id = $('#sale_id').val();		
		var client_id = $('#client_id').val();
		
		if(remition_by && remition_amount && remition_date){
			var datatosend = {remition_by: remition_by, amount:remition_amount, sale_id:sale_id, remition_date:remition_date, client_id:client_id, remark:remition_remark};
			datatosend[csrf_token_name] = csrf_token_value;
			$.post(site_url+'dailysale/ajax_save_remition_item', datatosend, function(data, txtStatus, jqXHR){
				if(data.status == 'success'){
					var html = $('<tr></tr>');		
					html.append('<td>Remitted By '+data.remition_by+'</td>');
					html.append('<td class="no-padding"><input type="text" value="'+data.amount+'" class="rem_amount form-control disabled"></td>');
					html.append('<td class="no-padding text-center"><button type="button" class="btn btn-o btn-danger btn-xs" onclick="deleteRemitionItem(this);" data-rid="'+data.item_id+'"><i class="fa fa-trash"></i></button></td>');
					html.insertBefore(tr);
					$('#remition_by').val('');
					$('#remition_date').val('');
					$('#remition_amount').val('');
					$('#remition_remark').val('');					
					tr.removeClass('danger');
					calculate_summary();
				}
				
				$.toaster({settings:{timeout:10000,toast:{template:'<div class="alert alert-'+data.status+'"><button data-dismiss="alert" class="close">×</button> '+data.message+'</div>'}}, message:''});		
			});
		}else{
			alert('Please enter the Remition By & amount');
			if(!remition_by){
				$('#remition_by').focus();
			}else if(!remition_date){
				$('#remition_date').focus();
			}else if(!remition_amount){
				$('#remition_amount').focus();
			}
			tr.addClass('danger');
		}
	
	});
	
});













