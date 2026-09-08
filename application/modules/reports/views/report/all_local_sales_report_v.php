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
                        <input type="text" class="form-control datepicker" placeholder="dd/mm/yyyy" name="to_date" autocomplete="off" data-toggle="tooltip" data-title="To Date" />
                    </div>
                </div>
                <div class="col-md-2 no-padding">
                    <select name="market_category" class="form-control market_category" data-toggle="tooltip" data-title="Market Category">
                        <option value="0">All</option>
                        <?php
                        if(!empty($market_category)){
                            foreach($market_category as $row){
                                $op_val = $row['ID'];
                                $op_text = $row['name'];
                        ?>
                        <option value="<?=$op_val?>"><?=$op_text?></option>
                        <?php }} ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="market_id" class="form-control market_place"  data-toggle="tooltip" data-title="Market Place">
                        <option value="0">All</option>
                        <?php
                        if(!empty($market_places)){
                            foreach($market_places as $row){
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
                <div class="col-md-1 no-padding" style="margin-left:-25px;">
                	<span class="display-table-cell vertical-align-middle padding-left-10">Expand Report</span>
                	<div class="display-table-cell">
                        <input type="checkbox"  id="expand_report_container"  class="js-switch" data-target="#<?=$table_class?>" />
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-body">
            <div class="row" id="<?=$table_class?>">
                <div class="col-sm-12">
                    <table class="table report_table_header" style="width:1260px !important;">
                        <thead>
                            <tr>
                                <th colspan="9" style="text-align:center; font-size: 20px"><?=$page_title;?></th>
                            </tr>
                            <tr>
                                <th colspan="5" style="text-align:center; font-size: 20px">Date: <span id="search_date"></span></th>
                                <th colspan="4" style="text-align:center; font-size: 20px">Market : <span id="search_key"></span></th>
                            </tr>
                            <tr class="report_header">
                                <th>Date</th>
                                <th>Party Name</th>
                                <th>Mode</th>
                                <th>Point</th>
                                <th>Rec. No.</th>
                                <th>Remark</th>                                
                                <th>Grand Total</th>
                                <th>Cash Rec.</th>
                                <th>Balance</th>
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
                                <th>Date</th>
                                <th>Party Name</th>
                                <th>Mode</th>
                                <th>Point</th>
                                <th>Rec. No.</th>
                                <th>Remark</th>                                
                                <th>Grand Total</th>
                                <th>Cash Rec.</th>
                                <th>Balance</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
