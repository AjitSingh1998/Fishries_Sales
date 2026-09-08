<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<form class="form-vertical CUSTOM-tab-style hoursStyles common-css" id="calendar-settings ">
  <div class="row">
            <div class="col-md-12 col-bottom-margin">
                <div class="alert alert-info">
                    <i class="fa fa-info-circle fa-2x pull-left"></i>
                    <p>
                        Welcome to the Calendar setup page, from within this page you can control the appearance of the calendar, setup new cancellation reasons, and new appointment
                        statuses.
                    </p>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
  <div class="row">
    <div class="col-md-4">
            <h3>Display settings</h3>
            <p>Set up your calendar to match the way you work.</p>
        </div>
    <div class="col-sm-8">
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group ">
                <label for="CalendarFirstDay">First day of the week:</label>
                <select class="form-control" id="CalendarFirstDay" name="CalendarFirstDay"><option selected="selected" value="0">Sunday</option>
                    <option value="1">Monday</option>
                    <option value="2">Tuesday</option>
                    <option value="3">Wednesday</option>
                    <option value="4">Thursday</option>
                    <option value="5">Friday</option>
                    <option value="6">Saturday</option>
                    <option value="-2"></option>
                    <option value="-1">Current day</option>
                </select>
            </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <div class="hour">
            <label for="">Calendar start time:</label>
            <div class="form-group"> 				 <!--  -----------Monday---------->
            <div class="btn-group">
                 <label for="" class="active">
                    <select name="hours" class="form-control drop-select select-menu" data-parsley-id="8950">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
                <option value="7">7</option>
                <option value="8">8</option>
                <option value="9" selected="'selected'">9</option>
                <option value="10">10</option>
                <option value="11">11</option>
                <option value="12">12</option>
        </select>
                </label>
                 <label for="" class="active">
                <select name="minutes" class="form-control drop-select select-menu2" data-parsley-id="7110">
                    <option value="0" selected="'selected'">00</option>
                    <option value="1">01</option>
                    <option value="2">02</option>
                    <option value="3">03</option>
                    <option value="4">04</option>
                    <option value="5">05</option>
                    <option value="6">06</option>
                    <option value="7">07</option>
                    <option value="8">08</option>
                    <option value="9">09</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                    <option value="13">13</option>
                    <option value="14">14</option>
                    <option value="15">15</option>
                    <option value="16">16</option>
                    <option value="17">17</option>
                    <option value="18">18</option>
                    <option value="19">19</option>
                    <option value="20">20</option>
                    <option value="21">21</option>
                    <option value="22">22</option>
                    <option value="23">23</option>
                    <option value="24">24</option>
                    <option value="25">25</option>
                    <option value="26">26</option>
                    <option value="27">27</option>
                    <option value="28">28</option>
                    <option value="29">29</option>
                    <option value="30">30</option>
                    <option value="31">31</option>
                    <option value="32">32</option>
                    <option value="33">33</option>
                    <option value="34">34</option>
                    <option value="35">35</option>
                    <option value="36">36</option>
                    <option value="37">37</option>
                    <option value="38">38</option>
                    <option value="39">39</option>
                    <option value="40">40</option>
                    <option value="41">41</option>
                    <option value="42">42</option>
                    <option value="43">43</option>
                    <option value="44">44</option>
                    <option value="45">45</option>
                    <option value="46">46</option>
                    <option value="47">47</option>
                    <option value="48">48</option>
                    <option value="49">49</option>
                    <option value="50">50</option>
                    <option value="51">51</option>
                    <option value="52">52</option>
                    <option value="53">53</option>
                    <option value="54">54</option>
                    <option value="55">55</option>
                    <option value="56">56</option>
                    <option value="57">57</option>
                    <option value="58">58</option>
                    <option value="59">59</option>
                </select>
                </label>
                 <label for="" class="active">
                 <select name="ampm" class="form-control drop-select select-menu3" data-parsley-id="6520">
                    <option value="am" selected="'selected'">am</option>
                    <option value="pm">pm</option>
                  </select>
                </label>
            </div>
        </div>
      </div>
     </div>
    </div>
  </div>
  <div class="row">
  <div class="col-sm-12">
      <div class="form-group">
         <label for="">Calendar intervals:</label>
          <select class="form-control" id="" name="">
             <option value="5">5 minutes</option>
            <option value="10">10 minutes</option>
            <option selected="selected" value="15">15 minutes</option>
            <option value="20">20 minutes</option>
            <option value="30">30 minutes</option>
            <option value="45">45 minutes</option>
        </select>
            </div>
            <div class="checkbox">
                <label class=""><input id="" name="" value="true" type="checkbox">Display the calendar in high contrast mode </label>
            </div>
      </div>
  </div>
 </div> <hr>
 
 
 </div>
  <div role="presentation" class="divider"></div>
 <div class="row">
 	<div class="col-sm-4">
    	<h3>Appointment settings</h3>
    </div>
    <div class="col-sm-8">
    	<div class="row">
        	<div class="col-sm-12">
            	<div class="form-group">
                    <label for="">Initial status for new appointments:</label>
                     <a href="javascript:void(0);">&nbsp;<i class="fa fa-question-circle">&nbsp;</i></a>
                    <select class="form-control" id="" name="">
                        <option selected="selected" value="2">Confirmed</option>
                        <option value="1">Pencilled-in</option>
                    </select>
            </div>
            <div class="checkbox">
                <label class=""><input id="" name="" value="true" type="checkbox"><input name="CompanyNameEnabled" value="false" type="hidden"> Add company name field for customers</label>
            </div>
            <div class="checkbox">
                <label class=""><input id="" name="" value="true" type="checkbox"><input name="CompanyNameEnabled" value="false" type="hidden"> Allow appointments to be deleted</label>
            </div>
            </div>
        </div>
    </div>
 </div>
  <div role="presentation" class="divider"></div>
 <div class="row">
 	<div class="col-md-4">
            <h3>Daily appointment summary</h3>
            <p>This is sent to the account holder.</p>
            <p>You can also turn this on for <a href="/Settings/StaffList">individual staff</a>.</p>
        </div>
    <div class="col-sm-8">
    	<div class="row">
        	<div class="col-sm-12">
            <br/> <br/>
            	<div class="checkbox">
                <label class=""><input id="" name="" value="true" type="checkbox"><input name="CompanyNameEnabled" value="false" type="hidden"> Receive an email summary of all appointments for the day.</label>
            </div>
           </div>
                    
            </div>
        </div>
    </div>
   <div role="presentation" class="divider"></div>
  <div class="row">
 	<div class="col-md-4">
            <h3>Cancellation reasons</h3>
            <p> You can choose from this list when you cancel an appointment. </p>
        </div>
    <div class="col-sm-8">
    	<div class="row">
        	<div class="col-sm-12">
           	<table class="table">
                <thead>
                    <tr>
                        <th style="border: 0px none;" >Reason</th>
                        <th style="text-align: center; vertical-align: middle; border: 0px none;" width="10%"><a class="btn btn-small btn-bg-dr" href="javascript:;" data-bind="click: addItem"><i class="fa fa-plus"></i></a></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td width="82%" style="border: 0px none;" > <input class="form-control" id="" name="" type="text"><span></span></td>
                        <td style="text-align: center; vertical-align: middle; border: 0px none;" width="10%"><a href="#"><i class="fa fa-trash-o fa-lg  fa-fw"></i></a></td>
                    </tr>
                   <tr>
                        <td width="82%" style="border: 0px none;" > <input class="form-control" id="" name="" type="text"><span></span></td>
                        <td style="text-align: center; vertical-align: middle; border: 0px none;" width="10%"><a href="#"><i class="fa fa-trash-o fa-lg  fa-fw"></i></a></td>
                    </tr>
                    <tr>
                        <td width="82%" style="border: 0px none;" > <input class="form-control" id="" name="" type="text"><span></span></td>
                        <td style="text-align: center; vertical-align: middle; border: 0px none;" width="10%"><a href="#"><i class="fa fa-trash-o fa-lg  fa-fw"></i></a></td>
                    </tr>
                	
                </tbody>
                </table>
            	
           </div>
                    
            </div>
        </div>
    </div>
  <div role="presentation" class="divider"></div>
  <div class="row">
 	<div class="col-md-4">
            <h3>Appointment statuses</h3>
            <p>These can be saved against appointments and will appear in reports.</p>
        </div>
    <div class="col-sm-8">
    	<div class="row">
        	<div class="col-sm-12">
           	<table class="table">
                <thead>
                    <tr>
                        <th style="border: 0px none;" >Status</th>
                        <th style="text-align: center; vertical-align: middle; border: 0px none;" width="10%"><a class="btn btn-small btn-bg-dr" href="javascript:;" data-bind="click: addItem"><i class="fa fa-plus"></i></a></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td width="82%" style="border: 0px none;" ><input class="form-control" value="Not Started" title="" name="" readonly type="text"> <span></span></td>
                        <td style="text-align: center; vertical-align: middle; border: 0px none;" width="10%"><a href="#"><i class="fa  fa-fw fa-lock fa-lg"></i></a></td>
                    </tr>
                   <tr>
                        <td width="82%" style="border: 0px none;" > <input class="form-control" value="Arrived" title="" name="" readonly type="text"> <span></span></td>
                        <td style="text-align: center; vertical-align: middle; border: 0px none;" width="10%"><a href="#"><i class="fa  fa-fw fa-lock fa-lg"></i></a></td>
                    </tr>
                    <tr>
                        <td width="82%" style="border: 0px none;" > <input class="form-control" value="Started" title="" name="" readonly type="text"> <span></span></td>
                        <td style="text-align: center; vertical-align: middle; border: 0px none;" width="10%"><a href="#"><i class="fa  fa-fw fa-lock fa-lg"></i></a></td>
                    </tr>
                    <tr>
                        <td width="82%" style="border: 0px none;" > <input class="form-control" value="Completed" title="" name="" readonly type="text"> <span></span></td>
                        <td style="text-align: center; vertical-align: middle; border: 0px none;" width="10%"><a href="#"><i class="fa  fa-fw fa-lock fa-lg"></i></a></td>
                    </tr>
                    <tr>
                        <td width="82%" style="border: 0px none;" > <input class="form-control" value="Did not show" title="" name="" readonly type="text"> <span></span></td>
                        <td style="text-align: center; vertical-align: middle; border: 0px none;" width="10%"><a href="#"><i class="fa  fa-fw fa-lock fa-lg"></i></a></td>
                    </tr>
                	
                </tbody>
                </table>
            </div>
          </div>
        </div>
    </div>
  <div role="presentation" class="divider"></div>
    <div class="row">
    	<div class="col-sm-12 btn-align-">
        	<button type="button" class="btn btn-bg-dr btn-padded">Save</button>
        </div>
    </div>
 
  
  </form>
