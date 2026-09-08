<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- start: BREADCRUMB -->
<?php echo form_open('', 'class="form-horizontal" id="x_package_form"');?>
	<input type="hidden" name="carret_number" value="<?=$carret_data['carret_number']?>" />
    <input type="hidden" name="production_type" value="<?=$production_type?>" />
    <input type="hidden" name="sr_no" value="<?=$sr_no?>" />
    <input type="hidden" name="sale_id" value="<?=$sale_id?>" />
    <input type="hidden" name="mp_code" value="<?=$mp_code?>" />
	<div class="row">
		<div class="col-sm-10 col-sm-offset-1">
		   <table class="table">
			<thead>
				<tr>
					<th>Fish <span class="symbol required"></span></th>
					<th>Quantity <span class="symbol required"></span></th>
					<th>Carret Weight <span class="symbol required"></span></th>
                    <th>Box Weight</th>
					<th class="no-print">Action</th>
				</tr>                    
			</thead>
			<tbody id="x_package-container">
            	<?php
                $carret_items = !empty($carret_data['carret_items']) ? explode('|', $carret_data['carret_items']) : array();
				if(is_array($carret_items) && !empty($carret_items)){
					$t_carret_wt = $t_carret_qty = $t_box_wt = 0;
					foreach($carret_items as $carret){
						$items = !empty($carret) ? explode('/', $carret) : array();
						$t_carret_qty 	+= $items[2];
						$t_carret_wt 	+= $items[3];
						$t_box_wt 		+= $items[4];
				?>
			    <tr>
					<td>
					<input type="hidden" class="fish_name" name="package[fish_name][]" value="<?=$items[1]?>" />
                    <input type="hidden" class="fish_rate" name="package[fish_rate][]" value="<?=$items[5]?>" />
					<select class="form-control x_fish_code" name="package[fish_code][]" tabindex="10">
					  <option value="">Select Fish</option>
					  <?php
						if(!empty($all_fishes)){
							foreach($all_fishes as $row){
								$op_val = $row['code'];
								$op_text = $row['name'];
								$fish_rate = $row['fish_rate'];
								$fish_type = $row['type'];
								$fish_dhalta = $row['dhalta'];
								$selected = ($op_val == $items[0]) ? 'selected="selected"' : '';
					  ?>
					  <option <?=$selected?> value="<?=$op_val?>" data-fish_type="<?=$fish_type?>" data-fish_name="<?=$op_text?>" data-fish_rate="<?=$fish_rate?>" data-fish_dhalta="<?=$fish_dhalta?>"><?=$op_val.' ('.$op_text.')'?></option>
					  <?php }} ?>
                      
					</select>
					</td>
					<td><input type="text" value="<?=$items[2]?>" name="package[fish_qty][]" class="form-control strict_integer x_fish_qty" tabindex="11"></td>
					<td><input type="text" value="<?=$items[3]?>" onkeyup="checkDecimal(this);" name="package[fish_wt][]" class="form-control strict_numeric x_fish_wt" tabindex="12"></td>
                    <td><input type="text" value="<?=$items[4]?>" onkeyup="checkDecimal(this);" name="package[box_wt][]" class="form-control strict_numeric x_box_wt"></td>
					<td class="fish_action">
                    	<button type="button" class="btn btn-danger btn-sm x_remove_fish">
                        	<i class="fa fa-times"></i>
                        </button>
                    </td>
				</tr>
                <?php } } ?>
			</tbody>
            <?php
				$carret_data['box_number'];
				$disabled = '';
				$checked = '';
				$readonly = 'readonly="readonly"';
				if(!empty($carret_data['box_number'])){
					$disabled = 'disabled="disabled"';
					$checked = 'checked="checked"';
					$readonly = '';
				}
			?>
			<tfoot class="x_tbl_footer">
				<tr>
                  <td>
                        <select name="client_id" class="form-control x_client_id" <?=$disabled?>>
                          <option value="">Select Client</option>
                          <?php
                            if(!empty($all_clients)){
                                foreach($all_clients as $row){
                                    $op_val = $row['client_id'];
                                    $op_text = $row['client_name'].' - '.$row['client_mobile'];
									$selected = ($row['client_id'] == $carret_data['client_id']) ? 'selected="selected"' : '';
									
                          ?>
                          <option <?=$selected?> value="<?=$op_val?>"><?=$op_text?></option>
                          <?php }} ?>
                        </select>
                    </td>
                    <td>                        
						<div class="input-group">
                            <span class="input-group-addon">Box Number</span>
                            <input type="text" name="box_number" value="<?=$carret_data['box_number']?>" <?=$readonly?> class="form-control x_box_number disabled" placeholder="Box Number" data-title="Box Number" data-toggle="tooltip" autocomplete="off">
                            <span class="input-group-addon" style="background-color: #FFF; border: #FFF;"> 
                            	<input type="checkbox" <?=$checked?> name="make_box" class="x_make_box" value="Yes" /> 
                            </span>
                        </div>
					 </td>
					<td>
						<div class="input-group">
						  <span class="input-group-addon">Carret Weight</span>
						  <input type="hidden" name="carret_quantity" value="<?=set_value('carret_quantity', $t_carret_qty);?>" />
						  <input type="text" name="carret_weight" class="form-control" readonly="readonly" placeholder="Carret Weight" data-title="Carret Weight" data-toggle="tooltip" value="<?=set_value('carret_weight', $t_carret_wt);?>" />
						 </div>
					</td>
                    <td>
                        <div class="input-group">
						  <span class="input-group-addon">Box Weight</span>
						  <input type="text" name="box_weight" class="form-control strict_numeric" readonly="readonly" placeholder="Box Weight" onkeyup="checkDecimal(this);" data-title="Box Weight" data-toggle="tooltip" value="<?=set_value('box_weight', $t_box_wt);?>" />
						 </div>
					</td>
                    <td> <button type="button" class="btn btn-success btn-sm x_add_fish"> <i class="fa fa-plus"></i> </button> </td>
				</tr>    
			</tfoot>                
		</table>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12 ajax_reponse"></div>
	</div>
	<div class="row">
		<div class="col-sm-12 text-center">
            <?=fish_type_selector($carret_data['fish_type'])?>
			<button type="button" name="update_carret" class="btn btn-success text-left update_carret" tabindex="13">Update Carret</button>
            <?php /*?><input type="hidden" name="carret_box_number" value="<?=$carret_data['box_number'];?>" /><?php */?>
		</div>
	</div>
<?php echo form_close();?>
<table class="table hidden" id="x_asset-table">
   <tr>
		<td>
            <input type="hidden" class="fish_name" name="package[fish_name][]" value="" />
            <input type="hidden" class="fish_rate" name="package[fish_rate][]" value="" />
			<select class="form-control x_fish_code" name="package[fish_code][]">
			  <option value="">Select Fish</option>
			  <?php
				if(!empty($all_fishes)){
					foreach($all_fishes as $row){
						$op_val = $row['code'];
						$op_text = $row['name'];
						$fish_rate = $row['fish_rate'];
						$fish_type = $row['type'];
						$fish_dhalta = $row['dhalta'];
			  ?>
			  <option value="<?=$op_val?>" data-fish_type="<?=$fish_type?>" data-fish_name="<?=$op_text?>" data-fish_rate="<?=$fish_rate?>" data-fish_dhalta="<?=$fish_dhalta?>"><?=$op_val.' ('.$op_text.')'?></option>
			  <?php }} ?>
			</select>
		</td>
		<td><input type="text" name="package[fish_qty][]" class="form-control strict_integer x_fish_qty"></td>
		<td><input type="text" onkeyup="checkDecimal(this);" name="package[fish_wt][]" class="form-control strict_numeric x_fish_wt"></td>
        <td><input type="text" onkeyup="checkDecimal(this);" name="package[box_wt][]" class="form-control strict_numeric x_box_wt"></td>
		<td class="fish_action"><button type="button" class="btn btn-danger btn-sm x_remove_fish"> <i class="fa fa-times"></i> </button>
		</td>
	</tr>              
</table>

<script>
function x_insert_box_wt(){
	var $package_tr = $('tbody#x_package-container tr');
	var dhalta_of_first = 0;
	$package_tr.each(function(index, element) {
       	var tr 			= $(element);
	   	var fish_wt 	= tr.find(".x_fish_wt").val();
		var fish_dhalta = tr.find(".x_fish_code option:selected").data('fish_dhalta');
		if(isNaN(fish_wt) || fish_wt == "" || fish_wt == "undefined"){fish_wt = 0;}
		if(isNaN(fish_dhalta) || fish_dhalta == "" || fish_dhalta == "undefined"){fish_dhalta = 0;}else{ fish_dhalta = fish_dhalta/1000;}
		if(index == 0){
			dhalta_of_first = fish_dhalta;
		}
		if($package_tr.length > 1){
			fish_dhalta = dhalta_of_first;
		}
		tr.find('.x_box_wt').val(fish_wt-fish_dhalta);
    });
	x_calculate_carret_wt();
}

function x_calculate_carret_wt(){
	var carret_weight = fish_wt = 0;
	$('#x_package-container').find('.x_fish_wt').each(function(index, element) {
        fish_wt = parseFloat($(element).val());
		if(isNaN(fish_wt) || fish_wt == ""){fish_wt = parseFloat(0.00);}
		carret_weight += fish_wt;
    });
	$('.x_tbl_footer').find('input[name="carret_weight"]').val(carret_weight.toFixed(3));
	
	var t_box_wt = box_wt = 0;
	$('#x_package-container').find('.x_box_wt').each(function(index, element) {
		box_wt = parseFloat($(element).val());
		if(isNaN(box_wt) || box_wt == ""){box_wt = parseFloat(0);}
		t_box_wt += box_wt;
	});
	$('.x_tbl_footer').find('input[name="box_weight"]').val(t_box_wt.toFixed(3));
}

function x_calculate_carret_qty(){
	var carret_quantity = fish_qty = 0;
	$('#x_package-container').find('.x_fish_qty').each(function(index, element) {
        fish_qty = parseInt($(element).val());
		if(isNaN(fish_qty) || fish_qty == ""){fish_qty = parseInt(0);}
		carret_quantity += fish_qty;
    });
	$('.x_tbl_footer').find('input[name="carret_quantity"]').val(carret_quantity);
}

$(document).ready(function(e) {
	$(document).off('click', '.x_fish_code');
	$(document).off('click', '.x_fish_qty');
	$(document).off('click', '.x_fish_wt');
	$(document).off('click', '.x_box_wt');
	$(document).off('click', '.x_remove_fish');
	var fs_remove_btn = '<button type="button" class="btn btn-danger btn-sm x_remove_fish"> <i class="fa fa-times"></i> </button>';
	
	//Save row
	$('.x_add_fish').on('click', function(){
		var pack_cont = $('#x_package-container');
		var this_tr = $(this).closest('tr');
		var fishes = [];
		pack_cont.find('tr').each(function(index, element) {
            var package_tr = $(element);
			fishes[index] = package_tr.find('.x_fish_code option:selected').val();
        });		
		var $asset_table = $('#x_asset-table tr').clone();		
		$asset_table.find('.x_fish_code > option').each(function(index, element) {
            //alert($(this).text() + ' ' + $(this).val());
			for(var i = 0; i< fishes.length; i++){
				if($(this).val() == fishes[i]){
					$(this).remove();
				}
			}
        });
		var tr_last = $('#x_package-container tr:last');
		//var box_number = $('.box_number').val();
		var fish_code = tr_last.find('.x_fish_code').val();
		var fish_qty = tr_last.find('.x_fish_qty').val();
		var fish_wt = tr_last.find('.x_fish_wt').val();
		
		if(fish_code && fish_wt > 0){
			tr_last.removeClass('alert-danger');
			this_tr.find('.fish_action').html(fs_remove_btn);
			pack_cont.append($asset_table);
			$('#x_package-container tr:last').find(".x_fish_code").focus();
		}else{
			alert('Please insert valid required data first.');
			tr_last.removeClass('alert-success').addClass('alert-danger');
		}
	});
	
	$(document).on('click', '.x_remove_fish', function(){
		var $this = $(this);
		var conf = confirm("Are you sure you want to remove fish?");
		if(conf){
			$this.closest('tr').remove();
			x_calculate_carret_wt();
			x_calculate_carret_qty();
		}
	});
	
	$(document).on('change', '.x_fish_code', function(){		
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
	
	$(document).on('change', '.x_fish_qty', function(){
		x_calculate_carret_qty();
	});
	
	$(document).on('change', '.x_fish_wt', function(){
		if($('.x_make_box').prop('checked')){
			x_insert_box_wt();
		}
		x_calculate_carret_wt();
	});
	
	$(document).on('change', '.x_box_wt', function(){
		x_calculate_carret_wt();
	});
	
	$('.x_make_box').on('change', function(){
		var $this = $(this);
		if($this.prop('checked') === true){
			//make box checked
			$('.x_box_number').prop('readonly', false).removeClass('disabled');
			$('.x_client_name').val("");
			$('.x_client_mobile').val("");
			$('.x_client_email').val("");
			$('.x_client_id').val(null).prop('disabled', true);
		}else{
			//make box unchecked
			$('.x_box_number')/*.val('')*/.prop('readonly', true).addClass('disabled');
			$('.x_client_id').prop('disabled', false);
		}
	});
	
	$('.update_carret').on('click', function(){
		show_loader($('#x_package_form'));
		var tr 			= $('#x_package-container tr');
		var form_data 	= $('#x_package_form').serialize();
		
		$.ajax({
			type: "POST",
			url: site_url+"production/ajax_update_carret",
			data: form_data,
			beforeSend: function(){$('.ajax_reponse').html('');},
			error: function (xhr, ajaxOptions, thrownError){
				hide_loader($('#panel_container'));
				alert('Response - '+ xhr.status+':  '+thrownError+' Please contact to development department.');
			},
			success: function( data ){
				if(data.status == 'success'){
					$('#'+data.sr_no).html(data.tr);
					$('#common-modal-asset').find('button.close').trigger('click');
				}
				hide_loader($('#x_package_form'));
				$.toaster({settings:{timeout:10000,toast:{template:'<div class="alert alert-'+data.status+'"><button data-dismiss="alert" class="close">×</button> '+data.message+'</div>'}}, message:''});
			}						
		});
	});
});

</script>
