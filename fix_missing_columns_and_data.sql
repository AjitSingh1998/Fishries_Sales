-- 1. Alter psac_sale to ensure all GC columns exist
ALTER TABLE psac_sale ADD COLUMN IF NOT EXISTS total_wt DECIMAL(12,2) DEFAULT 0.00 AFTER net_wt;
ALTER TABLE psac_sale ADD COLUMN IF NOT EXISTS total_gross_wt DECIMAL(12,2) DEFAULT 0.00 AFTER total_wt;
ALTER TABLE psac_sale ADD COLUMN IF NOT EXISTS sms_sent VARCHAR(50) DEFAULT 'No' AFTER status;

-- 2. Alter psac_production to ensure all GC columns exist
ALTER TABLE psac_production ADD COLUMN IF NOT EXISTS forfeiture_wt DECIMAL(12,2) DEFAULT 0.00 AFTER point_wt;
ALTER TABLE psac_production ADD COLUMN IF NOT EXISTS forfeiture_rt_wt DECIMAL(12,2) DEFAULT 0.00 AFTER forfeiture_wt;
ALTER TABLE psac_production ADD COLUMN IF NOT EXISTS total_pt_wt DECIMAL(12,2) DEFAULT 0.00 AFTER forfeiture_rt_wt;
ALTER TABLE psac_production ADD COLUMN IF NOT EXISTS jhinga_point_wt DECIMAL(12,2) DEFAULT 0.00 AFTER total_wt;
ALTER TABLE psac_production ADD COLUMN IF NOT EXISTS jhinga_depot_wt DECIMAL(12,2) DEFAULT 0.00 AFTER jhinga_point_wt;

-- 3. Alter psac_product_dispatch to ensure all GC columns exist
ALTER TABLE psac_product_dispatch MODIFY COLUMN status VARCHAR(50) DEFAULT 'Active';
ALTER TABLE psac_product_dispatch ADD COLUMN IF NOT EXISTS total_box INT(11) DEFAULT 0 AFTER dispatch_to;
ALTER TABLE psac_product_dispatch ADD COLUMN IF NOT EXISTS departure_time DATETIME NULL AFTER total_box_wt;
ALTER TABLE psac_product_dispatch ADD COLUMN IF NOT EXISTS arrival_time DATETIME NULL AFTER departure_time;

-- 4. Alter psac_cash_received to ensure all GC columns exist
ALTER TABLE psac_cash_received ADD COLUMN IF NOT EXISTS amount DECIMAL(12,2) DEFAULT 0.00 AFTER client_id;
ALTER TABLE psac_cash_received ADD COLUMN IF NOT EXISTS invoice_number VARCHAR(50) NULL AFTER client_id;
ALTER TABLE psac_cash_received ADD COLUMN IF NOT EXISTS remition_by VARCHAR(100) DEFAULT 'Bank' AFTER amount;
ALTER TABLE psac_cash_received ADD COLUMN IF NOT EXISTS updated_by INT(11) NULL AFTER added_date;
ALTER TABLE psac_cash_received ADD COLUMN IF NOT EXISTS updated_date DATETIME NULL AFTER updated_by;
ALTER TABLE psac_cash_received ADD COLUMN IF NOT EXISTS action_microtime VARCHAR(50) NULL AFTER updated_date;
ALTER TABLE psac_cash_received ADD COLUMN IF NOT EXISTS source VARCHAR(50) DEFAULT 'Web' AFTER action_microtime;

-- 5. Seed rich sample data into psac_sale
TRUNCATE TABLE psac_sale;
INSERT INTO psac_sale 
(`id`, `invoice_number`, `dr_number`, `sale_date`, `client_id`, `client_name`, `market_type`, `market_category`, `market_id`, `gross_wt`, `net_wt`, `total_wt`, `total_gross_wt`, `gross_sale`, `net_sale`, `discount_perc`, `discount_amount`, `commission`, `ice_amount`, `destroyed_amount`, `total_expenses`, `sub_total`, `grand_total`, `received_amt`, `balance`, `payment_mode`, `payment_date`, `remark`, `added_by`, `added_date`, `status`, `sms_sent`, `editable`) 
VALUES
-- Point Sales (market_type = 1, market_category = 1)
(1, 'INV-PT-001', 'DR-2026-001', '2026-08-15', 1, 'Agrawal Fish Traders', 1, 1, 1, 450.00, 438.75, 438.75, 450.00, 81000.00, 78975.00, 2.00, 1620.00, 500.00, 300.00, 0.00, 800.00, 79380.00, 78580.00, 50000.00, 28580.00, 'Cash', '2026-08-15', 'Daily Point 1 wholesale sale', 1, '2026-08-15 10:00:00', 'Active', 'Yes', 'Unlock'),
(2, 'INV-PT-002', 'DR-2026-002', '2026-08-16', 2, 'Malwa Fresh Fish Co.', 1, 1, 1, 320.00, 312.00, 312.00, 320.00, 67200.00, 65520.00, 2.50, 1680.00, 450.00, 250.00, 0.00, 700.00, 65520.00, 64820.00, 64820.00, 0.00, 'Online', '2026-08-16', 'Direct point delivery', 1, '2026-08-16 11:30:00', 'Active', 'Yes', 'Unlock'),
(3, 'INV-PT-003', 'DR-2026-003', '2026-08-17', 3, 'Rajputana Fish Center', 1, 1, 7, 500.00, 487.50, 487.50, 500.00, 82500.00, 80437.50, 1.50, 1237.50, 600.00, 400.00, 0.00, 1000.00, 81262.50, 80262.50, 60000.00, 20262.50, 'Bank Transfer', '2026-08-17', 'Point collection billing', 1, '2026-08-17 09:15:00', 'Active', 'Yes', 'Unlock'),
(4, 'INV-PT-004', 'DR-2026-004', '2026-08-18', 4, 'Ghazipur Fish Suppliers', 1, 1, 7, 600.00, 585.00, 585.00, 600.00, 108000.00, 105300.00, 3.00, 3240.00, 750.00, 500.00, 0.00, 1250.00, 104760.00, 103510.00, 100000.00, 3510.00, 'Cash', '2026-08-18', 'Fresh Point Lot', 1, '2026-08-18 08:30:00', 'Active', 'Yes', 'Unlock'),

-- Depot Sales (market_type = 1, market_category = 2)
(5, 'INV-DP-001', 'DR-2026-005', '2026-08-15', 5, 'Chambal River Foods', 1, 2, 2, 800.00, 780.00, 780.00, 800.00, 168000.00, 163800.00, 2.00, 3360.00, 1000.00, 600.00, 0.00, 1600.00, 164640.00, 163040.00, 150000.00, 13040.00, 'Cheque', '2026-08-15', 'Rampura Central Depot bulk sale', 1, '2026-08-15 14:00:00', 'Active', 'Yes', 'Unlock'),
(6, 'INV-DP-002', 'DR-2026-006', '2026-08-16', 6, 'Indore Marine & Fresh', 1, 2, 2, 950.00, 926.25, 926.25, 950.00, 171000.00, 166725.00, 2.50, 4275.00, 1200.00, 800.00, 0.00, 2000.00, 166725.00, 164725.00, 164725.00, 0.00, 'Online', '2026-08-16', 'Depot supply invoice', 1, '2026-08-16 15:30:00', 'Active', 'Yes', 'Unlock'),
(7, 'INV-DP-003', 'DR-2026-007', '2026-08-17', 7, 'Bhopal Fish Junction', 1, 2, 3, 720.00, 702.00, 702.00, 720.00, 144000.00, 140400.00, 2.00, 2880.00, 900.00, 550.00, 0.00, 1450.00, 141120.00, 139670.00, 120000.00, 19670.00, 'Cash', '2026-08-17', 'Bhanpura Depot collection sale', 1, '2026-08-17 16:00:00', 'Active', 'Yes', 'Unlock'),
(8, 'INV-DP-004', 'DR-2026-008', '2026-08-18', 8, 'Jaipur Fresh Seafood', 1, 2, 4, 850.00, 828.75, 828.75, 850.00, 178500.00, 174037.50, 3.00, 5355.00, 1100.00, 700.00, 0.00, 1800.00, 173145.00, 171345.00, 171345.00, 0.00, 'Bank Transfer', '2026-08-18', 'Garoth Depot shipment invoice', 1, '2026-08-18 13:45:00', 'Active', 'Yes', 'Unlock'),

-- Outside Sales (market_type = 2)
(9, 'INV-OUT-001', 'DR-2026-009', '2026-08-16', 9, 'Ahmedabad Fish Mart', 2, 3, 6, 1200.00, 1170.00, 1170.00, 1200.00, 252000.00, 245700.00, 2.00, 5040.00, 1500.00, 1000.00, 0.00, 2500.00, 246960.00, 244460.00, 200000.00, 44460.00, 'Cheque', '2026-08-16', 'Interstate Delhi Ghazipur Mandi sale', 1, '2026-08-16 18:00:00', 'Active', 'Yes', 'Unlock'),
(10, 'INV-OUT-002', 'DR-2026-010', '2026-08-17', 10, 'Delhi Premium Catch', 2, 3, 8, 1500.00, 1462.50, 1462.50, 1500.00, 315000.00, 307125.00, 2.50, 7875.00, 1800.00, 1200.00, 0.00, 3000.00, 307125.00, 304125.00, 304125.00, 0.00, 'Bank Transfer', '2026-08-17', 'Interstate Jaipur Muhana Mandi sale', 1, '2026-08-17 19:30:00', 'Active', 'Yes', 'Unlock');

-- 6. Seed psac_production
TRUNCATE TABLE psac_production;
INSERT INTO psac_production 
(`id`, `production_id`, `production_date`, `depot_id`, `market_id`, `client_id`, `fish_code`, `fish_name`, `fish_type`, `fish_qty`, `fish_wt`, `fish_rate`, `total_wt`, `point_wt`, `forfeiture_wt`, `forfeiture_rt_wt`, `total_pt_wt`, `fresh_wt`, `rotten_wt`, `destroyed_wt`, `depot_wt`, `jhinga_point_wt`, `jhinga_depot_wt`, `surplus_wt`, `carret_quantity`, `carret_weight`, `box_number`, `box_wt`, `remark`, `added_by`, `added_date`, `status`, `editable`, `production_type`, `packing_date`, `local_minor_wt`, `tm_wt`, `total_catch_wt`, `local_sale_wt`, `destroyed_sale_wt`, `dispatch_wt`, `transfer_wt`, `free_sale_wt`, `carret_count`) 
VALUES
-- Point Production (production_type = 'point')
(1, 'PROD-PT-001', '2026-08-15', 2, 1, 1, 'FISH-ROHU', 'Rohu', 'Fresh', 250.00, 450.00, 180.00, 450.00, 450.00, 0.00, 0.00, 450.00, 382.50, 0.00, 0.00, 0.00, 25.00, 0.00, 5.50, 15, 450.00, 'BOX-001', 50.00, 'Point 1 fresh catch', 1, '2026-08-15 08:00:00', 'Active', 'Unlock', 'point', '2026-08-15', 15.00, 25.00, 450.00, 135.00, 0.00, 270.00, 0.00, 5.00, 15),
(2, 'PROD-PT-002', '2026-08-16', 2, 1, 2, 'FISH-CATLA', 'Catla', 'Fresh', 160.00, 320.00, 210.00, 320.00, 320.00, 0.00, 0.00, 320.00, 272.00, 0.00, 0.00, 0.00, 18.00, 0.00, 4.00, 11, 320.00, 'BOX-002', 50.00, 'Point 1 morning landing', 1, '2026-08-16 08:30:00', 'Active', 'Unlock', 'point', '2026-08-16', 12.00, 20.00, 320.00, 96.00, 0.00, 192.00, 0.00, 5.00, 11),
(3, 'PROD-PT-003', '2026-08-17', 2, 7, 3, 'FISH-MRIGAL', 'Mrigal', 'Fresh', 300.00, 500.00, 165.00, 500.00, 500.00, 0.00, 0.00, 500.00, 425.00, 0.00, 0.00, 0.00, 30.00, 0.00, 6.00, 17, 500.00, 'BOX-003', 48.50, 'Point 7 fresh catch', 1, '2026-08-17 08:15:00', 'Active', 'Unlock', 'point', '2026-08-17', 18.00, 30.00, 500.00, 150.00, 0.00, 300.00, 0.00, 5.00, 17),
(4, 'PROD-PT-004', '2026-08-18', 2, 7, 4, 'FISH-CATLA', 'Catla', 'Fresh', 310.00, 620.00, 210.00, 620.00, 620.00, 0.00, 0.00, 620.00, 527.00, 0.00, 0.00, 0.00, 35.00, 0.00, 7.50, 21, 620.00, 'BOX-004', 52.00, 'Point 7 major carp landing', 1, '2026-08-18 08:45:00', 'Active', 'Unlock', 'point', '2026-08-18', 22.00, 35.00, 620.00, 186.00, 0.00, 372.00, 0.00, 5.00, 21),

-- Depot Production (production_type = 'depot')
(5, 'PROD-DP-001', '2026-08-15', 2, 2, 5, 'FISH-ROHU', 'Rohu', 'Fresh', 400.00, 800.00, 180.00, 800.00, 0.00, 0.00, 0.00, 0.00, 680.00, 0.00, 0.00, 800.00, 0.00, 45.00, 9.00, 27, 800.00, 'BOX-005', 50.00, 'Rampura Central Depot receiving', 1, '2026-08-15 14:00:00', 'Active', 'Unlock', 'depot', '2026-08-15', 30.00, 45.00, 800.00, 240.00, 0.00, 480.00, 0.00, 10.00, 27),
(6, 'PROD-DP-002', '2026-08-16', 2, 2, 6, 'FISH-CATLA', 'Catla', 'Fresh', 475.00, 950.00, 210.00, 950.00, 0.00, 0.00, 0.00, 0.00, 807.50, 0.00, 0.00, 950.00, 0.00, 50.00, 11.00, 32, 950.00, 'BOX-006', 49.00, 'Rampura Central Depot graded stock', 1, '2026-08-16 14:30:00', 'Active', 'Unlock', 'depot', '2026-08-16', 35.00, 50.00, 950.00, 285.00, 0.00, 570.00, 0.00, 10.00, 32),
(7, 'PROD-DP-003', '2026-08-17', 3, 3, 7, 'FISH-MRIGAL', 'Mrigal', 'Fresh', 420.00, 720.00, 165.00, 720.00, 0.00, 0.00, 0.00, 0.00, 612.00, 0.00, 0.00, 720.00, 0.00, 40.00, 8.00, 24, 720.00, 'BOX-007', 51.50, 'Bhanpura Depot receiving', 1, '2026-08-17 15:00:00', 'Active', 'Unlock', 'depot', '2026-08-17', 25.00, 40.00, 720.00, 216.00, 0.00, 432.00, 0.00, 8.00, 24),
(8, 'PROD-DP-004', '2026-08-18', 4, 4, 8, 'FISH-ROHU', 'Rohu', 'Fresh', 450.00, 850.00, 180.00, 850.00, 0.00, 0.00, 0.00, 0.00, 722.50, 0.00, 0.00, 850.00, 0.00, 48.00, 10.00, 29, 850.00, 'BOX-008', 50.00, 'Garoth Depot receiving', 1, '2026-08-18 15:15:00', 'Active', 'Unlock', 'depot', '2026-08-18', 32.00, 48.00, 850.00, 255.00, 0.00, 510.00, 0.00, 10.00, 29),

-- Transfer Production (production_type = 'transfer')
(9, 'PROD-TR-001', '2026-08-16', 2, 3, 9, 'FISH-CATLA', 'Catla', 'Fresh', 175.00, 350.00, 210.00, 350.00, 0.00, 0.00, 0.00, 0.00, 350.00, 0.00, 0.00, 350.00, 0.00, 20.00, 0.00, 12, 350.00, 'BOX-009', 53.00, 'Inter-depot transfer Bhanpura to Rampura', 1, '2026-08-16 16:00:00', 'Active', 'Unlock', 'transfer', '2026-08-16', 0.00, 0.00, 350.00, 0.00, 0.00, 0.00, 350.00, 0.00, 12),
(10, 'PROD-TR-002', '2026-08-17', 2, 4, 10, 'FISH-ROHU', 'Rohu', 'Fresh', 210.00, 420.00, 180.00, 420.00, 0.00, 0.00, 0.00, 0.00, 420.00, 0.00, 0.00, 420.00, 0.00, 25.00, 0.00, 14, 420.00, 'BOX-010', 49.50, 'Inter-depot transfer Garoth to Rampura', 1, '2026-08-17 16:30:00', 'Active', 'Unlock', 'transfer', '2026-08-17', 0.00, 0.00, 420.00, 0.00, 0.00, 0.00, 420.00, 0.00, 14);

-- 7. Seed psac_product_dispatch
TRUNCATE TABLE psac_product_dispatch;
INSERT INTO psac_product_dispatch
(`id`, `dr_number`, `dispatch_date`, `dispatch_from`, `dispatch_to`, `total_box`, `market_id`, `vehicle_number`, `driver_name`, `driver_mobile_no`, `total_box_qty`, `total_box_wt`, `departure_time`, `arrival_time`, `advance_freight`, `remaining_freight`, `total_freight`, `dispatch_status`, `remark`, `added_by`, `added_date`, `status`, `editable`)
VALUES
-- Prepare DR (status = 'Active')
(1, 'DR-2026-001', '2026-08-15', 2, 6, 25, 6, 'MP-14-GA-1234', 'Suresh Kumar', '9826112345', 500, 1250.00, '2026-08-15 18:00:00', '2026-08-16 06:00:00', 5000.00, 10000.00, 15000.00, 'Prepare', 'Rampura to Delhi batch 1', 1, '2026-08-15 17:30:00', 'Active', 'Unlock'),
(2, 'DR-2026-002', '2026-08-16', 2, 8, 30, 8, 'MP-14-GA-2345', 'Ramesh Yadav', '9826223456', 600, 1500.00, '2026-08-16 19:00:00', '2026-08-17 07:00:00', 6000.00, 12000.00, 18000.00, 'Prepare', 'Rampura to Jaipur batch 2', 1, '2026-08-16 18:30:00', 'Active', 'Unlock'),
(3, 'DR-2026-003', '2026-08-17', 3, 10, 20, 10, 'MP-14-GA-3456', 'Kailash Singh', '9826334567', 400, 1000.00, '2026-08-17 18:30:00', '2026-08-18 05:30:00', 4000.00, 10000.00, 14000.00, 'Prepare', 'Bhanpura to Indore batch 3', 1, '2026-08-17 18:00:00', 'Active', 'Unlock'),
(4, 'DR-2026-004', '2026-08-18', 4, 6, 35, 6, 'MP-14-GA-4567', 'Dinesh Meena', '9826445678', 700, 1750.00, '2026-08-18 19:30:00', '2026-08-19 08:00:00', 7000.00, 14000.00, 21000.00, 'Prepare', 'Garoth to Delhi batch 4', 1, '2026-08-18 19:00:00', 'Active', 'Unlock'),

-- Dispatched Vouchers (status = 'Dispatched')
(5, 'DR-2026-005', '2026-08-13', 2, 6, 28, 6, 'MP-14-GA-5678', 'Mukesh Verma', '9826556789', 560, 1400.00, '2026-08-13 18:00:00', '2026-08-14 06:00:00', 6800.00, 10000.00, 16800.00, 'Dispatched', 'Rampura to Delhi dispatched voucher 1', 1, '2026-08-13 17:30:00', 'Dispatched', 'Unlock'),
(6, 'DR-2026-006', '2026-08-14', 2, 8, 32, 8, 'MP-14-GA-6789', 'Anil Sharma', '9826667890', 640, 1600.00, '2026-08-14 18:30:00', '2026-08-15 06:30:00', 9200.00, 10000.00, 19200.00, 'Dispatched', 'Rampura to Jaipur dispatched voucher 2', 1, '2026-08-14 18:00:00', 'Dispatched', 'Unlock'),
(7, 'DR-2026-007', '2026-08-14', 3, 9, 22, 9, 'MP-14-GA-7890', 'Gopal Patidar', '9826778901', 440, 1100.00, '2026-08-14 19:00:00', '2026-08-15 07:00:00', 5200.00, 8000.00, 13200.00, 'Dispatched', 'Bhanpura to Bhopal dispatched voucher 3', 1, '2026-08-14 18:30:00', 'Dispatched', 'Unlock'),
(8, 'DR-2026-008', '2026-08-15', 4, 10, 26, 10, 'MP-14-GA-8901', 'Prakash Joshi', '9826889012', 520, 1300.00, '2026-08-15 19:30:00', '2026-08-16 07:30:00', 5600.00, 10000.00, 15600.00, 'Dispatched', 'Garoth to Indore dispatched voucher 4', 1, '2026-08-15 19:00:00', 'Dispatched', 'Unlock');

-- 8. Seed psac_cash_received
TRUNCATE TABLE psac_cash_received;
INSERT INTO psac_cash_received
(`id`, `client_id`, `invoice_number`, `amount`, `received_amount`, `remition_by`, `payment_mode`, `reference_number`, `payment_date`, `remark`, `added_by`, `added_date`, `updated_by`, `updated_date`, `action_microtime`, `source`, `status`, `editable`)
VALUES
(1, 1, 'REC-2026-001', 50000.00, 50000.00, 'Agrawal Fish Traders', 'Cash', 'REF-001', '2026-08-15', 'Cash payment against INV-PT-001', 1, '2026-08-15 10:00:00', 1, '2026-08-15 10:00:00', '1723700000.123', 'Web', 'Active', 'Unlock'),
(2, 2, 'REC-2026-002', 64820.00, 64820.00, 'Malwa Fresh Fish Co.', 'Online', 'REF-002', '2026-08-16', 'Full settlement for INV-PT-002', 1, '2026-08-16 11:30:00', 1, '2026-08-16 11:30:00', '1723700000.234', 'Web', 'Active', 'Unlock'),
(3, 3, 'REC-2026-003', 60000.00, 60000.00, 'Rajputana Fish Center', 'Bank Transfer', 'REF-003', '2026-08-17', 'Advance deposit for INV-PT-003', 1, '2026-08-17 09:15:00', 1, '2026-08-17 09:15:00', '1723700000.345', 'Web', 'Active', 'Unlock'),
(4, 5, 'REC-2026-004', 150000.00, 150000.00, 'Chambal River Foods', 'Cheque', 'REF-004', '2026-08-15', 'Part payment for INV-DP-001', 1, '2026-08-15 14:00:00', 1, '2026-08-15 14:00:00', '1723700000.456', 'Web', 'Active', 'Unlock');

-- 9. Seed psac_free_sale
TRUNCATE TABLE psac_free_sale;
INSERT INTO psac_free_sale
(`id`, `invoice_number`, `free_sale_date`, `client_id`, `company_name`, `contact_number`, `email`, `total_quantity`, `total_weight`, `received_amount`, `market_type`, `market_category`, `market_id`, `remark`, `added_by`, `added_date`, `status`, `editable`)
VALUES
(1, 'FS-2026-001', '2026-08-15', 1, 'Agrawal Fish Traders', '9826000001', 'agrawal@example.com', 10.00, 25.00, 0.00, 1, 1, 1, 'Complimentary fish sampling lot 1', 1, '2026-08-15 10:00:00', 'Active', 'Unlock'),
(2, 'FS-2026-002', '2026-08-16', 2, 'Malwa Fresh Fish Co.', '9826000002', 'malwa@example.com', 8.00, 20.00, 0.00, 1, 1, 2, 'Promotional sample box lot 2', 1, '2026-08-16 11:30:00', 'Active', 'Unlock'),
(3, 'FS-2026-003', '2026-08-17', 5, 'Chambal River Foods', '9826000005', 'chambal@example.com', 12.00, 30.00, 0.00, 1, 2, 2, 'Government delegation tasting sample', 1, '2026-08-17 14:00:00', 'Active', 'Unlock');
