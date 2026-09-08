<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php

/*echo '<pre>';
print_r(loginUserInfo());
print_r(loginCompanyInfo());
print_r( $this->session);
echo '</pre>';*/
?>
<div class="panel panel-white">
  <div class="panel-body">
   <?php 
   		echo validation_errors();
  		$attributes = array( 'class'=>'bussiness_details','id'=>'bussiness_details','role'=>'form' , 'enctype'=>'multipart/form-data' );
		echo form_open('settings/business/details', $attributes );
   		$businessID = loginCompanyInfo('business_id');
  ?>

    <div class="row">
        <div class="col-sm-4">
              <h4> <?=lang('business_details_label')?> </h4>
              <p>  <?=lang('business_details_label_desc')?> </p>
        </div>
        <div class="col-sm-8">
            <!-- -->
             <div class="form-group">
                <label class="control-label"> <?=lang('business_name_label')?> </label>
                <input class="form-control address" name="business_name" value="<?=($details['business_name'])?$details['business_name']:''?>" type="text">
             </div>
             <div class="row">
             	<div class="col-sm-6">
                	<div class="form-group">
                        <label class="control-label"> <?=lang('business_website_label')?> 
                            <a 
                                href="javascript:void(0);" 
                                data-toggle="popover" 
                                data-placement="top" 
                                data-trigger="hover"
                                title="<strong>Your own website</strong>"  
                                data-content="<?=lang('business_website_label_desc')?>">
                                &nbsp;<i class="fa fa-question-circle" aria-hidden="true">&nbsp;</i>
                           </a>
                        </label>
                        <input class="form-control" name="website" value="<?=($details['website'])?$details['website']:''?>" type="text">
                    </div>
                </div>
                <div class="col-sm-6">
                	<div class="form-group">
                        <label class="control-label"> <?=lang('business_email_label')?> </label>
                        <input class="form-control email" name="email" value="<?=($details['email'])?$details['email']:''?>" type="text">
                    </div>
                </div>
            </div>
            <div class="row">
             	<div class="col-sm-6">
                	<div class="form-group">
                        <label class="control-label"> <?=lang('business_phone_label')?> </label>
                        <input class="form-control phone" name="business_phone" value="<?=($details['business_phone'])?$details['business_phone']:''?>" type="text">
                    </div>
                </div>
                <div class="col-sm-6">
                	<div class="form-group">
                        <label class="control-label">
                           <?=lang('business_established_label')?> 
                            <a 
                                href="javascript:void(0);" 
                                data-toggle="popover" 
                                data-placement="top" 
                                data-trigger="hover"
                                title="<strong><?=lang('business_established_label')?></strong>"  
                                data-content="Enter since year of start bussiness.">
                                &nbsp;<i class="fa fa-question-circle" aria-hidden="true">&nbsp;</i>
                           </a>
                        </label>
                        <input class="form-control " name="year_established" value="<?=($details['year_established'])?$details['year_established']:''?>" type="text">
                    </div>
                </div>
            </div>    
            
            
            
            <div class="form-group">
                <label class="control-label">                	
                    <?=lang('business_payment_label')?>
                    <a 
                        href="javascript:void(0);" 
                        data-toggle="popover" 
                        data-placement="top" 
                        data-trigger="hover"
                        title="<strong><?=lang('business_payment_label')?></strong>"  
                        data-content="Enter <?=lang('business_payment_label')?> like cash , debit card, paytm etc. with comma seperated for each method.">
                        &nbsp;<i class="fa fa-question-circle" aria-hidden="true">&nbsp;</i>
                   </a>
                </label>
                <input class="form-control " name="payment_method" value="<?=($details['payment_method'])?$details['payment_method']:''?>" type="text">
            </div>
            
            <div class="form-group">
                <label class="control-label"><?=lang('billing_details_label')?></label>
                <div class="checkbox includes-tax">
					<?php
                      if(isset($details['same_billing_detail']) && $details['same_billing_detail'] == 'yes'){
					  	$cheked = 'checked="checked"';
					  } else {$cheked = '';} 
                    ?>
                    <label> <input  name="same_billing_detail" value="yes" type="checkbox" <?=$cheked?>/>                          
                        <?=lang('billing_details_check_label')?>
                        <a 
                            href="javascript:void(0);" 
                            data-toggle="popover" 
                            data-placement="top" 
                            data-trigger="hover"
                            title="<strong>Upadate billing details</strong>"  
                            data-content="<?=lang('billing_details_label_desc')?>">
                            &nbsp;<i class="fa fa-question-circle" aria-hidden="true">&nbsp;</i>
                       </a>
                    </label> 
                </div>                                       
            </div>
            
        </div>
    </div>
    <!-- 1 -->
    <div role="presentation" class="divider"></div>   
    <div class="row">
        <div class="col-sm-4">
              <h4>  <?=lang('business_authorized_courses')?>  </h4>
              <p> <?=lang('business_authorized_courses_details')?> </p>
        </div>
        <div class="col-sm-8">
            <!-- -->
            <div class="form-group">
                <label class="control-label"> Select Courses </label>                   
                <select name="business_course[]"  class="form-control select2" multiple="multiple">                    
                    <?php
					
                    if(!empty($allcourses)){						
						$selected = set_value('business_course[]', $business_courses);						
						foreach( $allcourses as $course ){
							$selectedVal = (in_array($course['keyword_alias'] , $business_courses)) ? 'selected="selected"' : '';
							echo '<option '.$selectedVal.' value="'.$course['keyword_alias'].'" >'.$course['keyword_title'].'</option>';		   
						}
                    }
                    ?>
                </select>
            </div>
            <!-- -->
        </div>
    </div>
    <div role="presentation" class="divider"></div>
    <!-- 2 -->
    <div class="row">
        <div class="col-sm-4">
              <h4> <?=lang('regional_settings_label')?> </h4>
              <p> <?=lang('regional_settings_label_desc')?> </p>
        </div>
        <div class="col-sm-8">
            <!-- -->
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label"><?=lang('country_label')?></label>
                        <select name="country" class="form-control">                            
                            <option value="IN">India</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                    <label class="control-label"><?=lang('currency_label')?></label>                   
                        <select name="currency" class="form-control">                            
                            <option value="INR">Indian Rupee</option>
                        </select>
                    </div>
                </div>
            </div>            
                        
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group ">
                        <label class="control-label"><?=lang('date_format_label')?></label>
                        <select name="date_format" class="form-control">
                            <option value="1">26 Jun 2017</option>
                            <option selected="selected" value="2">Jun 26, 2017</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                    <label class=""><?=lang('time_format_label')?></label>                   
                        <select name="time_format" class="form-control">
                            <option selected="selected" value="1">4:40 AM</option>
                            <option value="2">04:40</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group ">
                        <label class="control-label"><?=lang('timezone_label')?></label>
                        <select name="time_zone" class="form-control">                          
                            <option value="Asia/Kolkata">(GMT+05:30) IST(Asia/Kolkata)</option>                                          
                        </select>
                    </div>
                </div>
            </div>
            <!-- -->
        </div>
    </div>
    <!-- 2 -->
    
    <div role="presentation" class="divider"></div>
    
    <!-- 3 -->
    <div class="row">
        <div class="col-sm-4">
              <h4> <?=lang('business_description_label')?> </h4>
              <p>  <?=lang('business_description_label_desc')?>  for use on your <a href=""> <strong>mini-website</strong> </a>.  </p>
        </div>
        <div class="col-sm-8">
            <!-- -->
            <div class="form-group ">
              <textarea name="business_details" class="form-control" id="" rows="10"><?=($details['business_details'])?$details['business_details']:''?></textarea>  
            </div>
        </div>
    </div>
    <!-- 3 -->
    
    <div role="presentation" class="divider"></div>
    
    <!-- 4 -->
    <div class="row">
        <div class="col-sm-4">
              <h4> <?=lang('business_logo_label')?>   </h4>
              <p>  <?=lang('business_logo_label_desc')?> </p>
        </div>
        <div class="col-sm-8">
            <!-- business_logo -->
            <div class="row">
                <div class="col-sm-12">
                <div class="col-sm-6">                
                    <div class="form-group">
                        <div class="logo">
                        	<?php
								$logoPath = base_url(BUSINESS_LOGO_PATH);
								$logoName = ($details['logo']) ? $details['logo'] : '' ;
								$logo = $logoPath . $logoName;								
								$bs_logo = displayImage($logo, DEFAULT_LOGO);
							?>
                            <div style="width: 250px; height: 150px; line-height: 150px;">
                                <img src="<?=$bs_logo?>" alt="" style="width: 250px; height: 150px; line-height: 150px;">
                            </div>
                        </div>
                    </div>
                    <div class="choose-file-ix">
                        <a class="btn btn-primary" href='javascript:void(0);'>
                            <?=lang('edit_logo_label')?>
                            <input type="file" name="logo" onchange='jQuery("#upload-file-info").html(jQuery(this).val());' />
                        </a> &nbsp;
                        <span class='label label-info' id="upload-file-info"></span>
                        <?php if(!empty($logoName)):?>
                            <a class="btn btn-danger" href="<?php echo site_url('settings/business/remove_logo?logo='.$logoName);?>">
                                <?=lang('remove_logo_label')?>                              
                            </a> 
                        <?php endif; ?>
                    </div>
                 </div>   
                   
                </div>
            </div>
            <!-- -->
        </div>
    </div>
    <!-- 4 -->

    <div role="presentation" class="divider"></div>
    
    <!-- 5 -->
    <div class="row">
        <div class="col-sm-4">
              <h4> <?=lang('social_label')?> </h4>
              <p> <?=lang('social_label_desc')?> </p>
        </div>
        <div class="col-sm-8">
            <!-- -->
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label"> <?=lang('twitter_label')?> </label>                   
                        <div class="input-group">
                        <span class="input-group-addon"><strong>@</strong></span>
                        <input value="<?=($details['twitter_url'])?$details['twitter_url']:''?>" class="form-control" name="twitter_url" type="text">
                        <span></span>
                        </div>
                        <span>  e.g. Formduniya </span>                                     
                    </div>
                </div>            
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label"> <?=lang('facebook_label')?> </label>                   
                        <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-facebook" aria-hidden="true"></i></span>
                        <input value="<?=($details['facebook_url'])?$details['facebook_url']:''?>" name="facebook_url" class="form-control" type="text">
                        <span></span>
                        </div>
                        <span>  e.g. http://www.facebook.com/formduniya </span>                                       
                    </div>
                </div>
            </div>
            <!-- -->
        </div>
    </div>
    <!-- 5 -->   
   <div role="presentation" class="divider"></div>
    <!-- 6 -->
    <div class="row">
        <div class="col-sm-12 text-right">                           
                <input type="hidden" name="business_id" value="<?=$businessID?>">
                <button type="submit" class="btn btn-primary btn-bg-dr padding-dr"><?=lang('submit_btn_label')?></button>
        </div>
    </div>
    <!-- 6 -->
  <?php echo form_close();?> 
  </div>
</div>

