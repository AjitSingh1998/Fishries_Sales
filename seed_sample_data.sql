-- ====================================================================
-- SEED 10 SAMPLE DATA ENTRIES PER TABLE
-- Database: sales_gandhisagar
-- Application: Simran Fisheries Pvt. Ltd. (Gandhisagar Sales & Supply Chain)
-- ====================================================================

USE `sales_gandhisagar`;
SET FOREIGN_KEY_CHECKS = 0;

-- --------------------------------------------------------
-- 1. psac_administrator_group (Ensure 10 records)
-- --------------------------------------------------------
INSERT INTO `psac_administrator_group` (`id`, `group_name`, `description`, `status`) VALUES
(1, 'Super Admin', 'Full Access Super Administrator', 'Active'),
(2, 'Manager', 'Manager Level Access', 'Active'),
(3, 'Supervisor', 'Field and Depot Supervisor', 'Active'),
(4, 'Accountant', 'Financial and Billing Incharge', 'Active'),
(5, 'Depot Incharge', 'Depot Collection & Stock Incharge', 'Active'),
(6, 'Point Incharge', 'Fishing Point Collection Incharge', 'Active'),
(7, 'Quality Inspector', 'Fish Grading & Quality Assessment', 'Active'),
(8, 'Dispatcher', 'Vehicle and Box Dispatch Incharge', 'Active'),
(9, 'Store Keeper', 'Inventory and Cold Storage Manager', 'Active'),
(10, 'Auditor', 'Internal Audit and Inspection', 'Active')
ON DUPLICATE KEY UPDATE `group_name`=VALUES(`group_name`), `description`=VALUES(`description`), `status`=VALUES(`status`);

-- --------------------------------------------------------
-- 2. psac_administrator (Ensure 10 records)
-- (Password hash is md5('admin') -> 21232f297a57a5a743894a0e4a801fc3)
-- --------------------------------------------------------
INSERT INTO `psac_administrator` (`id`, `group_id`, `first_name`, `last_name`, `username`, `password`, `email`, `phone`, `market_id`, `depot_id`, `created_date`, `status`) VALUES
(1, 1, 'Admin', 'User', 'admin', '21232f297a57a5a743894a0e4a801fc3', 'admin@simransolvex.com', '9893011111', 1, 1, '2026-01-01 09:00:00', 'Active'),
(2, 1, 'Super', 'Admin', 'superadmin', '21232f297a57a5a743894a0e4a801fc3', 'superadmin@simransolvex.com', '9893022222', 1, 1, '2026-01-01 09:00:00', 'Active'),
(3, 2, 'Ramesh', 'Sharma', 'ramesh.sharma', '21232f297a57a5a743894a0e4a801fc3', 'ramesh.sharma@simransolvex.com', '9893033333', 2, 2, '2026-01-05 10:00:00', 'Active'),
(4, 3, 'Suresh', 'Verma', 'suresh.verma', '21232f297a57a5a743894a0e4a801fc3', 'suresh.verma@simransolvex.com', '9893044444', 3, 3, '2026-01-05 10:30:00', 'Active'),
(5, 4, 'Dinesh', 'Patel', 'dinesh.patel', '21232f297a57a5a743894a0e4a801fc3', 'dinesh.patel@simransolvex.com', '9893055555', 4, 4, '2026-01-06 11:00:00', 'Active'),
(6, 5, 'Rajesh', 'Gupta', 'rajesh.gupta', '21232f297a57a5a743894a0e4a801fc3', 'rajesh.gupta@simransolvex.com', '9893066666', 5, 5, '2026-01-06 11:30:00', 'Active'),
(7, 6, 'Mukesh', 'Yadav', 'mukesh.yadav', '21232f297a57a5a743894a0e4a801fc3', 'mukesh.yadav@simransolvex.com', '9893077777', 6, 6, '2026-01-07 09:15:00', 'Active'),
(8, 7, 'Mahesh', 'Jain', 'mahesh.jain', '21232f297a57a5a743894a0e4a801fc3', 'mahesh.jain@simransolvex.com', '9893088888', 7, 7, '2026-01-07 10:15:00', 'Active'),
(9, 8, 'Anil', 'Choudhary', 'anil.choudhary', '21232f297a57a5a743894a0e4a801fc3', 'anil.choudhary@simransolvex.com', '9893099999', 8, 8, '2026-01-08 14:00:00', 'Active'),
(10, 9, 'Sunil', 'Mishra', 'sunil.mishra', '21232f297a57a5a743894a0e4a801fc3', 'sunil.mishra@simransolvex.com', '9893010101', 9, 9, '2026-01-08 15:00:00', 'Active')
ON DUPLICATE KEY UPDATE `password`=VALUES(`password`), `status`=VALUES(`status`);

-- --------------------------------------------------------
-- 3. psac_business_detail (10 records)
-- --------------------------------------------------------
INSERT INTO `psac_business_detail` (`business_id`, `company_name`, `dam_name`, `address`, `city`, `state`, `pincode`, `phone`, `email`, `session_year`, `status`) VALUES
(1, 'Simran Fisheries Pvt. Ltd. - HQ', 'Gandhisagar Reservoir', 'Main Gate, Dam Site', 'Mandsaur', 'Madhya Pradesh', '458001', '07422-220101', 'info@simranfisheries.com', '2018-19', 'Active'),
(2, 'Simran Fisheries - Rampura Unit', 'Gandhisagar Reservoir', 'Rampura Shoreline', 'Rampura', 'Madhya Pradesh', '458118', '07422-220102', 'rampura@simranfisheries.com', '2018-19', 'Active'),
(3, 'Simran Fisheries - Bhanpura Unit', 'Gandhisagar Reservoir', 'Bhanpura Jetty', 'Bhanpura', 'Madhya Pradesh', '458775', '07422-220103', 'bhanpura@simranfisheries.com', '2018-19', 'Active'),
(4, 'Simran Fisheries - Garoth Unit', 'Gandhisagar Reservoir', 'Garoth Dock', 'Garoth', 'Madhya Pradesh', '458880', '07422-220104', 'garoth@simranfisheries.com', '2018-19', 'Active'),
(5, 'Simran Fisheries - Chandwasa Unit', 'Gandhisagar Reservoir', 'Chandwasa Hub', 'Chandwasa', 'Madhya Pradesh', '458888', '07422-220105', 'chandwasa@simranfisheries.com', '2018-19', 'Active'),
(6, 'Simran Fisheries - Bolia Unit', 'Gandhisagar Reservoir', 'Bolia Point Dock', 'Bolia', 'Madhya Pradesh', '458885', '07422-220106', 'bolia@simranfisheries.com', '2018-19', 'Active'),
(7, 'Simran Fisheries - Neemuch Depot', 'Gandhisagar Reservoir', 'Indore Road Depot', 'Neemuch', 'Madhya Pradesh', '458441', '07423-220107', 'neemuch@simranfisheries.com', '2018-19', 'Active'),
(8, 'Simran Fisheries - Kota Central Depot', 'Chambal River System', 'Aerodrome Circle', 'Kota', 'Rajasthan', '324007', '0744-220108', 'kota@simranfisheries.com', '2018-19', 'Active'),
(9, 'Simran Fisheries - Indore Distribution', 'Indore Central Hub', 'Navlakha Transport Nagar', 'Indore', 'Madhya Pradesh', '452001', '0731-220109', 'indore@simranfisheries.com', '2018-19', 'Active'),
(10, 'Simran Fisheries - Bhopal Cold Hub', 'Bhopal Processing Plant', 'Govindpura Industrial Area', 'Bhopal', 'Madhya Pradesh', '462023', '0755-220110', 'bhopal@simranfisheries.com', '2018-19', 'Active')
ON DUPLICATE KEY UPDATE `company_name`=VALUES(`company_name`), `status`=VALUES(`status`);

-- --------------------------------------------------------
-- 4. psac_market_type (10 records)
-- --------------------------------------------------------
INSERT INTO `psac_market_type` (`id`, `code`, `name`, `editable`, `status`) VALUES
(1, 'LOCAL', 'Local Market', 1, 'Active'),
(2, 'EXPORT', 'Export Market', 1, 'Active'),
(3, 'WHOLESALE', 'Wholesale Mandi', 1, 'Active'),
(4, 'RETAIL', 'Retail Outlet', 1, 'Active'),
(5, 'INSTITUTIONAL', 'Institutional Supply', 1, 'Active'),
(6, 'GOVERNMENT', 'Government Supply', 1, 'Active'),
(7, 'INTERSTATE', 'Interstate Transport', 1, 'Active'),
(8, 'PROCESSING', 'Processing Unit Supply', 1, 'Active'),
(9, 'ONLINE', 'Direct E-Commerce / B2B', 1, 'Active'),
(10, 'HOTEL_REST', 'HORECA Supply', 1, 'Active')
ON DUPLICATE KEY UPDATE `code`=VALUES(`code`), `name`=VALUES(`name`), `status`=VALUES(`status`);

-- --------------------------------------------------------
-- 5. psac_market_category (10 records)
-- --------------------------------------------------------
INSERT INTO `psac_market_category` (`id`, `market_type`, `code`, `name`, `status`) VALUES
(1, 1, 'POINT', 'Fishing Point Market', 'Active'),
(2, 1, 'DEPOT', 'Central Depot Market', 'Active'),
(3, 2, 'OUTSIDE', 'Outside Market / Interstate', 'Active'),
(4, 3, 'MANDI_MP', 'MP Wholesale Mandi', 'Active'),
(5, 3, 'MANDI_RJ', 'Rajasthan Wholesale Mandi', 'Active'),
(6, 4, 'URBAN_RETAIL', 'Urban Daily Retail', 'Active'),
(7, 5, 'CONTRACT_CORP', 'Corporate Contract Supply', 'Active'),
(8, 7, 'DELHI_NCR', 'Delhi NCR Terminal Market', 'Active'),
(9, 7, 'MUMBAI_MMR', 'Mumbai Wholesale Market', 'Active'),
(10, 8, 'FREEZING_PLANT', 'Cold Chain & Freezing Unit', 'Active')
ON DUPLICATE KEY UPDATE `code`=VALUES(`code`), `name`=VALUES(`name`), `status`=VALUES(`status`);

-- --------------------------------------------------------
-- 6. psac_market_place (10 records)
-- --------------------------------------------------------
INSERT INTO `psac_market_place` (`id`, `market_type`, `market_category`, `code`, `name`, `added_by`, `added_date`, `status`) VALUES
(1, 1, 1, 'MP-GND-01', 'Gandhisagar Point 1', 1, '2026-01-01 10:00:00', 'Active'),
(2, 1, 2, 'MP-DEP-01', 'Rampura Main Depot', 1, '2026-01-01 10:00:00', 'Active'),
(3, 1, 2, 'MP-DEP-02', 'Bhanpura Main Depot', 1, '2026-01-01 10:00:00', 'Active'),
(4, 1, 2, 'MP-DEP-03', 'Garoth Depot', 1, '2026-01-01 10:00:00', 'Active'),
(5, 1, 2, 'MP-DEP-04', 'Chandwasa Depot', 1, '2026-01-01 10:00:00', 'Active'),
(6, 2, 3, 'MP-OUT-01', 'Delhi Ghazipur Fish Market', 1, '2026-01-02 11:00:00', 'Active'),
(7, 2, 3, 'MP-OUT-02', 'Jaipur Muhana Mandi', 1, '2026-01-02 11:00:00', 'Active'),
(8, 2, 3, 'MP-OUT-03', 'Ahmedabad Jamalpur Market', 1, '2026-01-02 11:00:00', 'Active'),
(9, 1, 2, 'MP-DEP-05', 'Neemuch Sub Depot', 1, '2026-01-03 12:00:00', 'Active'),
(10, 2, 3, 'MP-OUT-04', 'Indore Chhavani Mandi', 1, '2026-01-03 12:00:00', 'Active')
ON DUPLICATE KEY UPDATE `code`=VALUES(`code`), `name`=VALUES(`name`), `status`=VALUES(`status`);

-- --------------------------------------------------------
-- 7. psac_depot (10 records)
-- --------------------------------------------------------
INSERT INTO `psac_depot` (`id`, `code`, `name`, `location`, `status`) VALUES
(1, 'DEP-01', 'Rampura Main Depot', 'Rampura Shoreline Dock, Mandsaur', 'Active'),
(2, 'DEP-02', 'Bhanpura Central Depot', 'Bhanpura Jetty Platform, Mandsaur', 'Active'),
(3, 'DEP-03', 'Garoth Collection Depot', 'Garoth Dockyard, Mandsaur', 'Active'),
(4, 'DEP-04', 'Chandwasa Cold Depot', 'Chandwasa Hub Complex, Mandsaur', 'Active'),
(5, 'DEP-05', 'Bolia Shore Depot', 'Bolia Terminal, Mandsaur', 'Active'),
(6, 'DEP-06', 'Gandhisagar Dam Top Depot', 'Dam Gate Station No. 2, Mandsaur', 'Active'),
(7, 'DEP-07', 'Neemuch Distribution Depot', 'Transport Nagar, Neemuch', 'Active'),
(8, 'DEP-08', 'Kota Riverfront Depot', 'Aerodrome Circle, Kota', 'Active'),
(9, 'DEP-09', 'Rawatbhata Lake Depot', 'Rana Pratap Sagar Dock, Rawatbhata', 'Active'),
(10, 'DEP-10', 'Sunel Border Depot', 'Sunel Crossing, Jhalawar', 'Active')
ON DUPLICATE KEY UPDATE `code`=VALUES(`code`), `name`=VALUES(`name`), `status`=VALUES(`status`);

-- --------------------------------------------------------
-- 8. psac_fish_category (10 records)
-- --------------------------------------------------------
INSERT INTO `psac_fish_category` (`id`, `code`, `name`, `editable`, `status`) VALUES
(1, 'CAT-MC', 'Major Carp (IMC)', 1, 'Active'),
(2, 'CAT-CF', 'Catfish Varieties', 1, 'Active'),
(3, 'CAT-MU', 'Murrel / Snakehead', 1, 'Active'),
(4, 'CAT-FB', 'Featherback Species', 1, 'Active'),
(5, 'CAT-TL', 'Tilapia Varieties', 1, 'Active'),
(6, 'CAT-EC', 'Exotic Carps', 1, 'Active'),
(7, 'CAT-MN', 'Minor Carps', 1, 'Active'),
(8, 'CAT-EL', 'Freshwater Eel', 1, 'Active'),
(9, 'CAT-PR', 'Freshwater Prawn', 1, 'Active'),
(10, 'CAT-DF', 'Processed / Dry Fish', 1, 'Active')
ON DUPLICATE KEY UPDATE `code`=VALUES(`code`), `name`=VALUES(`name`), `status`=VALUES(`status`);

-- --------------------------------------------------------
-- 9. psac_fish (10 records)
-- --------------------------------------------------------
INSERT INTO `psac_fish` (`id`, `category_id`, `code`, `name`, `type`, `fish_rate`, `dhalta`, `status`) VALUES
(1, 1, 'FISH-ROHU', 'Rohu (Labeo rohita)', 'Fresh', 180.00, 2.50, 'Active'),
(2, 1, 'FISH-CATLA', 'Catla (Gibelion catla)', 'Fresh', 210.00, 3.00, 'Active'),
(3, 1, 'FISH-MRIGAL', 'Mrigal (Cirrhinus mrigala)', 'Fresh', 165.00, 2.00, 'Active'),
(4, 2, 'FISH-SINGHI', 'Singhi (Heteropneustes fossilis)', 'Fresh', 320.00, 1.50, 'Active'),
(5, 2, 'FISH-MAGUR', 'Magur (Clarias batrachus)', 'Fresh', 350.00, 1.50, 'Active'),
(6, 6, 'FISH-SILVER', 'Silver Carp (Hypophthalmichthys)', 'Fresh', 130.00, 2.00, 'Active'),
(7, 6, 'FISH-GRASS', 'Grass Carp (Ctenopharyngodon)', 'Fresh', 145.00, 2.50, 'Active'),
(8, 2, 'FISH-TENGRA', 'Tengra (Mystus tengara)', 'Fresh', 240.00, 1.00, 'Active'),
(9, 2, 'FISH-PANGA', 'Pangasius (Pangasianodon)', 'Fresh', 120.00, 2.00, 'Active'),
(10, 1, 'FISH-MAHSEER', 'Tor Mahseer (Tor tor)', 'Fresh', 450.00, 3.50, 'Active')
ON DUPLICATE KEY UPDATE `code`=VALUES(`code`), `name`=VALUES(`name`), `fish_rate`=VALUES(`fish_rate`), `status`=VALUES(`status`);

-- --------------------------------------------------------
-- 10. psac_fishingpoints (10 records)
-- --------------------------------------------------------
INSERT INTO `psac_fishingpoints` (`id`, `name`, `code`, `status`) VALUES
(1, 'Point Rampura Bay No. 1', 'FP-RP-01', 'Active'),
(2, 'Point Rampura Bay No. 2', 'FP-RP-02', 'Active'),
(3, 'Point Bhanpura Deep Basin', 'FP-BP-01', 'Active'),
(4, 'Point Bhanpura South Shore', 'FP-BP-02', 'Active'),
(5, 'Point Garoth Inlet Point', 'FP-GR-01', 'Active'),
(6, 'Point Garoth Island Channel', 'FP-GR-02', 'Active'),
(7, 'Point Chandwasa Backwaters', 'FP-CW-01', 'Active'),
(8, 'Point Bolia Riverbed Zone', 'FP-BL-01', 'Active'),
(9, 'Point Dam Spillway Basin', 'FP-DS-01', 'Active'),
(10, 'Point Rawatbhata Border Reach', 'FP-RB-01', 'Active')
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`), `code`=VALUES(`code`), `status`=VALUES(`status`);

-- --------------------------------------------------------
-- 11. psac_maingroup_type (10 records)
-- --------------------------------------------------------
INSERT INTO `psac_maingroup_type` (`id`, `type_name`, `status`) VALUES
(1, 'Cooperative Society Group', 'Active'),
(2, 'Private Fishermen Association', 'Active'),
(3, 'Dam Basin Deepwater Unit', 'Active'),
(4, 'Shoreline Seine Netters', 'Active'),
(5, 'Gillnet Specialized Team', 'Active'),
(6, 'Motorboat Catch Brigade', 'Active'),
(7, 'Traditional Longliner Team', 'Active'),
(8, 'Night Catch Special Unit', 'Active'),
(9, 'Monsoon Surge Fishermen', 'Active'),
(10, 'Reservoir Border Patrol Catchers', 'Active')
ON DUPLICATE KEY UPDATE `type_name`=VALUES(`type_name`), `status`=VALUES(`status`);

-- --------------------------------------------------------
-- 12. psac_maingroup (10 records)
-- --------------------------------------------------------
INSERT INTO `psac_maingroup` (`ID`, `Name`, `Type`, `product_balance`, `wages_balance`, `status`) VALUES
(1, 'Gandhisagar Matsya Vikas Samiti', 1, 15400.00, 24800.00, 'Active'),
(2, 'Chambal Machhuara Sahakari Samiti', 1, 12800.00, 18500.00, 'Active'),
(3, 'Rampura Deepwater Fishermen Group', 3, 9500.00, 14200.00, 'Active'),
(4, 'Bhanpura Jai Jaldevta Mandal', 2, 8200.00, 16900.00, 'Active'),
(5, 'Garoth Narmada-Chambal Fisher Union', 4, 11000.00, 19500.00, 'Active'),
(6, 'Chandwasa Matsyopadak Dal', 5, 7400.00, 13400.00, 'Active'),
(7, 'Bolia Machhuara Sangh', 6, 6800.00, 11800.00, 'Active'),
(8, 'Shiv Shakti Jaljivi Samuh', 7, 5200.00, 9600.00, 'Active'),
(9, 'Mandsaur Ekta Fishermen Group', 8, 14300.00, 22100.00, 'Active'),
(10, 'Rajasthan-MP Border Fishermen Union', 10, 16700.00, 26500.00, 'Active')
ON DUPLICATE KEY UPDATE `Name`=VALUES(`Name`), `status`=VALUES(`status`);

-- --------------------------------------------------------
-- 13. psac_fisherman (10 records)
-- --------------------------------------------------------
INSERT INTO `psac_fisherman` (`ID`, `Code`, `Name`, `MainGroup`, `ContactNumber`, `Address`, `products_balance`, `wages_balance`, `status`) VALUES
(1, 'FM-001', 'Kailash Kashyap', 1, '9826011001', 'Rampura Fisherman Colony No. 1', 1200.00, 3500.00, 'Active'),
(2, 'FM-002', 'Bhanwarlal Dhimar', 1, '9826011002', 'Rampura Rivergate Mohalla', 850.00, 2900.00, 'Active'),
(3, 'FM-003', 'Madanlal Kewat', 2, '9826011003', 'Bhanpura Jetty Nagar', 1450.00, 4200.00, 'Active'),
(4, 'FM-004', 'Raju Sahni', 2, '9826011004', 'Bhanpura Near Old Temple', 600.00, 1800.00, 'Active'),
(5, 'FM-005', 'Radheshyam Mallah', 3, '9826011005', 'Garoth Dock Road Ward 4', 980.00, 3100.00, 'Active'),
(6, 'FM-006', 'Gopal Nishad', 3, '9826011006', 'Garoth Machhuara Basti', 1100.00, 3650.00, 'Active'),
(7, 'FM-007', 'Mukesh Barman', 4, '9826011007', 'Chandwasa Main Chowk', 750.00, 2400.00, 'Active'),
(8, 'FM-008', 'Sohanlal Jalwanshi', 4, '9826011008', 'Chandwasa Shoreline hut 12', 1300.00, 3900.00, 'Active'),
(9, 'FM-009', 'Govind Meena', 5, '9826011009', 'Bolia Fishermen Quarters', 500.00, 1600.00, 'Active'),
(10, 'FM-010', 'Dinesh Majhi', 5, '9826011010', 'Bolia Canal Road', 1650.00, 4800.00, 'Active')
ON DUPLICATE KEY UPDATE `Name`=VALUES(`Name`), `status`=VALUES(`status`);

-- --------------------------------------------------------
-- 14. psac_secondaryfisherman (10 records)
-- --------------------------------------------------------
INSERT INTO `psac_secondaryfisherman` (`ID`, `Primary`, `Secondary`) VALUES
(1, 1, 2),
(2, 1, 3),
(3, 2, 4),
(4, 3, 5),
(5, 4, 6),
(6, 5, 7),
(7, 6, 8),
(8, 7, 9),
(9, 8, 10),
(10, 9, 1)
ON DUPLICATE KEY UPDATE `Primary`=VALUES(`Primary`), `Secondary`=VALUES(`Secondary`);

-- --------------------------------------------------------
-- 15. psac_client (10 records)
-- --------------------------------------------------------
INSERT INTO `psac_client` (`id`, `code`, `company_name`, `trademark_name`, `contact_number`, `contact_number2`, `email`, `address`, `city`, `state`, `country`, `market_type`, `maingroup`, `opening_balance`, `products_balance`, `wages_balance`, `remark`, `added_by`, `added_date`, `status`) VALUES
(1, 'CL-001', 'Agrawal Fish Traders', 'Agrawal Fresh', '9827012345', '9827012346', 'agrawal.fish@gmail.com', 'Shop 12, Main Mandi', 'Indore', 'Madhya Pradesh', 'India', 1, 1, 50000.00, 25000.00, 0.00, 'Regular Bulk Buyer', 1, '2026-01-01 10:00:00', 'Active'),
(2, 'CL-002', 'Malwa Fresh Fish Co.', 'Malwa Catch', '9827023456', '9827023457', 'malwafresh@gmail.com', 'Plot 44, Scheme 78', 'Indore', 'Madhya Pradesh', 'India', 1, 1, 35000.00, 18000.00, 0.00, 'Wholesale Distributor', 1, '2026-01-01 10:30:00', 'Active'),
(3, 'CL-003', 'Rajputana Fish Center', 'Rajputana Royal', '9827034567', '9827034568', 'rajputana.fish@gmail.com', 'Muhana Mandi Gate 3', 'Jaipur', 'Rajasthan', 'India', 2, 2, 75000.00, 42000.00, 0.00, 'Interstate Client', 1, '2026-01-02 11:00:00', 'Active'),
(4, 'CL-004', 'Ghazipur Fish Suppliers', 'Capital Fish', '9827045678', '9827045679', 'ghazipur.fish@gmail.com', 'Block D, Ghazipur Mandi', 'Delhi', 'Delhi', 'India', 2, 2, 120000.00, 65000.00, 0.00, 'Large Volume Trader', 1, '2026-01-02 11:30:00', 'Active'),
(5, 'CL-005', 'Chambal River Foods', 'Chambal Fresh', '9827056789', '9827056780', 'chambal.foods@gmail.com', 'Station Road', 'Kota', 'Rajasthan', 'India', 1, 3, 28000.00, 12000.00, 0.00, 'Daily Buyer', 1, '2026-01-03 09:00:00', 'Active'),
(6, 'CL-006', 'Bhopal Sagar Seafoods', 'Sagar Brand', '9827067890', '9827067891', 'bhopal.sagar@gmail.com', 'Hamidia Road', 'Bhopal', 'Madhya Pradesh', 'India', 1, 3, 40000.00, 15000.00, 0.00, 'Regional Client', 1, '2026-01-03 09:30:00', 'Active'),
(7, 'CL-007', 'Gujarat Oceanic Traders', 'Gujarat Sea & Lake', '9827078901', '9827078902', 'gujarat.oceanic@gmail.com', 'Jamalpur Wholesale', 'Ahmedabad', 'Gujarat', 'India', 2, 4, 95000.00, 38000.00, 0.00, 'Weekly Truckload Buyer', 1, '2026-01-04 10:00:00', 'Active'),
(8, 'CL-008', 'Ujjain Mahakal Fish Mart', 'Mahakal Fresh', '9827089012', '9827089013', 'mahakal.fish@gmail.com', 'Dewas Gate', 'Ujjain', 'Madhya Pradesh', 'India', 1, 4, 20000.00, 9000.00, 0.00, 'Local Retail & Bulk', 1, '2026-01-04 10:30:00', 'Active'),
(9, 'CL-009', 'Gwalior Chambal Traders', 'Gwalior Catch', '9827090123', '9827090124', 'gwalior.chambal@gmail.com', 'Lashkar Fish Market', 'Gwalior', 'Madhya Pradesh', 'India', 1, 5, 32000.00, 14000.00, 0.00, 'Daily Van Buyer', 1, '2026-01-05 11:00:00', 'Active'),
(10, 'CL-010', 'National Cold Chain Logistics', 'NCC Seafood', '9827001234', '9827001235', 'ncc.logistics@gmail.com', 'Transport Nagar', 'Agra', 'Uttar Pradesh', 'India', 2, 5, 150000.00, 80000.00, 0.00, 'Reefer Truck Buyer', 1, '2026-01-05 11:30:00', 'Active')
ON DUPLICATE KEY UPDATE `company_name`=VALUES(`company_name`), `status`=VALUES(`status`);

-- --------------------------------------------------------
-- 16. psac_particulars (10 records)
-- --------------------------------------------------------
INSERT INTO `psac_particulars` (`id`, `particular`, `status`) VALUES
(1, 'Ice Block Supply & Crushing', 'Active'),
(2, 'Vehicle Diesel & Transportation', 'Active'),
(3, 'Loading & Unloading Labor', 'Active'),
(4, 'Thermocol Packaging & Crates', 'Active'),
(5, 'Toll Tax & Interstate Permit Fee', 'Active'),
(6, 'Weighbridge Charges', 'Active'),
(7, 'Depot Cold Storage Maintenance', 'Active'),
(8, 'Commission & Brokerage Charges', 'Active'),
(9, 'Fish Grading & Sorting Labor', 'Active'),
(10, 'Miscellaneous Administrative Expenses', 'Active')
ON DUPLICATE KEY UPDATE `particular`=VALUES(`particular`), `status`=VALUES(`status`);

-- --------------------------------------------------------
-- 17. psac_box_number (10 records)
-- --------------------------------------------------------
INSERT INTO `psac_box_number` (`id`, `box_number`, `status`) VALUES
(1, 'BOX-GS-001', 'Active'),
(2, 'BOX-GS-002', 'Active'),
(3, 'BOX-GS-003', 'Active'),
(4, 'BOX-GS-004', 'Active'),
(5, 'BOX-GS-005', 'Active'),
(6, 'BOX-GS-006', 'Active'),
(7, 'BOX-GS-007', 'Active'),
(8, 'BOX-GS-008', 'Active'),
(9, 'BOX-GS-009', 'Active'),
(10, 'BOX-GS-010', 'Active')
ON DUPLICATE KEY UPDATE `box_number`=VALUES(`box_number`), `status`=VALUES(`status`);

-- --------------------------------------------------------
-- 18. psac_associations (10 records)
-- --------------------------------------------------------
INSERT INTO `psac_associations` (`id`, `firstName`, `lastName`, `phone_number`, `city`, `registerDate`, `status`) VALUES
(1, 'Devendra', 'Kashyap', '9752011001', 'Rampura', '2026-01-01', 'Active'),
(2, 'Shyamlal', 'Dhimar', '9752011002', 'Bhanpura', '2026-01-02', 'Active'),
(3, 'Jagdish', 'Kewat', '9752011003', 'Garoth', '2026-01-03', 'Active'),
(4, 'Omkar', 'Sahni', '9752011004', 'Chandwasa', '2026-01-04', 'Active'),
(5, 'Hariom', 'Mallah', '9752011005', 'Bolia', '2026-01-05', 'Active'),
(6, 'Ramcharan', 'Nishad', '9752011006', 'Mandsaur', '2026-01-06', 'Active'),
(7, 'Bhagwandas', 'Barman', '9752011007', 'Neemuch', '2026-01-07', 'Active'),
(8, 'Santosh', 'Jalwanshi', '9752011008', 'Kota', '2026-01-08', 'Active'),
(9, 'Ghanshyam', 'Meena', '9752011009', 'Rawatbhata', '2026-01-09', 'Active'),
(10, 'Laxman', 'Majhi', '9752011010', 'Jhalawar', '2026-01-10', 'Active')
ON DUPLICATE KEY UPDATE `firstName`=VALUES(`firstName`), `status`=VALUES(`status`);

-- --------------------------------------------------------
-- 19. psac_staff_groups (10 records)
-- --------------------------------------------------------
INSERT INTO `psac_staff_groups` (`id`, `group_name`, `created_date`, `status`) VALUES
(1, 'Executive Management', '2026-01-01 08:00:00', 'Active'),
(2, 'Accounts & Finance Staff', '2026-01-01 08:00:00', 'Active'),
(3, 'Depot Field Operations', '2026-01-01 08:00:00', 'Active'),
(4, 'Point Catch Collection Team', '2026-01-01 08:00:00', 'Active'),
(5, 'Cold Storage & Ice Operators', '2026-01-01 08:00:00', 'Active'),
(6, 'Quality & Grading Technicians', '2026-01-01 08:00:00', 'Active'),
(7, 'Packaging & Crate Crew', '2026-01-01 08:00:00', 'Active'),
(8, 'Logistics & Dispatch Drivers', '2026-01-01 08:00:00', 'Active'),
(9, 'Security & Basin Surveillance', '2026-01-01 08:00:00', 'Active'),
(10, 'IT & Systems Support', '2026-01-01 08:00:00', 'Active')
ON DUPLICATE KEY UPDATE `group_name`=VALUES(`group_name`), `status`=VALUES(`status`);

-- --------------------------------------------------------
-- 20. psac_sale_type (10 records)
-- --------------------------------------------------------
INSERT INTO `psac_sale_type` (`id`, `name`, `status`) VALUES
(1, 'Direct Cash Sale', 'Active'),
(2, 'Credit Sale (Regular Client)', 'Active'),
(3, 'Consignment Outside Sale', 'Active'),
(4, 'Advance Booking Sale', 'Active'),
(5, 'Free Sample / Trial Sale', 'Active'),
(6, 'Emergency Distress Sale', 'Active'),
(7, 'Institutional Contract Sale', 'Active'),
(8, 'Bulk Mandi Auction Sale', 'Active'),
(9, 'Direct Processing Plant Sale', 'Active'),
(10, 'Retail Stall Daily Sale', 'Active')
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`), `status`=VALUES(`status`);

-- --------------------------------------------------------
-- 21. psac_sales_typest (10 records)
-- --------------------------------------------------------
INSERT INTO `psac_sales_typest` (`id`, `name`, `status`) VALUES
(1, 'Standard Fresh Fish Sale', 'Active'),
(2, 'Graded Premium Major Carp', 'Active'),
(3, 'Live Catfish Specialized Sale', 'Active'),
(4, 'Bulk Ice-Packed Crates', 'Active'),
(5, 'Reefer Container Lot', 'Active'),
(6, 'Dam Head Point Direct Catch Sale', 'Active'),
(7, 'Depot Aggregated Bulk Lot', 'Active'),
(8, 'Interstate Van Load', 'Active'),
(9, 'Special Seasonal Bidding Sale', 'Active'),
(10, 'End of Day Clearance Sale', 'Active')
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`), `status`=VALUES(`status`);

-- --------------------------------------------------------
-- 22. psac_sale_markets (10 records)
-- --------------------------------------------------------
INSERT INTO `psac_sale_markets` (`id`, `market_id`, `status`) VALUES
(1, 1, 'Active'),
(2, 2, 'Active'),
(3, 3, 'Active'),
(4, 4, 'Active'),
(5, 5, 'Active'),
(6, 6, 'Active'),
(7, 7, 'Active'),
(8, 8, 'Active'),
(9, 9, 'Active'),
(10, 10, 'Active')
ON DUPLICATE KEY UPDATE `market_id`=VALUES(`market_id`), `status`=VALUES(`status`);

-- --------------------------------------------------------
-- 23. psac_email_templates (10 records)
-- --------------------------------------------------------
INSERT INTO `psac_email_templates` (`id`, `task`, `subject`, `body`) VALUES
(1, 'daily_invoice', 'Invoice #{invoice_number} from Simran Fisheries', 'Dear {client_name}, please find attached your daily invoice #{invoice_number} for date {sale_date}. Total: Rs. {grand_total}.'),
(2, 'payment_receipt', 'Payment Receipt for Invoice #{invoice_number}', 'Dear {client_name}, we have received your payment of Rs. {amount} via {payment_mode}. Current Balance: Rs. {balance}.'),
(3, 'dispatch_notice', 'Dispatch Confirmation - Vehicle {vehicle_number}', 'Consignment of {total_box_qty} boxes ({total_box_wt} kg) has been dispatched from {depot_name} to {market_name}.'),
(4, 'wages_summary', 'Weekly Wages Statement for {fisherman_name}', 'Dear {fisherman_name}, your weekly wages for period {period} has been calculated: Gross Rs. {gross}, Deductions Rs. {deductions}, Net Rs. {net}.'),
(5, 'daily_stock_report', 'Gandhisagar Daily Stock & Catch Summary', 'Please find attached the daily production and closing stock report for {stock_date}. Total Weight: {total_weight} kg.'),
(6, 'advance_disbursement', 'Fisherman Advance Notice - {fisherman_name}', 'Cash advance of Rs. {amount} has been disbursed to {fisherman_name} on {advance_date}.'),
(7, 'stock_alert_destroyed', 'Damage / Destruction Stock Report', 'Stock of {weight} kg fish categorized as destroyed on {record_date} at {depot_name}.'),
(8, 'new_user_welcome', 'Welcome to Simran Fisheries Management System', 'Hello {first_name}, your user account ({username}) has been created with access group {group_name}.'),
(9, 'password_reset', 'Simran Fisheries Password Reset OTP', 'Your one-time password for account reset is {otp}. Valid for 15 minutes.'),
(10, 'backup_notification', 'System Database Backup Completed', 'Scheduled database backup {backup_dbname} completed successfully on {backup_date}.')
ON DUPLICATE KEY UPDATE `subject`=VALUES(`subject`), `body`=VALUES(`body`);

-- --------------------------------------------------------
-- 24. psac_settings (Ensure 10 records)
-- --------------------------------------------------------
INSERT INTO `psac_settings` (`id`, `keyword`, `value`, `status`) VALUES
(1, 'COMPANY_NAME', 'Simran Fisheries Pvt. Ltd.', 'Active'),
(2, 'DAM_NAME', 'Gandhisagar', 'Active'),
(3, 'SESSION_YEAR', '2018-19', 'Active'),
(4, 'DEFAULT_CURRENCY', 'INR', 'Active'),
(5, 'SMS_GATEWAY_STATUS', 'Enabled', 'Active'),
(6, 'ICE_RATE_PER_KG', '2.50', 'Active'),
(7, 'DEFAULT_COMMISSION_PERC', '5.00', 'Active'),
(8, 'AUTO_LOCK_TIME_HOURS', '24', 'Active'),
(9, 'APP_THEME', 'lyt4-theme-1', 'Active'),
(10, 'BACKUP_RETENTION_DAYS', '90', 'Active')
ON DUPLICATE KEY UPDATE `value`=VALUES(`value`), `status`=VALUES(`status`);

-- --------------------------------------------------------
-- 25. psac_demo_user (10 records)
-- --------------------------------------------------------
INSERT INTO `psac_demo_user` (`id`, `username`, `password`) VALUES
(1, 'demouser1', '21232f297a57a5a743894a0e4a801fc3'),
(2, 'demouser2', '21232f297a57a5a743894a0e4a801fc3'),
(3, 'demouser3', '21232f297a57a5a743894a0e4a801fc3'),
(4, 'demouser4', '21232f297a57a5a743894a0e4a801fc3'),
(5, 'demouser5', '21232f297a57a5a743894a0e4a801fc3'),
(6, 'demouser6', '21232f297a57a5a743894a0e4a801fc3'),
(7, 'demouser7', '21232f297a57a5a743894a0e4a801fc3'),
(8, 'demouser8', '21232f297a57a5a743894a0e4a801fc3'),
(9, 'demouser9', '21232f297a57a5a743894a0e4a801fc3'),
(10, 'demouser10', '21232f297a57a5a743894a0e4a801fc3')
ON DUPLICATE KEY UPDATE `password`=VALUES(`password`);

-- --------------------------------------------------------
-- 26. psac_production (10 records)
-- --------------------------------------------------------
INSERT INTO `psac_production` (`id`, `production_id`, `production_date`, `depot_id`, `market_id`, `client_id`, `fish_code`, `fish_name`, `fish_type`, `fish_qty`, `fish_wt`, `fish_rate`, `total_wt`, `point_wt`, `depot_wt`, `surplus_wt`, `carret_quantity`, `carret_weight`, `box_number`, `box_wt`, `remark`, `added_by`, `added_date`, `status`) VALUES
(1, 'PROD-2026-001', '2026-01-10', 1, 1, 1, 'FISH-ROHU', 'Rohu', 'Fresh', 150.00, 450.00, 180.00, 450.00, 220.00, 230.00, 0.00, 15, 450.00, 'BOX-GS-001', 30.00, 'Morning Catch Rampura', 1, '2026-01-10 08:00:00', 'Active'),
(2, 'PROD-2026-002', '2026-01-10', 1, 1, 1, 'FISH-CATLA', 'Catla', 'Fresh', 80.00, 320.00, 210.00, 320.00, 160.00, 160.00, 0.00, 10, 320.00, 'BOX-GS-002', 32.00, 'Morning Catch Rampura', 1, '2026-01-10 08:30:00', 'Active'),
(3, 'PROD-2026-003', '2026-01-11', 2, 2, 2, 'FISH-MRIGAL', 'Mrigal', 'Fresh', 120.00, 300.00, 165.00, 300.00, 150.00, 150.00, 0.00, 10, 300.00, 'BOX-GS-003', 30.00, 'Bhanpura Jetty Catch', 1, '2026-01-11 08:15:00', 'Active'),
(4, 'PROD-2026-004', '2026-01-11', 2, 2, 2, 'FISH-SINGHI', 'Singhi', 'Fresh', 200.00, 100.00, 320.00, 100.00, 50.00, 50.00, 0.00, 5, 100.00, 'BOX-GS-004', 20.00, 'Live Catch Bhanpura', 1, '2026-01-11 08:45:00', 'Active'),
(5, 'PROD-2026-005', '2026-01-12', 3, 3, 3, 'FISH-MAGUR', 'Magur', 'Fresh', 180.00, 120.00, 350.00, 120.00, 60.00, 60.00, 0.00, 6, 120.00, 'BOX-GS-005', 20.00, 'Garoth Night Catch', 1, '2026-01-12 07:30:00', 'Active'),
(6, 'PROD-2026-006', '2026-01-12', 3, 3, 3, 'FISH-SILVER', 'Silver Carp', 'Fresh', 140.00, 420.00, 130.00, 420.00, 210.00, 210.00, 0.00, 14, 420.00, 'BOX-GS-006', 30.00, 'Garoth Surface Catch', 1, '2026-01-12 08:00:00', 'Active'),
(7, 'PROD-2026-007', '2026-01-13', 4, 4, 4, 'FISH-GRASS', 'Grass Carp', 'Fresh', 110.00, 330.00, 145.00, 330.00, 160.00, 170.00, 0.00, 11, 330.00, 'BOX-GS-007', 30.00, 'Chandwasa Catch', 1, '2026-01-13 08:30:00', 'Active'),
(8, 'PROD-2026-008', '2026-01-13', 4, 4, 4, 'FISH-TENGRA', 'Tengra', 'Fresh', 250.00, 125.00, 240.00, 125.00, 60.00, 65.00, 0.00, 5, 125.00, 'BOX-GS-008', 25.00, 'Chandwasa Shallow Zone', 1, '2026-01-13 09:00:00', 'Active'),
(9, 'PROD-2026-009', '2026-01-14', 5, 5, 5, 'FISH-PANGA', 'Pangasius', 'Fresh', 160.00, 480.00, 120.00, 480.00, 240.00, 240.00, 0.00, 16, 480.00, 'BOX-GS-009', 30.00, 'Bolia Riverbed Catch', 1, '2026-01-14 08:00:00', 'Active'),
(10, 'PROD-2026-010', '2026-01-14', 5, 5, 5, 'FISH-MAHSEER', 'Tor Mahseer', 'Fresh', 50.00, 200.00, 450.00, 200.00, 100.00, 100.00, 0.00, 8, 200.00, 'BOX-GS-010', 25.00, 'Deep Reservoir Catch', 1, '2026-01-14 08:30:00', 'Active')
ON DUPLICATE KEY UPDATE `production_id`=VALUES(`production_id`), `total_wt`=VALUES(`total_wt`), `status`=VALUES(`status`);

-- --------------------------------------------------------
-- 27. psac_daily_production (10 records)
-- --------------------------------------------------------
INSERT INTO `psac_daily_production` (`id`, `production_date`, `depot_id`, `market_id`, `fish_code`, `fish_quantity`, `fish_weight`, `added_by`, `added_date`, `status`) VALUES
(1, '2026-01-10', 1, 1, 'FISH-ROHU', 150.00, 450.00, 1, '2026-01-10 18:00:00', 'Active'),
(2, '2026-01-10', 1, 1, 'FISH-CATLA', 80.00, 320.00, 1, '2026-01-10 18:00:00', 'Active'),
(3, '2026-01-11', 2, 2, 'FISH-MRIGAL', 120.00, 300.00, 1, '2026-01-11 18:00:00', 'Active'),
(4, '2026-01-11', 2, 2, 'FISH-SINGHI', 200.00, 100.00, 1, '2026-01-11 18:00:00', 'Active'),
(5, '2026-01-12', 3, 3, 'FISH-MAGUR', 180.00, 120.00, 1, '2026-01-12 18:00:00', 'Active'),
(6, '2026-01-12', 3, 3, 'FISH-SILVER', 140.00, 420.00, 1, '2026-01-12 18:00:00', 'Active'),
(7, '2026-01-13', 4, 4, 'FISH-GRASS', 110.00, 330.00, 1, '2026-01-13 18:00:00', 'Active'),
(8, '2026-01-13', 4, 4, 'FISH-TENGRA', 250.00, 125.00, 1, '2026-01-13 18:00:00', 'Active'),
(9, '2026-01-14', 5, 5, 'FISH-PANGA', 160.00, 480.00, 1, '2026-01-14 18:00:00', 'Active'),
(10, '2026-01-14', 5, 5, 'FISH-MAHSEER', 50.00, 200.00, 1, '2026-01-14 18:00:00', 'Active')
ON DUPLICATE KEY UPDATE `fish_weight`=VALUES(`fish_weight`), `status`=VALUES(`status`);

-- --------------------------------------------------------
-- 28. psac_production_carret (10 records)
-- --------------------------------------------------------
INSERT INTO `psac_production_carret` (`id`, `production_id`, `carret_number`, `carret_weight`) VALUES
(1, 1, 'CRT-001', 30.00),
(2, 1, 'CRT-002', 30.00),
(3, 2, 'CRT-003', 32.00),
(4, 2, 'CRT-004', 32.00),
(5, 3, 'CRT-005', 30.00),
(6, 4, 'CRT-006', 20.00),
(7, 5, 'CRT-007', 20.00),
(8, 6, 'CRT-008', 30.00),
(9, 7, 'CRT-009', 30.00),
(10, 8, 'CRT-010', 25.00)
ON DUPLICATE KEY UPDATE `carret_weight`=VALUES(`carret_weight`);

-- --------------------------------------------------------
-- 29. psac_production_carret_items (10 records)
-- --------------------------------------------------------
INSERT INTO `psac_production_carret_items` (`id`, `carret_id`, `fish_code`, `fish_qty`, `fish_wt`) VALUES
(1, 1, 'FISH-ROHU', 10.00, 30.00),
(2, 2, 'FISH-ROHU', 10.00, 30.00),
(3, 3, 'FISH-CATLA', 8.00, 32.00),
(4, 4, 'FISH-CATLA', 8.00, 32.00),
(5, 5, 'FISH-MRIGAL', 12.00, 30.00),
(6, 6, 'FISH-SINGHI', 40.00, 20.00),
(7, 7, 'FISH-MAGUR', 30.00, 20.00),
(8, 8, 'FISH-SILVER', 10.00, 30.00),
(9, 9, 'FISH-GRASS', 10.00, 30.00),
(10, 10, 'FISH-TENGRA', 50.00, 25.00)
ON DUPLICATE KEY UPDATE `fish_wt`=VALUES(`fish_wt`);

-- --------------------------------------------------------
-- 30. psac_product_box (10 records)
-- --------------------------------------------------------
INSERT INTO `psac_product_box` (`id`, `box_number`, `depot_id`, `market_id`, `fish_code`, `box_weight`, `carret_weight`, `box_status`, `packing_date`, `added_by`, `added_date`, `status`) VALUES
(1, 'BOX-GS-001', 1, 1, 'FISH-ROHU', 30.00, 32.00, 'Packed', '2026-01-10', 1, '2026-01-10 10:00:00', 'Active'),
(2, 'BOX-GS-002', 1, 1, 'FISH-CATLA', 32.00, 34.00, 'Packed', '2026-01-10', 1, '2026-01-10 10:15:00', 'Active'),
(3, 'BOX-GS-003', 2, 2, 'FISH-MRIGAL', 30.00, 32.00, 'Packed', '2026-01-11', 1, '2026-01-11 10:00:00', 'Active'),
(4, 'BOX-GS-004', 2, 2, 'FISH-SINGHI', 20.00, 22.00, 'Packed', '2026-01-11', 1, '2026-01-11 10:30:00', 'Active'),
(5, 'BOX-GS-005', 3, 3, 'FISH-MAGUR', 20.00, 22.00, 'Packed', '2026-01-12', 1, '2026-01-12 09:00:00', 'Active'),
(6, 'BOX-GS-006', 3, 3, 'FISH-SILVER', 30.00, 32.00, 'Packed', '2026-01-12', 1, '2026-01-12 09:30:00', 'Active'),
(7, 'BOX-GS-007', 4, 4, 'FISH-GRASS', 30.00, 32.00, 'Packed', '2026-01-13', 1, '2026-01-13 10:00:00', 'Active'),
(8, 'BOX-GS-008', 4, 4, 'FISH-TENGRA', 25.00, 27.00, 'Packed', '2026-01-13', 1, '2026-01-13 10:30:00', 'Active'),
(9, 'BOX-GS-009', 5, 5, 'FISH-PANGA', 30.00, 32.00, 'Packed', '2026-01-14', 1, '2026-01-14 09:30:00', 'Active'),
(10, 'BOX-GS-010', 5, 5, 'FISH-MAHSEER', 25.00, 27.00, 'Packed', '2026-01-14', 1, '2026-01-14 10:00:00', 'Active')
ON DUPLICATE KEY UPDATE `box_weight`=VALUES(`box_weight`), `status`=VALUES(`status`);

-- --------------------------------------------------------
-- 31. psac_product_box_items (10 records)
-- --------------------------------------------------------
INSERT INTO `psac_product_box_items` (`id`, `box_id`, `fish_code`, `fish_qty`, `fish_wt`) VALUES
(1, 1, 'FISH-ROHU', 10.00, 30.00),
(2, 2, 'FISH-CATLA', 8.00, 32.00),
(3, 3, 'FISH-MRIGAL', 12.00, 30.00),
(4, 4, 'FISH-SINGHI', 40.00, 20.00),
(5, 5, 'FISH-MAGUR', 30.00, 20.00),
(6, 6, 'FISH-SILVER', 10.00, 30.00),
(7, 7, 'FISH-GRASS', 10.00, 30.00),
(8, 8, 'FISH-TENGRA', 50.00, 25.00),
(9, 9, 'FISH-PANGA', 10.00, 30.00),
(10, 10, 'FISH-MAHSEER', 6.00, 25.00)
ON DUPLICATE KEY UPDATE `fish_wt`=VALUES(`fish_wt`);

-- --------------------------------------------------------
-- 32. psac_product_dr (10 records)
-- --------------------------------------------------------
INSERT INTO `psac_product_dr` (`id`, `dr_number`, `dr_date`, `depot_id`, `market_id`, `status`) VALUES
(1, 'DR-2026-001', '2026-01-10', 1, 1, 'Active'),
(2, 'DR-2026-002', '2026-01-10', 1, 2, 'Active'),
(3, 'DR-2026-003', '2026-01-11', 2, 3, 'Active'),
(4, 'DR-2026-004', '2026-01-11', 2, 6, 'Active'),
(5, 'DR-2026-005', '2026-01-12', 3, 7, 'Active'),
(6, 'DR-2026-006', '2026-01-12', 3, 8, 'Active'),
(7, 'DR-2026-007', '2026-01-13', 4, 4, 'Active'),
(8, 'DR-2026-008', '2026-01-13', 4, 9, 'Active'),
(9, 'DR-2026-009', '2026-01-14', 5, 5, 'Active'),
(10, 'DR-2026-010', '2026-01-14', 5, 10, 'Active')
ON DUPLICATE KEY UPDATE `dr_number`=VALUES(`dr_number`), `status`=VALUES(`status`);

-- --------------------------------------------------------
-- 33. psac_product_dispatch (10 records)
-- --------------------------------------------------------
INSERT INTO `psac_product_dispatch` (`id`, `dr_number`, `dispatch_date`, `dispatch_from`, `dispatch_to`, `market_id`, `vehicle_number`, `driver_name`, `driver_mobile_no`, `total_box_qty`, `total_box_wt`, `advance_freight`, `remaining_freight`, `total_freight`, `dispatch_status`, `remark`, `added_by`, `added_date`, `status`) VALUES
(1, 'DR-2026-001', '2026-01-10', 1, 2, 1, 'MP-14-GA-1234', 'Ramesh Gurjar', '9893012341', 50, 1500.00, 3000.00, 4500.00, 7500.00, 'Dispatched', 'Indore Morning Dispatch', 1, '2026-01-10 11:00:00', 'Active'),
(2, 'DR-2026-002', '2026-01-10', 1, 3, 2, 'MP-14-GA-1235', 'Sitaram Patidar', '9893012342', 40, 1200.00, 2500.00, 3500.00, 6000.00, 'Dispatched', 'Ujjain Delivery', 1, '2026-01-10 11:30:00', 'Active'),
(3, 'DR-2026-003', '2026-01-11', 2, 6, 6, 'RJ-20-GA-5678', 'Jagdish Meena', '9893012343', 100, 3200.00, 8000.00, 12000.00, 20000.00, 'Dispatched', 'Delhi Ghazipur Reefer', 1, '2026-01-11 12:00:00', 'Active'),
(4, 'DR-2026-004', '2026-01-11', 2, 7, 7, 'RJ-20-GA-5679', 'Bheru Singh', '9893012344', 80, 2500.00, 5000.00, 8000.00, 13000.00, 'Dispatched', 'Jaipur Muhana Market', 1, '2026-01-11 12:30:00', 'Active'),
(5, 'DR-2026-005', '2026-01-12', 3, 8, 8, 'GJ-01-TA-9988', 'Pravin Patel', '9893012345', 70, 2100.00, 6000.00, 9000.00, 15000.00, 'Dispatched', 'Ahmedabad Interstate', 1, '2026-01-12 11:00:00', 'Active'),
(6, 'DR-2026-006', '2026-01-12', 3, 1, 1, 'MP-14-GA-1236', 'Kalu Ram', '9893012346', 30, 900.00, 2000.00, 2500.00, 4500.00, 'Dispatched', 'Local Depot Transfer', 1, '2026-01-12 11:30:00', 'Active'),
(7, 'DR-2026-007', '2026-01-13', 4, 10, 10, 'MP-09-KA-4455', 'Mukesh Tanwar', '9893012347', 60, 1800.00, 3500.00, 5500.00, 9000.00, 'Dispatched', 'Bhopal Sagar Consignment', 1, '2026-01-13 10:30:00', 'Active'),
(8, 'DR-2026-008', '2026-01-13', 4, 5, 5, 'MP-14-GA-1237', 'Balram Dhangar', '9893012348', 35, 1050.00, 2000.00, 3000.00, 5000.00, 'Dispatched', 'Kota Daily Run', 1, '2026-01-13 11:00:00', 'Active'),
(9, 'DR-2026-009', '2026-01-14', 5, 2, 2, 'MP-14-GA-1238', 'Sunil Solanki', '9893012349', 45, 1350.00, 2500.00, 4000.00, 6500.00, 'Dispatched', 'Rampura Central Transfer', 1, '2026-01-14 10:00:00', 'Active'),
(10, 'DR-2026-010', '2026-01-14', 5, 6, 6, 'UP-80-BT-7711', 'Vikram Yadav', '9893012350', 90, 2800.00, 7000.00, 11000.00, 18000.00, 'Dispatched', 'Agra-Delhi Express Load', 1, '2026-01-14 11:00:00', 'Active')
ON DUPLICATE KEY UPDATE `total_box_wt`=VALUES(`total_box_wt`), `status`=VALUES(`status`);

-- --------------------------------------------------------
-- 34. psac_product_dispatch_box (10 records)
-- --------------------------------------------------------
INSERT INTO `psac_product_dispatch_box` (`id`, `dispatch_id`, `box_number`, `box_weight`) VALUES
(1, 1, 'BOX-GS-001', 30.00),
(2, 1, 'BOX-GS-002', 32.00),
(3, 2, 'BOX-GS-003', 30.00),
(4, 3, 'BOX-GS-004', 20.00),
(5, 4, 'BOX-GS-005', 20.00),
(6, 5, 'BOX-GS-006', 30.00),
(7, 6, 'BOX-GS-007', 30.00),
(8, 7, 'BOX-GS-008', 25.00),
(9, 8, 'BOX-GS-009', 30.00),
(10, 9, 'BOX-GS-010', 25.00)
ON DUPLICATE KEY UPDATE `box_weight`=VALUES(`box_weight`);

-- --------------------------------------------------------
-- 35. psac_product_inward (10 records)
-- --------------------------------------------------------
INSERT INTO `psac_product_inward` (`id`, `inward_date`, `depot_id`, `fisherman_id`, `grand_total`, `added_by`, `added_date`, `status`) VALUES
(1, '2026-01-10', 1, 1, 45000.00, 1, '2026-01-10 09:00:00', 'Active'),
(2, '2026-01-10', 1, 2, 38000.00, 1, '2026-01-10 09:30:00', 'Active'),
(3, '2026-01-11', 2, 3, 52000.00, 1, '2026-01-11 09:00:00', 'Active'),
(4, '2026-01-11', 2, 4, 29000.00, 1, '2026-01-11 09:30:00', 'Active'),
(5, '2026-01-12', 3, 5, 41000.00, 1, '2026-01-12 09:00:00', 'Active'),
(6, '2026-01-12', 3, 6, 36000.00, 1, '2026-01-12 09:30:00', 'Active'),
(7, '2026-01-13', 4, 7, 33000.00, 1, '2026-01-13 09:00:00', 'Active'),
(8, '2026-01-13', 4, 8, 47000.00, 1, '2026-01-13 09:30:00', 'Active'),
(9, '2026-01-14', 5, 9, 26000.00, 1, '2026-01-14 09:00:00', 'Active'),
(10, '2026-01-14', 5, 10, 58000.00, 1, '2026-01-14 09:30:00', 'Active')
ON DUPLICATE KEY UPDATE `grand_total`=VALUES(`grand_total`), `status`=VALUES(`status`);

-- --------------------------------------------------------
-- 36. psac_product_inward_return (10 records)
-- --------------------------------------------------------
INSERT INTO `psac_product_inward_return` (`id`, `inward_id`, `return_date`, `total_price`, `status`) VALUES
(1, 1, '2026-01-10', 1500.00, 'Active'),
(2, 2, '2026-01-10', 1200.00, 'Active'),
(3, 3, '2026-01-11', 2100.00, 'Active'),
(4, 4, '2026-01-11', 800.00, 'Active'),
(5, 5, '2026-01-12', 1600.00, 'Active'),
(6, 6, '2026-01-12', 950.00, 'Active'),
(7, 7, '2026-01-13', 1100.00, 'Active'),
(8, 8, '2026-01-13', 1750.00, 'Active'),
(9, 9, '2026-01-14', 700.00, 'Active'),
(10, 10, '2026-01-14', 2400.00, 'Active')
ON DUPLICATE KEY UPDATE `total_price`=VALUES(`total_price`), `status`=VALUES(`status`);

-- --------------------------------------------------------
-- 37. psac_product_outward (10 records)
-- --------------------------------------------------------
INSERT INTO `psac_product_outward` (`id`, `outward_date`, `outward_to`, `fisherman_id`, `main_group_id`, `grand_total`, `added_by`, `added_date`, `status`) VALUES
(1, '2026-01-10', 'Fisherman', 1, 1, 8500.00, 1, '2026-01-10 14:00:00', 'Active'),
(2, '2026-01-10', 'Group', 2, 1, 12000.00, 1, '2026-01-10 14:30:00', 'Active'),
(3, '2026-01-11', 'Fisherman', 3, 2, 9400.00, 1, '2026-01-11 14:00:00', 'Active'),
(4, '2026-01-11', 'Group', 4, 2, 15500.00, 1, '2026-01-11 14:30:00', 'Active'),
(5, '2026-01-12', 'Fisherman', 5, 3, 7200.00, 1, '2026-01-12 14:00:00', 'Active'),
(6, '2026-01-12', 'Group', 6, 3, 11800.00, 1, '2026-01-12 14:30:00', 'Active'),
(7, '2026-01-13', 'Fisherman', 7, 4, 6900.00, 1, '2026-01-13 14:00:00', 'Active'),
(8, '2026-01-13', 'Group', 8, 4, 13400.00, 1, '2026-01-13 14:30:00', 'Active'),
(9, '2026-01-14', 'Fisherman', 9, 5, 5500.00, 1, '2026-01-14 14:00:00', 'Active'),
(10, '2026-01-14', 'Group', 10, 5, 17200.00, 1, '2026-01-14 14:30:00', 'Active')
ON DUPLICATE KEY UPDATE `grand_total`=VALUES(`grand_total`), `status`=VALUES(`status`);

-- --------------------------------------------------------
-- 38. psac_product_outward_item (10 records)
-- --------------------------------------------------------
INSERT INTO `psac_product_outward_item` (`id`, `outward_id`, `fish_code`, `quantity`, `price`) VALUES
(1, 1, 'FISH-ROHU', 50.00, 8500.00),
(2, 2, 'FISH-CATLA', 60.00, 12000.00),
(3, 3, 'FISH-MRIGAL', 60.00, 9400.00),
(4, 4, 'FISH-SINGHI', 50.00, 15500.00),
(5, 5, 'FISH-MAGUR', 20.00, 7200.00),
(6, 6, 'FISH-SILVER', 90.00, 11800.00),
(7, 7, 'FISH-GRASS', 45.00, 6900.00),
(8, 8, 'FISH-TENGRA', 55.00, 13400.00),
(9, 9, 'FISH-PANGA', 45.00, 5500.00),
(10, 10, 'FISH-MAHSEER', 38.00, 17200.00)
ON DUPLICATE KEY UPDATE `price`=VALUES(`price`);

-- --------------------------------------------------------
-- 39. psac_product_outward_item_return (10 records)
-- --------------------------------------------------------
INSERT INTO `psac_product_outward_item_return` (`id`, `outward_id`, `fisherman_id`, `main_group_id`, `outward_to`, `total_price`, `return_date`, `status`) VALUES
(1, 1, 1, 1, 'Fisherman', 500.00, '2026-01-10', 'Active'),
(2, 2, 2, 1, 'Group', 800.00, '2026-01-10', 'Active'),
(3, 3, 3, 2, 'Fisherman', 600.00, '2026-01-11', 'Active'),
(4, 4, 4, 2, 'Group', 1100.00, '2026-01-11', 'Active'),
(5, 5, 5, 3, 'Fisherman', 450.00, '2026-01-12', 'Active'),
(6, 6, 6, 3, 'Group', 750.00, '2026-01-12', 'Active'),
(7, 7, 7, 4, 'Fisherman', 400.00, '2026-01-13', 'Active'),
(8, 8, 8, 4, 'Group', 900.00, '2026-01-13', 'Active'),
(9, 9, 9, 5, 'Fisherman', 350.00, '2026-01-14', 'Active'),
(10, 10, 10, 5, 'Group', 1250.00, '2026-01-14', 'Active')
ON DUPLICATE KEY UPDATE `total_price`=VALUES(`total_price`), `status`=VALUES(`status`);

-- --------------------------------------------------------
-- 40. psac_closing_stock (10 records)
-- --------------------------------------------------------
INSERT INTO `psac_closing_stock` (`id`, `stock_date`, `depot_id`, `market_id`, `total_weight`, `added_by`, `added_date`, `status`) VALUES
(1, '2026-01-10', 1, 1, 1250.00, 1, '2026-01-10 20:00:00', 'Active'),
(2, '2026-01-10', 2, 2, 980.00, 1, '2026-01-10 20:00:00', 'Active'),
(3, '2026-01-11', 1, 1, 1420.00, 1, '2026-01-11 20:00:00', 'Active'),
(4, '2026-01-11', 2, 2, 1150.00, 1, '2026-01-11 20:00:00', 'Active'),
(5, '2026-01-12', 3, 3, 850.00, 1, '2026-01-12 20:00:00', 'Active'),
(6, '2026-01-12', 4, 4, 920.00, 1, '2026-01-12 20:00:00', 'Active'),
(7, '2026-01-13', 3, 3, 760.00, 1, '2026-01-13 20:00:00', 'Active'),
(8, '2026-01-13', 4, 4, 880.00, 1, '2026-01-13 20:00:00', 'Active'),
(9, '2026-01-14', 5, 5, 1340.00, 1, '2026-01-14 20:00:00', 'Active'),
(10, '2026-01-14', 6, 6, 1650.00, 1, '2026-01-14 20:00:00', 'Active')
ON DUPLICATE KEY UPDATE `total_weight`=VALUES(`total_weight`), `status`=VALUES(`status`);

-- --------------------------------------------------------
-- 41. psac_closing_stock_items (10 records)
-- --------------------------------------------------------
INSERT INTO `psac_closing_stock_items` (`id`, `stock_id`, `fish_code`, `quantity`, `weight`) VALUES
(1, 1, 'FISH-ROHU', 250.00, 750.00),
(2, 1, 'FISH-CATLA', 125.00, 500.00),
(3, 2, 'FISH-MRIGAL', 200.00, 500.00),
(4, 2, 'FISH-SINGHI', 300.00, 480.00),
(5, 3, 'FISH-MAGUR', 400.00, 800.00),
(6, 4, 'FISH-SILVER', 200.00, 600.00),
(7, 5, 'FISH-GRASS', 150.00, 450.00),
(8, 6, 'FISH-TENGRA', 500.00, 250.00),
(9, 7, 'FISH-PANGA', 200.00, 600.00),
(10, 8, 'FISH-MAHSEER', 100.00, 400.00)
ON DUPLICATE KEY UPDATE `weight`=VALUES(`weight`);

-- --------------------------------------------------------
-- 42. psac_daily_closing_stock (10 records)
-- --------------------------------------------------------
INSERT INTO `psac_daily_closing_stock` (`id`, `stock_date`, `depot_id`, `market_id`, `fish_code`, `quantity`, `weight`, `added_by`, `added_date`, `status`) VALUES
(1, '2026-01-10', 1, 1, 'FISH-ROHU', 250.00, 750.00, 1, '2026-01-10 20:00:00', 'Active'),
(2, '2026-01-10', 1, 1, 'FISH-CATLA', 125.00, 500.00, 1, '2026-01-10 20:00:00', 'Active'),
(3, '2026-01-11', 2, 2, 'FISH-MRIGAL', 200.00, 500.00, 1, '2026-01-11 20:00:00', 'Active'),
(4, '2026-01-11', 2, 2, 'FISH-SINGHI', 300.00, 480.00, 1, '2026-01-11 20:00:00', 'Active'),
(5, '2026-01-12', 3, 3, 'FISH-MAGUR', 400.00, 800.00, 1, '2026-01-12 20:00:00', 'Active'),
(6, '2026-01-12', 3, 3, 'FISH-SILVER', 200.00, 600.00, 1, '2026-01-12 20:00:00', 'Active'),
(7, '2026-01-13', 4, 4, 'FISH-GRASS', 150.00, 450.00, 1, '2026-01-13 20:00:00', 'Active'),
(8, '2026-01-13', 4, 4, 'FISH-TENGRA', 500.00, 250.00, 1, '2026-01-13 20:00:00', 'Active'),
(9, '2026-01-14', 5, 5, 'FISH-PANGA', 200.00, 600.00, 1, '2026-01-14 20:00:00', 'Active'),
(10, '2026-01-14', 5, 5, 'FISH-MAHSEER', 100.00, 400.00, 1, '2026-01-14 20:00:00', 'Active')
ON DUPLICATE KEY UPDATE `weight`=VALUES(`weight`), `status`=VALUES(`status`);

-- --------------------------------------------------------
-- 43. psac_daily_closing_stock_box (10 records)
-- --------------------------------------------------------
INSERT INTO `psac_daily_closing_stock_box` (`id`, `stock_id`, `box_number`, `weight`) VALUES
(1, 1, 'BOX-GS-001', 30.00),
(2, 2, 'BOX-GS-002', 32.00),
(3, 3, 'BOX-GS-003', 30.00),
(4, 4, 'BOX-GS-004', 20.00),
(5, 5, 'BOX-GS-005', 20.00),
(6, 6, 'BOX-GS-006', 30.00),
(7, 7, 'BOX-GS-007', 30.00),
(8, 8, 'BOX-GS-008', 25.00),
(9, 9, 'BOX-GS-009', 30.00),
(10, 10, 'BOX-GS-010', 25.00)
ON DUPLICATE KEY UPDATE `weight`=VALUES(`weight`);

-- --------------------------------------------------------
-- 44. psac_destroyed (10 records)
-- --------------------------------------------------------
INSERT INTO `psac_destroyed` (`id`, `record_date`, `depot_id`, `market_id`, `total_weight`, `added_by`, `added_date`, `status`) VALUES
(1, '2026-01-10', 1, 1, 25.00, 1, '2026-01-10 19:00:00', 'Active'),
(2, '2026-01-10', 2, 2, 18.00, 1, '2026-01-10 19:00:00', 'Active'),
(3, '2026-01-11', 1, 1, 30.00, 1, '2026-01-11 19:00:00', 'Active'),
(4, '2026-01-11', 2, 2, 15.00, 1, '2026-01-11 19:00:00', 'Active'),
(5, '2026-01-12', 3, 3, 22.00, 1, '2026-01-12 19:00:00', 'Active'),
(6, '2026-01-12', 4, 4, 14.00, 1, '2026-01-12 19:00:00', 'Active'),
(7, '2026-01-13', 3, 3, 28.00, 1, '2026-01-13 19:00:00', 'Active'),
(8, '2026-01-13', 4, 4, 16.00, 1, '2026-01-13 19:00:00', 'Active'),
(9, '2026-01-14', 5, 5, 20.00, 1, '2026-01-14 19:00:00', 'Active'),
(10, '2026-01-14', 5, 5, 12.00, 1, '2026-01-14 19:00:00', 'Active')
ON DUPLICATE KEY UPDATE `total_weight`=VALUES(`total_weight`), `status`=VALUES(`status`);

-- --------------------------------------------------------
-- 45. psac_destroyed_items (10 records)
-- --------------------------------------------------------
INSERT INTO `psac_destroyed_items` (`id`, `destroyed_id`, `fish_code`, `weight`) VALUES
(1, 1, 'FISH-SILVER', 25.00),
(2, 2, 'FISH-MRIGAL', 18.00),
(3, 3, 'FISH-ROHU', 30.00),
(4, 4, 'FISH-CATLA', 15.00),
(5, 5, 'FISH-GRASS', 22.00),
(6, 6, 'FISH-PANGA', 14.00),
(7, 7, 'FISH-SILVER', 28.00),
(8, 8, 'FISH-TENGRA', 16.00),
(9, 9, 'FISH-MRIGAL', 20.00),
(10, 10, 'FISH-MAGUR', 12.00)
ON DUPLICATE KEY UPDATE `weight`=VALUES(`weight`);

-- --------------------------------------------------------
-- 46. psac_sale (10 records)
-- --------------------------------------------------------
INSERT INTO `psac_sale` (`id`, `invoice_number`, `dr_number`, `sale_date`, `client_id`, `client_name`, `market_type`, `market_category`, `market_id`, `gross_wt`, `net_wt`, `gross_sale`, `net_sale`, `discount_perc`, `discount_amount`, `commission`, `ice_amount`, `destroyed_amount`, `total_expenses`, `sub_total`, `grand_total`, `received_amt`, `balance`, `payment_mode`, `payment_date`, `remark`, `added_by`, `added_date`, `status`) VALUES
(1, 'INV-2026-001', 'DR-2026-001', '2026-01-10', 1, 'Agrawal Fish Traders', 1, 1, 1, 450.00, 440.00, 81000.00, 79200.00, 2.00, 1584.00, 5.00, 500.00, 0.00, 1200.00, 77616.00, 78116.00, 50000.00, 28116.00, 'Cash', '2026-01-10', 'Depot Sale Invoice 1', 1, '2026-01-10 12:00:00', 'Active'),
(2, 'INV-2026-002', 'DR-2026-002', '2026-01-10', 2, 'Malwa Fresh Fish Co.', 1, 2, 2, 320.00, 310.00, 67200.00, 65100.00, 0.00, 0.00, 5.00, 400.00, 0.00, 950.00, 65100.00, 65500.00, 65500.00, 0.00, 'Bank Transfer', '2026-01-10', 'Full Payment Settled', 1, '2026-01-10 12:30:00', 'Active'),
(3, 'INV-2026-003', 'DR-2026-003', '2026-01-11', 3, 'Rajputana Fish Center', 2, 3, 3, 500.00, 485.00, 82500.00, 80025.00, 1.50, 1200.00, 4.00, 650.00, 0.00, 2500.00, 78825.00, 79475.00, 40000.00, 39475.00, 'Cheque', '2026-01-11', 'Interstate Consignment', 1, '2026-01-11 13:00:00', 'Active'),
(4, 'INV-2026-004', 'DR-2026-004', '2026-01-11', 4, 'Ghazipur Fish Suppliers', 2, 3, 6, 800.00, 775.00, 168000.00, 162750.00, 3.00, 4882.50, 5.00, 1200.00, 0.00, 4500.00, 157867.50, 159067.50, 100000.00, 59067.50, 'NEFT', '2026-01-11', 'Delhi Truckload Sale', 1, '2026-01-11 14:00:00', 'Active'),
(5, 'INV-2026-005', 'DR-2026-005', '2026-01-12', 5, 'Chambal River Foods', 1, 2, 4, 250.00, 245.00, 45000.00, 44100.00, 0.00, 0.00, 5.00, 300.00, 0.00, 800.00, 44100.00, 44400.00, 30000.00, 14400.00, 'Cash', '2026-01-12', 'Kota Daily Run', 1, '2026-01-12 11:30:00', 'Active'),
(6, 'INV-2026-006', 'DR-2026-006', '2026-01-12', 6, 'Bhopal Sagar Seafoods', 1, 2, 5, 420.00, 410.00, 54600.00, 53300.00, 2.00, 1066.00, 5.00, 550.00, 0.00, 1500.00, 52234.00, 52784.00, 35000.00, 17784.00, 'UPI', '2026-01-12', 'Bhopal Direct Delivery', 1, '2026-01-12 12:00:00', 'Active'),
(7, 'INV-2026-007', 'DR-2026-007', '2026-01-13', 7, 'Gujarat Oceanic Traders', 2, 3, 8, 600.00, 580.00, 87000.00, 84100.00, 2.50, 2102.50, 4.50, 900.00, 0.00, 3200.00, 81997.50, 82897.50, 50000.00, 32897.50, 'RTGS', '2026-01-13', 'Ahmedabad Consignment', 1, '2026-01-13 13:30:00', 'Active'),
(8, 'INV-2026-008', 'DR-2026-008', '2026-01-13', 8, 'Ujjain Mahakal Fish Mart', 1, 1, 7, 280.00, 275.00, 67200.00, 66000.00, 0.00, 0.00, 5.00, 350.00, 0.00, 750.00, 66000.00, 66350.00, 50000.00, 16350.00, 'Cash', '2026-01-13', 'Ujjain Point Sale', 1, '2026-01-13 14:00:00', 'Active'),
(9, 'INV-2026-009', 'DR-2026-009', '2026-01-14', 9, 'Gwalior Chambal Traders', 1, 2, 9, 350.00, 340.00, 42000.00, 40800.00, 1.00, 408.00, 5.00, 450.00, 0.00, 1100.00, 40392.00, 40842.00, 25000.00, 15842.00, 'Bank Transfer', '2026-01-14', 'Gwalior Van Supply', 1, '2026-01-14 11:30:00', 'Active'),
(10, 'INV-2026-010', 'DR-2026-010', '2026-01-14', 10, 'National Cold Chain Logistics', 2, 3, 10, 750.00, 725.00, 135000.00, 130500.00, 3.00, 3915.00, 5.00, 1100.00, 0.00, 4200.00, 126585.00, 127685.00, 80000.00, 47685.00, 'NEFT', '2026-01-14', 'Agra Truckload Supply', 1, '2026-01-14 12:30:00', 'Active')
ON DUPLICATE KEY UPDATE `grand_total`=VALUES(`grand_total`), `status`=VALUES(`status`);

-- --------------------------------------------------------
-- 47. psac_sale_items (10 records)
-- --------------------------------------------------------
INSERT INTO `psac_sale_items` (`id`, `sale_id`, `fish_code`, `fish_quantity`, `fish_weight`, `fish_rate`, `amount`) VALUES
(1, 1, 'FISH-ROHU', 150.00, 450.00, 180.00, 81000.00),
(2, 2, 'FISH-CATLA', 80.00, 320.00, 210.00, 67200.00),
(3, 3, 'FISH-MRIGAL', 200.00, 500.00, 165.00, 82500.00),
(4, 4, 'FISH-CATLA', 200.00, 800.00, 210.00, 168000.00),
(5, 5, 'FISH-ROHU', 90.00, 250.00, 180.00, 45000.00),
(6, 6, 'FISH-SILVER', 140.00, 420.00, 130.00, 54600.00),
(7, 7, 'FISH-GRASS', 200.00, 600.00, 145.00, 87000.00),
(8, 8, 'FISH-TENGRA', 560.00, 280.00, 240.00, 67200.00),
(9, 9, 'FISH-PANGA', 120.00, 350.00, 120.00, 42000.00),
(10, 10, 'FISH-ROHU', 250.00, 750.00, 180.00, 135000.00)
ON DUPLICATE KEY UPDATE `amount`=VALUES(`amount`);

-- --------------------------------------------------------
-- 48. psac_sale_expenditure (10 records)
-- --------------------------------------------------------
INSERT INTO `psac_sale_expenditure` (`id`, `sale_id`, `particular`, `amount`) VALUES
(1, 1, 'Ice Block Supply', 500.00),
(2, 1, 'Loading Labor', 700.00),
(3, 2, 'Ice & Crushing', 400.00),
(4, 3, 'Interstate Toll Fee', 1200.00),
(5, 4, 'Reefer Truck Diesel Surcharge', 3500.00),
(6, 5, 'Local Van Freight', 800.00),
(7, 6, 'Thermocol Boxes & Strapping', 1500.00),
(8, 7, 'Highway Permit & Weight Bridge', 1800.00),
(9, 9, 'Sorting Labor Charges', 650.00),
(10, 10, 'Long Haul Reefer Packaging', 2400.00)
ON DUPLICATE KEY UPDATE `amount`=VALUES(`amount`);

-- --------------------------------------------------------
-- 49. psac_sale_challan_detail (10 records)
-- --------------------------------------------------------
INSERT INTO `psac_sale_challan_detail` (`id`, `sale_id`, `challan_number`, `details`) VALUES
(1, 1, 'CH-2026-001', 'Delivery to Indore Mandi gate via vehicle MP-14-GA-1234'),
(2, 2, 'CH-2026-002', 'Delivery to Malwa Fresh Hub, Ujjain road'),
(3, 3, 'CH-2026-003', 'Jaipur consignment under bill of lading 5678'),
(4, 4, 'CH-2026-004', 'Delhi Ghazipur direct truckload reefer 9988'),
(5, 5, 'CH-2026-005', 'Kota daily depot transfer delivery'),
(6, 6, 'CH-2026-006', 'Bhopal central hub insulated crates delivery'),
(7, 7, 'CH-2026-007', 'Ahmedabad wholesale mandi dispatch'),
(8, 8, 'CH-2026-008', 'Ujjain direct client pickup challan'),
(9, 9, 'CH-2026-009', 'Gwalior market van route distribution'),
(10, 10, 'CH-2026-010', 'Agra interstate long haul delivery challan')
ON DUPLICATE KEY UPDATE `challan_number`=VALUES(`challan_number`);

-- --------------------------------------------------------
-- 50. psac_free_sale (10 records)
-- --------------------------------------------------------
INSERT INTO `psac_free_sale` (`id`, `invoice_number`, `free_sale_date`, `client_id`, `company_name`, `contact_number`, `email`, `total_quantity`, `total_weight`, `received_amount`, `market_type`, `market_category`, `market_id`, `remark`, `added_by`, `added_date`, `status`) VALUES
(1, 'FS-2026-001', '2026-01-10', 1, 'Agrawal Fish Traders', '9827012345', 'agrawal.fish@gmail.com', 10.00, 30.00, 0.00, 1, 1, 1, 'Promotional Sample Major Carp', 1, '2026-01-10 10:00:00', 'Active'),
(2, 'FS-2026-002', '2026-01-10', 2, 'Malwa Fresh Fish Co.', '9827023456', 'malwafresh@gmail.com', 8.00, 24.00, 0.00, 1, 2, 2, 'Sample Tasting Lot', 1, '2026-01-10 10:30:00', 'Active'),
(3, 'FS-2026-003', '2026-01-11', 3, 'Rajputana Fish Center', '9827034567', 'rajputana.fish@gmail.com', 12.00, 36.00, 0.00, 2, 3, 3, 'Trial Consignment Box', 1, '2026-01-11 11:00:00', 'Active'),
(4, 'FS-2026-004', '2026-01-11', 4, 'Ghazipur Fish Suppliers', '9827045678', 'ghazipur.fish@gmail.com', 15.00, 45.00, 0.00, 2, 3, 6, 'Quality Inspection Batch', 1, '2026-01-11 11:30:00', 'Active'),
(5, 'FS-2026-005', '2026-01-12', 5, 'Chambal River Foods', '9827056789', 'chambal.foods@gmail.com', 6.00, 18.00, 0.00, 1, 2, 4, 'Promotional Live Catfish', 1, '2026-01-12 09:00:00', 'Active'),
(6, 'FS-2026-006', '2026-01-12', 6, 'Bhopal Sagar Seafoods', '9827067890', 'bhopal.sagar@gmail.com', 10.00, 30.00, 0.00, 1, 2, 5, 'Customer Sampling Crates', 1, '2026-01-12 09:30:00', 'Active'),
(7, 'FS-2026-007', '2026-01-13', 7, 'Gujarat Oceanic Traders', '9827078901', 'gujarat.oceanic@gmail.com', 14.00, 42.00, 0.00, 2, 3, 8, 'Trial Shipment Inspection', 1, '2026-01-13 10:00:00', 'Active'),
(8, 'FS-2026-008', '2026-01-13', 8, 'Ujjain Mahakal Fish Mart', '9827089012', 'mahakal.fish@gmail.com', 8.00, 20.00, 0.00, 1, 1, 7, 'Local Stall Tasting Box', 1, '2026-01-13 10:30:00', 'Active'),
(9, 'FS-2026-009', '2026-01-14', 9, 'Gwalior Chambal Traders', '9827090123', 'gwalior.chambal@gmail.com', 10.00, 25.00, 0.00, 1, 2, 9, 'Van Sample Box', 1, '2026-01-14 09:00:00', 'Active'),
(10, 'FS-2026-010', '2026-01-14', 10, 'National Cold Chain Logistics', '9827001234', 'ncc.logistics@gmail.com', 20.00, 50.00, 0.00, 2, 3, 10, 'Export Grade Test Sample', 1, '2026-01-14 09:30:00', 'Active')
ON DUPLICATE KEY UPDATE `total_weight`=VALUES(`total_weight`), `status`=VALUES(`status`);

-- --------------------------------------------------------
-- 51. psac_free_sale_items (10 records)
-- --------------------------------------------------------
INSERT INTO `psac_free_sale_items` (`id`, `free_sale_id`, `fish_code`, `fish_quantity`, `fish_weight`, `fish_rate`, `amount`) VALUES
(1, 1, 'FISH-ROHU', 10.00, 30.00, 0.00, 0.00),
(2, 2, 'FISH-CATLA', 8.00, 24.00, 0.00, 0.00),
(3, 3, 'FISH-MRIGAL', 12.00, 36.00, 0.00, 0.00),
(4, 4, 'FISH-ROHU', 15.00, 45.00, 0.00, 0.00),
(5, 5, 'FISH-SINGHI', 6.00, 18.00, 0.00, 0.00),
(6, 6, 'FISH-SILVER', 10.00, 30.00, 0.00, 0.00),
(7, 7, 'FISH-GRASS', 14.00, 42.00, 0.00, 0.00),
(8, 8, 'FISH-TENGRA', 8.00, 20.00, 0.00, 0.00),
(9, 9, 'FISH-PANGA', 10.00, 25.00, 0.00, 0.00),
(10, 10, 'FISH-MAHSEER', 20.00, 50.00, 0.00, 0.00)
ON DUPLICATE KEY UPDATE `fish_weight`=VALUES(`fish_weight`);

-- --------------------------------------------------------
-- 52. psac_cash_received (10 records)
-- --------------------------------------------------------
INSERT INTO `psac_cash_received` (`id`, `client_id`, `received_amount`, `payment_mode`, `reference_number`, `payment_date`, `remark`, `added_by`, `added_date`, `status`) VALUES
(1, 1, 50000.00, 'Cash', 'CR-2026-001', '2026-01-10', 'Part payment for INV-2026-001', 1, '2026-01-10 15:00:00', 'Active'),
(2, 2, 65500.00, 'Bank Transfer', 'TXN99881122', '2026-01-10', 'Full settlement for INV-2026-002', 1, '2026-01-10 16:00:00', 'Active'),
(3, 3, 40000.00, 'Cheque', 'CHQ-554433', '2026-01-11', 'Cheque deposit against INV-2026-003', 1, '2026-01-11 15:30:00', 'Active'),
(4, 4, 100000.00, 'NEFT', 'NEFT-DL-001122', '2026-01-11', 'Advance / Invoice payment', 1, '2026-01-11 16:30:00', 'Active'),
(5, 5, 30000.00, 'Cash', 'CR-2026-002', '2026-01-12', 'Cash collected at Kota depot', 1, '2026-01-12 14:00:00', 'Active'),
(6, 6, 35000.00, 'UPI', 'UPI-9900112233', '2026-01-12', 'UPI payment Bhopal client', 1, '2026-01-12 15:00:00', 'Active'),
(7, 7, 50000.00, 'RTGS', 'RTGS-GJ-445566', '2026-01-13', 'Interstate bank clearance', 1, '2026-01-13 16:00:00', 'Active'),
(8, 8, 50000.00, 'Cash', 'CR-2026-003', '2026-01-13', 'Direct point cash collection', 1, '2026-01-13 16:30:00', 'Active'),
(9, 9, 25000.00, 'Bank Transfer', 'TXN77889900', '2026-01-14', 'Gwalior account transfer', 1, '2026-01-14 15:00:00', 'Active'),
(10, 10, 80000.00, 'NEFT', 'NEFT-AG-667788', '2026-01-14', 'Truckload clearance payment', 1, '2026-01-14 16:00:00', 'Active')
ON DUPLICATE KEY UPDATE `received_amount`=VALUES(`received_amount`), `status`=VALUES(`status`);

-- --------------------------------------------------------
-- 53. psac_cash_deposited_payment (10 records)
-- --------------------------------------------------------
INSERT INTO `psac_cash_deposited_payment` (`id`, `fisherman_id`, `maingroup_id`, `deposited_by`, `product_liability`, `wages_liability`, `deposit_date`, `remark`, `added_by`, `added_date`, `status`) VALUES
(1, 1, 1, 'Fisherman', 500.00, 1000.00, '2026-01-10', 'Cash deposit towards gear advance', 1, '2026-01-10 17:00:00', 'Active'),
(2, 2, 1, 'Fisherman', 400.00, 800.00, '2026-01-10', 'Weekly liability adjustment', 1, '2026-01-10 17:30:00', 'Active'),
(3, 3, 2, 'Fisherman', 600.00, 1200.00, '2026-01-11', 'Deposit against boat fuel advance', 1, '2026-01-11 17:00:00', 'Active'),
(4, 4, 2, 'Group', 1500.00, 3000.00, '2026-01-11', 'Group deposit for cooperative fund', 1, '2026-01-11 17:30:00', 'Active'),
(5, 5, 3, 'Fisherman', 450.00, 900.00, '2026-01-12', 'Net recovery deposit', 1, '2026-01-12 17:00:00', 'Active'),
(6, 6, 3, 'Fisherman', 550.00, 1100.00, '2026-01-12', 'Routine liability refund', 1, '2026-01-12 17:30:00', 'Active'),
(7, 7, 4, 'Fisherman', 350.00, 700.00, '2026-01-13', 'Chandwasa fisherman payment', 1, '2026-01-13 17:00:00', 'Active'),
(8, 8, 4, 'Group', 1200.00, 2500.00, '2026-01-13', 'Mandal group deposit', 1, '2026-01-13 17:30:00', 'Active'),
(9, 9, 5, 'Fisherman', 300.00, 600.00, '2026-01-14', 'Bolia unit settlement', 1, '2026-01-14 17:00:00', 'Active'),
(10, 10, 5, 'Fisherman', 800.00, 1600.00, '2026-01-14', 'Boat repair loan repayment', 1, '2026-01-14 17:30:00', 'Active')
ON DUPLICATE KEY UPDATE `wages_liability`=VALUES(`wages_liability`), `status`=VALUES(`status`);

-- --------------------------------------------------------
-- 54. psac_advancefisherman (10 records)
-- --------------------------------------------------------
INSERT INTO `psac_advancefisherman` (`id`, `Fisherman`, `maingroup_id`, `advance_to`, `Amount`, `advance_date`, `remark`, `added_by`, `added_date`, `status`) VALUES
(1, 1, 1, 'Fisherman', 2000.00, '2026-01-05', 'Advance for fishing net repair', 1, '2026-01-05 10:00:00', 'Active'),
(2, 2, 1, 'Fisherman', 1500.00, '2026-01-05', 'Boat diesel advance', 1, '2026-01-05 10:30:00', 'Active'),
(3, 3, 2, 'Fisherman', 2500.00, '2026-01-06', 'Medical advance', 1, '2026-01-06 11:00:00', 'Active'),
(4, 4, 2, 'Group', 10000.00, '2026-01-06', 'Group community gear procurement', 1, '2026-01-06 11:30:00', 'Active'),
(5, 5, 3, 'Fisherman', 1800.00, '2026-01-07', 'Seasonal preparation advance', 1, '2026-01-07 10:00:00', 'Active'),
(6, 6, 3, 'Fisherman', 2200.00, '2026-01-07', 'Boat engine maintenance', 1, '2026-01-07 10:30:00', 'Active'),
(7, 7, 4, 'Fisherman', 1200.00, '2026-01-08', 'Festival cash advance', 1, '2026-01-08 11:00:00', 'Active'),
(8, 8, 4, 'Group', 8000.00, '2026-01-08', 'Cooperative ice box grant', 1, '2026-01-08 11:30:00', 'Active'),
(9, 9, 5, 'Fisherman', 1000.00, '2026-01-09', 'Rope & floaters purchase', 1, '2026-01-09 10:00:00', 'Active'),
(10, 10, 5, 'Fisherman', 3000.00, '2026-01-09', 'Boat hull overhaul advance', 1, '2026-01-09 10:30:00', 'Active')
ON DUPLICATE KEY UPDATE `Amount`=VALUES(`Amount`), `status`=VALUES(`status`);

-- --------------------------------------------------------
-- 55. psac_wages_advance (10 records)
-- --------------------------------------------------------
INSERT INTO `psac_wages_advance` (`id`, `Fisherman`, `maingroup_id`, `advance_to`, `Amount`, `advance_date`, `remark`, `added_by`, `added_date`, `status`) VALUES
(1, 1, 1, 'Fisherman', 2000.00, '2026-01-05', 'Advance against future wages', 1, '2026-01-05 10:00:00', 'Active'),
(2, 2, 1, 'Fisherman', 1500.00, '2026-01-05', 'Wages advance weekly', 1, '2026-01-05 10:30:00', 'Active'),
(3, 3, 2, 'Fisherman', 2500.00, '2026-01-06', 'Special catch incentive advance', 1, '2026-01-06 11:00:00', 'Active'),
(4, 4, 2, 'Group', 10000.00, '2026-01-06', 'Group advance wages pool', 1, '2026-01-06 11:30:00', 'Active'),
(5, 5, 3, 'Fisherman', 1800.00, '2026-01-07', 'Advance against January catch', 1, '2026-01-07 10:00:00', 'Active'),
(6, 6, 3, 'Fisherman', 2200.00, '2026-01-07', 'Mid-week wage drawdown', 1, '2026-01-07 10:30:00', 'Active'),
(7, 7, 4, 'Fisherman', 1200.00, '2026-01-08', 'Emergency cash advance', 1, '2026-01-08 11:00:00', 'Active'),
(8, 8, 4, 'Group', 8000.00, '2026-01-08', 'Group bonus advance', 1, '2026-01-08 11:30:00', 'Active'),
(9, 9, 5, 'Fisherman', 1000.00, '2026-01-09', 'Daily subsistence advance', 1, '2026-01-09 10:00:00', 'Active'),
(10, 10, 5, 'Fisherman', 3000.00, '2026-01-09', 'Heavy catch advance', 1, '2026-01-09 10:30:00', 'Active')
ON DUPLICATE KEY UPDATE `Amount`=VALUES(`Amount`), `status`=VALUES(`status`);

-- --------------------------------------------------------
-- 56. psac_wages (10 records)
-- --------------------------------------------------------
INSERT INTO `psac_wages` (`id`, `wage_date`, `FishermanId`, `MainGroup`, `wages_for`, `GrossWages`, `GroupLiabilityDeduction`, `AdvanceWagesDeduction`, `NetWages`, `added_by`, `added_date`, `status`) VALUES
(1, '2026-01-10', 1, 1, 'Fisherman', 8500.00, 500.00, 1000.00, 7000.00, 1, '2026-01-10 18:00:00', 'Active'),
(2, '2026-01-10', 2, 1, 'Fisherman', 7200.00, 400.00, 800.00, 6000.00, 1, '2026-01-10 18:30:00', 'Active'),
(3, '2026-01-11', 3, 2, 'Fisherman', 9600.00, 600.00, 1500.00, 7500.00, 1, '2026-01-11 18:00:00', 'Active'),
(4, '2026-01-11', 4, 2, 'Group', 25000.00, 2000.00, 4000.00, 19000.00, 1, '2026-01-11 18:30:00', 'Active'),
(5, '2026-01-12', 5, 3, 'Fisherman', 6800.00, 450.00, 900.00, 5450.00, 1, '2026-01-12 18:00:00', 'Active'),
(6, '2026-01-12', 6, 3, 'Fisherman', 7900.00, 550.00, 1100.00, 6250.00, 1, '2026-01-12 18:30:00', 'Active'),
(7, '2026-01-13', 7, 4, 'Fisherman', 5800.00, 350.00, 700.00, 4750.00, 1, '2026-01-13 18:00:00', 'Active'),
(8, '2026-01-13', 8, 4, 'Group', 22000.00, 1800.00, 3500.00, 16700.00, 1, '2026-01-13 18:30:00', 'Active'),
(9, '2026-01-14', 9, 5, 'Fisherman', 4900.00, 300.00, 600.00, 4000.00, 1, '2026-01-14 18:00:00', 'Active'),
(10, '2026-01-14', 10, 5, 'Fisherman', 11500.00, 800.00, 1800.00, 8900.00, 1, '2026-01-14 18:30:00', 'Active')
ON DUPLICATE KEY UPDATE `NetWages`=VALUES(`NetWages`), `status`=VALUES(`status`);

-- --------------------------------------------------------
-- 57. psac_wagesitem (10 records)
-- --------------------------------------------------------
INSERT INTO `psac_wagesitem` (`id`, `wages_id`, `FishermanId`, `MainGroup`, `wages_for`, `fish_code`, `fish_weight`, `rate`, `amount`, `AdvanceWagesDeduction`, `GroupLiabilityDeduction`, `status`) VALUES
(1, 1, 1, 1, 'Fisherman', 'FISH-ROHU', 50.00, 170.00, 8500.00, 1000.00, 500.00, 'Active'),
(2, 2, 2, 1, 'Fisherman', 'FISH-CATLA', 36.00, 200.00, 7200.00, 800.00, 400.00, 'Active'),
(3, 3, 3, 2, 'Fisherman', 'FISH-MRIGAL', 60.00, 160.00, 9600.00, 1500.00, 600.00, 'Active'),
(4, 4, 4, 2, 'Group', 'FISH-SINGHI', 80.00, 312.50, 25000.00, 4000.00, 2000.00, 'Active'),
(5, 5, 5, 3, 'Fisherman', 'FISH-MAGUR', 20.00, 340.00, 6800.00, 900.00, 450.00, 'Active'),
(6, 6, 6, 3, 'Fisherman', 'FISH-SILVER', 63.20, 125.00, 7900.00, 1100.00, 550.00, 'Active'),
(7, 7, 7, 4, 'Fisherman', 'FISH-GRASS', 41.40, 140.00, 5800.00, 700.00, 350.00, 'Active'),
(8, 8, 8, 4, 'Group', 'FISH-TENGRA', 95.65, 230.00, 22000.00, 3500.00, 1800.00, 'Active'),
(9, 9, 9, 5, 'Fisherman', 'FISH-PANGA', 42.60, 115.00, 4900.00, 600.00, 300.00, 'Active'),
(10, 10, 10, 5, 'Fisherman', 'FISH-MAHSEER', 26.74, 430.00, 11500.00, 1800.00, 800.00, 'Active')
ON DUPLICATE KEY UPDATE `amount`=VALUES(`amount`), `status`=VALUES(`status`);

-- --------------------------------------------------------
-- 58. psac_liability_deduction (10 records)
-- --------------------------------------------------------
INSERT INTO `psac_liability_deduction` (`id`, `deducted_for`, `amount`, `advance_deduction`, `deduction_date`, `remark`, `status`) VALUES
(1, 1, 500.00, 1000.00, '2026-01-10', 'Deduction from weekly wages batch 1', 'Active'),
(2, 2, 400.00, 800.00, '2026-01-10', 'Deduction from weekly wages batch 1', 'Active'),
(3, 3, 600.00, 1500.00, '2026-01-11', 'Medical & gear deduction', 'Active'),
(4, 4, 2000.00, 4000.00, '2026-01-11', 'Group advance deduction', 'Active'),
(5, 5, 450.00, 900.00, '2026-01-12', 'Net deduction', 'Active'),
(6, 6, 550.00, 1100.00, '2026-01-12', 'Engine repair recovery', 'Active'),
(7, 7, 350.00, 700.00, '2026-01-13', 'Chandwasa recovery', 'Active'),
(8, 8, 1800.00, 3500.00, '2026-01-13', 'Group ice box deduction', 'Active'),
(9, 9, 300.00, 600.00, '2026-01-14', 'Subsistence recovery', 'Active'),
(10, 10, 800.00, 1800.00, '2026-01-14', 'Overhaul loan deduction', 'Active')
ON DUPLICATE KEY UPDATE `amount`=VALUES(`amount`), `status`=VALUES(`status`);

-- --------------------------------------------------------
-- 59. psac_transferred_liability (10 records)
-- --------------------------------------------------------
INSERT INTO `psac_transferred_liability` (`id`, `from_fisherman`, `to_fisherman`, `amount`, `transfer_date`, `remark`, `status`) VALUES
(1, 1, 2, 1200.00, '2026-01-08', 'Mutual net sharing liability transfer', 'Active'),
(2, 2, 3, 850.00, '2026-01-08', 'Boat partnership adjustment', 'Active'),
(3, 3, 4, 1500.00, '2026-01-09', 'Diesel credit transfer', 'Active'),
(4, 4, 5, 600.00, '2026-01-09', 'Crate handling liability transfer', 'Active'),
(5, 5, 6, 950.00, '2026-01-10', 'Catch sharing transfer', 'Active'),
(6, 6, 7, 700.00, '2026-01-10', 'Labor liability handover', 'Active'),
(7, 7, 8, 1100.00, '2026-01-11', 'Chandwasa-Bhanpura transfer', 'Active'),
(8, 8, 9, 450.00, '2026-01-11', 'Floater material adjustment', 'Active'),
(9, 9, 10, 1300.00, '2026-01-12', 'Joint haul liability settlement', 'Active'),
(10, 10, 1, 1600.00, '2026-01-12', 'End of cycle inter-member clearance', 'Active')
ON DUPLICATE KEY UPDATE `amount`=VALUES(`amount`), `status`=VALUES(`status`);

-- --------------------------------------------------------
-- 60. psac_activity_log (10 records)
-- --------------------------------------------------------
INSERT INTO `psac_activity_log` (`id`, `user_id`, `ip_address`, `activity`, `created_date`) VALUES
(1, 1, '127.0.0.1', 'Admin login successful', '2026-01-10 08:30:00'),
(2, 1, '127.0.0.1', 'Created daily sale invoice INV-2026-001', '2026-01-10 12:05:00'),
(3, 1, '127.0.0.1', 'Created daily sale invoice INV-2026-002', '2026-01-10 12:35:00'),
(4, 1, '127.0.0.1', 'Recorded cash receipt CR-2026-001 (Rs. 50000)', '2026-01-10 15:05:00'),
(5, 1, '127.0.0.1', 'Recorded product dispatch DR-2026-001 (50 boxes)', '2026-01-10 11:05:00'),
(6, 1, '127.0.0.1', 'Generated weekly wages statement for 10 fishermen', '2026-01-10 18:35:00'),
(7, 1, '127.0.0.1', 'Recorded daily closing stock for Rampura depot', '2026-01-10 20:05:00'),
(8, 1, '127.0.0.1', 'Updated fish master rates for Major Carps', '2026-01-11 09:15:00'),
(9, 1, '127.0.0.1', 'Initiated weekly database backup', '2026-01-11 23:00:00'),
(10, 1, '127.0.0.1', 'Approved fisherman advance disbursement', '2026-01-12 10:15:00')
ON DUPLICATE KEY UPDATE `activity`=VALUES(`activity`);

-- --------------------------------------------------------
-- 61. psac_api_access (10 records)
-- --------------------------------------------------------
INSERT INTO `psac_api_access` (`id`, `key`, `all_access`, `controller`, `date_created`) VALUES
(1, 'APIKEY-SIMRAN-001-ALPHA', 1, 'api/v1/sales', '2026-01-01 00:00:00'),
(2, 'APIKEY-SIMRAN-002-BRAVO', 1, 'api/v1/stock', '2026-01-01 00:00:00'),
(3, 'APIKEY-SIMRAN-003-CHARL', 1, 'api/v1/dispatch', '2026-01-01 00:00:00'),
(4, 'APIKEY-SIMRAN-004-DELTA', 1, 'api/v1/production', '2026-01-01 00:00:00'),
(5, 'APIKEY-SIMRAN-005-ECHO', 1, 'api/v1/fishermen', '2026-01-01 00:00:00'),
(6, 'APIKEY-SIMRAN-006-FOXT', 1, 'api/v1/clients', '2026-01-01 00:00:00'),
(7, 'APIKEY-SIMRAN-007-GOLF', 1, 'api/v1/wages', '2026-01-01 00:00:00'),
(8, 'APIKEY-SIMRAN-008-HOTEL', 1, 'api/v1/reports', '2026-01-01 00:00:00'),
(9, 'APIKEY-SIMRAN-009-INDIA', 1, 'api/v1/inward', '2026-01-01 00:00:00'),
(10, 'APIKEY-SIMRAN-010-JULI', 1, 'api/v1/outward', '2026-01-01 00:00:00')
ON DUPLICATE KEY UPDATE `controller`=VALUES(`controller`);

-- --------------------------------------------------------
-- 62. psac_api_keys (10 records)
-- --------------------------------------------------------
INSERT INTO `psac_api_keys` (`id`, `user_id`, `key`, `level`, `ignore_limits`, `is_private_key`, `ip_addresses`, `date_created`) VALUES
(1, 1, 'APIKEY-SIMRAN-001-ALPHA', 10, 1, 0, '127.0.0.1,192.168.1.100', 1736500000),
(2, 2, 'APIKEY-SIMRAN-002-BRAVO', 10, 1, 0, '127.0.0.1,192.168.1.101', 1736500001),
(3, 3, 'APIKEY-SIMRAN-003-CHARL', 5, 0, 0, '192.168.1.102', 1736500002),
(4, 4, 'APIKEY-SIMRAN-004-DELTA', 5, 0, 0, '192.168.1.103', 1736500003),
(5, 5, 'APIKEY-SIMRAN-005-ECHO', 5, 0, 0, '192.168.1.104', 1736500004),
(6, 6, 'APIKEY-SIMRAN-006-FOXT', 5, 0, 0, '192.168.1.105', 1736500005),
(7, 7, 'APIKEY-SIMRAN-007-GOLF', 5, 0, 0, '192.168.1.106', 1736500006),
(8, 8, 'APIKEY-SIMRAN-008-HOTEL', 5, 0, 0, '192.168.1.107', 1736500007),
(9, 9, 'APIKEY-SIMRAN-009-INDIA', 5, 0, 0, '192.168.1.108', 1736500008),
(10, 10, 'APIKEY-SIMRAN-010-JULI', 5, 0, 0, '192.168.1.109', 1736500009)
ON DUPLICATE KEY UPDATE `key`=VALUES(`key`);

-- --------------------------------------------------------
-- 63. psac_api_limits (10 records)
-- --------------------------------------------------------
INSERT INTO `psac_api_limits` (`id`, `uri`, `count`, `hour_started`, `api_key`) VALUES
(1, 'api/v1/sales', 45, 1736510000, 'APIKEY-SIMRAN-001-ALPHA'),
(2, 'api/v1/stock', 32, 1736510000, 'APIKEY-SIMRAN-002-BRAVO'),
(3, 'api/v1/dispatch', 28, 1736510000, 'APIKEY-SIMRAN-003-CHARL'),
(4, 'api/v1/production', 50, 1736510000, 'APIKEY-SIMRAN-004-DELTA'),
(5, 'api/v1/fishermen', 15, 1736510000, 'APIKEY-SIMRAN-005-ECHO'),
(6, 'api/v1/clients', 20, 1736510000, 'APIKEY-SIMRAN-006-FOXT'),
(7, 'api/v1/wages', 18, 1736510000, 'APIKEY-SIMRAN-007-GOLF'),
(8, 'api/v1/reports', 12, 1736510000, 'APIKEY-SIMRAN-008-HOTEL'),
(9, 'api/v1/inward', 22, 1736510000, 'APIKEY-SIMRAN-009-INDIA'),
(10, 'api/v1/outward', 19, 1736510000, 'APIKEY-SIMRAN-010-JULI')
ON DUPLICATE KEY UPDATE `count`=VALUES(`count`);

-- --------------------------------------------------------
-- 64. psac_api_logs (10 records)
-- --------------------------------------------------------
INSERT INTO `psac_api_logs` (`id`, `uri`, `method`, `params`, `api_key`, `ip_address`, `time`, `rtime`, `authorized`, `response_code`) VALUES
(1, 'api/v1/sales/get_daily', 'GET', '{"date":"2026-01-10"}', 'APIKEY-SIMRAN-001-ALPHA', '127.0.0.1', 1736510100, 0.045, '1', 200),
(2, 'api/v1/stock/get_current', 'GET', '{"depot_id":1}', 'APIKEY-SIMRAN-002-BRAVO', '127.0.0.1', 1736510200, 0.038, '1', 200),
(3, 'api/v1/dispatch/list', 'GET', '{"status":"Dispatched"}', 'APIKEY-SIMRAN-003-CHARL', '127.0.0.1', 1736510300, 0.052, '1', 200),
(4, 'api/v1/production/add', 'POST', '{"fish_code":"FISH-ROHU","weight":450}', 'APIKEY-SIMRAN-004-DELTA', '127.0.0.1', 1736510400, 0.061, '1', 200),
(5, 'api/v1/fishermen/list', 'GET', '{"group_id":1}', 'APIKEY-SIMRAN-005-ECHO', '127.0.0.1', 1736510500, 0.029, '1', 200),
(6, 'api/v1/clients/search', 'GET', '{"q":"Agrawal"}', 'APIKEY-SIMRAN-006-FOXT', '127.0.0.1', 1736510600, 0.033, '1', 200),
(7, 'api/v1/wages/summary', 'GET', '{"month":"2026-01"}', 'APIKEY-SIMRAN-007-GOLF', '127.0.0.1', 1736510700, 0.048, '1', 200),
(8, 'api/v1/reports/revenue', 'GET', '{"year":"2026"}', 'APIKEY-SIMRAN-008-HOTEL', '127.0.0.1', 1736510800, 0.075, '1', 200),
(9, 'api/v1/inward/history', 'GET', '{"depot_id":2}', 'APIKEY-SIMRAN-009-INDIA', '127.0.0.1', 1736510900, 0.041, '1', 200),
(10, 'api/v1/outward/history', 'GET', '{"group_id":2}', 'APIKEY-SIMRAN-010-JULI', '127.0.0.1', 1736511000, 0.044, '1', 200)
ON DUPLICATE KEY UPDATE `response_code`=VALUES(`response_code`);

-- --------------------------------------------------------
-- 65. psac_media_category (10 records)
-- --------------------------------------------------------
INSERT INTO `psac_media_category` (`category_id`, `category_name`, `category_alias`, `category_for`, `created_date`, `status`) VALUES
(1, 'Dam Catch Operations', 'dam-catch-ops', 'Photo', '2026-01-01 10:00:00', 'Active'),
(2, 'Depot Grading & Packing', 'depot-grading-packing', 'Photo', '2026-01-01 10:00:00', 'Active'),
(3, 'Cold Storage Facilities', 'cold-storage-facilities', 'Photo', '2026-01-01 10:00:00', 'Active'),
(4, 'Reefer Dispatch Fleet', 'reefer-dispatch-fleet', 'Photo', '2026-01-01 10:00:00', 'Active'),
(5, 'Fishermen Cooperative Meet', 'fishermen-coop-meet', 'Photo', '2026-01-01 10:00:00', 'Active'),
(6, 'Drone Footage of Reservoir', 'drone-reservoir-footage', 'Video', '2026-01-01 10:00:00', 'Active'),
(7, 'Harvesting Techniques Demo', 'harvest-techniques-demo', 'Video', '2026-01-01 10:00:00', 'Active'),
(8, 'Quality Testing Procedure', 'quality-testing-procedure', 'Video', '2026-01-01 10:00:00', 'Active'),
(9, 'Interstate Mandi Auction', 'interstate-mandi-auction', 'Video', '2026-01-01 10:00:00', 'Active'),
(10, 'Dam Spillway Monsoon Surge', 'dam-spillway-monsoon', 'Video', '2026-01-01 10:00:00', 'Active')
ON DUPLICATE KEY UPDATE `category_name`=VALUES(`category_name`), `status`=VALUES(`status`);

-- --------------------------------------------------------
-- 66. psac_media_images (10 records)
-- --------------------------------------------------------
INSERT INTO `psac_media_images` (`id`, `category_id`, `caption`, `image_path`, `created_date`, `status`) VALUES
(1, 1, 'Sunrise catch at Rampura bay', 'assets/media/images/catch_01.jpg', '2026-01-10 08:00:00', 'Active'),
(2, 1, 'Major carp net haul Gandhisagar', 'assets/media/images/catch_02.jpg', '2026-01-10 08:30:00', 'Active'),
(3, 2, 'Fish grading and weighing at depot', 'assets/media/images/grading_01.jpg', '2026-01-11 09:00:00', 'Active'),
(4, 2, 'Thermocol crate ice packaging', 'assets/media/images/packing_01.jpg', '2026-01-11 09:30:00', 'Active'),
(5, 3, 'Inside cold storage chamber 1', 'assets/media/images/cold_01.jpg', '2026-01-12 10:00:00', 'Active'),
(6, 3, 'Ice crusher plant operations', 'assets/media/images/ice_plant_01.jpg', '2026-01-12 10:30:00', 'Active'),
(7, 4, 'Reefer truck loading for Delhi', 'assets/media/images/dispatch_01.jpg', '2026-01-13 11:00:00', 'Active'),
(8, 4, 'Insulated van departure', 'assets/media/images/dispatch_02.jpg', '2026-01-13 11:30:00', 'Active'),
(9, 5, 'Fishermen group annual meeting', 'assets/media/images/coop_01.jpg', '2026-01-14 14:00:00', 'Active'),
(10, 5, 'Safety jacket & gear distribution', 'assets/media/images/gear_dist_01.jpg', '2026-01-14 15:00:00', 'Active')
ON DUPLICATE KEY UPDATE `caption`=VALUES(`caption`), `status`=VALUES(`status`);

-- --------------------------------------------------------
-- 67. psac_media_videos (10 records)
-- --------------------------------------------------------
INSERT INTO `psac_media_videos` (`id`, `category_id`, `caption`, `image_path`, `youtube_url`, `created_date`, `status`) VALUES
(1, 6, 'Aerial view of Gandhisagar deepwater netting', 'assets/media/thumbs/video_01.jpg', 'https://www.youtube.com/watch?v=sample01', '2026-01-10 10:00:00', 'Active'),
(2, 6, 'Reservoir island channel drone patrol', 'assets/media/thumbs/video_02.jpg', 'https://www.youtube.com/watch?v=sample02', '2026-01-10 11:00:00', 'Active'),
(3, 7, 'Traditional gillnet deployment method', 'assets/media/thumbs/video_03.jpg', 'https://www.youtube.com/watch?v=sample03', '2026-01-11 10:00:00', 'Active'),
(4, 7, 'Seine net encirclement technique', 'assets/media/thumbs/video_04.jpg', 'https://www.youtube.com/watch?v=sample04', '2026-01-11 11:00:00', 'Active'),
(5, 8, 'Quality grading standard operating procedure', 'assets/media/thumbs/video_05.jpg', 'https://www.youtube.com/watch?v=sample05', '2026-01-12 10:00:00', 'Active'),
(6, 8, 'Cold chain temperature validation', 'assets/media/thumbs/video_06.jpg', 'https://www.youtube.com/watch?v=sample06', '2026-01-12 11:00:00', 'Active'),
(7, 9, 'Jaipur Muhana mandi fish auction live', 'assets/media/thumbs/video_07.jpg', 'https://www.youtube.com/watch?v=sample07', '2026-01-13 10:00:00', 'Active'),
(8, 9, 'Delhi Ghazipur unloading footage', 'assets/media/thumbs/video_08.jpg', 'https://www.youtube.com/watch?v=sample08', '2026-01-13 11:00:00', 'Active'),
(9, 10, 'Gandhisagar spillway during monsoon', 'assets/media/thumbs/video_09.jpg', 'https://www.youtube.com/watch?v=sample09', '2026-01-14 10:00:00', 'Active'),
(10, 10, 'Chambal river surge overflow overview', 'assets/media/thumbs/video_10.jpg', 'https://www.youtube.com/watch?v=sample10', '2026-01-14 11:00:00', 'Active')
ON DUPLICATE KEY UPDATE `caption`=VALUES(`caption`), `status`=VALUES(`status`);

-- --------------------------------------------------------
-- 68. psac_database_backup (10 records)
-- --------------------------------------------------------
INSERT INTO `psac_database_backup` (`backup_id`, `backup_dbname`, `display_name`, `backup_type`, `backup_path`, `backup_date`, `backup_by`, `status`) VALUES
(1, 'db_backup_2026_01_01', 'Weekly Backup - Jan W1', 'Weekly', 'DB_BACKUP/db_backup_2026_01_01.sql.gz', '2026-01-01 23:00:00', 1, 'Active'),
(2, 'db_backup_2026_01_08', 'Weekly Backup - Jan W2', 'Weekly', 'DB_BACKUP/db_backup_2026_01_08.sql.gz', '2026-01-08 23:00:00', 1, 'Active'),
(3, 'db_backup_2026_01_15', 'Weekly Backup - Jan W3', 'Weekly', 'DB_BACKUP/db_backup_2026_01_15.sql.gz', '2026-01-15 23:00:00', 1, 'Active'),
(4, 'db_backup_2026_01_22', 'Weekly Backup - Jan W4', 'Weekly', 'DB_BACKUP/db_backup_2026_01_22.sql.gz', '2026-01-22 23:00:00', 1, 'Active'),
(5, 'db_backup_2026_01_29', 'Weekly Backup - Jan W5', 'Weekly', 'DB_BACKUP/db_backup_2026_01_29.sql.gz', '2026-01-29 23:00:00', 1, 'Active'),
(6, 'db_backup_2026_02_05', 'Weekly Backup - Feb W1', 'Weekly', 'DB_BACKUP/db_backup_2026_02_05.sql.gz', '2026-02-05 23:00:00', 1, 'Active'),
(7, 'db_backup_2026_02_12', 'Weekly Backup - Feb W2', 'Weekly', 'DB_BACKUP/db_backup_2026_02_12.sql.gz', '2026-02-12 23:00:00', 1, 'Active'),
(8, 'db_backup_2026_02_19', 'Weekly Backup - Feb W3', 'Weekly', 'DB_BACKUP/db_backup_2026_02_19.sql.gz', '2026-02-19 23:00:00', 1, 'Active'),
(9, 'db_backup_yearly_2024_25', 'Yearly Archive 2024-25', 'Yearly', 'DB_BACKUP/db_backup_yearly_2024_25.sql.gz', '2025-03-31 23:59:59', 1, 'Active'),
(10, 'db_backup_yearly_2025_26', 'Yearly Archive 2025-26', 'Yearly', 'DB_BACKUP/db_backup_yearly_2025_26.sql.gz', '2026-03-31 23:59:59', 1, 'Active')
ON DUPLICATE KEY UPDATE `display_name`=VALUES(`display_name`), `status`=VALUES(`status`);

-- --------------------------------------------------------
-- 69. psac_database_backup_tables (10 records)
-- --------------------------------------------------------
INSERT INTO `psac_database_backup_tables` (`table_id`, `backup_id`, `table_name`, `file_path`, `backup_date`) VALUES
(1, 1, 'psac_sale', 'DB_BACKUP/tables/psac_sale_2026_01_01.sql', '2026-01-01 23:05:00'),
(2, 1, 'psac_sale_items', 'DB_BACKUP/tables/psac_sale_items_2026_01_01.sql', '2026-01-01 23:06:00'),
(3, 1, 'psac_wages', 'DB_BACKUP/tables/psac_wages_2026_01_01.sql', '2026-01-01 23:07:00'),
(4, 1, 'psac_production', 'DB_BACKUP/tables/psac_production_2026_01_01.sql', '2026-01-01 23:08:00'),
(5, 2, 'psac_product_dispatch', 'DB_BACKUP/tables/psac_product_dispatch_2026_01_08.sql', '2026-01-08 23:05:00'),
(6, 2, 'psac_cash_received', 'DB_BACKUP/tables/psac_cash_received_2026_01_08.sql', '2026-01-08 23:06:00'),
(7, 3, 'psac_client', 'DB_BACKUP/tables/psac_client_2026_01_15.sql', '2026-01-15 23:05:00'),
(8, 3, 'psac_fisherman', 'DB_BACKUP/tables/psac_fisherman_2026_01_15.sql', '2026-01-15 23:06:00'),
(9, 4, 'psac_closing_stock', 'DB_BACKUP/tables/psac_closing_stock_2026_01_22.sql', '2026-01-22 23:05:00'),
(10, 5, 'psac_destroyed', 'DB_BACKUP/tables/psac_destroyed_2026_01_29.sql', '2026-01-29 23:05:00')
ON DUPLICATE KEY UPDATE `file_path`=VALUES(`file_path`);

-- --------------------------------------------------------
-- 70. psac_database_backup_yearly (10 records)
-- --------------------------------------------------------
INSERT INTO `psac_database_backup_yearly` (`id`, `dbname`, `display_name`, `session_year`, `created_date`) VALUES
(1, 'sales_gandhisagar_2016_17', 'Gandhisagar Sales Archive 2016-17', '2016-17', '2017-04-01 00:00:00'),
(2, 'sales_gandhisagar_2017_18', 'Gandhisagar Sales Archive 2017-18', '2017-18', '2018-04-01 00:00:00'),
(3, 'sales_gandhisagar_2018_19', 'Gandhisagar Sales Archive 2018-19', '2018-19', '2019-04-01 00:00:00'),
(4, 'sales_gandhisagar_2019_20', 'Gandhisagar Sales Archive 2019-20', '2019-20', '2020-04-01 00:00:00'),
(5, 'sales_gandhisagar_2020_21', 'Gandhisagar Sales Archive 2020-21', '2020-21', '2021-04-01 00:00:00'),
(6, 'sales_gandhisagar_2021_22', 'Gandhisagar Sales Archive 2021-22', '2021-22', '2022-04-01 00:00:00'),
(7, 'sales_gandhisagar_2022_23', 'Gandhisagar Sales Archive 2022-23', '2022-23', '2023-04-01 00:00:00'),
(8, 'sales_gandhisagar_2023_24', 'Gandhisagar Sales Archive 2023-24', '2023-24', '2024-04-01 00:00:00'),
(9, 'sales_gandhisagar_2024_25', 'Gandhisagar Sales Archive 2024-25', '2024-25', '2025-04-01 00:00:00'),
(10, 'sales_gandhisagar_2025_26', 'Gandhisagar Sales Current 2025-26', '2025-26', '2026-04-01 00:00:00')
ON DUPLICATE KEY UPDATE `display_name`=VALUES(`display_name`);

-- --------------------------------------------------------
-- 71. psac_database_setting (10 records)
-- --------------------------------------------------------
INSERT INTO `psac_database_setting` (`id`, `admin_id`, `db_id`, `assign_date`, `assign_by`, `status`) VALUES
(1, 1, 1, '2026-01-01 09:00:00', 1, 'Active'),
(2, 2, 1, '2026-01-01 09:00:00', 1, 'Active'),
(3, 3, 2, '2026-01-02 09:00:00', 1, 'Active'),
(4, 4, 3, '2026-01-02 09:00:00', 1, 'Active'),
(5, 5, 4, '2026-01-03 09:00:00', 1, 'Active'),
(6, 6, 5, '2026-01-03 09:00:00', 1, 'Active'),
(7, 7, 6, '2026-01-04 09:00:00', 1, 'Active'),
(8, 8, 7, '2026-01-04 09:00:00', 1, 'Active'),
(9, 9, 8, '2026-01-05 09:00:00', 1, 'Active'),
(10, 10, 9, '2026-01-05 09:00:00', 1, 'Active')
ON DUPLICATE KEY UPDATE `status`=VALUES(`status`);

-- --------------------------------------------------------
-- 72. psac_sync_table_info (10 records)
-- --------------------------------------------------------
INSERT INTO `psac_sync_table_info` (`id`, `table_name`, `last_sync_id`, `last_sync_date`) VALUES
(1, 'psac_sale', 10, '2026-01-14 23:59:00'),
(2, 'psac_sale_items', 10, '2026-01-14 23:59:00'),
(3, 'psac_production', 10, '2026-01-14 23:59:00'),
(4, 'psac_product_dispatch', 10, '2026-01-14 23:59:00'),
(5, 'psac_product_box', 10, '2026-01-14 23:59:00'),
(6, 'psac_closing_stock', 10, '2026-01-14 23:59:00'),
(7, 'psac_wages', 10, '2026-01-14 23:59:00'),
(8, 'psac_cash_received', 10, '2026-01-14 23:59:00'),
(9, 'psac_fisherman', 10, '2026-01-14 23:59:00'),
(10, 'psac_client', 10, '2026-01-14 23:59:00')
ON DUPLICATE KEY UPDATE `last_sync_id`=VALUES(`last_sync_id`), `last_sync_date`=VALUES(`last_sync_date`);

-- --------------------------------------------------------
-- 73. psac_sync_table_history (10 records)
-- --------------------------------------------------------
INSERT INTO `psac_sync_table_history` (`id`, `table_name`, `sync_count`, `sync_date`) VALUES
(1, 'psac_sale', 10, '2026-01-14 23:59:00'),
(2, 'psac_sale_items', 10, '2026-01-14 23:59:00'),
(3, 'psac_production', 10, '2026-01-14 23:59:00'),
(4, 'psac_product_dispatch', 10, '2026-01-14 23:59:00'),
(5, 'psac_product_box', 10, '2026-01-14 23:59:00'),
(6, 'psac_closing_stock', 10, '2026-01-14 23:59:00'),
(7, 'psac_wages', 10, '2026-01-14 23:59:00'),
(8, 'psac_cash_received', 10, '2026-01-14 23:59:00'),
(9, 'psac_fisherman', 10, '2026-01-14 23:59:00'),
(10, 'psac_client', 10, '2026-01-14 23:59:00')
ON DUPLICATE KEY UPDATE `sync_count`=VALUES(`sync_count`), `sync_date`=VALUES(`sync_date`);

-- --------------------------------------------------------
-- 74. psac_truncate (10 records)
-- --------------------------------------------------------
INSERT INTO `psac_truncate` (`id`, `table_name`, `action_date`) VALUES
(1, 'psac_api_logs_archive', '2026-01-01 00:00:00'),
(2, 'psac_activity_log_archive', '2026-01-01 00:00:00'),
(3, 'psac_temp_import_buffer', '2026-01-02 00:00:00'),
(4, 'psac_temp_excel_staging', '2026-01-03 00:00:00'),
(5, 'psac_cache_query_results', '2026-01-04 00:00:00'),
(6, 'psac_session_tokens_expired', '2026-01-05 00:00:00'),
(7, 'psac_temp_dispatch_staging', '2026-01-06 00:00:00'),
(8, 'psac_temp_stock_reconciliation', '2026-01-07 00:00:00'),
(9, 'psac_temp_wages_batch', '2026-01-08 00:00:00'),
(10, 'psac_audit_staging_table', '2026-01-09 00:00:00')
ON DUPLICATE KEY UPDATE `action_date`=VALUES(`action_date`);

SET FOREIGN_KEY_CHECKS = 1;
