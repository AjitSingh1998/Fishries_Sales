<?php
$cookie_file = '/tmp/ci_crud_cookie.txt';
$ch = curl_init('http://127.0.0.1/dailysale/point/edit/1');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
$res = curl_exec($ch);
echo "RESPONSE FROM POINT SALE EDIT:\n";
preg_match_all('/<h4>A PHP Error was encountered<\/h4>(.*?)<\/div>/is', $res, $matches);
foreach ($matches[1] as $m) {
    echo "ERROR: " . trim(strip_tags($m)) . "\n";
}

$ch = curl_init('http://127.0.0.1/setup/add_client');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
$res2 = curl_exec($ch);
echo "RESPONSE FROM ADD CLIENT:\n";
preg_match_all('/<h4>A PHP Error was encountered<\/h4>(.*?)<\/div>/is', $res2, $matches2);
foreach ($matches2[1] as $m) {
    echo "ERROR: " . trim(strip_tags($m)) . "\n";
}
echo "RAW ADD CLIENT: " . substr($res2, 0, 300) . "\n";
