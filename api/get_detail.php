<?php 
	$user= "root";
	$dbpass="";
	$host="localhost";
	$dbname="twitter";
	$dsn="mysql:host={$host};dbname={$dbname};charset=utf8";//forと同じくセミコロン
	$pdo=new PDO($dsn,$user,$dbpass);

	$sql="SELECT * FROM user_details WHERE u_id = :u_id";
	$stmt=$pdo->prepare($sql);
	$stmt->bindValue(":u_id",$_GET["xid"],PDO::PARAM_STR);
	$stmt->execute();
	$row=$stmt->fetch(PDO::FETCH_ASSOC);
	var_dump($row);
	
	$json = json_encode($row);
	echo $json;
	
?>