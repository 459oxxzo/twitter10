<?php
//データベース接続
$user= "root";
$dbpass="";
$host="localhost";
$dbname="twitter";
$dsn="mysql:host={$host};dbname={$dbname};charset=utf8";//forと同じくセミコロン
$pdo=new PDO($dsn,$user,$dbpass);
?>
