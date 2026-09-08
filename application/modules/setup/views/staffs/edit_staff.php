<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="panel panel-white">
  <div class="panel-body">
    <div class="container">
    <form class="form-vertical common-css">
    	<div class="row">
      <div class="col-sm-8">
        <h1> <a href="<?=site_url('settings')?>"><?=lang('page_title')?></a> &nbsp; <i class="fa fa-angle-right"></i> &nbsp; <?=$page_title?></h1>
      </div>
      <div class="col-sm-4 btn-align-">
        <button type="button" class="btn btn-default padding-dr">Cancel</button>
        <button type="button" class="btn btn-primary padding-dr">Save</button>
      </div>
    </div>
        <div class="row">
          <div class="col-sm-4">
            <h2> Staff details </h2>
            <p> The SMS number and email will be used for staff reminders. </p>
          </div>
          <div class="col-sm-8">
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group ">
                  <label class="">First name</label>
                  <input class="form-control address" id="" name="" value="" type="text">
                  <span></span> </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group ">
                  <label class="">Last Name</label>
                  <input class="form-control address" id="" name="" value="" type="text">
                  <span></span> </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group ">
                  <label class="">Telephone</label>
                  <input class="form-control address" id="" name="" value="" type="number">
                  <span></span> </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group ">
                  <label class="">SMS number</label>
                  <div class="input-group"> <span class="input-group-addon">+91</span>
                    <input value="" class="form-control" type="number">
                    <span></span> </div>
                  <span></span> </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <div class="form-group ">
                  <label class="">Email</label>
                  <div class="input-group"> <span class="input-group-addon"> <i class="fa fa-envelope" aria-hidden="true"></i> </span>
                    <input value="" class="form-control" type="email">
                    <span></span> </div>
                  <span></span> </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group ">
                  <label class="">Address</label>
                  <input value="" class="form-control" type="text">
                  <span></span> <span></span> </div>
                <div class="form-group ">
                  <input value="" class="form-control" type="text">
                  <span></span> <span></span> </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group ">
                  <label class=""> City </label>
                  <input value="" class="form-control" type="text">
                  <span></span> <span></span> </div>
              </div>
            </div>
          </div>
          <div role="presentation" class="divider"></div>
          <!-- -->
          <div class="col-sm-4">
            <h2> Normal working hours </h2>
            <p> Set availibilty for bookings. </p>
          </div>
          <div class="col-sm-8" >
            <div class="row">
              <div class="col-sm-12">
                <div class="form-group">
                  <div class="col-sm-4 nopadding"> <strong>Can be booked online every</strong> </div>
                  <div class="col-sm-2 nopadding">
                    <select class="form-control">
                      <option value=""> 15 Minutes</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <div class="form-group ">
                  <div class="checkbox includes-tax">
                    <label>
                      <input type="checkbox" />
                      Reset online booking time after a break. </label>
                    <span></span> </div>
                </div>
              </div>
            </div>
            <div class="row hoursStyles">
              <div class="hour">
                <div class="[ form-group ]"> <!--  -----------Monday---------->
                  <input type="checkbox" name="fancy-checkbox-info" id="fancy-checkbox-info" autocomplete="off" />
                  <div class="[ btn-group ]">
                    <label for="fancy-checkbox-info" class="[ btn btn-info circle-dr ]"> <span class="[ glyphicon glyphicon-ok ]"></span> <span> </span> </label>
                    <label for="fancy-checkbox-info" class="[active day-dr ]"> Monday </label>
                    <label for="" class="[active ]">
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
                    <label for="" class="[active ]">
                      <select name="minutes" class="form-control drop-select select-menu2" data-parsley-id="7110" >
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
                    <label for="" class="[active ]">
                      <select name="ampm" class="form-control drop-select select-menu3" data-parsley-id="6520">
                        <option value="am" selected="'selected'">am</option>
                        <option value="pm">pm</option>
                      </select>
                    </label>
                    <span style="padding: 0px 8px;">to</span>
                    <label for="" class="[active ]">
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
                    <label for="" class="[active ]">
                      <select name="minutes" class="form-control drop-select select-menu2" data-parsley-id="7110" >
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
                    <label for="" class="[active ]">
                      <select name="ampm" class="form-control drop-select select-menu3" data-parsley-id="6520">
                        <option value="am" selected="'selected'">am</option>
                        <option value="pm">pm</option>
                      </select>
                    </label>
                  </div>
                </div>
                <!--  ----------------------------Monday-END------------------------------>
                
                <div class="[ form-group ]"> <!--  -----------Tuesday---------->
                  <input type="checkbox" name="fancy-checkbox-info" id="fancy-checkbox-info" autocomplete="off" />
                  <div class="[ btn-group ]">
                    <label for="fancy-checkbox-info" class="[ btn btn-info circle-dr ]"> <span class="[ glyphicon glyphicon-ok ]"></span> <span> </span> </label>
                    <label for="fancy-checkbox-info" class="[active day-dr ]"> Monday </label>
                    <label for="" class="[active ]">
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
                    <label for="" class="[active ]">
                      <select name="minutes" class="form-control drop-select select-menu2" data-parsley-id="7110" >
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
                    <label for="" class="[active ]">
                      <select name="ampm" class="form-control drop-select select-menu3" data-parsley-id="6520">
                        <option value="am" selected="'selected'">am</option>
                        <option value="pm">pm</option>
                      </select>
                    </label>
                    <span style="padding: 0px 8px;">to</span>
                    <label for="" class="[active ]">
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
                    <label for="" class="[active ]">
                      <select name="minutes" class="form-control drop-select select-menu2" data-parsley-id="7110" >
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
                    <label for="" class="[active ]">
                      <select name="ampm" class="form-control drop-select select-menu3" data-parsley-id="6520">
                        <option value="am" selected="'selected'">am</option>
                        <option value="pm">pm</option>
                      </select>
                    </label>
                  </div>
                </div>
                <!--------------------------Tuesday-END------------------------------->
                
                <div class="[ form-group ]"> <!--  -----------Wednesday---------->
                  <input type="checkbox" name="fancy-checkbox-info" id="fancy-checkbox-info" autocomplete="off" />
                  <div class="[ btn-group ]">
                    <label for="fancy-checkbox-info" class="[ btn btn-info circle-dr ]"> <span class="[ glyphicon glyphicon-ok ]"></span> <span> </span> </label>
                    <label for="fancy-checkbox-info" class="[active day-dr ]"> Monday </label>
                    <label for="" class="[active ]">
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
                    <label for="" class="[active ]">
                      <select name="minutes" class="form-control drop-select select-menu2" data-parsley-id="7110" >
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
                    <label for="" class="[active ]">
                      <select name="ampm" class="form-control drop-select select-menu3" data-parsley-id="6520">
                        <option value="am" selected="'selected'">am</option>
                        <option value="pm">pm</option>
                      </select>
                    </label>
                    <span style="padding: 0px 8px;">to</span>
                    <label for="" class="[active ]">
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
                    <label for="" class="[active ]">
                      <select name="minutes" class="form-control drop-select select-menu2" data-parsley-id="7110" >
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
                    <label for="" class="[active ]">
                      <select name="ampm" class="form-control drop-select select-menu3" data-parsley-id="6520">
                        <option value="am" selected="'selected'">am</option>
                        <option value="pm">pm</option>
                      </select>
                    </label>
                  </div>
                </div>
                <!--  -----------Wednesday-END--------->
                
                <div class="[ form-group ]"> <!--  -----------Thursday---------->
                  <input type="checkbox" name="fancy-checkbox-info" id="fancy-checkbox-info" autocomplete="off" />
                  <div class="[ btn-group ]">
                    <label for="fancy-checkbox-info" class="[ btn btn-info circle-dr ]"> <span class="[ glyphicon glyphicon-ok ]"></span> <span> </span> </label>
                    <label for="fancy-checkbox-info" class="[active day-dr ]"> Monday </label>
                    <label for="" class="[active ]">
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
                    <label for="" class="[active ]">
                      <select name="minutes" class="form-control drop-select select-menu2" data-parsley-id="7110" >
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
                    <label for="" class="[active ]">
                      <select name="ampm" class="form-control drop-select select-menu3" data-parsley-id="6520">
                        <option value="am" selected="'selected'">am</option>
                        <option value="pm">pm</option>
                      </select>
                    </label>
                    <span style="padding: 0px 8px;">to</span>
                    <label for="" class="[active ]">
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
                    <label for="" class="[active ]">
                      <select name="minutes" class="form-control drop-select select-menu2" data-parsley-id="7110" >
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
                    <label for="" class="[active ]">
                      <select name="ampm" class="form-control drop-select select-menu3" data-parsley-id="6520">
                        <option value="am" selected="'selected'">am</option>
                        <option value="pm">pm</option>
                      </select>
                    </label>
                  </div>
                </div>
                <!--  -----------Thursday-END--------->
                <div class="[ form-group ]"> <!--  ----------Friday---------->
                  <input type="checkbox" name="fancy-checkbox-info" id="fancy-checkbox-info" autocomplete="off" />
                  <div class="[ btn-group ]">
                    <label for="fancy-checkbox-info" class="[ btn gray-dr circle-dr ]"> <span class="[ glyphicon glyphicon-ok ]"></span> <span> </span> </label>
                    <label for="fancy-checkbox-info" class="[active day-dr ]"> Monday </label>
                    <label for="" class="[active ]">
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
                    <label for="" class="[active ]">
                      <select name="minutes" class="form-control drop-select select-menu2" data-parsley-id="7110" >
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
                    <label for="" class="[active ]">
                      <select name="ampm" class="form-control drop-select select-menu3" data-parsley-id="6520">
                        <option value="am" selected="'selected'">am</option>
                        <option value="pm">pm</option>
                      </select>
                    </label>
                    <span style="padding: 0px 8px;">to</span>
                    <label for="" class="[active ]">
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
                    <label for="" class="[active ]">
                      <select name="minutes" class="form-control drop-select select-menu2" data-parsley-id="7110" >
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
                    <label for="" class="[active ]">
                      <select name="ampm" class="form-control drop-select select-menu3" data-parsley-id="6520">
                        <option value="am" selected="'selected'">am</option>
                        <option value="pm">pm</option>
                      </select>
                    </label>
                  </div>
                </div>
                <!---------------Friday--END-------------->
                <div class="[ form-group ]"> <!--  -----------Saturday---------->
                  <input type="checkbox" name="fancy-checkbox-info" id="fancy-checkbox-info" autocomplete="off" />
                  <div class="[ btn-group ]">
                    <label for="fancy-checkbox-info" class="[ btn gray-dr circle-dr ]"> <span class="[ glyphicon glyphicon-ok ]"></span> <span> </span> </label>
                    <label for="fancy-checkbox-info" class="[active day-dr ]"> Monday </label>
                    <label for="" class="[active ]">
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
                    <label for="" class="[active ]">
                      <select name="minutes" class="form-control drop-select select-menu2" data-parsley-id="7110" >
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
                    <label for="" class="[active ]">
                      <select name="ampm" class="form-control drop-select select-menu3" data-parsley-id="6520">
                        <option value="am" selected="'selected'">am</option>
                        <option value="pm">pm</option>
                      </select>
                    </label>
                    <span style="padding: 0px 8px;">to</span>
                    <label for="" class="[active ]">
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
                    <label for="" class="[active ]">
                      <select name="minutes" class="form-control drop-select select-menu2" data-parsley-id="7110" >
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
                    <label for="" class="[active ]">
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
          <!-- -->
          <div class="clearfix"></div>
        </div>
      	<div class="panel dr-panel panel-body">
        	<button type="button" class="btn btn-default padding-dr">Cancel</button>
        	<button type="button" class="btn btn-primary padding-dr">Save</button>
      	</div>
    </form>
  </div>
  </div>
</div>
