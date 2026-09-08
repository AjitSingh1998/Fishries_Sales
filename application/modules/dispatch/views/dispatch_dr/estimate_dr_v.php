<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!-- start: BREADCRUMB -->
<style>
.table{margin:0px !important;}
.table .table tr:last-child th, .table .table tr:last-child td{border-bottom:none !important;}
th, tr, td { text-align: center; }
.estimated_price {
	text-align: center !important;
    height: auto !important;
    padding-left: 0px !important;
    padding-right: 0px !important;
}
textarea {
  resize: none;
}
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
        
        <div class="panel-heading border-light light-bg">
			<?php echo form_open('dispatch/save_dispatch_info', 'class="form-horizontal dispatch_form" id="dispatch_form"');?>
			<input type="hidden" name="action_mode" value="<?=$action_mode?>" />
			<input type="hidden" name="dispatch_id" value="<?=$dispatch_id?>" />
			<input type="hidden" name="dr_number" value="<?=$dr_number?>" />
        	<h3 class="text-center">
            	<a href="<?=$go_back_url?>" class=""><i class="fa fa-arrow-circle-left"></i> </a>
				<?=$page_title?>
                <small>Estimate By: </small> <select class="estimate_by"><option value="box">Box</option><option value="fish">Fish Type</option></select>
            </h3>
            <div class="form-group">
                <div class="col-sm-12 ajax_reponse"></div>
            </div>
            <div class="form-group">
                <div class="col-sm-2">
                    <label class="control-label bold">Dispatch Date: </label>
                    <input type="text" name="dispatch_date" class="form-control disabled" value="<?=$dispatch_date;?>" <?=$readonly?> />
                </div>
                <div class="col-sm-2">
                    <label class="control-label bold">Depot:</label>
                    <input type="text" name="dispatch_from" class="form-control disabled" value="<?=$source;?>" <?=$readonly?> />
                </div>
                <div class="col-sm-2">
                    <label class="control-label bold">Destination:</label>
                    <input type="text" name="dispatch_to" class="form-control disabled" value="<?=$destination;?>" <?=$readonly?> />
                </div>
                <div class="col-sm-2">
                    <label class="control-label bold">Departure Time:</label>
                    <input type="text" name="departure_time" class="form-control disabled" value="<?=$departure_time;?>" <?=$readonly?> />
                </div>
                <div class="col-sm-2">
                    <label class="control-label bold">Arrival Time:</label>
                    <input type="text" name="arrival_time" class="form-control disabled" value="<?=$arrival_time;?>" <?=$readonly?> />
                </div>
                <div class="col-sm-2">
                    <label class="control-label bold">Vehicle No.:</label>
                    <input type="text" name="vehicle_number" class="form-control disabled" value="<?=$vehicle_number?>" <?=$readonly?> />
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-2">
                    <label class="control-label bold">Driver Name:</label>
                    <input type="text" name="driver_name" class="form-control strict_integer disabled" value="<?=$driver_name?>" <?=$readonly?> />
                </div>
                <div class="col-sm-2">
                    <label class="control-label bold">Driver Mob. No:</label>
                    <input type="text" name="driver_mobile_no" class="form-control strict_integer disabled" value="<?=$driver_mobile_no?>" <?=$readonly?> />
                </div>
                <div class="col-sm-2">
                    <label class="control-label bold">Alternate Mob. No:</label>
                    <input type="text" name="driver_mobile_no" class="form-control strict_integer disabled" value="<?=$alter_mobile_no?>" <?=$readonly?> />
                </div>
                <div class="col-sm-2">
                    <label class="control-label bold">Total Freight:</label>
                    <input type="text" name="total_freight" class="form-control disabled" value="<?=$total_freight?>" <?=$readonly?> />
                </div>
                <div class="col-sm-2">
                    <label class="control-label bold">Advance Freight:</label>
                    <input type="text" name="advance_freight" class="form-control disabled" value="<?=$advance_freight?>" <?=$readonly?> />
                </div>
                <div class="col-sm-2">
                    <label class="control-label bold">Remaining Freight:</label>
                    <input type="text" name="remaining_freight" class="form-control disabled" value="<?=$remaining_freight?>" readonly="readonly" />
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-10">
                    <label class="control-label bold">Remark :</label>
                    <textarea class="form-control disabled" disabled="disabled"><?=$remark?></textarea>
                </div>
                <div class="col-sm-2 margin-top-5"> <br />
                    <button type="button" class="btn btn-warning" id="export_data" data-toggle="tooltip" data-title="Download Excel" data-target="#panel-body-container" data-filename="Estimated Boxes"> <i class="fa fa-file-excel-o"></i> </button>&nbsp;
                    <a id="dlink" style="display:none;"></a>
                    <button type="button" class="btn btn-success print_data" data-target="#panel-body-container" data-toggle="tooltip" data-title="Print" > <i class="fa fa-print"></i></button>
					<div class="inline-block">
						<input type="checkbox"  id="expand_report_container"  class="js-switch" data-target="#panel-body-container" />
					</div>
                </div>
            </div>
			<?php echo form_close();?>
			<?php echo form_open(site_url('dispatch/ajax_save_dhalta'), 'class="form-inline dr_dhalta_form" id="dr_dhalta_form"');?>
			  <div class="form-group">
				<label for="dhalta">Dhalta %</label>
				<input type="text" name="dhalta" class="form-control" id="dhalta" value="<?=@$dhalta?>" />
			  </div>
			  <div class="form-group">
				<label for="commission">Commission %</label>
				<input type="text" name="Commission" class="form-control" id="commission" value="<?=@$commission?>"/>
			  </div>
			  <div class="form-group">
				<label for="expenses">Expenses </label>
				<input type="text" name="expenses" class="form-control" id="expenses" value="<?=@$expenses?>"/>
			  </div>
			  <button type="button" class="btn btn-success" id="save_dhalta"> Save</button>
			<?php echo form_close();?>
		</div>		
        
		<div class="panel-body div_estimate_by" id="panel-body-container">
            <?=$estimate_by?>
        </div>
    </div>
</div>
