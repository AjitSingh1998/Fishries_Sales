<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- start: BREADCRUMB -->
<style>
input[type="text"].form-control:focus,select.form-control:focus,textarea.form-control:focus {
    border-color: #66afe9 !important;
    outline: 0 !important;
    -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,0.075), 0 0 8px rgba(102,175,233,0.6) !important;
    box-shadow: inset 0 1px 1px rgba(0,0,0,0.075), 0 0 8px rgba(102,175,233,0.6) !important;
}
table.box_items_table, table.listing_header{
	margin: 0px !important;
}
table.box_items_table tr:last-child td, table#container_table tr:last-child td, table#footer_table tr:last-child th{
	border-bottom: none !important;
}

table.tbl_hi tr:last-child th{
	border-bottom: none !important;
}

.tr-bg {
	background-color: #5b9bd121 !important;
}

.switchery-default {
  border-radius: 20px;
  height: 20px;
  width: 33px;
}

.switchery-default > small {
  height: 20px;
  width: 20px;
}

.editable-input{position:relative;}
.editable-input span.edit-icon{
	display: none;
	position: absolute;
	top: 2px;
	right: 2px;
	padding:6px 7px 6px;
	background: #e4e5e6;
	cursor:pointer;
}
.editable-input:hover span.edit-icon {
 display: block ;
}

</style>
<div class="container-fluid container-fullw">
  <div class="row">
    <div class="col-sm-12">
      <div class="panel panel-white" id="panel_container">
        <div class="panel-heading border-light light-bg dp_heading">
          <div class="col-sm-12 text-center">
                <h3>
                <a href="<?=$go_back_url?>" class="" data-toggle="tooltip" data-title="Go Back"><i class="fa fa-arrow-circle-left"></i></a>
                <?=$page_title?>
                
                <?php if($production_id){ ?>
                <button type="button" class="btn btn-warning btn-xs dp_edit_mode" data-url="<?=site_url('production/ajax_dp_edit_mode/'.$production_id)?>">Edit</button>
                <?php } ?>
              </h3>
              <?php if($production_id){  ?>
              <p class="no-margin"><?=$production_data['depot_name']?> / <?=$production_data['market_name']?> / <?=get_datetime('d-m-Y',$production_data['production_date'])?>
                &nbsp;&nbsp;&nbsp;
                <input type="checkbox" id="expand_report_container" class="js-switch" data-target="#panel-body-container" />
                <button type="button" class="btn btn-warning btn-xs" id="export_data" data-toggle="tooltip" data-title="Download Excel" data-target="#panel-body-container" data-filename="Production"> <i class="fa fa-file-excel-o"></i> </button>
                <a id="dlink" style="display:none;"></a>
                <button type="button" class="btn btn-success btn-xs print_data" data-toggle="tooltip" data-title="Print" data-target="#panel-body-container"> <i class="fa fa-print"></i> </button>
				<button type="button" class="btn btn-info btn-xs open_aside_modal" data-style="bottom" data-toggle="tooltip" data-title="Import Excel Data" data-url="<?=site_url('excel_import/production/'.$production_id)?>"> 
                	<i class="fa fa-file-excel-o"></i> Import
                </button>
              </p>
              <?php } ?>
          </div>
		  <?php echo form_open('', 'class="form-horizontal dp_form"');?>
          <input type="hidden" id="action_mode" name="action_mode" value="<?=$action_mode?>" />
          <input type="hidden" id="production_id" name="production_id" value="<?=$production_id?>" />
          <input type="hidden" id="production_type" name="production_type" value="<?=$production_data['production_type']?>" />
          <input type="hidden" name="depot_id" value="<?=$production_data['depot_id']?>" />
          <input type="hidden" name="market_id" value="<?=$production_data['market_id']?>" />
          <input type="hidden" name="production_date" value="<?=get_datetime('d/m/Y',$production_data['production_date'])?>" />
          <div class="row">
            <div class="col-sm-12 ajax-response"></div>
          </div>
          <?php if(!$production_id){  ?>
          <?php if($production_data['production_type'] == "Point"){?>
          <div class="form-group">
            <div class="col-sm-2">
              <label class="control-label">Depot :  <span class="symbol required"></span></label>
              <select class="form-control input-sm" name="depot_id">
                <option value="" data-code="">Select Depot</option>
                <?php
				if(!empty($all_depots)){
					foreach($all_depots as $row){
						$op_val = $row['ID'];
						$op_text = $row['name'];
						$op_code = $row['code'];
				?>
                <option value="<?=$op_val?>" data-code="<?=$op_code?>"><?=$op_text?></option>
                <?php }} ?>
              </select>
            </div>
            <div class="col-sm-2">
              <label class="control-label">Point :  <span class="symbol required"></span></label>
              <select class="form-control input-sm" name="market_id">
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
            <div class="col-sm-2">
              <label class="control-label">Date :  <span class="symbol required"></span></label>
              <input type="text" name="production_date" autocomplete="off" class="form-control input-sm <?=$datepicker_class?>" placeholder="dd/mm/yyyy" />
            </div>
            <div class="col-md-2">
                <label class="control-label">Jhinga Point Weight : </label>
                <input type="text" name="jhinga_point_wt" class="form-control strict_numeric input-sm" value="<?=$production_data['jhinga_point_wt']?>" onkeyup="checkDecimal(this);">
            </div>
            <div class="col-md-2">
                <label class="control-label">Jhinga Depot Weight : </label>
                <input type="text" name="jhinga_depot_wt" class="form-control strict_numeric input-sm" value="<?=$production_data['jhinga_depot_wt']?>" onkeyup="checkDecimal(this);">
            </div>
            
          </div>
          
          <div class="form-group">
            <div class="col-md-2">
                <label class="control-label">Point Weight : <span class="symbol required"></span></label>
                <input type="text" name="point_wt" class="form-control strict_numeric input-sm" onkeyup="checkDecimal(this);" value="<?=$production_data['point_wt']?>">
            </div>
            <div class="col-md-3">
                <label class="control-label">Forfeiture (S&D) Weight :</label>
                <input type="text" name="forfeiture_wt" class="form-control strict_numeric input-sm" onkeyup="checkDecimal(this);" value="<?=$production_data['forfeiture_wt']?>">
            </div>
            <div class="col-md-3">
                <label class="control-label">Forfeiture Rotten Weight :</label>
                <input type="text" name="forfeiture_rt_wt" class="form-control strict_numeric input-sm" onkeyup="checkDecimal(this);" value="<?=$production_data['forfeiture_rt_wt']?>" />
            </div>
            <div class="col-md-2">
                <label class="control-label">Total Point Weight :</label>
                <input type="text" name="total_pt_wt" class="form-control input-sm" readonly="readonly" value="<?=$production_data['total_pt_wt']?>">
            </div>
          </div>
          <?php }else{ ?>
          <div class="form-group">
            <div class="col-sm-3">
              <label class="control-label">From Depot :  <span class="symbol required"></span></label>
              <?php
              	$fd_name = 'depot_id';
				if($production_data['production_type'] == "Transfer"){
					$fd_name = 'market_id';
				}
			  ?>
              <select class="form-control input-sm" name="<?=$fd_name?>">
                <option value="" data-code="">Select Depot</option>
                <?php
				if(!empty($all_depots)){
					foreach($all_depots as $row){
						$op_val = $row['ID'];
						$op_text = $row['name'];
						$op_code = $row['code'];
				?>
                <option value="<?=$op_val?>" data-code="<?=$op_code?>"><?=$op_text?></option>
                <?php }} ?>
              </select>
            </div>
            <?php 
			$label = "Point";
			if($production_data['production_type'] == "Transfer"){
				$label = "To Depot";
				$all_points = $all_depots;
			}
			?>
            <div class="col-sm-3">
              <label class="control-label"><?=$label?> :  <span class="symbol required"></span></label>
              <?php
              	$td_name = 'market_id';
				if($production_data['production_type'] == "Transfer"){
					$td_name = 'depot_id';
				}
			  ?>
              <select class="form-control input-sm" name="<?=$td_name?>">
                <option value="" data-code="">Select <?=$label?></option>
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
            
            <div class="col-sm-2">
              <label class="control-label">Date :  <span class="symbol required"></span></label>
              <input type="text" name="production_date" autocomplete="off" class="form-control input-sm <?=$datepicker_class?>" placeholder="dd/mm/yyyy" />
            </div>
            <div class="col-md-2">
                <label class="control-label"><?=$production_data['production_type']?> Weight : <span class="symbol required"></span></label>
                <input type="text" name="point_wt" class="form-control strict_numeric input-sm" onkeyup="checkDecimal(this);" value="<?=$production_data['point_wt']?>">
            </div>
            <div class="col-md-2 hidden">
                <label class="control-label">Total <?=$production_data['production_type']?> Weight :</label>
                <input type="hidden" name="total_pt_wt" class="form-control input-sm" readonly="readonly" value="<?=$production_data['total_pt_wt']?>">
            </div>
          </div>
          <?php } ?>
          <div class="form-group">
            <div class="col-md-10">
                <label class="control-label">Remark :</label>
                <textarea class="form-control" name="remark" cols="2"><?=$production_data['remark']?></textarea>
            </div>
            <div class="col-sm-2 margin-top-20 text-left"> <br />
              <button type="button" class="btn btn-primary btn-sm save_data" data-url="<?=site_url('production/ajax_save_dp_data')?>">Save</button>
              &nbsp;
            </div>
          </div>
          <?php } ?>
          <?php echo form_close();?>
        </div>
        <div class="panel-body <?=$item_table?>" id="panel-body-container">
            <table class="table listing_header" width="100%" id="header_table">
                <thead>
                    <tr>
                        <th style="width:50px;">S.No.</th>
                        <th style="width:80px;">Fish Type</th>
                        <th style="width:100px;">Carret No.</th>
                        <th style="width:400px;">
                            <table class="table borderless table-condensed tbl_hi" style="margin: 0px !important;">
                               <tbody>
                               		<tr>
                                        <th style="width:40%;" class="text-center">Fish</th>
                                        <th style="width:20%;">Quantity</th>
                                        <th style="width:20%;">Carret Wt</th>
                                        <th style="width:20%;">Box Wt</th>
                                	</tr>
                            	</tbody>
                            </table>
                        </th>
                        <th style="width:100px;"><span data-title="Total Quantity" data-toggle="tooltip">Total Qty</span></th>
                        <th style="width:100px;"><span data-title="Total Quantity" data-toggle="tooltip">Total WT</span></th>
                        <th style="width:100px;">Client</th>
                        <th style="width:100px;"><span data-title="Box Number" data-toggle="tooltip">Box No.</span></th>
                        <th style="width:100px;"><span data-title="Box Weight" data-toggle="tooltip">Box WT</span></th>
                        <th style="width:100px;" class="no-print no-exl">Action</th>
                    </tr>                    
                </thead>
            </table>
            <div class="panel-scroll height-300"> 
                <table class="table table-striped" width="100%" id="container_table">
                    <tbody id="box_listing_container">
					<?php 
                    if(isset($all_data) && !empty($all_data)){
						$i = 1;
                        foreach($all_data as $carret_data){
                            $carret_items = !empty($carret_data['carret_items']) ? explode('|', $carret_data['carret_items']) : array();
                            if(is_array($carret_items) && !empty($carret_items)){
                                $data_items = array();
                                foreach($carret_items as $carret){
									$items = !empty($carret) ? explode('/', $carret) : array();
									$data_items[] = array('fish_code'	=> @$items[0],
														  'fish_name'	=> @$items[1],
														  'fish_qty' 	=> @$items[2], 
														  'fish_wt' 	=> @$items[3],
														  'box_wt' 		=> @$items[4]
														  );
                                }
                                echo generate_box_list_html($i, $carret_data, $data_items, $action_mode);
								$i++;
                            }
                        }
                    }
                    ?>
                    </tbody>                
                </table>
			</div>
            <table class="table listing_header" width="100%" id="footer_table">
                <thead>
                    <tr>
                        <th style="width:50px;">S.No.</th>
                        <th style="width:80px;">Fish Type</th>
                        <th style="width:100px;">Carret No.</th>
                        <th style="width:400px;">
                            <table class="table borderless table-condensed tbl_hi" style="margin: 0px !important;">
                               <tbody>
                               		<tr>
                                        <th style="width:40%;" class="text-center">Fish</th>
                                        <th style="width:20%;">Quantity</th>
                                        <th style="width:20%;">Carret Wt</th>
                                        <th style="width:20%;">Box Wt</th>
                                	</tr>
                            	</tbody>
                            </table>
                        </th>
                        <th style="width:100px;"><span data-title="Total Quantity" data-toggle="tooltip">Total Qty</span></th>
                        <th style="width:100px;"><span data-title="Total Quantity" data-toggle="tooltip">Total WT</span></th>
                        <th style="width:100px;">Client</th>
                        <th style="width:100px;"><span data-title="Box Number" data-toggle="tooltip">Box No.</span></th>
                        <th style="width:100px;"><span data-title="Box Weight" data-toggle="tooltip">Box WT</span></th>
                        <th style="width:100px;" class="no-print no-exl">Action</th>
                    </tr>                    
                </thead>
            </table>
        </div>
        <div class="panel-footer border-light light-bg <?=$item_table?>">
            <?php echo form_open('', 'class="form-horizontal" id="package_form"');?>
                <input type="hidden" name="action_mode" value="<?=$action_mode?>" />
                <input type="hidden" name="depot_id" value="<?=$production_data['depot_id']?>" />
                <input type="hidden" name="market_id" value="<?=$production_data['market_id']?>" />
                <input type="hidden" name="mp_code" class="mp_code" value="<?=$production_data['depot_code']?>" />
                <input type="hidden" name="production_id" value="<?=$production_id?>" />
                <input type="hidden" name="production_date" value="<?=$production_data['production_date']?>" />
                <input type="hidden" name="production_type" value="<?=$production_data['production_type']?>" />
                <div class="row">
                    <div class="col-sm-12">
                       <table class="table">
                        <thead>
                            <tr>
                                <th>Fish <span class="symbol required"></span></th>
                                <th>Quantity <span class="symbol required"></span></th>
                                <th>Carret Weight <span class="symbol required"></span></th>
                                <th>Box Weight</th>
                                <th class="no-print">Action</th>
                            </tr>                    
                        </thead>
                        <tbody id="package-container">
                           <tr>
                                <td>
                                <input type="hidden" class="fish_name" name="package[fish_name][]" value="" />
                                <input type="hidden" class="fish_rate" name="package[fish_rate][]" value="" />
                                <select class="form-control fish_code" name="package[fish_code][]" tabindex="1">
                                  <option value="">Select Fish</option>
                                  <?php
                                    if(!empty($all_fishes)){
                                        foreach($all_fishes as $row){
                                            $op_val = $row['code'];
                                            $op_text = $row['name'];
											$fish_rate = $row['fish_rate'];
											$fish_type = $row['type'];
											$fish_dhalta = $row['dhalta'];
                                  ?>
                                  <option value="<?=$op_val?>" data-fish_type="<?=$fish_type?>" data-fish_name="<?=$op_text?>" data-fish_rate="<?=$fish_rate?>" data-fish_dhalta="<?=$fish_dhalta?>"><?=$op_val.' ('.$op_text.')'?></option>
                                  <?php }} ?>
                                </select>
                                </td>
                                <td><input type="text" name="package[fish_qty][]" class="form-control strict_integer fish_qty" tabindex="2"></td>
                                <td><input type="text" onkeyup="checkDecimal(this);" name="package[fish_wt][]" class="form-control strict_numeric fish_wt" tabindex="3"></td>
                                <td><input type="text" onkeyup="checkDecimal(this);" name="package[box_wt][]" class="form-control strict_numeric box_wt"></td>
                                <td class="fish_action">
                                	<button type="button" class="btn btn-success btn-sm add_fish" data-toggle="tooltip" data-title="Add New Fish">
                                		<i class="fa fa-plus"></i>
                                    </button>
                                </td>
                            </tr> 
                        </tbody>
                        <tfoot>
                        	<tr>
                                <td>
                                    <input type="hidden" name="client_id" id="client_id" value="" />
                                    <input type="hidden" name="client_name" id="client_name" value="" />
                                    <input type="hidden" name="client_mobile" id="client_mobile" value="" />
                                    <input type="hidden" name="client_email" id="client_email" value="" />
                                    <div class="editable-input">
                                    	<input class="form-control select-client" />
                                    	<span class="edit-icon change_customer"><i class="fa fa-times"></i></span>
              						</div>
                                </td>
                                <td>
									<?php /*?>
                                	<div class="checkbox">
										<label>
											<input type="checkbox" name="box_number" class="box_number" value="Yes" class="grey">
											<span class="bold">Make Box</span>
										</label>
									</div>
                                    <?php */?>
                                    <div class="input-group">
										<span class="input-group-addon">Box Number</span>
                                        <input type="text" name="box_number" readonly="readonly" class="form-control disabled" placeholder="Box Number" data-title="Box Number" data-toggle="tooltip" autocomplete="off">
										<span class="input-group-addon" style="background-color: #FFF; border: #FFF;"> <input type="checkbox" name="make_box" class="make_box" value="Yes" /> </span>
									</div>									
                                </td>
                                <td>
                                	<div class="input-group">
                                      <span class="input-group-addon">T. Carret Weight</span>
                                      <input type="hidden" name="carret_quantity" value="0" />
                                      <input type="text" name="carret_weight" class="form-control" readonly="readonly" placeholder="Carret Weight" data-title="Total Carret Weight" data-toggle="tooltip">
                                     </div>
                                </td>
                                <td>
                                	<div class="input-group">
                                      <span class="input-group-addon">T. Box Weight</span>
                                      <input type="text" name="box_weight" class="form-control strict_numeric" readonly="readonly" placeholder="Box Weight" onkeyup="checkDecimal(this);" data-title="Total Box Weight" data-toggle="tooltip">
                                     </div>
                                </td>
                            </tr>    
                        </tfoot>                
                    </table>
                    </div>
                </div>
                <div class="row">
                	<div class="col-sm-12 ajax_reponse"></div>
                </div>
                <div class="row">
                	<div class="col-sm-12 text-center">
                        <?=fish_type_selector()?>
                        <button type="button" name="save_box" class="btn btn-success text-left save_carret" tabindex="4">Save Carret</button>
                    </div>
                </div>
			<?php echo form_close();?>
            <table class="table hidden" id="asset-table">
               <tr>
                    <td>
                    	<input type="hidden" class="fish_name" name="package[fish_name][]" value="" />
                        <input type="hidden" class="fish_rate" name="package[fish_rate][]" value="" />
                        <select class="form-control fish_code" name="package[fish_code][]">
                          <option value="">Select Fish</option>
                          <?php
                            if(!empty($all_fishes)){
                                foreach($all_fishes as $row){
                                    $op_val = $row['code'];
                                    $op_text = $row['name'];
									$fish_rate = $row['fish_rate'];
									$fish_type = $row['type'];
									$fish_dhalta = $row['dhalta'];
                          ?>
                          <option value="<?=$op_val?>" data-fish_name="<?=$op_text?>" data-fish_rate="<?=$fish_rate?>" data-fish_type="<?=$fish_type?>" data-fish_dhalta="<?=$fish_dhalta?>"><?=$op_val.' ('.$op_text.')'?></option>
                          <?php }} ?>
                        </select>
                    </td>
                    <td><input type="text" name="package[fish_qty][]" class="form-control strict_integer fish_qty"></td>
                    <td><input type="text" onkeyup="checkDecimal(this);" name="package[fish_wt][]" class="form-control strict_numeric fish_wt"></td>
                    <td><input type="text" onkeyup="checkDecimal(this);" name="package[box_wt][]" class="form-control strict_numeric box_wt"></td>
                    <td class="fish_action">
                    	<button type="button" class="btn btn-success btn-sm add_fish" data-toggle="tooltip" data-title="Add New Fish">
                        	<i class="fa fa-plus"></i>
                        </button>
                    </td>
                </tr>              
            </table>
		</div>
      </div>
    </div>
  </div>
</div>
