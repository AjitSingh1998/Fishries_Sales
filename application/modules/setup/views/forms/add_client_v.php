<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php
$cities = $city;
extract($dbdata);
if(isset($stylesheet) && !empty($stylesheet)){
	if(is_array($stylesheet)){
		foreach($stylesheet as $style){
			echo '<link rel="stylesheet" href="'.$style.'" />'."\n";
		}
	}
}
?>

<?=form_open($form_action)?>
<input type="hidden" name="action_mode" value="<?=$action_mode?>" />
<input type="hidden" name="client_id" class="client_id" value="<?=$ID?>" />

<div class="row">
    <div class="col-md-2">
        <div class="form-group">
            <label class="control-label">Code :</label>
            <input readonly="readonly" type="text" class="form-control" name="code" placeholder="Enter Code" value="<?=set_value('code', $code)?>">
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label class="control-label">Trademark Name :</label>
            <input type="text" class="form-control" name="trademark_name" placeholder="Enter Code" value="<?=set_value('trademark_name', $trademark_name)?>">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label class="control-label">Customer Name : <span class="symbol required"></span></label>
            <input type="text" class="form-control" name="company_name" placeholder="Enter Name" value="<?=set_value('company_name', $company_name)?>">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label class="control-label">Market Type: <span class="symbol required"></span></label>
            <select class="form-control" name="market_type" id="market_type">
                <option value="">Select Market</option>
                <?php
                $value = set_value('market_types', $market_type);
                if(!empty($market_types)){
                    $selected = '';
                    foreach($market_types as $market){
                        $op_val = $market['ID'];
                        $op_text = $market['name'];
                    	$selected = ($value == $op_val) ? 'selected="selected"' : '';
                ?>
                <option <?=$selected?> value="<?=$op_val?>"><?=$op_text?></option>
                <?php }} ?>
            </select>
        </div>
    </div>  
</div>

<div class="row">
	<div class="col-md-4">
        <div class="form-group">
            <label class="control-label">Email </label>
            <input type="text" class="form-control" name="email" value="<?=set_value('email', $email)?>">
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="form-group">
            <label class="control-label">Contact Number <span class="symbol required"></span></label>
            <input type="text" class="form-control" name="contact_number" value="<?=set_value('contact_number', $contact_number)?>">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label class="control-label">Contact Number 2 :</label>
            <input type="text" class="form-control" name="contact_number2" value="<?=set_value('contact_number2', $contact_number2)?>">
        </div>
    </div>   
</div>
<div class="row">
	<div class="col-md-4">
        <div class="form-group">
            <label class="control-label">Market  <span class="symbol required"></span></label>
            <select name="city" class="form-control" id="city">
            <option value="">Select Market</option>
            <?php
			
			$value = set_value('city', $city);
			if(!empty($cities)){
				$selected = '';
				foreach($cities as $row){
					$op_val = $row['ID'];
					$op_text = $row['name'];
					$selected = ($value == $op_val) ? 'selected="selected"' : '';
			?>
			<option <?=$selected?> value="<?=$op_val?>"><?=$op_text?></option>
			<?php }} ?>
            </select>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="form-group">
            <label class="control-label">State </label>
            <input type="text" class="form-control" name="state" value="<?=set_value('state', $state)?>">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label class="control-label">Country </label>
            <input type="text" class="form-control" name="country" value="<?=set_value('country', $country)?>">
        </div>
    </div>   
</div>


<div class="row">
	<div class="col-md-6">
        <div class="form-group">
            <label class="control-label">Address </label>
            <textarea rows="2" class="form-control" name="address"><?=set_value('address', $address)?></textarea>
        </div>
	</div>
    <div class="col-md-3">
        <div class="form-group">
            <label class="control-label">Opening Balance Number :</label>
            <input type="text" class="form-control input-lg" name="opening_balance" value="<?=set_value('opening_balance', $opening_balance)?>">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label class="control-label"> Status <span class="symbol required"></span> </label>
            <select name="status" class="form-control input-lg">
                <option value="">Select Status</option>
                <option <?=(set_value('status', $status) == 'Active' ? 'selected="selected"' : '')?> value="Active">Active</option>
                <option <?=(set_value('status', $status) == 'Inactive' ? 'selected="selected"' : '')?> value="Inactive">Inactive</option>
            </select>
        </div>
	</div>
</div>
<?=form_close()?>

<?php
	if(isset($scriptsrc) && !empty($scriptsrc)){	
		if(is_array($scriptsrc)){
			foreach($scriptsrc as $script){
				echo '<script src="'.$script.'"></script>'."\n";
			}
		}
	}
?>
