<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- start: BREADCRUMB -->

<div class="container-fluid container-fullw">
  <div class="row">
    <div class="col-sm-12">
    	<?php /*?><div class="row clearfix">
            <div class="col-sm-3 col-xs-12">
                <div class="info-box">
                    <div class="icon bg-blue">
                        <i class="fa fa-database"></i>
                    </div>
                    <div class="content">
                        <div class="text">Closing Stock <br> <small><?php echo get_date('d/m/Y'); ?></small></div>
                        <div class="number count-to"><?php echo isset($stock['closing_stock']) ? $stock['closing_stock'] : 0; ?></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-3 col-xs-12">
                <div class="info-box">
                    <div class="icon bg-green" style="background:#4CAF50 !important">
                        <i class="fa fa-database"></i>
                    </div>
                    <div class="content">
                        <div class="text"> Fresh Stock <br> <small><?php echo get_date('d/m/Y'); ?></small></div>
                        <div class="number count-to"><?php echo isset($stock['fresh_wt']) ? $stock['fresh_wt'] : 0; ?></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-3 col-xs-12">
                <div class="info-box">
                    <div class="icon bg-yellow">
                        <i class="fa fa-database"></i>
                    </div>
                    <div class="content">
                        <div class="text">Rotten Stock <br> <small><?php echo get_date('d/m/Y'); ?></small></div>
                        <div class="number count-to"><?php echo isset($stock['rotten_wt']) ? $stock['rotten_wt'] : 0; ?></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-3 col-xs-12">
                <div class="info-box">
                    <div class="icon bg-red">
                        <i class="fa fa-database"></i>
                    </div>
                    <div class="content">
                        <div class="text">Destroyed Stock <br> <small><?php echo get_date('d/m/Y'); ?></small></div>
                        <div class="number count-to"><?php echo isset($stock['destroyed_wt']) ? $stock['destroyed_wt'] : 0; ?></div>
                    </div>
                </div>
            </div>
        </div><?php */?>
      <div class="panel panel-white">
        <div class="panel-body">
            <?=$output?>
        </div>
      </div>
    </div>
  </div>
</div>
