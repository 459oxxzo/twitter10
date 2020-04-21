<?php
	session_start();
	require_once("../tool/config.php");
	//PDO生成済み
	//項目に抜けがあれば元のページに戻す
	if(empty($_POST["u_id"]) || empty($_POST["pass"])){
		header("Location:../login.php");
		exit();
	}

	$sql = "INSERT INTO users(u_id,pass) VALUES (:u_id,:pass)";
	
	$stmt = $pdo->prepare($sql);
	$stmt-> bindValue(":u_id",$_POST["u_id"],PDO::PARAM_STR);
	// $stmt-> bindValue(":mail",$_POST["mail"],PDO::PARAM_STR);
	$pass = password_hash($_POST["pass"],PASSWORD_DEFAULT);
	$stmt-> bindValue(":pass",$pass,PDO::PARAM_STR);
	$stmt-> execute();

	//------------------
	$sql2 = "INSERT INTO user_details(u_id,nickname) VALUES (:u_id,:nickname)";
	$stmt2 = $pdo->prepare($sql2);
	$stmt2-> bindValue(":u_id",$_POST["u_id"],PDO::PARAM_STR);
	$stmt2-> bindValue(":nickname",$_POST["nickname"],PDO::PARAM_STR);
	$stmt2-> execute();
	//-------------------
	
	//この時点でログインしたとみなす
	//ログイン
	$_SESSION["login"]=true;
	$_SESSION["u_id"]=$_POST["u_id"];
	
	header("Location:../profile.php");
	exit();
	
	
?>