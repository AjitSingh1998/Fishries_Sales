<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row">
  <div class="col-sm-12">
    <?=validation_errors();?>
  </div>
</div>
<?php
	if(!empty($locationData)){
		$location_name = set_value('branch_name', $locationData['branch_name']);
		$pincode = set_value('pincode', $locationData['pincode']);
		$state = set_value('state', $locationData['state']);
		$address_line1 = set_value('address_line1', $locationData['address']);
		$city = set_value('city', $locationData['city']);
		$telephone = set_value('telephone', $locationData['phone_number']);
		$location_id = $locationData['branch_id'];
?>
<style>
.circle-inactive{background-color: #ddd;border-color: #ddd;	color: #ffffff;	}
.required:after{content: " *";color: #00b3f0;}
.break_name{margin: 0px 10px;}
.break_name input{	width: 131px;}
</style>
<?=form_open('settings/business/edit_location/'.$location_id, 'class="form-vertical hoursStyles common-css" id="Locations_step_first" ')?>
<div class="row">
  <div class="col-md-12">
    <div class="alert alert-info"> <i class="fa fa-info-circle fa-2x pull-left"></i>
      <p class="lead"> A location in Practice is your place of business. This is where your bookings will take place. </p>
      <div class="clearfix"></div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-md-4">
    <h3> <?=lang('location_details_label')?> </h3>
    <p>  <?=lang('location_details_desc')?>  </p>
  </div>
  <div class="col-sm-8">
    <div class="form-group">
      <label for="branch_name" class="required"><strong><?=lang('location_name_label')?> </strong></label>
      <input class="form-control" id="branch_name" name="branch_name" value="<?=$location_name;?>" type="text">
    </div>
    <div class="row">
      <?php
				if(isset($telephone) && !empty($telephone)){
				$telephone = explode(',' , $telephone );
				$count = 1 ;
				foreach( $telephone as $phone ){
				if($count == 1 ){
					$btnClass = 'info';
					$btnHtml  = '+';
					$btnId    = 'add_more_phone';
				} else {
					$btnClass = 'danger';
					$btnHtml  = '<i class="fa fa-trash"></i>';
					$btnId    = 'remove_more_phone';
				}
				?>
      <div class="parentClone">
        <div class="col-sm-11">
          <div class="form-group" id="location_telephone">
            <label for="telephone_when_fixed"><strong><?=lang('phone_label')?></strong></label>
            <div class="input-group"> <span class="input-group-addon"><i class="glyphicon glyphicon-earphone"></i></span>
              <input type="text" name="telephone[]" id="telephone" class="form-control" value="<?=$phone?>">
            </div>
          </div>
        </div>
        <div class="col-sm-1" id="actionHandler">
          <label for=""></label>
          <button type="button" class="btn btn-<?=$btnClass?> btn-xm" id="<?=$btnId?>"  style="display:inherit;margin-top:4px;">
          <?=$btnHtml?>
          </button>
        </div>
      </div>
      <?php $count++; }  } ?>
    </div>
    <div class="col-sm-6">
      <div class="form-group">
        <label class=" label_address_line1" for="address_line1"><strong><?=lang('address_label')?></strong></label>
        <input type="text" name="address_line1" id="address_line1" class="form-control" value="<?=$address_line1?>">
      </div>
    </div>
    <div class="col-sm-6">
      <div class="form-group">
        <label class="pincode" for="pincode"><strong><?=lang('pincode_label')?></strong></label>
        <input type="text" name="pincode" id="pincode" class="form-control" value="<?=$pincode;?>">
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
        <input type="text" name="state" id="state" class="form-control" value="<?=$state;?>">
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
    <div class="xcol-sm-x">
      <div class="hour">
        <?php
		
		$weekdays = array();
		$isFromPost = set_value('monday[from_hour]');
		if(empty($isFromPost)){
			foreach($locationHourData as $loc_data){
				if($loc_data['day_status'] == 'Available' || $loc_data['day_status'] == 'Busy'){
					$weekdays[$loc_data['day_name']]['day_name'] = $loc_data['day_name'];
					$weekdays[$loc_data['day_name']]['from_time'] = $loc_data['from_time'];
					$weekdays[$loc_data['day_name']]['to_time'] = $loc_data['to_time'];
					$weekdays[$loc_data['day_name']]['day_status'] = $loc_data['day_status'];
				}
				if($loc_data['day_status'] == 'Break'){
					$weekdays[$loc_data['day_name']]['break']['name'][] = $loc_data['break_name'];
					$weekdays[$loc_data['day_name']]['break']['course'][] = $loc_data['course'];
					$weekdays[$loc_data['day_name']]['break']['from_time'][] = $loc_data['from_time'];
					$weekdays[$loc_data['day_name']]['break']['to_time'][] = $loc_data['to_time'];
				}
			}
		}else{
			$week = weekdays();
			for($w=0; $w<count($week); $w++){
				$week_day_name = $week[$w];
				
				$week_from_time = set_value($week_day_name.'[from_hour]').':'.set_value($week_day_name.'[from_min]').' '.set_value($week_day_name.'[from_ampm]');
				$week_to_time 	= set_value($week_day_name.'[to_hour]').':'.set_value($week_day_name.'[to_min]').' '.set_value($week_day_name.'[to_ampm]');


				$weekdays[$week_day_name]['day_name'] = $week_day_name;
				$weekdays[$week_day_name]['from_time'] = date('H:i:s', strtotime($week_from_time));
				$weekdays[$week_day_name]['to_time'] = date('H:i:s', strtotime($week_to_time));
				$weekdays[$week_day_name]['day_status'] = (set_value($week_day_name.'_active'))?"Available":"Busy";
				
				$break = set_value($week_day_name.'[break]');
				if(!empty($break)){
					for($a=0; $a<count($break['name']);$a++){						
						$break_from_time = ($break['from_hour'][$a]).':'.($break['from_min'][$a]).' '.($break['from_ampm'][$a]);
						$break_to_time	 = ($break['to_hour'][$a]).':'.($break['to_min'][$a]).' '.($break['to_ampm'][$a]);
						$weekdays[$week_day_name]['break']['name'][$a] 		= $break['name'][$a];
						$weekdays[$week_day_name]['break']['from_time'][$a] = date('H:i:s', strtotime($break_from_time));
						$weekdays[$week_day_name]['break']['to_time'][$a] 	= date('H:i:s', strtotime($break_to_time));
					}
					
					
				}
			}
		}
	?>
        <?php 
	if(!empty($weekdays)){
		foreach($weekdays as $day){
			$day_name = $day['day_name'];
			$isDayActive = ($day['day_status']=="Available")?"1":"0";
			$from_hour = date('h',strtotime($day['from_time']));
			$from_minute = date('i',strtotime($day['from_time']));
			$from_ampm = date('a',strtotime($day['from_time']));
			$to_hour = date('h',strtotime($day['to_time']));
			$to_minute = date('i',strtotime($day['to_time']));
			$to_ampm = date('a',strtotime($day['to_time']));
	?>
        <div class="form-group day_hours">
          <input type="checkbox" name="<?=$day_name?>_active" id="<?=$day_name?>" <?php if($isDayActive){echo 'checked="checked"';}?>  value="1" />
          <div class="btn-group">
            <label for="<?=$day_name?>" class="btn circle-dr day-circle <?php if($isDayActive){echo 'btn-info';}else{echo 'circle-inactive';}?>"> <span class="glyphicon <?php if($isDayActive){echo 'glyphicon-ok';}?>"></span> <span></span> </label>
            <label for="fancy-checkbox-info" class="day-dr"> <strong>
              <?=lang($day_name)?>
              </strong> </label>
            <label class="day-unit <?php if(!$isDayActive){echo 'hidden';}?>">
              <select name="<?=$day_name?>[from_hour]" class="form-control drop-select select-menu <?=$day_name?>_fh">
                <?php 
					$hours = lang('hours');
					$selected_attribute = ''; 
					$selected = $from_hour;
					foreach($hours as $key => $value){
					if($selected != ''){
						if($selected == $key){
							$selected_attribute = 'selected="selected"'; 
						}else{
							$selected_attribute = '';
						}
					}
				?>
                <option <?=$selected_attribute?> value="<?=$key?>">
                <?=$value?>
                </option>
                <?php } ?>
              </select>
            </label>
            <label class="day-unit <?php if(!$isDayActive){echo 'hidden';}?>">
              <select name="<?=($day_name)?>[from_min]" class="form-control drop-select select-menu2 <?=($day_name)?>_fm">
                <?php 
					$seconds = lang('minutes');
					$selected_attribute = ''; 
					$selected = $from_minute;
					foreach($seconds as $key => $value){
					if($selected != ''){
						if($selected == $key){
							$selected_attribute = 'selected="selected"'; 
						}else{
							$selected_attribute = '';
						}
					}
				?>
                <option <?=$selected_attribute?> value="<?=$key?>">
                <?=$value?>
                </option>
                <?php } ?>
              </select>
            </label>
            <label class="day-unit <?php if(!$isDayActive){echo 'hidden';}?>">
              <select name="<?=($day_name)?>[from_ampm]" class="form-control drop-select select-menu3 <?=($day_name)?>_fampm">
                <?php 
					$ampm = lang('ampm');
					$selected_attribute = ''; 
					$selected = $from_ampm;
					foreach($ampm as $key => $value){
					if($selected != ''){
						if($selected == $key){
							$selected_attribute = 'selected="selected"'; 
						}else{
							$selected_attribute = '';
						}
					}
				?>
                <option <?=$selected_attribute?> value="<?=$key?>">
                <?=$value?>
                </option>
                <?php } ?>
              </select>
            </label>
            <span class="day-unit <?php if(!$isDayActive){echo 'hidden';}?>" style="padding: 0px 8px;">to</span>
            <label class="day-unit <?php if(!$isDayActive){echo 'hidden';}?>">
              <select name="<?=($day_name)?>[to_hour]" class="form-control drop-select select-menu <?=($day_name)?>_th">
                <?php 
					$hours = lang('hours');
					$selected_attribute = ''; 
					$selected = $to_hour;
					foreach($hours as $key => $value){
					if($selected != ''){
						if($selected == $key){
							$selected_attribute = 'selected="selected"'; 
						}else{
							$selected_attribute = '';
						}
					}
				?>
                <option <?=$selected_attribute?> value="<?=$key?>">
                <?=$value?>
                </option>
                <?php } ?>
              </select>
            </label>
            <label class="day-unit <?php if(!$isDayActive){echo 'hidden';}?>">
              <select name="<?=($day_name)?>[to_min]" class="form-control drop-select select-menu2 <?=($day_name)?>_tm">
                <?php 
					$seconds = lang('minutes');
					$selected_attribute = ''; 
					$selected = $to_minute;
					foreach($seconds as $key => $value){
					if($selected != ''){
						if($selected == $key){
							$selected_attribute = 'selected="selected"'; 
						}else{
							$selected_attribute = '';
						}
					}
				?>
                <option <?=$selected_attribute?> value="<?=$key?>">
                <?=$value?>
                </option>
                <?php } ?>
              </select>
            </label>
            <label class="day-unit <?php if(!$isDayActive){echo 'hidden';}?>">
              <select name="<?=($day_name)?>[to_ampm]" class="form-control drop-select select-menu3 <?=($day_name)?>_tampm">
                <?php 
					$ampm = lang('ampm');
					$selected_attribute = ''; 
					$selected = $to_ampm;
					foreach($ampm as $key => $value){
					if($selected != ''){
						if($selected == $key){
							$selected_attribute = 'selected="selected"'; 
						}else{
							$selected_attribute = '';
						}
					}
				?>
                <option <?=$selected_attribute?> value="<?=$key?>">
                <?=$value?>
                </option>
                <?php } ?>
              </select>
            </label>
            <label class="day-unit <?php if(!$isDayActive){echo 'hidden';}?>"> <a href="javascript:void(0);" class="add_break_hour"><i class="fa fa-plus"></i> Batch</a> </label>
          </div>
        </div>
        <?php
    	if(isset($day['break'])){
			$break = $day['break'];
			//printr($break['name'][$b]);
			for($b=0; $b<count($day['break']['name']); $b++){
				//printr($break['name'][$b]);
				$break_name 		= $break['name'][$b];
				$course_val 		= $break['course'][$b];
				$break_from_hour 	= date('h',strtotime($break['from_time'][$b]));
				$break_from_minute 	= date('i',strtotime($break['from_time'][$b]));
				$break_from_ampm 	= date('a',strtotime($break['from_time'][$b]));
				$break_to_hour 		= date('h',strtotime($break['to_time'][$b]));
				$break_to_minute 	= date('i',strtotime($break['to_time'][$b]));
				$break_to_ampm 		= date('a',strtotime($break['to_time'][$b]));
			
	?>
        <div class="form-group break_group_<?=($day_name)?>">
          <label class="break_name Xcourse_name">
            <select name="<?=($day_name)?>[break][course][]" class="form-control">
             <?php 
				 if(!empty($data_course)){
					 foreach($data_course as $course){
						 
						 if( $course_val == $course){
							 $course_selected = 'selected="selected"';
						 }else{
							 $course_selected = '';
						 }
						 echo '<option '.$course_selected.' value="'.$course.'">'.$course.'</option>';
					 }
				 }
			 ?>
            </select>
          </label>
          <label class="break_name">
            <input type="text" placeholder="Break Name" value="<?=$break_name?>" name="<?=($day_name)?>[break][name][]" class="form-control">
          </label>
          <div class="btn-group">
            <label class="day-unit ">
              <select name="<?=($day_name)?>[break][from_hour][]" class="form-control drop-select select-menu <?=($day_name)?>_fh">
                <?php 
            $hours = lang('hours');
            $selected_attribute = ''; 
            $selected = $break_from_hour;
            foreach($hours as $key => $value){
                if($selected != ''){
                    if($selected == $key){
                        $selected_attribute = 'selected="selected"'; 
                    }else{
                        $selected_attribute = '';
                    }
                }
            ?>
                <option <?=$selected_attribute?> value="<?=$key?>">
                <?=$value?>
                </option>
                <?php } ?>
              </select>
            </label>
            <label class="day-unit ">
              <select name="<?=($day_name)?>[break][from_min][]" class="form-control drop-select select-menu2 <?=($day_name)?>_fm">
                <?php 
            $seconds = lang('minutes');
            $selected_attribute = ''; 
            $selected = $break_from_minute;
            foreach($seconds as $key => $value){
                if($selected != ''){
                    if($selected == $key){
                        $selected_attribute = 'selected="selected"'; 
                    }else{
                        $selected_attribute = '';
                    }
                }
            ?>
                <option <?=$selected_attribute?> value="<?=$key?>">
                <?=$value?>
                </option>
                <?php } ?>
              </select>
            </label>
            <label class="day-unit ">
              <select name="<?=($day_name)?>[break][from_ampm][]" class="form-control drop-select select-menu3 <?=($day_name)?>_fampm">
                <?php 
            $ampm = lang('ampm');
                $selected_attribute = ''; 
                $selected = $break_from_ampm;
                foreach($ampm as $key => $value){
                    if($selected != ''){
                        if($selected == $key){
                            $selected_attribute = 'selected="selected"'; 
                        }else{
                            $selected_attribute = '';
                    }
                }
            ?>
                <option <?=$selected_attribute?> value="<?=$key?>">
                <?=$value?>
                </option>
                <?php } ?>
              </select>
            </label>
            <span class="day-unit " style="padding: 0px 8px;">to</span>
            <label class="day-unit ">
              <select name="<?=($day_name)?>[break][to_hour][]" class="form-control drop-select select-menu <?=($day_name)?>_th">
                <?php 
            $hours = lang('hours');
            $selected_attribute = ''; 
            $selected = $break_to_hour;
            foreach($hours as $key => $value){
                if($selected != ''){
                    if($selected == $key){
                        $selected_attribute = 'selected="selected"'; 
                    }else{
                        $selected_attribute = '';
                    }
                }
            ?>
                <option <?=$selected_attribute?> value="<?=$key?>">
                <?=$value?>
                </option>
                <?php } ?>
              </select>
            </label>
            <label class="day-unit ">
              <select name="<?=($day_name)?>[break][to_min][]" class="form-control drop-select select-menu2 <?=($day_name)?>_tm">
                <?php 
            $seconds = lang('minutes');
            $selected_attribute = ''; 
            $selected = $break_to_minute;
            foreach($seconds as $key => $value){
                if($selected != ''){
                    if($selected == $key){
                        $selected_attribute = 'selected="selected"'; 
                    }else{
                        $selected_attribute = '';
                    }
                }
            ?>
                <option <?=$selected_attribute?> value="<?=$key?>">
                <?=$value?>
                </option>
                <?php } ?>
              </select>
            </label>
            <label class="day-unit ">
              <select name="<?=($day_name)?>[break][to_ampm][]" class="form-control drop-select select-menu3 <?=($day_name)?>_tampm">
                <?php 
            $ampm = lang('ampm');
            $selected_attribute = ''; 
            $selected = $break_to_ampm;
            foreach($ampm as $key => $value){
                if($selected != ''){
                    if($selected == $key){
                        $selected_attribute = 'selected="selected"'; 
                    }else{
                        $selected_attribute = '';
                    }
                }
            ?>
                <option <?=$selected_attribute?> value="<?=$key?>">
                <?=$value?>
                </option>
                <?php } ?>
              </select>
            </label>
            <label class="day-unit "><a id="" href="javascript:void(0);" class="text-danger remove_batch_edit"><i class="fa fa-trash"></i> Remove</a></label>
          </div>
        </div>
        <?php } /* Break Loop End */	
		} /* Break isset End */
	   } /* Weekdays isset End */
	}/* locationData isset End */else{
	?>
        <div class="alert alert-danger">Please delete this record and insert again.</div>
        <?php } ?>
      </div>
    </div>
  </div>
</div>
<div class="text-right"> <a href="<?=site_url('settings/business/locations')?>" class="btn btn-default padding-dr"><?=lang('cancel_button')?></a>
  <button type="submit" class="btn btn-bg-dr padding-dr"><?=lang('save_button')?></button>
</div>
<?=form_close()?>
<script src="http://localhost/coaching/assets/plugins/jquery/dist/jquery.min.js"></script> 
<script>
var json_course = '<?=$json_course?>';
</script>
<?php } ?>
