<table class="table table-striped" width="100%" id="header_table">
    <thead>
        <tr>
            <th style="width:50px;">S.No.</th>
            <th style="width:100px;">Box Number</th>
            <th style="width:100px;">Total Qty</th>
            <th style="width:50px;">Total WT</th>
            <th style="width:500px;">
                <table class="table borderless table-condensed">
                   <tr>
                        <th style="width:30%;">Fish</th>
                        <th style="width:15%;">Quantity</th>
                        <th style="width:15%;">Carret Wt</th>
                        <th style="width:15%;">Box Wt</th>
                        <th style="width:20%; text-align:left;">Estimated Price</th>
                        <th style="width:5%;" class="no-print no-exl">Action</th>
                    </tr>
                </table>
            </th>
        </tr>                    
    </thead>
</table>
<div class="panel-scroll height-300">
    <table class="table table-striped" width="100%" id="container_table">
        <tbody id="box_listing_container">
        <?php 
        if(isset($all_boxes) && !empty($all_boxes)){
            $i = 1;
            foreach($all_boxes as $box_data){
                $box_number = $box_data['box_number'];
                $box_items = !empty($box_data['box_items']) ? explode('|', $box_data['box_items']) : array();
                if(is_array($box_items) && !empty($box_items)){
                    $dataset = array();
                    foreach($box_items as $box){
                    $items = !empty($box) ? explode('/', $box) : array();
                    $dataset[] = array( 'fish_code' 	=> @$items[0], 
										'fish_name' 	=> @$items[1], 
										'fish_qty' 		=> @$items[2], 
										'fish_wt' 		=> @$items[3], 
										'box_wt' 		=> @$items[4], 
										'item_id' 		=> @$items[5], 
										'estimated_price' => @$items[6]
										);
                    }
                    echo generate_estimate_box_html($i, $box_data, $dataset, $action_mode);
                }                            
                $i++;
            }
        }
        ?>
        </tbody>                
    </table>
</div>


