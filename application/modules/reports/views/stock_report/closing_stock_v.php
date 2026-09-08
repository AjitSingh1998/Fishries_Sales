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
                        <input type="text" class="form-control datepicker" placeholder="dd/mm/yyyy" name="closing_date" autocomplete="off">
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
                <div class="col-md-4">                
                    <button type="button" class="btn btn-primary find_report" data-url="<?=$ajax_url?>"> <i class="fa fa-search"></i> Find</button>
                    <button type="button" class="btn btn-warning" id="export_data" data-toggle="tooltip" data-title="Download Excel" data-target="#<?=$table_class?>" data-filename="<?=$page_title?>"> <i class="fa fa-file-excel-o"></i> Excel</button>
                    <a id="dlink" style="display:none;"></a>
                    <button type="button" class="btn btn-success print_data" data-target="#<?=$table_class?>"> <i class="fa fa-print"></i> Print</button>
                </div>
                <div class="col-md-2">
                	<span class="display-table-cell vertical-align-middle padding-left-10">Show All Data &nbsp;</span>
                	<div class="display-table-cell">
                        <input type="checkbox" id="show_all_data"  class="js-switch" data-target="#<?=$table_class?>" />
                    </div>
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
        	<div class="table-responsive">
                <div class="row" id="<?=$table_class?>" style="width:1600px;">
                    <div class="col-sm-12">
                    <table class="table report_table_header">
                        <thead>
                            <tr>
                                <th colspan="30" style="text-align:center; font-size: 20px"><?=$page_title?> : <span id="search_date"></span></th>
                            </tr>                            
                            <tr class="report_header">
                                <th>S.No.</th>                        
                                <!--<th>Type</th>
                                <th>Fish Name</th>-->
                                <th><span data-toggle="tooltip" data-title="Fish Code">Code</span></th>
                                
                                <th><span data-toggle="tooltip" data-title="Production Quantity">P.Qty</span></th>
                                <th class="success"><span data-toggle="tooltip" data-title="Production Weight">P.WT</span></th>
                                
                                <th><span data-toggle="tooltip" data-title="Transfer IN Quantity">TI.Qty</span></th>
                                <th class="success"><span data-toggle="tooltip" data-title="Transfer IN Weight">TI.WT</span></th>
                                
                                <th><span data-toggle="tooltip" data-title="Transfer Out Quantity">TO.Qty</span></th>
                                <th class="danger"><span data-toggle="tooltip" data-title="Transfer Out Weight">TO.WT</span></th>
                                
                                <th><span data-toggle="tooltip" data-title="Bachat Quantity">B.Qty</span></th>
                                <th><span data-toggle="tooltip" data-title="Bachat Weight">B.WT</span></th>
                                <th><span data-toggle="tooltip" data-title="Bachat Sorting Weight">BS.WT</span></th>
                                <th class="success"><span data-toggle="tooltip" data-title="Bachat Shortage Weight">BS.WT</span></th>
                                
                                <th><span data-toggle="tooltip" data-title="Opening Quantity">O.Qty</span></th>
                                <th class="success"><span data-toggle="tooltip" data-title="Opening Weight">O.WT</span></th>
                                <th><span data-toggle="tooltip" data-title="Opening Sorting Weight">OS.WT</span></th>
                                <th class="success"><span data-toggle="tooltip" data-title="Opening Shortage Weight">OS.WT</span></th>
                                
								<th><span data-toggle="tooltip" data-title="Opening Box Quantity">OB.QTY</span></th>
                                <th class="success"><span data-toggle="tooltip" data-title="Opening Box Weight">OB.WT</span></th>
								
                                <th><span data-toggle="tooltip" data-title="Paid Sale Quantity">PS.Qty</span></th>
                                <th class="danger"><span data-toggle="tooltip" data-title="Paid Sale Weight">PS.WT</span></th>
                                
                                <th><span data-toggle="tooltip" data-title="Free Sale Quantity">FS.Qty</span></th>
                                <th class="danger"><span data-toggle="tooltip" data-title="Free Sale Weight">FS.WT</span></th>
                                
                                <th><span data-toggle="tooltip" data-title="Dispatched Quantity">D.Qty</span></th>
                                <th class="danger"><span data-toggle="tooltip" data-title="Dispatched Weight">D.WT</span></th>
                                
                                <th><span data-toggle="tooltip" data-title="Prepared Box Quantity">PB.Qty</span></th>
                                <th class="danger"><span data-toggle="tooltip" data-title="Prepared Box Weight">PB.WT</span></th>
                                
								<th><span data-toggle="tooltip" data-title="Destroyed Fish Quantity">DF.Qty</span></th>
                                <th class="danger"><span data-toggle="tooltip" data-title="Destroyed Fish Weight">DF.WT</span></th>
								
                                <th><span data-toggle="tooltip" data-title="Closing Stock Quantity">C.Qty</span></th>
                                <th><span data-toggle="tooltip" data-title="Closing Stock Weight">C.WT</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                            	<td colspan="30">
                                	<div class="panel-scroll height-300">
                                        <table class="table table-striped report_table_body">
                                            <tbody id="report_data"></tbody>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr id="report_summary"></tr>
                            <tr class="report_footer">
                                <th>S.No.</th>                        
                                <!--<th>Type</th>
                                <th>Fish Name</th>-->
                                <th><span data-toggle="tooltip" data-title="Fish Code">Code</span></th>
                                
                                <th><span data-toggle="tooltip" data-title="Production Quantity">P.Qty</span></th>
                                <th class="success"><span data-toggle="tooltip" data-title="Production Weight">P.WT</span></th>
                                
                                <th><span data-toggle="tooltip" data-title="Transfer IN Quantity">TI.Qty</span></th>
                                <th class="success"><span data-toggle="tooltip" data-title="Transfer IN Weight">TI.WT</span></th>
                                
                                <th><span data-toggle="tooltip" data-title="Transfer Out Quantity">TO.Qty</span></th>
                                <th class="danger"><span data-toggle="tooltip" data-title="Transfer Out Weight">TO.WT</span></th>
                                
                                <th><span data-toggle="tooltip" data-title="Bachat Quantity">B.Qty</span></th>
                                <th><span data-toggle="tooltip" data-title="Bachat Weight">B.WT</span></th>
                                <th><span data-toggle="tooltip" data-title="Bachat Sorting Weight">BS.WT</span></th>
                                <th class="success"><span data-toggle="tooltip" data-title="Bachat Shortage Weight">BS.WT</span></th>
                                
                                <th><span data-toggle="tooltip" data-title="Opening Quantity">O.Qty</span></th>
                                <th class="success"><span data-toggle="tooltip" data-title="Opening Weight">O.WT</span></th>
                                <th><span data-toggle="tooltip" data-title="Opening Sorting Weight">OS.WT</span></th>
                                <th class="success"><span data-toggle="tooltip" data-title="Opening Shortage Weight">OS.WT</span></th>
                                
								<th><span data-toggle="tooltip" data-title="Opening Box Quantity">OB.QTY</span></th>
                                <th class="success"><span data-toggle="tooltip" data-title="Opening Box Weight">OB.WT</span></th>
								
                                <th><span data-toggle="tooltip" data-title="Paid Sale Quantity">PS.Qty</span></th>
                                <th class="danger"><span data-toggle="tooltip" data-title="Paid Sale Weight">PS.WT</span></th>
                                
                                <th><span data-toggle="tooltip" data-title="Free Sale Quantity">FS.Qty</span></th>
                                <th class="danger"><span data-toggle="tooltip" data-title="Free Sale Weight">FS.WT</span></th>
                                
                                <th><span data-toggle="tooltip" data-title="Dispatched Quantity">D.Qty</span></th>
                                <th class="danger"><span data-toggle="tooltip" data-title="Dispatched Weight">D.WT</span></th>
                                
                                <th><span data-toggle="tooltip" data-title="Prepared Box Quantity">PB.Qty</span></th>
                                <th class="danger"><span data-toggle="tooltip" data-title="Prepared Box Weight">PB.WT</span></th>
                                
								<th><span data-toggle="tooltip" data-title="Destroyed Fish Quantity">DF.Qty</span></th>
                                <th class="danger"><span data-toggle="tooltip" data-title="Destroyed Fish Weight">DF.WT</span></th>
								
                                <th><span data-toggle="tooltip" data-title="Closing Stock Quantity">C.Qty</span></th>
                                <th><span data-toggle="tooltip" data-title="Closing Stock Weight">C.WT</span></th>
                            </tr>
                        </tfoot>
                    </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
