<?php
session_start();

//関数セット（各ページ共通）
  require_once("tool/config.php");
  require_once("tool/function.php");
  require_once("tool/build_tweet.php");
  $this_page = basename(__FILE__);
  
  //未ログインは総合タイムラインのみ
  $posts= "SELECT t_id FROM tweets WHERE canceled=0 ORDER BY t_id DESC LIMIT 30";
  $posts2 = "SELECT count(*) amount FROM tweets WHERE canceled=0";
  
  if(!empty($_GET["page"])){
    //ページ指定がNULLならば（トップページなので）開始指定はなし。そうでなければ
    $start = ($_GET["page"]-1) * 50 + 1;
    $offset = " OFFSET ".$start;
    $posts .= $offset;
  }
  $rs=$pdo->query($posts);
  $rs2=$pdo->query($posts2);
  $amount = $rs2->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lahg="ja">
<head>
  <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
	<meta charset="utf-8">
	<title>USER</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/user.css">
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

<!--◆ ログイン時にのみ表示-->
  <?php if(!empty($_SESSION["login"])): ?>
    <!-- ここから投稿フォーム -->
    <div id="t_form"> 
    <h3>今どうしてる？</h3>
    <!-- フォーム（コメントと画像） -->
    <form action="api/tweet_post.php" method="post" enctype="multipart/form-data">
        <textarea name="content" cols="50" rows="5"></textarea><br>
        <input type="file" name="img" id="t_form_file">
        <button type="submit">送信</button>
    </form>
    <!-- 画像情報表示 -->
    <div id="preview"><img src="" ></div>
    <p id="picdata"></p>
  </div>
  <!-- ここまで -->
  <?php else: ?>
    <h3>全ツイート（一覧）</h3>
  <?php endif; ?>
  
    
<div class="page">
<?php
	// ★受け取ったidリストからtweet書き出し（共通）
	while($r = $rs->fetch(PDO::FETCH_ASSOC)){
      build_tweet($r["t_id"]);
		}
?>
</div>
 <?php require_once("tool/page_nav.php"); ?>
</main>

<?php // ◆ここからサイドバー
	require("tool/aside.php") ?>

<script src="js/tweet.js"></script>
</body>
</html>
