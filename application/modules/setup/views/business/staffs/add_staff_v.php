<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="panel panel-white">
<div class="panel-body">
  <div class="container">
  	<?php 
		$attributes = array( 'class'=>'form-vertical common-css add_staff','id'=>'add_staff','role'=>'form' , 'enctype'=>'multipart/form-data' );
		echo form_open('settings/business/add_staff', $attributes )?>        
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
                  <input type="text" name="first_name" class="form-control" id="first_name" value="<?=set_value('first_name')?>">
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="last_name">Last Name <span class="symbol required"></span></label>
                  <input type="text" name="last_name" class="form-control" id="last_name" value="<?=set_value('last_name')?>">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="phone_number">Telephone</label>
                  <input type="text" name="phone_number" id="phone_number" class="form-control" value="<?=set_value('phone_number')?>">
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="sms_number">SMS number</label>
                  <div class="input-group"> <span class="input-group-addon">+91</span>
                    <input type="text" name="sms_number" id="sms_number" class="form-control" value="<?=set_value('sms_number')?>">
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <div class="form-group">
                  <label class="email">Email</label>
                  <div class="input-group"> <span class="input-group-addon"> <i class="fa fa-envelope" aria-hidden="true"></i> </span>
                    <input type="email" name="email" id="email" class="form-control" value="<?=set_value('email')?>">
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="address">Address</label>
                  <input type="text" name="address" id="address" class="form-control" value="<?=set_value('address')?>">
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="zipcode"> Zipcode </label>
                  <input type="text" name="zipcode" id="zipcode" class="form-control" value="<?=set_value('zipcode')?>">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="city">City</label>
                  <input type="text" name="city" id="city" class="form-control" value="<?=set_value('city')?>">
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="state"> State </label>
                  <input type="text" name="state" id="state" class="form-control" value="<?=set_value('state')?>">
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
                            	<label><input 
                                	type="checkbox" 
                                    name="list_staff_person" 
                                    value="yes"> Do you want to list this staff as contact person?  </label>
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
                  <input type="text" name="nickname" class="form-control" id="nickname" value="<?=set_value('nickname')?>">
                 </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group ">
                  <label class="job_title">Job title </label>
                  <input type="text" name="job_title" class="form-control" id="job_title" value="<?=set_value('nickname')?>">
                 </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="personal_phone">Phone</label>
                  <div class="input-group"> <span class="input-group-addon">+91</span>
                    <input type="text" name="personal_phone" id="personal_phone" class="form-control" value="<?=set_value('personal_phone')?>">
                  </div>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="personal_email">Alternate Email</label>
                  <div class="input-group"> <span class="input-group-addon"> <i class="fa fa-envelope" aria-hidden="true"></i> </span>
                    <input type="email" name="personal_email" id="personal_email" class="form-control" value="<?=set_value('personal_email')?>">
                  </div>
                </div>
              </div>
            </div>
            
            <div class="row">
              <div class="col-sm-12">
                <div class="form-group">
                  <label class="biography">Bio <span>Max Characters: 500</span></label>
                  <textarea name="biography" id="biography" rows="8" class="form-control" value="<?=set_value('biography')?>"></textarea>
                </div>
              </div>
            </div>
                        
            <div class="form-group">
                <label class="control-label">Image Upload</label>
                <div class="fileupload fileupload-new" data-provides="fileupload">
                    <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;"><img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA?text=no+image" alt=""/>
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
        	<input type="hidden" name="business_id" value="<?=loginCompanyInfo('business_id')?>" />
       	 	<button type="button" class="btn btn-default padding-dr">Cancel</button>
        	<button type="submit" class="btn btn-primary padding-dr">Save</button>
      	</div>
    <?=form_close()?>
  </div>
</div>
</div>
