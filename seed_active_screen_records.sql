-- 1. Ensure Menu URLs point to the actual table listing methods
UPDATE psac_admin_page_menu SET menu_url = 'dispatch/dispatch/prepare_dr' WHERE id = 401;
UPDATE psac_admin_page_menu SET menu_url = 'dispatch/dispatch/dispatched_dr' WHERE id = 402;
UPDATE psac_admin_page_menu SET menu_url = 'dispatch/dispatch/prepare_dr' WHERE id = 4;

-- 2. Populate psac_sale with rich categorized rows for Point Sales, Depot Sales, and Outside Sales
TRUNCATE TABLE psac_sale;
INSERT INTO psac_sale 
(`id`, `invoice_number`, `dr_number`, `sale_date`, `client_id`, `client_name`, `market_type`, `market_category`, `market_id`, `gross_wt`, `net_wt`, `gross_sale`, `net_sale`, `discount_perc`, `discount_amount`, `commission`, `ice_amount`, `destroyed_amount`, `total_expenses`, `sub_total`, `grand_total`, `received_amt`, `balance`, `payment_mode`, `payment_date`, `remark`, `added_by`, `added_date`, `status`, `editable`) 
VALUES
-- Point Sales (market_type = 1, market_category = 1)
(1, 'INV-PT-001', 'DR-2026-001', '2026-08-15', 1, 'Agrawal Fish Traders', 1, 1, 1, 450.00, 438.75, 81000.00, 78975.00, 2.00, 1620.00, 500.00, 300.00, 0.00, 800.00, 79380.00, 78580.00, 50000.00, 28580.00, 'Cash', '2026-08-15', 'Daily Point 1 wholesale sale', 1, '2026-08-15 10:00:00', 'Active', 'Unlock'),
(2, 'INV-PT-002', 'DR-2026-002', '2026-08-16', 2, 'Malwa Fresh Fish Co.', 1, 1, 1, 320.00, 312.00, 67200.00, 65520.00, 2.50, 1680.00, 450.00, 250.00, 0.00, 700.00, 65520.00, 64820.00, 64820.00, 0.00, 'Online', '2026-08-16', 'Direct point delivery', 1, '2026-08-16 11:30:00', 'Active', 'Unlock'),
(3, 'INV-PT-003', 'DR-2026-003', '2026-08-17', 3, 'Rajputana Fish Center', 1, 1, 7, 500.00, 487.50, 82500.00, 80437.50, 1.50, 1237.50, 600.00, 400.00, 0.00, 1000.00, 81262.50, 80262.50, 60000.00, 20262.50, 'Bank Transfer', '2026-08-17', 'Point collection billing', 1, '2026-08-17 09:15:00', 'Active', 'Unlock'),
(4, 'INV-PT-004', 'DR-2026-004', '2026-08-18', 4, 'Ghazipur Fish Suppliers', 1, 1, 7, 600.00, 585.00, 108000.00, 105300.00, 3.00, 3240.00, 750.00, 500.00, 0.00, 1250.00, 104760.00, 103510.00, 100000.00, 3510.00, 'Cash', '2026-08-18', 'Fresh Point Lot', 1, '2026-08-18 08:30:00', 'Active', 'Unlock'),

-- Depot Sales (market_type = 1, market_category = 2)
(5, 'INV-DP-001', 'DR-2026-005', '2026-08-15', 5, 'Chambal River Foods', 1, 2, 2, 800.00, 780.00, 168000.00, 163800.00, 2.00, 3360.00, 1000.00, 600.00, 0.00, 1600.00, 164640.00, 163040.00, 150000.00, 13040.00, 'Cheque', '2026-08-15', 'Rampura Central Depot bulk sale', 1, '2026-08-15 14:00:00', 'Active', 'Unlock'),
(6, 'INV-DP-002', 'DR-2026-006', '2026-08-16', 6, 'Indore Marine & Fresh', 1, 2, 2, 950.00, 926.25, 171000.00, 166725.00, 2.50, 4275.00, 1200.00, 800.00, 0.00, 2000.00, 166725.00, 164725.00, 164725.00, 0.00, 'Online', '2026-08-16', 'Depot supply invoice', 1, '2026-08-16 15:30:00', 'Active', 'Unlock'),
(7, 'INV-DP-003', 'DR-2026-007', '2026-08-17', 7, 'Bhopal Fish Junction', 1, 2, 3, 720.00, 702.00, 144000.00, 140400.00, 2.00, 2880.00, 900.00, 550.00, 0.00, 1450.00, 141120.00, 139670.00, 120000.00, 19670.00, 'Cash', '2026-08-17', 'Bhanpura Depot collection sale', 1, '2026-08-17 16:00:00', 'Active', 'Unlock'),
(8, 'INV-DP-004', 'DR-2026-008', '2026-08-18', 8, 'Jaipur Fresh Seafood', 1, 2, 4, 850.00, 828.75, 178500.00, 174037.50, 3.00, 5355.00, 1100.00, 700.00, 0.00, 1800.00, 173145.00, 171345.00, 171345.00, 0.00, 'Bank Transfer', '2026-08-18', 'Garoth Depot shipment invoice', 1, '2026-08-18 13:45:00', 'Active', 'Unlock'),

-- Outside Sales (market_type = 2)
(9, 'INV-OUT-001', 'DR-2026-009', '2026-08-16', 9, 'Ahmedabad Fish Mart', 2, 3, 6, 1200.00, 1170.00, 252000.00, 245700.00, 2.00, 5040.00, 1500.00, 1000.00, 0.00, 2500.00, 246960.00, 244460.00, 200000.00, 44460.00, 'Cheque', '2026-08-16', 'Interstate Delhi Ghazipur Mandi sale', 1, '2026-08-16 18:00:00', 'Active', 'Unlock'),
(10, 'INV-OUT-002', 'DR-2026-010', '2026-08-17', 10, 'Delhi Premium Catch', 2, 3, 8, 1500.00, 1462.50, 315000.00, 307125.00, 2.50, 7875.00, 1800.00, 1200.00, 0.00, 3000.00, 307125.00, 304125.00, 304125.00, 0.00, 'Bank Transfer', '2026-08-17', 'Interstate Jaipur Muhana Mandi sale', 1, '2026-08-17 19:30:00', 'Active', 'Unlock');

-- 3. Populate psac_production with rows for Point, Depot, and Transfer
TRUNCATE TABLE psac_production;
INSERT INTO psac_production 
(`id`, `production_id`, `production_date`, `depot_id`, `market_id`, `client_id`, `fish_code`, `fish_name`, `fish_type`, `fish_qty`, `fish_wt`, `fish_rate`, `total_wt`, `point_wt`, `depot_wt`, `surplus_wt`, `carret_quantity`, `carret_weight`, `box_number`, `box_wt`, `remark`, `added_by`, `added_date`, `status`, `editable`, `production_type`, `packing_date`, `fresh_wt`, `local_minor_wt`, `tm_wt`, `total_catch_wt`, `local_sale_wt`, `destroyed_sale_wt`, `dispatch_wt`, `transfer_wt`, `free_sale_wt`, `carret_count`, `rotten_wt`, `destroyed_wt`) 
VALUES
-- Point Production (production_type = 'point')
(1, 'PROD-PT-001', '2026-08-15', 1, 1, 1, 'FISH-ROHU', 'Rohu', 'Fresh', 250.00, 450.00, 180.00, 450.00, 450.00, 0.00, 5.50, 15, 450.00, 'BOX-001', 50.00, 'Point 1 fresh catch', 1, '2026-08-15 08:00:00', 'Active', 'Unlock', 'point', '2026-08-15', 382.50, 15.00, 25.00, 450.00, 135.00, 0.00, 270.00, 0.00, 5.00, 15, 0.00, 0.00),
(2, 'PROD-PT-002', '2026-08-16', 1, 1, 2, 'FISH-CATLA', 'Catla', 'Fresh', 160.00, 320.00, 210.00, 320.00, 320.00, 0.00, 4.00, 11, 320.00, 'BOX-002', 50.00, 'Point 1 morning landing', 1, '2026-08-16 08:30:00', 'Active', 'Unlock', 'point', '2026-08-16', 272.00, 12.00, 20.00, 320.00, 96.00, 0.00, 192.00, 0.00, 5.00, 11, 0.00, 0.00),
(3, 'PROD-PT-003', '2026-08-17', 2, 7, 3, 'FISH-MRIGAL', 'Mrigal', 'Fresh', 300.00, 500.00, 165.00, 500.00, 500.00, 0.00, 6.00, 17, 500.00, 'BOX-003', 48.50, 'Point 7 fresh catch', 1, '2026-08-17 08:15:00', 'Active', 'Unlock', 'point', '2026-08-17', 425.00, 18.00, 30.00, 500.00, 150.00, 0.00, 300.00, 0.00, 5.00, 17, 0.00, 0.00),
(4, 'PROD-PT-004', '2026-08-18', 2, 7, 4, 'FISH-CATLA', 'Catla', 'Fresh', 310.00, 620.00, 210.00, 620.00, 620.00, 0.00, 7.50, 21, 620.00, 'BOX-004', 52.00, 'Point 7 major carp landing', 1, '2026-08-18 08:45:00', 'Active', 'Unlock', 'point', '2026-08-18', 527.00, 22.00, 35.00, 620.00, 186.00, 0.00, 372.00, 0.00, 5.00, 21, 0.00, 0.00),

-- Depot Production (production_type = 'depot')
(5, 'PROD-DP-001', '2026-08-15', 2, 2, 5, 'FISH-ROHU', 'Rohu', 'Fresh', 400.00, 800.00, 180.00, 800.00, 0.00, 800.00, 9.00, 27, 800.00, 'BOX-005', 50.00, 'Rampura Central Depot receiving', 1, '2026-08-15 14:00:00', 'Active', 'Unlock', 'depot', '2026-08-15', 680.00, 30.00, 45.00, 800.00, 240.00, 0.00, 480.00, 0.00, 10.00, 27, 0.00, 0.00),
(6, 'PROD-DP-002', '2026-08-16', 2, 2, 6, 'FISH-CATLA', 'Catla', 'Fresh', 475.00, 950.00, 210.00, 950.00, 0.00, 950.00, 11.00, 32, 950.00, 'BOX-006', 49.00, 'Rampura Central Depot graded stock', 1, '2026-08-16 14:30:00', 'Active', 'Unlock', 'depot', '2026-08-16', 807.50, 35.00, 50.00, 950.00, 285.00, 0.00, 570.00, 0.00, 10.00, 32, 0.00, 0.00),
(7, 'PROD-DP-003', '2026-08-17', 3, 3, 7, 'FISH-MRIGAL', 'Mrigal', 'Fresh', 420.00, 720.00, 165.00, 720.00, 0.00, 720.00, 8.00, 24, 720.00, 'BOX-007', 51.50, 'Bhanpura Depot receiving', 1, '2026-08-17 15:00:00', 'Active', 'Unlock', 'depot', '2026-08-17', 612.00, 25.00, 40.00, 720.00, 216.00, 0.00, 432.00, 0.00, 8.00, 24, 0.00, 0.00),
(8, 'PROD-DP-004', '2026-08-18', 4, 4, 8, 'FISH-ROHU', 'Rohu', 'Fresh', 450.00, 850.00, 180.00, 850.00, 0.00, 850.00, 10.00, 29, 850.00, 'BOX-008', 50.00, 'Garoth Depot receiving', 1, '2026-08-18 15:15:00', 'Active', 'Unlock', 'depot', '2026-08-18', 722.50, 32.00, 48.00, 850.00, 255.00, 0.00, 510.00, 0.00, 10.00, 29, 0.00, 0.00),

-- Transfer Production (production_type = 'transfer')
(9, 'PROD-TR-001', '2026-08-16', 2, 3, 9, 'FISH-CATLA', 'Catla', 'Fresh', 175.00, 350.00, 210.00, 350.00, 0.00, 0.00, 0.00, 12, 350.00, 'BOX-009', 53.00, 'Inter-depot transfer Bhanpura to Rampura', 1, '2026-08-16 16:00:00', 'Active', 'Unlock', 'transfer', '2026-08-16', 350.00, 0.00, 0.00, 350.00, 0.00, 0.00, 0.00, 350.00, 0.00, 12, 0.00, 0.00),
(10, 'PROD-TR-002', '2026-08-17', 2, 4, 10, 'FISH-ROHU', 'Rohu', 'Fresh', 210.00, 420.00, 180.00, 420.00, 0.00, 0.00, 0.00, 14, 420.00, 'BOX-010', 49.50, 'Inter-depot transfer Garoth to Rampura', 1, '2026-08-17 16:30:00', 'Active', 'Unlock', 'transfer', '2026-08-17', 420.00, 0.00, 0.00, 420.00, 0.00, 0.00, 0.00, 420.00, 0.00, 14, 0.00, 0.00);

-- 4. Alter psac_product_dispatch status to varchar and populate rows
ALTER TABLE psac_product_dispatch MODIFY COLUMN status VARCHAR(50) DEFAULT 'Active';

TRUNCATE TABLE psac_product_dispatch;
INSERT INTO psac_product_dispatch
(`id`, `dr_number`, `dispatch_date`, `dispatch_from`, `dispatch_to`, `market_id`, `vehicle_number`, `driver_name`, `driver_mobile_no`, `total_box_qty`, `total_box_wt`, `advance_freight`, `remaining_freight`, `total_freight`, `dispatch_status`, `remark`, `added_by`, `added_date`, `status`, `editable`)
VALUES
-- Prepare DR (status = 'Active')
(1, 'DR-2026-001', '2026-08-15', 2, 6, 6, 'MP-14-GA-1234', 'Suresh Kumar', '9826112345', 500, 1250.00, 5000.00, 10000.00, 15000.00, 'Prepare', 'Rampura to Delhi batch 1', 1, '2026-08-15 17:30:00', 'Active', 'Unlock'),
(2, 'DR-2026-002', '2026-08-16', 2, 8, 8, 'MP-14-GA-2345', 'Ramesh Yadav', '9826223456', 600, 1500.00, 6000.00, 12000.00, 18000.00, 'Prepare', 'Rampura to Jaipur batch 2', 1, '2026-08-16 18:30:00', 'Active', 'Unlock'),
(3, 'DR-2026-003', '2026-08-17', 3, 10, 10, 'MP-14-GA-3456', 'Kailash Singh', '9826334567', 400, 1000.00, 4000.00, 10000.00, 14000.00, 'Prepare', 'Bhanpura to Indore batch 3', 1, '2026-08-17 18:00:00', 'Active', 'Unlock'),
(4, 'DR-2026-004', '2026-08-18', 4, 6, 6, 'MP-14-GA-4567', 'Dinesh Meena', '9826445678', 700, 1750.00, 7000.00, 14000.00, 21000.00, 'Prepare', 'Garoth to Delhi batch 4', 1, '2026-08-18 19:00:00', 'Active', 'Unlock'),

-- Dispatched Vouchers (status = 'Dispatched')
(5, 'DR-2026-005', '2026-08-13', 2, 6, 6, 'MP-14-GA-5678', 'Mukesh Verma', '9826556789', 560, 1400.00, 6800.00, 10000.00, 16800.00, 'Dispatched', 'Rampura to Delhi dispatched voucher 1', 1, '2026-08-13 17:30:00', 'Dispatched', 'Unlock'),
(6, 'DR-2026-006', '2026-08-14', 2, 8, 8, 'MP-14-GA-6789', 'Anil Sharma', '9826667890', 640, 1600.00, 9200.00, 10000.00, 19200.00, 'Dispatched', 'Rampura to Jaipur dispatched voucher 2', 1, '2026-08-14 18:00:00', 'Dispatched', 'Unlock'),
(7, 'DR-2026-007', '2026-08-14', 3, 9, 9, 'MP-14-GA-7890', 'Gopal Patidar', '9826778901', 440, 1100.00, 5200.00, 8000.00, 13200.00, 'Dispatched', 'Bhanpura to Bhopal dispatched voucher 3', 1, '2026-08-14 18:30:00', 'Dispatched', 'Unlock'),
(8, 'DR-2026-008', '2026-08-15', 4, 10, 10, 'MP-14-GA-8901', 'Prakash Joshi', '9826889012', 520, 1300.00, 5600.00, 10000.00, 15600.00, 'Dispatched', 'Garoth to Indore dispatched voucher 4', 1, '2026-08-15 19:00:00', 'Dispatched', 'Unlock');

-- 5. Populate psac_product_box for Prepare Box view
TRUNCATE TABLE psac_product_box;
INSERT INTO psac_product_box
(`id`, `box_number`, `depot_id`, `market_id`, `fish_code`, `box_weight`, `carret_weight`, `box_status`, `packing_date`, `added_by`, `added_date`, `status`, `editable`, `fish_type`)
VALUES
(1, 'BOX-001', 2, 1, 'FISH-ROHU', 50.00, 52.00, 'Packed', '2026-08-15', 1, '2026-08-15 10:00:00', 'Active', 'Unlock', 'Fresh'),
(2, 'BOX-002', 2, 1, 'FISH-CATLA', 50.00, 52.00, 'Packed', '2026-08-15', 1, '2026-08-15 10:15:00', 'Active', 'Unlock', 'Fresh'),
(3, 'BOX-003', 2, 1, 'FISH-MRIGAL', 48.50, 50.50, 'Packed', '2026-08-16', 1, '2026-08-16 10:30:00', 'Active', 'Unlock', 'Fresh'),
(4, 'BOX-004', 2, 7, 'FISH-ROHU', 52.00, 54.00, 'Packed', '2026-08-16', 1, '2026-08-16 11:00:00', 'Active', 'Unlock', 'Fresh'),
(5, 'BOX-005', 3, 7, 'FISH-CATLA', 50.00, 52.00, 'Packed', '2026-08-17', 1, '2026-08-17 09:30:00', 'Active', 'Unlock', 'Fresh'),
(6, 'BOX-006', 3, 2, 'FISH-MRIGAL', 49.00, 51.00, 'Packed', '2026-08-17', 1, '2026-08-17 10:00:00', 'Active', 'Unlock', 'Fresh'),
(7, 'BOX-007', 4, 2, 'FISH-ROHU', 51.50, 53.50, 'Packed', '2026-08-18', 1, '2026-08-18 09:00:00', 'Active', 'Unlock', 'Fresh'),
(8, 'BOX-008', 4, 3, 'FISH-CATLA', 50.00, 52.00, 'Packed', '2026-08-18', 0, '2026-08-18 09:30:00', 'Active', 'Unlock', 'Fresh'),
(9, 'BOX-009', 2, 4, 'FISH-MRIGAL', 53.00, 55.00, 'Packed', '2026-08-18', 1, '2026-08-18 10:00:00', 'Active', 'Unlock', 'Fresh'),
(10, 'BOX-010', 2, 4, 'FISH-ROHU', 49.50, 51.50, 'Packed', '2026-08-18', 1, '2026-08-18 10:30:00', 'Active', 'Unlock', 'Fresh');

-- 6. Populate psac_closing_stock
TRUNCATE TABLE psac_closing_stock;
INSERT INTO psac_closing_stock
(`id`, `stock_date`, `depot_id`, `market_id`, `total_weight`, `added_by`, `added_date`, `status`, `editable`)
VALUES
(1, '2026-08-15', 2, 1, 550.00, 1, '2026-08-15 20:00:00', 'Active', 'Unlock'),
(2, '2026-08-16', 2, 1, 600.00, 1, '2026-08-16 20:00:00', 'Active', 'Unlock'),
(3, '2026-08-17', 3, 7, 470.00, 1, '2026-08-17 20:00:00', 'Active', 'Unlock'),
(4, '2026-08-18', 4, 3, 500.00, 1, '2026-08-18 20:00:00', 'Active', 'Unlock');
