<?php
$pdo = new PDO('mysql:host=db;dbname=sales_gandhisagar;charset=utf8', 'root', 'root');
$tables = $pdo->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);
foreach ($tables as $table) {
    $cols = $pdo->query("SHOW COLUMNS FROM `$table` LIKE 'editable'")->fetchAll();
    if (!empty($cols)) {
        $count = $pdo->exec("UPDATE `$table` SET `editable` = 'Unlock' WHERE `editable` IS NULL OR `editable` != 'Unlock'");
        echo "Updated table $table ($count rows modified)\n";
    }
}
