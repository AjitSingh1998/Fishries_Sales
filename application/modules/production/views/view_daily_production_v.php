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
<?=form_open($form_action)?>

<input type="hidden" name="action_mode" value="<?=$action_mode?>" />
<div class="row">
    <div class="form-group col-md-2">
        <label class="control-label">Market Place: <span class="symbol required"></span></label>
        <input type="text" class="form-control" value="<?=@$production_data['market_name']?>" readonly="readonly">
    </div>
    <div class="form-group col-md-2">
        <label class="control-label">Date : <span class="symbol required"></span></label>
        <input type="text" name="production_date" class="form-control date" placeholder="Date" value="<?=get_date('d/m/Y', @$production_data['production_date'])?>" readonly="readonly" />
    </div>
    <div class="form-group col-md-3">
        <label class="control-label">Jhinga Point Weight : </label>
        <input type="text" class="form-control" value="<?=@$production_data['jhinga_point_wt']?>" readonly="readonly" />
    </div>
    <div class="form-group col-md-3">
        <label class="control-label">Jhinga Depot Weight : </label>
        <input type="text" class="form-control" value="<?=@$production_data['jhinga_depot_wt']?>" readonly="readonly" />
    </div>
</div>
<div class="row">
    <div class="form-group col-md-2">
        <label class="control-label">Point Weight : <span class="symbol required"></span></label>
        <input type="text" class="form-control" value="<?=@$production_data['point_wt']?>" readonly="readonly" />
    </div>
    <div class="form-group col-md-3">
        <label class="control-label">Forfeiture (S&D) Weight :</label>
        <input type="text" class="form-control" value="<?=@$production_data['forfeiture_wt']?>" readonly="readonly" />
    </div>
    <div class="form-group col-md-3">
        <label class="control-label">Forfeiture Rotten Weight :</label>
        <input type="text" class="form-control" value="<?=@$production_data['forfeiture_rt_wt']?>" readonly="readonly" />
    </div>
    <div class="form-group contact col-md-2">
        <label class="control-label">Total Point Weight :</label>
        <input type="text" class="form-control" readonly="readonly" value="<?=@$production_data['total_pt_wt']?>" readonly="readonly" />
    </div>
</div>
<div class="row">
    <div class="form-group col-md-2">
        <label class="control-label">Destroyed :</label>
        <input type="text" class="form-control" value="<?=@$production_data['destroyed_wt']?>" readonly="readonly" />
    </div>
    <div class="form-group col-md-2">
        <label class="control-label">Rotten Weight :</label>
        <input type="text" class="form-control" value="<?=@$production_data['rotten_wt']?>" readonly="readonly" />
    </div>
    <div class="form-group col-md-2">
        <label class="control-label">Depot Weight :<span class="symbol required"></span></label>
        <input type="text" class="form-control" value="<?=@$production_data['depot_wt']?>" readonly="readonly" />
    </div>
</div>

<div class="row">
    <div class="form-group col-md-2">
        <label class="control-label">Sale :</label>
        <input type="text" class="form-control" value="<?=@$production_data['paid_sale']?>" readonly="readonly" />
    </div>
    <div class="form-group col-md-2">
        <label class="control-label">Free Sale :</label>
        <input type="text" class="form-control" value="<?=@$production_data['free_sale']?>" readonly="readonly" />
    </div>
    <div class="form-group col-md-2">
        <label class="control-label">Total Weight :<span class="symbol required"></span></label>
        <input type="text" class="form-control" readonly="readonly" value="<?=@$production_data['total_wt']?>" readonly="readonly" />
    </div>
    <div class="form-group col-md-2">
        <label class="control-label">Surplus :</label>
        <input type="text" class="form-control" readonly="readonly" value="<?=@$production_data['surplus_wt']?>" readonly="readonly" />
    </div>
    <div class="form-group col-md-2">
        <label class="control-label">Surplus % :</label>
        <input type="text" class="form-control" readonly="readonly" value="<?=@$production_data['surplus_perc']?>" readonly="readonly" />
    </div>
</div>

<div class="row">
    <div class="form-group col-md-11">
        <label class="control-label">Remark :</label>
        <textarea name="remark" rows="3" class="form-control" readonly="readonly"><?=@$production_data['remark']?></textarea>
    </div>
</div>
<?=form_close()?>
<script>
$('.save_form').hide();
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
