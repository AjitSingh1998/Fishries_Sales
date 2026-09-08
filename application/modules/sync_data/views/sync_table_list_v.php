<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!-- start: BREADCRUMB -->
<table class="table" id="table_table">
    <thead>
        <tr>
            <th>Data Type</th>
            <th>Last Sync</th>
            <th>Total Rows</th>
            <th>Synced Rows</th>
            <th>Action</th>
            <th>Response</th>
        </tr>
    </thead>
    <tbody>
        <?php 

            //echo strtotime('2018-08-30 17:47:29')* 1000 ."<br>";
            //echo date('Y-m-d H:i:s', '1535644049.6396');
            $html = '';			
            if(isset($tablesInfo) && !empty($tablesInfo)){
                foreach($tablesInfo as $table){
                    $nofrows = $table['rows'];
                    $name = str_replace(array('psac_', '_'),array('',' '),$table['table_name']);
                    $last_sync = $table['last_sync'];
                    $url = $sync_url.'/'.$table['table_name'].'/'.$last_sync;
                    
                    
                        $html .= '<tr id="'.$table['table_name'].'">
                                        <td>'.$name.'</td>
                                        <td>'.date('d/m/Y h:i A', $table['last_sync']).'</td>
                                        <td>'.$table['rows'].'</td>
                                        <td>'.$table['synced_rows'].'</td>
                                        <td>';
										
						if($table['rows'] > 0){
							
                        $html .= '<button type="button" data-url="'.$url.'" data-records="'.$table['rows'].'" 
                                                data-table="'.$table['table_name'].'" data-type="'.$table['data_type'].'" data-srows="'.$table['synced_rows'].'" 
                                                class="sync_table btn btn-primary btn-sm disabled">
                                                <i class="fa fa-refresh"></i> Start Sync
                                            </button>';
						}else{
						$html .= 'No Data';
						}
					    $html .= '</td>
									<td class="response"></td>
								</tr>';                  
                       
                    
                          
                }
            }else{
				echo $disabled = '<tr><td colspan="6" class="text-center">Data tables not found!</td></tr>';
			}
            echo $html;
        ?>
    </tbody>                                
</table>