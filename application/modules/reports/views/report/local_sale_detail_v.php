<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!-- start: BREADCRUMB -->

<?php
$market_name = $invoice_number = $payment_mode = $sale_date = $party_name = '';
$t_fish_quantity = $t_fish_weight = $t_total_amount = $grand_total = $received_amt = $remaining_amt = 0;
$tbody = $tfoot ='';
if(!empty($report_data)){
	$i=0;
	foreach($report_data as $rdata){$i++;
		$remark = ($rdata['remark']) ? ' / '. $rdata['remark'] : '';
		$tbody .='<tr class="record_data_yes">';
		$tbody .='    <td>'.$i.'</td>';
		$tbody .='    <td>'.$rdata['fish_name'].$remark.'</td>';
		$tbody .='    <td>'.$rdata['fish_code'].'</td>';
		$tbody .='    <td>'.$rdata['fish_category_name'].'</td>';
		$tbody .='    <td>'.$rdata['fish_quantity'].'</td>';
		$tbody .='    <td>'.$rdata['fish_weight'].'</td>';
		$tbody .='    <td>'.$rdata['fish_rate'].'</td>';
		$tbody .='    <td>'.$rdata['total_amount'].'</td>';
		$tbody .='</tr>';
							
		$t_fish_quantity 	+= $rdata['fish_quantity'];
		$t_fish_weight 		+= $rdata['fish_weight'];
		$t_total_amount 		+= $rdata['total_amount'];
		
		$market_name = $rdata['point_name'];
		$invoice_number = $rdata['mp_code'].'-'.$rdata['invoice_number'];
		$payment_mode = $rdata['payment_mode'];
		$sale_date = get_date('d/m/Y', $rdata['sale_date']);
		
		$party_name = $rdata['client_name'];
		$party_name .= ($rdata['contact_number'])? ' / '.$rdata['contact_number'] : '';
		$party_name .= ($rdata['contact_number2'])? ' / '.$rdata['contact_number2'] : '';
		
		$ice_weight	= $rdata['ice_weight'];
		$ice_rate	= $rdata['ice_rate'];
		$ice_amount	= $rdata['ice_amount'];
		
		$destroyed_weight	= $rdata['destroyed_weight'];
		$destroyed_rate	= $rdata['destroyed_rate'];
		$destroyed_amount	= $rdata['destroyed_amount'];
		
		$discount_perc	= $rdata['discount_perc'];
		$discount_amount	= $rdata['discount_amount'];
		
		
		$grand_total 	= $rdata['grand_total'];
		$received_amt 	= $rdata['received_amt'];
		$remaining_amt	= ($rdata['grand_total'] - $rdata['received_amt']);
	}
}

?>
<table class="table table-striped table-bordered" id="local_sale_detail">
    <thead>
        <tr>
            <th colspan="8" style="text-align:center; font-size: 20px">BILL/INVOICE/MEMO</th>
        </tr>
        <tr>
            <th colspan="2">Market: <?=$market_name?></th>
            <th colspan="2">Rec.No.: <?=$invoice_number?></th>
            <th colspan="2">Pay.Mode: <?=$payment_mode?></th>
            <th colspan="2">Date: <?=$sale_date?></th>
        </tr>
        <tr>
            <th colspan="8">Mr./ Ms: <?=$party_name?></th>
        </tr>                
        <tr class="report_header">
            <th>S.N.</th>
            <th>Description</th>
            <th>Fish Code</th>
            <th>Type</th>
            <th>Quantity.</th>
            <th>Weight</th>
            <th>Rate</th>
            <th>Amout</th>
        </tr>
    </thead>
    <tbody id="report_data">
        <?=$tbody?>
    </tbody>
    <tfoot>
       <tr>
            <th colspan="4">Sub Total</th>
            <th><?=$t_fish_quantity;?></th>
            <th><?=$t_fish_weight;?></th>
            <th></th>
            <th><?=number_format($t_total_amount,2,".","");?></th>
        </tr>
        <tr>
            <td colspan="4">Ice</td>
            <td></td><td><?=$ice_weight;?></td>
            <td><?=$ice_rate;?></td>
            <td><?=$ice_amount;?></td>
        </tr>
        <tr>
            <td colspan="4">Destroyed</td>
            <td></td>
            <td><?=$destroyed_weight;?></td>
            <td><?=$destroyed_rate;?></td>
            <td><?=$destroyed_amount;?></td>            
        </tr>
        <tr>
            <td colspan="4">Discount <small>(In %)</small></td>
            <td></td>
            <td></td>
            <td><?=$discount_perc;?></td>
            <td><?=$discount_amount;?></td>
        </tr>
        <tr>
            <td colspan="4">Grand Total</td>
            <td></td>
            <td></td>
            <td></td>
            <th><?=$grand_total;?></th>
        </tr>       
        <tr>
            <td colspan="4">Received Amount</td>
            <td></td>
            <td></td>
            <td></td>
            <th><?=$received_amt?></th>
        </tr>        
        <tr>
            <td colspan="4">Remaining Amount</td>
            <td></td>
            <td></td>
            <td></td>
            <th><?=$remaining_amt?></th>
        </tr>
    </tfoot>
</table>
