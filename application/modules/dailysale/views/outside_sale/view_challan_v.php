<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- start: BREADCRUMB -->
<?php $company_name = 'SIMRAN FISHERIES PVT. LTD.';?>
<div class="container-fluid container-fullw">
  <div class="row">
    <div class="col-sm-12">
        <div class="panel panel-white" id="panel_container">            
            <div class="panel-body" id="panel-body-container">
            	<div class="col-sm-12 text-center margin-bottom-10">
                	<a href="<?=$go_back_url?>" class="btn btn-primary" data-toggle="tooltip" data-title="Go Back"><i class="fa fa-arrow-circle-left"></i></a>
                    <!--<button type="button" class="btn btn-warning" id="export_data" data-toggle="tooltip" data-title="Download Excel" data-target="#table_table" data-filename="Closing Stock Report"> <i class="fa fa-file-excel-o"></i> Excel</button>&nbsp;-->
                    <a id="dlink" style="display:none;"></a>
                    <button type="button" class="btn btn-success print_data" data-target="#challan_print_preview"> <i class="fa fa-print"></i> Print</button>

                </div>
            	<table class="table table-striped table-bordered" id="challan_print_preview">
                	<tr>
                    	<th colspan="3" class="text-center"><h3><?=COMPANY_NAME?></h3></th>
                    </tr>
                    <tr>
                    	<th colspan="3" class="text-center"><h4>FISH CONTRACTOR <br> <small>Whole Sale Fish Market</small></h4></th>
                    </tr>
                    <tr>
                    	<th colspan="3" class="text-center">
                        	<h4><?=$sale_data['company_name']?> <br> <small><?=$sale_data['contact_number']?><br><?=$sale_data['client_address']?></small></h4>
                        </th>
                    </tr>
                    <tr>
                    	<th style="text-align:center;width:33%;">
                        	<table style="border:0px;">
                            	<tr>
                                	<td style="padding:10px; vertical-align:middle;">TRADE</td>
                                    <td>
                                    	<h4 style="border-bottom:1px dotted #333333;margin-bottom:0px;"><?=$sale_data['trade_mark']?></h4>
                                    	<h4><?=$sale_data['market_name']?></h4>
                                    </td>
                                    <td style="padding:10px; vertical-align:middle;">MARK</td>
                                </tr>
                            </table>
                        </th>
                    	<th style="width:33%;"><div style="border-bottom:1px dotted #333333;width:100%;">R.R: <?=$sale_data['remark']?></div></th>
                        <th style="width:33%;"><div style="border-bottom:1px dotted #333333;width:100%;">Date: <?=get_datetime('d/m/Y h:i a', $sale_data['sale_date'])?></div></th>
                    </tr>
                    <tr>
                    	<th colspan="3"><div style="border-bottom:1px dotted #333333;width:100%;">Messers: <?=$company_name;?></div></th>
                    </tr>
                    <tr>
                    	<th><div style="border-bottom:1px dotted #333333;width:100%;">Truck: <?=$sale_data['vehicle_number']?></div></th>
                        <th><div style="border-bottom:1px dotted #333333;width:100%;">Box: <?=count($all_boxes);?></div></th>
                        <th><div style="border-bottom:1px dotted #333333;width:100%;">DR. No.: <?=$sale_data['dr_number']?></div></th>
                    </tr>
                	<tr>
                    	<td  colspan="2" class="no-padding" style="vertical-align:top;">
                        	<table class="table table-striped table-bordered no-margin">
                                <thead>
                                    <tr>
                                        <th>S.No.</th>
                                        <th>Box</th>
                                        <th>Fish</th>
                                        <th>Qty</th>                                        
                                        <th>Gross WT</th>
                                        <th>Net WT</th>
                                        <th>Rate</th>
                                        <th>Amount</th>
                                    </tr>                    
                                </thead>
                                <tbody>
                                <?php
								 $sr_no = 0;
                                $t_box_qty = $t_disp_wt = $t_gross_wt =  $t_net_wt =  $t_amount = 0;
                                if(isset($all_boxes) && !empty($all_boxes))
                                {                                 
                                  foreach($all_boxes as $box_number => $box)//Main For Loop For each Box
                                  {
                                    $checked = '';
                                    $checked_class = 'box_unchecked';
                                    $box_quantity = $box_weight = 0;
                                    $items_tr = '';
                                    foreach($box as $item_data) //Inner For Loop For items
                                    {
                                        $sr_no++; 
                                        $checked 		= !empty($item_data['sale_item_id']) ? 'checked="checked"' : '';
                                        $checked_class 	= !empty($item_data['sale_item_id']) ? 'box_checked info' : 'box_unchecked';
                                        $box_quantity 	+= $item_data['fish_qty'];
                                        $box_weight 	+= $item_data['box_wt'];
                                        $t_box_qty		+= $item_data['fish_qty'];
                                        $t_disp_wt 		+= $item_data['box_wt'];
                                        $t_gross_wt 	+= $item_data['gross_wt'];
                                        $t_net_wt 		+= $item_data['net_wt'];
                                        $t_amount 		+= $item_data['total_amount'];
                                ?>
                                 <tr class="box_item_row">
                                    <td><?=$sr_no;?></td>
                                    <td><?=$box_number;?></td>
                                    <td><?=$item_data['fish_code'].' ('.$item_data['fish_name'];?>)</td>
                                    <td><?=$item_data['fish_qty'];?></td>
                                    <td><?=my_number_format($item_data['gross_wt']);?></td>
                                    <td><?=my_number_format($item_data['net_wt']);?></td>
                                    <td><?=$item_data['fish_rate'];?></td>
                                    <td><?=$item_data['total_amount'];?></td>
                                 </tr>
                                <?php } // Box items foreach?>                        
                                <?php                        	
                                  }//Box foreach Loop
                                } else{
                                ?>
                                <tr>    
                                    <th class="text-center" colspan="8"><div class="alert alert-danger">Sorry, No Record Found</div></th>    
                                </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </td>
                        <td class="no-padding" style="vertical-align:top;">
                        	<table class="table table-striped table-bordered no-margin">
                                <thead>
                                  <tr>
                                    <th colspan="3" style="text-align:center;">Detail of Expenditure</th>
                                  </tr>
                                </thead>
                                <tbody class="tbody_items">
                                  <?php
								  $total_expences = 0;
                                  if(!empty($all_expenditure)){
                                      foreach($all_expenditure as $expenditure){
										 $total_expences += $expenditure['amount'];
                                  ?>
                                  <tr class="expenditure_item_tr">
                                    <td><?=$expenditure['particular']?></td>
                                    <td><?=$expenditure['amount']?></td>
                                  </tr>
                                  <?php }} 
								  
								  $gross_sale = number_format($t_amount,2 ,'.', '');
								  $net_sale = number_format(($t_amount-$total_expences),2,'.', '');
								  $grand_total = number_format(($net_sale + $prev_balance),2 ,'.', '');
								  ?>
                                  <tr>
                                  	<th>Gross Sale</th>
                                    <th><?=$gross_sale?></th>
                                  </tr> 
                                  <tr>
                                  	<th>Total Expenses</th>
                                    <th><?=$total_expences?></th>                                  
                                  </tr> 
                                  <tr>
                                  	<th>Net Sale</th>
                                    <th><?=$net_sale?></th>
                                  </tr> 
                                  <tr>
                                  	<th>Prev Balance</th>
                 					<th><?=$prev_balance?></th>                                 
                                  </tr> 
                                  <tr>
                                  	<th>Grand Total</th>
                                    <th colspan="2"><?=$grand_total?></th>                                 
                                  </tr> 
                                  <?php
								  $total_remition = 0;
                                  if(!empty($remition_data)){
                                      foreach($remition_data as $remition){
										 $total_remition += $remition['amount'];
										 $remark 	= $remition['remark'] ? "<br>".$remition['remark'] : '';
                                  ?>
                                  <tr class="remition_item_tr">
                                    <td>Remitted By <?=$remition['remition_by'] .' - '. get_date('d/m/Y', $remition['payment_date']).$remark?></td>
                                    <td><?=$remition['amount']?></td>
                                  </tr>
                                  <?php }}?>
                                  <tr>
                                  	<th>Total Remition</th>
                                    <th><?=$total_remition?></th>
                                  </tr>
                                  <tr>
                                  	<th>Balance</th>
                                    <th><?=($grand_total-$total_remition)?></th>
                                  </tr>
                                  <tr>
                                    <td colspan="2">Detail: &nbsp;&nbsp; <?=$sale_data['detail']?></td>
                                  </tr>
                                  <tr>
                                  	<td colspan="2" style="text-align:center;border-bottom:none;"><br><br>Signature<br><br></td>                                
                                  </tr>                                
                                </tbody>                                
                            </table>
                        </td>
                    </tr>
                    <tr>
                    	<td colspan="2" class="no-padding" style="vertical-align:top;">
                            <table class="table table-bordered no-padding no-margin">
                                <thead>
                                    <tr>
                                        <th>Summary</th>
                                        <th>Box: <?=$sr_no;?></th>
                                        <th></th>
                                        <th>Quantity: <?=$t_box_qty;?></th>
                                        <th><?=my_number_format($t_gross_wt)?></th>
                                        <th><?=number_format($t_net_wt,2 ,'.', '')?></th>
                                        <th></th>
                                        <th><?=number_format($t_amount,2 ,'.', '')?></th>
                                    </tr>                  
                                </thead>
                            </table>
                        </td>
                        <th style="text-align:center;">Detail Of Expenditure</th>
                    </tr>
                </table>               
            </div>
        </div>
    </div>
  </div>
</div>