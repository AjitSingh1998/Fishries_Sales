<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!-- start: BREADCRUMB -->
<style>
input[type="text"].form-control:focus,select.form-control:focus,textarea.form-control:focus {
    border-color: #66afe9 !important;
    outline: 0 !important;
    -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,0.075), 0 0 8px rgba(102,175,233,0.6) !important;
    box-shadow: inset 0 1px 1px rgba(0,0,0,0.075), 0 0 8px rgba(102,175,233,0.6) !important;
}
.table .table{margin:0px !important;}
.table .table tr:last-child th, .table .table tr:last-child td{border-bottom:none !important;}
.listinge_header th, tr, td, #box_listing_container th, tr, td { text-align: center !important; }
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
extract($header);
?>
<div class="container-fluid container-fullw">
    <div class="row">
        <div class="col-sm-10 col-sm-offset-1">
            <div class="panel panel-white" id="panel_container">
                <div class="panel-heading border-light light-bg">
                        <div class="row">
                        	<div class="col-sm-12 text-center">
                            <h3>
                                <a href="<?=$go_back_url?>" class="" data-toggle="tooltip" data-title="Go Back">
                                <i class="fa fa-arrow-circle-left"></i></a>
                                <?=$page_title?>
                            </h3>
                            <?php if($action_mode == 'edit'){?>
                            <p class="no-margin">
								<?=$package_date?> / <?=(isset($depot['name']) ? $depot['name'] : '')?> / <?=(isset($point['name']) ? $point['name'] : '')?>
                                &nbsp;&nbsp;
                            	<button type="button" class="btn btn-warning btn-xs export_data" data-toggle="tooltip" data-title="Download Excel" data-target="panel-body-container" data-filename="Prepared Boxes"> <i class="fa fa-file-excel-o"></i></button>&nbsp;
                                <a id="dlink" style="display:none;"></a>
                                <button type="button" class="btn btn-success btn-xs print_data" data-toggle="tooltip" data-title="Print"><i class="fa fa-print"></i></button>
                            	<input type="checkbox" id="expand_report_container" class="js-switch" data-target="#panel-body-container" />
                            </p>
                        <?php } ?>
                        </div>
                        </div>
                        <?php if($action_mode == 'add'){?>
                        <div class="row">
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label class="control-label">Date <span class="symbol required"></span></label>
                                    <input type="text" class="form-control p_date <?=$disabled_class?> <?=$datepicker?>" placeholder="dd/mm/yyyy" value="<?=$package_date;?>">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label class="control-label">Depot: <span class="symbol required"></span></label>
                                    <select class="form-control <?=$disabled_class?> p_depot_id">
                                        <?php
                                        $mp_code = '';
                                        if($action_mode == 'add'){
                                            echo '<option value="">Select Depot</option>';
                                        }
                                        $depot_value = set_value('depot_id', @$depot_id);
                                        if(!empty($all_depots)){
                                            foreach($all_depots as $row){
                                                $op_val = $row['ID'];
                                                $op_text = $row['name'];
                                                $op_code = $row['code'];
                                            
                                            if($depot_value == $op_val){
                                                $mp_code = $op_code;
                                                $selected = 'selected="selected"';
                                            }else{
                                                $selected = '';
                                            }
                                            if($action_mode == 'add'){
                                        ?>
                                        <option <?=$selected?> data-code="<?=$op_code?>" value="<?=$op_val?>"><?=$op_text?></option>
                                        <?php }elseif($action_mode == 'edit' && $selected != ''){?>
                                        <option <?=$selected?> data-code="<?=$op_code?>" value="<?=$op_val?>"><?=$op_text?></option>
                                        <?php }}}
                                        ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label class="control-label">Point: <span class="symbol required"></span></label>
                                    <select class="form-control <?=$disabled_class?> p_market_id">
                                        <?php
                                        if($action_mode == 'add'){
                                            echo '<option value="">Select Depot</option>';
                                        }
                                        $point_value = set_value('market_id', @$market_id);
                                        if(!empty($market_places)){
                                            foreach($market_places as $row){
                                                $op_val = $row['ID'];
                                                $op_text = $row['name'];
                                            
                                                if($point_value == $op_val){
                                                    $selected = 'selected="selected"';
                                                }else{
                                                    $selected = '';
                                                }
                                                if($action_mode == 'add'){
                                        ?>
                                        <option <?=$selected?> value="<?=$op_val?>"><?=$op_text?></option>
                                        <?php }elseif($action_mode == 'edit' && $selected != ''){?>
                                        <option <?=$selected?> value="<?=$op_val?>"><?=$op_text?></option>
                                        <?php }}}
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group margin-top-20">
                                    <button type="button" class="btn btn-warning export_data" data-toggle="tooltip" data-title="Download Excel" data-target="panel-body-container" data-filename="Prepared Boxes"> 
                                        <i class="fa fa-file-excel-o"></i></button>&nbsp;
                                    <a id="dlink" style="display:none;"></a>
                                    <button type="button" class="btn btn-success print_data" data-toggle="tooltip" data-title="Print"> 
                                        <i class="fa fa-print"></i></button>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                </div>
                <div class="panel-body" id="panel-body-container">
                    <table class="table listinge_header" width="100%" id="header_table">
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
                                <th style="width:100px;"><span data-title="Total Quantity" data-toggle="tooltip">Total Qty</span></th>
                                <th style="width:100px;"><span data-title="Total Quantity" data-toggle="tooltip">Total WT</span></th>
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
                                            $row_item = !empty($row) ? explode('/', $row) : array();
                                            $box_items[] = array('box_number' 	=> $box_data['box_number'], 
                                                                 'fish_code'	=> @$row_item[0],
																 'fish_name'	=> @$row_item[1],
                                                                 'fish_qty' 	=> @$row_item[2], 
                                                                 'fish_wt' 		=> @$row_item[3],
																 'box_wt' 		=> @$row_item[4]);
                                        }
                                        echo generate_box_list_html($i, $box_data, $box_items, $action_mode);
                                        $i++;
                                    }                            
                                }
                            }
                            ?>
                            </tbody>                
                        </table>
                    </div>
                </div>
                <div class="panel-footer border-light light-bg">
                
                	<table class="table listinge_header" width="100%" id="header_table">
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
                                <th style="width:100px;"><span data-title="Total Quantity" data-toggle="tooltip">Total Qty</span></th>
                                <th style="width:100px;"><span data-title="Total Quantity" data-toggle="tooltip">Total WT</span></th>
                                <th style="width:100px;" class="no-print no-exl">Action</th>
                            </tr>                    
                        </thead>
                    </table>
                
                    <?php echo form_open('dispatch/save_product_box', 'class="form-horizontal" id="package_form"');?>
                    <input type="hidden" name="action_mode" value="<?=$action_mode?>" />
                    <?php if($action_mode == 'edit'){?>
                    <input type="hidden" name="depot_id" id="depot_id" value="<?=$depot['ID']?>" />
                    <input type="hidden" name="mp_code" id="mp_code" value="<?=$depot['code']?>" />
                    <input type="hidden" name="market_id" id="market_id" value="<?=$point['ID']?>" />
                    <?php }else{ ?>
                    <input type="hidden" name="depot_id" id="depot_id" value="<?=$depot_value?>" />
                    <input type="hidden" name="mp_code" id="mp_code" value="<?=$mp_code?>" />
                    <input type="hidden" name="market_id" id="market_id" value="<?=$point_value?>" />
                    <?php } ?>
                    <input type="hidden" name="packing_date" id="packing_date" value="<?=$package_date;?>" />
                        <div class="row">
                            <div class="col-sm-12">
                               <table class="table">
                                <thead>
                                    <tr>
                                        <th>Fish <span class="symbol required"></span></th>
                                        <th>Quantity <span class="symbol required"></span></th>
                                        <th>Carret Wt <span class="symbol required"></span></th>
                                        <th>Box Wt <span class="symbol required"></span></th>
                                        <th class="no-print">Action</th>
                                    </tr>                    
                                </thead>
                                <tbody id="package-container">
                                   <tr>
                                         <td>
                                         <input type="hidden" class="fish_name" name="package[fish_name][]" value="" />
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
										  <option value="<?=$op_val?>" data-fish_name="<?=$op_text?>" data-fish_rate="<?=$fish_rate?>" data-fish_type="<?=$fish_type?>" data-fish_dhalta="<?=$fish_dhalta?>"><?=$op_val.' ('.$op_text.')'?></option>
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
                                        <th>Box Summary</th>
                                        <td>
                                            <div class="input-group">
                                              <span class="input-group-addon">Box Number</span>
                                              <input type="text" name="box_number" class="form-control box_number" placeholder="Box Number" data-title="Box Number" data-toggle="tooltip" autocomplete="off" tabindex="4">
                                             </div>
                                         </td>
                                        <td>
                                            <div class="input-group">
                                              <span class="input-group-addon">T. Carret Weight</span>
                                              <input type="hidden" name="carret_quantity" value="0" />
                                              <input type="text" name="carret_weight" class="form-control" readonly="readonly" placeholder="Total Carret Weight" data-title="Total Carret Weight" data-toggle="tooltip">
                                             </div>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                              <span class="input-group-addon">T. Box Weight</span>
                                              <input type="text" name="box_weight" class="form-control strict_numeric" readonly="readonly" placeholder="Total Box Weight" onkeyup="checkDecimal(this);" data-title="Total Box Weight" data-toggle="tooltip">
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
                                <button type="button" name="save_box" class="btn btn-success text-left save_box" tabindex="5">Save Box</button>
                            </div>
                        </div>
                    <?php echo form_close();?>
                    <table class="table hidden" id="asset-table">
                       <tr>
                            <td>
                                <input type="hidden" class="fish_name" name="package[fish_name][]" value="" />
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
