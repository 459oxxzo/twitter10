<?php 
if(empty($_GET["t_id"])){
		exit();
	}
	require_once("../tool/config.php");
	require_once("../tool/function.php");
	
	$sql = "SELECT t.t_id,t.u_id,nickname,icon,content,created,replies,retweets,likes,ent_id,m_source FROM user_details u,tweets t LEFT JOIN entities e ON t.t_id=e.t_id WHERE reply_to = :t_id AND t.u_id = u.u_id ORDER BY created";
	
	$stmt = $pdo->prepare($sql);
	//ターゲットツイートに対する返信を全部吐く
	$stmt->bindValue("t_id",$_GET["t_id"],PDO::PARAM_INT);
	$stmt->execute();
	$rs = $stmt->fetchAll(PDO::FETCH_ASSOC);
	$html = "";
	foreach($rs AS $row){
		$html .= make_tweet($row);
	}

	echo $html;

	

function make_tweet($row){
//var_dump($row);
$hm = "";
$hm.="<article class='reply_item'>";
//<div class='top_msg'><div class='rep_line'>rl</div></div>
$hm.="<div class='container'><div class=";
          if($row["replies"]!=0){
            $hm.= "'t_side_box rep_line'>";
          }else{
            $hm.= "'t_side_box'>";
					}
					
$hm.= "<img src='icon/";
          if(empty($row["icon"])){
            $hm.= "default.jpg'></div>";
          }else{
						$hm.= $row["icon"]."'></div>";
          }
$hm.='<div class="t_main_box">';
$hm.='<header><a href="user?u_id='.$row["u_id"].'">';
$hm.='<span class="nickname">'.$row["nickname"].'</span> <span class="user_id">@'.$row["u_id"].'</span>';
$hm.='</a><span class="elapsed_time">'.diff_calc($row["created"]).'</span>';
$hm.='<div class="dd_menu" data-u_id="'.$row["u_id"].'"></div></header>';
$hm.='<div class="content"><p>'.h($row["content"]).'</p></div>';
$hm.='<div class="media">';
if(!empty($row["ent_id"])){
	$hm.="<img src='entities/imgs/".$row["m_source"]."'>";
}
$hm.='</div>';
$hm.='<div class="social_num" data-t_id="'.$row["t_id"].'"><div class="this_reply"><span><i class="fas fa-reply"></i>'.$row["replies"].'</span></div>';
$hm.='<div class="this_retweet nyrt"><span><i class="fas fa-retweet"></i>'.$row["retweets"].'</span></div>';
$hm.='<div class="this_like nyl"><i class="fas fa-heart"></i><span>'.$row["likes"].'<span></div></div></div></div>';

$hm.='</article>';
return $hm;
}


?>