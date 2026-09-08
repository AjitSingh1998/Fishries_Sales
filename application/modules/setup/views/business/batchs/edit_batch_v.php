<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style>
	.circle-inactive{ background-color: #ddd; border-color: #ddd;color: #ffffff;}
	.required:after{content: " *"; color: #00b3f0;}	
	.break_name{margin: 0px 10px;}
	.break_name input{	width: 131px;}
	.hidden_field{ display:none;}
</style>
<div class="row">
  <div class="col-sm-12">
    <?=validation_errors();?>
  </div>
</div>

<style>
.circle-inactive{background-color: #ddd;border-color: #ddd;	color: #ffffff;	}
.required:after{content: " *";color: #00b3f0;}
.break_name{margin: 0px 10px;}
.break_name input{	width: 131px;}
.padding4{ padding-left:4px; padding-right:4px;}
</style>
<?=form_open('settings/business/edit_batch/'.$batch_id, 'class="form-vertical hoursStyles common-css" id="editBatch" ')?>
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
    <h3>Location details</h3>
    <p> Choose whether your location is fixed or mobile, and enter in the location details. </p>
  </div>
    <div class="col-sm-8"> </div>
</div>
<hr>
<div class="row">
  <div class="col-md-4">
    <h3>Hours</h3>
    <p>Choose the standard opening times for this location. </p>
  </div>
  <div class="col-sm-8">
    <div class="row">
   		<div class="col-md-6">
            <div class="form-group">
            	<label class="control-label"> Batch Name </label>
                <input type="text" name="batch_name" value="<?=set_value('batch_name',$batch_data['break_name'])?>" class="form-control" />
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="form-group">
            	<label class="control-label"> Day </label>
                <select name="day_name" class="form-control">
                	<option value=""> Select Day  </option>
                    <?php
						$weekdays = array('monday','tuesday','wednesday','thursday','friday','saturday','sunday');
						if(!empty($weekdays)){
						foreach($weekdays as $dname){                    	
							$selected = ($batch_data['day_name'] == $dname)? 'selected="selected"':'';	
								echo '<option value="'.$dname.'" '.$selected.'> '.$dname.' </option>';
							}
						}
					?>
                </select>
            </div>
        </div>

    </div>
    <div class="row">
   		<div class="col-md-6">
            <div class="form-group">
            	<label class="control-label"> Branch </label>
                <select name="branch_name" class="form-control">
                	<option value=""> Select Branch </option>
                    <?php
                    	if(!empty($locations)){
							foreach($locations as $location){
							$selected = ($batch_data['branch_id'] == $location['branch_id'])? 'selected="selected"':'';	
								echo '<option value="'.$location['branch_id'].'" '.$selected.'> '.$location['branch_name'].' </option>';
							}
						}
					?>
                </select>
            </div>
        </div>
   		<div class="col-md-6">
            <div class="form-group">
            	<label class="control-label"> Course </label>
                <select name="course_name" class="form-control">
                	<option value=""> Select Course </option>
                    <?php
                    	if(!empty($courses)){
							foreach($courses as $course){
							$selected = ($batch_data['course'] == $course )? 'selected="selected"':'';	
							echo '<option value="'.$course.'" '.$selected.'> '.ucfirst($course).' </option>';
							}
						}
					?>
                </select>
            </div>
        </div>
    </div>
    <div class="row">
            <div class="col-md-2 padding4" >
                <div class="form-group">
                    <label class="control-label"> From hour </label>
                    <select name="from_hour" class="form-control" >
                      <?php 
                            $from_hour = date('h',strtotime($batch_data['from_time']));					
                            $hours = lang('hours');
                            $selected_attribute = ''; 
                            $selected = $from_hour;	
                            foreach($hours as $key => $value){	
                                if($selected != ''){
                                    if($selected == $key){ $selected_attribute = 'selected="selected"'; }
                                    else{ $selected_attribute = ''; }
                                }
                                echo '<option '.$selected_attribute.' value="'.$key.'"> '.$value. '</option>';
                            } 
                      ?>
                    </select>
       			</div>
            </div>
            <div class="col-md-2 padding4">
                <div class="form-group">
                   <label class="control-label"> From Minute </label>
                    <select name="from_min" class="form-control" >
						<?php 
                        $from_minute = date('i',strtotime($batch_data['from_time']));
                        $seconds = lang('minutes');
                        $selected_attribute = ''; 
                        $selected = $from_minute;
                        foreach($seconds as $key => $value){
							if($selected != ''){
								if($selected == $key){ $selected_attribute = 'selected="selected"'; }
								else{$selected_attribute = '';}
							}
                       	echo '<option '.$selected_attribute.' value="'.$key.'"> '.$value. '</option>';
					   } 
					   ?>
                    </select>
                </div>
            </div>
            <div class="col-md-1 padding4">
                <div class="form-group">
                   <label class="control-label">  </label>
                    <select name="from_ampm" class="form-control">
                    <?php 
						$from_ampm = date('a',strtotime($batch_data['from_time']));
						$ampm = lang('ampm');
						$selected_attribute = ''; 
						$selected = $from_ampm;
						foreach($ampm as $key => $value){
						if($selected != ''){
							if($selected == $key){ $selected_attribute = 'selected="selected"';}
							else{ $selected_attribute = ''; }
						}
						echo '<option '.$selected_attribute.' value="'.$key.'"> '.$value. '</option>';
						}
					?>
                    </select>
                </div>
            </div>
             <div class="col-md-1 padding4"> <span > To </span> </div>
            <div class="col-md-2 padding4" >
                <div class="form-group">
                    <label class="control-label"> To hour </label>
                    <select name="to_hour" class="form-control" >
                      <?php 
							$to_hour = date('h',strtotime($batch_data['to_time']));
                            $hours = lang('hours');
                            $selected_attribute = ''; 
                            $selected = $to_hour;	
                            foreach($hours as $key => $value){	
                                if($selected != ''){
                                    if($selected == $key){ $selected_attribute = 'selected="selected"'; }
                                    else{ $selected_attribute = ''; }
                                }
                                echo '<option '.$selected_attribute.' value="'.$key.'"> '.$value. '</option>';
                            } 
                      ?>
                    </select>
       			</div>
            </div>
            <div class="col-md-2 padding4">
                <div class="form-group">
                   <label class="control-label"> To Minute </label>
                    <select name="to_min" class="form-control" >
						<?php 
						$to_minute = date('i',strtotime($batch_data['to_time']));
                        $seconds = lang('minutes');
                        $selected_attribute = ''; 
                        $selected = $to_minute;
                        foreach($seconds as $key => $value){
							if($selected != ''){
								if($selected == $key){ $selected_attribute = 'selected="selected"'; }
								else{$selected_attribute = '';}
							}
                       	echo '<option '.$selected_attribute.' value="'.$key.'"> '.$value. '</option>';
					   } 
					   ?>
                    </select>
                </div>
            </div>
            <div class="col-md-1 padding4">
                <div class="form-group">
                 	<label class="control-label">  </label>
                    <select name="to_ampm" class="form-control">
                    <?php 
						$to_ampm = date('a',strtotime($batch_data['to_time']));
						$ampm = lang('ampm');
						$selected_attribute = ''; 
						$selected = $to_ampm;
						foreach($ampm as $key => $value){
						if($selected != ''){
							if($selected == $key){ $selected_attribute = 'selected="selected"';}
							else{ $selected_attribute = ''; }
						}
						echo '<option '.$selected_attribute.' value="'.$key.'"> '.$value. '</option>';
						}
					?>
                    </select>
                </div>
            </div>
    </div>      
  </div>
</div>
<div class="text-right"> <a href="<?=site_url('settings/business/batchs')?>" class="btn btn-default padding-dr">Cancel</a>
	<input type="hidden" name="batch_id" value="<?=$batch_id?>" />
  <button type="submit" name="editBatch" class="btn btn-bg-dr padding-dr">Save</button>
</div>
<?=form_close()?>
<script>
var json_course = '<?=$json_course?>';
var json_location = '<?=$json_location?>';
</script>