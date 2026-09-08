<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- start: BREADCRUMB -->
<div class="container-fluid container-fullw">
  <div class="row">
    <div class="col-sm-10 col-sm-offset-1">
      <div class="panel panel-white" id="panel_container">
        <div class="panel-heading border-light light-bg panel-sale">
        	<h3 class="text-center">
            <a href="<?=$go_back_url?>" class="" data-toggle="tooltip" data-title="Go Back"><i class="fa fa-arrow-circle-left"></i></a>
            <?=$page_title?>
            <?php if($action_mode == 'edit'){ ?>
            <button type="button" class="btn btn-warning btn-xs point_edit_mode" data-url="<?=site_url('dailysale/ajax_point_edit_mode/'.$sale_id)?>">Edit</button>
            <?php } ?>
          </h3>
          <?php if($sale_id){ ?>
          	<input type="hidden" id="client_id" value="<?=$sale_data['client_id']?>" />
          	<input type="hidden" id="market_id" value="<?=$sale_data['market_id']?>" />
          	<div class="text-center">
			<?=$sale_data['market_name']?> / <?=$sale_data['client_name']?> <?=($sale_data['client_mobile']) ? '('.$sale_data['client_mobile'].')' : '' ?> / <?=$sale_data['invoice_number']?> / <?=get_datetime('d-m-Y h:i a', $sale_data['sale_date'])?>
             &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
             <button type="button" class="btn btn-info btn-xs open_aside_modal" data-style="bottom" data-toggle="tooltip" data-title="Import Excel Data" data-url="<?=site_url('excel_import/import/point_sale/'.$sale_id)?>"> 
                <i class="fa fa-file-excel-o"></i> Import
             </button>
            </div>
          		  
		  <?php } ?>
          
		  <?php echo form_open('dailysale/point/'.$action_mode, 'class="form-horizontal point_sale_form" id="point_sale_form"');?>
          <input type="hidden" id="action_mode" name="action_mode" value="<?=$action_mode?>" />
          <input type="hidden" id="sale_id" name="sale_id" value="<?=$sale_id?>" />
          <input type="hidden" id="mp_code" name="mp_code" value="<?=$sale_data['mp_code']?>" />
          <input type="hidden" id="sale_date" value="<?=get_datetime('d/m/Y h:i a', $sale_data['sale_date'])?>">
          <div class="row">
            <div class="col-sm-8 ajax-response"></div>
          </div>
          <?php if(!$sale_id && $action_mode == 'add'){ ?>
          <div class="form-group">
            <div class="col-sm-3">
              <select class="form-control input-sm market_id" id="market_id" name="market_id">
                <option value="" data-code="">Select Point</option>
                <?php
				if(!empty($all_points)){
					foreach($all_points as $row){
						$op_val = $row['ID'];
						$op_text = $row['name'];
						$op_code = $row['code'];
				?>
                <option value="<?=$op_val?>" data-code="<?=$op_code?>"><?=$op_text?></option>
                <?php }} ?>
              </select>
            </div>
            <?php /*?><div class="col-sm-4">
              <input type="text" name="invoice_number" placeholder="Invoice No." class="form-control strict_integer input-sm" value="<?=$sale_data['invoice_number']?>" <?=$disabled_input?>>
            </div><?php */?>
            <div class="col-sm-3">
              <input type="text" name="sale_date" autocomplete="off" class="form-control input-sm <?=$datepicker_class?>" placeholder="dd/mm/yyyy hh:mm am/pm" />
            </div>
            <div class="col-sm-3">
              	<select class="form-control input-sm" name="customer_type">
                	<option value="">Customer Type</option>
                    <option value="regular">Regular</option>
                    <option value="onetime">One Time</option>
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
              	<input type="text" name="customer_code" class="form-control input-sm customer" id="customer_code" autocomplete="off"  />
              	<span class="edit-icon change_customer"><i class="fa fa-pencil"></i></span>
              </div>
            </div>
            <div class="col-sm-2 for_new">
              <label class="control-label bold">Customer Name <span class="symbol required"></span></label>
              <div class="editable-input">
              	<input type="text" name="customer_name" class="form-control input-sm customer" id="customer_name" maxlength="30" autocomplete="off"  />
              	<span class="edit-icon change_customer"><i class="fa fa-pencil"></i></span>
              </div>
            </div>
            <div class="col-sm-2 for_new">
              <label class="control-label bold">Customer Mobile</label>
              <div class="editable-input">
              	<input type="text" name="customer_mobile" class="form-control input-sm strict_integer customer" id="customer_mobile" maxlength="12" autocomplete="off"  />
              	<span class="edit-icon change_customer"><i class="fa fa-pencil"></i></span>
              </div>
            </div>
            <div class="col-sm-3 for_new">
              <label class="control-label bold">Customer Email</label>
              <div class="editable-input">
              	<input type="text" class="form-control input-sm" name="customer_email" id="customer_email" autocomplete="off"  />
              	<span class="edit-icon change_customer"><i class="fa fa-pencil"></i></span>
              </div>
            </div>
          </div>
          <div class="form-group">
          	<div class="col-sm-8">
            	<label class="control-label bold">Remark</label>
                <textarea name="remark" class="form-control"></textarea>
          	</div>
            <div class="col-sm-2 margin-top-15"> <br />
              <button type="button" class="btn btn-primary save_point_sale_data">Save</button>
              &nbsp; </div>
          </div>
          <?php } ?>
          <?php echo form_close();?>
        </div>
        <?php if($sale_id){ ?>
        <div class="panel-body" id="panel-body-container">
          <table class="table listinge_header" width="100%" id="header_table">
            <thead>
              <tr>
                <th>S.No.</th>
                <th>Fish</th>
                <th>Quantity</th>
                <th>Weight</th>
                <th>Rate</th>
                <th>Amount</th>
                <th>Remark</th>
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
                <td><input type="text" readonly="readonly" value="<?=$item['fish_rate']?>" class="form-control disabled input-sm si_rt" /></td>
                <td><input type="text" readonly="readonly" value="<?=$item['total_amount']?>" class="form-control disabled input-sm si_amt" /></td>
                <td><?=$item['remark']?></td>
                <?php if($action_mode == 'edit'){ ?>
                <td><a href="javascript:void(0);" data-sale_id="<?=$item['sale_id']?>" data-sale_item_id="<?=$item['id']?>" 
                  	 class="btn btn-danger btn-sm delete_sale_item"> <i class="fa fa-trash"></i> </a></td>
                <?php } ?>
              </tr>
              <?php $i++; }} ?>
            </tbody>
            <tfoot>
                <?php if($action_mode == 'edit'){ ?>
                <form id="sale_item_form">
                  <input type="hidden" name="stock_type" id="stock_type" value="Current" />
                  <tr id="tr_sale_item">
                    <td></td>
                    <td><select class="form-control fish_code" id="fish_code" name="fish_code">
                        <option value="">Fish</option>
                        <?php
                        if(!empty($all_fishes)){
                            foreach($all_fishes as $row){
                                $op_val = $row['code'].'___'.$row['fish_rate'];
                                $op_text = $row['code'] .' - '. $row['name'];
                        ?>
                        <option value="<?=$op_val?>"><?=$op_text?></option>
                        <?php }} ?>
                      </select>
                    </td>
                    <td><input type="text" name="fish_quantity" id="fish_quantity" class="form-control strict_integer fish_quantity"></td>
                    <td><input type="text" onkeyup="checkDecimal(this);" name="fish_weight" maxlength="5" id="fish_weight" class="form-control strict_numeric fish_weight"></td>
                    <td><input type="text" onkeyup="checkDecimal(this);" name="fish_rate" maxlength="5" id="fish_rate" class="form-control strict_numeric fish_rate"></td>
                    <td><input type="text" name="total_amount" id="total_amount" class="form-control total_amount" readonly="readonly"></td>
                    <td><input type="text" name="remark" id="remark" class="form-control remark"></td>
                    <td><button type="button" class="btn btn-success btn-sm save_point_item" data-toggle="tooltip" data-title="Save Item"> <i class="fa fa-save"></i></button></td>
                  </tr>
                </form>
                <?php } ?>
                <tr class="tr-bg">
                  <td></td>
                  <td>Sub Total</td>
                  <td><input type="text" readonly="readonly" value="<?=$sale_data['total_qty']?>" class="form-control disabled input-sm" name="total_qty" /></td>
                  <td><input type="text" readonly="readonly" value="0.00" class="form-control disabled input-sm" name="total_wt" /></td>
                  <td></td>
                  <td><input type="text" readonly="readonly" value="0.00" class="form-control disabled input-sm" name="sub_total" /></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr class="tr-bg">
                  <td></td>
                  <td>Ice</td>
                  <td></td>
                  <td><input type="text" value="<?=$sale_data['ice_weight']?>" class="form-control input-sm strict_numeric <?=$extra_class?>" onkeyup="checkDecimal(this);" name="ice_weight" /></td>
                  <td><input type="text" value="<?=$sale_data['ice_rate']?>" class="form-control input-sm strict_numeric <?=$extra_class?>" onkeyup="checkDecimal(this);" name="ice_rate" /></td>
                  <td><input type="text" value="<?=$sale_data['ice_amount']?>" readonly="readonly" class="form-control disabled input-sm" name="ice_amount" /></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr class="tr-bg">
                  <td></td>
                  <td>Destroyed</td>
                  <td></td>
                  <td><input type="text" value="<?=$sale_data['destroyed_weight']?>" class="form-control input-sm strict_numeric <?=$extra_class?>" onkeyup="checkDecimal(this);" name="destroyed_weight" /></td>
                  <td><input type="text" value="<?=$sale_data['destroyed_rate']?>" class="form-control input-sm strict_numeric <?=$extra_class?>" onkeyup="checkDecimal(this);" name="destroyed_rate" /></td>
                  <td><input type="text" value="<?=$sale_data['destroyed_amount']?>" readonly="readonly" class="form-control disabled input-sm" name="destroyed_amount" /></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr class="tr-bg">
                  <td></td>
                  <td colspan="2">Discount <small>(In %)</small></td>
                  <td></td>
                  <td><input type="text" value="<?=$sale_data['discount_perc']?>" class="form-control input-sm strict_numeric <?=$extra_class?>" onkeyup="checkDecimal(this);" name="discount_perc" /></td>
                  <td><input type="text" value="<?=$sale_data['discount_amount']?>" readonly="readonly" class="form-control disabled input-sm" name="discount_amount" /></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr class="tr-bg">
                  <td></td>
                  <th>Grand Total</th>
                  <td></td>
                  <td></td>
                  <td></td>
                  <th><input type="text" value="<?=$sale_data['grand_total']?>" readonly="readonly" class="form-control disabled input-sm" name="grand_total" /></th>
                  <td></td>
                  <td></td>
                </tr>
                <tr class="tr-bg">
                  <td></td>
                  <th colspan="3">Old Remaining Amount</th>
                  <td></td>
                  <th><input type="text" name="old_remaining" value="<?=number_format($old_remaining_amt,2,'.','')?>" readonly="readonly" class="form-control disabled input-sm" /></th>
                  <td></td>
                  <td></td>
                </tr>
                <tr class="tr-bg">
                  <td></td>
                  <th colspan="3">Grand Total + Old Remaining</th>
                  <td></td>
                  <th><input type="text" id="grand_n_old" class="form-control disabled input-sm" value="0.00" readonly="readonly" /></th>
                  <td></td>
                  <td></td>
                </tr>
                <tr class="tr-bg">
                  <td></td>
                  <th colspan="2">Received Amount</th>
                  <td></td>
                  <td></td>
                  <th colspan="3">
                  <input style="width:120px" type="text" value="<?=$sale_data['received_amt']?>" class="form-control input-sm strict_numeric <?=$extra_class?>" onkeyup="checkDecimal(this);" name="received_amt" />
                  <?php
					$by_remition = 0;
                    if(!empty($cash_received))
					{
						foreach($cash_received as $row)
						{ 
						$by_remition = $by_remition + $row['amount'];
					
					?>
                    <p> <?=$row['amount']?> rupees received by <?=$row['remition_by']?> on <?=get_datetime('d M Y, h:i a',$row['payment_date'])?></p>
                    <?php 
						} 
					}
					?>
                    <input type="hidden" value="<?=$by_remition?>" id="by_remition" />
                  </th>
                </tr>
                
                <tr class="tr-bg">
                  <td></td>
                  <th colspan="2">Remaining Amount</th>
                  <td></td>
                  <td></td>
                  <th><input type="text" value="0.00" readonly="readonly" class="form-control disabled input-sm" name="remaining_amt" /></th>
                  <td></td>
                  <td></td>
                </tr>
            </tfoot>
          </table>
          <?php if($action_mode == 'edit'){ ?>
          <div class="row">
          	<div class="col-sm-8 col-sm-offset-2 summary-response"></div>
            <div class="col-sm-12 text-center">
              <?php $btn_text = 'Save And Go Back'; if($action_mode=='edit'){$btn_text = 'Update And Go Back';} ?>
              <button type="button" class="btn btn-success btn-lg save_n_back"><?=$btn_text?></button>
            </div>
          </div>
          <?php } ?>
        </div>
        <?php } ?>
      </div>
    </div>
  </div>
</div>
