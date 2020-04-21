<?php
session_start();
//データが転送されているかを確認。なければ戻す
if(empty($_POST["u_id"] )|| empty($_POST["pass"])){
	//header("Location:../login.php");//最初に戻す
	//exit();//念のため再度処理終了
}

require_once("../tool/config.php");

$sql="SELECT * FROM users WHERE u_id = :u_id";
$stmt=$pdo->prepare($sql);
$stmt->bindValue(":u_id",$_POST["u_id"],PDO::PARAM_STR);//３引数
$stmt->execute();
$row=$stmt->fetch(PDO::FETCH_ASSOC);//連想配列化

//ここから認証
if(password_verify($_POST["pass"],$row["pass"])){
	//ここで、セッションIDの再発行をするとセキュアになる。
	//セッションの固定化を避ける唯一の方法
	session_regenerate_id();

	//ユーザー情報を記憶してからリダイレクト
	$_SESSION["login"]=true;
	$_SESSION["u_id"]=$row["u_id"];
	$_SESSION["nickname"]=$row["nickname"];
	
	header("Location:index.php");
	exit();//念のため
}else{
	//認証失敗
	$_SESSION["login"]=false;
	//ログインフォームに戻す
	header("Location:login.php");
	exit();
};
//9434mmi8rg8cjg99ace0lvfm3j
//セッションID。これにサーバ側ではいろんな情報を紐づけている
//ログアウト処理はセッションハイジャック対策が必須
?>