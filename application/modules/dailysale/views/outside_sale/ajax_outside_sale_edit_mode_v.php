<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- start: BREADCRUMB -->
<h3 class="text-center">
    <a href="<?=$go_back_url?>" class="" data-toggle="tooltip" data-title="Go Back"><i class="fa fa-arrow-circle-left"></i></a>
    <?=$page_title?>
</h3>
<?php echo form_open('dailysale/outside/'.$action_mode, 'class="form-horizontal outside_sale_form" id="outside_sale_form"');?>
<input type="hidden" id="action_mode" name="action_mode" value="<?=$action_mode?>" />
<input type="hidden" id="sale_id" name="sale_id" value="<?=$sale_id?>" />
<input type="hidden" id="mp_code" name="mp_code" value="<?=$sale_data['mp_code']?>">
<div class="form-group">
    <div class="col-sm-8 ajax-response"></div>
</div>                
<div class="form-group">
    <div class="col-sm-2">
        <label class="control-label">Date <span class="symbol required"></span></label>
        <input type="text" name="sale_date" id="sale_date" autocomplete="off" class="form-control <?=$datepicker_class?>" value="<?=get_datetime('d/m/Y h:i a', $sale_data['sale_date'])?>" />
    </div>
    
    <div class="col-sm-2">
        <label class="control-label">DR No. <span class="symbol required"></span></label>
        <input type="text" class="form-control strict_integer" name="dr_number" id="dr_number" value="<?=$sale_data['dr_number']?>" />
    </div>
    
    <div class="col-sm-2">
        <label class="control-label">Market <span class="symbol required"></span></label>
        <select name="market_id" id="market_id" class="form-control">
            <option value="">Select Market</option>
            <?php
            if(!empty($outside_markets)){
                foreach($outside_markets as $row){
                    $op_val = $row['ID'];
                    $op_text = $row['name'];
                    $op_code = $row['code'];
					$selected = '';
					if($op_val == $sale_data['market_id']){
						$selected = 'selected="selected"';
					}
            ?>
            <option <?=$selected?> value="<?=$op_val?>" data-code="<?=$op_code?>"><?=$op_text?></option>
            <?php }} ?>
        </select>
    </div>
    
    <div class="col-sm-3">
        <label class="control-label">Select Customer <span class="symbol required"></span></label>
        <select name="client_id" id="client_id" class="form-control select-customer" data-c_type="outside">
            <option value="<?=$sale_data['client_id']?>"><?=$sale_data['company_name']?></option>
        </select>
    </div>
    
    <div class="col-sm-2">
        <label class="control-label">Commission <small>(Dhalta)</small> <span class="symbol required"></span></label>
        <input type="text" name="commission" id="commission" class="form-control strict_numeric" onkeyup="checkDecimal(this);" value="<?=$sale_data['commission']?>" />
    </div>
</div>
<div class="form-group">
    <div class="col-sm-12 text-center"> <br />
        <button type="button" class="btn btn-primary btn-sm update_outside_sale_data">Update</button>&nbsp;
        <a href="<?=site_url('dailysale/outside/edit/'.$sale_id)?>" class="btn btn-danger btn-sm">Cancel</a>
    </div>
</div>
<?php echo form_close();?>

<script>
$(document).ready(function(e) {
	$('.datetimepicker').datetimepicker({
		format: 'DD/MM/YYYY hh:mm a',
		sideBySide: true,
	});
	
    var customer_data = '';
	$('.select-customer').select2({
	  placeholder: "Select Customer",
	  ajax: {
		url: site_url+'dailysale/get_customers/2', //$market_type : Local Market = 1, Outside Market = 2
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
	
	$('#commission').on('change', function(){
		$('.box_checked').each(function(index, element) {
			var tr = $(element);
			calculate_total(tr);
		});
	});
	
	$('.update_outside_sale_data').on('click', function(){
		var datatosend = {};
		datatosend[csrf_token_name] = csrf_token_value;
		datatosend['action_mode'] 	= $('#action_mode').val();
		datatosend['sale_id'] 		= $('#sale_id').val();
		datatosend['sale_date'] 	= $('#sale_date').val();
		datatosend['dr_number'] 	= $('#dr_number').val();
		datatosend['market_id'] 	= $('#market_id').val();
		datatosend['mp_code'] 		= $('#mp_code').val();
		datatosend['client_id'] 	= $('#client_id').val();
		datatosend['commission'] 	= $('#commission').val();
		box_number 					= [];
		box_qty 					= [];
		gross_wt					= [];
		net_wt						= [];
		rate						= [];
		amount						= [];
		
		$.each($('.box_checked'), function(index, element) {
			var box_checked = $(element);			
			box_number.push(box_checked.find('.box_number').val());
			box_qty.push(box_checked.find('.box_qty').val());
			gross_wt.push(box_checked.find('.gross_wt').val());
			net_wt.push(box_checked.find('.net_wt').val());
			rate.push(box_checked.find('.rate').val());
			amount.push(box_checked.find('.amount').val());
		});
		datatosend['box_number'] 	= box_number;
		datatosend['box_qty'] 		= box_qty;
		datatosend['gross_wt'] 		= gross_wt;
		datatosend['net_wt'] 		= net_wt;
		datatosend['rate'] 			= rate;
		datatosend['amount'] 		= amount;
		
		show_loader($('#panel_container'));
		$.ajax({
			url : site_url+'dailysale/ajax_update_sale_data', 
			type : "POST",
			data : datatosend,
			success: function(response, status, xhr){
				if(status == "success"){
					var ct = xhr.getResponseHeader("content-type") || "";
					if (ct.indexOf('json') > -1) {
					  	if(response.status == 'success'){
							document.location.href = site_url+'dailysale/outside/edit/'+response.data;
						}else{
							hide_loader($('#panel_container'));
							$('.ajax-response').html(response.message);
						}
					}else{
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
</script>