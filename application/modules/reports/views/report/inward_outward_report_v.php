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
                        <input type="text" class="form-control datepicker" placeholder="dd/mm/yyyy" name="closing_date" autocomplete="off" data-toggle="tooltip" data-title="Production Date">
                    </div>
                </div>
                <div class="col-md-2">
                    <select class="form-control" name="market_id" data-toggle="tooltip" data-title="Production Depot">
                      <option value="">Select Depot</option>
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
                <div class="col-md-4">
                    <div class="input-group">
                        <button type="button" class="btn btn-primary find_report" data-url="<?=$ajax_url?>"> <i class="fa fa-search"></i> Find</button>&nbsp;
                        <button type="button" class="btn btn-warning" id="export_data" data-toggle="tooltip" data-title="Download Excel" data-target="#<?=$table_class?>" data-filename="<?=$page_title?>"> <i class="fa fa-file-excel-o"></i> Excel</button>&nbsp;
                        <a id="dlink" style="display:none;"></a>
                        <button type="button" class="btn btn-success print_data" data-target="#<?=$table_class?>"> <i class="fa fa-print"></i> Print</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-body">
        	<div class="row" id="<?=$table_class?>">
                <div class="col-sm-12">
                    <table class="table table-striped table-bordered report_table_body">
                    	<thead>
                            <tr>
                                <th colspan="3" style="text-align:center; font-size: 20px"><?=$page_title?> : <span id="search_date"></span></th>
                            </tr>
                        </thead>
                        <tbody id="report_data"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
