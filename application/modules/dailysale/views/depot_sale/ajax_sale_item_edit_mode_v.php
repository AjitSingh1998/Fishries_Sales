<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- start: BREADCRUMB -->
<?php echo form_open('', 'class="form-horizontal" id="x_package_form"');?>
	<?php 
	$readonly = '';
	$disabled_class = '';
	if($item_data['carret_number'] > 0){
		$readonly = 'readonly="readonly"';
		$disabled_class = 'disabled';
	}
	?>
	<input type="hidden" name="carret_number" value="<?=$carret_number?>" />
    <input type="hidden" name="sale_id" value="<?=$sale_id?>" />
    <input type="hidden" name="sale_item_id" value="<?=$sale_item_id?>" />
	<div class="row">
		<div class="col-sm-10 col-sm-offset-1">
		   <table class="table">
			<thead>
				<tr>
                <th>Carret</th>
                <th style="width: 200px;">Fish</th>
                <th>Quantity</th>
                <th>Weight</th>
                <th>Rate</th>
                <th>Amount</th>
                <th>Remark</th>
                <th style="width: 150px;">Stock Type</th>
              </tr>                   
			</thead>
			<tbody id="x_package-container">
            	<tr id="tr_sale_item">
                    <td><?=$item_data['carret_number']?></td>
                    <td>
                      <?php if($item_data['carret_number'] > 0){ ?>
                      <input type="hidden" name="fish_code" value="<?=$item_data['fish_code']?>" />
                      <input type="text" name="fish_name" value="<?=$item_data['fish_name']?>" class="form-control <?=$disabled_class?>" <?=$readonly?> />
                      <?php }else{ ?>
                      <select class="form-control x_fish_code" name="fish_code">
                        <option value="">Fish</option>
                        <?php
                        if(!empty($all_fishes)){
                            foreach($all_fishes as $row){
								$op_val = $row['code'];
                                $op_text = $row['code'] .' - '. $row['name'];
								$selected = ($op_val == $item_data['fish_code']) ? 'selected="selected"' : '';
                        ?>
                        <option <?=$selected?> data-fish_rate="<?=$row['fish_rate']?>" value="<?=$op_val?>"><?=$op_text?></option>
                        <?php }} ?>
                      </select>
                      <?php } ?>
                    </td>
                    <td><input type="text" name="fish_quantity" value="<?=$item_data['fish_quantity']?>" class="form-control strict_integer x_fish_quantity <?=$disabled_class?>" <?=$readonly?> /></td>
                    <td><input type="text" name="fish_weight" value="<?=$item_data['fish_weight']?>" maxlength="5" class="form-control strict_numeric x_fish_weight <?=$disabled_class?>" onkeyup="checkDecimal(this);" <?=$readonly?> /></td>
                    <td><input type="text" name="fish_rate" value="<?=$item_data['fish_rate']?>" maxlength="5" class="form-control strict_numeric x_fish_rate" onkeyup="checkDecimal(this);" /></td>
                    <td><input type="text" name="total_amount" value="<?=$item_data['total_amount']?>" class="form-control x_total_amount" readonly="readonly"></td>
                    <td><input type="text" name="remark" value="<?=$item_data['remark']?>" class="form-control x_remark"></td>
                    <td>
                    	<?php if($item_data['carret_number'] > 0){ ?>
                        <input type="hidden" name="stock_type" value="<?=$item_data['stock_type']?>" />
                        <input type="text" value="<?=$item_data['stock_type']?> Stock" class="form-control <?=$disabled_class?>" <?=$readonly?> />
                        <?php }else{ ?>
                        <select name="stock_type" class="form-control x_stock_type">
                        	<option value="">Stock</option>
                            <option value="Bachat" <?php if($item_data['stock_type'] == "Bachat"){ echo 'selected="selected"';} ?> >Bachat Stock</option>
                            <option value="Opening" <?php if($item_data['stock_type'] == "Opening"){ echo 'selected="selected"';} ?> >Opening Stock</option>
                        </select>
                        <?php } ?>
                    </td>
                </tr>
			</tbody>               
		</table>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12 ajax_reponse"></div>
	</div>
	<div class="row">
		<div class="col-sm-12 text-center">
			<button type="button" name="update_carret" class="btn btn-success text-left update_item">Update</button>
		</div>
	</div>
<?php echo form_close();?>

<script>

	$('.x_fish_weight').on('change', function(){
		var x_fish_weight = parseFloat($(this).val());
		var x_fish_rate   = parseFloat($('.x_fish_rate').val());
	
		if(isNaN(x_fish_rate)){x_fish_rate = 0.00;}
		if(isNaN(x_fish_weight)){x_fish_weight = 0.00;}
		
		$('.x_total_amount').val(parseFloat(x_fish_rate*x_fish_weight).toFixed(2));
	});
	
	$('.x_fish_rate').on('change', function(){
		var x_fish_rate 	= parseFloat($(this).val());
		var x_fish_weight 	= parseFloat($('.x_fish_weight').val());
		
		if(isNaN(x_fish_rate)){x_fish_rate = 0.00;}
		if(isNaN(x_fish_weight)){x_fish_weight = 0.00;}
		
		$('.x_total_amount').val(parseFloat(x_fish_rate*x_fish_weight).toFixed(2));
	});
	
	$('.x_fish_code').on('change', function(){
		var $this = $(this);
		var tr = $this.closest('tr');
		var x_fish_rate = $(this).find("option:selected").data('fish_rate');
		console.log(x_fish_rate);
		tr.find('.x_fish_rate').val(x_fish_rate);
		$('.x_fish_rate').trigger('change');
	});
	
	$('.update_item').on('click', function(){
		show_loader($('#x_package_form'));
		var tr 			= $('#x_package-container tr');
		var form_data 	= $('#x_package_form').serialize();
		$(this).prop('disabled', true);
		$.ajax({
			type: "POST",
			url: site_url+"dailysale/ajax_update_dp_item",
			data: form_data,
			beforeSend: function(){$('.ajax_reponse').html('');},
			error: function (xhr, ajaxOptions, thrownError){
				hide_loader($('#panel_container'));
				alert('Response - '+ xhr.status+':  '+thrownError+' Please contact to development department.');
			},
			success: function( data ){
				if(data.status == 'success'){
					setTimeout(function(){window.location.href = data.redirect_url}, 3000);
					//$('#'+data.sr_no).html(data.tr);
					//$('#common-modal-asset').find('button.close').trigger('click');
				}
				setTimeout(function(){hide_loader($('#x_package_form'))}, 2000);
				$.toaster({settings:{timeout:10000,toast:{template:'<div class="alert alert-'+data.status+'"><button data-dismiss="alert" class="close">×</button> '+data.message+'</div>'}}, message:''});
			}						
		});
	});
</script>
