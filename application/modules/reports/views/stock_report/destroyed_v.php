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
                        <input type="text" class="form-control datepicker" placeholder="From Date" name="from_date" autocomplete="off">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="input-group">
                    	<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                        <input type="text" class="form-control datepicker" placeholder="To Date" name="to_date" autocomplete="off">
                    </div>
                </div>
                <div class="col-md-2">
                	<select name="market_id" class="form-control">
						<?php 
                            if(isset($allDepot) && !empty($allDepot)){
                                foreach($allDepot as $depot){
                                    echo '<option value="'.$depot['ID'].'">'.$depot['name'].'</option>';
                                }
                            }
                        ?>
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
                            <th colspan="7" style="text-align:center; font-size: 20px"><?=$page_title?> : <span id="search_date"></span></th>
                        </tr>
                        <tr class="report_header">
                            <th>S.No.</th> 
                            <th>Point Name</th>                       
                            <th>Type</th>
                            <th>Fish Name</th>
                            <th>Fish Code</th>
                            <th>Quantity</th>
                            <th>Weight</th>
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
                            <th>S.No.</th> 
                            <th>Point Name</th>                       
                            <th>Type</th>
                            <th>Fish Name</th>
                            <th>Fish Code</th>
                            <th>Quantity</th>
                            <th>Weight</th>
                        </tr>
                    </tfoot>
                </table>
                </div>
            </div>
        </div>
    </div>
</div>
