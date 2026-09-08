<?php
// Initialize session cookie by logging in as Super Admin
$cookie_file = '/tmp/superadmin_session.txt';
if (file_exists($cookie_file)) unlink($cookie_file);

// 1. POST login
$ch = curl_init('http://127.0.0.1/account/login');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, ['email' => 'admin@simransolvex.com', 'password' => 'admin']);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_exec($ch);
curl_close($ch);

$testUrls = [
    // Daily Sales
    'DailySale - Point Sale'       => 'http://127.0.0.1/dailysale/dailysale/point_sale',
    'DailySale - Depot Sale'       => 'http://127.0.0.1/dailysale/dailysale/depot_sale',
    'DailySale - Outside Sale'     => 'http://127.0.0.1/dailysale/dailysale/outside_sale',
    'DailySale - Free Sale'        => 'http://127.0.0.1/dailysale/dailysale/free_sale',
    'DailySale - Cash Deposit'     => 'http://127.0.0.1/dailysale/dailysale/cash_deposit',
    'DailySale - Expenditure'      => 'http://127.0.0.1/dailysale/dailysale/expenditure',

    // Dispatch
    'Dispatch - Prepare Box'       => 'http://127.0.0.1/dispatch/dispatch/prepare_box',
    'Dispatch - Product DR'        => 'http://127.0.0.1/dispatch/dispatch/prepare_dr',
    'Dispatch - Dispatched DR'     => 'http://127.0.0.1/dispatch/dispatch/dispatched_dr',

    // Production
    'Production - Point Prod'      => 'http://127.0.0.1/production/production/daily_production/point',
    'Production - Depot Prod'      => 'http://127.0.0.1/production/production/daily_production/depot',
    'Production - Transfer Prod'   => 'http://127.0.0.1/production/production/daily_production/transfer',
    'Production - Closing Stock'   => 'http://127.0.0.1/production/production/closing_stock',
];

echo "Verifying Rendered Data Rows Across Screens:\n";
echo str_repeat("=", 80) . "\n";

foreach ($testUrls as $title => $url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $html = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Count data rows in tbody
    preg_match('/<tbody[^>]*>(.*?)<\/tbody>/is', $html, $tbodyMatch);
    $rowCount = 0;
    if (!empty($tbodyMatch[1])) {
        preg_match_all('/<tr[^>]*>/i', $tbodyMatch[1], $trMatches);
        $rowCount = count($trMatches[0]);
    }

    // Check if table contains text like "No data" or "No items"
    $isNoData = (stripos($html, 'No items') !== false || stripos($html, 'No data') !== false || $rowCount === 0);

    echo sprintf("%-30s | Rows: %2d | %s\n", 
        $title, 
        $rowCount, 
        (!$isNoData && $rowCount > 0) ? "[OK - DATA RENDERED]" : "[CHECK DATA]"
    );
}
