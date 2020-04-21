<?php
session_start();
require_once("tool/build_tweet.php");
// データベースに接続
require_once("tool/config.php");
$rep_view_hidden=1;

// 遡上関数
function ancestor($id){
	$sql="SELECT * FROM tweets WHERE canceled=0 AND t_id=".$id;
	global $pdo;
	$rs=$pdo ->query($sql);
	//print_r($pdo->errorInfo());
  $data=$rs->fetch(PDO::FETCH_ASSOC);
	if(!empty($data["reply_to"])){
		$parent = $data["reply_to"];
		ancestor($parent);
	}
	if($id!=$_GET["t_id"]){
		build_tweet($id);
	}
}
// 子を取得する関数
function children($id){
	$sql="SELECT * FROM tweets WHERE canceled=0 AND reply_to=".$id;
	global $pdo;
	$rs=$pdo ->query($sql);
	while($data=$rs->fetch(PDO::FETCH_ASSOC)){
		progeny($data["t_id"]);
		echo "<div class='wall'></div>";
	}
}
// 子の子孫を取得する関数
function progeny($id){
	//受け取ったIDのツイートを表示し嫡子を抽出。無ければ終わり
	build_tweet($id);
	$sql="SELECT * FROM tweets WHERE canceled=0 AND reply_to=".$id." LIMIT 1";
	global $pdo;
	$rs=$pdo ->query($sql);
	$data=$rs->fetch(PDO::FETCH_ASSOC);
	//自分を親とするツイートがあれば
	if(!empty($data)){
		//さらにそれを呼び出す
		progeny($data["t_id"]);
	}
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>

	<meta charset="utf-8">
	<title>thread</title>
	<link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
	<meta charset="utf-8">
	<link rel="stylesheet" href="css/style.css">
	<!-- <link rel="stylesheet" href="css/user.css"> -->
	<link rel="stylesheet" href="css/thread.css">
	<script src="js/jquery-3.4.1.min.js"></script>
</head>
<body>
	<div id="body_area">
	<!--
◆左のナビゲーション
-->
<?php require_once("tool/nav.php"); ?>



<!--
◆ここから中央のメインコンテンツエリア
-->
<main id="main_area">
  <?php if(!empty($_SESSION["login"])): ?>
		<div><h1>スレッド=ID<?php echo $_GET["t_id"]; ?></h1>
	<?php else: ?>
    <!--◇ 非ログイン時にのみ表示-->
    <div><a href="login.php">→ログイン</a></div>
  <?php endif; ?>
	
	<?php ancestor($_GET["t_id"]); ?>
	<div id="target">
	<?php build_tweet($_GET["t_id"]);?>
	</div>
	<?php children($_GET["t_id"]); ?>

</main>
<!--
◆ここからサイドバー
-->
<?php require("tool/aside.php") ?>

</div>
<script src="js/tweet.js"></script>
</body>
</html>