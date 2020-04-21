<?php
/****************************************
ツイートIDを引数を基に内容をDBから取得

変数




******************************************/

function build_tweet($tid){

	require_once("tool/function.php");
	require_once("tool/config.php");
	global $pdo;//関数の中で使うにはグローバル宣言が必要
	
	//
	$a = "SELECT l.t_id,l.u_id lu,r.u_id rep_to_user,l.content,l.created,l.canceled,l.likes,l.replies,l.reply_to,l.retweets FROM tweets l LEFT JOIN tweets r ON l.reply_to = r.t_id WHERE l.t_id=".$tid." AND l.canceled=0";
	//
	$b = "SELECT t_id,lu,rep_to_user,content,created,canceled,likes,replies,reply_to,retweets,u_id,nickname FROM ($a) AS tt LEFT JOIN user_details ud ON tt.rep_to_user = ud.u_id";
	//
	$sqlb = "SELECT t_id,content,created,lu u_id,dd.nickname,tcount,icon,retweets,likes,replies,reply_to,rep_to_user,ttt.nickname rep_to_uname FROM user_details dd, ($b) AS ttt WHERE lu=dd.u_id";
	
	$stmtb = $pdo->query($sqlb);
	$row = $stmtb->fetch(PDO::FETCH_ASSOC);
	
	//
	if(empty($_SESSION["u_id"])){
		$sqlw = "SELECT COUNT(*) wu FROM re_tweets WHERE t_id=$tid AND u_id='GUEST'";
	}else{
		$sqlw = "SELECT COUNT(*) wu FROM re_tweets WHERE t_id=$tid AND u_id='".$_SESSION["u_id"]."'";
	}
	
	$stmw = $pdo->query($sqlw);
	$roww = $stmw->fetch(PDO::FETCH_ASSOC);


?>
	<article id="t_<?php echo $row["t_id"]; ?>">
	<!--◆ 上側のメッセージ領域 -->
		<?php if($row["reply_to"]!=null): ?>
			<div class="top_msg">
				<div class="rep_line">rl</div>
			<!-- 返信先のデータが取得できていれば -->
				<?php if(!empty($row["rep_to_user"])): ?>
					<a href="user.php?u_id=<?php echo h($row["rep_to_user"]); ?>">			
					<?php echo h($row["rep_to_uname"]); ?>さん</a>の
					<a href="thread.php?t_id=<?php echo h($row["reply_to"]); ?>#target" >tweet</a>への返信
			<!-- そうでなければ -->
				<?php else: ?>		
					<span class="gray">返信先は取り消されました<span>
				<?php endif; ?>
<!-- これのテストは明日する -->
					
			</div>
		<?php endif; ?>	

	<!--◆ 本体領域 -->
		<div class="container">

		<!--◆ 左側のアイコン領域 -->		
		<div class="<?php 
	  	if($row["replies"]!=0){
	        echo "t_side_box rep_line";
	    }else{
	  	    echo "t_side_box";
	    } ?>">
		<!-- アイコン画像 --SQLいじる必要-->
		<img class="icon" src="icon/<?php 
	    if(empty($row["icon"])){
		      echo "default.jpg";
	    }else{echo $row["icon"];
			} ?>">
			<!--SQLを書き換えてiconを取り出せるように-->
		</div>

		<!--◆ 右側のコンテンツ領域 -->
		<div class="t_main_box">	
		
		<!--◇ tweetしたユーザーとtweet時間 -->
			<header>
				<a href="user.php?u_id=<?php echo h($row["u_id"]); ?>">
				<span class="nickname">
					<?php echo $row["nickname"]; ?> </span>
				<span class="user_id">
					@<?php echo $row["u_id"]; ?></span></a>
				<span class="elapsed_time">
					<?php echo diff_calc($row["created"]); ?></span>
				<div class="dd_menu" data-u_id="<?php echo $row["u"]; ?>">
				<span class="edit">●編集</span> / <span><a href="thread.php?t_id=<?php echo h($row["t_id"]); ?>#target" >〇ここを見る</a><span></div>
	    </header>

	  <!--◇ 本文 -->
			<div class="content">
				<p><?php echo make_link(make_tag(nl2br(h($row["content"])))); ?></p>
	    </div>
	        
	  <!--◇ 画像の挿入 -->
					<div class="media">
	        <?php 
							// $pdo2=new PDO($dsn,$user,$dbpass);
	          	$sql_m = "SELECT m_type,m_source FROM entities WHERE t_id=".$row["t_id"];
							$rs_m=$pdo->query($sql_m);
							//ChromePhp::log($tid.':'.$rs_m);
							// echo $rs_m->rowCount();
							// var_dump($rs_m);
							// if(!empty($rs_m[1])){
							//↓fatal errorの多発点。
								if($rs_m->rowCount() > 0) {
									$row_m = $rs_m->fetch(PDO::FETCH_ASSOC);
									echo "<img src='entities/imgs/".$row_m["m_source"]."'>";
									// echo "<div class='bg_box' style='background-image:url(entities/imgs/".$row_m["m_source"].")'>";
								}
							//ここまでの間に処理が終わっている（以下スキップ） }
	        ?>
	        </div>
	        <!-- <?php echo "this" ?> -->
	        
<!-- tweetのデータ -->
	<div class="social_num" data-t_id="<?php echo $row["t_id"]; ?>">
		
		<!-- ◇返信 -->
		<div class="this_reply"><span><i class="fas fa-reply"></i>
			<?php echo $row["replies"]?></span>
		</div>
							
			<!-- リツイート済みの処理に異常 赤くならない
			自分の投稿でなく、かつ未リツイートなら（wuの値が異常では？＞＞そもそも無い）
			-->
		<!-- ◇リツイート -->
		<div class="this_retweet <?php 
			if($row["u_id"]!=$_SESSION["u_id"] && $roww["wu"]==0){
				echo "nyrt";
			}elseif($roww["wu"]>0){
				echo "alrt";
			} ?>">
			<span><i class="fas fa-retweet"></i>
			<?php echo $row["retweets"]?></span>
		</div>							
		
		<!-- ◇LIKE 自分のツイートでなくlikesにデータがあるならarl無いならnyl-->
		<?php if(!empty($_SESSION["u_id"])): ?>
			<?php	$sql_lc = "SELECT COUNT(*) lc FROM likes WHERE t_id=$tid AND u_id='".$_SESSION["u_id"]."'";
			$stmt_lc = $pdo->query($sql_lc);
			$r_lc = $stmt_lc->fetch(PDO::FETCH_ASSOC); ?>		
			<div class="this_like <?php if($row["u_id"]!=$_SESSION["u_id"]){ if($r_lc["lc"]>0){ echo "arl"; }else{ echo "nyl"; }} ?>">
		<?php else: ?>
			<div class="this_like">
		<?php endif; ?>
			<i class="fas fa-heart"></i>
			<span><?php echo $row["likes"]?></span>
		</div>

		<!-- ◇削除 -->
		<div class="this_cancel <?php 
			if($row["u_id"]!=$_SESSION["u_id"]){
				echo "hdn";
			} ?>">
			<i class="far fa-caret-square-up"></i>削除</div>
		</div>
	</div>
</div><!--clearfix-->

<!--threadではこの部分は非表示-->

	<!--返信表示用エリア-->
	<div class="t_message_box">
		<?php if($row["replies"]!=0): ?>
			<div class="replies_viewer">
				<!--ここに返信を差し込み-->
			</div>
			<div class="rv_open" data-t_id="<?php echo $row["t_id"]; ?>"><!--開閉ボタン-->
				<span class="rv_close"> 返信を見る</span> / 
				<a href="thread.php?t_id=<?php echo $row["t_id"] ?>#target">スレッドを読む</a>
			</div>
		<?php endif; ?>	
	</div>

</article>

<?php
}
?>

