<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style>
	.circle-inactive{
		background-color: #ddd;
		border-color: #ddd;
		color: #ffffff;
	}
	.required:after{
		content: " *";
		color: #00b3f0;
	}
	
	.break_name{
		margin: 0px 10px;
	}
	.break_name input{
		width: 131px;
	}
</style>
<div class="row">
	<div class="col-sm-8">
    	<?=validation_errors();?>
        <?php 
		if($this->session->flashdata('message_success')){
			echo '<div class="alert alert-success" style="margin-top:20px;"> '. $this->session->flashdata('message_success') .'&nbsp;<button data-dismiss="alert" class="close">×</button></div>';
		}elseif($this->session->flashdata('message_failed')){
			echo '<div class="alert alert-danger" style="margin-top:20px;"> '. $this->session->flashdata('message_failed') .'&nbsp;<button data-dismiss="alert" class="close">×</button></div>'; 
		}
		?>
    </div>
</div>
<?=form_open('settings/business/add_location', 'class="form-vertical hoursStyles common-css" id="Locations_step_first" ')?>
<div class="row">
  <div class="col-md-12">
    <div class="alert alert-info"> <i class="fa fa-info-circle fa-2x pull-left"></i>
      <p class="lead"> A location in Practice is your place of business. This is where your bookings will take place. </p>
      <div class="clearfix"></div>
    </div>
  </div>
</div>

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
    <h3> <?=lang('location_details_label')?> </h3>
    <p>  <?=lang('location_details_desc')?>  </p>
  </div>
  <div class="col-sm-8">  
        <div class="form-group">
          <label for="branch_name" class="required"><strong> <?=lang('location_name_label')?> </strong></label>
          <input class="form-control" id="branch_name" name="branch_name" value="<?=$location_name;?>" type="text">
        </div>
        
            <div class="row">             	
                <div class="parentClone">
                    <div class="col-sm-11">  
                    <div class="form-group" id="location_telephone">
                      <label for="telephone_when_fixed"><strong> <?=lang('phone_label')?> </strong></label>
                      <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-earphone"></i></span>
                        <input type="text" name="telephone[]" id="telephone" class="form-control" value="">
                      </div>
                    </div> 
                    </div> 
                                      
                    <div class="col-sm-1" id="actionHandler">            
                        <label for=""></label>
                        <button type="button" class="btn btn-info btn-xm" id="add_more_phone"  style="display:inherit; margin-top: 4px;"> +  </button>
                    </div>
                 </div> 
            </div>   
           
    
        <div class="col-sm-6">
            <div class="form-group">
                <label class="<?=$adrs1_req?> label_address_line1" for="address_line1"><strong><?=lang('address_label')?></strong></label>
                <input type="text" name="address_line1" id="address_line1" class="form-control" value="<?=$address_line1?>">
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label class="pincode" for="pincode"><strong><?=lang('pincode_label')?></strong></label>
                <input type="text" name="pincode" id="pincode" class="form-control" value="">
            </div>
        </div>
    
        <div class="col-sm-6">
            <div class="form-group">
                <label class="city" for="city"><strong><?=lang('city_label')?></strong></label>
                <input type="text" name="city" id="city" class="form-control" value="<?=$city?>">
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label class="state" for="address_line1"><strong><?=lang('state_label')?></strong></label>
                <input type="text" name="state" id="state" class="form-control" value="">
            </div>
        </div>            
  	</div>    
</div>
  
<hr>
<div class="row">
  <div class="col-md-4">
    <h3> <?=lang('hours_label')?> </h3>
    <p> <?=lang('hours_desc')?> </p>
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
          <label for="<?=($dname)?>" class="btn circle-dr day-circle <?php if($isDayActive){ echo 'btn-info';}else{echo 'circle-inactive';}?>">
          	<span class="glyphicon glyphicon-ok <?php if($isDayActive){echo 'glyphicon-ok';}?>"></span> <span></span>
          </label>
          <label for="fancy-checkbox-info" class="day-dr"> <strong><?=lang($dname)?></strong> </label>
          <label class="day-unit <?php if(!$isDayActive){echo 'hidden';}?>">
            <select name="<?=($dname)?>[from_hour]" class="form-control drop-select select-menu <?=($dname)?>_fh">
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
            <select name="<?=($dname)?>[from_min]" class="form-control drop-select select-menu2 <?=($dname)?>_fm">
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
            <select name="<?=($dname)?>[from_ampm]" class="form-control drop-select select-menu3 <?=($dname)?>_fampm">
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
          <span class="day-unit <?php if(!$isDayActive){echo 'hidden';}?>" style="padding: 0px 8px;">to</span>
          <label class="day-unit <?php if(!$isDayActive){echo 'hidden';}?>">
            <select name="<?=($dname)?>[to_hour]" class="form-control drop-select select-menu <?=($dname)?>_th">
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
            <select name="<?=($dname)?>[to_min]" class="form-control drop-select select-menu2 <?=($dname)?>_tm">
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
            <select name="<?=($dname)?>[to_ampm]" class="form-control drop-select select-menu3 <?=($dname)?>_tampm">
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
          <label class="day-unit <?php if(!$isDayActive){echo 'hidden';}?>">
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
        <label class="day-unit"><a href="javascript:void(0);" class="text-danger"><i class="fa fa-trash"></i> Remove</a></label>
        </div>
    </div>
    <?php }}/*Break Loop End*/}} ?>
    </div>
  </div>
</div>

<div class="text-right">
  <a href="<?=site_url('settings/business/locations')?>" class="btn btn-default padding-dr"><?=lang('cancel_button')?></a>
  <button type="submit" class="btn btn-bg-dr padding-dr"><?=lang('save_button')?></button>
</div>
<?=form_close()?>
<script>
var json_course = '<?=$json_course?>';
</script>