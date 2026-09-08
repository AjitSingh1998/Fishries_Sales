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
        	<h3 class="text-center">
                <a href="<?=$go_back_url?>" class=""><i class="fa fa-arrow-circle-left"></i> </a>
				<?=$page_title?>
            </h3>
            <form class="form-horizontal">
                <div class="form-group">
                    <div class="col-sm-2">
                        <label class="control-label bold">Dispatch Date:</label>
                        <input type="text" class="form-control underline disabled" value="<?=$dispatch_date?>" <?=$disabled_input?>>
                    </div>
                    <div class="col-sm-2">
                        <label class="control-label bold">Depot: </label>
                        <input type="text" class="form-control disabled" value="<?=$source?>" <?=$disabled_input?> />
                    </div>
                    <div class="col-sm-2">
                        <label class="control-label bold">Destination: </label>
                        <input type="text" class="form-control disabled" value="<?=$destination?>" <?=$disabled_input?> />
                    </div>
                    <div class="col-sm-2">
                        <label class="control-label bold">Departure Time :</label>
                        <input type="text" class="form-control disabled" value="<?=$departure_time?>" <?=$disabled_input?>>
                    </div>
                    <div class="col-sm-2">
                        <label class="control-label bold">Arrival Time :</label>
                        <input type="text" class="form-control disabled" value="<?=$arrival_time?>" <?=$disabled_input?>>
                    </div>
                    <div class="col-sm-2">
                        <label class="control-label bold">Vehicle No. :</label>
                        <input type="text" class="form-control disabled" value="<?=$vehicle_number?>" <?=$disabled_input?>>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-2">
                        <label class="control-label bold">Driver Name :</label>
                        <input type="text" class="form-control disabled" value="<?=$driver_name?>" <?=$disabled_input?>>
                    </div>
                    <div class="col-sm-2">
                        <label class="control-label bold">Driver Mob. No :</label>
                        <input type="text" class="form-control disabled" value="<?=$driver_mobile_no?>" <?=$disabled_input?>>
                    </div>
                    <div class="col-sm-2">
                        <label class="control-label bold">Aleternate Mob. No :</label>
                        <input type="text" class="form-control disabled" value="<?=$alter_mobile_no?>" <?=$disabled_input?>>
                    </div>
                    <div class="col-sm-2">
                        <label class="control-label bold">Total Freight :</label>
                        <input type="number" class="form-control disabled" value="<?=$total_freight?>" <?=$disabled_input?>>
                    </div>
                    <div class="col-sm-2">
                        <label class="control-label bold">Advance Freight :</label>
                        <input type="number" class="form-control disabled" value="<?=$advance_freight?>" <?=$disabled_input?>>
                    </div>
                    <div class="col-sm-2">
                        <label class="control-label bold">Remaining Freight :</label>
                        <input type="number" class="form-control disabled" value="<?=$remaining_freight?>" <?=$disabled_input?>>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-10">
                        <label class="control-label bold">Remark :</label>
                        <textarea class="form-control" <?=$disabled_input?>><?=$remark?></textarea>
                    </div>
                    <div class="col-sm-2 margin-top-5"> <br />
                        <button type="button" class="btn btn-warning export_data" data-toggle="tooltip" data-title="Download Excel" data-target="panel-body-container" data-filename="Dispatched Boxes"> <i class="fa fa-file-excel-o"></i> </button>&nbsp;
                        <a id="dlink" style="display:none;"></a>
                        <button type="button" class="btn btn-success print_data" data-toggle="tooltip" data-title="Print"> <i class="fa fa-print"></i></button>
                    </div>
                </div>
            </form>
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
                            $data_items = !empty($box_data['box_items']) ? explode('|', $box_data['box_items']) : array();
                            if(is_array($data_items) && !empty($data_items)){
                                $box_items = array();
								foreach($data_items as $row){
                                	$items = !empty($row) ? explode('/', $row) : array();
                                	$box_items[] = array('fish_code' 	=> @$items[0],
													     'fish_name' 	=> @$items[1],
													     'fish_qty' 	=> @$items[2], 
													     'fish_wt' 		=> @$items[3],
														 'box_wt' 		=> @$items[4],
														 'sale_id'		=> $box_data['sale_id']
														 );
                                }
								echo generate_box_list_html($i, $box_data, $box_items, $action_mode);
                            }                            
                        	$i++;
						}
                    }
                    ?>
                    </tbody>                
                </table>
			</div>
        </div>
    </div>
</div>
