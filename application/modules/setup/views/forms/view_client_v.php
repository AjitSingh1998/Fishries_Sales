<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php extract($dbdata); ?>
<div class="row">
    <div class="col-md-2">
        <div class="form-group">
            <label class="control-label">Code : </label>
            <input type="text" class="form-control" value="<?=@$code?>" <?=$disabled?>>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label class="control-label">Trademark Name : </label>
            <input type="text" class="form-control" value="<?=@$trademark_name?>" <?=$disabled?>>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label class="control-label">Customer Name : </label>
            <input type="text" class="form-control" value="<?=@$company_name?>" <?=$disabled?>>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label class="control-label">Market Type : </label>
            <input type="text" class="form-control" value="<?=@$market_type_name?>" <?=$disabled?>>
        </div>
    </div>  
</div>

<div class="row">
	<div class="col-md-4">
        <div class="form-group">
            <label class="control-label">Email : </label>
            <input type="text" class="form-control" name="email" value="<?=@$email?>" <?=$disabled?>>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="form-group">
            <label class="control-label">Contact Number : </label>
            <input type="text" class="form-control" value="<?=@$contact_number?>" <?=$disabled?>>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label class="control-label">Contact Number 2 : </label>
            <input type="text" class="form-control" value="<?=@$contact_number2?>" <?=$disabled?>>
        </div>
    </div>   
</div>

<div class="row">
	<div class="col-md-4">
        <div class="form-group">
            <label class="control-label">City : </label>
            <input type="text" class="form-control" value="<?=@$city_name?>" <?=$disabled?>>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="form-group">
            <label class="control-label">State : </label>
            <input type="text" class="form-control" value="<?=@$state?>" <?=$disabled?>>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label class="control-label">Country : </label>
            <input type="text" class="form-control" value="<?=@$country?>" <?=$disabled?>>
        </div>
    </div>   
</div>


<div class="row">
	<div class="col-md-6">
        <div class="form-group">
            <label class="control-label">Address : </label>
            <textarea rows="2" class="form-control" <?=$disabled?>><?=@$address?></textarea>
        </div>
	</div>
    <div class="col-md-3">
        <div class="form-group">
            <label class="control-label">Opening Balance Number :</label>
            <input type="text" class="form-control input-lg" value="<?=@$opening_balance?>" <?=$disabled?> />
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label class="control-label"> Status : </label>
            <input type="text" class="form-control" value="<?=@$status?>" <?=$disabled?>>
        </div>
	</div>
</div>

<script>
$('.save_form').remove();
</script>
