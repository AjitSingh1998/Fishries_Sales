<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!-- start: BREADCRUMB -->

<div class="container-fluid container-fullw">
    <div class="panel panel-white" id="report_panel">
        <div class="panel-heading border-light">
            <h3><?=$page_title?></h3>
            <div class="row">
            	<div class="col-sm-12 ajax-response"></div>
                <div class="col-md-2">
                    <label>Date<span class="symbol required"></span> :</label>
                    <div class="input-group">
                    	<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                        <input type="text" class="form-control datepicker" placeholder="dd/mm/yyyy" name="production_date" autocomplete="off">
                    </div>
                </div>
                <div class="col-md-2">
                    <label>Market Category  :</label>
                    <div class="input-group">
                    	<select name="market_id" class="form-control">
                        	<option value="">All Point</option>
                            <?php
							if(!empty($all_points)){
								foreach($all_points as $row){
									$op_val = $row['ID'];
									$op_text = $row['name'];
							?>
							<option value="<?=$op_val?>"><?=$op_text?></option>
							<?php }} ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <label>&nbsp; </label>
                    <div class="input-group">
                        <button type="button" class="btn btn-primary find_report" data-url="<?=$ajax_url?>"> <i class="fa fa-search"></i> Find</button>&nbsp;
                        <button type="button" class="btn btn-warning export_data" data-toggle="tooltip" data-title="Download Excel" data-target="<?=$table_class?>" data-filename="<?=$page_title?>"> <i class="fa fa-file-excel-o"></i> Excel</button>&nbsp;
                        <a id="dlink" style="display:none;"></a>
                        <button type="button" class="btn btn-success print_data"> <i class="fa fa-print"></i> Print</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-body">
            <table class="table table-striped table-bordered" id="<?=$table_class?>">
                <thead>
                    <tr>
                        <th colspan="15" style="text-align:center; font-size: 20px">Production Detail : <span id="search_date"></span></th>
                    </tr>
                    <tr>
                    	<th><span data-toggle="tooltip" data-title="Point Name">Point</span></th>
                        <th><span data-toggle="tooltip" data-title="Point Weight">Point Wt</span></th>
                        <th><span data-toggle="tooltip" data-title="Forfeiture (Size & Dabish Weight)">Forf Wt</span></th>
                        <th><span data-toggle="tooltip" data-title="Forfeiture Rotten Weight">Forf Rotten Wt</span></th>
                        <th><span data-toggle="tooltip" data-title="Total Point Weight">Total Point Wt</span></th>
                        <th><span data-toggle="tooltip" data-title="Destroyed Sale">Destroyed</span></th>
                        <th><span data-toggle="tooltip" data-title="Rotten Sale">Rotten</span></th>
                        <th><span data-toggle="tooltip" data-title="Depot Weight">Depot Wt</span></th>
                        <th><span data-toggle="tooltip" data-title="Sale">Sale</span></th>
                        <th><span data-toggle="tooltip" data-title="Free Sale">Free Sale</span></th>
                        <th><span data-toggle="tooltip" data-title="Total Weight">Total W</span>t</th>
                        <th><span data-toggle="tooltip" data-title="Surplus">Surplus</span></th>
                        <th><span data-toggle="tooltip" data-title="Surplus Percentage">Surplus %</span></th>
                        <th><span data-toggle="tooltip" data-title="Jhinga Point Weight">Jhinga Point Wt</span></th>
                        <th><span data-toggle="tooltip" data-title="Jhinga Depot Weight">Jhinga Depot Wt</span></th>
                    </tr>
                </thead>
                <tbody id="report_data"></tbody>
                <tbody>
                    <tr id="report_summary"></tr>
                </tbody>
            </table>
        </div>
        <div class="panel-footer border-light"> </div>
    </div>
</div>
