<?php
$cookie_file = '/tmp/ci_crud_cookie.txt';
$ch = curl_init('http://127.0.0.1/sync_data/sync_data/live');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
$res = curl_exec($ch);
echo "RESPONSE FROM SYNC LIVE:\n";
preg_match_all('/<h4>A PHP Error was encountered<\/h4>(.*?)<\/div>/is', $res, $matches);
foreach ($matches[1] as $m) {
    echo "ERROR: " . trim(strip_tags($m)) . "\n";
}
if (empty($matches[1])) {
    echo substr($res, 0, 500);
}
