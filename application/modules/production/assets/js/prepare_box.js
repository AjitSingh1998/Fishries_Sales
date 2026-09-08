function calculate_carret_wt(){
	//fish_wt
	var carret_weight = 0;
	$('#package-container').find('.fish_wt').each(function(index, element) {
        var fish_wt = parseFloat($(element).val());
		if(isNaN(fish_wt)){fish_wt = parseFloat(0.00);}
		carret_weight += fish_wt;
    });
	$('input[name="carret_weight"]').val(carret_weight);
}

function calculate_carret_qty(){
	//fish_wt
	var carret_quantity = 0;
	$('#package-container').find('.fish_qty').each(function(index, element) {
        var fish_qty = parseFloat($(element).val());
		if(isNaN(fish_qty)){fish_qty = parseFloat(0.00);}
		carret_quantity += fish_qty;
    });
	$('input[name="carret_quantity"]').val(carret_quantity);
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
		var $this = $(this);
		$this.closest('tr').remove();
		calculate_carret_wt();
		calculate_carret_qty();
	});
	
	$(document).on('change', '.fish_code', function(){
		var $this = $(this);
		var td = $this.closest('td');
		var fish_nc = $(this).find("option:selected").text();
		td.find('input[type="hidden"]').val(fish_nc);
	});
	
	$(document).on('change', '.fish_qty', function(){
		calculate_carret_qty();
	});
	
	$(document).on('change', '.fish_wt', function(){
		calculate_carret_wt();
	});
	
	$(document).on('click', '.save_carret', function(){
		//show_loader($('#panel_container'));
		var tr 			= $('#package-container tr');
		var form_data 	= $('#package_form').serialize();
		var s_no 		= $('#box_listing_container').children().length;	
		
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
					tr.each(function(index, element) {
                        var ele = $(element);
						ele.find('input').val('');
						ele.find('.remove_fish').trigger('click');
                    });
					$.toaster({settings:{timeout:10000,toast:{template:'<div class="alert alert-success"><button data-dismiss="alert" class="close">×</button> '+data.message+'</div>'}}, message:''});
					if(data.action_mode == 'add'){
						window.location.href = data.redirect_url;
					}
					$('#package-container tr:last option:eq(0)').prop('selected', true);
					$('#package-container tr:last').removeClass('alert-success').removeClass('alert-danger');
					$('#package-container tr:last select').focus();
					$('.ajax_reponse').html('');
					$(".panel-scroll").scrollTop( $( ".panel-scroll" ).prop( "scrollHeight" ) );
					$(".panel-scroll").perfectScrollbar('update');
					document.getElementById('package_form').reset();
				}else if(data.status == 'failed'){
					//if error in data insertion
					$.toaster({settings:{timeout:10000,toast:{template:'<div class="alert alert-danger"><button data-dismiss="alert" class="close">×</button> '+data.message+'</div>'}}, message:''});
				}else if(data.status == 'error'){
					//if formvalidatin error
					$('#package-container tr:last').addClass('alert-danger');
					$('.ajax_reponse').html(data.message);
					$('#package_form').find('.fish_code').focus().select();
				}
				hide_loader($('#panel_container'));
			}						
		});	
	});
});