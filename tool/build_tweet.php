<?php
/****************************************
ツイートID(引数)を基に内容をDBから取得
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
		<?php
			// ◆上側のメッセージ領域
			echo part_top($row);
			// ◆ 本体領域------
			echo '<div class="container">';
				// ◆ 左側のアイコン領域
				echo part_icon($row);
				// ◆ 右側のコンテンツ領域
				echo '<div class="t_main_box">';
					// ◇ tweetしたユーザーとtweet時間
					echo part_created($row);
					// ◇ 本文
					echo '<div class="content"><p>';
					echo make_link(make_tag(nl2br(h($row["content"]))));
					echo '</p></div>';
					// ◇ 画像の挿入
					echo part_media($row);
				// ◆ tweetのデータ --
				//echo '<div class="social_num" data-t_id="'.$row["t_id"].'">';
				echo "<div class='social_num' data-t_id={$row["t_id"]}>";
					// ◇ 返信
					echo part_reply($row);
					// ◇ リツイート
					echo part_retweet($row,$roww);
					// ◇ いいね
					echo part_like($row);
					// ◇ 削除
					echo part_cancel($row);
			echo '</div></div>'; // t_main,container
			// threadではこの部分は非表示
			// ◆ 返信表示用領域
			echo part_rep_view($row);
		?>
	</article>
<?php
}
// ------------ function build_tweet ----------------
?>


<?php
// メンテナンスしやすくするために関数化して分割

// ◆上側のメッセージ領域 ----------
function part_top($row){
	$html="";// 変数宣言（必須）
	if($row["reply_to"]!=null){
		$html.='<div class="top_msg">';// ※
		$html.='<div class="rep_line">rl</div>';

		// 返信先のデータが取得できていれば
		if( !empty($row["rep_to_user"])){
			// 自分への返信（連ツイ）なら
			if(!empty($_SESSION["u_id"]) && $row["rep_to_user"]==$_SESSION["u_id"]){
				$html.='自分の';
			}else{
				// 他のユーザー宛なら
				$html.='<a href="user.php?u_id='.h($row["rep_to_user"]).'">';
				$html.=h($row["rep_to_uname"])."さん</a>の";
			}
			$html.='<a href="thread.php?t_id='.h($row["reply_to"]).'#target" >tweet</a>への返信';
		// 返信先は存在するのにデータが不在
		}else{
			$html.='<span class="gray">返信先は取り消されました<span>';
		}
		$html.='</div>'; // ※
	}
	return $html;
}

// ◆ 左側のアイコン領域 ----------
function part_icon($row){
	$html='<div class=';
		if($row["replies"]!=0){
	    $html.='"t_side_box rep_line">';
    }else{
	  	$html.='"t_side_box">';
    }
	$html.='<img class="icon" src="icon/';
	  if(empty($row["icon"])){
	    $html.='"default.jpg">';
	  }else{
			$html.=$row["icon"].'">';
		}
	$html.='</div>';
	return $html;
}

// ◇ tweetしたユーザーとtweet時間 ----------
function part_created($row){
	$html='<header>';
	$html.='<a href="user.php?u_id='.h($row["u_id"]).'">';
	$html.='<span class="nickname">'.$row["nickname"].'</span>';
	$html.='<span class="user_id">@'.$row["u_id"].'</span></a>';
	//時間計算
	$html.='<span class="elapsed_time">'.diff_calc($row["created"]).'</span>';
	//右寄せ組
	$html.='<div class="dd_menu">';
	if(!empty($_SESSION["u_id"]) &&  $row["u_id"]==$_SESSION["u_id"]){
		$html.= '<span class="edit" data-t_id='.h($row["t_id"]).'>●編集</span> / ';
	}
	//スレッドへのリンク
	$html.='<span><a href="thread.php?t_id='.h($row["t_id"]).'#target" >〇ここを見る</a><span></div></header>';
	return $html;
}

// ◇ 画像の挿入 ----------
function part_media($row){
	global $pdo;
	$html='<div class="media">';
	$sql = "SELECT m_type,m_source FROM entities WHERE t_id=".$row["t_id"];
	$rs=$pdo->query($sql);
	//画像があればmediaに挿入
	if($rs->rowCount() > 0) {
		$media = $rs->fetch(PDO::FETCH_ASSOC);
		$html.="<img src='entities/imgs/".$media["m_source"]."'>";
	}
	$html.="</div>";
	return $html;
}

// ◇返信  ----------
function part_reply($row){
	$html='<div class="this_reply"><span><i class="fas fa-reply"></i>';
	$html.=$row["replies"].'</span></div>';
	return $html;
}

// ◇リツイート ----------
function part_retweet($row,$roww){
	$html='<div class="this_retweet ';
	if(!empty($_SESSION["u_id"])){
		if($row["u_id"]!=$_SESSION["u_id"] && $roww["wu"]==0){
			$html.='nyrt';
		}elseif($roww["wu"]>0){
			$html.='alrt';
		}
	}
	$html.='">';
	$html.='<span><i class="fas fa-retweet"></i>';
	$html.=$row["retweets"].'</span></div>';
	return $html;
}

// ◇LIKE  ----------

//それが自分自身のツイートでなく,かつかつていいねした
//（likesにデータがある）ならclass=arl,無いならclass=nyl
function part_like($row){
	global $pdo;
	//ログインしているなら
	if(!empty($_SESSION["u_id"])){
		//自分とツイートIDでいいねテーブルを検索
		$sql = "SELECT COUNT(*) lc FROM likes WHERE t_id=".$row["t_id"]." AND u_id='".$_SESSION["u_id"]."'";
		$stmt = $pdo->query($sql);
		$r = $stmt->fetch(PDO::FETCH_ASSOC);

		$html='<div class="this_like ';
		// 自分のツイートではなく
		if($row["u_id"]!=$_SESSION["u_id"]){
			// 検索結果が0でないなら（テーブルにデータがあれば）
			if($r["lc"]>0){
				$html.= 'arl'; // already like->like_on
			}else{
				$html.= 'nyl'; // not yet like->like_off
			}
		}
		$html.='">';
	// 未ログイン
	}else{
		$html='<div class="this_like">';
	}
	$html.='<i class="fas fa-heart"></i>';
	$html.='<span>'.$row["likes"].'</span></div>';
	return $html;
}

// ◇削除 ----------
function part_cancel($row){
	$html='<div class="this_cancel ';
	if(!empty($_SESSION["u_id"]) && $row["u_id"]!=$_SESSION["u_id"]){
		$html.= 'hdn';
	}elseif(empty($_SESSION["login"])){
		$html.= 'hdn';
	}

	$html.='">';
	$html.='<i class="far fa-caret-square-up"></i>削除</div>';
	$html.='</div>';
	return $html;
}

// 返信表示用エリア ----------
function part_rep_view($row){
	$html='<div class="t_message_box">'; // #
	if($row["replies"]!=0){
		//ここに返信を差し込み
		$html.='<div class="replies_viewer"></div>';
		//開閉ボタン
		$html.='<div class="rv_open" data-t_id='.$row["t_id"].'>'; // ##
			$html.='<span class="rv_close"> 返信を見る</span> /';
			$html.='<a href="thread.php?t_id='.$row["t_id"].'#target">スレッドを読む</a>';
		$html.='</div>'; // ##
	}
	$html.='</div>'; // #
	return $html;
}


?>
