<?php
session_start();
	require_once("tool/config.php");
	require_once("tool/function.php");
	require_once("tool/build_tweet.php");


//未ログインは総合タイムラインのみ
if(empty($_SESSION["login"])){
  	header("Location:timeline.php");
}
$rsx="";
$rsy="";
if(!empty($_GET["xid"]) && !empty($_GET["yid"])){
	$sqlx="SELECT *, (SELECT count(*) xtoy FROM follows WHERE moto = :xid AND saki=:yid) xtoy FROM user_details WHERE u_id=:xid";
	
	$stmtx=$pdo->prepare($sqlx);
  $stmtx->bindValue(":xid",$_GET["xid"],PDO::PARAM_STR);
  $stmtx->bindValue(":yid",$_GET["yid"],PDO::PARAM_STR);
	$stmtx->execute();
  $rsx=$stmtx->fetch(PDO::FETCH_ASSOC);
  
  $sqly="SELECT *, (SELECT count(*) ytox FROM follows WHERE moto = :yid AND saki=:xid) ytox FROM user_details WHERE u_id=:yid";
  $stmty=$pdo->prepare($sqly);
	$stmty->bindValue(":xid",$_GET["xid"],PDO::PARAM_STR);
	$stmty->bindValue(":yid",$_GET["yid"],PDO::PARAM_STR);
	$stmty->execute();
	$rsy=$stmty->fetch(PDO::FETCH_ASSOC);
	

	function make_pop($arr){
		$f = "";
		//var_dump($arr);
		foreach($arr as $val){
			//var_dump($val);
			$f .= "<span>".$val."</span><br/>";
		}
		echo $f;
	}

}
?>

<!DOCTYPE html>
<html lahg="ja">
<head>
  <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
	<meta charset="utf-8">
	<title>USER</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/user.css">
  <link rel="stylesheet" href="css/sg.css">
	<script src="js/jquery-3.4.1.min.js"></script>
</head>
<body>
  <div id="body_area">

<!--
◆左のナビゲーション
-->
<?php require_once("tool/nav.php"); ?>

<!--
◆ここからサイドバー
-->
<?php require("tool/aside.php") ?>

<!--
◆ここから中央のメインコンテンツエリア
-->
<main id="main_area">
<span class="back">← </span><h1 style="display:inline-block">分析ページ</h1>


<div id="container">
	<!-- #この下にフォームと個人情報欄 -->
	<form action="social_graph.php" method="get">
		<table id="sg_form">
			<tr>
				<th><input type="text" name="xid" id="xid"></th>
				<th><button type="submit" id="btn">分析</button></th>
				<th><input type="text" name="yid" id="yid"></th>
			</tr>
			<tr>
				<td><p>xのユーザーID</p></td>
				<td></td>
				<td><p>yのユーザーID</p></td>
			</tr>
			<tr>
				<td id="x_wrapper">
			
      <!-- 個人情報の表示   -->
			<?php if(!empty($_GET["xid"]) && !empty($_GET["yid"])): ?>
			<div id="x_details">
				<img class="icon" src="icon/<?php echo $rsx["icon"]; ?>"></div>
				<div class="d_wrapper">
					<span class="id"><?php echo $rsx["u_id"]; ?></span>
					<span class="name"><?php echo $rsx["nickname"]; ?></span>
				</div>
				<div class="description"><?php echo $rsx["description"]; ?></div>
      </div>
      <?php endif; ?>
				</td>
		
				<td id="relation_view">
					<?php if(!empty($rsx) && $rsx["xtoy"]==1): ?>
						<div id="x_to_y"><p>→</p></div>
					<?php endif; ?>
					<?php if(!empty($rsy) && $rsy["ytox"]==1): ?>
						<div id="y_to_x"><p>←</p></div>
					<?php endif; ?>
				</td>

				<td id="y_wrapper">
			
      <!-- 個人情報の表示   -->
			<?php if(!empty($_GET["xid"]) && !empty($_GET["yid"])): ?>
			<div id="y_details">
				<img class="icon" src="icon/<?php echo $rsy["icon"]; ?>"></div>
				<div class="d_wrapper">
					<span class="id"><?php echo $rsy["u_id"]; ?></span>
					<span class="name"><?php echo $rsy["nickname"]; ?></span>
				</div>
				<div class="description"><?php echo $rsy["description"]; ?></div>
      </div>
      <?php endif; ?>
				</td>
			</tr>
		</table>
	</form>

  <?php 
  	if(!empty($_GET["xid"]) || !empty($_GET["yid"])):
	?>
	
	<?php 
		//
		require("api/get_follows.php"); 
	?>
	
	<?php
  	$add=0;
    if($rsx["xtoy"]==1 && $rsy["ytox"]==1){
      $add=1+count($arr_x1y1);
    }
  ?>

		<table id="graph">
	    <tr><th class="head xy" id="xy0"></th>
	        <th class="head ym rgh"><div class="rbar ym">Yの相互フォロー</div></th>
	        <th class="head yf rgh"><div class="rbar yf">Yがフォロー</div></th>
	        <th class="head yr rgh"><div class="rbar yr">Yをフォロー</div></th>
	        <th class="head "></th></tr>
	    <tr><th class="head xm lft"><div class="lbar xm">Xの相互フォロー</div></th>
					<td class="cell xm ym" id="xy1"><p><?php echo $add; ?></p>
							<div class="pop"><?php make_pop($arr_x1y1); ?></div></td>					
					<td class="cell xm yf" id="xy2"><p><?php echo count($arr_x1y2); ?></p>
							<div class="pop"><?php make_pop($arr_x1y2); ?></div></td>
					<td class="cell xm yr" id="xy3"><p><?php echo count($arr_x1y3); ?></p>
							<div class="pop"><?php make_pop($arr_x1y3); ?></div></td>
	        <td class="cell xm total"><p><?php echo count($arr_x1); ?></p></td></tr>
	    <tr><th class="head xf lft"><p><div class="lbar xf">Xがフォロー</div></p></th>
					<td class="cell xf ym" id="xy4"><p><?php echo count($arr_x2y1); ?></p>
							<div class="pop"><?php make_pop($arr_x2y1); ?></div></td>
					<td class="cell xf yf" id="xy5"><p><?php echo count($arr_x2y2); ?></p>
							<div class="pop"><?php make_pop($arr_x2y2); ?></div></td>
					<td class="cell xf yr" id="xy6"><p><?php echo count($arr_x2y3); ?></p>
							<div class="pop"><?php make_pop($arr_x2y3); ?></div></td>
	        <td class="cell xf total"><p><?php echo count($arr_x2); ?></p></td></tr>
	    <tr><th class="head xr lft"><div class="lbar xr">Xをフォロー</div></th>
					<td class="cell xr ym" id="xy7"><p><?php echo count($arr_x3y1); ?></p>
							<div class="pop"><?php make_pop($arr_x3y1); ?></div></td>
					<td class="cell xr yf" id="xy8"><p><?php echo count($arr_x3y2); ?></p>
							<div class="pop"><?php make_pop($arr_x3y2); ?></div></td>
					<td class="cell xr yr" id="xy9"><p><?php echo count($arr_x3y3); ?></p>
							<div class="pop"><?php make_pop($arr_x3y3); ?></div></td>
	        <td class="cell xr total"><p><?php echo count($arr_x3); ?></p></td></tr>
	    <tr><th class="head xr "></th>
					<td class="cell ym total" id="yy1"><p><?php echo count($arr_y1); ?></p></td>
					<td class="cell yf total" id="yy2"><p><?php echo count($arr_y2); ?></p></td>
					<td class="cell yr total" id="yy3"><p><?php echo count($arr_y3); ?></p></td>
	        <td class="cell total"><p>合計</p></td></tr>
		</table>
		
	<?php endif; ?>
</div><!-- #container -->
<div class="insert">
	<!-- <p id="next" data-page="1">next</p> -->
</div>
</main>

<!--
  ◆ モーダルウインドウ 
-->
<?php require("tool/modal.php") ?>
<?php require("tool/media_modal.php") ?>



<script src="js/tweet.js"></script>
<script>
  
  
$(function() {
	$("#btn").on("click",function(){
		var xid = $("#xid").val();
		var yid = $("#yid").val();
		console.log(xid);
			$.ajax({
        url:"api/get_follows.php",
				data:{
						'xid':xid,
					},
				type:"get",
				dataType:"json",
			}).done(function(data){
				console.log("OK");
/*			$("#x_details .icon").attr('src',data["x"]["icon"]);
				$("#x_details .id").text(data["x"]["u_id"]);
				$("#x_details .name").text(data["x"]["nickname"]);
				$("#x_details .description").text(data["x"]["description"]);*/
			}).fail(function(){
				console.log("NG");
			});
	});
});

    $(function(){
      $('.xm').on('mouseover',function(){
        $('.xm').css('opacity',0.8);
      });
      $('.xf').on('mouseover',function(){
        $('.xf').css('opacity',0.8);
      });
      $('.xr').on('mouseover',function(){
        $('.xr').css('opacity',0.8);
      });
      $('.ym').on('mouseover',function(){
        $('.ym').css('opacity',0.8);
      });
      $('.yf').on('mouseover',function(){
        $('.yf').css('opacity',0.8);
      });
      $('.yr').on('mouseover',function(){
        $('.yr').css('opacity',0.8);
      });
      $('.xm').on('mouseout',function(){
        $('.xm').css('opacity',1);
      });
      $('.xf').on('mouseout',function(){
        $('.xf').css('opacity',1);
      });
      $('.xr').on('mouseout',function(){
        $('.xr').css('opacity',1);
      });
      $('.ym').on('mouseout',function(){
        $('.ym').css('opacity',1);
      });
      $('.yf').on('mouseout',function(){
        $('.yf').css('opacity',1);
      });
      $('.yr').on('mouseout',function(){
        $('.yr').css('opacity',1);
      });

    });

</script>
</body>
</html>