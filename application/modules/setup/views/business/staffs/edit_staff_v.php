<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="panel panel-white">
<div class="panel-body">
  <div class="container">  
  	<?php
		$staff_id = $this->uri->segment(4);
		if(empty($staff_id)){
			$this->messageci->set('Please select staff to edit','error');
			redirect(site_url('settings/business/staff'));
		}

		$attributes = array( 'class'=>'form-vertical common-css edit_staff','id'=>'edit_staff','role'=>'form' , 'enctype'=>'multipart/form-data' );
		echo form_open('settings/business/edit_staff/'.$staff_id.'?staff='.$staff_id, $attributes );
		
		if(!empty($staff_details)){
			
			$business_id 		= $staff_details['business_id'];
			$staff_id 			= $staff_details['user_id'];
			$group_id 			= $staff_details['group_id'];
			$list_staff_person 	= $staff_details['list_staff_person'];
			$first_name 		= set_value('first_name', $staff_details['firstName'] );
			$last_name 			= set_value('last_name', $staff_details['lastName'] );
			$phone_number 		= set_value('phone_number', $staff_details['phone_number'] );
			$sms_number 		= set_value('sms_number', $staff_details['sms_number'] );
			$email 				= set_value('email', $staff_details['email'] );
			$address 			= set_value('address', $staff_details['address'] );
			$zipcode			= set_value('zipcode', $staff_details['zipcode'] );
			$city 				= set_value('city', $staff_details['city'] );
			$state 				= set_value('state', $staff_details['state'] );
			$nickname 			= set_value('nickname', $staff_details['nickname'] );
			$job_title 			= set_value('job_title', $staff_details['job_title'] );
			$personal_phone 	= set_value('personal_phone', $staff_details['personal_phone'] );
			$personal_email 	= set_value('personal_email', $staff_details['personal_email'] );
			$biography 			= set_value('biography', $staff_details['biography'] );
			$profile_image 		= $staff_details['profile_image'];
		} else {
			$this->messageci->set('Please select staff to edit or Staff user not found.','error');
			redirect(site_url('settings/business/staff'));
		}
    ?>  
    	<div class="row">
            <div class="col-sm-12"><?=validation_errors();?></div>
        </div>
        <div class="row">
          <div class="col-sm-4">
            <h4> Staff details </h4>
            <p> The SMS number and email will be used for staff reminders. </p>
          </div>
          <div class="col-sm-8">
            
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="first_name">First name <span class="symbol required"></span></label>
                  <input type="text" name="first_name" class="form-control" id="first_name" value="<?=$first_name?>">
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="last_name">Last Name <span class="symbol required"></span></label>
                  <input type="text" name="last_name" class="form-control" id="last_name" value="<?=$last_name?>">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="phone_number">Telephone</label>
                  <input type="text" name="phone_number" id="phone_number" class="form-control" value="<?=$phone_number?>">
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="sms_number">SMS number</label>
                  <div class="input-group"> <span class="input-group-addon">+91</span>
                    <input type="text" name="sms_number" id="sms_number" class="form-control" value="<?=$sms_number?>">
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <div class="form-group">
                  <label class="email">Email</label>
                  <div class="input-group"> <span class="input-group-addon"> <i class="fa fa-envelope" aria-hidden="true"></i> </span>
                    <input type="email" name="email" id="email" class="form-control" value="<?=$email?>">
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="address">Address</label>
                  <input type="text" name="address" id="address" class="form-control" value="<?=$address?>">
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="zipcode"> Zipcode </label>
                  <input type="text" name="zipcode" id="zipcode" class="form-control" value="<?=$zipcode?>">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="city">City</label>
                  <input type="text" name="city" id="city" class="form-control" value="<?=$city?>">
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="state"> State </label>
                  <input type="text" name="state" id="state" class="form-control" value="<?=$state?>">
                </div>
              </div>
            </div>
          </div>
        </div>
       
        <hr/> 
        <div class="row">
            <div class="col-sm-4">
            <h4> List staff as contact </h4>
            <p> If you mark this staff as a contact person, then it will be display in contact area on your mini website. </p>
            </div>
            <div class="col-sm-8">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group ">
                            <div class="checkbox">
                            	<?php
                                if(!empty($list_staff_person) && $list_staff_person == 'yes'){
									$sChked = 'checked="checked"';
								} else { 
									$sChked = '';
								}
								?>
                            	<label><input 
                                	type="checkbox" 
                                    name="list_staff_person" 
                                    value="yes"
                                    <?=$sChked?> > Do you want to list this staff as contact person?
                                </label>
                            </div>  
                        </div>
                    </div>
                </div>
            </div>
        </div>
              
        <hr/>               
               
        <div class="row">
          <div class="col-sm-4">
            <h4> Personal info </h4>
            <p> This information will be shown to customers when they book online and in emails, SMS messages, and invoices. </p>
          </div>
          <div class="col-sm-8">
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group ">
                  <label class="nickname">Alias / nickname </label>
                  <input type="text" name="nickname" class="form-control" id="nickname" value="<?=$nickname?>">
                 </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group ">
                  <label class="job_title">Job title </label>
                  <input type="text" name="job_title" class="form-control" id="job_title" value="<?=$job_title?>">
                 </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="personal_phone">Phone</label>
                  <div class="input-group"> <span class="input-group-addon">+91</span>
                    <input type="text" name="personal_phone" id="personal_phone" class="form-control" value="<?=$personal_phone?>">
                  </div>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="personal_email">Alternate Email</label>
                  <div class="input-group"> <span class="input-group-addon"> <i class="fa fa-envelope" aria-hidden="true"></i> </span>
                    <input type="email" name="personal_email" id="personal_email" class="form-control" value="<?=$personal_email?>">
                  </div>
                </div>
              </div>
            </div>
            
            <div class="row">
              <div class="col-sm-12">
                <div class="form-group">
                  <label class="biography">Bio <span>Max Characters: 500</span></label>
                  <textarea name="biography" id="biography" rows="8" class="form-control"><?=$biography?></textarea>
                </div>
              </div>
            </div>
                        
            <div class="form-group">
                <label class="control-label">Image Upload</label>
                <div class="fileupload fileupload-new" data-provides="fileupload">
                    <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
                    <?php
                    if(!empty($profile_image)){
						$image_url = base_url().STAFF_PROFILE_IMAGE_PATH.$profile_image;
					} else {
						$image_url = 'http://www.placehold.it/200x150/EFEFEF/AAAAAA?text=no+image';
					}
					?>
                    
                    <img src="<?=$image_url?>" alt="<?=$first_name?>"/>
                    </div>
                    <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
                    <div>
                        <span class="btn btn-light-grey btn-file"><span class="fileupload-new"><i class="fa fa-picture-o"></i> Select image </span><span class="fileupload-exists"><i class="fa fa-picture-o"></i> Change</span>
                            <input type="file" name="profile_image">
                        </span>
                        <a href="#" class="btn fileupload-exists btn-light-grey" data-dismiss="fileupload">
                            <i class="fa fa-times"></i> Remove
                        </a>
                    </div>
                </div>                
            </div>           
            
          </div>
        </div>
        <hr/>
        
      	<div class="form-group text-right">
        	<input type="hidden" name="business_id" value="<?=$business_id?>" />
        	<input type="hidden" name="staff_id" value="<?=$staff_id?>" />
       	 	<button type="button" class="btn btn-default padding-dr">Cancel</button>
        	<button type="submit" class="btn btn-primary padding-dr">Save</button>
      	</div>
    <?=form_close()?>
  </div>
</div>
</div>
