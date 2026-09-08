<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="panel panel-white">
<div class="panel-body">
</div>
</div>
<div class="common-css ">
    <div>
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-info">
                <i class="fa fa-info-circle fa-2x pull-left"></i>
                <p class="lead">
                    Your staff members are shown below. You can add more staff any time. If you have finished adding staff, you are ready to head on to the next step.
                </p>
                <div class="clearfix"></div>
            </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
          	  <h3>Staff</h3>
            </div>
            <div class="col-sm-6">
              <div class="form-actions text-right">
               <a href="<?=site_url('settings/business/add_staff')?>" class="btn btn-bg-dr padding-dr">Add staff</a>
            </div>
            </div>
        </div>
    <hr>
       <div class="row">
          <div class="col-sm-1">
            <div class="card__thumb-">
                <img class="" alt="img" src="<?php echo base_url(); ?>assets/modules/images/placeholder_70x70.gif">
            </div>
          </div>
             <div class="col-sm-8">
            <div class="card__body-">
              <h4 class="card__title">
                  <span data-bind="text: name">Manoj Kumar</span>
                  <a  class="modal-open" href="#"><i class="fa fa-envelope"></i></a>
                  <a style="display: none;"><i class="fa fa-key"></i></a>
                  <a style="display: none;"><i class="fa fa-envelope"></i></a>
                  <a style="display: none;"><i class="fa fa-calendar"></i></a>
              </h4>

              <ul class="name-list-detail">
                  <li>
                      <h5>Email</h5>
                      <div data-bind="text: email">manoj1kumar1@gmail.com</div>
                  </li>
                  <li class="short-column">
                      <h5>Phone</h5>
                      <div data-bind="text: phone">N/A</div>
                  </li>
                  <li class="short-column">
                      <h5>SMS</h5>
              <div><span data-bind="text: smsCode"></span><span data-bind="text: sms">N/A</span></div>
                  </li>
              </ul>

                        </div>
          </div>
          <div class="col-sm-3">
            <div class="card__actions-">
              <a class="btn btn-bg-dr btn-padded btn-sm" href="#">Edit</a>
              <a class="btn btn-default btn-padded btn-sm"  href="javascript:void(0);">Archive</a>
              <a class="pop btn btn-danger btn-sm" href="javascript:void(0);"> <i class="fa fa-trash-o"></i></a>
          </div>
              
      </div>
    
    
    </div>
  </div>
  </div>