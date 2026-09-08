<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- start: BREADCRUMB -->



  <h3 class="text-center">
    <a href="<?=$go_back_url?>" class="" data-toggle="tooltip" data-title="Go Back"><i class="fa fa-arrow-circle-left"></i></a>
    <?=$page_title?>
  </h3>
  <?php echo form_open('', 'class="form-horizontal depot_sale_form" id="depot_sale_form"');?>
  <input type="hidden" id="action_mode" name="action_mode" value="<?=$action_mode?>" />
  <input type="hidden" id="sale_id" name="sale_id" value="<?=$sale_id?>" />
  <input type="hidden" name="mp_code_old" value="<?=$sale_data['mp_code']?>" />
  <input type="hidden" id="mp_code" name="mp_code" value="<?=$sale_data['mp_code']?>" />
  <div class="form-group">
    <div class="col-sm-8 ajax-response"></div>
  </div>
  <div class="form-group">
    <div class="col-sm-3">
      <select class="form-control input-sm  market_id" id="market_id" name="market_id">
        <option value="">Select Depot</option>
        <?php
            if(!empty($all_depots)){
                foreach($all_depots as $row){
                    $op_val = $row['ID'];
                    $op_text = $row['name'];
					$op_code = $row['code'];
					$selected = '';
					if($op_val == $sale_data['market_id']){
						$selected = 'selected="selected"';
					}else{
						$selected = '';
					}
        ?>
        <option <?=$selected?> value="<?=$op_val?>" data-code="<?=$op_code?>"><?=$op_text?></option>
        <?php }} ?>
      </select>
    </div>
    <?php /*?>
	<div class="col-sm-4">
      <input type="text" name="invoice_number" placeholder="Invoice No." class="form-control strict_integer input-sm" value="<?=$sale_data['invoice_number']?>" />
    </div>
	<?php */?>
    <div class="col-sm-3">
      <input type="text" name="sale_date" autocomplete="off" class="form-control input-sm datetimepicker" value="<?=get_datetime('d/m/Y h:i a',$sale_data['sale_date'])?>" />
    </div>
    <div class="col-sm-3">
        <select class="form-control input-sm" name="customer_type">
            <option value="">Customer Type</option>
            <option value="regular" <?php if($sale_data['client_id'] > 0){ echo 'selected="selected"';} ?>>Regular</option>
            <option value="onetime" <?php if($sale_data['client_id'] == 0){ echo 'selected="selected"';} ?>>One Time</option>
        </select>
    </div>
  </div>
  <div class="form-group">
	<?php /*?><div class="col-sm-3 for_registered">
      <label class="control-label bold">Select Customer </label>
      <select class="form-control select-customer" name="customer_id">
      </select>
    </div><?php */?>
    
    <div class="col-sm-2 for_new">
      <label class="control-label bold">Customer Code</label>
      <div class="editable-input">
        <input type="text" name="customer_code" readonly="readonly" class="form-control input-sm customer disabled" id="customer_code" value="<?=$sale_data['client_id']?>" autocomplete="off" />
        <span class="edit-icon change_customer"><i class="fa fa-pencil"></i></span>
      </div>
    </div>
    <div class="col-sm-2 for_new">
      <label class="control-label bold">Customer Name <span class="symbol required"></span></label>
      <div class="editable-input">
        <input type="text" name="customer_name" readonly="readonly" class="form-control input-sm customer disabled" id="customer_name" maxlength="50" value="<?=$sale_data['client_name']?>" autocomplete="off" />
        <span class="edit-icon change_customer"><i class="fa fa-pencil"></i></span>
      </div>
    </div>
    <div class="col-sm-2 for_new">
      <label class="control-label bold">Customer Mobile</label>
      <div class="editable-input">
        <input type="text" name="customer_mobile" readonly="readonly" class="form-control input-sm strict_integer customer disabled" id="customer_mobile" maxlength="30" value="<?=$sale_data['client_mobile']?>" autocomplete="off" />
        <span class="edit-icon change_customer"><i class="fa fa-pencil"></i></span>
      </div>
    </div>
    <div class="col-sm-3 for_new">
      <label class="control-label bold">Customer Email</label>
      <div class="editable-input">
        <input type="text" class="form-control input-sm disabled" readonly="readonly" name="customer_email" id="customer_email" value="<?=$sale_data['client_email']?>" autocomplete="off" />
        <span class="edit-icon change_customer"><i class="fa fa-pencil"></i></span>
      </div>
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-8">
        <label class="control-label">Remark</label>
        <textarea name="remark" class="form-control"><?=$sale_data['remark']?></textarea>
    </div>
    <div class="col-sm-3" style="margin-top: 15px"> <br />
      <button type="button" class="btn btn-success save_depot_sale_data">Update</button>&nbsp;
      <a href="<?=site_url('dailysale/depot/edit/'.$sale_id)?>" class="btn btn-danger">Cancel</a>
    </div>
  </div>
  <?php echo form_close();?>


<script>

var datatosend = {};
datatosend[csrf_token_name] = csrf_token_value;
$('.customer').typeahead({
	//fitToElement: true,
	delay: 3,
	source: function (query, process){
				datatosend['search_type'] = $(this.$element[0]).attr('id');
				datatosend['search_key'] = query;
				return $.post(site_url + "dailysale/typeahead_get_customers/1", datatosend, function (data) {
					return process(data.result);
				});
			},
	afterSelect: function(item){
		$('#customer_code').val(item.code).prop('readonly', true).addClass('disabled');
		$('#customer_name').val(item.company_name).prop('readonly', true).addClass('disabled');
		$('#customer_mobile').val(item.contact_number).prop('readonly', true).addClass('disabled');
		$('#customer_email').val(item.email).prop('readonly', true).addClass('disabled');	
								
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
</script>