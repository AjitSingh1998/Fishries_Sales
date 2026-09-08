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

// 1. psac_product_dispatch
addCol($pdo, 'psac_product_dispatch', 'dispatch_id', 'INT(11) NULL', 'id');
addCol($pdo, 'psac_product_dispatch', 'alter_mobile_no', 'VARCHAR(50) DEFAULT ""', 'driver_mobile_no');
addCol($pdo, 'psac_product_dispatch', 'dhalta', 'DECIMAL(12,2) DEFAULT 0.00', 'total_freight');
addCol($pdo, 'psac_product_dispatch', 'expenses', 'DECIMAL(12,2) DEFAULT 0.00', 'dhalta');
addCol($pdo, 'psac_product_dispatch', 'commission', 'DECIMAL(12,2) DEFAULT 0.00', 'expenses');
$pdo->exec("UPDATE psac_product_dispatch SET dispatch_id = id WHERE dispatch_id IS NULL OR dispatch_id = 0");

// 2. psac_product_box_items
addCol($pdo, 'psac_product_box_items', 'item_id', 'INT(11) NULL', 'id');
addCol($pdo, 'psac_product_box_items', 'box_wt', 'DECIMAL(12,2) DEFAULT 0.00', 'fish_wt');
addCol($pdo, 'psac_product_box_items', 'estimated_price', 'DECIMAL(12,2) DEFAULT 0.00', 'box_wt');
$pdo->exec("UPDATE psac_product_box_items SET item_id = id WHERE item_id IS NULL OR item_id = 0");
$pdo->exec("UPDATE psac_product_box_items SET box_wt = fish_wt WHERE box_wt = 0.00");

// 3. psac_sale_items
addCol($pdo, 'psac_sale_items', 'market_id', 'INT(11) NULL', 'client_id');
addCol($pdo, 'psac_sale_items', 'sale_date', 'DATE NULL', 'market_id');
addCol($pdo, 'psac_sale_items', 'stock_type', 'VARCHAR(50) DEFAULT "Opening"', 'sale_date');
addCol($pdo, 'psac_sale_items', 'carret_number', 'VARCHAR(50) DEFAULT "0"', 'stock_type');

$pdo->exec("UPDATE psac_sale_items si JOIN psac_sale s ON s.id = si.sale_id SET si.market_id = s.market_id, si.sale_date = s.sale_date");

// 4. psac_free_sale_items
addCol($pdo, 'psac_free_sale_items', 'market_id', 'INT(11) NULL');
addCol($pdo, 'psac_free_sale_items', 'free_sale_date', 'DATE NULL');
addCol($pdo, 'psac_free_sale_items', 'stock_type', 'VARCHAR(50) DEFAULT "Opening"');
addCol($pdo, 'psac_free_sale_items', 'status', 'VARCHAR(50) DEFAULT "Active"');

// Seed psac_free_sale_items
$pdo->exec("TRUNCATE TABLE psac_free_sale_items");
$pdo->exec("
INSERT INTO psac_free_sale_items
(`id`, `free_sale_id`, `market_id`, `free_sale_date`, `fish_code`, `fish_quantity`, `fish_weight`, `stock_type`, `status`, `editable`)
VALUES
(1, 1, 1, '2026-08-15', 'FISH-ROHU', 10.00, 25.00, 'Opening', 'Active', 'Unlock'),
(2, 2, 1, '2026-08-16', 'FISH-CATLA', 8.00, 20.00, 'Opening', 'Active', 'Unlock'),
(3, 3, 2, '2026-08-17', 'FISH-MRIGAL', 12.00, 30.00, 'Opening', 'Active', 'Unlock')
");

// 5. psac_daily_closing_stock
addCol($pdo, 'psac_daily_closing_stock', 'production_date', 'DATE NULL');
addCol($pdo, 'psac_daily_closing_stock', 'pb_wt', 'DECIMAL(12,2) DEFAULT 0.00');
addCol($pdo, 'psac_daily_closing_stock', 'cs_wt', 'DECIMAL(12,2) DEFAULT 0.00');

$pdo->exec("TRUNCATE TABLE psac_daily_closing_stock");
$pdo->exec("
INSERT INTO psac_daily_closing_stock
(`id`, `depot_id`, `market_id`, `stock_date`, `production_date`, `weight`, `pb_wt`, `cs_wt`, `status`, `editable`)
VALUES
(1, 2, 1, '2026-08-14', '2026-08-14', 500.00, 250.00, 250.00, 'Active', 'Unlock'),
(2, 2, 1, '2026-08-15', '2026-08-15', 550.00, 300.00, 250.00, 'Active', 'Unlock')
");

// 6. Update psac_sale
addCol($pdo, 'psac_sale', 'sale_id', 'INT(11) NULL', 'id');
addCol($pdo, 'psac_sale', 'dr_number', 'VARCHAR(50) NULL');
$pdo->exec("UPDATE psac_sale SET sale_id = id WHERE sale_id IS NULL OR sale_id = 0");
$pdo->exec("UPDATE psac_sale SET dr_number = 'DR-2026-005' WHERE id IN (1, 5)");

echo "Database upgrade for reports completed successfully!\n";
