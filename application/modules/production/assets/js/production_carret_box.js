function insert_box_wt(){
	var $package_tr = $('tbody#package-container tr');
	var dhalta_of_first = 0;
	$package_tr.each(function(index, element) {
       	var tr 			= $(element);
	   	var fish_wt 	= tr.find(".fish_wt").val();
		var fish_dhalta = tr.find(".fish_code option:selected").data('fish_dhalta');
		if(isNaN(fish_wt) || fish_wt == "" || fish_wt == "undefined"){fish_wt = 0;}
		if(isNaN(fish_dhalta) || fish_dhalta == "" || fish_dhalta == "undefined"){fish_dhalta = 0;}else{ fish_dhalta = fish_dhalta/1000;}
		if(index == 0){
			dhalta_of_first = fish_dhalta;
		}
		if($package_tr.length > 1){
			fish_dhalta = dhalta_of_first;
		}
		tr.find('.box_wt').val(fish_wt-fish_dhalta);
    });
	calculate_carret_wt();
}

function calculate_carret_wt(){
	var carret_weight = fish_wt = 0;
	$('#package-container').find('.fish_wt').each(function(index, element) {
        fish_wt = parseFloat($(element).val());
		if(isNaN(fish_wt) || fish_wt == ""){fish_wt = parseFloat(0);}
		carret_weight += fish_wt;
    });
	$('input[name="carret_weight"]').val(carret_weight.toFixed(3));
	
	var t_box_wt = box_wt = 0;
	$('.box_wt').each(function(index, element) {
		box_wt = parseFloat($(element).val());
		if(isNaN(box_wt) || box_wt == ""){box_wt = parseFloat(0);}
		t_box_wt += box_wt;
	});
	$('input[name="box_weight"]').val(t_box_wt.toFixed(3));
}

function calculate_carret_qty(){
	var carret_quantity = fish_qty = 0;
	$('#package-container').find('.fish_qty').each(function(index, element) {
        fish_qty = parseInt($(element).val());
		if(isNaN(fish_qty)){fish_qty = parseInt(0);}
		carret_quantity += fish_qty;
    });
	$('input[name="carret_quantity"]').val(carret_quantity);
}

function serial_number(){
	$('tbody#box_listing_container').find('.carret_row').each(function(index, element) {
		$(element).find('.sr_no').text(index+1);
    });
}

$(document).ready(function(e) {
	var fs_remove_btn = '<button type="button" class="btn btn-danger btn-sm remove_fish"> <i class="fa fa-times"></i> </button>';
	
	//Save row
	$(document).on('click', '.add_fish', function(){
		var pack_cont = $('#package-container');
		var this_tr = $(this).closest('tr');
		var fishes = [];
		pack_cont.find('tr').each(function(index, element) {
            var package_tr = $(element);
			fishes[index] = package_tr.find('.fish_code option:selected').val();
        });		
		var $asset_table = $('#asset-table tr').clone();		
		$asset_table.find('.fish_code > option').each(function(index, element) {
            //alert($(this).text() + ' ' + $(this).val());
			for(var i = 0; i< fishes.length; i++){
				if($(this).val() == fishes[i]){
					$(this).remove();
				}
			}
        });
		var tr = $('#package-container tr:last');
		//var box_number = $('.box_number').val();
		var fish_code = tr.find('.fish_code').val();
		var fish_qty = tr.find('.fish_qty').val();
		var fish_wt = tr.find('.fish_wt').val();
		
		if(fish_code && fish_wt > 0){
			tr.removeClass('alert-danger').addClass('alert-success');
			this_tr.find('.fish_action').html(fs_remove_btn);
			$('#package-container').append($asset_table);
			$('#package-container tr:last').find(".fish_code").focus();
		}else{
			alert('Please insert valid required data first.');
			tr.removeClass('alert-success').addClass('alert-danger');
		}
	});
	
	$(document).on('click', '.remove_fish', function(){
		var conf = confirm("Are you sure do delete this item?");
		if(conf){
			$(this).closest('tr').remove();
			calculate_carret_wt();
			calculate_carret_qty();
		}
	});
	
	$(document).on('change', '.fish_code', function(){
		var $this = $(this);
		var td = $this.closest('td');
		var fish_type = $this.find("option:selected").data('fish_type');
		var fish_name = $this.find("option:selected").data('fish_name');
		var fish_rate = $this.find("option:selected").data('fish_rate');
		td.find('.fish_name').val(fish_name);
		td.find('.fish_rate').val(fish_rate);
		var all_fish_type = $('select[name="fish_type"]').val();
		if(fish_type == "Rotten"){
			$('select[name="fish_type"]').val('Rotten');
		}else if(all_fish_type){
			$('select[name="fish_type"]').val(all_fish_type);
		}else {
			$('select[name="fish_type"]').val('Fresh');
		}
	});
	
	$(document).on('keyup input change blur', '.fish_qty', function(){
		calculate_carret_qty();
	});
	
	$(document).on('keyup input change blur', '.fish_wt', function(){
		if($('.make_box').prop('checked')){
			insert_box_wt();
		}
		calculate_carret_wt();
	});
	
	$(document).on('keyup input change blur', '.box_wt', function(){
		calculate_carret_wt();
	});
	
	$(document).on('click', '.delete_carret', function(){
		var carret_row = $(this).closest('tr.carret_row');
		var carret_number = $(this).data('carret_number');
		var confi = confirm('Are you sure you want to delete carret number '+carret_number);
		if(confi){
			var datatosend = {};
			datatosend['production_id'] = $("#production_id").val();
			datatosend['carret_number'] = carret_number;
			datatosend['box_number'] 	= $(this).data('box_number');
			datatosend['sale_id'] 		= $(this).data('sale_id');
			datatosend[csrf_token_name] = csrf_token_value;
			
			$.ajax({
				url : site_url+'production/ajax_delete_carret', 
				type : "POST",
				beforeSend: function(){$('.ajax-response').html(''); show_loader($('#panel_container'));},
				data : datatosend,
				success: function(response, status, xhr){
					if(status == "success"){
						var ct = xhr.getResponseHeader("content-type") || "";
						if (ct.indexOf('json') > -1){
							//If response is json
							if(response.status == 'success'){
								$.toaster({settings:{timeout:10000,toast:{template:'<div class="alert alert-success"><button data-dismiss="alert" class="close">×</button> '+response.message+'</div>'}}, message:''});
								carret_row.fadeOut(200, function() { carret_row.remove(); serial_number(); });
							}else{
								$.toaster({settings:{timeout:10000,toast:{template:'<div class="alert alert-danger"><button data-dismiss="alert" class="close">×</button> '+response.message+'</div>'}}, message:''});
							}
							hide_loader($('#panel_container'));
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
	
	$(document).on('click', '.edit_carret', function(event){
		var $this = $(this);
		event.preventDefault();
		var production_type = $('#production_type').val();
		var carret_number 	= $(this).data('carret_number');
		var mp_code 		= $('.mp_code').val();
		var box_number		= $(this).data('box_number') ? $(this).data('box_number') : 0;
		var sale_id			= $(this).data('sale_id') ? $(this).data('sale_id') : 0;
		var sr_no 			= $(this).closest('tr.carret_row').find('td.sr_no').text();
		var urll = site_url+'production/ajax_carret_edit_mode/'+carret_number+'/'+sr_no+'/'+box_number+'/'+sale_id+'/'+mp_code+'/'+production_type;
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
	
	var datatosend = {};
	datatosend[csrf_token_name] = csrf_token_value;
	if($.fn.typeahead){
		$('.select-client').typeahead({
			//fitToElement: true,
			delay: 1,
			source: function (query, process){
						datatosend['search_key'] = query;
						return $.post(site_url + "production/typeahead_get_customers/1", datatosend, function (data) {
							return process(data.result);
						});
					},
			afterSelect: function(item){
				$('#client_id').val(item.ID);
				$('#client_name').val(item.company_name);
				$('#client_mobile').val(item.contact_number);
				$('#client_email').val(item.email);	
				$('.select-client').prop('readonly', true);
				$('.make_box').prop('checked', false).trigger('change');
			}
		});
	}	
	
	$('.make_box').on('change', function(){
		var $this = $(this);
		if($this.prop('checked') === true){
			//make box checked
			$('input[name="box_number"]').prop('readonly', false).removeClass('disabled');
			$('#client_id').val("");
			$('#client_name').val("");
			$('#client_mobile').val("");
			$('#client_email').val("");
			$('.change_customer').addClass('hidden');
			$('.select-client').val(null).prop('readonly', true);
			var current_box = $('input[name="box_number"]').val();
			if(!current_box || current_box.trim() === ''){
				$.getJSON(site_url + "production/ajax_get_next_box_number", function(res){
					if(res && res.box_number){
						$('input[name="box_number"]').val(res.box_number);
					}
				});
			}
			insert_box_wt();
		}else{
			//make box unchecked
			$('input[name="box_number"]').val('').prop('readonly', true).addClass('disabled');
			$('.change_customer').removeClass('hidden');
			$('.select-client').prop('readonly', false);
			$('input[name="box_weight"]').val('0.00');
			$('.box_wt').val('0.00');
			calculate_carret_wt();
		}
	});
	
	$(document).on('click', '.change_customer', function(){
		$('#client_id').val("");
		$('#client_name').val("");
		$('#client_mobile').val("");
		$('#client_email').val("");
		$('.select-client').val("").prop('readonly', false);
	});
	
	$(document).on('click', '.save_carret', function(){
		// Remove any trailing empty rows (where fish_code or fish_wt is empty) if at least one valid row exists
		var valid_rows = 0;
		$('#package-container tr').each(function(){
			var fc = $(this).find('.fish_code').val();
			var fw = parseFloat($(this).find('.fish_wt').val());
			if(fc && !isNaN(fw) && fw > 0){
				valid_rows++;
			}
		});
		if(valid_rows === 0){
			alert('Please select a fish and enter a valid carret weight.');
			$('#package-container tr:first').find('.fish_code').focus();
			return false;
		}
		$('#package-container tr').each(function(){
			var fc = $(this).find('.fish_code').val();
			var fw = parseFloat($(this).find('.fish_wt').val());
			if(!fc || isNaN(fw) || fw <= 0){
				$(this).remove();
			}
		});

		var is_make_box = $('.make_box').prop('checked');
		if(is_make_box){
			insert_box_wt();
			var b_no = $('input[name="box_number"]').val();
			if(!b_no || b_no.trim() == ''){
				// Auto generate next box number synchronously if empty
				$.ajax({
					url: site_url + "production/ajax_get_next_box_number",
					dataType: 'json',
					async: false,
					success: function(res){
						if(res && res.box_number){
							$('input[name="box_number"]').val(res.box_number);
						}
					}
				});
			}
		}else{
			$('input[name="box_number"]').val('');
			$('input[name="box_weight"]').val('0.00');
			$('.box_wt').val('0.00');
			calculate_carret_wt();
			calculate_carret_qty();
		}
		
		show_loader($('#panel_container'));
		var package_form = $('#package_form');
		var tr 			= $('#package-container tr');
		var form_data 	= $('#package_form').serialize();
		var s_no 		= $('#box_listing_container').children().length;	
		var fish_type	= $('select[name="fish_type"]').val();
		var cl_name		= $('.select-client').val();
		var make_box	= (is_make_box) ? "Yes" : "No";
		var box_number  = $('input[name="box_number"]').val();
		$.ajax({
			type: "POST",
			url: site_url+"production/ajax_save_carret/"+s_no,
			data: form_data,
			beforeSend: function(){$('.ajax_reponse').html('');},
			error: function (xhr, ajaxOptions, thrownError){
				hide_loader($('#panel_container'));
				alert('Response - '+ xhr.status+':  '+thrownError+' Please contact to development department.');
			},
			success: function( data ){
				if(data.status == 'success'){
					$('#box_listing_container').append(data.data);
					$('#package-container').find('.alert-success').remove();
					$.toaster({settings:{timeout:10000,toast:{template:'<div class="alert alert-success"><button data-dismiss="alert" class="close">×</button> '+data.message+'</div>'}}, message:''});
					if(data.action_mode == 'add'){
						window.location.href = data.redirect_url;
					}
					$('#package-container tr:last option:eq(0)').prop('selected', true);
					$('#package-container tr:last').removeClass('alert-success').removeClass('alert-danger');
					$('#package-container tr:last select').focus();
					$('.ajax_reponse').html('');
					$(".panel-scroll").scrollTop( $( ".panel-scroll" ).prop( "scrollHeight" ) );
					if($.fn.perfectScrollbar){
						$(".panel-scroll").perfectScrollbar('update');
					}
					package_form.find('input[name="carret_quantity"]').val(0);
					document.getElementById('package_form').reset();
					$('.select-client').val(cl_name);
					if(make_box == "Yes" && box_number){
						var split_number = parseInt(box_number.replace(/\D/g,''));
    					var split_text = box_number.replace(new RegExp("[0-9]", "g"), "");
						var new_box_number = isNaN(split_number) ? 1 : parseInt(split_number+1);
						$('input[name="box_number"]').val(split_text+""+new_box_number);
						$('.make_box').prop('checked', true);
					}else{
						$('.make_box').prop('checked', false);
					}
					if(data.extra_job !== undefined && data.extra_job == 'Box'){
						$('#package_form').find('.box_number').prop('checked', true);
					}
					$('[name=fish_type] option').filter(function() { 
						return ($(this).text() == fish_type);
					}).prop('selected', true);
				}else if(data.status == 'failed'){
					$.toaster({settings:{timeout:10000,toast:{template:'<div class="alert alert-danger"><button data-dismiss="alert" class="close">×</button> '+data.message+'</div>'}}, message:''});
					$('.ajax_reponse').html(data.message);
				}else if(data.status == 'error'){
					$('#package-container tr:last').addClass('alert-danger');
					$('.ajax_reponse').html(data.message);
					$.toaster({settings:{timeout:10000,toast:{template:'<div class="alert alert-danger"><button data-dismiss="alert" class="close">×</button> '+data.message+'</div>'}}, message:''});
					$('#package_form').find('.fish_code').focus().select();
				}
				hide_loader($('#panel_container'));
			}						
		});	
	});
});