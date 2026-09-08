<?php 

	function generate_box_list_html($sr_no = '', $carret_data, $carret_items, $action_mode = 'view', $param_1 = ''){
		$carret_number 		= $carret_data['carret_number'];
		$carret_quantity 	= $carret_data['carret_quantity'];
		$carret_weight 		= $carret_data['carret_weight'];
		$fish_type 			= $carret_data['fish_type'];
		$client_name		= $carret_data['client_name'];
		$sale_id			= $carret_data['sale_id'];
		
		$box_number 		= isset($carret_data['box_number']) ? $carret_data['box_number'] : '';
		$box_weight = '';
		if($box_number){
			$box_weight = ($carret_data['box_weight'] > 0) ? my_number_format($carret_data['box_weight']) : '';
		}
		$box_status = $carret_data['box_status'];
		
		
		$response_html  = '';
		if($param_1 != 'ajx_upd'){
			$response_html .= '<tr class="carret_row carret_'.$carret_number.'" id="'.$sr_no.'">';
		}
		$response_html .= '	<td class="sr_no" style="width:50px;">'.$sr_no.'</td>
								<td style="width:100px;">'.$fish_type.'</td>
								<td style="width:100px;">'.$carret_number.'</td>
								<td style="width:400px;">
									<table class="table table-striped table-condensed box_items_table">';
				
		foreach($carret_items as $item){
			$response_html .= '<tr>
									<td style="width:40%;">'.$item['fish_code']." (".$item['fish_name'].')</td>
									<td style="width:20%;">'.$item['fish_qty'].'</td>
									<td style="width:20%;">'.my_number_format($item['fish_wt']).'</td>
									<td style="width:20%;">'.my_number_format($item['box_wt']).'</td>';		
			$response_html .= '</tr>';
		}
		$response_html .= '	</table>
							</td>
							<td style="width:100px;">'.$carret_quantity.'</td>
							<td style="width:100px;">'.my_number_format($carret_weight).'</td>
							<td style="width:100px;">'.$client_name.'</td>
							<td style="width:100px;">'.$box_number.'</td>
							<td style="width:100px;">'.$box_weight.'</td>';
		
		$action_btn = '<button type="button" data-carret_number="'.$carret_number.'" data-box_number="'.$box_number.'" data-sale_id="'.$sale_id.'" class="btn btn-primary btn-xs edit_carret" data-toggle="tooltip" data-title="Edit Carret"><i class="fa fa-pencil"></i></button>
					   <button type="button" data-carret_number="'.$carret_number.'" data-box_number="'.$box_number.'" data-sale_id="'.$sale_id.'" class="btn btn-danger btn-xs delete_carret" data-toggle="tooltip" data-title="Delete Carret"><i class="fa fa-trash"></i></button>
					  ';
		
		/*
		if(isset($item['status']) && ($item['status'] == 'Prepared' || $item['status'] == 'Estimate' || $item['status'] == 'Dispatched')){
			$action_btn = '<span data-carret_number="'.$carret_number.'" class="label label-info">Box '.$item['status'].'</span>';
		}
		
		if(isset($item['sale_id']) && $item['sale_id'] > 0){
			$action_btn = '<span data-carret_number="'.$carret_number.'" class="label label-info">Box Sold</span>';
		}
		*/
		
		if($action_mode == 'add' || $action_mode == 'edit'){
			if($box_status == "Active" || empty($box_status)){
				$response_html .= '	<td class="no-print no-exl" style="width:100px;">'.$action_btn.'</td>';
			}else{
				$response_html .= '	<td class="no-print no-exl" style="width:100px;">'.$box_status.'</td>';
			}
		}
		if($param_1 != 'ajx_upd'){
			$response_html .= '</tr>';
		}
		return $response_html;
	}
	
	function generate_box_number($mp_code){
		$ci = &get_instance();
		$result = $ci->db->select('MAX(number) as b_number')->where(array('mp_code'=>$mp_code))->get(BOX_NUMBER)->result_array();
		//$mp_code = (strlen($mp_code) > 1) ? strtoupper(substr($mp_code, 0, 1)) : strtoupper($mp_code);
		if(!empty($result)){
			return (1 + $result[0]['b_number']);
		}
		return 1;
		
		
	}
	
?>