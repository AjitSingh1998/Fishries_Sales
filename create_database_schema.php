<?php
/**
 * Database and Schema Initializer for sales_gandhisagar
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'sales_gandhisagar';

echo "========================================================
";
echo "INITIALIZING DATABASE RECONSTRUCTION: {$dbname}
";
echo "========================================================

";

try {
    $conn = new mysqli($host, $user, $pass);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error . "
");
    }
    echo "[1/4] Connected to MySQL server successfully.
";

    // 1. Create database
    $sql_db = "CREATE DATABASE IF NOT EXISTS `{$dbname}` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";
    if ($conn->query($sql_db) === TRUE) {
        echo "[2/4] Database '{$dbname}' created/verified successfully.
";
    } else {
        die("Error creating database: " . $conn->error . "
");
    }

    $conn->select_db($dbname);

    // 2. Load and run SQL script
    $sql_file = __DIR__ . '/schema_sales_gandhisagar.sql';
    if (!file_exists($sql_file)) {
        die("SQL schema file not found at: {$sql_file}
");
    }

    $sql_statements = file_get_contents($sql_file);
    
    // Execute multi query
    if ($conn->multi_query($sql_statements)) {
        do {
            /* store first result set */
            if ($result = $conn->store_result()) {
                $result->free();
            }
        } while ($conn->more_results() && $conn->next_result());
        echo "[3/4] SQL Schema and tables created successfully!
";
    } else {
        echo "Multi query execution note: " . $conn->error . "
";
    }

    // 3. Verify created tables
    $res = $conn->query("SHOW TABLES");
    $tables = [];
    while ($row = $res->fetch_array()) {
        $tables[] = $row[0];
    }

    echo "
[4/4] VERIFICATION RESULT:
";
    echo "Total tables created in '{$dbname}': " . count($tables) . "
";
    foreach ($tables as $t) {
        echo "  - {$t}
";
    }

    echo "
========================================================
";
    echo "DATABASE RECONSTRUCTION COMPLETED SUCCESSFULLY!
";
    echo "========================================================
";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "
";
}
?>
