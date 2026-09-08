<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- start: BREADCRUMB -->
<?php echo form_open('', 'class="form-horizontal" id="x_udpate_rate"');?>
    <input type="hidden" name="sale_id" value="<?=$sale_id?>" />
	<div class="row">
		<div class="col-sm-10 col-sm-offset-1">
		   <table class="table">
			<thead>
				<tr>
                <th>Fish</th>
                <th>New Rate</th>
              </tr>
			</thead>
			<tbody id="x_package-container">
            	<?php
				if(!empty($result))
				{
				  foreach($result as $row)
				  {
				?>
                <tr>
                	<td><?=$row['fish_name'].' ('.$row['fish_code'].')'?></td>
                    <td><input type="text" name="new_rate[<?=$row['fish_code']?>]" class="form-control strict_numeric" value="<?=$row['fish_rate']?>" onkeyup="checkDecimal(this)" /></td>
                </tr>
                <?php 
				}
				  }
				?>
			</tbody>               
		</table>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12 ajax_reponse"></div>
	</div>
	<div class="row">
		<div class="col-sm-12 text-center">
			<button type="button" name="save_rate" class="btn btn-success text-left save_rate">Update</button>
		</div>
	</div>
<?php echo form_close();?>

<script>	
	$('.save_rate').on('click', function(){
		var $this = $(this);
		show_loader($('#x_udpate_rate'));
		var form_data 	= $('#x_udpate_rate').serialize();
		$this.prop('disabled', true);
		$.ajax({
			type: "POST",
			url: site_url+"dailysale/update_rate/<?=$sale_id?>",
			data: form_data,
			beforeSend: function(){$('.ajax_reponse').html('');},
			error: function (xhr, ajaxOptions, thrownError){
				$this.prop('disabled', false);
				hide_loader($('#x_udpate_rate'));
				alert('Response - '+ xhr.status+':  '+thrownError+' Please contact to development department.');
			},
			success: function( response ){
				hide_loader($('#x_udpate_rate'));
				if(response.status == 'success'){
					setTimeout(function(){location.reload();}, 1000);
				}
				$.toaster({settings:{timeout:10000,toast:{template:'<div class="alert alert-'+response.status+'"><button data-dismiss="alert" class="close">×</button> '+response.message+'</div>'}}, message:''});
			}						
		});
	});
</script>
