<?php 
function view_sale_box_html($box_number, $box_items, $s_no = '', $all_clients=''){
	$sale = '';
	foreach($box_items as $item){
		
		$client_name = '';
		if(!empty($all_clients)){
			foreach($all_clients as $client){
				if($item['client_id'] == $client['ID']){
					$client_name = $client['company_name'];
				}
			}
		}
		
		
		$sale .= '<tr class="sale_row">
						<td style="width:15%;">'.$item['fish_code'].'</td>
						<td style="width:10%;">'.$item['sale_qty'].'</td>
						<td style="width:10%;">'.$item['sale_wt'].'</td>
						<td style="width:10%;">'.$item['sale_price'].'</td>
						<td style="width:30%;">'.$item['sale_remark'].'</td>
						<td style="width:20%;">'.$client_name.'</td>
					  </tr>';
	}
	
	$response_html = '<tr class="box_row" id="'.$box_number.'">
							<td class="s_no" style="width:2%;">'.$s_no.'</td>
							<td style="width:5%;">'.$box_number.'</td>';
	$response_html .= '<td style="width:37%;">
						<table class="table table-striped table-condensed">';
	$response_html .= $sale;
	$response_html .= '	</table>
						</td>						
					</tr>';
	return $response_html;
}	

function generate_sale_box_html($box_number, $box_items, $s_no = '', $all_clients=''){
	$total_qty = $total_wt = 0;	
	$sale = '';
	foreach($box_items as $item){
		$disabled = '';
		$btn_class = 'btn-success save_sale';
		$btn_text = 'Save';
		if(!empty($item['sale_price']) && $item['sale_price'] != 0.00){
			$disabled = 'disabled="disabled"';
			$btn_class = 'btn-warning edit_sale';
			$btn_text = 'Edit';
		}
		
		$client_select_box = '<select name="client_id" '.$disabled.' class="form-control client_id">';
		$client_select_box .= '<option value="">Select Client</option>';
		if(!empty($all_clients)){
			foreach($all_clients as $client){
				$selected = '';
				if($item['client_id'] == $client['ID']){
					$selected = 'selected="selected"';
				}
				$client_select_box .= '<option '.$selected.' value="'.$client['ID'].'">'.$client['company_name'].'</option>';
			}
		}
		$client_select_box .= '</select>';
		
		$sale .= '<tr class="sale_row">
						<td style="width:15%;">'.$item['fish_code'].'</td>
						<td style="width:10%;"><input type="number" min="0" name="sale_qty" class="form-control sale_qty" value="'.$item['sale_qty'].'" '.$disabled.' /></td>
						<td style="width:10%;"><input type="number" min="0" name="sale_wt" class="form-control sale_wt" value="'.$item['sale_wt'].'" '.$disabled.' /></td>
						<td style="width:10%;"><input type="number" min="0" name="sale_price" class="form-control sale_price" value="'.$item['sale_price'].'" '.$disabled.' /></td>
						<td style="width:30%;"><input type="text" name="sale_remark" class="form-control sale_remark" value="'.$item['sale_remark'].'" '.$disabled.' /></td>
						<td style="width:20%;">'.$client_select_box.'</td>
						<td style="width:5%;">
							<input type="hidden" name="item_id" value="'.$item['item_id'].'" />
							<input type="hidden" name="box_number" value="'.$box_number.'" />
							<button type="button" class="btn '.$btn_class.' btn-xs">'.$btn_text.'</button>
						</td>
					  </tr>';
		$total_qty += $item['sale_qty'];
		$total_wt += $item['sale_wt'];
	}
	$response_html = '<tr class="box_row" id="'.$box_number.'">
							<td class="s_no" style="width:2%;">'.$s_no.'</td>
							<td style="width:5%;">'.$box_number.'</td>';
							/*<td style="width:5%;">'.$total_qty.'</td>
							<td style="width:5%;">'.$total_wt.'</td>*/
	$response_html .= '<td style="width:37%;">
						<table class="table table-striped table-condensed">';
	$response_html .= $sale;
	$response_html .= '	</table>
						</td>						
					</tr>';
	return $response_html;
}

function outside_sale_box_list_html($sr_no = '', $box_detail, $box_items, $action_mode = 'view'){
	$box_quantity 	= ($box_detail['box_quantity'] > 0) ? my_number_format($box_detail['box_quantity']) : '0.00';
	$box_number   	= $box_detail['box_number'];
	$box_weight   	= ($box_detail['box_weight'] > 0) ? my_number_format($box_detail['box_weight']) : '';
	$box_status		= $box_detail['box_status'];
	
	$gross_wt		= $box_detail['gross_wt'];
	$net_wt			= $box_detail['net_wt'];
	$rate			= $box_detail['rate'];
	$amount			= $box_detail['amount'];
	
	if($action_mode == 'view' && !$box_detail['item_id']){
		return $response_html = '';
	}
	$response_html = '';
	
	$tr_class = ' box_unchecked';
	$checked  = '';
	if($box_detail['item_id']){$tr_class = ' box_checked info'; $checked  = 'checked="checked"';}
	
	$response_html = '<tr class="box_row box_'.$box_number.$tr_class.'" id="'.$sr_no.'">';
	if($action_mode == 'edit'){
		$response_html .= '    <td class="sr_no" style="width:45px;"><input type="checkbox" class="box_check" '.$checked.' /></td>';
	}
	$response_html .= '    	<td style="width:100px;">'.$sr_no.'</td>
						   	<td style="width:100px;"><input class="box_number disabled" readonly="readonly" style="width:80px;" type="text" value="'.$box_number.'" /></td>
						   	<td style="width:100px;"><input type="text" value="'.$box_quantity.'" class="box_qty disabled" readonly="readonly" style="width:80px;" /></td>
							<td style="width:100px;"><input type="text" value="'.$box_weight.'" class="disp_wt disabled" readonly="readonly" style="width:80px;" /></td>
						    <td style="width:700px;">
								<table class="table table-striped table-condensed">';	
	
	
	$disabled_class = '';
	$readonly = '';
	if($action_mode == 'view'){
		$disabled_class = 'disabled';
		$readonly = 'readonly="readonly"';
	}
	foreach($box_items as $item){
		$response_html .= '<tr class="box_item_row">
								<input type="hidden" name="fish_code" value="'.$item['fish_code'].'" />
								<input type="hidden" name="" value="'.$item['fish_qty'].'" />
								<td style="width:20%;">'.$item['fish_code'].' ('.$item['fish_name'].')</td>
								<td style="width:10%;">'.$item['fish_qty'].'</td>
								<td style="width:14%;"><input class="box_wt disabled stict_numeric" readonly="readonly" style="width:80px;" type="text" value="'.my_number_format($item['box_wt']).'" /></td>	
								<td style="width:14%;"><input type="text" value="'.$gross_wt.'" class="gross_wt strict_numeric '.$disabled_class.'" '.$readonly.' style="width:80px;" onkeyup="checkDecimal(this);" /></td>
								<td style="width:14%;"><input type="text" value="'.$net_wt.'" class="net_wt disabled" style="width:80px;" readonly="readonly" /></td>
								<td style="width:14%;"><input type="text" value="'.$rate.'" class="rate strict_numeric '.$disabled_class.'" '.$readonly.' style="width:80px;" /></td>
								<td style="width:14%;"><input type="text" value="'.$amount.'" class="amount disabled" readonly="readonly" style="width:80px;" /></td>';
		$response_html .= '</tr>';
	}
	
	
	$response_html .= '	</table></td></tr>';
	
	return $response_html;
}

?>