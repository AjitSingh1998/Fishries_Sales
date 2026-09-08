<?php
$cookie_file = '/tmp/ci_crud_cookie.txt';
$ch = curl_init('http://127.0.0.1/lock/administration/administrators');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
$res = curl_exec($ch);
echo "RESPONSE FROM LOCK ADMINS:\n";
preg_match_all('/<h4>A PHP Error was encountered<\/h4>(.*?)<\/div>/is', $res, $matches);
foreach ($matches[1] as $m) {
    echo "ERROR: " . trim(strip_tags($m)) . "\n";
}
if (empty($matches[1])) {
    echo $res;
}

$ch = curl_init('http://127.0.0.1/lock/setup/main_group');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
$res2 = curl_exec($ch);
echo "\nRESPONSE FROM LOCK MAIN GROUP:\n";
preg_match_all('/<h4>A PHP Error was encountered<\/h4>(.*?)<\/div>/is', $res2, $matches2);
foreach ($matches2[1] as $m) {
    echo "ERROR: " . trim(strip_tags($m)) . "\n";
}
if (empty($matches2[1])) {
    echo $res2;
}
