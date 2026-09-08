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

// 1. psac_sale
addCol($pdo, 'psac_sale', 'destroyed_weight', 'DECIMAL(12,2) DEFAULT 0.00', 'destroyed_amount');
addCol($pdo, 'psac_sale', 'total_net_wt', 'DECIMAL(12,2) DEFAULT 0.00', 'net_wt');
$pdo->exec("UPDATE psac_sale SET total_net_wt = net_wt WHERE total_net_wt = 0.00");

// 2. psac_product_box
addCol($pdo, 'psac_product_box', 'carret_weight', 'DECIMAL(12,2) DEFAULT 0.00', 'box_weight');
$pdo->exec("UPDATE psac_product_box SET carret_weight = box_weight WHERE carret_weight = 0.00");

// 3. psac_production_carret
addCol($pdo, 'psac_production_carret', 'depot_id', 'INT(11) NULL', 'production_id');
addCol($pdo, 'psac_production_carret', 'packing_date', 'DATE NULL', 'depot_id');
addCol($pdo, 'psac_production_carret', 'production_type', 'VARCHAR(50) DEFAULT "Point"', 'packing_date');
addCol($pdo, 'psac_production_carret', 'status', 'VARCHAR(50) DEFAULT "Active"', 'editable');

// 4. psac_production_carret_items
addCol($pdo, 'psac_production_carret_items', 'depot_id', 'INT(11) NULL', 'carret_id');
addCol($pdo, 'psac_production_carret_items', 'market_id', 'INT(11) NULL', 'depot_id');
addCol($pdo, 'psac_production_carret_items', 'packing_date', 'DATE NULL', 'market_id');
addCol($pdo, 'psac_production_carret_items', 'production_type', 'VARCHAR(50) DEFAULT "Point"', 'packing_date');
addCol($pdo, 'psac_production_carret_items', 'status', 'VARCHAR(50) DEFAULT "Active"', 'editable');

// 5. psac_daily_closing_stock_box
addCol($pdo, 'psac_daily_closing_stock_box', 'depot_id', 'INT(11) NULL', 'stock_id');
addCol($pdo, 'psac_daily_closing_stock_box', 'closing_date', 'DATE NULL', 'depot_id');
addCol($pdo, 'psac_daily_closing_stock_box', 'status', 'VARCHAR(50) DEFAULT "Active"', 'editable');

// Seed test data in psac_production_carret and psac_production_carret_items
$pdo->exec("TRUNCATE TABLE psac_production_carret");
$pdo->exec("
INSERT INTO psac_production_carret
(`id`, `production_id`, `depot_id`, `packing_date`, `production_type`, `carret_number`, `carret_weight`, `fish_type`, `status`, `editable`)
VALUES
(1, 1, 2, '2026-08-15', 'Point', 'CRT-001', 50.00, 'Fresh', 'Active', 'Unlock'),
(2, 1, 2, '2026-08-15', 'Point', 'CRT-002', 48.50, 'Fresh', 'Active', 'Unlock'),
(3, 2, 2, '2026-08-15', 'Transfer', 'CRT-003', 52.00, 'Fresh', 'Active', 'Unlock'),
(4, 3, 2, '2026-08-15', 'Destroyed', 'CRT-004', 15.00, 'Destroyed', 'Active', 'Unlock')
");

$pdo->exec("TRUNCATE TABLE psac_production_carret_items");
$pdo->exec("
INSERT INTO psac_production_carret_items
(`id`, `carret_id`, `depot_id`, `market_id`, `packing_date`, `production_type`, `fish_code`, `fish_qty`, `fish_wt`, `fish_type`, `status`, `editable`)
VALUES
(1, 1, 2, 1, '2026-08-15', 'Point', 'FISH-ROHU', 25.00, 50.00, 'Fresh', 'Active', 'Unlock'),
(2, 2, 2, 1, '2026-08-15', 'Point', 'FISH-CATLA', 20.00, 48.50, 'Fresh', 'Active', 'Unlock'),
(3, 3, 2, 3, '2026-08-15', 'Transfer', 'FISH-MRIGAL', 22.00, 52.00, 'Fresh', 'Active', 'Unlock'),
(4, 4, 2, 1, '2026-08-15', 'Destroyed', 'FISH-ROHU', 5.00, 15.00, 'Destroyed', 'Active', 'Unlock')
");

echo "Inward/Outward database upgrade complete!\n";
