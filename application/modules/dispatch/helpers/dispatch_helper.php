<?php 
function generate_box_list_html($sr_no = '', $box_detail, $box_items, $action_mode = 'view', $param_1 = ''){
	$box_quantity 	= ($box_detail['box_quantity'] > 0) ? $box_detail['box_quantity'] : '0';
	$box_number   	= $box_detail['box_number'];
	$box_weight   	= ($box_detail['box_weight'] > 0) ? my_number_format($box_detail['box_weight']) : '';
	$box_status		= $box_detail['box_status'];
	
	$response_html = '';
	if($param_1 != 'ajx_upd'){
		$response_html .= '<tr class="box_row box_'.$box_number.'" id="'.$sr_no.'">';
	}
	$response_html .= '	<td class="sr_no" style="width:50px;">'.$sr_no.'</td>
							<td style="width:100px;">'.$box_number.'</td>
							<td style="width:400px;">
								<table class="table table-striped table-condensed">';	
	
	foreach($box_items as $item){
		$response_html .= '<tr>
								<td style="width:40%;">'.$item['fish_code'].' ('.$item['fish_name'].')</td>
								<td style="width:20%;">'.$item['fish_qty'].'</td>
								<td style="width:20%;">'.my_number_format($item['fish_wt']).'</td>
								<td style="width:20%;">'.my_number_format($item['box_wt']).'</td>';		
		$response_html .= '</tr>';
	}
	
	$response_html .= '	</table>
						</td>
						<td style="width:100px;">'.$box_quantity.'</td>
						<td style="width:100px;">'.$box_weight.'</td>';
	
	//Action button
	$action_btn = '<button type="button" data-box_number="'.$box_number.'" class="btn btn-primary btn-xs edit_box" data-toggle="tooltip" data-title="Edit Box"><i class="fa fa-pencil"></i></button>&nbsp;<button type="button" data-box_number="'.$box_number.'" class="btn btn-danger btn-xs delete_box" data-toggle="tooltip" data-title="Delete Box"><i class="fa fa-trash"></i></button>';
	
	if(($box_status == 'Prepared' || $box_status == 'Estimate' || $box_status == 'Dispatched')){
		$action_btn = '<span data-box_number="'.$box_number.'" class="label label-info">Box '.$box_status.'</span>';
	}
	
	/*
	if(isset($item['sale_id']) && $item['sale_id'] > 0){
		$action_btn = '<span data-box_number="'.$box_number.'" class="label label-info">Box Sold</span>';
	}
	*/
	
	if($action_mode != 'view'){
		$response_html .= '	<td style="width:100px;" class="no-print no-exl">'.$action_btn.'</td>';
	}
	
	if($param_1 != 'ajx_upd'){
		$response_html .= '</tr>';
	}
	return $response_html;
}

function dr_box_list_html($sr_no = '', $box_detail, $box_items, $action_mode = 'view'){
	$box_quantity 	= ($box_detail['box_quantity'] > 0) ? $box_detail['box_quantity'] : '0';
	$box_number   	= $box_detail['box_number'];
	$box_weight   	= ($box_detail['box_weight'] > 0) ? my_number_format($box_detail['box_weight']) : '';
	$dispatch_status= $box_detail['dispatch_status'];
	
	$response_html = '';
	$response_html .= '<tr class="box_row box_'.$box_number.'" id="'.$sr_no.'">';
	$response_html .= '	<td class="sr_no" style="width:50px;">'.$sr_no.'</td>
							<td style="width:100px;">'.$box_number.'</td>
							<td style="width:400px;">
								<table class="table table-striped table-condensed">';	
	
	foreach($box_items as $item){
		$response_html .= '<tr>
								<td style="width:40%;">'.$item['fish_code'].' ('.$item['fish_name'].')</td>
								<td style="width:20%;">'.$item['fish_qty'].'</td>
								<td style="width:20%;">'.my_number_format($item['fish_wt']).'</td>
								<td style="width:20%;">'.my_number_format($item['box_wt']).'</td>';		
		$response_html .= '</tr>';
	}
	
	$response_html .= '	</table>
						</td>
						<td style="width:100px;">'.$box_quantity.'</td>
						<td style="width:100px;">'.$box_weight.'</td>';
	
	//Action button
	$action_btn = '<button type="button" data-box_number="'.$box_number.'" class="btn btn-danger btn-xs remove_box" data-toggle="tooltip" data-title="Remove Box From DR"><i class="fa fa-trash"></i></button>';
	
	if(($dispatch_status != 'Active')){
		$action_btn = '<span data-box_number="'.$box_number.'" class="label label-info">Box '.$dispatch_status.'</span>';
	}	
	if($action_mode != 'view'){
		$response_html .= '	<td style="width:100px;" class="no-print no-exl">'.$action_btn.'</td>';
	}
	$response_html .= '</tr>';
	return $response_html;
}

function dispatched_box_list_html($sr_no = '', $box_detail, $box_items, $action_mode = 'view'){
	$box_quantity 	= ($box_detail['box_quantity'] > 0) ? $box_detail['box_quantity'] : '0';
	$box_number   	= $box_detail['box_number'];
	$box_weight   	= ($box_detail['box_weight'] > 0) ? my_number_format($box_detail['box_weight']) : '';
	$dispatch_status= $box_detail['dispatch_status'];
	
	$response_html = '';
	$response_html .= '<tr class="box_row box_'.$box_number.'" id="'.$sr_no.'">';
	$response_html .= '	<td class="sr_no" style="width:50px;">'.$sr_no.'</td>
							<td style="width:100px;">'.$box_number.'</td>
							<td style="width:400px;">
								<table class="table table-striped table-condensed">';	
	
	foreach($box_items as $item){
		$response_html .= '<tr>
								<td style="width:40%;">'.$item['fish_code'].' ('.$item['fish_name'].')</td>
								<td style="width:20%;">'.$item['fish_qty'].'</td>
								<td style="width:20%;">'.my_number_format($item['fish_wt']).'</td>
								<td style="width:20%;">'.my_number_format($item['box_wt']).'</td>';		
		$response_html .= '</tr>';
	}
	
	$response_html .= '	</table>
						</td>
						<td style="width:100px;">'.$box_quantity.'</td>
						<td style="width:100px;">'.$box_weight.'</td>';
	
	//Action button
	$action_btn = '<button type="button" data-box_number="'.$box_number.'" class="btn btn-danger delete_box" data-toggle="tooltip" data-title="Delete Box"><i class="fa fa-trash"></i></button>';
	
	if(($dispatch_status != 'Active')){
		$action_btn = '<span data-box_number="'.$box_number.'" class="label label-info">Box '.$dispatch_status.'</span>';
	}	
	if($action_mode != 'view'){
		$response_html .= '	<td style="width:100px;" class="no-print no-exl">'.$action_btn.'</td>';
	}
	$response_html .= '</tr>';
	return $response_html;
}

function generate_estimate_box_html( $sr_no = '', $box_detail, $box_items, $action_mode = 'view'){
	$box_number 	= $box_detail['box_number'];
	$box_quantity 	= $box_detail['box_quantity'];
	$box_weight 	= $box_detail['box_weight'];
	
	$estimate = '';
	foreach($box_items as $item){
		$disabled = '';
		$btn_class = 'btn-success save_estimate_by_box';
		$btn_text = 'Save';
		if(!empty($item['estimated_price']) && $item['estimated_price'] != 0.00){
			$disabled = 'disabled="disabled"';
			$btn_class = 'btn-warning edit_estimate_by_box';
			$btn_text = 'Edit';
		}
		if($action_mode == 'view'){
			$disabled = 'disabled="disabled"';
		}
		
		$estimate .= '<tr class="estimate_row">
						<td style="width:30%;">'.$item['fish_code'].' ('.$item['fish_name'].')</td>
						<td style="width:15%;">'.$item['fish_qty'].'</td>
						<td style="width:15%;">'.$item['fish_wt'].'</td>
						<td style="width:15%;">'.$item['box_wt'].'</td>
						<td style="width:20%;"><input type="text" name="estimated_price" class="strict_numeric estimated_price" value="'.$item['estimated_price'].'" onkeyup="checkDecimal(this);" '.$disabled.' /></td>
						<td style="width:5%;" class="no-print no-exl">
							<input type="hidden" name="item_id" value="'.$item['item_id'].'" />
							<input type="hidden" name="box_number" value="'.$box_number.'" />';
		$estimate .= '	<button type="button" class="btn '.$btn_class.' btn-xs">'.$btn_text.'</button>';
		$estimate .= '	</td>
					  </tr>';
	}
	
	$response_html = '<tr class="box_row box_'.$box_number.'" id="'.$sr_no.'">
							<td class="sr_no" style="width:50px;">'.$sr_no.'</td>
							<td style="width:100px;">'.$box_number.'</td>
							<td style="width:100px;">'.$box_quantity.'</td>
							<td style="width:100px">'.$box_weight.'</td>
							<td style="width:500px;">
								<table class="table table-striped table-condensed">';
	$response_html .= $estimate;
	$response_html .= '	</table>
						</td>						
					</tr>';
	return $response_html;
}

function transfer_box_list_html($s_no = '', $box_detail, $box_items, $action_mode = 'view', $checked = ''){
	$box_quantity 	= ($box_detail['box_quantity'] > 0) ? $box_detail['box_quantity'] : '0';
	$box_number   	= $box_detail['box_number'];
	$box_weight   	= ($box_detail['box_weight'] > 0) ? my_number_format($box_detail['box_weight']) : '';
	
	if($action_mode == 'transfer'){
		$s_no = '<input type="hidden" name="all_boxes[]" value="'.$box_number.'" /><input type="checkbox" '.$checked.' class="boxes" name="boxes[]" value="'.$box_number.'" />';
	}
	$response_html = '<tr class="box_row" id="'.$box_number.'">
							<td class="s_no" style="width:50px;">'.$s_no.'</td>
							<td style="width:100px;">'.$box_number.'</td>
							<td style="width:400px;">
								<table class="table table-striped table-condensed">';
		
	foreach($box_items as $item){
		$response_html .= '<tr>
								<td style="width:40%;">'.$item['fish_code'].' ('.$item['fish_name'].')</td>
								<td style="width:20%;">'.$item['fish_qty'].'</td>
								<td style="width:20%;">'.my_number_format($item['fish_wt']).'</td>
								<td style="width:20%;">'.my_number_format($item['estimated_price']).'</td>';
		
		$response_html .= '</tr>';
	}
	$response_html .= '	</table>
						</td>
						<td style="width:100px;">'.$box_quantity.'</td>
						<td style="width:100px;">'.$box_weight.'</td>';
	return $response_html;
}

function generate_box_number($mp_code){		
	$ci = &get_instance();
	$invoice_number = '';
	$result = $ci->db->select('MAX(number) as b_number')->where(array('mp_code'=>$mp_code))->get(BOX_NUMBER)->result_array();
	//$mp_code = (strlen($mp_code) > 1) ? strtoupper(substr($mp_code, 0, 1)) : strtoupper($mp_code);
	if(!empty($result)){
		return (1 + $result[0]['b_number']);
	}
	return 1;	
}

?>