<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- start: BREADCRUMB -->
  <h3 class="text-center"><?=$page_title?></h3>
  <?php echo form_open('', 'class="form-horizontal dp_form"');?>
  <input type="hidden" id="action_mode" name="action_mode" value="<?=$action_mode?>" />
  <input type="hidden" id="production_id" name="production_id" value="<?=$production_id?>" />
  <input type="hidden" id="production_type" name="production_type" value="<?=$production_data['production_type']?>" />
  <div class="form-group">
    <div class="col-sm-8 ajax-response"></div>
  </div>
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
				$selected = '';
				if($op_val == $production_data['depot_id']){
					$selected = 'selected="selected"';
				}
        ?>
        <option <?=$selected?> value="<?=$op_val?>" data-code="<?=$op_code?>"><?=$op_text?></option>
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
				$selected = '';
				if($op_val == $production_data['market_id']){
					$selected = 'selected="selected"';
				}
        ?>
        <option <?=$selected?> value="<?=$op_val?>" data-code="<?=$op_code?>"><?=$op_text?></option>
        <?php }} ?>
      </select>
    </div>
    <div class="col-sm-2">
      <label class="control-label">Date :  <span class="symbol required"></span></label>
      <input type="text" name="production_date" autocomplete="off" class="form-control input-sm <?=$datepicker_class?>" value="<?=get_datetime('d/m/Y',$production_data['production_date'])?>" />
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
    <div class="col-md-3">
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
    <div class="col-md-3">
        <label class="control-label">Total Point Weight :</label>
        <input type="text" name="total_pt_wt" class="form-control input-sm" readonly="readonly" value="<?=$production_data['total_pt_wt']?>">
    </div>
  </div>
  <?php }else{ ?>
  <div class="form-group">
    <?php if($production_data['production_type'] == "Transfer"){ ?>
        <div class="col-sm-3">
          <label class="control-label">From Depot :  <span class="symbol required"></span></label>
          <select class="form-control input-sm" name="market_id">
            <option value="" data-code="">Select Depot</option>
            <?php
            if(!empty($all_depots)){
                foreach($all_depots as $row){
                    $op_val = $row['ID'];
                    $op_text = $row['name'];
                    $op_code = $row['code'];
                    $selected = '';
                    if($op_val == $production_data['market_id']){
                        $selected = 'selected="selected"';
                    }
            ?>
            <option <?=$selected?> value="<?=$op_val?>" data-code="<?=$op_code?>"><?=$op_text?></option>
            <?php }} ?>
          </select>
        </div>
        <div class="col-sm-3">
          <label class="control-label">To Depot :  <span class="symbol required"></span></label>
          <select class="form-control input-sm" name="depot_id">
            <option value="" data-code="">Select Depot</option>
            <?php
            if(!empty($all_depots)){
                foreach($all_depots as $row){
                    $op_val = $row['ID'];
                    $op_text = $row['name'];
                    $op_code = $row['code'];
                    $selected = '';
                    if($op_val == $production_data['depot_id']){
                        $selected = 'selected="selected"';
                    }
            ?>
            <option <?=$selected?> value="<?=$op_val?>" data-code="<?=$op_code?>"><?=$op_text?></option>
            <?php }} ?>
          </select>
        </div>
    <?php }else{ ?>
    	<div class="col-sm-3">
          <label class="control-label">Depot :  <span class="symbol required"></span></label>
          <select class="form-control input-sm" name="depot_id">
            <option value="" data-code="">Select Depot</option>
            <?php
            if(!empty($all_depots)){
                foreach($all_depots as $row){
                    $op_val = $row['ID'];
                    $op_text = $row['name'];
                    $op_code = $row['code'];
                    $selected = '';
                    if($op_val == $production_data['depot_id']){
                        $selected = 'selected="selected"';
                    }
            ?>
            <option <?=$selected?> value="<?=$op_val?>" data-code="<?=$op_code?>"><?=$op_text?></option>
            <?php }} ?>
          </select>
        </div>
        <div class="col-sm-3">
          <label class="control-label">Point :  <span class="symbol required"></span></label>
          <select class="form-control input-sm" name="market_id">
            <option value="" data-code="">Select Point</option>
            <?php
            if(!empty($all_points)){
                foreach($all_points as $row){
                    $op_val = $row['ID'];
                    $op_text = $row['name'];
                    $op_code = $row['code'];
                    $selected = '';
                    if($op_val == $production_data['market_id']){
                        $selected = 'selected="selected"';
                    }
            ?>
            <option <?=$selected?> value="<?=$op_val?>" data-code="<?=$op_code?>"><?=$op_text?></option>
            <?php }} ?>
          </select>
        </div>
    <?php } ?>
    
    <div class="col-sm-2">
      <label class="control-label">Date :  <span class="symbol required"></span></label>
      <input type="text" name="production_date" autocomplete="off" class="form-control input-sm <?=$datepicker_class?>" value="<?=get_datetime('d/m/Y',$production_data['production_date'])?>" />
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
      <button type="button" class="btn btn-primary btn-sm save_data" data-url="<?=site_url('production/ajax_save_dp_data')?>">Update</button>&nbsp;
      <a type="button" class="btn btn-warning btn-sm" href="<?=site_url('production/add_dp/edit/'.strtolower($production_data['production_type']).'/'.$production_id)?>">Cancel</a>
    </div>
  </div>
  
  <?php echo form_close();?>