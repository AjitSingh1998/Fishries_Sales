<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!-- start: BREADCRUMB -->
<style>
.nav>li.active>a{background-color: #eee;}
</style>
<?php
	$class = $this->router->fetch_class();
	$method = $this->router->fetch_method();
	$page = $this->uri->segment(3);
?>
<div class="panel panel-white">
	<div class="panel-heading border-light light-bg">
        <h3 class="text-center"> Data Type</h3>
    </div>
    <div class="panel-body">    	
        <div class="list-group">
        	<?php 
				//echo strtotime('2018-08-30 17:47:29')* 1000 ."<br>";
				//echo date('Y-m-d H:i:s', '1535644049.6396');
				$active = $disabled = '';
            	if(isset($tablesInfo) && !empty($tablesInfo)){
					foreach($tablesInfo as $table){
						$nofrows = $table['rows'];
						$name = str_replace(array('psac_', '_'),array('',' '),$table['table_name']);
						$last_sync = $table['last_sync'];
						$url = site_url('sync_data/export/'.$table['table_name'].'/'.$last_sync);
						
						if($table['rows'] > 0){
							$active .= '<a href="#" data-url="'.$url.'" data-records="'.$table['rows'].'" data-table="'.$table['table_name'].'" class="list-group-item sync_table">
										<h5 class="list-group-item-heading">'.$name.'</h5>
										<p class="list-group-item-text">New records: <span class="badge badge-dark">'.$nofrows.'</span></p>
									  </a>';
						}else{
							$disabled .= '<a href="#" data-url="'.$url.'" data-records="'.$table['rows'].'" data-table="'.$table['table_name'].'" class="list-group-item sync_table">
											<h5 class="list-group-item-heading">'.$name.'</h5>
											<p class="list-group-item-text">New records: <span class="badge badge-dark">'.$nofrows.'</span></p>
										  </a>';
						}
							  
					}
				}
				echo $active, $disabled;
			?>        
        </div>
	</div>
</div>