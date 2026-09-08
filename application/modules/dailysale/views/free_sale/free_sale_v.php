<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- start: BREADCRUMB -->
<style>
.tr-bg {
	background-color: #5b9bd121 !important;
}
</style>
<div class="container-fluid container-fullw">
  <div class="row">
    <div class="col-sm-10 col-sm-offset-1">
      <div class="panel panel-white" id="panel_container">
        <div class="panel-heading border-light light-bg">
        	<h3 class="text-center">
            <a href="<?=$go_back_url?>" class="" data-toggle="tooltip" data-title="Go Back"><i class="fa fa-arrow-circle-left"></i></a>
            <?=$page_title?>
            <?php if($action_mode == "edit"){ ?>
            <button type="button" class="btn btn-warning btn-xs fs_edit_mode" data-url="<?=site_url('dailysale/ajax_fs_edit_mode/'.$free_sale_id)?>">Edit</button>
            <?php } ?>
          </h3>
		  <?php echo form_open('dailysale/free/'.$action_mode, 'class="form-horizontal free_sale_form" id="free_sale_form"');?>
          <input type="hidden" id="action_mode" name="action_mode" value="<?=@$action_mode?>" />
          <input type="hidden" id="free_sale_id" name="free_sale_id" value="<?=@$free_sale_id?>" />
          
          <div class="form-group">
            <div class="col-sm-6 ajax-response"></div>
          </div>
          <?php if($free_sale_id){ ?>
          <div class="form-group">
            <div class="col-sm-4">
              <label class="col-sm-4 control-label bold">Category</label>
              <div class="col-sm-6">
                <input type="hidden" id="market_category" value="<?=$sale_data['market_category']?>" />
                <input type="text" class="form-control underline" value="<?=$sale_data['market_category_name']?>" <?=$disabled_input?>/>
              </div>
            </div>
            <div class="col-sm-4">
              <label class="col-sm-4 control-label bold">Point/Depot</label>
              <div class="col-sm-6">
                <input type="hidden" id="market_id" value="<?=$sale_data['market_id']?>" />
                <input type="text" class="form-control underline" value="<?=$sale_data['market_name']?>" <?=$disabled_input?>/>
              </div>
            </div>
            
            <div class="col-sm-4">
              <label class="col-sm-2 control-label bold">Date</label>
              <div class="col-sm-9">
                <input type="text" id="free_sale_date" class="form-control underline <?=$datepicker_class?>" value="<?=get_datetime('d/m/Y h:i a', $sale_data['free_sale_date'])?>" <?=$disabled_input?>>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="col-sm-4">
              <label class="col-sm-4 control-label bold">Order By </label>
              <div class="col-sm-6">
              	<input type="hidden" id="client_id" value="<?=$sale_data['client_id']?>" />
                <input type="text" class="form-control underline" value="<?=$sale_data['company_name']?>" <?=$disabled_input?>/>
              </div>
            </div>
            <div class="col-sm-4">
              <label class="col-sm-4 control-label bold">Mobile </label>
              <div class="col-sm-6">
                <input type="text" class="form-control underline" value="<?=$sale_data['contact_number']?>" <?=$disabled_input?> />
              </div>
            </div>
            <div class="col-sm-4">
              <label class="col-sm-2 control-label bold">Email</label>
              <div class="col-sm-9">
                <input type="text" class="form-control underline" value="<?=$sale_data['email']?>" <?=$disabled_input?>/>
              </div>
            </div>
          </div>
          <div class="form-group">
          	<div class="col-sm-12">
                <label class="col-sm-1 control-label bold">Remark</label>
                <div class="col-sm-8">
                    <textarea name="remark" rows="2" class="form-control" readonly="readonly" style="resize:none;"><?=$sale_data['remark']?></textarea>
                </div>
            </div>
          </div>
          <?php }else{ ?>
          <div class="form-group">
            <div class="col-sm-3">
              <select class="form-control input-sm market_category" id="market_category" name="market_category">
                <option value="">Select Category</option>
                <?php
					if(!empty($market_category)){
						foreach($market_category as $row){
							$op_val = $row['ID'];
							$op_text = $row['name'];
				?>
                <option value="<?=$op_val?>"><?=$op_text?></option>
                <?php }} ?>
              </select>
            </div>
            <div class="col-sm-4">
              <select class="form-control input-sm market_id" id="market_id" name="market_id" disabled="disabled">
              	<option>Select Market</option>
              </select>
            </div>
            <div class="col-sm-3">
              <input type="text" name="free_sale_date" autocomplete="off" class="form-control input-sm <?=$datepicker_class?>" placeholder="dd/mm/yyyy hh:mm am/pm" />
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
              </select>
            </div>
            <?php /*?><div class="col-sm-3 for_new" style="display: none;">
              <label class="control-label">Customer Name<span class="symbol required"></span></label>
              <input type="text" class="form-control input-sm" name="customer_name" maxlength="30" />
            </div>
            <div class="col-sm-3 for_new" style="display: none;">
              <label class="control-label">Customer Mobile<span class="symbol required"></span></label>
              <input type="text" class="form-control input-sm" name="customer_mobile" maxlength="12" />
            </div>
            <div class="col-sm-3 for_new" style="display: none;">
              <label class="control-label">Customer Email</label>
              <input type="text" class="form-contro input-sml" name="customer_email" />
            </div><?php */?>
          </div>
          <div class="form-group">
          	<div class="col-sm-10">
            	<textarea name="remark" rows="2" class="form-control" placeholder="Remark"></textarea>
            </div>
            <div class="col-sm-2">
              <button type="button" class="btn btn-primary save_free_sale_data">Save</button>
              &nbsp;
            </div>
          </div>
          <?php } ?>
          <?php echo form_close();?>
        </div>
        <?php if($free_sale_id){ ?>
        <div class="panel-body" id="panel-body-container">
          <table class="table listinge_header" width="100%" id="header_table">
            <thead>
              <tr>
                <th>S.No.</th>
                <th>Fish</th>
                <th>Quantity</th>
                <th>Weight</th>
                <th>Stock</th>
                <?php if($action_mode == 'add' || $action_mode == 'edit'){ ?>
                <th>Action</th>
                <?php } ?>
              </tr>
            </thead>
            <tbody class="tbody_sale_item">
              <?php
				if(!empty($sale_item)){
					$i = 1;
					foreach($sale_item as $item){
			  ?>
              <tr>
                <td><?=$i?></td>
                <td><?=$item['fish_name'].' ('.$item['fish_code'].')'?></td>
                <td><input type="text" readonly="readonly" value="<?=$item['fish_quantity']?>" class="form-control disabled input-sm si_qty" /></td>
                <td><input type="text" readonly="readonly" value="<?=$item['fish_weight']?>" class="form-control disabled input-sm si_wt" /></td>
                <td><?=$item['stock_type']?></td>
                <?php if($action_mode == 'add' || $action_mode == 'edit'){ ?>
                <td><a href="javascript:void(0);" data-free_sale_id="<?=$item['free_sale_id']?>" data-sale_item_id="<?=$item['id']?>" 
                  	 class="btn btn-danger btn-sm delete_sale_item"> <i class="fa fa-trash"></i> </a></td>
                <?php } ?>
              </tr>
              <?php $i++; }} ?>
            </tbody>
            <tfoot>
            	<?php if($action_mode == "edit"){ ?>
                <form id="freesale_item_form">
                  <tr id="tr_sale_item">
                    <td></td>
                    <td><select class="form-control fish_code" id="fish_code" name="fish_code">
                        <option value="">Fish</option>
                        <?php
                        if(!empty($all_fishes)){
                            foreach($all_fishes as $row){
                                $op_val = $row['code'];
                                $op_text = $row['name'];
                        ?>
                        <option value="<?=$op_val?>"><?=$op_val.' - '.$op_text?></option>
                        <?php }} ?>
                      </select>
                    </td>
                    <td><input type="text" name="fish_quantity" id="fish_quantity" class="form-control strict_integer fish_quantity"></td>
                    <td><input type="text" onkeyup="checkDecimal(this);" name="fish_weight" maxlength="5" id="fish_weight" class="form-control strict_numeric fish_weight"></td>
                    <td>
                    	<?php if($sale_data['market_category'] == 2){ ?>
                        <select name="stock_type" class="form-control" id="stock_type">
                            <option value="">Select Stock</option>
                            <option value="Bachat">Bachat Stock</option>
                        	<option value="Opening">Opening Stock</option>
                        </select>
                        <?php }else{ ?>
                        <input type="text" name="stock_type" id="stock_type" value="Current" class="form-controll disabled input-sm" readonly="readonly" />
                        <?php } ?>
                    </td>
                    <td><button type="button" class="btn btn-success btn-sm save_free_item" data-toggle="tooltip" data-title="Save Item"> <i class="fa fa-save"></i></button></td>
                  </tr>
                </form>
                <?php } ?>
                <tr class="tr-bg">
                  <td></td>
                  <td>Total</td>
                  <td><input type="text" readonly="readonly" value="<?=$sale_data['total_quantity']?>" class="form-control disabled input-sm" name="total_qty" /></td>
                  <td><input type="text" readonly="readonly" value="<?=$sale_data['total_weight']?>" class="form-control disabled input-sm" name="total_wt" /></td>
                  <td></td>
                  <td></td>
                </tr>
            </tfoot>
          </table>
        </div>
        <?php } ?>
      </div>
    </div>
  </div>
</div>
