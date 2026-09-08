<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!-- start: BREADCRUMB -->

<div class="container-fluid container-fullw">
    <div class="panel panel-white" id="report_panel">
        <div class="panel-heading border-light">
            <div class="row">
            	<div class="col-sm-12 ajax-response"></div>
                <?php /*?>
                <div class="col-sm-2">
                    <div class="input-group">
                    	<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                        <input type="text" class="form-control datepicker" placeholder="dd/mm/yyyy" name="dispatch_date" autocomplete="off" data-toggle="tooltip" data-title="Dispatch Date">
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="input-group">
                    	<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                        <input type="text" class="form-control datepicker" placeholder="dd/mm/yyyy" name="sale_date" autocomplete="off" data-toggle="tooltip" data-title="Sale Date">
                    </div>
                </div>
                <?php */?>
                <div class="col-sm-2">
                    <select class="form-control dr_number" name="dr_number" data-toggle="tooltip" data-title="DR Number"></select>
                </div>
                <div class="col-sm-2">
                    <select name="market_id" class="form-control market_place" data-toggle="tooltip" data-title="Market Places" disabled="disabled">
                        <option value="">Select Market</option>
                    </select>
                </div>
                <div class="col-sm-2">
                    <select name="client_id" class="form-control clients" data-toggle="tooltip" data-title="Select Customer" disabled="disabled">
                        <option value="">Select Customers</option>
                    </select>
                </div>
                <div class="col-sm-3">
                    <button type="button" class="btn btn-primary find_report" data-url="<?=$ajax_url?>"> <i class="fa fa-search"></i> Find</button>&nbsp;
                    <button type="button" class="btn btn-warning export_data" data-toggle="tooltip" data-title="Download Excel" data-target="#<?=$table_class?>" data-filename="<?=$page_title?>"> <i class="fa fa-file-excel-o"></i> Excel</button>&nbsp;
                    <a id="dlink" style="display:none;"></a>
                    <button type="button" class="btn btn-success print_data"> <i class="fa fa-print"></i> Print</button>
                </div>
                <div class="col-sm-2">
                	<span class="display-table-cell vertical-align-middle padding-left-10">Expand Report &nbsp;</span>
                	<div class="display-table-cell">
                        <input type="checkbox"  id="expand_report_container"  class="js-switch" data-target="#<?=$table_class?>" />
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-body">
            <table class="table table-striped table-bordered" id="<?=$table_class?>">
                <thead>
                    <tr>
                    	<th colspan="15" style="text-align:center; font-size: 20px"><?=$page_title?></th>
                    </tr>
                    <tr>
                        <th colspan="15" style="text-align:center; font-size: 20px"><span id="search_key"></span></th>
                    </tr>
                    <tr>
                        <th colspan="8" style="text-align:center; font-size: 20px">Comparison Report (PLACE) - <span id="search_date"></span></th>
                        <th colspan="7" style="text-align:center; font-size: 20px">Party (All/single party )</th>
                    </tr>
                    <tr>
                    	<th class="danger">S.No.</th>
                        <th class="danger">B.No.</th>
                        <th class="danger">F.C.</th>
                        <th class="danger">Qty.</th>
                        <th class="danger">Wt.</th>
                        <th class="danger">N. Wt.</th>
                        <th class="danger">Rate</th>
                        <th class="danger">Amount</th>
                        <th class="success">Bill Wt.</th>
                        <th class="success">Bill Rate</th>
                        <th class="success">Amount</th>
                        <th class="success">Party</th>
                        <th class="info">MQW</th>
                        <th class="info">Realization Cost</th>
                        <th class="info">MPV</th>
                    </tr>
                </thead>
                <tbody id="report_data"></tbody>
                <tbody id="report_summary"></tbody>
            </table>
        </div>
        <div class="panel-footer border-light"> </div>
    </div>
</div>
