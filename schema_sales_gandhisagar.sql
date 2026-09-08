-- ====================================================================
-- Database Schema for sales_gandhisagar
-- Application: Simran Fisheries Pvt. Ltd. (Gandhisagar Sales & Supply Chain)
-- Generated automatically from codebase analysis
-- Prefix: psac_
-- ====================================================================

CREATE DATABASE IF NOT EXISTS `sales_gandhisagar` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `sales_gandhisagar`;

SET FOREIGN_KEY_CHECKS=0;

-- --------------------------------------------------------
-- Table structure for psac_activity_log
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `psac_activity_log` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) DEFAULT NULL,
  `ip_address` VARCHAR(50) DEFAULT NULL,
  `activity` VARCHAR(255) DEFAULT NULL,
  `created_date` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------
-- Table structure for psac_admin_page_menu
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `psac_admin_page_menu` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `menu_id` INT(11) DEFAULT NULL,
  `page_title` VARCHAR(255) DEFAULT NULL,
  `content_view` VARCHAR(255) DEFAULT NULL,
  `parent_id` INT(11) DEFAULT '0',
  `parents` TEXT DEFAULT NULL,
  `items` TEXT DEFAULT NULL,
  `group_id` INT(11) DEFAULT NULL,
  `actions` TEXT DEFAULT NULL,
  `source` VARCHAR(100) DEFAULT NULL,
  `action_microtime` VARCHAR(50) DEFAULT NULL,
  `added_by` INT(11) DEFAULT NULL,
  `added_date` DATETIME DEFAULT NULL,
  `status` ENUM('Active','Inactive','Deleted') DEFAULT 'Active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------
-- Table structure for psac_administrator
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `psac_administrator` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `group_id` INT(11) NOT NULL DEFAULT '1',
  `first_name` VARCHAR(100) DEFAULT NULL,
  `last_name` VARCHAR(100) DEFAULT NULL,
  `username` VARCHAR(100) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `email` VARCHAR(150) DEFAULT NULL,
  `phone` VARCHAR(50) DEFAULT NULL,
  `market_id` INT(11) DEFAULT NULL,
  `depot_id` INT(11) DEFAULT NULL,
  `created_date` DATETIME DEFAULT NULL,
  `status` ENUM('Active','Inactive','Deleted') DEFAULT 'Active',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------
-- Table structure for psac_administrator_group
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `psac_administrator_group` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `group_name` VARCHAR(100) NOT NULL,
  `description` VARCHAR(255) DEFAULT NULL,
  `status` ENUM('Active','Inactive','Deleted') DEFAULT 'Active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------
-- Table structure for psac_administrator_group_permission
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `psac_administrator_group_permission` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `group_id` INT(11) NOT NULL,
  `menu_id` INT(11) NOT NULL,
  `actions` TEXT DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------
-- Table structure for psac_advancefisherman / psac_wages_advance
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `psac_advancefisherman` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `Fisherman` INT(11) DEFAULT NULL,
  `maingroup_id` INT(11) DEFAULT NULL,
  `advance_to` ENUM('Fisherman','Group') DEFAULT 'Fisherman',
  `Amount` DECIMAL(12,2) DEFAULT '0.00',
  `advance_date` DATE DEFAULT NULL,
  `remark` TEXT DEFAULT NULL,
  `added_by` INT(11) DEFAULT NULL,
  `added_date` DATETIME DEFAULT NULL,
  `status` ENUM('Active','Inactive','Deleted') DEFAULT 'Active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `psac_wages_advance` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `Fisherman` INT(11) DEFAULT NULL,
  `maingroup_id` INT(11) DEFAULT NULL,
  `advance_to` ENUM('Fisherman','Group') DEFAULT 'Fisherman',
  `Amount` DECIMAL(12,2) DEFAULT '0.00',
  `advance_date` DATE DEFAULT NULL,
  `remark` TEXT DEFAULT NULL,
  `added_by` INT(11) DEFAULT NULL,
  `added_date` DATETIME DEFAULT NULL,
  `status` ENUM('Active','Inactive','Deleted') DEFAULT 'Active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------
-- Table structure for API tables
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `psac_api_access` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `key` VARCHAR(40) NOT NULL,
  `all_access` TINYINT(1) NOT NULL DEFAULT '0',
  `controller` VARCHAR(50) NOT NULL,
  `date_created` DATETIME DEFAULT NULL,
  `date_modified` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `psac_api_keys` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `key` VARCHAR(40) NOT NULL,
  `level` INT(2) NOT NULL,
  `ignore_limits` TINYINT(1) NOT NULL DEFAULT '0',
  `is_private_key` TINYINT(1) NOT NULL DEFAULT '0',
  `ip_addresses` TEXT DEFAULT NULL,
  `date_created` INT(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `psac_api_limits` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `uri` VARCHAR(255) NOT NULL,
  `count` INT(10) NOT NULL,
  `hour_started` INT(11) NOT NULL,
  `api_key` VARCHAR(40) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `psac_api_logs` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `uri` VARCHAR(255) NOT NULL,
  `method` VARCHAR(6) NOT NULL,
  `params` TEXT DEFAULT NULL,
  `api_key` VARCHAR(40) NOT NULL,
  `ip_address` VARCHAR(45) NOT NULL,
  `time` INT(11) NOT NULL,
  `rtime` FLOAT DEFAULT NULL,
  `authorized` VARCHAR(1) NOT NULL,
  `response_code` SMALLINT(3) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------
-- Table structure for psac_associations
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `psac_associations` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `firstName` VARCHAR(100) DEFAULT NULL,
  `lastName` VARCHAR(100) DEFAULT NULL,
  `phone_number` VARCHAR(50) DEFAULT NULL,
  `city` VARCHAR(100) DEFAULT NULL,
  `registerDate` DATE DEFAULT NULL,
  `status` ENUM('Active','Inactive','Deleted') DEFAULT 'Active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------
-- Table structure for psac_box_number
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `psac_box_number` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `box_number` VARCHAR(50) NOT NULL,
  `status` ENUM('Active','Inactive','Deleted') DEFAULT 'Active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------
-- Table structure for psac_business_detail
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `psac_business_detail` (
  `business_id` INT(11) NOT NULL AUTO_INCREMENT,
  `company_name` VARCHAR(255) DEFAULT NULL,
  `dam_name` VARCHAR(255) DEFAULT NULL,
  `address` TEXT DEFAULT NULL,
  `city` VARCHAR(100) DEFAULT NULL,
  `state` VARCHAR(100) DEFAULT NULL,
  `pincode` VARCHAR(20) DEFAULT NULL,
  `phone` VARCHAR(50) DEFAULT NULL,
  `email` VARCHAR(150) DEFAULT NULL,
  `session_year` VARCHAR(20) DEFAULT '2018-19',
  `status` ENUM('Active','Inactive','Deleted') DEFAULT 'Active',
  PRIMARY KEY (`business_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------
-- Table structure for psac_cash_deposited_payment
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `psac_cash_deposited_payment` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `fisherman_id` INT(11) DEFAULT NULL,
  `maingroup_id` INT(11) DEFAULT NULL,
  `deposited_by` ENUM('Fisherman','Group') DEFAULT 'Fisherman',
  `product_liability` DECIMAL(12,2) DEFAULT '0.00',
  `wages_liability` DECIMAL(12,2) DEFAULT '0.00',
  `deposit_date` DATE DEFAULT NULL,
  `remark` TEXT DEFAULT NULL,
  `added_by` INT(11) DEFAULT NULL,
  `added_date` DATETIME DEFAULT NULL,
  `status` ENUM('Active','Inactive','Deleted') DEFAULT 'Active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------
-- Table structure for psac_cash_received
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `psac_cash_received` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `client_id` INT(11) NOT NULL,
  `received_amount` DECIMAL(12,2) DEFAULT '0.00',
  `payment_mode` VARCHAR(50) DEFAULT 'Cash',
  `reference_number` VARCHAR(100) DEFAULT NULL,
  `payment_date` DATE DEFAULT NULL,
  `remark` TEXT DEFAULT NULL,
  `added_by` INT(11) DEFAULT NULL,
  `added_date` DATETIME DEFAULT NULL,
  `status` ENUM('Active','Inactive','Deleted') DEFAULT 'Active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------
-- Table structure for psac_client / psac_fisherman
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `psac_client` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `code` VARCHAR(50) DEFAULT NULL,
  `company_name` VARCHAR(255) NOT NULL,
  `trademark_name` VARCHAR(255) DEFAULT NULL,
  `contact_number` VARCHAR(50) DEFAULT NULL,
  `contact_number2` VARCHAR(50) DEFAULT NULL,
  `email` VARCHAR(150) DEFAULT NULL,
  `address` TEXT DEFAULT NULL,
  `city` VARCHAR(100) DEFAULT NULL,
  `state` VARCHAR(100) DEFAULT NULL,
  `country` VARCHAR(100) DEFAULT NULL,
  `market_type` INT(11) DEFAULT NULL,
  `maingroup` INT(11) DEFAULT NULL,
  `opening_balance` DECIMAL(12,2) DEFAULT '0.00',
  `products_balance` DECIMAL(12,2) DEFAULT '0.00',
  `wages_balance` DECIMAL(12,2) DEFAULT '0.00',
  `remark` TEXT DEFAULT NULL,
  `added_by` INT(11) DEFAULT NULL,
  `added_date` DATETIME DEFAULT NULL,
  `status` ENUM('Active','Inactive','Deleted') DEFAULT 'Active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `psac_fisherman` (
  `ID` INT(11) NOT NULL AUTO_INCREMENT,
  `Code` VARCHAR(50) DEFAULT NULL,
  `Name` VARCHAR(255) NOT NULL,
  `MainGroup` INT(11) DEFAULT NULL,
  `ContactNumber` VARCHAR(50) DEFAULT NULL,
  `Address` TEXT DEFAULT NULL,
  `products_balance` DECIMAL(12,2) DEFAULT '0.00',
  `wages_balance` DECIMAL(12,2) DEFAULT '0.00',
  `status` ENUM('Active','Inactive','Deleted') DEFAULT 'Active',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `psac_secondaryfisherman` (
  `ID` INT(11) NOT NULL AUTO_INCREMENT,
  `Primary` INT(11) NOT NULL,
  `Secondary` INT(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------
-- Table structure for Inventory Closing Stock tables
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `psac_closing_stock` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `stock_date` DATE NOT NULL,
  `depot_id` INT(11) DEFAULT NULL,
  `market_id` INT(11) DEFAULT NULL,
  `total_weight` DECIMAL(12,2) DEFAULT '0.00',
  `added_by` INT(11) DEFAULT NULL,
  `added_date` DATETIME DEFAULT NULL,
  `status` ENUM('Active','Inactive','Deleted') DEFAULT 'Active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `psac_closing_stock_items` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `stock_id` INT(11) NOT NULL,
  `fish_code` VARCHAR(50) DEFAULT NULL,
  `quantity` DECIMAL(12,2) DEFAULT '0.00',
  `weight` DECIMAL(12,2) DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `psac_daily_closing_stock` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `stock_date` DATE NOT NULL,
  `depot_id` INT(11) DEFAULT NULL,
  `market_id` INT(11) DEFAULT NULL,
  `fish_code` VARCHAR(50) DEFAULT NULL,
  `quantity` DECIMAL(12,2) DEFAULT '0.00',
  `weight` DECIMAL(12,2) DEFAULT '0.00',
  `added_by` INT(11) DEFAULT NULL,
  `added_date` DATETIME DEFAULT NULL,
  `status` ENUM('Active','Inactive','Deleted') DEFAULT 'Active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `psac_daily_closing_stock_box` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `stock_id` INT(11) NOT NULL,
  `box_number` VARCHAR(50) DEFAULT NULL,
  `weight` DECIMAL(12,2) DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------
-- Table structure for psac_daily_production & psac_production
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `psac_daily_production` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `production_date` DATE NOT NULL,
  `depot_id` INT(11) DEFAULT NULL,
  `market_id` INT(11) DEFAULT NULL,
  `fish_code` VARCHAR(50) DEFAULT NULL,
  `fish_quantity` DECIMAL(12,2) DEFAULT '0.00',
  `fish_weight` DECIMAL(12,2) DEFAULT '0.00',
  `added_by` INT(11) DEFAULT NULL,
  `added_date` DATETIME DEFAULT NULL,
  `status` ENUM('Active','Inactive','Deleted') DEFAULT 'Active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `psac_production` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `production_id` VARCHAR(50) DEFAULT NULL,
  `production_date` DATE DEFAULT NULL,
  `depot_id` INT(11) DEFAULT NULL,
  `market_id` INT(11) DEFAULT NULL,
  `client_id` INT(11) DEFAULT NULL,
  `fish_code` VARCHAR(50) DEFAULT NULL,
  `fish_name` VARCHAR(100) DEFAULT NULL,
  `fish_type` VARCHAR(50) DEFAULT NULL,
  `fish_qty` DECIMAL(12,2) DEFAULT '0.00',
  `fish_wt` DECIMAL(12,2) DEFAULT '0.00',
  `fish_rate` DECIMAL(12,2) DEFAULT '0.00',
  `total_wt` DECIMAL(12,2) DEFAULT '0.00',
  `point_wt` DECIMAL(12,2) DEFAULT '0.00',
  `depot_wt` DECIMAL(12,2) DEFAULT '0.00',
  `surplus_wt` DECIMAL(12,2) DEFAULT '0.00',
  `carret_quantity` INT(11) DEFAULT '0',
  `carret_weight` DECIMAL(12,2) DEFAULT '0.00',
  `box_number` VARCHAR(50) DEFAULT NULL,
  `box_wt` DECIMAL(12,2) DEFAULT '0.00',
  `remark` TEXT DEFAULT NULL,
  `added_by` INT(11) DEFAULT NULL,
  `added_date` DATETIME DEFAULT NULL,
  `updated_by` INT(11) DEFAULT NULL,
  `updated_date` DATETIME DEFAULT NULL,
  `status` ENUM('Active','Inactive','Deleted') DEFAULT 'Active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `psac_production_carret` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `production_id` INT(11) NOT NULL,
  `carret_number` VARCHAR(50) DEFAULT NULL,
  `carret_weight` DECIMAL(12,2) DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `psac_production_carret_items` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `carret_id` INT(11) NOT NULL,
  `fish_code` VARCHAR(50) DEFAULT NULL,
  `fish_qty` DECIMAL(12,2) DEFAULT '0.00',
  `fish_wt` DECIMAL(12,2) DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------
-- Table structure for Database Backup & Setting tables
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `psac_database_backup` (
  `backup_id` INT(11) NOT NULL AUTO_INCREMENT,
  `backup_dbname` VARCHAR(100) NOT NULL,
  `display_name` VARCHAR(150) DEFAULT NULL,
  `backup_type` ENUM('Weekly','Yearly') DEFAULT 'Weekly',
  `backup_path` VARCHAR(255) DEFAULT NULL,
  `backup_date` DATETIME DEFAULT NULL,
  `backup_by` INT(11) DEFAULT NULL,
  `status` ENUM('Active','Inactive','Deleted') DEFAULT 'Active',
  PRIMARY KEY (`backup_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `psac_database_backup_tables` (
  `table_id` INT(11) NOT NULL AUTO_INCREMENT,
  `backup_id` INT(11) NOT NULL,
  `table_name` VARCHAR(100) NOT NULL,
  `file_path` VARCHAR(255) DEFAULT NULL,
  `backup_date` DATETIME DEFAULT NULL,
  PRIMARY KEY (`table_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `psac_database_backup_yearly` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `dbname` VARCHAR(100) NOT NULL,
  `display_name` VARCHAR(150) DEFAULT NULL,
  `session_year` VARCHAR(20) DEFAULT NULL,
  `created_date` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `psac_database_setting` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `admin_id` INT(11) NOT NULL,
  `db_id` INT(11) NOT NULL,
  `assign_date` DATETIME DEFAULT NULL,
  `assign_by` INT(11) DEFAULT NULL,
  `status` ENUM('Active','Inactive','Deleted') DEFAULT 'Active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------
-- Table structure for psac_depot
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `psac_depot` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `code` VARCHAR(50) DEFAULT NULL,
  `name` VARCHAR(150) NOT NULL,
  `location` VARCHAR(255) DEFAULT NULL,
  `status` ENUM('Active','Inactive','Deleted') DEFAULT 'Active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------
-- Table structure for psac_destroyed & psac_destroyed_items
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `psac_destroyed` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `record_date` DATE NOT NULL,
  `depot_id` INT(11) DEFAULT NULL,
  `market_id` INT(11) DEFAULT NULL,
  `total_weight` DECIMAL(12,2) DEFAULT '0.00',
  `added_by` INT(11) DEFAULT NULL,
  `added_date` DATETIME DEFAULT NULL,
  `status` ENUM('Active','Inactive','Deleted') DEFAULT 'Active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `psac_destroyed_items` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `destroyed_id` INT(11) NOT NULL,
  `fish_code` VARCHAR(50) DEFAULT NULL,
  `weight` DECIMAL(12,2) DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------
-- Table structure for psac_email_templates
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `psac_email_templates` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `task` VARCHAR(100) NOT NULL,
  `subject` VARCHAR(255) NOT NULL,
  `body` TEXT NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------
-- Table structure for psac_fish & psac_fish_category
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `psac_fish_category` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `code` VARCHAR(50) DEFAULT NULL,
  `name` VARCHAR(150) NOT NULL,
  `editable` TINYINT(1) DEFAULT '1',
  `status` ENUM('Active','Inactive','Deleted') DEFAULT 'Active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `psac_fish` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `category_id` INT(11) DEFAULT NULL,
  `code` VARCHAR(50) DEFAULT NULL,
  `name` VARCHAR(150) NOT NULL,
  `type` ENUM('Fresh','Rotten','Destroyed') DEFAULT 'Fresh',
  `fish_rate` DECIMAL(12,2) DEFAULT '0.00',
  `dhalta` DECIMAL(12,2) DEFAULT '0.00',
  `status` ENUM('Active','Inactive','Deleted') DEFAULT 'Active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------
-- Table structure for psac_fishingpoints
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `psac_fishingpoints` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(150) NOT NULL,
  `code` VARCHAR(50) DEFAULT NULL,
  `status` ENUM('Active','Inactive','Deleted') DEFAULT 'Active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------
-- Table structure for psac_free_sale & psac_free_sale_items
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `psac_free_sale` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `invoice_number` VARCHAR(50) DEFAULT NULL,
  `free_sale_date` DATE DEFAULT NULL,
  `client_id` INT(11) DEFAULT NULL,
  `company_name` VARCHAR(255) DEFAULT NULL,
  `contact_number` VARCHAR(50) DEFAULT NULL,
  `email` VARCHAR(150) DEFAULT NULL,
  `total_quantity` DECIMAL(12,2) DEFAULT '0.00',
  `total_weight` DECIMAL(12,2) DEFAULT '0.00',
  `received_amount` DECIMAL(12,2) DEFAULT '0.00',
  `market_type` INT(11) DEFAULT NULL,
  `market_category` INT(11) DEFAULT NULL,
  `market_id` INT(11) DEFAULT NULL,
  `remark` TEXT DEFAULT NULL,
  `added_by` INT(11) DEFAULT NULL,
  `added_date` DATETIME DEFAULT NULL,
  `updated_by` INT(11) DEFAULT NULL,
  `updated_date` DATETIME DEFAULT NULL,
  `status` ENUM('Active','Inactive','Deleted') DEFAULT 'Active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `psac_free_sale_items` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `free_sale_id` INT(11) NOT NULL,
  `fish_code` VARCHAR(50) DEFAULT NULL,
  `fish_quantity` DECIMAL(12,2) DEFAULT '0.00',
  `fish_weight` DECIMAL(12,2) DEFAULT '0.00',
  `fish_rate` DECIMAL(12,2) DEFAULT '0.00',
  `amount` DECIMAL(12,2) DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------
-- Table structure for psac_liability_deduction & psac_transferred_liability
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `psac_liability_deduction` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `deducted_for` INT(11) NOT NULL,
  `amount` DECIMAL(12,2) DEFAULT '0.00',
  `advance_deduction` DECIMAL(12,2) DEFAULT '0.00',
  `deduction_date` DATE DEFAULT NULL,
  `remark` TEXT DEFAULT NULL,
  `status` ENUM('Active','Inactive','Deleted') DEFAULT 'Active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `psac_transferred_liability` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `from_fisherman` INT(11) NOT NULL,
  `to_fisherman` INT(11) NOT NULL,
  `amount` DECIMAL(12,2) DEFAULT '0.00',
  `transfer_date` DATE DEFAULT NULL,
  `remark` TEXT DEFAULT NULL,
  `status` ENUM('Active','Inactive','Deleted') DEFAULT 'Active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------
-- Table structure for psac_maingroup & psac_maingroup_type
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `psac_maingroup_type` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `type_name` VARCHAR(100) NOT NULL,
  `status` ENUM('Active','Inactive','Deleted') DEFAULT 'Active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `psac_maingroup` (
  `ID` INT(11) NOT NULL AUTO_INCREMENT,
  `Name` VARCHAR(255) NOT NULL,
  `Type` INT(11) DEFAULT NULL,
  `product_balance` DECIMAL(12,2) DEFAULT '0.00',
  `wages_balance` DECIMAL(12,2) DEFAULT '0.00',
  `status` ENUM('Active','Inactive','Deleted') DEFAULT 'Active',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------
-- Table structure for Market Places, Types & Categories
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `psac_market_type` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `code` VARCHAR(50) DEFAULT NULL,
  `name` VARCHAR(150) NOT NULL,
  `editable` TINYINT(1) DEFAULT '1',
  `status` ENUM('Active','Inactive','Deleted') DEFAULT 'Active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `psac_market_category` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `market_type` INT(11) NOT NULL,
  `code` VARCHAR(50) DEFAULT NULL,
  `name` VARCHAR(150) NOT NULL,
  `status` ENUM('Active','Inactive','Deleted') DEFAULT 'Active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `psac_market_place` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `market_type` INT(11) NOT NULL,
  `market_category` INT(11) NOT NULL,
  `code` VARCHAR(50) DEFAULT NULL,
  `name` VARCHAR(150) NOT NULL,
  `added_by` INT(11) DEFAULT NULL,
  `added_date` DATETIME DEFAULT NULL,
  `status` ENUM('Active','Inactive','Deleted') DEFAULT 'Active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------
-- Table structure for Media & Particulars
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `psac_media_category` (
  `category_id` INT(11) NOT NULL AUTO_INCREMENT,
  `category_name` VARCHAR(150) NOT NULL,
  `category_alias` VARCHAR(150) DEFAULT NULL,
  `category_for` ENUM('Photo','Video') DEFAULT 'Photo',
  `created_date` DATETIME DEFAULT NULL,
  `status` ENUM('Active','Inactive','Deleted') DEFAULT 'Active',
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `psac_media_images` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `category_id` INT(11) NOT NULL,
  `caption` VARCHAR(255) DEFAULT NULL,
  `image_path` VARCHAR(255) NOT NULL,
  `created_date` DATETIME DEFAULT NULL,
  `status` ENUM('Active','Inactive','Deleted') DEFAULT 'Active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `psac_media_videos` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `category_id` INT(11) NOT NULL,
  `caption` VARCHAR(255) DEFAULT NULL,
  `image_path` VARCHAR(255) DEFAULT NULL,
  `youtube_url` VARCHAR(255) DEFAULT NULL,
  `created_date` DATETIME DEFAULT NULL,
  `status` ENUM('Active','Inactive','Deleted') DEFAULT 'Active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `psac_particulars` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `particular` VARCHAR(255) NOT NULL,
  `status` ENUM('Active','Inactive','Deleted') DEFAULT 'Active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------
-- Table structure for Dispatch, Box & Inward/Outward Supply Chain
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `psac_product_box` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `box_number` VARCHAR(50) NOT NULL,
  `depot_id` INT(11) DEFAULT NULL,
  `market_id` INT(11) DEFAULT NULL,
  `fish_code` VARCHAR(50) DEFAULT NULL,
  `box_weight` DECIMAL(12,2) DEFAULT '0.00',
  `carret_weight` DECIMAL(12,2) DEFAULT '0.00',
  `box_status` VARCHAR(50) DEFAULT 'Packed',
  `packing_date` DATE DEFAULT NULL,
  `added_by` INT(11) DEFAULT NULL,
  `added_date` DATETIME DEFAULT NULL,
  `status` ENUM('Active','Inactive','Deleted') DEFAULT 'Active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `psac_product_box_items` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `box_id` INT(11) NOT NULL,
  `fish_code` VARCHAR(50) DEFAULT NULL,
  `fish_qty` DECIMAL(12,2) DEFAULT '0.00',
  `fish_wt` DECIMAL(12,2) DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `psac_product_dispatch` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `dr_number` VARCHAR(50) DEFAULT NULL,
  `dispatch_date` DATE DEFAULT NULL,
  `dispatch_from` INT(11) DEFAULT NULL,
  `dispatch_to` INT(11) DEFAULT NULL,
  `market_id` INT(11) DEFAULT NULL,
  `vehicle_number` VARCHAR(50) DEFAULT NULL,
  `driver_name` VARCHAR(100) DEFAULT NULL,
  `driver_mobile_no` VARCHAR(50) DEFAULT NULL,
  `total_box_qty` INT(11) DEFAULT '0',
  `total_box_wt` DECIMAL(12,2) DEFAULT '0.00',
  `advance_freight` DECIMAL(12,2) DEFAULT '0.00',
  `remaining_freight` DECIMAL(12,2) DEFAULT '0.00',
  `total_freight` DECIMAL(12,2) DEFAULT '0.00',
  `dispatch_status` VARCHAR(50) DEFAULT 'Dispatched',
  `remark` TEXT DEFAULT NULL,
  `added_by` INT(11) DEFAULT NULL,
  `added_date` DATETIME DEFAULT NULL,
  `status` ENUM('Active','Inactive','Deleted') DEFAULT 'Active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `psac_product_dispatch_box` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `dispatch_id` INT(11) NOT NULL,
  `box_number` VARCHAR(50) NOT NULL,
  `box_weight` DECIMAL(12,2) DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `psac_product_dr` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `dr_number` VARCHAR(50) NOT NULL,
  `dr_date` DATE DEFAULT NULL,
  `depot_id` INT(11) DEFAULT NULL,
  `market_id` INT(11) DEFAULT NULL,
  `status` ENUM('Active','Inactive','Deleted') DEFAULT 'Active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `psac_product_inward` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `inward_date` DATE NOT NULL,
  `depot_id` INT(11) DEFAULT NULL,
  `fisherman_id` INT(11) DEFAULT NULL,
  `grand_total` DECIMAL(12,2) DEFAULT '0.00',
  `added_by` INT(11) DEFAULT NULL,
  `added_date` DATETIME DEFAULT NULL,
  `status` ENUM('Active','Inactive','Deleted') DEFAULT 'Active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `psac_product_inward_return` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `inward_id` INT(11) NOT NULL,
  `return_date` DATE DEFAULT NULL,
  `total_price` DECIMAL(12,2) DEFAULT '0.00',
  `status` ENUM('Active','Inactive','Deleted') DEFAULT 'Active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `psac_product_outward` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `outward_date` DATE NOT NULL,
  `outward_to` ENUM('Fisherman','Group') DEFAULT 'Fisherman',
  `fisherman_id` INT(11) DEFAULT NULL,
  `main_group_id` INT(11) DEFAULT NULL,
  `grand_total` DECIMAL(12,2) DEFAULT '0.00',
  `added_by` INT(11) DEFAULT NULL,
  `added_date` DATETIME DEFAULT NULL,
  `status` ENUM('Active','Inactive','Deleted') DEFAULT 'Active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `psac_product_outward_item` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `outward_id` INT(11) NOT NULL,
  `fish_code` VARCHAR(50) DEFAULT NULL,
  `quantity` DECIMAL(12,2) DEFAULT '0.00',
  `price` DECIMAL(12,2) DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `psac_product_outward_item_return` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `outward_id` INT(11) NOT NULL,
  `fisherman_id` INT(11) DEFAULT NULL,
  `main_group_id` INT(11) DEFAULT NULL,
  `outward_to` ENUM('Fisherman','Group') DEFAULT 'Fisherman',
  `total_price` DECIMAL(12,2) DEFAULT '0.00',
  `return_date` DATE DEFAULT NULL,
  `status` ENUM('Active','Inactive','Deleted') DEFAULT 'Active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------
-- Table structure for Sales, Invoices & Expenditure
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `psac_sale` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `invoice_number` VARCHAR(50) NOT NULL,
  `dr_number` VARCHAR(50) DEFAULT NULL,
  `sale_date` DATE NOT NULL,
  `client_id` INT(11) NOT NULL,
  `client_name` VARCHAR(255) DEFAULT NULL,
  `market_type` INT(11) DEFAULT NULL,
  `market_category` INT(11) DEFAULT NULL,
  `market_id` INT(11) DEFAULT NULL,
  `gross_wt` DECIMAL(12,2) DEFAULT '0.00',
  `net_wt` DECIMAL(12,2) DEFAULT '0.00',
  `gross_sale` DECIMAL(12,2) DEFAULT '0.00',
  `net_sale` DECIMAL(12,2) DEFAULT '0.00',
  `discount_perc` DECIMAL(5,2) DEFAULT '0.00',
  `discount_amount` DECIMAL(12,2) DEFAULT '0.00',
  `commission` DECIMAL(12,2) DEFAULT '0.00',
  `ice_amount` DECIMAL(12,2) DEFAULT '0.00',
  `destroyed_amount` DECIMAL(12,2) DEFAULT '0.00',
  `total_expenses` DECIMAL(12,2) DEFAULT '0.00',
  `sub_total` DECIMAL(12,2) DEFAULT '0.00',
  `grand_total` DECIMAL(12,2) DEFAULT '0.00',
  `received_amt` DECIMAL(12,2) DEFAULT '0.00',
  `balance` DECIMAL(12,2) DEFAULT '0.00',
  `payment_mode` VARCHAR(50) DEFAULT 'Cash',
  `payment_date` DATE DEFAULT NULL,
  `remark` TEXT DEFAULT NULL,
  `added_by` INT(11) DEFAULT NULL,
  `added_date` DATETIME DEFAULT NULL,
  `updated_by` INT(11) DEFAULT NULL,
  `updated_date` DATETIME DEFAULT NULL,
  `status` ENUM('Active','Inactive','Deleted') DEFAULT 'Active',
  PRIMARY KEY (`id`),
  UNIQUE KEY `inv_market` (`invoice_number`, `market_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `psac_sale_items` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `sale_id` INT(11) NOT NULL,
  `fish_code` VARCHAR(50) DEFAULT NULL,
  `fish_quantity` DECIMAL(12,2) DEFAULT '0.00',
  `fish_weight` DECIMAL(12,2) DEFAULT '0.00',
  `fish_rate` DECIMAL(12,2) DEFAULT '0.00',
  `amount` DECIMAL(12,2) DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `psac_sale_expenditure` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `sale_id` INT(11) NOT NULL,
  `particular` VARCHAR(255) NOT NULL,
  `amount` DECIMAL(12,2) DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `psac_sale_challan_detail` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `sale_id` INT(11) NOT NULL,
  `challan_number` VARCHAR(50) DEFAULT NULL,
  `details` TEXT DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `psac_sale_markets` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `market_id` INT(11) NOT NULL,
  `status` ENUM('Active','Inactive','Deleted') DEFAULT 'Active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `psac_sale_type` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `status` ENUM('Active','Inactive','Deleted') DEFAULT 'Active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `psac_sales_typest` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `status` ENUM('Active','Inactive','Deleted') DEFAULT 'Active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------
-- Table structure for Settings, Staff & Sync tables
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `psac_settings` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `keyword` VARCHAR(100) NOT NULL,
  `value` TEXT DEFAULT NULL,
  `status` ENUM('Active','Inactive','Deleted') DEFAULT 'Active',
  PRIMARY KEY (`id`),
  UNIQUE KEY `keyword` (`keyword`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `psac_staff_groups` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `group_name` VARCHAR(100) NOT NULL,
  `created_date` DATETIME DEFAULT NULL,
  `status` ENUM('Active','Inactive','Deleted') DEFAULT 'Active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `psac_sync_table_info` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `table_name` VARCHAR(100) NOT NULL,
  `last_sync_id` INT(11) DEFAULT '0',
  `last_sync_date` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `psac_sync_table_history` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `table_name` VARCHAR(100) NOT NULL,
  `sync_count` INT(11) DEFAULT '0',
  `sync_date` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `psac_truncate` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `table_name` VARCHAR(100) NOT NULL,
  `action_date` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------
-- Table structure for Wages tables
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `psac_wages` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `wage_date` DATE NOT NULL,
  `FishermanId` INT(11) DEFAULT NULL,
  `MainGroup` INT(11) DEFAULT NULL,
  `wages_for` ENUM('Fisherman','Group') DEFAULT 'Fisherman',
  `GrossWages` DECIMAL(12,2) DEFAULT '0.00',
  `GroupLiabilityDeduction` DECIMAL(12,2) DEFAULT '0.00',
  `AdvanceWagesDeduction` DECIMAL(12,2) DEFAULT '0.00',
  `NetWages` DECIMAL(12,2) DEFAULT '0.00',
  `added_by` INT(11) DEFAULT NULL,
  `added_date` DATETIME DEFAULT NULL,
  `status` ENUM('Active','Inactive','Deleted') DEFAULT 'Active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `psac_wagesitem` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `wages_id` INT(11) NOT NULL,
  `FishermanId` INT(11) DEFAULT NULL,
  `MainGroup` INT(11) DEFAULT NULL,
  `wages_for` ENUM('Fisherman','Group') DEFAULT 'Fisherman',
  `fish_code` VARCHAR(50) DEFAULT NULL,
  `fish_weight` DECIMAL(12,2) DEFAULT '0.00',
  `rate` DECIMAL(12,2) DEFAULT '0.00',
  `amount` DECIMAL(12,2) DEFAULT '0.00',
  `AdvanceWagesDeduction` DECIMAL(12,2) DEFAULT '0.00',
  `GroupLiabilityDeduction` DECIMAL(12,2) DEFAULT '0.00',
  `status` ENUM('Active','Inactive','Deleted') DEFAULT 'Active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `psac_demo_user` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(100) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

SET FOREIGN_KEY_CHECKS=1;

-- ====================================================================
-- DEFAULT SEED DATA
-- ====================================================================

-- Insert Default Admin Group
INSERT INTO `psac_administrator_group` (`id`, `group_name`, `description`, `status`) VALUES
(1, 'Super Admin', 'Full Access Super Administrator', 'Active'),
(2, 'Manager', 'Manager Level Access', 'Active');

-- Insert Default Super Admin User (Password: admin / md5(admin) or hash)
INSERT INTO `psac_administrator` (`id`, `group_id`, `first_name`, `last_name`, `username`, `password`, `email`, `status`) VALUES
(1, 1, 'Admin', 'User', 'admin', '21232f297a57a5a743894a0e4a801fc3', 'admin@simransolvex.com', 'Active');

-- Insert Business Detail
INSERT INTO `psac_business_detail` (`business_id`, `company_name`, `dam_name`, `session_year`, `status`) VALUES
(1, 'Simran Fisheries Pvt. Ltd.', 'Gandhisagar', '2018-19', 'Active');

-- Insert Default Market Types
INSERT INTO `psac_market_type` (`id`, `code`, `name`, `editable`, `status`) VALUES
(1, 'LOCAL', 'Local Market', 1, 'Active'),
(2, 'EXPORT', 'Export Market', 1, 'Active');

-- Insert Default Settings
INSERT INTO `psac_settings` (`keyword`, `value`, `status`) VALUES
('COMPANY_NAME', 'Simran Fisheries Pvt. Ltd.', 'Active'),
('DAM_NAME', 'Gandhisagar', 'Active'),
('SESSION_YEAR', '2018-19', 'Active');

