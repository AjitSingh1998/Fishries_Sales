<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!-- start: BREADCRUMB -->
<style>
.table .table{margin:0px !important;}
.table .table tr:last-child th, .table .table tr:last-child td{border-bottom:none !important;}
th, tr { text-align: center; }
textarea {
  resize: none;
}
</style>
<?php
$dispatch_info = isset($dispatch_info) && is_array($dispatch_info) ? $dispatch_info : array();
extract($dispatch_info);
$source = isset($source) ? $source : '';
$destination = isset($destination) ? $destination : '';
$dispatch_date = isset($dispatch_date) ? $dispatch_date : '';
$departure_time = isset($departure_time) ? $departure_time : '';
$arrival_time = isset($arrival_time) ? $arrival_time : '';
$disabled_input = isset($disabled_input) ? $disabled_input : 'disabled="disabled"';
$go_back_url = isset($go_back_url) ? $go_back_url : site_url('dispatch/dispatch/dispatched');
$page_title = isset($page_title) ? $page_title : 'DR No. Detail';
$dispatch_via = isset($dispatch_via) ? $dispatch_via : 0;
$dispatch_id = isset($dispatch_id) ? $dispatch_id : 0;
$alter_mobile_no = isset($alter_mobile_no) ? $alter_mobile_no : '';
$remark = isset($remark) ? $remark : '';
$driver_name = isset($driver_name) ? $driver_name : '';
$vehicle_no = isset($vehicle_no) ? $vehicle_no : '';
$driver_mobile_no = isset($driver_mobile_no) ? $driver_mobile_no : '';
$driver_license_no = isset($driver_license_no) ? $driver_license_no : '';
$cash_advance = isset($cash_advance) ? $cash_advance : '';
$diesel_amt = isset($diesel_amt) ? $diesel_amt : '';
$police_fee = isset($police_fee) ? $police_fee : '';
$entry_fee = isset($entry_fee) ? $entry_fee : '';
$toll_tax = isset($toll_tax) ? $toll_tax : '';
$transit_delay = isset($transit_delay) ? $transit_delay : '';
$weight_loss = isset($weight_loss) ? $weight_loss : '';


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
        	<h3 class="text-center">
                <a href="<?=$go_back_url?>" class=""><i class="fa fa-arrow-circle-left"></i> </a>
				<?=$page_title?>                
                <?php
                if(!empty($dispatch_via) && $dispatch_via > 0){
					echo '<button class="btn btn-warning btn-xs edit_transfer" data-url="'.site_url('dispatch/ajax_edit_trf_mode/'.$dispatch_id).'">Edit</button>';
				}
				?>
            </h3>
            <div class="form-horizontal">
                <div class="form-group">
                    <div class="col-sm-2">
                        <label class="control-label bold">Dispatch Date :</label>
                        <input type="text" class="form-control" value="<?=$dispatch_date?>" <?=$disabled_input?>>
                    </div>
                    <div class="col-sm-2">
                        <label class="control-label bold">Depot :</label>
                        <input type="text" name="dispatch_from" class="form-control" value="<?=$source;?>" <?=$disabled_input?>>
                    </div>
                    <div class="col-sm-2">
                        <label class="control-label bold">Destination :</label>
                        <input type="text" name="dispatch_to" class="form-control" value="<?=$destination;?>" <?=$disabled_input?>>
                    </div>
                    <div class="col-sm-2">
                        <label class="control-label bold">Departure Time :</label>
                        <input type="text" class="form-control" value="<?=$departure_time?>" <?=$disabled_input?>>
                    </div>
                    <div class="col-sm-2">
                        <label class="control-label bold">Arrival Time :</label>
                        <input type="text" class="form-control" value="<?=$arrival_time?>" <?=$disabled_input?>>
                    </div>
                    <div class="col-sm-2">
                        <label class="control-label bold">Vehicle No. :</label>
                        <input type="text" class="form-control" value="<?=$vehicle_number?>" <?=$disabled_input?>>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-2">
                        <label class="control-label bold">Driver Name :</label>
                        <input type="text" class="form-control" value="<?=$driver_name?>" <?=$disabled_input?>>
                    </div>
                    <div class="col-sm-2">
                        <label class="control-label bold">Driver Mob. No :</label>
                        <input type="text" class="form-control" value="<?=$driver_mobile_no?>" <?=$disabled_input?>>
                    </div>
                    <div class="col-sm-2">
                        <label class="control-label bold">Alternate Mob. No </label>
                        <input type="text" class="form-control" value="<?=$alter_mobile_no?>" <?=$disabled_input?>>
                    </div>
                    <div class="col-sm-2">
                        <label class="control-label bold">Total Freight :</label>
                        <input type="number" class="form-control" value="<?=$total_freight?>" <?=$disabled_input?>>
                    </div>
                    <div class="col-sm-2">
                        <label class="control-label bold">Advance Freight :</label>
                        <input type="number" class="form-control" value="<?=$advance_freight?>" <?=$disabled_input?>>
                    </div>
                    <div class="col-sm-2">
                        <label class="control-label bold">Remaining Freight :</label>
                        <input type="number" class="form-control" value="<?=$remaining_freight?>" <?=$disabled_input?>>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-10">
                        <label class="control-label bold">Remark :</label>
                        <textarea class="form-control" <?=$disabled_input?>><?=$remark?></textarea>
                    </div>
                    <div class="col-sm-2 margin-top-5"> <br />
                        <button type="button" class="btn btn-warning" id="export_data" data-toggle="tooltip" data-title="Download Excel" data-target="#panel-body-container" data-filename="Dispatched Boxes"> <i class="fa fa-file-excel-o"></i> </button>&nbsp;
                        <a id="dlink" style="display:none;"></a>
                        <button type="button" class="btn btn-success print_data" data-toggle="tooltip" data-title="Print" data-target="#panel-body-container"> <i class="fa fa-print"></i></button>
						
						<div class="inline-block">
							<input type="checkbox"  id="expand_report_container"  class="js-switch" data-target="#panel-body-container" />
						</div>

					
                    </div>
                </div>
            </div>
        </div>
        <?php echo form_close();?>
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
                            $box_number = $box_data['box_number'];
                            $box_items = !empty($box_data['box_items']) ? explode('|', $box_data['box_items']) : array();
                            if(is_array($box_items) && !empty($box_items)){
                                $dataset = array();
								foreach($box_items as $box){
									$items = !empty($box) ? explode('/', $box) : array();
									$dataset[] = array( 'fish_code' => @$items[0],
														'fish_name' => @$items[1],
														'fish_qty' 	=> @$items[2], 
														'fish_wt' 	=> @$items[3], 
														'box_wt' 	=> @$items[4]
													   );
                                }
								echo dispatched_box_list_html($i, $box_data, $dataset, 'view');
                            }                            
                        	$i++;
						}
                    }else{
						echo '<tr><td class="text-center" colspan="5"><div class="text-danger">Sorry, No boxes found in record.</div></td></tr>';
					}
                    ?>
                    </tbody>                
                </table>
			</div>
        </div>
    </div>
</div>
