<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

    <h3 class="text-center">
    <?php /*?><a href="<?=$go_back_url?>" class="" data-toggle="tooltip" data-title="Go Back"><i class="fa fa-arrow-circle-left"></i></a><?php */?>
    <?=$page_title?>
  </h3>
  <?php echo form_open('', 'class="form-horizontal free_sale_form" id="free_sale_form"');?>
  <input type="hidden" id="action_mode" name="action_mode" value="<?=$action_mode?>" />
  <input type="hidden" id="free_sale_id" name="free_sale_id" value="<?=$free_sale_id?>" />
  <div class="form-group">
    <div class="col-sm-6 ajax-response"></div>
  </div>
  <div class="form-group">
    <div class="col-sm-3">
      <select class="form-control input-sm market_category" id="market_category" name="market_category">
        <option value="">Select Category</option>
        <?php
            if(!empty($market_category)){
                foreach($market_category as $row){
                    $op_val = $row['ID'];
                    $op_text = $row['name'];
					$selected = '';
					if($op_val == $sale_data['market_category']){
						$selected = 'selected="selected"';
					}else{
						$selected = '';
					}
        ?>
        <option <?=$selected?> value="<?=$op_val?>"><?=$op_text?></option>
        <?php }} ?>
      </select>
    </div>
    <div class="col-sm-4">
      <select class="form-control input-sm market_id" id="market_id" name="market_id">
      <?php
		if(!empty($market_places)){
			foreach($market_places as $row){
				$op_val = $row['ID'];
				$op_text = $row['name'];
				$selected = '';
				if($op_val == $sale_data['market_id']){
					$selected = 'selected="selected"';
				}else{
					$selected = '';
				}
        ?>
        <option <?=$selected?> value="<?=$op_val?>"><?=$op_text?></option>
        <?php }} ?>
      </select>
    </div>
    <div class="col-sm-3">
      <input type="text" name="free_sale_date" autocomplete="off" class="form-control input-sm <?=$datepicker_class?>" value="<?=get_datetime('d/m/Y h:i a', $sale_data['free_sale_date'])?>" />
    </div>
  </div>
  <div class="form-group">
    <?php /*?><div class="col-sm-3">
      <label class="control-label">Customer Type </label>
      <div>
        <label class="radio-inline">
          <input type="radio" value="registered" name="customer_type" checked="checked">
          Registered</label>
        <label class="radio-inline">
          <input type="radio" value="new" name="customer_type">
          New</label>
      </div>
    </div><?php */?>
    <div class="col-sm-3 for_registered">
      <input type="hidden" name="customer_type" value="registered" />
      <label class="control-label">Order By </label>
      <select class="form-control select-customer" name="customer_id">
      	<option value="<?=$sale_data['client_id']?>" selected="selected"><?=$sale_data['company_name']?></option>
      </select>
    </div>
    <?php /*?><div class="col-sm-2 for_new" style="display: none;">
      <label class="control-label">Customer Name<span class="symbol required"></span></label>
      <input type="text" class="form-control input-sm" name="customer_name" maxlength="30" />
    </div>
    <div class="col-sm-2 for_new" style="display: none;">
      <label class="control-label">Customer Mobile<span class="symbol required"></span></label>
      <input type="text" class="form-control input-sm" name="customer_mobile" maxlength="12" />
    </div>
    <div class="col-sm-2 for_new" style="display: none;">
      <label class="control-label">Customer Email</label>
      <input type="text" class="form-contro input-sml" name="customer_email" />
    </div><?php */?>
    
  </div>
  <div class="form-group">
    <div class="col-sm-9">
        <textarea name="remark" rows="2" class="form-control"><?=$sale_data['remark']?></textarea>
    </div>
    <div class="col-sm-3 margin-top-10">
      <button type="button" class="btn btn-success btn-sm save_free_sale_data">Update</button>&nbsp;
      <a href="<?=site_url('dailysale/free/edit/'.$free_sale_id)?>" class="btn btn-danger btn-sm">Cancel</a>
    </div>
  </div>
  <?php echo form_close();?>

<script>
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