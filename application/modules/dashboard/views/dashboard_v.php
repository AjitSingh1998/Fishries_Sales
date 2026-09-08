<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<div class="container-fluid container-fullw bg-white">
	<div class="row">
		<div class="col-md-12">
			<h2 class="mainTitle margin-bottom-15">Simran Fisheries Management Dashboard</h2>
			<p class="text-muted margin-bottom-25">Welcome back, <strong><?=loginUserInfo('full_name')?></strong>! Manage your sales, dispatch, production, and accounts below.</p>
		</div>
	</div>

	<!-- Quick Summary Stat Cards -->
	<div class="row">
		<div class="col-sm-3">
			<div class="panel panel-white no-radius text-center">
				<div class="panel-body">
					<div class="padding-10">
						<i class="fa fa-shopping-cart fa-3x text-primary"></i>
						<h3 class="step-title">Daily Sales</h3>
						<p class="text-small text-muted">Point & Depot Sales</p>
					</div>
					<a href="<?=site_url('dailysale/dailysale/point_sale')?>" class="btn btn-primary btn-block btn-squared margin-top-10">
						Go to Daily Sales <i class="fa fa-arrow-circle-right"></i>
					</a>
				</div>
			</div>
		</div>

		<div class="col-sm-3">
			<div class="panel panel-white no-radius text-center">
				<div class="panel-body">
					<div class="padding-10">
						<i class="fa fa-truck fa-3x text-danger"></i>
						<h3 class="step-title">Dispatch</h3>
						<p class="text-small text-muted">Product Box & DR Vouchers</p>
					</div>
					<a href="<?=site_url('dispatch/dispatch/dr')?>" class="btn btn-danger btn-block btn-squared margin-top-10">
						Go to Dispatch <i class="fa fa-arrow-circle-right"></i>
					</a>
				</div>
			</div>
		</div>

		<div class="col-sm-3">
			<div class="panel panel-white no-radius text-center">
				<div class="panel-body">
					<div class="padding-10">
						<i class="fa fa-industry fa-3x text-success"></i>
						<h3 class="step-title">Production</h3>
						<p class="text-small text-muted">Catch & Closing Stock</p>
					</div>
					<a href="<?=site_url('production/production/daily_production')?>" class="btn btn-success btn-block btn-squared margin-top-10">
						Go to Production <i class="fa fa-arrow-circle-right"></i>
					</a>
				</div>
			</div>
		</div>

		<div class="col-sm-3">
			<div class="panel panel-white no-radius text-center">
				<div class="panel-body">
					<div class="padding-10">
						<i class="fa fa-cogs fa-3x text-info"></i>
						<h3 class="step-title">Master Setup</h3>
						<p class="text-small text-muted">Clients, Fishes & Places</p>
					</div>
					<a href="<?=site_url('setup/setup/client')?>" class="btn btn-info btn-block btn-squared margin-top-10">
						Go to Setup <i class="fa fa-arrow-circle-right"></i>
					</a>
				</div>
			</div>
		</div>
	</div>

	<!-- System Overview Section -->
	<div class="row margin-top-20">
		<div class="col-md-8">
			<div class="panel panel-white no-radius">
				<div class="panel-heading border-bottom">
					<h4 class="panel-title"><i class="fa fa-list text-primary"></i> Module Quick Shortcuts</h4>
				</div>
				<div class="panel-body">
					<div class="row text-center padding-20">
						<div class="col-xs-6 col-sm-3 margin-bottom-20">
							<a href="<?=site_url('setup/setup/client')?>" class="btn btn-default btn-squared btn-block padding-15">
								<i class="fa fa-users fa-2x text-primary"></i><br>
								<span class="text-bold">Clients</span>
							</a>
						</div>
						<div class="col-xs-6 col-sm-3 margin-bottom-20">
							<a href="<?=site_url('setup/setup/all_fishes')?>" class="btn btn-default btn-squared btn-block padding-15">
								<i class="fa fa-anchor fa-2x text-info"></i><br>
								<span class="text-bold">Fishes</span>
							</a>
						</div>
						<div class="col-xs-6 col-sm-3 margin-bottom-20">
							<a href="<?=site_url('setup/setup/market_place')?>" class="btn btn-default btn-squared btn-block padding-15">
								<i class="fa fa-map-marker fa-2x text-danger"></i><br>
								<span class="text-bold">Market Places</span>
							</a>
						</div>
						<div class="col-xs-6 col-sm-3 margin-bottom-20">
							<a href="<?=site_url('reports/reports/dr_summary')?>" class="btn btn-default btn-squared btn-block padding-15">
								<i class="fa fa-bar-chart fa-2x text-success"></i><br>
								<span class="text-bold">DR Summary</span>
							</a>
						</div>
						<div class="col-xs-6 col-sm-3 margin-bottom-20">
							<a href="<?=site_url('reports/stock_report/closing_stock')?>" class="btn btn-default btn-squared btn-block padding-15">
								<i class="fa fa-cubes fa-2x text-warning"></i><br>
								<span class="text-bold">Stock Report</span>
							</a>
						</div>
						<div class="col-xs-6 col-sm-3 margin-bottom-20">
							<a href="<?=site_url('setup/administration/administrators')?>" class="btn btn-default btn-squared btn-block padding-15">
								<i class="fa fa-user-secret fa-2x text-dark"></i><br>
								<span class="text-bold">Users</span>
							</a>
						</div>
						<div class="col-xs-6 col-sm-3 margin-bottom-20">
							<a href="<?=site_url('database/database/setting')?>" class="btn btn-default btn-squared btn-block padding-15">
								<i class="fa fa-database fa-2x text-primary"></i><br>
								<span class="text-bold">DB Settings</span>
							</a>
						</div>
						<div class="col-xs-6 col-sm-3 margin-bottom-20">
							<a href="<?=site_url('database/database/start_backup')?>" class="btn btn-default btn-squared btn-block padding-15">
								<i class="fa fa-download fa-2x text-success"></i><br>
								<span class="text-bold">DB Backup</span>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-md-4">
			<div class="panel panel-white no-radius">
				<div class="panel-heading border-bottom">
					<h4 class="panel-title"><i class="fa fa-info-circle text-info"></i> System Information</h4>
				</div>
				<div class="panel-body">
					<ul class="list-group">
						<li class="list-group-item">
							<span class="text-bold">Database:</span> sales_gandhisagar
						</li>
						<li class="list-group-item">
							<span class="text-bold">Active User:</span> <?=loginUserInfo('full_name')?>
						</li>
						<li class="list-group-item">
							<span class="text-bold">Role:</span> Super Admin
						</li>
						<li class="list-group-item">
							<span class="text-bold">Session Year:</span> <?=(defined('SESSION_YEAR') ? SESSION_YEAR : date('Y'))?>
						</li>
						<li class="list-group-item">
							<span class="text-bold">Server Status:</span> <span class="label label-success">Online (Docker)</span>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>