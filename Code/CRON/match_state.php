<?php

include "/home/u478818357/public_html/PHP/database.php";
global $db;

date_default_timezone_set('Europe/Paris');

$getMatchs = $db->prepare("UPDATE matchs SET status = 1 WHERE status = 0 AND date LIKE ?");
$getMatchs->execute(array(date("Y-m-d H")."%"));

$count = $getMatchs->rowCount();

if ($count > 0) {
    echo $count . " Match Updated";
}else{
    echo "0 Match Updated";
}

$file = '/home/u478818357/public_html/CRON/log.txt';
file_put_contents($file, date("Y-m-d H:i:s") . ";" . $count . "\n", FILE_APPEND | LOCK_EX);
echo "\n\r" . date("Y-m-d H:i:s");  

?>