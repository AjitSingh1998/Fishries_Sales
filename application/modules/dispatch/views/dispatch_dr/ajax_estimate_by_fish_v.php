<table class="table table-striped" width="100%" id="container_table">
    <thead>
        <tr>
            <th>S.No.</th>
            <th>Fish</th>
            <th>Quantity</th>
            <th>Estimated Price</th>
            <?php if($action_mode == 'edit' ){ ?>
            <th>Action</th>
            <?php } ?>
        </tr>                    
    </thead>
    <tbody id="box_listing_container">
    <?php 
    	if(isset($fishes) && !empty($fishes)){
			$i = 1;
			foreach($fishes as $fish){
				$btn_class = 'btn-success save_estimate_by_fish';
				$btn_text = 'Save';
				$disabled = '';
				if($fish['estimated_price'] > 0) {
					$disabled = 'disabled="disabled"';
					$btn_class = 'btn-warning edit_estimate_by_fish';
					$btn_text = 'Edit';
				}
				if($action_mode == 'view'){
					$disabled = 'disabled="disabled"';
				}
	?>
    <tr class="estimate_row">
        <td><?=$i?></td>
        <td><?=$fish['fish_nc']?></td>
        <td><?=$fish['fish_qty']?></td>
        <td><input type="text" name="estimated_price" class="strict_numeric estimated_price" onkeyup="checkDecimal(this);" value="<?=$fish['estimated_price']?>" <?=$disabled?>/></td>
        <?php if($action_mode == 'edit' ){ ?>
        <td>
        	<input type="hidden" name="fish_qty" value="<?=$fish['fish_qty']?>" />
			<input type="hidden" name="fish_code" value="<?=$fish['fish_code']?>" />
            <button type="button" class="btn <?=$btn_class?> btn-xs"><?=$btn_text?></button>
        </td>
        <?php } ?>
    </tr>
    <?php $i++; }}?>
    </tbody>                
</table>