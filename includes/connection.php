<?php
$db = new mysqli("localhost","root", "", "stemmer");
if ($db->connect_errno){
	die( "Sorry we are experiencing some problems..");
}
echo "Successfully connected..";
$db->query("SET NAMES utf8;");
$db->query("SET CHARACTER SET utf8;");
//ini_set('mysql.connect_timeout', 300);
//ini_set('default_socket_timeout', 300);
?>