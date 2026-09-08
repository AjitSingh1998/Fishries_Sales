<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- start: BREADCRUMB -->
<style>
input[type="text"].form-control:focus,select.form-control:focus,textarea.form-control:focus {
    border-color: #66afe9 !important;
    outline: 0 !important;
    -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,0.075), 0 0 8px rgba(102,175,233,0.6) !important;
    box-shadow: inset 0 1px 1px rgba(0,0,0,0.075), 0 0 8px rgba(102,175,233,0.6) !important;
}
table.table{
	margin-bottom: 0px !important;
}
tr:last-child td, tr:last-child th{
	border-bottom: none !important;
}
.tr-bg {
	background-color: #5b9bd121 !important;
}
</style>
<div class="container-fluid container-fullw">
  <div class="row">
    <div class="col-sm-12">
        <div class="panel panel-white" id="panel_container">
            <div class="panel-heading border-light light-bg panel-sale">
                <h3 class="text-center">
                    <a href="<?=$go_back_url?>" class="" data-toggle="tooltip" data-title="Go Back"><i class="fa fa-arrow-circle-left"></i></a>
                    <?=$page_title?>
                    <?php if($action_mode == "edit"){ ?>
                    <button type="button" class="btn btn-warning btn-xs outside_edit_mode" data-url="<?=site_url('dailysale/ajax_outside_edit_mode/'.$sale_id)?>">Edit</button>
                    <?php } ?>
                </h3>
                <?php echo form_open('dailysale/outside/'.$action_mode, 'class="form-horizontal outside_sale_form" id="outside_sale_form"');?>
                <input type="hidden" id="action_mode" name="action_mode" value="<?=$action_mode?>" />
                <input type="hidden" id="sale_id" name="sale_id" value="<?=$sale_id?>" />
                <input type="hidden" id="mp_code" name="mp_code" value="">
                <input type="hidden" id="old_commission">
                <div class="form-group">
                    <div class="col-sm-8 ajax-response"></div>
                </div>
                <?php if($sale_id){ ?>
                <div class="form-group">
                    <div class="col-sm-2">
                        <label class="control-label">Date</label>
                        <input type="text" id="sale_date" class="form-control disabled underline <?=$datepicker_class?>" value="<?=get_datetime('d/m/Y h:i a', $sale_data['sale_date'])?>" <?=$disabled_input?>>
                    </div>
                    <div class="col-sm-1">
                        <label class="control-label">DR No.</label>
                        <input type="text" class="form-control disabled underline" id="dr_number" value="<?=$sale_data['dr_number']?>" <?=$disabled_input?> />
                    </div>
                    <div class="col-sm-2">
                        <label class="control-label">Market </label>
                        <input type="hidden" id="market_id" value="<?=$sale_data['market_id']?>"  />
                        <input type="text" class="form-control disabled underline" value="<?=$sale_data['market_name']?>" <?=$disabled_input?> />
                    </div>
                    <div class="col-sm-2 nopadding">
                        <label class="control-label">Customer Name </label>
                        <input type="hidden" id="client_id" value="<?=$sale_data['client_id']?>"  />
                        <input type="text" class="form-control disabled underline" value="<?=$sale_data['company_name']?>" <?=$disabled_input?> />
                    </div>
                    <div class="col-sm-2 nopadding">
                        <label class="control-label">Commission <small>(Dhalta)</small></label>
                        <input type="text" id="commission" class="form-control disabled underline" value="<?=$sale_data['commission']?>" <?=$disabled_input?> />
                    </div>
                    <?php if($action_mode == 'edit'){ ?>
                    <div class="col-md-1">
                       <label class="control-label">Show All</label><br>
                       <div class="make-switch" data-on="primary" data-off="info">
                            <input type="checkbox" id="switch_btn" checked="checked">
                        </div>
                    </div>
					<div class="col-md-1">
                       <label class="control-label">Expand</label><br>
						<div class="inline-block">
							<input type="checkbox"  id="expand_report_container"  class="js-switch" data-target="#panel-body-container" />
						</div>
                    </div>
                    <?php } ?>
                </div>
                <?php }else{ ?>
                <div class="form-group">
                    <div class="col-sm-2">
                        <label class="control-label">Date <span class="symbol required"></span></label>
                        <input type="text" name="sale_date" autocomplete="off" class="form-control <?=$datepicker_class?>" placeholder="dd/mm/yyyy hh:mm am/pm" />
                    </div>
                    
                    <div class="col-sm-2">
                        <label class="control-label">DR No. <span class="symbol required"></span></label>
                        <select class="form-control dr_number" name="dr_number" data-toggle="tooltip" data-title="DR Number"></select>
                    </div>
                    
                    <div class="col-sm-2">
                        <label class="control-label">Market <span class="symbol required"></span></label>
                        <select name="market_id" id="market_id" class="form-control" disabled="disabled">
                            <option value="">Select Market</option>
                            <?php
                            /*if(!empty($outside_markets)){
                                foreach($outside_markets as $row){
                                    $op_val = $row['ID'];
                                    $op_text = $row['name'];
                                    $op_code = $row['code'];
                            ?>
                            <option value="<?=$op_val?>" data-code="<?=$op_code?>"><?=$op_text?></option>
                            <?php }}*/
							?>
                        </select>
                    </div>
                    
                    <div class="col-sm-3">
                        <label class="control-label">Select Customer <span class="symbol required"></span></label>
                        <select name="client_id" id="client_id" class="form-control" disabled="disabled">
                        	<option value="">Select Customer</option>
                        </select>
                    </div>
                    
                    <div class="col-sm-2">
                        <label class="control-label">Commission <small>(Dhalta)</small> <span class="symbol required"></span></label>
                        <input type="text" name="commission" class="form-control strict_numeric" onkeyup="checkDecimal(this);" />
                    </div>
                    
                    <div class="col-sm-1 margin-top-5"> <br />
                        <button type="button" class="btn btn-primary save_outside_sale_data">Save</button>&nbsp;
                    </div>
                </div>
                <?php } ?>
                <?php echo form_close();?>
            </div>
            <?php
            if($sale_id){
            echo form_open('dailysale/outside/'.$action_mode, 'class="form-horizontal" id="outsidesale_item_form"');
			?>
            <div class="panel-body" id="panel-body-container">
                <table class="table table-striped" width="100%" id="header_table">
                    <thead>
                        <tr>
                            <th style="width:45px;"><input type="checkbox" value="All" class="mark_all" /></th>
                            <th style="width:100px;">S.No.</th>
                            <th style="width:100px;">Box</th>
                            <th style="width:100px;">Box Qty</th>
                            <th style="width:100px;">Box WT</th>
                            <th style="width:700px;">
                            	<table class="table">
                                	<tr>
                                    	<th style="width:20%;">Fish</th>
                                        <th style="width:10%;">Item Qty</th>
                                        <th style="width:14%;">Item Wt</th>
                                        <th style="width:14%;">Gross WT</th>
                                        <th style="width:14%;">Net WT</th>
                                        <th style="width:14%;">Rate</th>
                                        <th style="width:14%;">Amount</th>
                                    </tr>
                                </table>
                            </th>
                        </tr>                    
                    </thead>
                </table>
                <div class="panel-scroll height-300">
                    <table class="table table-striped" width="100%" id="container_table">
                        <tbody id="box_listing_container">
                        <?php
						$t_box_qty = $t_disp_wt = $t_gross_wt =  $t_net_wt =  $t_amount = 0;
                        if(isset($all_boxes) && !empty($all_boxes))
						{
						  $sr_no = 1;
						  foreach($all_boxes as $box_number => $box)//Main For Loop For each Box
						  {
							$checked = '';
							$checked_class = 'box_unchecked';
							$box_quantity = $box_weight = 0;
							$items_tr = '';
                            foreach($box as $item_data) //Inner For Loop For items
							{
								$checked 		= !empty($item_data['sale_item_id']) ? 'checked="checked"' : '';
								$checked_class 	= !empty($item_data['sale_item_id']) ? 'box_checked info' : 'box_unchecked';
								$box_quantity 	+= $item_data['fish_qty'];
								$box_weight 	+= $item_data['box_wt'];
								$t_box_qty		+= $item_data['fish_qty'];
                                $t_disp_wt 		+= $item_data['box_wt'];
								$t_gross_wt 	+= $item_data['gross_wt'];
								$t_net_wt 		+= $item_data['net_wt'];
								$t_amount 		+= $item_data['total_amount'];
								
								$items_tr .= '<tr class="box_item_row">
												<input type="hidden" class="fish_code" value="'.$item_data['fish_code'].'">
												<input type="hidden" class="fish_qty" value="'.$item_data['fish_qty'].'">
												<td style="width:20%;">'.$item_data['fish_code'].' ('.$item_data['fish_name'].')</td>
												<td style="width:10%;">'.$item_data['fish_qty'].'</td>
												<td style="width:14%;"><input type="text" value="'.my_number_format($item_data['box_wt']).'" class="box_wt disabled stict_numeric" readonly="readonly" style="width:80px;" tabindex="-1" /></td>	
												<td style="width:14%;"><input type="text" value="'.my_number_format($item_data['gross_wt']).'" class="gross_wt strict_numeric " style="width:80px;" onkeyup="checkDecimal(this);"></td>
												<td style="width:14%;"><input type="text" value="'.my_number_format($item_data['net_wt']).'" class="net_wt disabled" style="width:80px;" readonly="readonly" tabindex="-1" /></td>
												<td style="width:14%;"><input type="text" value="'.$item_data['fish_rate'].'" class="rate strict_numeric " style="width:80px;"></td>
												<td style="width:14%;"><input type="text" value="'.$item_data['total_amount'].'" class="amount disabled" readonly="readonly" style="width:80px;" tabindex="-1" /></td>
											 </tr>';
							}//End Inner For Loop
						?>
						<tr class="box_row box_<?=$box_number?> <?=$checked_class?>" id="<?=$sr_no?>">
                            <td class="sr_no" style="width:45px;"><input <?=$checked?> type="checkbox" class="box_check"></td>
                            <td style="width:100px;"><?=$sr_no?></td>
                            <td style="width:100px;"><input class="box_number disabled" readonly="readonly" style="width:80px;" type="text" value="<?=$box_number?>"  tabindex="-1" /></td>
                            <td style="width:100px;"><input type="text" value="<?=$box_quantity?>" class="box_qty disabled" readonly="readonly" style="width:80px;" tabindex="-1" /></td>
                            <td style="width:100px;"><input type="text" value="<?=$box_weight?>" class="disp_wt disabled" readonly="readonly" style="width:80px;" tabindex="-1" /></td>
                            <td style="width:700px;">
                                <table class="table table-striped table-condensed">
                                    <tbody>
                                    <?=$items_tr?>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
						<?php
                        	$sr_no++; 
						  }//End Main For Loop
						}//End IF
						else{
                        ?>
                        <tr>    
                        	<th class="text-center"><div class="alert alert-danger">Sorry, No Record Found</div></th>    
						</tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="panel-footer border-light light-bg">
                <table class="table table-striped" width="100%" id="header_table">
                    <thead>
                        <tr class="info">
                            <th style="width:45px;"></th>
                            <th style="width:100px;">Summary</th>
                            <th style="width:100px;"></th>
                            <th style="width:100px;"></th>
                            <th style="width:100px;"><input type="text" name="t_disp_wt" style="width:80px;" class="t_disp_wt disabled" readonly="readonly" value="<?=my_number_format($t_disp_wt)?>" /></th>
                            <th style="width:700px;">
                            	<table class="table">
                                	<tr>
                                    	<th style="width:20%;"></th>
                                        <th style="width:10%;"></th>
                                        <th style="width:14%;"></th>
                                        <th style="width:14%;"><input type="text" name="t_gross_wt" style="width:80px;" class="t_gross_wt disabled" readonly="readonly" value="<?=my_number_format($t_gross_wt)?>" /></th>
                                        <th style="width:14%;"><input type="text" name="t_net_wt" style="width:80px;" class="t_net_wt disabled" readonly="readonly" value="<?=number_format($t_net_wt,2 ,'.', '')?>" /></th>
                                        <th style="width:14%;"></th>
                                        <th style="width:14%;"><input type="text" name="t_amount" style="width:80px;" class="t_amount disabled" readonly="readonly" value="<?=number_format($t_amount,2 ,'.', '')?>" /></th>
                                    </tr>
                                </table>
                            </th>
                        </tr>                    
                    </thead>
                </table>
                
                
                <table class="table table-striped" width="100%" id="header_table">
                    <thead>
                        <tr>
                            <th style="width:45px;"></th>
                            <th style="width:100px;">S.No.</th>
                            <th style="width:100px;">Box</th>
                            <th style="width:100px;">Box Qty</th>
                            <th style="width:100px;">Box WT</th>
                            <th style="width:700px;">
                            	<table class="table">
                                	<tr>
                                    	<th style="width:20%;">Fish</th>
                                        <th style="width:10%;">Item Qty</th>
                                        <th style="width:14%;">Item Wt</th>
                                        <th style="width:14%;">Gross WT</th>
                                        <th style="width:14%;">Net WT</th>
                                        <th style="width:14%;">Rate</th>
                                        <th style="width:14%;">Amount</th>
                                    </tr>
                                </table>
                            </th>
                        </tr>                    
                    </thead>
                </table>
                
                <?php if($action_mode == 'edit'){ ?>
                <hr />
                <div class="row">
                    <div class="col-sm-12 text-center">
                        <button type="button" class="btn btn-success btn-lg save_sale" data-toggle="tooltip" data-title="Save Sale">Save Sale</button>
                    </div>
                </div>
                <?php } ?>
            </div>
            <?php 
            echo form_close();
            }
			?>
        </div>
    </div>
  </div>
</div>