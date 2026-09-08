<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!-- start: BREADCRUMB -->
<style>
.table{margin:0px !important;}
.table .table tr:last-child th, .table .table tr:last-child td{border-bottom:none !important;}
th, tr { text-align: center; }
</style>
<?php
extract($dispatch_info);
if(isset($dispatch_date) && !empty($dispatch_date)){
	$dispatch_date = str_replace('/', '-', $dispatch_date);
	$dispatch_date = get_datetime('d/m/Y h:i a', $dispatch_date);
}
if(isset($departure_time) && !empty($departure_time)){
	$departure_time = str_replace('/', '-', $departure_time);
	$departure_time = get_datetime('d/m/Y h:i a', $departure_time);
}
if(isset($arrival_time) && !empty($arrival_time)){
	$arrival_time = str_replace('/', '-', $arrival_time);
	$arrival_time = get_datetime('d/m/Y h:i a', $arrival_time);
}
?>
<div class="container-fluid container-fullw">
    <div class="panel panel-white" id="panel_container">
        <?php echo form_open('', 'class="form-horizontal transfer_form" id="transfer_form"');?>
        <input type="hidden" name="action_mode" value="<?=$action_mode?>" />
        <input type="hidden" name="dispatch_id" value="<?=$dispatch_id?>" />
        <input type="hidden" name="dr_number" value="<?=$dr_number?>" />
        <input type="hidden" name="dispatch_via" value="<?=$dispatch_to?>"  />
        <div class="panel-heading border-light light-bg">
        	<h3 class="text-center">
				<a href="<?=$go_back_url?>" class=""><i class="fa fa-arrow-circle-left"></i> </a>
				<?=$page_title?> No. <?=$dr_number?>
            </h3>
            <div class="row">
                <div class="col-sm-12 ajax_reponse"></div>
            </div>
            <div class="form-group">
                <div class="col-sm-2">
                    <label class="control-label">Dispatch Date <span class="symbol required"></span></label>
                    <input type="text" name="dispatch_date" class="form-control <?=$datepicker?>" placeholder="dd/mm/yyyy h:m am/pm" value="<?=set_value('dispatch_date', $dispatch_date);?>" <?=$disabled_date?>>
                </div>
                <div class="col-sm-2">
                    <label class="control-label">Depot: <span class="symbol required"></span></label>
                    <select class="form-control dispatch_from" name="dispatch_from">
                        <?php
                        $dispatch_from = set_value('dispatch_from', $dispatch_from);
                        if(!empty($all_depot)){
                            foreach($all_depot as $row){
                                $op_val = $row['ID'];
                                $op_text = $row['name'];
                            
                            if($dispatch_from == $op_val){
                                $selected = 'selected="selected"';
                                echo '<option value="'.$op_val.'">'.$op_text.'</option>';
                            }
                        }} ?>
                    </select>
                </div>
                <div class="col-sm-2">
                    <label class="control-label">Destination: <span class="symbol required"></span></label>
                    <select class="form-control dispatch_to" id="dispatch_to" name="dispatch_to">
                      <option value="">Select Destination</option>
                      <?php
                        $dispatch_to = set_value('dispatch_to', $dispatch_to);
                        if(!empty($outside_markets)){
                            $selected = '';
                            foreach($outside_markets as $row){
                                $op_val = $row['ID'];
                                $op_text = $row['name'];
                            
                            if($dispatch_to == $op_val){
                                continue;
                            }else{
                                $selected = '';
                            }
                        ?>
                        <option <?=$selected?> value="<?=$op_val?>"><?=$op_text?></option>
                        <?php }} ?>
                    </select>
                </div>
                <div class="col-sm-2">
                    <label class="control-label">Departure Time <span class="symbol required"></span></label>
                    <input type="text" name="departure_time" class="form-control <?=$datepicker?>" placeholder="dd/mm/yyyy h:m am/pm" value="<?=set_value('departure_time', $departure_time);?>" <?=$disabled_date?>>
                </div>
                <div class="col-sm-2">
                    <label class="control-label">Arrival Time <span class="symbol required"></span></label>
                    <input type="text" name="arrival_time" class="form-control <?=$datepicker?>" placeholder="dd/mm/yyyy h:m am/pm" value="<?=set_value('arrival_time', $arrival_time);?>" <?=$disabled_date?>>
                </div>
                <div class="col-sm-2">
                    <label class="control-label">Vehicle No. <span class="symbol required"></span></label>
                    <input type="text" name="vehicle_number" class="form-control" value="<?=set_value('vehicle_number', $vehicle_number)?>">
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-2">
                    <label class="control-label">Driver Name <span class="symbol required"></span></label>
                    <input type="text" name="driver_name" class="form-control" value="<?=set_value('driver_name', $driver_name)?>">
                </div>
                <div class="col-sm-2">
                    <label class="control-label">Driver Mob. No <span class="symbol required"></span></label>
                    <input type="text" name="driver_mobile_no" class="form-control strict_integer" maxlength="12" value="<?=set_value('driver_mobile_no', $driver_mobile_no)?>">
                </div>
                <div class="col-sm-2">
                    <label class="control-label">Alternate Mob. No </label>
                    <input type="text" name="alter_mobile_no" class="form-control strict_integer" maxlength="12" value="<?=set_value('alter_mobile_no', $alter_mobile_no)?>">
                </div>
                <div class="col-sm-2">
                    <label class="control-label">Total Freight <span class="symbol required"></span></label>
                    <input type="text" name="total_freight" class="form-control strict_numeric" onkeyup="checkDecimal(this);" value="<?=set_value('total_freight', $total_freight)?>">
                </div>
                <div class="col-sm-2">
                    <label class="control-label">Advance Freight <span class="symbol required"></span></label>
                    <input type="text" name="advance_freight" class="form-control strict_numeric" onkeyup="checkDecimal(this);" value="<?=set_value('advance_freight', $advance_freight)?>">
                </div>
                <div class="col-sm-2">
                    <label class="control-label">Remaining Freight <span class="symbol required"></span></label>
                    <input type="text" name="remaining_freight" class="form-control" value="<?=set_value('remaining_freight', $remaining_freight)?>" readonly="readonly">
                </div>
                <div class="col-sm-2">
                	<?php /*?>
                    <div class="form-group margin-top-5">
                    	<br />
                        <button type="button" class="btn btn-warning export_data" data-toggle="tooltip" data-title="Download Excel" data-target="panel-body-container" data-filename="Dispatched Boxes"> <i class="fa fa-file-excel-o"></i> </button>&nbsp;
                        <a id="dlink" style="display:none;"></a>
                        <button type="button" class="btn btn-success print_data" data-toggle="tooltip" data-title="Print"> <i class="fa fa-print"></i></button>
                    </div>
                    <?php */?>
                </div>
            </div>
            
        </div>        
        <div class="panel-body" id="panel-body-container">
            <table class="table table-striped" width="100%" id="header_table">
                <thead>
                    <tr>
                        <th style="width:50px;"><label><input type="checkbox" class="mark_all" /> Transfer</label></th>
                        <th style="width:85px;">Box Number</th>
                        <th style="width:400px;">
                            <table class="table borderless table-condensed">
                               <tr>
                                    <th style="width:40%;">Fish</th>
                                    <th style="width:20%;">Quantity</th>
                                    <th style="width:20%;">Carret Wt</th>
                                    <th style="width:20%;">Box Wt</th>
                                </tr>
                            </table>
                        </th>                            
                        <th style="width:100px;">Total Qty</th>
                        <th style="width:100px;">Total WT</th>
                    </tr>                    
                </thead>
            </table>
        	<div class="panel-scroll height-300"> 
                <table class="table table-striped" width="100%" id="container_table">
                    <tbody id="box_listing_container">
				    <?php 
                    if(isset($all_boxes) && !empty($all_boxes)){
						$i = 1;
                        foreach($all_boxes as $box_data){							
                            $box_number 	= $box_data['box_number'];
							$sale_id 		= $box_data['sale_id'];
                            $box_items 		= !empty($box_data['box_items']) ? explode('|', $box_data['box_items']) : array();
							
							//Show only those box whose sale_id is empty
                            if(is_array($box_items) && !empty($box_items) && empty($sale_id)){
                                $dataset = array();
								foreach($box_items as $box){
									$items = !empty($box) ? explode('/', $box) : array();
									$dataset[] = array( 'fish_code' 	=> @$items[0], 
														'fish_name' 	=> @$items[1],
														'fish_qty' 		=> @$items[2], 
														'fish_wt'		=> @$items[3],  
														'box_wt'		=> @$items[4],
														'item_id' 		=> @$items[5], 
														'estimated_price' => @$items[6]
													   );
                                }
								echo transfer_box_list_html($i, $box_data, $dataset, 'transfer');
                            }                            
                        	$i++;
						}
                    }
                    ?>
                    </tbody>                
                </table>
			</div>
            <div class="row margin-top-15">
                <div class="col-sm-10">
                    <textarea class="form-control" name="remark" placeholder="Transfer Remark"><?=set_value('remark', $remark)?></textarea>
                </div>
                <div class="col-sm-2">
                    <div class="form-group margin-top-5">
                        <button type="button" class="btn btn-success transfer_btn" disabled="disabled" data-url="<?=site_url('dispatch/ajax_save_transfer_info')?>"> <i class="fa fa-truck"></i> Transfer</button>&nbsp;
                    </div>
                </div>
            </div>
        </div>
        <?php echo form_close();?>
    </div>
</div>
