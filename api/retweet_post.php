<?php
	session_start();
	if(empty($_SESSION["login"])){
		header("Location:../login.php");
	}
	if(empty($_POST["t_id"])){	
		//header("Location:../index.php");
	}
	var_dump($_POST);
	var_dump($_SESSION);
//妥協：リツイートは「コメントなしでtweetsテーブルに乗せない（twitterの仕様と違う）」
//ユーザータイムライン上でだけ、コメントと混ざって見える（結合後、oder by created desc）
		require_once("../tool/config.php");
		$rt = $_POST["t_id"];
		$ru = $_SESSION["u_id"];
		$sql="INSERT INTO re_tweets(t_id , u_id) VALUES ($rt , '".$ru."')";
		$rs = $pdo->query($sql);
		// var_dump($rs);

	//ツイートへのリプライ総数１加算
		$sql_rp="UPDATE tweets SET retweets= (SELECT retweets FROM (SELECT retweets FROM tweets WHERE t_id =$rt) AS a)+1 WHERE t_id = $rt";
		$rp = $pdo->query($sql_rp);
	$aa = $_SERVER['HTTP_REFERER'];
	header("Location:".$aa);
	exit();
?>