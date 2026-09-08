<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="row">
	<div class="col-sm-4"></div>
	<div class="col-sm-8">
		<?php echo validation_errors(); ?>
    </div>
</div>
<?php echo form_open('settings/business/online_booking', 'class="form-vertical CUSTOM-tab-style common-css" role="form" id="booking_settings"')?>
<div class="row">
  <div class="col-sm-4">
    <h4>Booking settings</h4>
  </div>
  <div class="col-sm-8 switch-toggle">
    <div class="onoffswitch">
    	<?php		
        	$online_booking = ($onlineSettingData['online_booking'] == "Yes")?'checked="checked"':'';
			$onlineSettingContainer = ($onlineSettingData['online_booking'] == "Yes")?'':'hidden';
		?>
      <input type="checkbox" name="online_booking" class="onoffswitch-checkbox" id="myonoffswitch" <?=$online_booking?> value="1">
      <label class="onoffswitch-label" for="myonoffswitch"> 
      	<span class="onoffswitch-inner"></span>
        <span class="onoffswitch-switch"></span>
      </label>
    </div>
  </div>
</div>

<div class="onlineSettingContainer <?=$onlineSettingContainer?>">
    <div role="presentation" class="divider"></div>
    <div class="row">
      <div class="col-md-4">
        <h4>Settings</h4>
        <p>Choose how online bookings are confirmed and what options customers have when booking.</p>
      </div>
      <div class="col-sm-8">
      	<?php
			$clientes = '';
			$servicios = '';
			$active = '';
			$initial_status = 1;
			if(!empty($onlineSettingData['initial_status'])){
				$initial_status = ($onlineSettingData['initial_status'] == "Confirm")?'1':'2';
				if($initial_status == 1){
					$clientes = 'active';
				}else{
					$servicios = 'active';
				}
			}else{
				$active = 'active';
				$initial_status = 1;
			}
			
		
		?>
        <input type="hidden" value="<?=$initial_status?>" name="initial_status" id="initial_status" />
        <div id="tab" class="btn-group" data-toggle="buttons-radio"> 
            <a href="#clientes" data-initial_status="1" class="btn default btn-design open-dr <?=$clientes.' '.$active?>" data-toggle="tab">Confirmed<br>(automatically accepted)</a>
            <a href="#servicios" data-initial_status="2" class="btn default btn-design open-dr <?=$servicios?>" data-toggle="tab">Pencilled-in<br>(these can be accepted or declined)</a> 
        </div>
        <div class="tab-content" style="border: 0px none;">
          <div class="tab-pane active" id="clientes">
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <p> Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent congue, lorem vel pulvinar pharetra, lorem velit aliquet turpis, id sollicitudin tellus elit finibus nisl. Vivamus mattis metus sit amet enim cursus, nec sagittis mauris blandit. Morbi tincidunt semper placerat. Vestibulum volutpat risus at vehicula finibus. In volutpat lorem et mauris sagittis sagittis at ac enim. </p>
                </div>
              </div>
            </div>
          </div>
          <div class="tab-pane" id="servicios">
            <div class="row">
              <div class="col-md-12">
                <p>lorem vel pulvinar pharetra, lorem velit aliquet turpis, id sollicitudin tellus elit finibus nisl. Vivamus mattis metus sit amet enim cursus, nec sagittis mauris blandit. Morbi tincidunt semper placerat. Vestibulum volutpat risus at vehicula finibus. </p>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12">
            <div class="checkbox">
            	<?php
					if(!empty($onlineSettingData['email_phone'])){
						$email_phone = ($onlineSettingData['email_phone'] == "Yes")?'checked="checked"':'';
					}else{
						$email_phone = 'checked="checked"';
					}
				?>
              <label>
                <input <?=$email_phone?> id="email_phone" name="email_phone" value="1" type="checkbox">
                The customer must enter an email address and telephone number </label>
            </div>
            <div class="checkbox">
            	<?php
					if(!empty($onlineSettingData['book_more'])){
						$book_more = ($onlineSettingData['book_more'] == "Yes")?'checked="checked"':'';
					}else{
						$book_more = 'checked="checked"';
					}
				?>
              <label>
                <input type="checkbox" <?=$book_more?> id="book_more" name="book_more" value="1">
                Allow customers to book appointments with more than one service </label>
            </div>
            <div class="checkbox">
            	<?php
					if(!empty($onlineSettingData['display_poweredby'])){
						$display_poweredby = ($onlineSettingData['display_poweredby'] == "Yes")?'checked="checked"':'';
					}else{
						$display_poweredby = 'checked="checked"';
					}
					
					if(!empty($onlineSettingData['local_timezone_booking'])){
						$local_timezone_booking = ($onlineSettingData['local_timezone_booking'] == "Yes")?'checked="checked"':'';
						$enable_booking_across_tz = ($onlineSettingData['local_timezone_booking'] == "Yes")?'':'hidden';
						$tz = ($onlineSettingData['local_timezone_booking'] == "Yes")?'hidden':'';
					}else{
						$local_timezone_booking = '';
						$enable_booking_across_tz = 'hidden';
						$tz = '';
					}
				?>
              <label>
                <input type="checkbox" <?=$display_poweredby?> id="display_poweredby" name="display_poweredby" value="1">
                Display the "Powered by Practice" logo and tagline <a href="javascript:void(0);"><i class="fa fa-question-circle"></i></a> </label>
            </div>
            <div> <a href="javascript:void(0);" id="enable_booking_across_tz" class="<?=$tz?>">Enable bookings across time zones...</a> </div>
          </div>
        </div>
      </div>
    </div>
    
    <div class="enable_booking_across_tz <?=$enable_booking_across_tz?>">
        <div role="presentation" class="divider"></div>
        <div class="row">
          <div class="col-md-4">
            <h4>Bookings across time zones</h4>
            <p> Use this setting if your customers are in a different time zone to you.</p>
            <p> One example of this would be a business that operates Skype calls with their clients rather than in person appointments </p>
          </div>
          <div class="col-md-8">
            <div class="checkbox">
              <label>
                <input type="checkbox" <?=$local_timezone_booking?> id="local_timezone_booking" name="local_timezone_booking" value="1">
                Allow customers to book appointments in their local time zone <a href="javascript:void(0);"><i class="fa fa-question-circle"></i></a></label>
            </div>
            <div class="alert alert-warning">
                <strong>Note:</strong> If your business offers in-person services, and/or has locations in more than one time zone, <strong>this setting is not for you</strong>. </div>
          </div>
        </div>
    </div>
    
    <div role="presentation" class="divider"></div>
    <div class="row">
      <div class="col-md-4">
        <h4>Booking policy</h4>
        <p>Choose when online bookings can be made.</p>
        <?php
			$booking_upto_before_starttime_value = '';
			if(!empty($onlineSettingData['booking_upto_before_starttime'])){
				$booking_upto_before_starttime_value = $onlineSettingData['booking_upto_before_starttime'];
			}
		?>
      </div>
      <div class="col-sm-8">
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="booking_before">Customers can book appointments up to:</label>
              <div class="inline-group">
                <select class="form-control" id="booking_upto_before_starttime" name="booking_upto_before_starttime" style="width: 106px; float: left; margin-right: 10px;">
                  <?php 
                    $booking_upto_before_starttime = lang('booking_upto_before_starttime');
                    $selecte_attribute = ''; 
                    $selected = $booking_upto_before_starttime_value;
                    foreach($booking_upto_before_starttime as $key => $value){
                        if($selected != ''){
                            if($selected == $key){
                                $selecte_attribute = 'selected="selected"'; 
                            }else{
                                $selecte_attribute = '';
                            }
                        }
                    ?>
                  <option <?=$selecte_attribute?> value="<?=$key?>"><?=$value?></option>
                  <?php } ?>
                </select>
                <span style="line-height: 33px;"> before start time. </span> </div>
            </div>
          </div>
          <div class="col-md-12">
            <div class="form-group">
            	<?php
					$booking_uifv = '';
					$booking_ufu = '';
					if(!empty($onlineSettingData['booking_upto_infuture_value'])){
						$booking_uifv = $onlineSettingData['booking_upto_infuture_value'];
					}
					if(!empty($onlineSettingData['booking_upto_infuture_unit'])){
						$booking_ufu = $onlineSettingData['booking_upto_infuture_unit'];
					}
				?>
              <label for="booking_upto_infuture_value">Customers can book appointments up to:</label>
              <div class="inline-group ">
                <select class="form-control" id="booking_upto_infuture_value" name="booking_upto_infuture_value" style="width:65px; float: left; margin-right: 10px;">
                  <?php 
                        $booking_upto_infuture_value = lang('booking_upto_infuture_value');
                        $selecte_attribute = ''; 
                        $selected = $booking_uifv;
                        foreach($booking_upto_infuture_value as $key => $value){
                            if($selected != ''){
                                if($selected == $key){
                                    $selecte_attribute = 'selected="selected"'; 
                                }else{
                                    $selecte_attribute = '';
                                }
                            }
                    ?>
                  <option <?=$selecte_attribute?> value="<?=$key?>"><?=$value?></option>
                  <?php } ?>
                </select>
                <select class="form-control" name="booking_upto_infuture_unit" style="width: 106px; float: left; margin-right: 10px;">
                  <?php 
                        $booking_upto_infuture_unit = lang('booking_upto_infuture_unit');
                        $selecte_attribute = ''; 
                        $selected = $booking_ufu;
                        foreach($booking_upto_infuture_unit as $key => $value){
                        if($selected != ''){
                            if($selected == $key){
                                $selecte_attribute = 'selected="selected"'; 
                            }else{
                                $selecte_attribute = '';
                            }
                        }
                    ?>
                  <option <?=$selecte_attribute?> value="<?=$key?>"><?=$value?></option>
                  <?php } ?>
                </select>
                <span style="line-height: 33px;"> in the future. </span> </div>
            </div>
            <br/>
          </div>
        </div>
      </div>
    </div>
    
    <div role="presentation" class="divider"></div>
    <div class="row">
      <div class="col-md-4">
        <h4>Cancellations and changes</h4>
        <p>Choose when online bookings can be cancelled or changed.</p>
      </div>
      <div class="col-sm-8">
        <div class="row">
          <div class="col-md-12">
            <div class="checkbox">
            	<?php
					if(!empty($onlineSettingData['include_links_in_email'])){
						$include_links_in_email = ($onlineSettingData['include_links_in_email'] == "Yes")?'checked="checked"':'';
					}else{
						$include_links_in_email = 'checked="checked"';
					}
				?>
              <label>
                <input type="checkbox" <?=$include_links_in_email?> id="include_links_in_email" name="include_links_in_email" value="1">
                Include a link in emails for customers to change their booking 
              </label>
            </div>
            <?php
				$manual = '';
				$anytime = '';
				$never = '';
				$cancellation_upto_value = '';
				$cancellation_upto_text = '3 hours';
				$cancellation_policy_class = '';
				$disabled = '';
				if(!empty($onlineSettingData['cancellation_policy'])){
					$cancellation_policy = $onlineSettingData['cancellation_policy'];
					if($cancellation_policy == 'manual'){
						$manual = 'checked="checked"';
						$disabled = '';
						$cancellation_policy_class = '';
						if(!empty($onlineSettingData['cancellation_upto'])){
							$cancellation_upto_value = $onlineSettingData['cancellation_upto'];
							
							if($cancellation_upto_value <= 48){
								$cancellation_upto_text = $cancellation_upto_value.' hours';
							}else{
								$cancellation_upto_text = ($cancellation_upto_value/24).' days';
							}
						}
					}elseif($cancellation_policy == 'anytime'){
						$anytime = 'checked="checked"';
						$disabled = 'disabled="disabled"';
						$cancellation_policy_class = 'hidden';
					}elseif($cancellation_policy == 'never'){
						$never = 'checked="checked"';
						$disabled = 'disabled="disabled"';
						$cancellation_policy_class = '';
					}
				}else{
					$manual = 'checked="checked"';
				}
			?>
            
            <div class="form-group">
              <label>Set when appointments can be changed or cancelled:</label>
              <div class="inline-group ">
                <div class="radio" style="width:65px; float: left; margin-right: 2px;">
                  <label><input type="radio" name="cancellation_policy" id="manual" class="cancellation_policy" value="manual" <?=$manual?> >Up to &nbsp; &nbsp; </label>
                </div>
                <select <?=$disabled?> class="form-control" id="cancellation_upto" name="cancellation_upto" style="width: 106px; float: left; margin-right: 10px;">
                  <?php 
                    $cancellation_upto = lang('cancellation_upto');
                    $selected_attribute = ''; 
                    $selected = $cancellation_upto_value;
                    foreach($cancellation_upto as $key => $value){
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
                <span style="line-height: 33px;"> before their appointment. </span> </div>
            </div>
            <div class="form-group clear">
              <div class="radio">
                <label>
                    <input type="radio" name="cancellation_policy" id="anytime" class="cancellation_policy" value="anytime" <?=$anytime?>> Anytime 
                </label>
              </div>
            </div>
            <div class="form-group">
              <div class="radio">
                <label for="never">
                    <input type="radio" name="cancellation_policy" id="never" class="cancellation_policy" value="never" <?=$never?>> Never
                </label>
              </div>
            </div>
            <div class="form-group cancellation_policy_message_body <?=$cancellation_policy_class?>">
              <label>This is what your customers will see:</label>
              <div class="alert alert-info cancellation_policy_message">
                No cancellations or changes allowed within <span id="hours"><?=$cancellation_upto_text?></span> of the appointment
              </div>
            </div>
            <div class="form-group">
                <label for="cancellation_terms">Additional cancellation terms:</label>
                <textarea maxlength="1000" name="cancellation_terms" id="cancellation_terms" class="char-count-1000 content-textarea form-control limited" cols="100" rows="5"><?=set_value('cancellation_terms',$onlineSettingData['cancellation_terms'])?></textarea>
                <small class="counter">Max Characters: 1000</small>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <div role="presentation" class="divider"></div>
    <div class="row">
      <div class="col-md-4">
        <h4 id="payments">Online payment terms</h4>
        <p> You can charge the full amount or a deposit for online bookings.<br>
          You can also change these settings <a href="#">per service</a>. </p>
      </div>
      <div class="col-md-8">
        <div class="alert alert-info">
          <h4>No payment gateways configured</h4>
          You must configure at least one <a href="#">payment gateway</a> before you can accept online payments </div>
      </div>
    </div>
    
    <div role="presentation" class="divider"></div>
    <div class="row">
    <?php
		$default = "";
		$neutral = "";
		$custom = "";
		$choose_colors = "hidden";
		$custom_header_bg_color = "#39464e";
		$custom_header_text_color = "#ffffff";
		$custom_link_color = "#337ab7";
		$custom_button_bg_color = "#61c561";
		$custom_button_text_color = "#ffffff";
		if(!empty($onlineSettingData['appearance'])){
			$appearance = $onlineSettingData['appearance'];
			if($appearance == 'default'){
				$default = 'checked="checked"';
				$choose_colors = 'hidden';
			}elseif($appearance == 'neutral'){
				$neutral = 'checked="checked"';
				$choose_colors = 'hidden';
				$custom_header_bg_color = "rgb(85, 85, 85)";
				$custom_header_text_color = "rgb(255, 255, 255)";
				$custom_link_color = "rgb(51, 122, 183)";
				$custom_button_bg_color = "rgb(92, 184, 92)";
				$custom_button_text_color = "rgb(255, 255, 255)";
			}elseif($appearance == 'custom'){
				$custom = 'checked="checked"';
				$choose_colors = '';
				$custom_header_bg_color = $onlineSettingData['custom_header_bg_color'];
				$custom_header_text_color = $onlineSettingData['custom_header_text_color'];
				$custom_link_color = $onlineSettingData['custom_link_color'];
				$custom_button_bg_color = $onlineSettingData['custom_button_bg_color'];
				$custom_button_text_color = $onlineSettingData['custom_button_text_color'];
			}
		}else{
			$default = 'checked="checked"';
			$choose_colors = 'hidden';
		}
	?>
      <div class="col-sm-4">
        <h4 id="appearance">Appearance</h4>
        <p>Custom colors to match your brand</p>
        <h4>Preview:</h4>
        <div class="model-preview">
          <div class="modal-header" id="previewModelHeader" style="background-color:<?=$custom_header_bg_color?>; color:<?=$custom_header_text_color?>;"> Header </div>
          <div class="modal-body">
            <a id="previewLink" style="color:<?=$custom_link_color?>;">Link</a>
          </div>
          <div class="modal-footer">
            <span class="btn btn-large btn-bg-dr forward online-booking-preview-btn" id="previewButton" style="border-color:<?=$custom_button_bg_color?>; background-color:<?=$custom_button_bg_color?>; color: <?=$custom_button_text_color?>;">
            Button&nbsp;<i class="fa fa-chevron-right"></i>
            </span> 
         </div>
        </div>
      </div>
       
      <div class="col-sm-8">
        <div class="form-group">
          <div class="inline-group">
            <div class="radio">
              <label>
                <input type="radio" name="appearance" class="appearance" <?=$default?> value="default">
                Use the default "Practice" theme </label>
            </div>
          </div>
        </div>
        <div class="form-group">
          <div class="inline-group">
            <div class="radio">
              <label>
                <input type="radio" name="appearance" class="appearance" <?=$neutral?> value="neutral">
                Use the "Neutral" theme </label>
            </div>
          </div>
        </div>
        <div class="form-group">
          <div class="inline-group">
            <div class="radio">
              <label>
                <input type="radio" name="appearance" class="appearance" <?=$custom?> value="custom">
                Add custom colors </label>
            </div>
          </div>
        </div>
        
        <div class="choose-colors <?=$choose_colors?>">
            <div class="form-group">
                <label for="headerBackgroundColor">Header background color</label>
                <div class="input-group headerBackgroundColor">
                    <div id="cp1" class="input-group">
                        <span class="input-group-addon"><i></i></span>
                      <input type="text" name="custom_header_bg_color" value="<?=$custom_header_bg_color?>" class="form-control cp1"/>
                       </div>
                </div>
            </div>
            
            <div class="form-group">
                <label for="headerTextColor">Header text color</label>
                <div class="input-group headerTextColor">
                    <div id="cp2" class="input-group">
                        <span class="input-group-addon"><i></i></span>
                      <input type="text" name="custom_header_text_color" value="<?=$custom_header_text_color?>" class="form-control cp2"/>
                       </div>
                </div>
            </div>
            
            <div class="form-group">
                <label for="linkColor">Link color</label>
                <div class="input-group linkColor">
                    <div id="cp3" class="input-group">
                        <span class="input-group-addon"><i></i></span>
                      <input type="text" name="custom_link_color" value="<?=$custom_link_color?>" class="form-control cp3"/>
                       </div>
                </div>
            </div>
            
            <div class="form-group">
                <label for="buttonBackgroundColor">Button background color</label>
                <div class="input-group buttonBackgroundColor">
                    <div id="cp4" class="input-group">
                        <span class="input-group-addon"><i></i></span>
                      <input type="text" name="custom_button_bg_color" value="<?=$custom_button_bg_color?>" class="form-control cp4"/>
                       </div>
                </div>
            </div>
            
            <div class="form-group">
                <label for="buttonTextColor">Button text color</label>
                <div class="input-group buttonTextColor">
                    <div id="cp5" class="input-group">
                        <span class="input-group-addon"><i></i></span>
                      <input type="text" name="custom_button_text_color" value="<?=$custom_button_text_color?>" class="form-control cp5"/>
                       </div>
                </div>
            </div>
        </div>
      </div>
    </div>
    
    <div role="presentation" class="divider"></div>
    <div class="row">
      <div class="col-md-4">
        <h4>Add your own text</h4>
        <p>Add instructions, extra information or customize text to each step or section of the online booking process.</p>
      </div>
      <div class="col-sm-8">
        <div class="form-group ">
          <label for="select_service">"Select service" step</label>
          <textarea name="select_service" id="select_service" class="form-control limited" maxlength="250" cols="100" rows="2"><?=set_value('select_service',$onlineSettingData['select_service'])?></textarea>
          <small class="counter">Max Characters: 250</small>
        </div>
        <div class="form-group ">
          <label for="select_staff">"Select staff" step</label>
          <textarea name="select_staff" id="select_staff" class="form-control limited" cols="100" maxlength="250" rows="2"><?=set_value('select_service',$onlineSettingData['select_staff'])?></textarea>
          <small class="counter">Max Characters: 250</small>
        </div>
        <div class="form-group ">
          <label for="select_date_time">"Select date/time" step</label>
          <textarea name="select_date_time" id="select_date_time" class="form-control limited" cols="100" maxlength="250" rows="2"><?=set_value('select_service',$onlineSettingData['select_date_time'])?></textarea>
          <small class="counter">Max Characters: 100</small>
        </div>
        <div class="form-group ">
          <label for="customer_details">"Enter customer details" step</label>
          <textarea name="customer_details" id="customer_details" class="form-control limited" cols="100" maxlength="250" rows="2"><?=set_value('select_service',$onlineSettingData['customer_details'])?></textarea>
          <small class="counter">Max Characters: 250</small>
        </div>
        <div class="form-group ">
          <label for="appointment_confirmed">"Appointment confirmed" step</label>
          <textarea name="appointment_confirmed" id="appointment_confirmed" class="form-control limited" cols="100" maxlength="250" rows="2"><?=set_value('select_service',$onlineSettingData['appointment_confirmed'])?></textarea>
          <small class="counter">Max Characters: 250</small>
        </div>
        <div class="form-group ">
          <label for="no_service_available">"No service available" text</label>
          <textarea name="no_service_available" id="no_service_available" class="form-control limited" cols="100" maxlength="250" rows="2"><?=set_value('select_service',$onlineSettingData['no_service_available'])?></textarea>
          <small class="counter">Max Characters: 250</small>
        </div>
      </div>
    </div>
</div>   

<div role="presentation" class="divider"></div>

<div class="row">
  <div class="col-md-12">
    <div class="form-actions text-right">
      <button type="submit" class="btn btn-bg-dr btn-padded">Save</button>
    </div>
  </div>
</div>

<?php echo form_close(); ?> 