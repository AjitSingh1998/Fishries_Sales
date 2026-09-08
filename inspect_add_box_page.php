<?php
$cookie_file = '/tmp/ci_crud_cookie.txt';
$ch = curl_init('http://127.0.0.1/dispatch/add_box/1787011200/4/3');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
$html = curl_exec($ch);
curl_close($ch);

file_put_contents('/tmp/add_box_rendered.html', $html);

preg_match_all('/<script[^>]*src=[\"\'](.*?)[\"\'][^>]*><\/script>/is', $html, $scripts);
echo "SCRIPTS LOADED:\n";
foreach($scripts[1] as $s) {
    echo " - " . $s . "\n";
    // Check if script exists and is reachable
    $ch = curl_init($s);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $res = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($code != 200) {
        echo "   [ERROR: HTTP $code]\n";
    }
}

preg_match_all('/<script(?![^>]*src=)[^>]*>(.*?)<\/script>/is', $html, $inlines);
echo "\nINLINE SCRIPTS (" . count($inlines[1]) . "):\n";
foreach($inlines[1] as $idx => $inline) {
    echo "--- Inline Script " . $idx . " ---\n";
    echo trim($inline) . "\n\n";
}
