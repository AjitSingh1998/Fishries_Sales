function insert_box_wt(){
	var $package_tr = $('tbody#package-container tr');
	var dhalta_of_first = 0;
	$package_tr.each(function(index, element) {
       	var tr 			= $(element);
	   	var fish_wt 	= tr.find(".fish_wt").val();
		var fish_dhalta = tr.find(".fish_code option:selected").data('fish_dhalta');
		if(isNaN(fish_wt) || fish_wt === "" || fish_wt === undefined){fish_wt = 0;} else { fish_wt = parseFloat(fish_wt); }
		if(isNaN(fish_dhalta) || fish_dhalta === "" || fish_dhalta === undefined){fish_dhalta = 0;} else { fish_dhalta = parseFloat(fish_dhalta)/1000;}
		if(index === 0){
			dhalta_of_first = fish_dhalta;
		}
		if($package_tr.length > 1){
			fish_dhalta = dhalta_of_first;
		}
		var calculated_box_wt = fish_wt - fish_dhalta;
		if(calculated_box_wt < 0) { calculated_box_wt = 0; }
		tr.find('.box_wt').val(calculated_box_wt.toFixed(3));
    });
	calculate_carret_wt();
}

function calculate_carret_wt(){
	var carret_weight = 0;
	var fish_wt = 0;
	$('#package-container').find('.fish_wt').each(function(index, element) {
        fish_wt = parseFloat($(element).val());
		if(isNaN(fish_wt)){fish_wt = 0.00;}
		carret_weight += fish_wt;
    });
	$('input[name="carret_weight"]').val(carret_weight.toFixed(3));
	
	var t_box_wt = 0;
	var box_wt = 0;
	$('#package-container').find('.box_wt').each(function(index, element) {
		box_wt = parseFloat($(element).val());
		if(isNaN(box_wt)){box_wt = 0.00;}
		t_box_wt += box_wt;
	});
	$('input[name="box_weight"]').val(t_box_wt.toFixed(3));
}

function calculate_carret_qty(){
	var carret_quantity = 0;
	var fish_qty = 0;
	$('#package-container').find('.fish_qty').each(function(index, element) {
        fish_qty = parseFloat($(element).val());
		if(isNaN(fish_qty)){fish_qty = 0;}
		carret_quantity += fish_qty;
    });
	$('input[name="carret_quantity"]').val(carret_quantity);
}

$(document).ready(function(e) {
	$(document).off('.delete_box');
	
	$('#expand_report_container').change(function() {
		var target = $(this).data('target');
		if($(this).prop('checked')){
			$(target).find('.panel-scroll').css("cssText", "width:100%; height: auto !important; overflow: visible !important;");
			$(target).find(".panel-scroll").perfectScrollbar('destroy');
		}else{
			$(target).find('.panel-scroll').css("cssText", "width:100%; height: 300px !important; overflow: hidden !important;");
			var panelScroll = $(target).find(".panel-scroll");
			if (panelScroll.length) {
				 panelScroll.perfectScrollbar({
				 	suppressScrollX : true
				 });
			}
		}
    });
	
	$(".p_date").on('change', function(){ 
		var p_date = $(this).val(); 
		$('input[name="packing_date"]').val(p_date); 
	});
	
	$(".p_market_id").on('change', function(){
		var p_market_id = $(this).val(); 
		$('input[name="market_id"]').val(p_market_id);
	});
	
	$(".p_depot_id").on('change', function(){
		var p_depot_id 	= $(this).val();
		var mp_code		= $(this).find('option:selected').data('code');
		$('input[name="depot_id"]').val(p_depot_id);
		$('input[name="mp_code"]').val(mp_code);
	});
	
	// Add new fish row
	var fs_remove_btn = '<button type="button" class="btn btn-danger btn-sm remove_fish"> <i class="fa fa-times"></i> </button>';
	$(document).off('click', '.add_fish').on('click', '.add_fish', function(){
		var pack_cont = $('#package-container');
		var this_tr = $(this).closest('tr');
		var fishes = [];
		pack_cont.find('tr').each(function(index, element) {
            var package_tr = $(element);
			fishes[index] = package_tr.find('.fish_code option:selected').val();
        });		
		var $asset_table = $('#asset-table tr').clone();		
		$asset_table.find('.fish_code > option').each(function(index, element) {
			for(var i = 0; i < fishes.length; i++){
				if($(this).val() === fishes[i] && $(this).val() !== ""){
					$(this).remove();
				}
			}
        });
		var tr = $('#package-container tr:last');
		var fish_code = tr.find('.fish_code').val();
		var fish_wt = parseFloat(tr.find('.fish_wt').val());
		
		if(fish_code && fish_wt > 0){
			this_tr.find('.fish_action').html(fs_remove_btn);
			tr.removeClass('alert-danger').addClass('alert-success');
			$('#package-container').append($asset_table);
			$('#package-container tr:last').find('.fish_code').focus();
		}else{
			alert('Please select a fish and enter valid carret weight first.');
			tr.removeClass('alert-success').addClass('alert-danger');
		}
	});
	
	$(document).off('click', '.remove_fish').on('click', '.remove_fish', function(){
		var conf = confirm("Are you sure to delete this item?");
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
		if(fish_type === "Rotten"){
			$('select[name="fish_type"]').val('Rotten');
		}else if(all_fish_type){
			$('select[name="fish_type"]').val(all_fish_type);
		}else{
			$('select[name="fish_type"]').val('Fresh');
		}
		insert_box_wt();
	});
	
	// Real-time calculation on typing
	$(document).on('keyup change blur input', '.fish_qty', function(){
		calculate_carret_qty();
	});
	
	$(document).on('keyup change blur input', '.fish_wt', function(){
		insert_box_wt();
	});
	
	$(document).on('keyup change blur input', '.box_wt', function(){
		calculate_carret_wt();
	});
	
	// Save Box button click
	$(document).off('click', '.save_box').on('click', '.save_box', function(e){
		e.preventDefault();
		
		// Ensure calculations are complete
		insert_box_wt();
		calculate_carret_wt();
		calculate_carret_qty();
		
		var box_number = $('input[name="box_number"]').val();
		if(!box_number || box_number.trim() === ""){
			alert('Please enter a Box Number in Box Summary.');
			$('input[name="box_number"]').focus();
			return false;
		}
		
		var first_fish = $('#package-container tr:first').find('.fish_code').val();
		var first_wt = parseFloat($('#package-container tr:first').find('.fish_wt').val());
		if(!first_fish){
			alert('Please select a Fish.');
			$('#package-container tr:first').find('.fish_code').focus();
			return false;
		}
		if(isNaN(first_wt) || first_wt <= 0){
			alert('Please enter a valid Carret Weight.');
			$('#package-container tr:first').find('.fish_wt').focus();
			return false;
		}
		
		show_loader($('#panel_container'));
		var form_data = $('#package_form').serialize();
		var s_no = $('#box_listing_container').children().length;	
		
		$.ajax({
			type: "POST",
			url: site_url+"dispatch/ajax_save_box/"+s_no,
			data: form_data,
			error: function (xhr, ajaxOptions, thrownError){
				hide_loader($('#panel_container'));
				alert('Response - '+ xhr.status+': '+thrownError+' Please contact development department.');
			},
			success: function( data ){
				if(typeof data === 'string'){
					try { data = JSON.parse(data); } catch(err){}
				}
				if(data.status === 'success'){
					$('#box_listing_container').append(data.data);
					$('#package-container').find('.alert-success').remove();
					$.toaster({settings:{timeout:10000,toast:{template:'<div class="alert alert-success"><button data-dismiss="alert" class="close">×</button> '+data.message+'</div>'}}, message:''});
					if(data.action_mode === 'add' && data.redirect_url){
						window.location.href = data.redirect_url;
						return;
					}
					// Reset the input fields
					$('#package-container tr:not(:first)').remove();
					var $firstRow = $('#package-container tr:first');
					$firstRow.find('.fish_code option:eq(0)').prop('selected', true);
					$firstRow.find('.fish_qty').val('');
					$firstRow.find('.fish_wt').val('');
					$firstRow.find('.box_wt').val('');
					$firstRow.find('.fish_action').html('<button type="button" class="btn btn-success btn-sm add_fish" data-toggle="tooltip" data-title="Add New Fish"><i class="fa fa-plus"></i></button>');
					$firstRow.removeClass('alert-success alert-danger');
					
					$('input[name="carret_weight"]').val('');
					$('input[name="box_weight"]').val('');
					$('input[name="carret_quantity"]').val('0');
					$('.ajax_reponse').html('');
					
					$(".panel-scroll").scrollTop( $( ".panel-scroll" ).prop( "scrollHeight" ) );
					if($(".panel-scroll").perfectScrollbar) {
						$(".panel-scroll").perfectScrollbar('update');
					}
					
					// Auto increment box number
					var split_number = parseInt(box_number.replace(/\D/g,''));
					var split_text = box_number.replace(new RegExp("[0-9]", "g"), "");
					if(!isNaN(split_number)){
						var new_box_number = parseInt(split_number + 1);
						$('input[name="box_number"]').val(split_text + "" + new_box_number);
					} else {
						$('input[name="box_number"]').val('');
					}
					$firstRow.find('.fish_code').focus();
					
				}else if(data.status === 'failed'){
					$.toaster({settings:{timeout:10000,toast:{template:'<div class="alert alert-danger"><button data-dismiss="alert" class="close">×</button> '+data.message+'</div>'}}, message:''});
					$('.ajax_reponse').html('<div class="alert alert-danger">'+data.message+'</div>');
				}else if(data.status === 'error'){
					$('#package-container tr:last').addClass('alert-danger');
					$('.ajax_reponse').html(data.message);
					$.toaster({settings:{timeout:10000,toast:{template:'<div class="alert alert-danger"><button data-dismiss="alert" class="close">×</button> '+data.message+'</div>'}}, message:''});
					$('#package_form').find('.fish_code').focus().select();
				}
				hide_loader($('#panel_container'));
			}						
		});	
	});
	
	// Delete Box
	$(document).off('click', '.delete_box').on('click', '.delete_box', function(){
		var box_number = $(this).data('box_number');
		var conf = confirm('Are you sure to delete box '+box_number+' ?');
		if(conf){
			var tr = $(this).closest('tr');
			if(box_number){
				var form_data = {'box_number': box_number};
				form_data[csrf_token_name] = csrf_token_value;
				$.ajax({
					type: "POST",
					url: site_url+"dispatch/ajax_delete_prepare_box",
					data: form_data,
					beforeSend: function(){show_loader($('#panel_container'));},
					error: function (xhr, ajaxOptions, thrownError) {
						alert('Response - '+ xhr.status+': '+thrownError+' Please contact development department.');
						hide_loader($('#panel_container'));
					},
					success: function( data ){
						if(typeof data === 'string'){
							try { data = JSON.parse(data); } catch(err){}
						}
						$('.ajax_reponse').html('');
						if(data.status === 'success'){
							tr.remove();
							var i = 1;
							$('tr.box_row').each(function(index, element) {
								var ele = $(element);
								ele.find('.sr_no').text(i);
								i++;
							});
							$.toaster({settings:{timeout:10000,toast:{template:'<div class="alert alert-success"><button data-dismiss="alert" class="close">×</button>'+data.message+'</div>'}}, message:''});
						}else if(data.status === 'failed'){
							$.toaster({settings:{timeout:10000,toast:{template:'<div class="alert alert-danger"><button data-dismiss="alert" class="close">×</button>'+data.message+'</div>'}}, message:''});
						}else{
							$('.ajax_reponse').html(data.message);				
						}
						setTimeout(function(){hide_loader($('#panel_container'));}, 500);
					}						
				});	
			}
		}
	});
	
	// Edit Box
	$(document).off('click', '.edit_box').on('click', '.edit_box', function(event){
		var $this = $(this);
		event.preventDefault();
		var box_number	= $(this).data('box_number');
		var sr_no 		= $(this).closest('tr.box_row').find('td.sr_no').text();
		var urll 		= site_url+'dispatch/ajax_box_edit_mode/'+box_number+'/'+sr_no;
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
});