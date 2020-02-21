<?php
$dbhost = '121.78.147.67';
$dbuser = 'mprd';
$dbpass = 'mprd1004';
$dbname = '2020misexpo';
$aes_key = "mprdMISE";

$connect = mysql_connect($dbhost, $dbuser, $dbpass);
if(!$connect){
	die("접속 실패 : ".mysql_error());
}

mysql_select_db($dbname, $connect);
@mysql_query('set names utf8');
?>
