<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!-- start: BREADCRUMB -->

<div class="container-fluid container-fullw">
    <div class="panel panel-white" id="report_panel">
        <div class="panel-heading border-light">
            <h3><?=$page_title?></h3>
            <div class="row">
            	<div class="col-sm-12 ajax-response"></div>
                <div class="col-md-2">
                    <input type="text" class="form-control datepicker" placeholder="dd/mm/yyyy" name="from_date" autocomplete="off" data-toggle="tooltip" data-title="From Date">
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control datepicker" placeholder="dd/mm/yyyy" name="to_date" autocomplete="off" data-toggle="tooltip" data-title="To Date">
                </div>
                <div class="col-md-3">
                    <select name="market_id" class="form-control">
                        <option value="">All</option>
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
                    <button type="button" class="btn btn-primary find_report" data-url="<?=$ajax_url?>"> <i class="fa fa-search"></i> Find</button>&nbsp;
                    <button type="button" class="btn btn-warning export_data" data-toggle="tooltip" data-title="Download Excel" data-target="<?=$table_class?>" data-filename="<?=$page_title?>"> <i class="fa fa-file-excel-o"></i> Excel</button>&nbsp;
                    <a id="dlink" style="display:none;"></a>
                    <button type="button" class="btn btn-success print_data"> <i class="fa fa-print"></i> Print</button>
                </div>
            </div>
        </div>
        <div class="panel-body">
            <table class="table table-striped table-bordered" id="<?=$table_class?>">
                <thead>
                    <tr>
                        <th colspan="6" style="text-align:center; font-size: 20px"><span id="search_date"></span></th>
                        <th colspan="5" style="text-align:center; font-size: 20px"><span id="search_key"></span></th>
                    </tr>
                    <tr>
                    	<th>Dispatch Date</th>
                        <th>Veh. No.</th>
                        <th>Dispatch Qty.</th>
                        <th>DR. No.</th>
                        <th>Total Box</th>
                        <th>Place</th>
                        <th>Sale Date</th>
                        <th>Party Name</th>
                        <th>Gross Wt.</th>
                        <th>Net Wt.</th>
                        <th>Boxes Sold</th>
                    </tr>
                </thead>
                <tbody id="report_data"></tbody>
                <tbody id="report_summary"></tbody>
            </table>
        </div>
        <div class="panel-footer border-light"> </div>
    </div>
</div>
