<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="row">
	<?php 
	$totalrow = count($locationData);
	$i=0;
	foreach($locationData as $location){ $i++;
	if($totalrow == $i){
		$borderClass = ' class="tr-td-no-border"';
	}else{
		$borderClass ='';
	}
	?>
	<div class="col-sm-12">
    	<table class="table">
        	<tr<?=$borderClass?>>
            	<td width="5%"><i class="fa fa-map-marker fa-4x"></i></td>
            	<td width="70%" class="text-left">
					<strong><?=$location['branch_name']?></strong><br />
                    <?php                    
					if(!empty($location['address']) || !empty($location['address']) || !empty($location['city'])){
						echo (($location['address'])?$location['address'].', ':'').' '.($location['city']);
					}else{
						echo 'Mobile location - no fixed address';
					}					
					?>
                </td>
               	<td width="25%" class="text-right">
                	<a href="<?=site_url('settings/business/edit_location/'.$location['branch_id'])?>" class="btn btn-primary">Edit</a>
                    <a href="<?=site_url('settings/business/delete_location/'.$location['branch_id'])?>" class="btn btn-danger" title="Delete">Delete</a>
                </td>
            </tr>
        </table>
    </div>
    <?php } ?>
</div>

