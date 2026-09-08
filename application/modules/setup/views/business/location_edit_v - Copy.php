<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="row">
	<div class="col-sm-12"><?=validation_errors();?></div>
</div>
<?php
	if(!empty($locationData)){
		$location_name = set_value('location_name', $locationData['location_name']);
		$location_type = set_value('location_type', $locationData['location_type']);
		$address_line1 = set_value('address_line1', $locationData['address_line1']);
		$address_line2 = set_value('address_line2', $locationData['address_line2']);
		$city = set_value('city', $locationData['city']);
		$fixed = '';
		$mobile = '';
		$req_adrs_active = '';
		$add_ofc_address = '';
		$adrs = 'hidden';
		$adrs1_req = 'required';
		$city_req = 'required';
		if($location_type == "Fixed"){
			$fixed = 'active';
			$req_adrs_active = 'hidden';
			$add_ofc_address = 'hidden';
			$adrs = '';
		}else{
			$mobile = 'active';
			$adrs1_req = '';
			$city_req = '';
			if(!empty($address_line1) || !empty($address_line2) || !empty($city)){
				$add_ofc_address = 'hidden';
				$adrs = '';
			}
		}
		$book_location_online = set_value('book_location_online', $locationData['book_location_online']);
		$book_location = '';
		if($book_location_online == 'Yes'){
			$book_location = 'checked="checked"';
		}
		$require_address  = set_value('require_address', $locationData['require_address']);
		$req_address = '';
		if($require_address == 'Yes'){
			$req_address = 'checked="checked"';
		}
		$telephone = set_value('telephone', $locationData['telephone']);
		$location_id = $locationData['location_id'];
?>
<?=form_open('settings/business/edit_location/'.$location_id, 'class="form-vertical hoursStyles common-css" id="Locations_step_first"')?>
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
    <h4>Location details</h4>
    <p> Choose whether your location is fixed or mobile, and enter in the location details. </p>
  </div>
  <div class="col-sm-8">
    <div class="form-group">
      <label for="location_name" class="required"><strong>Location name </strong></label>
      <input class="form-control" id="location_name" name="location_name" type="text" value="<?=$location_name?>">
    </div>
    <div id="tab" class="btn-group" data-toggle="buttons-radio">
    	<input type="hidden" name="location_type" id="location_type" value="1" />
    	<a href="#fixed" data-location_type="1" class="btn default btn-design open-dr <?=$fixed?>" data-toggle="tab">Fixed<br>(customers come to you)</a> 
      	<a href="#mobile" data-location_type="2" class="btn default btn-design open-dr <?=$mobile?>" data-toggle="tab">Mobile<br>(you go to them)</a>
    </div>
    <div class="checkbox">
      <label><input type="checkbox" <?=$book_location?> name="book_location_online" value="1">Customers can book this location online</label>
      <a href="javascript:void(0);"> <i class="fa fa-question-circle fa-fw">&nbsp;</i> </a>
    </div>
    <div class="checkbox require_address <?=$req_adrs_active?>">
      <label>
        <input type="checkbox" <?=$req_address?> name="require_address" value="1" >
        Appointments require an address from the customer 
      </label>
    </div>
    <div class="tab-content">
    	<div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="telephone_when_fixed"><strong>Telephone</strong></label>
              <div class="input-group">
              	<span class="input-group-addon"><i class="glyphicon glyphicon-earphone"></i></span>
                <input type="text" name="telephone" id="telephone" class="form-control" value="<?=$telephone?>">
              </div>
            </div>
          </div>
          <div class="col-md-12">
          	<a href="javascript:void(0);" class="add_ofc_address <?=$add_ofc_address?>">Add an office/billing address</a>
          </div>
          <div class="col-md-12 adrs <?=$adrs?>">
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="label_address_line1 <?=$adrs1_req?>" for="address_line1"><strong>Address</strong></label>
                  <input type="text" name="address_line1" id="address_line1" class="form-control" value="<?=$address_line1?>" >
                </div>
                <div class="form-group">
                  <input type="text" name="address_line2" id="address_line2" class="form-control" value="<?=$address_line2?>">
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="label_city  <?=$city_req?>" for="city"><strong>City</strong></label>
                  <input type="text" name="city" id="city" class="form-control" value="<?=$city?>" >
                </div>
              </div>
          </div>
        </div>
    </div>
  </div>
</div>
<hr>
<div class="row">
  <div class="col-md-4">
    <h4>Hours</h4>
    <p>Choose the standard opening times for this location. </p>
  </div>
  <div class="col-sm-8">
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
					$weekdays[$loc_data['day_name']]['break']['from_time'][] = $loc_data['from_time'];
					$weekdays[$loc_data['day_name']]['break']['to_time'][] = $loc_data['to_time'];
				}
			}
		}else{
			$week = weekdays();
			for($w=0; $w<count($week); $w++){
				$week_day_name = $week[$w];
				
				$week_from_time = set_value($week_day_name.'[from_hour]').':'.set_value($week_day_name.'[from_minute]').' '.set_value($week_day_name.'[from_ampm]');
				$week_to_time 	= set_value($week_day_name.'[to_hour]').':'.set_value($week_day_name.'[to_minute]').' '.set_value($week_day_name.'[to_ampm]');


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
          <label for="<?=$day_name?>" class="btn circle-dr day-circle <?php if($isDayActive){echo 'btn-info';}else{echo 'circle-inactive';}?>">
          	<span class="glyphicon <?php if($isDayActive){echo 'glyphicon-ok';}?>"></span> <span></span>
          </label>
          <label for="fancy-checkbox-info" class="day-dr"> <strong><?=lang($day_name)?></strong> </label>
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
              <option <?=$selected_attribute?> value="<?=$key?>"> <?=$value?></option>
              <?php } ?>
            </select>
          </label>
          <label class="day-unit <?php if(!$isDayActive){echo 'hidden';}?>">
            <select name="<?=($day_name)?>[from_minute]" class="form-control drop-select select-menu2 <?=($day_name)?>_fm">
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
              <option <?=$selected_attribute?> value="<?=$key?>"> <?=$value?> </option>
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
              <option <?=$selected_attribute?> value="<?=$key?>"> <?=$value?> </option>
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
              <option <?=$selected_attribute?> value="<?=$key?>"> <?=$value?> </option>
              <?php } ?>
            </select>
          </label>
          <label class="day-unit <?php if(!$isDayActive){echo 'hidden';}?>">
            <select name="<?=($day_name)?>[to_minute]" class="form-control drop-select select-menu2 <?=($day_name)?>_tm">
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
              <option <?=$selected_attribute?> value="<?=$key?>"><?=$value?></option>
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
              <option <?=$selected_attribute?> value="<?=$key?>"> <?=$value?> </option>
              <?php } ?>
            </select>
          </label>
          <label class="day-unit <?php if(!$isDayActive){echo 'hidden';}?>">
           <a href="javascript:void(0);" class="add_break_hour">Add Break</a>
          </label>
        </div>
      </div>
    <?php
    	if(isset($day['break'])){
			$break = $day['break'];
			//echo '<pre>'; print_r($days['break']); die;
			for($b=0; $b<count($day['break']['name']); $b++){
				$break_name 		= $break['name'][$b];
				$break_from_hour 	= date('h',strtotime($break['from_time'][$b]));
				$break_from_minute 	= date('i',strtotime($break['from_time'][$b]));
				$break_from_ampm 	= date('a',strtotime($break['from_time'][$b]));
				$break_to_hour 		= date('h',strtotime($break['to_time'][$b]));
				$break_to_minute 	= date('i',strtotime($break['to_time'][$b]));
				$break_to_ampm 		= date('a',strtotime($break['to_time'][$b]));
			
	?>
    
    <div class="form-group break_group_<?=($day_name)?>">
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
            <option <?=$selected_attribute?> value="<?=$key?>"><?=$value?></option>
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
            <option <?=$selected_attribute?> value="<?=$key?>"><?=$value?></option><?php } ?></select>
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
            <option <?=$selected_attribute?> value="<?=$key?>"> <?=$value?> </option>
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
            <option <?=$selected_attribute?> value="<?=$key?>"> <?=$value?></option>
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
            <option <?=$selected_attribute?> value="<?=$key?>"><?=$value?></option>
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
            <option <?=$selected_attribute?> value="<?=$key?>"> <?=$value?> </option>
            <?php } ?>
            </select>
        </label>
        <label class="day-unit "><a href="javascript:void(0);" class="text-danger"><i class="fa fa-trash"></i> Remove</a></label>
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

<div class="text-right">
  <a href="<?=site_url('settings/business/locations')?>" class="btn btn-default padding-dr">Cancel</a>
  <button type="submit" class="btn btn-bg-dr padding-dr">Update</button>
</div>
<?=form_close()?>

<?php }else{ ?>

<div class="row">
	<div class="col-sm-12">
    	<div class="alert alert-waring">
        	Location data not found 
            <a href="<?=site_url('settings/business/locations');?>" class="btn btn-primary">Back</a>
        </div>
    </div>
</div>

<?php } ?>