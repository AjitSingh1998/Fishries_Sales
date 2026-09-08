<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$db_prefix = 'psac_';
/* ---------------------------------------------------------- NEW  ---------------------------------------------------------------------------------- */
defined('USERS_ACTIVITY_LOG') 					OR define('USERS_ACTIVITY_LOG', $db_prefix.'activity_log');

defined('ADMIN_PAGE_MENU') 						OR define('ADMIN_PAGE_MENU', 	$db_prefix.'admin_page_menu');
defined('ADMINISTRATOR')				  		OR define('ADMINISTRATOR', 		$db_prefix.'administrator');
defined('ADMINISTRATOR_GROUP') 			  		OR define('ADMINISTRATOR_GROUP',$db_prefix.'administrator_group');
defined('ADMINISTRATOR_GROUP_PERMISSION') 		OR define('ADMINISTRATOR_GROUP_PERMISSION', $db_prefix.'administrator_group_permission');
defined('ADVANCEFISHERMAN') 					OR define('ADVANCEFISHERMAN', 	$db_prefix.'advancefisherman');
defined('API_ACCESS')	 						OR define('API_ACCESS',		 	$db_prefix.'api_access');
defined('API_KEYS')		 						OR define('API_KEYS',		 	$db_prefix.'api_keys');
defined('API_LIMITS')	 						OR define('API_LIMITS',		 	$db_prefix.'api_limits');
defined('API_LOGS')		 						OR define('API_LOGS',		 	$db_prefix.'api_logs');

defined('BUSINESS_DETAIL') 						OR define('BUSINESS_DETAIL', 	$db_prefix.'business_detail');
defined('BOX_NUMBER') 							OR define('BOX_NUMBER', 		$db_prefix.'box_number');

defined('CASH_RECEIVED') 						OR define('CASH_RECEIVED', 		$db_prefix.'cash_received');
defined('CLIENT') 								OR define('CLIENT', 			$db_prefix.'client');
defined('CLOSING_STOCK') 						OR define('CLOSING_STOCK', 		$db_prefix.'closing_stock');
defined('CLOSING_STOCK_ITEMS') 					OR define('CLOSING_STOCK_ITEMS',$db_prefix.'closing_stock_items');

defined('DESTROYED') 							OR define('DESTROYED', 			$db_prefix.'destroyed');
defined('DESTROYED_ITEMS') 						OR define('DESTROYED_ITEMS', 	$db_prefix.'destroyed_items');
defined('DAILY_CLOSING_STOCK') 					OR define('DAILY_CLOSING_STOCK', 	$db_prefix.'daily_closing_stock');
defined('DAILY_CLOSING_STOCK_BOX') 					OR define('DAILY_CLOSING_STOCK_BOX', 	$db_prefix.'daily_closing_stock_box');

defined('FREE_SALE') 							OR define('FREE_SALE', 			$db_prefix.'free_sale');
defined('FREE_SALE_ITEMS') 						OR define('FREE_SALE_ITEMS', 	$db_prefix.'free_sale_items');
defined('FISH_CATEGORY') 						OR define('FISH_CATEGORY', 		$db_prefix.'fish_category');
defined('FISH') 								OR define('FISH', 				$db_prefix.'fish');

defined('MARKET_TYPE') 							OR define('MARKET_TYPE', 		$db_prefix.'market_type');
defined('MARKET_CATEGORY') 						OR define('MARKET_CATEGORY', 	$db_prefix.'market_category');
defined('MARKET_PLACE') 						OR define('MARKET_PLACE', 		$db_prefix.'market_place');

defined('PRODUCTION') 							OR define('PRODUCTION', 		$db_prefix.'production');
defined('PRODUCTION_CARRET') 					OR define('PRODUCTION_CARRET', 	$db_prefix.'production_carret');
defined('PRODUCTION_CARRET_ITEMS') 				OR define('PRODUCTION_CARRET_ITEMS', $db_prefix.'production_carret_items');

defined('PARTICULARS') 							OR define('PARTICULARS', 		$db_prefix.'particulars');

defined('PRODUCT_DR') 							OR define('PRODUCT_DR', 		$db_prefix.'product_dr');
defined('PRODUCT_BOX') 							OR define('PRODUCT_BOX', 		$db_prefix.'product_box');
defined('PRODUCT_BOX_ITEMS') 					OR define('PRODUCT_BOX_ITEMS', 	$db_prefix.'product_box_items');
defined('PRODUCT_DISPATCH') 					OR define('PRODUCT_DISPATCH', 	$db_prefix.'product_dispatch');
defined('PRODUCT_DISPATCH_BOX') 				OR define('PRODUCT_DISPATCH_BOX',$db_prefix.'product_dispatch_box');

defined('SALE_TYPE') 							OR define('SALE_TYPE', 			$db_prefix.'sale_type');
defined('SETTINGS') 							OR define('SETTINGS', 			$db_prefix.'settings');
defined('SALE') 								OR define('SALE', 				$db_prefix.'sale');
defined('SALE_ITEMS') 							OR define('SALE_ITEMS', 		$db_prefix.'sale_items');
defined('SALE_EXPENDITURE') 					OR define('SALE_EXPENDITURE', 	$db_prefix.'sale_expenditure');
defined('SALE_CHALLAN_DETAIL') 					OR define('SALE_CHALLAN_DETAIL', $db_prefix.'sale_challan_detail');
defined('DATABASE_BACKUP') 						OR define('DATABASE_BACKUP', 	$db_prefix.'database_backup');
defined('DATABASE_BACKUP_TABLES') 				OR define('DATABASE_BACKUP_TABLES', $db_prefix.'database_backup_tables');
defined('DATABASE_BACKUP_YEARLY')				OR define('DATABASE_BACKUP_YEARLY', $db_prefix.'database_backup_yearly');
defined('DATABASE_SETTING') 					OR define('DATABASE_SETTING', 	$db_prefix.'database_setting');

defined('FISHERMAN') 							OR define('FISHERMAN', 			$db_prefix.'fisherman');
defined('SECONDARYFISHERMAN') 					OR define('SECONDARYFISHERMAN', $db_prefix.'secondaryfisherman');
defined('MAINGROUP') 							OR define('MAINGROUP', 			$db_prefix.'maingroup');
defined('MAINGROUP_TYPE') 						OR define('MAINGROUP_TYPE', 	$db_prefix.'maingroup_type');

defined('WAGES') 								OR define('WAGES', 				$db_prefix.'wages');
defined('WAGES_ADVANCE') 						OR define('WAGES_ADVANCE', 		$db_prefix.'wages_advance');
defined('WAGESITEM') 							OR define('WAGESITEM', 			$db_prefix.'wagesitem');

defined('PRODUCT_INWARD') 						OR define('PRODUCT_INWARD', 	$db_prefix.'product_inward');
defined('PRODUCT_INWARD_RETURN') 				OR define('PRODUCT_INWARD_RETURN', $db_prefix.'product_inward_return');
defined('PRODUCT_OUTWARD') 						OR define('PRODUCT_OUTWARD', 	$db_prefix.'product_outward');
defined('PRODUCT_OUTWARD_ITEM') 				OR define('PRODUCT_OUTWARD_ITEM', $db_prefix.'product_outward_item');
defined('PRODUCT_OUTWARD_ITEM_RETURN') 			OR define('PRODUCT_OUTWARD_ITEM_RETURN', $db_prefix.'product_outward_item_return');

defined('LIABILITY_DEDUCTION') 					OR define('LIABILITY_DEDUCTION', $db_prefix.'liability_deduction');
defined('TRANSFERRED_LIABILITY') 				OR define('TRANSFERRED_LIABILITY', $db_prefix.'transferred_liability');
defined('CASH_DEPOSITED_PAYMENT') 				OR define('CASH_DEPOSITED_PAYMENT', $db_prefix.'cash_deposited_payment');

defined('DAILY_PRODUCTION') 					OR define('DAILY_PRODUCTION', 	$db_prefix.'daily_production');
defined('EXPENDITURE') 							OR define('EXPENDITURE', 		$db_prefix.'expenditure');
defined('FISHINGPOINTS') 						OR define('FISHINGPOINTS', 		$db_prefix.'fishingpoints');
defined('ASSOCIATIONS') 						OR define('ASSOCIATIONS', 		$db_prefix.'associations');
defined('STAFF_GROUPS') 						OR define('STAFF_GROUPS', 		$db_prefix.'staff_groups');
defined('MEDIA_CATEGORY') 						OR define('MEDIA_CATEGORY', 	$db_prefix.'media_category');
defined('MEDIA_IMAGES') 						OR define('MEDIA_IMAGES', 		$db_prefix.'media_images');
defined('MEDIA_VIDEOS') 						OR define('MEDIA_VIDEOS', 		$db_prefix.'media_videos');
defined('TRUNCATE') 							OR define('TRUNCATE', 			$db_prefix.'truncate');
defined('SYNC_TABLE_INFO') 						OR define('SYNC_TABLE_INFO', 	$db_prefix.'sync_table_info');
defined('SYNC_TABLE_HISTORY') 					OR define('SYNC_TABLE_HISTORY', 	$db_prefix.'sync_table_history');
