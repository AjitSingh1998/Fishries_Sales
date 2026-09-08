<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!-- start: BREADCRUMB -->
<div class="container-fluid container-fullw">
	<div class="row flex">
    	<!--<div class="col-sm-3">
        	<?php //$this->load->view('sync_data/sidebar_navigation_v');?>
        </div>-->
        <div class="col-sm-12">
            <div class="panel panel-white" id="sync_panel">
                <div class="panel-heading border-light light-bg">
                    <h3 class="text-center">
						<span><?=$page_title?></span>
                    </h3>
                    <div class="row">
                    	<div class="col-sm-2">
                        	Limit :
                    		<input type="text" name="limit" placeholder="100" id="data_limit" class="form-controls data_limit" style="width:100px;">
                        </div>
                        
                    	<div class="col-sm-3">
                        	Sync date :
                    		<input type="text" name="sync_date" placeholder="dd-mm-yyyy" id="sync_date" class="form-controls datepicker">
                        </div>
                        
                        <div class="col-sm-2 no-padding">
                        	<div class="btn-group">
                                <button class="btn btn-primary btn-o active" id="local_data" data-url="<?=$table_url?>" data-type="Local"> Local Data </button>
                                <button class="btn btn-primary btn-o" id="master_data" data-url="<?=$table_url?>" data-type="Master"> Master Data </button>
                            </div>
                        </div>
                        <div class="col-sm-4 no-padding">
                            <span class="display-table-cell vertical-align-middle padding-left-10">Sync Data Auto &nbsp;</span>
                            <div class="display-table-cell">
                                <input type="checkbox" id="show_all_data"  class="js-switch" data-target="#table_table" />
                            </div>
                            <span class="display-table-cell vertical-align-middle">&nbsp; Manual </span>
                            <span class="display-table-cell vertical-align-middle padding-left-10">
                            	<button type="button" class="btn btn-primary initiate_sync_data"><i class="fa fa-refresh"></i> Start Sync</button>
                            </span>
                        </div>                                               
                    </div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-12" id="response_data">
                        	<?=$tablesInfo?>
                        </div>
                    </div>
                </div>
            </div>
         </div>
    </div>
</div>