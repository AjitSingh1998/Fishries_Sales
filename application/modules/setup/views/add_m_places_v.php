<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
if(isset($stylesheet) && !empty($stylesheet)){
	if(is_array($stylesheet)){
		foreach($stylesheet as $style){
			echo '<link rel="stylesheet" href="'.$style.'" />'."\n";
		}
	}
}
?>
<?=form_open($form_action, 'class="form-horizontal"')?>
<input type="hidden" name="action_mode" value="<?=$action_mode?>" />
<input type="hidden" name="market_id" value="<?=$m_places_data['ID']?>" />
<div class="form-group">
    <label class="control-label col-sm-3">Market Type: <span class="symbol required"></span></label>
    <div class="col-md-4">
        <select class="form-control market_type" name="market_type">
          <option value="">Select Type</option>
          <?php
			$m_type = set_value('market_type', $m_places_data['market_type']);
			if(!empty($market_types)){
				$selected = '';
				foreach($market_types as $row){
					$op_val = $row['ID'];
					$op_text = $row['name'];
				
					if($m_type == $op_val){
						$selected = 'selected="selected"';
					}else{
						$selected = '';
					}
		  ?>
          <option <?=$selected?> value="<?=$op_val?>"><?=$op_text?></option>
          <?php }} ?>
        </select>
    </div>
</div>
<div class="form-group">
    <label class="control-label col-sm-3">Market Category: <span class="symbol required"></span></label>
    <div class="col-md-4">
        <select class="form-control market_category" name="market_category">
          <option value="">Select Category</option>
          <?php
			$m_category = set_value('market_category', $m_places_data['market_category']);
			if(!empty($market_category) && !empty($m_type)){
				$selected = '';
				foreach($market_category as $row){
					$op_val 	= $row['ID'];
					$op_text 	= $row['name'];
					$op_m_type 	= $row['market_type'];
					if($op_m_type == $m_type){
						if($m_category == $op_val){
							$selected = 'selected="selected"';
						}else{
							$selected = '';
						}
		  ?>
          <option <?=$selected?> value="<?=$op_val?>"><?=$op_text?></option>
          <?php }}} ?>
        </select>
    </div>
</div>
<div class="form-group">
    <label class="control-label col-sm-3">Name: <span class="symbol required"></span></label>
    <div class="col-md-4">
        <input type="text" class="form-control" name="name" value="<?=set_value('name', $m_places_data['name'])?>" />
    </div>
</div>
<div class="form-group">
    <label class="control-label col-sm-3">Code: <span class="symbol required"></span></label>
    <div class="col-md-4">
        <input type="text" class="form-control" name="code" value="<?=set_value('code', $m_places_data['code'])?>" />
    </div>
</div>
<div class="form-group">
    <label class="control-label col-sm-3">Status: <span class="symbol required"></span></label>
    <div class="col-md-4">
    	<?php $status = set_value('status', $m_places_data['status']) ?>
        <select class="form-control" name="status">
        	<option value="">Select Status</option>
            <option value="Active" <?php if($status=="Active"){echo 'selected="selected"';}?>>Active</option>
            <option value="Inactive" <?php if($status=="Inactive"){echo 'selected="selected"';}?>>Inactive</option>
        </select>
    </div>
</div>
<?=form_close()?>
<script>
$('.save_form').show();
</script>
<?php
	if(isset($scriptsrc) && !empty($scriptsrc)){	
		if(is_array($scriptsrc)){
			foreach($scriptsrc as $script){
				echo '<script src="'.$script.'"></script>'."\n";
			}
		}
	}
?>
