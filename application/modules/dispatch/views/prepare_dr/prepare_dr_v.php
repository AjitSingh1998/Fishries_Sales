<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!-- start: BREADCRUMB -->
<style>
input[type="text"].form-control:focus,select.form-control:focus,textarea.form-control:focus {
    border-color: #66afe9 !important;
    outline: 0 !important;
    -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,0.075), 0 0 8px rgba(102,175,233,0.6) !important;
    box-shadow: inset 0 1px 1px rgba(0,0,0,0.075), 0 0 8px rgba(102,175,233,0.6) !important;
}
.table{margin:0px !important;}
.table .table tr:last-child th, .table .table tr:last-child td{border-bottom:none !important;}
th, tr { text-align: center; }

.switchery-default {
  border-radius: 20px;
  height: 20px;
  width: 33px;
}

.switchery-default > small {
  height: 20px;
  width: 20px;
}

</style>
<?php
extract($dispatch_info);
$source_depot = '';

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
$action_form_class = $heading_msg = $edit_button = $cancel_button = '';
if($action_mode != 'add'){
	$action_form_class = 'hidden';
	$heading_msg = '<div class="text-center">'.get_date('d/m/Y', str_replace('/','-',$dispatch_date)).' / '.$source.' / '.$destination.' / <input type="checkbox" id="expand_report_container" class="js-switch" data-target="#panel-body-container" /></div>';
	$edit_button = '<button type="button" class="btn btn-warning btn-xs edit_dr_detail " data-toggle="tooltip" data-title="Edit DR Detail Info">Edit</button>';
	$cancel_button = '<button type="button" class="btn btn-default cancel_edit_dr_detail ">Cancel</button>';
}
?>
<div class="container-fluid container-fullw">
    <div class="panel panel-white" id="panel_container">
        <div class="panel-heading border-light light-bg">
        	<h3 class="text-center">
				<a href="<?=$go_back_url?>" class=""><i class="fa fa-arrow-circle-left"></i> </a>
				<?=$page_title?>
                <?=$edit_button?>
            </h3>
            <?=$heading_msg?>
            <div class="row">
                <div class="col-sm-12 ajax_reponse"></div>
            </div>
            <?php echo form_open('dispatch/save_dispatch_info', 'class="form-horizontal dispatch_form '.$action_form_class.'" id="dispatch_form"');?>
            <input type="hidden" name="action_mode" value="<?=$action_mode?>" />
            <input type="hidden" name="dispatch_id" value="<?=$dispatch_id?>" />
            <input type="hidden" name="dr_number" value="<?=$dr_number?>" />
            <div class="form-group">
                <div class="col-sm-2">
                    <label class="control-label">Dispatch Date <span class="symbol required"></span></label>
                    <input type="text" name="dispatch_date" class="form-control <?=$datepicker?>" value="<?=set_value('dispatch_date', $dispatch_date)?>">
                </div>
                <div class="col-sm-2">
                    <label class="control-label">Depot: <span class="symbol required"></span></label>
                    <select class="form-control dispatch_from" name="dispatch_from">
                      <option value="">Select Depot</option>
                        <?php
                        $dispatch_from = set_value('dispatch_from', $dispatch_from);
                        if(!empty($all_depot)){
                            foreach($all_depot as $row){
                                $op_val = $row['ID'];
                                $op_text = $row['name'];
                            
                            if($dispatch_from == $op_val){
                                $source_depot = $op_text;
                                $selected = 'selected="selected"';
                            }else{
                                $selected = '';
                            }
                        ?>
                        <option <?=$selected?> value="<?=$op_val?>"><?=$op_text?></option>
                        <?php }} ?>
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
                                $selected = 'selected="selected"';
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
                    <input type="number" name="remaining_freight" class="form-control" value="<?=set_value('remaining_freight', $remaining_freight)?>" readonly="readonly">
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
            <div class="row">
            	<div class="col-sm-10">
                	<textarea name="remark" class="form-control" placeholder="Remark" rows="2"><?=set_value('remark', $remark)?></textarea>
                </div>
            	<div class="col-sm-2">
                    <div class="form-group margin-top-15">
                        <?php $action_btn = 'Save'; if($action_mode == "edit"){$action_btn = 'Update';}?>
                        <button type="button" class="btn btn-primary save_dispatch_info"><?=$action_btn?></button>&nbsp;
                        <?=$cancel_button?>
                    </div>
                </div>
                
            </div>
            <?php echo form_close();?>
        </div>
        <?php if($dispatch_id){ ?>
        <div class="panel-body" id="panel-body-container">
            <table class="table table-striped" width="100%" id="header_table">
                <thead>
                    <tr>
                        <th style="width:50px;">S.No.</th>
                        <th style="width:100px;">Box Number</th>
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
                        <th style="width:100px;" class="no-print no-exl">Action</th>
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
                            $data_items = !empty($box_data['box_items']) ? explode('|', $box_data['box_items']) : array();
                            if(is_array($data_items) && !empty($data_items)){
                                $box_items = array();
								foreach($data_items as $row){
                                	$items = !empty($row) ? explode('/', $row) : array();
                                	$box_items[] = array('fish_code' 	=> @$items[0],
													     'fish_name' 	=> @$items[1],
													     'fish_qty' 	=> @$items[2], 
													     'fish_wt' 		=> @$items[3],
														 'box_wt' 		=> @$items[4]
														 );
                                }
								echo dr_box_list_html($i, $box_data, $box_items, $action_mode);
                            }                            
                        	$i++;
						}
                    }else{ ?>
                    <?php /*?>
					<tr>
                    	<th colspan="8"><div class="alert alert-danger">Sorry, No record found</div></th>
                    </tr>
					<?php */?>
                    <?php } ?>
                    </tbody>                
                </table>
			</div>
        </div>
        <div class="panel-footer border-light light-bg">
        	<table class="table table-striped" width="100%" id="header_table" style="margin-bottom: 10px !important;">
                <thead>
                    <tr>
                        <th style="width:50px;">S.No.</th>
                        <th style="width:100px;">Box Number</th>
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
                        <th style="width:100px;" class="no-print no-exl">Action</th>
                    </tr>                    
                </thead>
            </table>
            
            <div class="row">
                <div class="col-sm-12 ajax_box_reponse"></div>
            	<div class="col-sm-8 col-sm-offset-2">
                	<?php echo form_open('dispatch/add_dispatch_box', 'id="add_box_form"');?>
                    <input type="hidden" name="dispatch_from" value="<?=$dispatch_from?>" />
                    <input type="hidden" name="dr_number" value="<?=$dr_number?>" />
                    <input type="hidden" name="dispatch_id" value="<?=$dispatch_id?>" />
                    <input type="hidden" name="source_depot" value="<?=$source_depot?>" />
                    <div class="col-sm-6">
                        <input type="text" name="box_number" class="form-control input-lg" placeholder="Enter box number" />
                    </div>
                    <div class="col-sm-6">
                        <button type="button" class="btn btn-primary btn-lg add_box">Add Box</button>
                        <button type="button" class="btn btn-warning btn-lg open_aside_modal" data-url="<?=site_url('excel_import/dispatch_box/'.$dispatch_id)?>" data-style="bottom"> 
                            <i class="fa fa-file-excel-o"></i> Import Excel
                        </button>
                    </div>
                    <?php echo form_close();?>
                </div>
                
            </div>
		</div>
        <?php } ?>
    </div>
</div>
