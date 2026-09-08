<?php
$cookie_file = '/tmp/ci_full_test_cookie.txt';
if (file_exists($cookie_file)) unlink($cookie_file);

// 1. GET login page to obtain CSRF token
$ch = curl_init('http://127.0.0.1/account/login');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
$html = curl_exec($ch);
curl_close($ch);

preg_match('/name="token"\s+value="([a-f0-9]+)"/i', $html, $tokenMatch);
$csrfToken = $tokenMatch[1] ?? '';

// 2. POST login with CSRF token
$ch = curl_init('http://127.0.0.1/account/login');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    'token' => $csrfToken,
    'email' => 'admin@simransolvex.com',
    'password' => 'admin'
]));
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_exec($ch);
curl_close($ch);

$screens = [
    // Daily Sales
    'DailySale - Point Sale'         => 'http://127.0.0.1/dailysale/dailysale/point_sale',
    'DailySale - Depot Sale'         => 'http://127.0.0.1/dailysale/dailysale/depot_sale',
    'DailySale - Outside Sale'       => 'http://127.0.0.1/dailysale/dailysale/outside_sale',
    'DailySale - Free Sale'          => 'http://127.0.0.1/dailysale/dailysale/free_sale',
    'DailySale - Cash Deposit'       => 'http://127.0.0.1/dailysale/dailysale/cash_deposit',

    // Dispatch
    'Dispatch - Prepare Box'         => 'http://127.0.0.1/dispatch/dispatch/prepare_box',
    'Dispatch - Product DR'          => 'http://127.0.0.1/dispatch/dispatch/prepare_dr',
    'Dispatch - Dispatched DR'       => 'http://127.0.0.1/dispatch/dispatch/dispatched_dr',

    // Production
    'Production - Point Prod'        => 'http://127.0.0.1/production/production/daily_production/point',
    'Production - Depot Prod'        => 'http://127.0.0.1/production/production/daily_production/depot',
    'Production - Transfer Prod'     => 'http://127.0.0.1/production/production/daily_production/transfer',
    'Production - Closing Stock'     => 'http://127.0.0.1/production/production/closing_stock',

    // Key Master & Setup Screens
    'Setup - Clients / Parties'      => 'http://127.0.0.1/setup/setup/client',
    'Setup - Market Places'          => 'http://127.0.0.1/setup/setup/market_place',
    'Setup - Fishes'                 => 'http://127.0.0.1/setup/setup/all_fishes',
    'Setup - Administrators'         => 'http://127.0.0.1/setup/administration/administrators',
    'Setup - Admin Groups'           => 'http://127.0.0.1/setup/administration/admin_group',
];

echo "========================================================================================\n";
echo sprintf("%-32s | %-10s | %-10s | %-20s\n", "SCREEN / MODULE", "HTTP CODE", "DATA ROWS", "STATUS");
echo "========================================================================================\n";

$passCount = 0;
$totalCount = count($screens);

foreach ($screens as $title => $url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $pageHtml = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Count rows inside tbody
    preg_match('/<tbody[^>]*>(.*?)<\/tbody>/is', $pageHtml, $tb);
    $rowCount = 0;
    if (!empty($tb[1])) {
        preg_match_all('/<tr[^>]*>/i', $tb[1], $rows);
        $rowCount = count($rows[0]);
    }

    $isPass = ($httpCode === 200 && $rowCount > 0);
    if ($isPass) $passCount++;

    echo sprintf("%-32s | %-10d | %-10d | %-20s\n", 
        $title, 
        $httpCode, 
        $rowCount, 
        $isPass ? "✓ DATA RENDERED" : "✗ EMPTY / ERROR"
    );
}

echo "========================================================================================\n";
echo "OVERALL RESULT: $passCount / $totalCount screens actively rendering data rows!\n";
echo "========================================================================================\n";
