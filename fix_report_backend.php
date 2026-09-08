<?php
$pdo = new PDO('mysql:host=db;dbname=sales_gandhisagar;charset=utf8', 'root', 'root', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

function addCol($pdo, $table, $col, $def, $after = '') {
    $stmt = $pdo->prepare("SHOW COLUMNS FROM `$table` LIKE ?");
    $stmt->execute([$col]);
    if ($stmt->rowCount() == 0) {
        $afterSql = $after ? " AFTER `$after`" : "";
        $pdo->exec("ALTER TABLE `$table` ADD COLUMN `$col` $def $afterSql");
        echo "Added $table.$col\n";
    }
}

// 1. Update psac_product_box_items schema
addCol($pdo, 'psac_product_box_items', 'box_number', 'VARCHAR(50) NULL', 'box_id');
addCol($pdo, 'psac_product_box_items', 'packing_date', 'DATE NULL', 'box_number');
addCol($pdo, 'psac_product_box_items', 'depot_id', 'INT(11) NULL', 'packing_date');
addCol($pdo, 'psac_product_box_items', 'market_id', 'INT(11) NULL', 'depot_id');
addCol($pdo, 'psac_product_box_items', 'status', 'VARCHAR(50) DEFAULT "Active"', 'editable');

// 2. Populate psac_product_box_items with matching rows from psac_product_box
$pdo->exec("TRUNCATE TABLE psac_product_box_items");
$pdo->exec("
INSERT INTO psac_product_box_items
(`id`, `box_id`, `box_number`, `packing_date`, `depot_id`, `market_id`, `fish_code`, `fish_qty`, `fish_wt`, `editable`, `fish_type`, `status`)
VALUES
(1, 1, 'BOX-001', '2026-08-15', 2, 1, 'FISH-ROHU', 25.00, 50.00, 'Unlock', 'Fresh', 'Active'),
(2, 2, 'BOX-002', '2026-08-15', 2, 1, 'FISH-CATLA', 20.00, 50.00, 'Unlock', 'Fresh', 'Active'),
(3, 3, 'BOX-003', '2026-08-16', 2, 1, 'FISH-MRIGAL', 22.00, 48.50, 'Unlock', 'Fresh', 'Active'),
(4, 4, 'BOX-004', '2026-08-16', 2, 7, 'FISH-ROHU', 26.00, 52.00, 'Unlock', 'Fresh', 'Active'),
(5, 5, 'BOX-005', '2026-08-17', 3, 7, 'FISH-CATLA', 25.00, 50.00, 'Unlock', 'Fresh', 'Active'),
(6, 6, 'BOX-006', '2026-08-17', 3, 2, 'FISH-MRIGAL', 24.00, 49.00, 'Unlock', 'Fresh', 'Active'),
(7, 7, 'BOX-007', '2026-08-18', 4, 2, 'FISH-ROHU', 26.00, 51.50, 'Unlock', 'Fresh', 'Active'),
(8, 8, 'BOX-008', '2026-08-18', 4, 3, 'FISH-CATLA', 25.00, 50.00, 'Unlock', 'Fresh', 'Active'),
(9, 9, 'BOX-009', '2026-08-18', 2, 4, 'FISH-MRIGAL', 27.00, 53.00, 'Unlock', 'Fresh', 'Active'),
(10, 10, 'BOX-010', '2026-08-18', 2, 4, 'FISH-ROHU', 24.00, 49.50, 'Unlock', 'Fresh', 'Active')
");

// 3. Update psac_product_dispatch_box schema and data
addCol($pdo, 'psac_product_dispatch_box', 'dr_number', 'VARCHAR(50) NULL', 'dispatch_id');
addCol($pdo, 'psac_product_dispatch_box', 'status', 'VARCHAR(50) DEFAULT "Active"', 'editable');

$pdo->exec("TRUNCATE TABLE psac_product_dispatch_box");
$pdo->exec("
INSERT INTO psac_product_dispatch_box
(`id`, `dispatch_id`, `dr_number`, `box_number`, `box_weight`, `status`, `editable`)
VALUES
(1, 1, 'DR-2026-001', 'BOX-001', 50.00, 'Active', 'Unlock'),
(2, 1, 'DR-2026-001', 'BOX-002', 50.00, 'Active', 'Unlock'),
(3, 2, 'DR-2026-002', 'BOX-003', 48.50, 'Active', 'Unlock'),
(4, 2, 'DR-2026-002', 'BOX-004', 52.00, 'Active', 'Unlock'),
(5, 5, 'DR-2026-005', 'BOX-005', 50.00, 'Dispatched', 'Unlock'),
(6, 5, 'DR-2026-005', 'BOX-006', 49.00, 'Dispatched', 'Unlock'),
(7, 6, 'DR-2026-006', 'BOX-007', 51.50, 'Dispatched', 'Unlock'),
(8, 6, 'DR-2026-006', 'BOX-008', 50.00, 'Dispatched', 'Unlock')
");

// 4. Update psac_sale_items schema and data
addCol($pdo, 'psac_sale_items', 'box_number', 'VARCHAR(50) NULL', 'fish_code');
addCol($pdo, 'psac_sale_items', 'net_wt', 'DECIMAL(12,2) DEFAULT 0.00', 'fish_weight');
addCol($pdo, 'psac_sale_items', 'total_amount', 'DECIMAL(12,2) DEFAULT 0.00', 'amount');
addCol($pdo, 'psac_sale_items', 'client_id', 'INT(11) NULL', 'sale_id');
addCol($pdo, 'psac_sale_items', 'remark', 'TEXT NULL', 'amount');
addCol($pdo, 'psac_sale_items', 'status', 'VARCHAR(50) DEFAULT "Active"', 'editable');

$pdo->exec("TRUNCATE TABLE psac_sale_items");
$pdo->exec("
INSERT INTO psac_sale_items
(`id`, `sale_id`, `client_id`, `fish_code`, `box_number`, `fish_quantity`, `fish_weight`, `net_wt`, `fish_rate`, `amount`, `total_amount`, `remark`, `status`, `editable`)
VALUES
(1, 1, 1, 'FISH-ROHU', 'BOX-001', 250.00, 438.75, 438.75, 180.00, 78975.00, 78975.00, 'Point 1 lot', 'Active', 'Unlock'),
(2, 2, 2, 'FISH-CATLA', 'BOX-002', 160.00, 312.00, 312.00, 210.00, 65520.00, 65520.00, 'Point 1 lot', 'Active', 'Unlock'),
(3, 3, 3, 'FISH-MRIGAL', 'BOX-003', 300.00, 487.50, 487.50, 165.00, 80437.50, 80437.50, 'Point 7 lot', 'Active', 'Unlock'),
(4, 4, 4, 'FISH-CATLA', 'BOX-004', 310.00, 585.00, 585.00, 210.00, 105300.00, 105300.00, 'Point 7 lot', 'Active', 'Unlock'),
(5, 5, 5, 'FISH-ROHU', 'BOX-005', 400.00, 780.00, 780.00, 180.00, 163800.00, 163800.00, 'Depot Rampura lot', 'Active', 'Unlock')
");

echo "Report backend DB seeding complete!\n";
