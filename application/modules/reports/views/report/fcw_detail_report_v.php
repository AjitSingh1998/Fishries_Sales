<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!-- start: BREADCRUMB -->

<div class="container-fluid container-fullw">
    <div class="panel panel-white" id="report_panel">
        <div class="panel-heading border-light light-bg">
            <div class="row">
            	<div class="col-sm-12 ajax-response"></div>
                <div class="col-md-2">
                    <div class="input-group">
                    	<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                        <input type="text" class="form-control datepicker" placeholder="dd/mm/yyyy" name="from_date" autocomplete="off" data-toggle="tooltip" data-title="From Date">
                    </div>
                </div>
                
                <div class="col-md-2">
                    <div class="input-group">
                    	<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                        <input type="text" class="form-control datepicker" placeholder="dd/mm/yyyy" name="to_date" autocomplete="off" data-toggle="tooltip" data-title="To Date">
                    </div>
                </div>
                
                <div class="col-md-2">
                    <select class="form-control select2" name="market_id" data-toggle="tooltip" data-title="Select Deopt">
                      <option value="">All</option>
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
                
                <div class="col-md-3">
                    <div class="input-group">
                        <button type="button" class="btn btn-primary find_report" data-url="<?=$ajax_url?>"> <i class="fa fa-search"></i> Find</button>&nbsp;
                        <button type="button" class="btn btn-warning export_data" data-toggle="tooltip" data-title="Download Excel" data-target="#<?=$table_class?>" data-filename="<?=$page_title?>"> <i class="fa fa-file-excel-o"></i> Excel</button>&nbsp;
                        <a id="dlink" style="display:none;"></a>
                        <button type="button" class="btn btn-success print_data" data-target="#<?=$table_class?>"> <i class="fa fa-print"></i> Print</button>
                    </div>
                </div>
                <div class="col-md-3">
                	<span class="display-table-cell vertical-align-middle padding-left-10">Show All Data &nbsp;</span>
                	<div class="display-table-cell">
                        <input type="checkbox" id="show_all_data"  class="js-switch" data-target="#<?=$table_class?>" />
                    </div>
                	<span class="display-table-cell vertical-align-middle padding-left-10">Expand Report &nbsp;</span>
                	<div class="display-table-cell">
                        <input type="checkbox"  id="expand_report_container"  class="js-switch" data-target="#<?=$table_class?>" />
                    </div>
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
                        <tr class="report_header">
                            <th width="20%"><span data-toggle="tooltip" data-title="Box Number">Box No.</span></th>
                            <th width="20%"><span data-toggle="tooltip" data-title="Fish Category">Fish Category</span></th>
                            <th width="20%"><span data-toggle="tooltip" data-title="Fish Quantity">Quantity</span></th>
                            <th width="20%"><span data-toggle="tooltip" data-title="Fish Weight">Weight</span></th>
                            <th width="20%"><span data-toggle="tooltip" data-title="Party">Party</span></th>
                        </tr>
                    </thead>
                </table>
                </div>
                <div class="col-sm-12 panel-scroll height-300" style="width:100%;">
                    <table class="table table-striped report_table_body">
                        <tbody id="report_data"></tbody>
                    </table>
                </div>
                <div class="col-sm-12">
                <table class="table report_table_footer">
                    <tfoot>
                        <tr id="report_summary"></tr>
                        <tr class="report_footer">
                            <th width="20%"><span data-toggle="tooltip" data-title="Box Number">Box No.</span></th>
                            <th width="20%"><span data-toggle="tooltip" data-title="Fish Category">Fish Category</span></th>
                            <th width="20%"><span data-toggle="tooltip" data-title="Fish Quantity">Quantity</span></th>
                            <th width="20%"><span data-toggle="tooltip" data-title="Fish Weight">Weight</span></th>
                            <th width="20%"><span data-toggle="tooltip" data-title="Party">Party</span></th>
                        </tr>
                    </tfoot>
                </table>
                </div>
            </div>            
        </div>
    </div>
</div>
