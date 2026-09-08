-- Update Admin Groups
UPDATE psac_administrator_group SET group_id = id WHERE group_id IS NULL OR group_id = 0;

-- Clean and Refresh psac_admin_page_menu
TRUNCATE TABLE psac_admin_page_menu;

INSERT INTO psac_admin_page_menu 
(`id`, `page_menu_id`, `page_title`, `parent_id`, `status`, `menu_url`, `menu_level`, `menu_title`, `icon_class`, `menu_order`, `editable`) 
VALUES
-- 1. Dashboard
(1, 1, 'Dashboard', 0, 'Active', 'dashboard', 0, 'Dashboard', 'fa fa-dashboard', 1, 'Unlock'),

-- 2. Setup
(2, 2, 'Setup', 0, 'Active', 'setup/setup/client', 0, 'Setup', 'fa fa-cogs', 2, 'Unlock'),
(201, 201, 'Clients', 2, 'Active', 'setup/setup/client', 1, 'Clients', '', 1, 'Unlock'),
(202, 202, 'Fishes', 2, 'Active', 'setup/setup/all_fishes', 1, 'Fishes', '', 2, 'Unlock'),
(203, 203, 'Fish Category', 2, 'Active', 'setup/setup/fish_category', 1, 'Fish Category', '', 3, 'Unlock'),
(204, 204, 'Market Types', 2, 'Active', 'setup/setup/market_type', 1, 'Market Types', '', 4, 'Unlock'),
(205, 205, 'Market Categories', 2, 'Active', 'setup/setup/market_category', 1, 'Market Categories', '', 5, 'Unlock'),
(206, 206, 'Market Places', 2, 'Active', 'setup/setup/market_place', 1, 'Market Places', '', 6, 'Unlock'),
(207, 207, 'Particulars', 2, 'Active', 'setup/setup/particulars', 1, 'Particulars', '', 7, 'Unlock'),
(208, 208, 'Box Numbers', 2, 'Active', 'setup/administration/box_number', 1, 'Box Numbers', '', 8, 'Unlock'),
(209, 209, 'Administrators', 2, 'Active', 'setup/administration/administrators', 1, 'Administrators', '', 9, 'Unlock'),
(210, 210, 'Admin Groups', 2, 'Active', 'setup/administration/admin_group', 1, 'Admin Groups', '', 10, 'Unlock'),
(211, 211, 'Company Details', 2, 'Active', 'setup/administration/company_details', 1, 'Company Details', '', 11, 'Unlock'),

-- 3. Daily Sales
(3, 3, 'Daily Sales', 0, 'Active', 'dailysale/dailysale/point_sale', 0, 'Daily Sales', 'fa fa-shopping-cart', 3, 'Unlock'),
(301, 301, 'Point Sales', 3, 'Active', 'dailysale/dailysale/point_sale', 1, 'Point Sales', '', 1, 'Unlock'),
(302, 302, 'Depot Sales', 3, 'Active', 'dailysale/dailysale/depot_sale', 1, 'Depot Sales', '', 2, 'Unlock'),
(303, 303, 'Outside Sales', 3, 'Active', 'dailysale/dailysale/outside_sale', 1, 'Outside Sales', '', 3, 'Unlock'),
(304, 304, 'Free Sales', 3, 'Active', 'dailysale/dailysale/free_sale', 1, 'Free Sales', '', 4, 'Unlock'),
(305, 305, 'Cash Received', 3, 'Active', 'dailysale/dailysale/cash_deposit', 1, 'Cash Received', '', 5, 'Unlock'),
(306, 306, 'Expenditure', 3, 'Active', 'dailysale/dailysale/expenditure', 1, 'Expenditure', '', 6, 'Unlock'),

-- 4. Dispatch
(4, 4, 'Dispatch', 0, 'Active', 'dispatch/dispatch/dr', 0, 'Dispatch', 'fa fa-truck', 4, 'Unlock'),
(401, 401, 'Product Dispatch (DR)', 4, 'Active', 'dispatch/dispatch/dr', 1, 'Product Dispatch (DR)', '', 1, 'Unlock'),
(402, 402, 'Dispatched Vouchers', 4, 'Active', 'dispatch/dispatch/dispatched', 1, 'Dispatched Vouchers', '', 2, 'Unlock'),
(403, 403, 'Prepare Box', 4, 'Active', 'dispatch/dispatch/prepare_box', 1, 'Prepare Box', '', 3, 'Unlock'),

-- 5. Production
(5, 5, 'Production', 0, 'Active', 'production/production/daily_production/point', 0, 'Production', 'fa fa-industry', 5, 'Unlock'),
(501, 501, 'Point Production', 5, 'Active', 'production/production/daily_production/point', 1, 'Point Production', '', 1, 'Unlock'),
(502, 502, 'Depot Production', 5, 'Active', 'production/production/daily_production/depot', 1, 'Depot Production', '', 2, 'Unlock'),
(503, 503, 'Transfer Production', 5, 'Active', 'production/production/daily_production/transfer', 1, 'Transfer Production', '', 3, 'Unlock'),
(504, 504, 'Closing Stock', 5, 'Active', 'production/production/closing_stock', 1, 'Closing Stock', '', 4, 'Unlock'),

-- 6. Reports
(6, 6, 'Reports', 0, 'Active', 'reports/reports/dr_summary', 0, 'Reports', 'fa fa-file-text-o', 6, 'Unlock'),
(601, 601, 'DR Summary', 6, 'Active', 'reports/reports/dr_summary', 1, 'DR Summary', '', 1, 'Unlock'),
(602, 602, 'Production Report', 6, 'Active', 'reports/reports/production_report', 1, 'Production Report', '', 2, 'Unlock'),
(603, 603, 'FCW Detail Report', 6, 'Active', 'reports/reports/fcw_detail_report', 1, 'FCW Detail Report', '', 3, 'Unlock'),
(604, 604, 'DR For Driver', 6, 'Active', 'reports/reports/dr_for_driver', 1, 'DR For Driver', '', 4, 'Unlock'),
(605, 605, 'Inward Outward Report', 6, 'Active', 'reports/reports/inward_outward', 1, 'Inward Outward Report', '', 5, 'Unlock'),
(606, 606, 'X-Box Report', 6, 'Active', 'reports/reports/xbox_report', 1, 'X-Box Report', '', 6, 'Unlock'),
(607, 607, 'Comparison Report', 6, 'Active', 'reports/reports/comparison_report', 1, 'Comparison Report', '', 7, 'Unlock'),
(608, 608, 'All Boxes Report', 6, 'Active', 'reports/reports/all_boxes_report', 1, 'All Boxes Report', '', 8, 'Unlock'),
(609, 609, 'Local Sales Report', 6, 'Active', 'reports/reports/all_local_sales_report', 1, 'Local Sales Report', '', 9, 'Unlock'),
(610, 610, 'Stock Closing Report', 6, 'Active', 'reports/stock_report/closing_stock', 1, 'Stock Closing Report', '', 10, 'Unlock'),
(611, 611, 'Destroyed Stock Report', 6, 'Active', 'reports/stock_report/destroyed', 1, 'Destroyed Stock Report', '', 11, 'Unlock'),
(612, 612, 'Free Sale Report', 6, 'Active', 'reports/stock_report/free_sale', 1, 'Free Sale Report', '', 12, 'Unlock'),
(613, 613, 'Point Bachat Report', 6, 'Active', 'reports/stock_report/point_bachat', 1, 'Point Bachat Report', '', 13, 'Unlock'),

-- 7. Database
(7, 7, 'Database', 0, 'Active', 'database/database/setting', 0, 'Database', 'fa fa-database', 7, 'Unlock'),
(701, 701, 'Database Setting', 7, 'Active', 'database/database/setting', 1, 'Database Setting', '', 1, 'Unlock'),
(702, 702, 'Backup Database', 7, 'Active', 'database/database/start_backup', 1, 'Backup Database', '', 2, 'Unlock');

-- Clean and Populate psac_administrator_group_permission for all groups
TRUNCATE TABLE psac_administrator_group_permission;

-- Grant all permissions for Super Admin (group_id = 1) and other groups
INSERT INTO psac_administrator_group_permission (`group_id`, `menu_id`, `actions`, `editable`)
SELECT g.id AS group_id, m.page_menu_id AS menu_id, '["add","edit","delete","view","export","import"]' AS actions, 'Unlock' AS editable
FROM psac_administrator_group g
CROSS JOIN psac_admin_page_menu m;
