<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<form class="form-vertical CUSTOM-tab-style common-css" id="Booking_settings" >
	<div class="row"> 
    	<div class="col-sm-4">
        	<h3>Booking settings </h3>
        </div>
        <div class="col-sm-8 switch-toggle">
        	<div class="onoffswitch">
                    <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="myonoffswitch" checked>
                    <label class="onoffswitch-label" for="myonoffswitch">
                        <span class="onoffswitch-inner"></span>
                        <span class="onoffswitch-switch"></span>
                    </label>
                </div>
        </div>

	</div>
    <div role="presentation" class="divider"></div>
    
  <div class="row">
      <div class="col-md-4">
        <h3>Settings</h3>
        <p>Choose how online bookings are confirmed and what options customers have when booking.</p>
      </div>
    <div class="col-sm-8">
    	<div class="">
            <div id="tab" class="btn-group" data-toggle="buttons-radio">
              <a href="#clientes" class="btn default btn-design active" data-toggle="tab">Confirmed<br>(automatically accepted)</a>
              <a href="#servicios" class="btn default btn-design" data-toggle="tab">Pencilled-in<br>(these can be accepted or declined)</a>
           </div>
            <div class="tab-content" style="border: 0px none;">
                <div class="tab-pane active" id="clientes">
              <div class="row">
                    <div class="col-md-12">
                     <div class="form-group">
                    <p>	Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent congue, lorem vel pulvinar pharetra, lorem velit aliquet turpis, id sollicitudin tellus elit finibus nisl. Vivamus mattis metus sit amet enim cursus, nec sagittis mauris blandit. Morbi tincidunt semper placerat. Vestibulum volutpat risus at vehicula finibus. In volutpat lorem et mauris sagittis sagittis at ac enim. </p>
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
        </div>
        <div class="row">
        	<div class="col-sm-12">
            	<div class="checkbox">
                    <label><input checked="checked" id="" name="" value="true" type="checkbox">The customer must enter an email address and telephone number  </label>
                </div>
                <div class="checkbox">
                    <label><input checked="checked" id="" name="" value="true" type="checkbox">Allow customers to book appointments with more than one service  </label>
                </div>
                <div class="checkbox">
                    <label><input checked="checked" id="" name="" value="true" type="checkbox"> Display the "Powered by Timely" logo and tagline
                        <a href="javascript:void(0);"><i class="fa fa-question-circle"></i></a>
                    </label>
                </div>
                <div>
                    <a href="javascript:void(0);" >Enable bookings across time zones...</a>
                </div>
            </div>
        </div>
        </div>
    </div> 
   <div role="presentation" class="divider"></div>
    <div class="row">
		<div class="col-md-4">
            <h3>Booking policy</h3>
            <p>Choose when online bookings can be made.</p>
        </div>
    <div class="col-sm-8">
         <div class="row">
        	<div class="col-md-12">
            	<div class="form-group">
                	<label for="">Customers can book appointments up to:</label>
                      <div class="inline-group ">
                          <select class="form-control" id="" name="" style="width: 106px; float: left; margin-right: 10px;">
                              <option value="1">1 hour</option>
                              <option selected="selected" value="2">2 hours</option>
                              <option value="3">3 hours</option>
                              <option value="4">4 hours</option>
                              <option value="5">5 hours</option>
                              <option value="6">6 hours</option>
                              <option value="12">12 hours</option>
                              <option value="18">18 hours</option>
                              <option value="24">24 hours</option>
                              <option value="48">48 hours</option>
                              <option value="72">72 hours</option>
                              <option value="168">1 week</option>
                              <option value="336">2 weeks</option>
                          </select> <span style="line-height: 33px;">   before start time. </span>
                      </div>
          </div>
            </div>
            <div class="col-md-12">
            	<div class="form-group">
                	<label for="">Customers can book appointments up to:</label>
                      <div class="inline-group ">
                      	<select class="form-control" id="" name="" style="width:65px; float: left; margin-right: 10px;">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option selected="selected" value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                       	 </select>	
                          <select class="form-control" id="" name="" style="width: 106px; float: left; margin-right: 10px;">
                            <option selected="selected" value="5">month(s)</option>
                            <option value="4">week(s)</option>
                            <option value="3">day(s)</option>
                          </select> <span style="line-height: 33px;"> in the future. </span>
                      </div>
          </div><br/>
         </div>
        </div>
           </div>   
        </div>
   <div role="presentation" class="divider"></div>
        <div class="row">
		<div class="col-md-4">
            <h3>Cancellations and changes</h3>
            <p>Choose when online bookings can be cancelled or changed.</p>
        </div>
        
    <div class="col-sm-8">
    	<div class="row">
        	<div class="col-md-12">
            	<div class="checkbox">
                <label><input checked="checked" id="" name="" value="true" type="checkbox">Include a link in emails for customers to change their booking        </label>
            </div>
            <div class="form-group">
                	<label for="">Set when appointments can be changed or cancelled:</label>
                      <div class="inline-group ">
                      	<div class="radio" style="width:65px; float: left; margin-right: 2px;">
                        <label class="">
                            <input checked="'checked'" name="ChangePolicyInHoursRadio" value="manual" type="radio">Up to &nbsp; &nbsp;
                        </label>
                    </div>
                          <select class="form-control" id="" name="" style="width: 106px; float: left; margin-right: 10px;">
                            <option value="3">3 hours</option>
                            <option value="6">6 hours</option>
                            <option value="12">12 hours</option>
                            <option selected="selected" value="24">24 hours</option>
                            <option value="48">48 hours</option>
                            <option value="72">72 hours</option>
                            <option value="168">1 week</option>
                            <option value="336">2 week</option>
                          </select> <span style="line-height: 33px;"> before their appointment. </span>
                      </div>
                      
                      
         		 </div>
                <div class="form-group clear">
                    <div class="radio">
                          <label>
                              <input name="" value="anytime" type="radio">Anytime
                          </label>
                      </div>
                </div>
                <div class="form-group">
                    <div class="radio">
                          <label>
                              <input name="" value="name="Never"" type="radio">Never
                          </label>
                      </div>
                </div>         
                <div class="form-group">
                 <label for="">This is what your customers will see:</label>
                    <div class="alert alert-info">No cancellations or changes allowed within 24 hours of the appointment</div>
                <label for="">Additional cancellation terms:</label>
                <div class="">
                    <textarea class="char-count-1000 content-textarea form-control" cols="100" id="CancellationText" name="CancellationText" rows="5"></textarea>
                    <small class="counter">Characters left: 1000</small>
                </div>

            </div>       
        </div>
   		 </div>
        </div>
        </div>
   <div role="presentation" class="divider"></div>
		<div class="row">
			<div class="col-md-4">
            <h3 id="payments">Online payment terms</h3>
            <p> You can charge the full amount or a deposit for online bookings.<br>
                You can also change these settings <a href="#">per service</a>.
            </p>
        </div>
          	<div class="col-md-8">
            	<div class="alert alert-info">
                    <h3>No payment gateways configured</h3>
                    You must configure at least one <a href="#">payment gateway</a> before you can accept online payments
                </div>
            </div>
        </div>   
   <div role="presentation" class="divider"></div>
 <div class="row">
 		<div class="col-sm-4">
                <h3 id="appearance">Appearance</h3>
                <p>Custom colours to match your brand</p>
                <h4>Preview:</h4>
                <div class="model-preview">
                    <div class="modal-header" id="previewModelHeader" style="background-color:; color:">
                        Header
                    </div>
                    <div class="modal-body">
                        <a href="" id="previewLink" style="color:">Link</a>
                    </div>
                    <div class="modal-footer">
                        <span class="btn btn-large btn-bg-dr forward online-booking-preview-btn" id="previewButton" style="border-color:; background-color:; color:">Button&nbsp;<i class="fa fa-chevron-right"></i></span>
                    </div>
                </div>
            </div> 
            <div class="col-sm-8"> 
            	<div class="form-group">
                    <div class="inline-group">
                        <div class="radio">
                            <label class="">
                                <input checked="checked" id="DefaultTimelyTheme" name="DefaultTimelyTheme" value="True" type="radio"> Use the default "Timely" theme
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="inline-group">
                        <div class="radio">
                            <label class="">
                                <input id="NeutralTimelyTheme" name="NeutralTimelyTheme" value="True" type="radio"> Use the "Neutral" theme
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="inline-group">
                        <div class="radio">
                            <label class="">
                                <input id="CustomTimelyTheme" name="CustomTimelyTheme" value="True" type="radio"> Add custom colours
                            </label>
                        </div>
                    </div>
                </div>
            </div>
   	 </div>
     <div role="presentation" class="divider"></div>
     <div class="row">
        <div class="col-md-4">
            <h3>Add your own text</h3>
            <p>Add instructions, extra information or customize text to each step or section of the online booking process.</p>
        </div> 
        <div class="col-sm-8"> 
            <div class="form-group ">
                <label for="">"Select service" step</label>
                <textarea class="form-control" cols="100" id="" name="" rows="2"></textarea><small class="counter">Characters left: 250</small>
            </div>
            <div class="form-group ">
                <label for="">"Select staff" step</label>
                <textarea class="form-control" cols="100" id="" name="" rows="2"></textarea><small class="counter">Characters left: 250</small>
            </div>
            <div class="form-group ">
              <label for="">"Select date/time" step</label>
                <textarea class="form-control" cols="100" id="" name="" rows="2"></textarea><small class="counter">Characters left: 250</small>
            </div>
          <div class="form-group ">
            <label for="">"Enter customer details" step</label>
           <textarea class="form-control" cols="100" id="" name="" rows="2"></textarea><small class="counter">Characters left: 250</small>
          </div>
        <div class="form-group ">
              <label for="">"Appointment confirmed" step</label>
              <textarea class="form-control" cols="100" id="" name="" rows="2"></textarea><small class="counter">Characters left: 250</small>
         </div>
         <div class="form-group ">
            <label for="">"No service available" text</label>
            <textarea class="form-control" cols="100" id="" name="" rows="2"></textarea><small class="counter">Characters left: 250</small>
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
     
 </form>
