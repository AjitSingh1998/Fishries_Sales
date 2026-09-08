<?php
$file = '/var/www/html/application/modules/dailysale/controllers/Dailysale.php';
$content = file_get_contents($file);
$content = str_replace('assets/plugins/moment-develop/min/moment-with-locales.min.js', 'assets/plugins/moment/min/moment-with-locales.min.js', $content);
$content = str_replace('assets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js', 'assets/plugins/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js', $content);
$content = str_replace('assets/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css', 'assets/plugins/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css', $content);
$content = str_replace('assets/js/jQuery.print.min.js', 'assets/modules/reports/jQuery.print.min.js', $content);
$content = str_replace('assets/js/table2excel.custom.js', 'assets/modules/reports/jquery.table2excel.min.js', $content);
file_put_contents($file, $content);
echo "Replaced asset paths in Dailysale.php successfully!\n";
