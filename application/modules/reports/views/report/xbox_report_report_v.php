<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!-- start: BREADCRUMB -->

<div class="container-fluid container-fullw">
    <div class="panel panel-white" id="report_panel">
        <div class="panel-heading border-light">
            
            <div class="row">
            	<div class="col-sm-12 ajax-response"></div>
                <div class="col-md-2">
                    <div class="input-group">
                    	<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                        <input type="text" class="form-control datepicker" placeholder="dd/mm/yyyy" name="dispatch_date" autocomplete="off" data-toggle="tooltip" data-title="Dispatch Date">
                    </div>
                </div>
                <div class="col-md-2">
                    <select class="form-control dr_number" name="dr_number" data-toggle="tooltip" data-title="DR Number"></select>
                </div>
                <div class="col-md-2">
                    <select name="market_id" class="form-control" data-toggle="tooltip" data-title="Depot">
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
                    <button type="button" class="btn btn-primary find_report" data-url="<?=$ajax_url?>"> <i class="fa fa-search"></i> Find</button>
                    <button type="button" class="btn btn-warning" id="export_data" data-toggle="tooltip" data-title="Download Excel" data-target="#<?=$table_class?>" data-filename="<?=$page_title?>"> <i class="fa fa-file-excel-o"></i> Excel</button>
                    <a id="dlink" style="display:none;"></a>
                    <button type="button" class="btn btn-success print_data" data-target="#<?=$table_class?>"> <i class="fa fa-print"></i> Print</button>                    
                </div>
                <div class="col-md-2">
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
                                <th colspan="10" style="text-align:center; font-size: 20px"><?=$page_title?></th>
                            </tr>
                            <tr>
                                <th colspan="10" style="text-align:center; font-size: 20px"><span id="search_key"></span></th>
                            </tr>                            
                        </thead>
                    </table>
                </div>
                <div class="col-sm-12 panel-scroll height-300" style="width:100%;">
                    <table class="table table-striped report_table_body">
                        <tbody>
												
							<tr>
							<td id="report_data"></td>
							</tr>
                                	
						</tbody>
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
        <div class="panel-footer border-light"> </div>
    </div>
</div>
