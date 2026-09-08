<?php
$cookie_file = '/tmp/ci_crud_cookie.txt';
function testEndpoint($name, $url, $cookie_file) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
    $res = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    $hasPhpError = (stripos($res, 'A PHP Error was encountered') !== false || stripos($res, 'Fatal error') !== false || stripos($res, 'An uncaught Exception') !== false);
    $status = ($code == 200 && !$hasPhpError) ? 'SUCCESS' : 'FAILED';
    echo sprintf("%-35s | %-10s | %-15s | Size: %dB\n", $name, $code, $status, strlen($res));
    if ($hasPhpError) {
        preg_match_all('/<h4>A PHP Error was encountered<\/h4>(.*?)<\/div>/is', $res, $matches);
        foreach ($matches[1] as $m) {
            echo "  PHP ERROR: " . trim(strip_tags($m)) . "\n";
        }
    }
}

echo "====================================================================================================\n";
echo sprintf("%-35s | %-10s | %-15s | %-20s\n", 'ROUTE NAME', 'HTTP CODE', 'STATUS', 'DETAILS');
echo "====================================================================================================\n";

testEndpoint('Point Production', 'http://127.0.0.1/production/production/daily_production/point', $cookie_file);
testEndpoint('Depot Production', 'http://127.0.0.1/production/production/daily_production/depot', $cookie_file);
testEndpoint('Stock Production', 'http://127.0.0.1/production/production/daily_production/stock', $cookie_file);
testEndpoint('Bachat Production', 'http://127.0.0.1/production/production/daily_production/bachat', $cookie_file);
testEndpoint('Transfer Production', 'http://127.0.0.1/production/production/daily_production/transfer', $cookie_file);
testEndpoint('Closing Stock', 'http://127.0.0.1/production/production/closing_stock', $cookie_file);
testEndpoint('View Challan 9', 'http://127.0.0.1/dailysale/view_challan/9', $cookie_file);
testEndpoint('Point Sale', 'http://127.0.0.1/dailysale/dailysale/point_sale', $cookie_file);
testEndpoint('Depot Sale', 'http://127.0.0.1/dailysale/dailysale/depot_sale', $cookie_file);
testEndpoint('Outside Sale', 'http://127.0.0.1/dailysale/dailysale/outside_sale', $cookie_file);

echo "====================================================================================================\n";
