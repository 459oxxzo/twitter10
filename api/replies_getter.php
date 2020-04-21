<?php 
if(empty($_GET["t_id"])){
		exit();
	}
	require_once("config.php");
	//t_idに対するすべての
	$sql="SELECT * FROM user_details u,tweets t LEFT JOIN entities e ON t.t_id=e.t_id WHERE reply_to = :t_id AND t.u_id = u.u_id ORDER BY created";
	$stmt = $pdo->prepare($sql);
	//ターゲットツイートに対する返信を全部吐く
	$stmt->bindValue("t_id",$_GET["t_id"],PDO::PARAM_INT);
	$stmt->execute();
	$rs = $stmt->fetchAll(PDO::FETCH_ASSOC);
	$html = "";
	// var_dump($rs);
	// for($i=0;$i<$rs.length;$i++){
	// 	console.log($i);
	// }  
	foreach($rs AS $row){
		//var_dump($row);
		$hm = "";
		$html .= make_tweet($row);
	}

	echo $html;

	

function make_tweet($row){
//var_dump($row);
$hm = "";
$hm.="<article><div class='container'><div class=";
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
$hm.='<span>'.$row["nickname"].'</span><span>'.$row["u_id"].'</span>';
$hm.='</a><span>'.diff_calc($row["created"]).'</span>';
$hm.='<div class="dd_menu" data-u_id="'.$row["u_id"].'"></div></header>';
$hm.='<div class="content"><p>'.h($row["content"]).'</p></div>';
$hm.='<div class="media">';
if(!empty($row["ent_id"])){
	$hm.="<img src='entities/imgs/".$row["m_source"]."'>";
}
$hm.='</div>';
$hm.='<div class="created"><div class="this_reply" data-t_id="'.$row["t_id"].'"><span><i class="fas fa-reply"></i>'.$row["replies"].'</span></div>';
$hm.='<div class="this_retweet"><span data-t_id="'.$row["t_id"].'"><i class="fas fa-retweet"></i>'.$row["retweets"].'</span></div>';
$hm.='<div class="this_like" data-t_id="'.$row["t_id"].'"><i class="fas fa-heart"></i><span>'.$row["likes"].'<span></div></div></div></div>';

	if($row["replies"]!=0){
		$user= "root";
		$dbpass="";
		$host="localhost";
		$dbname="twitter";
		$dsn="mysql:host={$host};dbname={$dbname};charset=utf8";//forと同じくセミコロン
		$pdo2=new PDO($dsn,$user,$dbpass);
		// require("config.php");
		$sql="SELECT * FROM user_details u,tweets t LEFT JOIN entities e ON t.t_id=e.t_id WHERE reply_to = :t_id2 AND t.u_id = u.u_id ORDER BY created LIMIT 1";
		$stmt2 = $pdo2->prepare($sql);
		//ターゲットツイートに対する返信を全部吐く
		$stmt2->bindValue("t_id2",$row["t_id"],PDO::PARAM_INT);
		$stmt2->execute();
		$row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
		var_dump($row2);
		$hm .= '</article>'.make_tweet($row2);
	}else{
		$hm.='</article>';
	}	

return $hm;
}


?>