<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!-- start: BREADCRUMB -->

<div class="container-fluid container-fullw">
    <div class="panel panel-white" id="report_panel">
        <div class="panel-heading border-light">
            <div class="row">
            	<div class="col-sm-12 ajax-response"></div>
                <div class="col-md-2">
                    <label class="control-label">Depot <span class="symbol required"></span></label>
                    <select class="form-control market_id" name="market_id">
                      <option value="">Select Depot</option>
                      <?php
                        if(!empty($all_depot)){
                            foreach($all_depot as $row){
                                $op_val = $row['ID'];
                                $op_text = $row['name'];
                        ?>
                      <option value="<?=$op_val?>"><?=$op_text?></option>
                      <?php }} ?>
                    </select>
              	</div>
                <div class="col-md-2">
                    <label>Dispatch Date <span class="symbol required"></span></label>
                    <div class="input-group">
                    	<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                        <input type="text" class="form-control dispatch_date datepicker" placeholder="dd/mm/yyyy" name="dispatch_date" autocomplete="off">
                    </div>
                </div>
                <div class="col-md-3">
                    <label>Vehicle No. <span class="symbol required"></span></label>
                    <select class="form-control" id="vehicle_number" name="vehicle_number">
                    	<option value="">Select Vehicle</option>
                    </select>
                </div>
                
                <div class="col-md-4">
                    <label>&nbsp; </label>
                    <div>
                    <button type="button" class="btn btn-primary find_report" data-url="<?=$ajax_url?>"> <i class="fa fa-search"></i> Find</button>
                    <button type="button" class="btn btn-warning export_data" data-toggle="tooltip" data-title="Download Excel" data-target="#<?=$table_class?>" data-filename="<?=$page_title?>"> <i class="fa fa-file-excel-o"></i> Excel</button>
                    <a id="dlink" style="display:none;"></a>
                    <button type="button" class="btn btn-success print_data" data-target="#<?=$table_class?>"> <i class="fa fa-print"></i> Print</button>                    </div>                    
                </div>
            </div>
        </div>
        <div class="panel-body">
            <div class="row" id="<?=$table_class?>">
                <div class="col-sm-12">
                <table class="table report_table_header">
                	<thead>
                        <tr>
                            <th colspan="5" style="text-align:center; font-size: 20px"><?=$page_title?></th>
                        </tr>
                        <tr>
                            <th colspan="2" style="text-align:left; font-size: 20px">Depot : <span id="search_key"></span></th>
                            <th colspan="3" style="text-align:right; font-size: 20px">Date : <span id="search_date"></span></th>
                        </tr>
                    </thead>
                </table>
                </div>
                <div class="col-sm-12 <?php /*?>panel-scroll height-300<?php */?>" style="width:100%;">
                    <table class="table table-striped report_table_body">
                        <tbody id="report_data"></tbody>
                    </table>
                </div>
                <div class="col-sm-12">
                <table class="table report_table_footer">
                    <tfoot>
                        <tr id="report_summary"></tr>
                    </tfoot>
                </table>
                </div>
            </div>
        </div>
    </div>
</div>
