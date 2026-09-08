<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
	.circle-inactive{ background-color: #ddd; border-color: #ddd;color: #ffffff;}
	.required:after{content: " *"; color: #00b3f0;}	
	.break_name{margin: 0px 10px;}
	.break_name input{	width: 131px;}
	.hidden_field{ display:none;}
</style>
<div class="row">
	<div class="col-sm-8">
    	<?=validation_errors();?>
    </div>
</div>
<?=form_open('settings/business/add_batch', 'class="form-vertical hoursStyles common-css" id="addBatch" ')?>

<?php
	$location_name 	 		= set_value('location_name');
	$loctn_type 	 		= set_value('location_type');
	$book_location_online 	= set_value('book_location_online');
	$require_address 		= set_value('require_address');
	$address_line1 	 		= set_value('address_line1');
	$address_line2 	 		= set_value('address_line2');
	$city 			 		= set_value('city');
	$telephone 				= set_value('telephone');
	$location_type 	 		= ($loctn_type)?$loctn_type:'1';
	
	$book_location_check = '';
	if($book_location_online == 1){
		$book_location_check = 'checked="checked"';
	}
	
	$req_address_check = '';
	if($require_address == 1){
		$req_address_check = 'checked="checked"';
	}
	
	$fixed 	= '';
	$mobile = '';
	$req_add_div = '';
	$add_ofc_address_div = '';
	$adrs_div = '';
	$adrs1_req = 'required';
	$city_req = 'required';
	if($location_type == 1){
		$fixed = 'active';
		$req_add_div = 'hidden';
		$adrs_div = '';
		$add_ofc_address_div = 'hidden';
	}elseif($location_type == 2){
		$mobile	= 'active';
		$add_ofc_address_div = '';
		$req_add_div = '';
		$adrs_div = 'hidden';
		$adrs1_req 	= '';
		$city_req 	= '';
		if(!empty($address_line1) || !empty($address_line2) || !empty($city)){
			$add_ofc_address_div = 'hidden';
			$adrs_div = '';
		}
	}
?>

<div class="row">
  <div class="col-md-4">
    <h3> Location details </h3>
    <p> Choose branch ,course and enter in the batch details. </p>
  </div>
  <div class="col-sm-8"> </div>    
</div>
  
<hr>
<div class="row">
  <div class="col-md-4">
    <h3>Hours</h3>
    <p>Choose the standard opening times for this batch. </p>
  </div>
  <div class="col-sm-8">
    <div class="hour">
    <?php	
	$weekdays = array('monday','tuesday','wednesday','thursday','friday','saturday','sunday');
	if(!empty($weekdays)){
		foreach($weekdays as $dname){
			$isDayActive = set_value($dname.'_active');
			$day_break_name = set_value($dname.'[break][name]');
	?>
    	<div class="form-group day_hours">
        <input type="checkbox" name="<?=($dname)?>_active" id="<?=($dname)?>" <?=($isDayActive ? 'checked="checked"' : '');?>  value="1" />
        <div class="btn-group">
          <label for="<?=($dname)?>" class="btn circle-dr add_break_hour day-circle <?php if($isDayActive){ echo 'btn-info';}else{echo 'circle-inactive';}?>">
          	<span class="glyphicon glyphicon-ok <?php if($isDayActive){echo 'glyphicon-ok';}?>"></span> <span></span>
          </label>
          <label for="fancy-checkbox-info" class="day-dr"> <strong><?=lang($dname)?></strong> </label>
          <label class="day-unit <?php if(!$isDayActive){echo 'hidden';}?>">
            <select name="<?=($dname)?>[from_hour]" class="form-control drop-select select-menu <?=($dname)?>_fh hidden_field" >
              <?php 
					$hours = lang('hours');
					$selected_attribute = ''; 
					$selected = set_value($dname.'[from_hour]','09');
					foreach($hours as $key => $value){
					if($selected != ''){
						if($selected == $key){
							$selected_attribute = 'selected="selected"'; 
						}else{
							$selected_attribute = '';
						}
					}
				?>
              <option <?=$selected_attribute?> value="<?=$key?>"> <?=$value?></option>
              <?php } ?>
            </select>
          </label>
          <label class="day-unit <?php if(!$isDayActive){echo 'hidden';}?>">
            <select name="<?=($dname)?>[from_min]" class="form-control drop-select select-menu2 <?=($dname)?>_fm hidden_field">
              <?php 
					$seconds = lang('minutes');
					$selected_attribute = ''; 
					$selected = set_value($dname.'[from_min]', '00');
					foreach($seconds as $key => $value){
					if($selected != ''){
						if($selected == $key){
							$selected_attribute = 'selected="selected"'; 
						}else{
							$selected_attribute = '';
						}
					}
				?>
              <option <?=$selected_attribute?> value="<?=$key?>"> <?=$value?> </option>
              <?php } ?>
            </select>
          </label>
          <label class="day-unit <?php if(!$isDayActive){echo 'hidden';}?>">
            <select name="<?=($dname)?>[from_ampm]" class="form-control drop-select select-menu3 <?=($dname)?>_fampm hidden_field">
              <?php 
					$ampm = lang('ampm');
					$selected_attribute = ''; 
					$selected = set_value($dname.'[from_ampm]', 'am');
					foreach($ampm as $key => $value){
					if($selected != ''){
						if($selected == $key){
							$selected_attribute = 'selected="selected"'; 
						}else{
							$selected_attribute = '';
						}
					}
				?>
              <option <?=$selected_attribute?> value="<?=$key?>"> <?=$value?> </option>
              <?php } ?>
            </select>
          </label>
          <span class="day-unit hidden_field <?php if(!$isDayActive){echo 'hidden';}?>" style="padding: 0px 8px;">to</span>
          <label class="day-unit <?php if(!$isDayActive){echo 'hidden';}?>">
            <select name="<?=($dname)?>[to_hour]" class="form-control drop-select select-menu <?=($dname)?>_th hidden_field">
              <?php 
					$hours = lang('hours');
					$selected_attribute = ''; 
					$selected = set_value($dname.'[to_hour]', '06');
					foreach($hours as $key => $value){
					if($selected != ''){
						if($selected == $key){
							$selected_attribute = 'selected="selected"'; 
						}else{
							$selected_attribute = '';
						}
					}
				?>
              <option <?=$selected_attribute?> value="<?=$key?>"> <?=$value?> </option>
              <?php } ?>
            </select>
          </label>
          <label class="day-unit <?php if(!$isDayActive){echo 'hidden';}?>">
            <select name="<?=($dname)?>[to_min]" class="form-control drop-select select-menu2 <?=($dname)?>_tm hidden_field">
              <?php 
					$seconds = lang('minutes');
					$selected_attribute = ''; 
					$selected = set_value($dname.'[to_min]', '00');
					foreach($seconds as $key => $value){
					if($selected != ''){
						if($selected == $key){
							$selected_attribute = 'selected="selected"'; 
						}else{
							$selected_attribute = '';
						}
					}
				?>
              <option <?=$selected_attribute?> value="<?=$key?>"><?=$value?></option>
              <?php } ?>
            </select>
          </label>
          <label class="day-unit <?php if(!$isDayActive){echo 'hidden';}?>">
            <select name="<?=($dname)?>[to_ampm]" class="form-control drop-select select-menu3 <?=($dname)?>_tampm hidden_field">
              <?php 
					$ampm = lang('ampm');
					$selected_attribute = ''; 
					$selected = set_value($dname.'[to_ampm]', 'pm');
					foreach($ampm as $key => $value){
					if($selected != ''){
						if($selected == $key){
							$selected_attribute = 'selected="selected"'; 
						}else{
							$selected_attribute = '';
						}
					}
				?>
              <option <?=$selected_attribute?> value="<?=$key?>"> <?=$value?> </option>
              <?php } ?>
            </select>
          </label>
          <label class="day-unit <?php if(!$isDayActive){echo 'hidden';}?>" >
           <a href="javascript:void(0);" class="add_break_hour"> <i class="fa fa-plus"></i> Batch </a>
          </label>
        </div>
      </div>
    <?php
    	if(isset($day_break_name) && !empty($day_break_name)){
			for($bri = 0; $bri < count($day_break_name); $bri++){					
				$break_name = $day_break_name[$bri];
				$fh_db = set_value($dname.'[break][from_hour]['.$bri.']', '01');
				$fm_db = set_value($dname.'[break][from_min]['.$bri.']', '00');
				$fampm_db = set_value($dname.'[break][from_ampm]['.$bri.']', 'pm');
				$th_db = set_value($dname.'[break][to_hour]['.$bri.']', '02');
				$tm_db = set_value($dname.'[break][to_min]['.$bri.']', '00');
				$tampm_db = set_value($dname.'[break][to_ampm]['.$bri.']', 'pm');
				/*echo '<br>'.$fh_db 		= @$day_break_from_hour[$bri];
				echo '<br>'.$fm_db 		= @$day_break_from_min[$bri];
				echo '<br>'.$fampm_db 	= @$day_break_from_ampm[$bri];
				echo '<br>'.$th_db 		= @$day_break_to_hour[$bri];
				echo '<br>'.$tm_db 		= @$day_break_to_minute[$bri];
				echo '<br>'.$tampm_db 	= @$day_break_to_ampm[$bri];*/
	?>
    
    <div class="form-group break_group_<?=($dname)?>">
        <label class="break_name">
        	<input type="text" placeholder="Break Name" value="<?=$break_name?>" name="<?=($dname)?>[break][name][]" class="form-control">
        </label>
        <div class="btn-group">
        <label class="day-unit">
        <select name="<?=($dname)?>[break][from_hour][]" class="form-control drop-select select-menu <?=($dname)?>_fh">
        <?php 
        $hours = lang('hours');
        $selected_attribute = ''; 
        $selected = $fh_db;
        foreach($hours as $key => $value){
            if($selected != ''){
                if($selected == $key){
                    $selected_attribute = 'selected="selected"'; 
                }else{
                    $selected_attribute = '';
                }
            }
        ?>
        <option <?=$selected_attribute?> value="<?=$key?>"><?=$value?></option>
        <?php } ?>
        </select>
        </label>
        <label class="day-unit">
        <select name="<?=($dname)?>[break][from_min][]" class="form-control drop-select select-menu2 <?=($dname)?>_fm">
        <?php 
        $seconds = lang('minutes');
        $selected_attribute = ''; 
        $selected = $fm_db;
        foreach($seconds as $key => $value){
			if($selected != ''){
				if($selected == $key){
					$selected_attribute = 'selected="selected"'; 
				}else{
					$selected_attribute = '';
				}
			}
        ?>
        <option <?=$selected_attribute?> value="<?=$key?>"><?=$value?></option>
        <?php } ?>
        </select>
        </label>
        <label class="day-unit">
        <select name="<?=($dname)?>[break][from_ampm][]" class="form-control drop-select select-menu3 <?=($dname)?>_fampm">
        <?php 
        $ampm = lang('ampm');
			$selected_attribute = ''; 
			$selected = $fampm_db;
			foreach($ampm as $key => $value){
				if($selected != ''){
					if($selected == $key){
						$selected_attribute = 'selected="selected"'; 
					}else{
						$selected_attribute = '';
				}
			}
        ?>
        <option <?=$selected_attribute?> value="<?=$key?>"> <?=$value?> </option>
        <?php } ?>
        </select>
        </label>
        <span class="day-unit" style="padding: 0px 8px;">to</span>
        <label class="day-unit">
        <select name="<?=($dname)?>[break][to_hour][]" class="form-control drop-select select-menu <?=($dname)?>_th">
        <?php 
        $hours = lang('hours');
        $selected_attribute = ''; 
        $selected = $th_db;
        foreach($hours as $key => $value){
			if($selected != ''){
				if($selected == $key){
					$selected_attribute = 'selected="selected"'; 
				}else{
					$selected_attribute = '';
				}
			}
        ?>
        <option <?=$selected_attribute?> value="<?=$key?>"> <?=$value?></option>
        <?php } ?>
        </select>
        </label>
        <label class="day-unit">
        <select name="<?=($dname)?>[break][to_min][]" class="form-control drop-select select-menu2 <?=($dname)?>_tm">
        <?php 
        $seconds = lang('minutes');
        $selected_attribute = ''; 
        $selected = $tm_db;
        foreach($seconds as $key => $value){
			if($selected != ''){
				if($selected == $key){
					$selected_attribute = 'selected="selected"'; 
				}else{
					$selected_attribute = '';
				}
			}
        ?>
        <option <?=$selected_attribute?> value="<?=$key?>"><?=$value?></option>
        <?php } ?>
        </select>
        </label>
        <label class="day-unit">
        <select name="<?=($dname)?>[break][to_ampm][]" class="form-control drop-select select-menu3 <?=($dname)?>_tampm">
        <?php 
        $ampm = lang('ampm');
        $selected_attribute = ''; 
        $selected = $tampm_db;
        foreach($ampm as $key => $value){
			if($selected != ''){
				if($selected == $key){
					$selected_attribute = 'selected="selected"'; 
				}else{
					$selected_attribute = '';
				}
			}
        ?>
        <option <?=$selected_attribute?> value="<?=$key?>"> <?=$value?> </option>
        <?php } ?>
        </select>
        </label>
        <label class="day-unit" id=""><a href="javascript:void(0);" class="text-danger"><i class="fa fa-trash"></i> Remove</a></label>
        </div>
    </div>
    <?php }}/*Break Loop End*/}} ?>
    </div>
  </div>
</div>

<div class="text-right">
  <a href="<?=site_url('settings/business/batchs')?>" class="btn btn-default padding-dr">Cancel</a>
  <button type="submit" class="btn btn-bg-dr padding-dr">Save</button>
</div>
<?=form_close()?>
<script>
var json_course = '<?=$json_course?>';
var json_location = '<?=$json_location?>';
</script>