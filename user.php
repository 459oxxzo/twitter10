<?php
session_start();

//未ログインは総合タイムラインのみ
if(empty($_SESSION["login"])){
  	header("Location:timeline.php");
}
//関数セット（各ページ共通）
  require_once("tool/config.php");
  require_once("tool/function.php");
  require_once("tool/build_tweet.php");
  $this_page = basename(__FILE__);

//取得するツイートの条件：index個別
  require_once("tool/user_sql.php");

?>

<!DOCTYPE html>
<html lahg="ja">
<head>
  <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
	<meta charset="utf-8">
	<title>INDEX</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/user.css">
	<script src="js/jquery-3.4.1.min.js"></script>
</head>
<body>

  <div id="body_area">



<?php // ◆左のナビゲーション
	require_once("tool/nav.php"); ?>

<main id="main_area">

<?php
	// ◆ここから中央のメインコンテンツエリア
	// ★ログインユーザー情報を表示する領域：index個別
 	require_once("tool/user_header.php");
 ?>

<div class="page">
<?php
	// ★受け取ったidリストからtweet書き出し（共通）
	while($r = $rs->fetch(PDO::FETCH_ASSOC)){
      build_tweet($r["t_id"]);
		}
?>
</div>
 <?php require_once("tool/user_page_nav.php"); ?>
</main>

<?php // ◆ここからサイドバー
	require("tool/aside.php") ?>
</div>

<script src="js/tweet.js"></script>
</body>
</html>
