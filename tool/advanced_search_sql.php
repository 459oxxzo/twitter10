<?php
$cntw=0;
if(!empty($_GET)){
//var_dump($_GET);
  $post= " FROM tweets WHERE t_id>1 ";
	$add="";
	
	//本文に～含む
	if(!empty($_GET["kw"])){
		$str = explode(" ",h($_GET["kw"]));
		
		$add .="AND (";
		foreach($str as $s){
			if($cntw==0){
				$add .="";
			}elseif($_GET["andor1"]=="and_kw"){
				$add .="AND ";	
			}else{
				$add .="OR ";
			}
			$cntw++;
			$add.="content LIKE '%".$s."%' ";
		}	
		$add .=") ";
	}

	//名前に～含む
	if(!empty($_GET["u_id"])){
		$str = explode(" ",h($_GET["u_id"]));
		$cnt=0;
		$add .="AND (";
		foreach($str as $s){
			if($cnt==0){
				$add .="";
			}else{
				$add .="OR ";
			}
			$cnt++;
			$add.="u_id='".$s."' ";
		}	
		$add .=") ";
	}
	
	//タグ
		if(!empty($_GET["tag"])){
		$add.="AND content LIKE '%"."[!]"."%' ";
	}
	//html
	if(!empty($_GET["htm"])){
		$add.="AND content LIKE '%"."http"."%' ";
	}
	
	//期間//日付には‘’を忘れず。結果が帰らずしばらく詰まった。
	if(!empty($_GET["since"]) && !empty($_GET["until"])){
		$add.="AND created BETWEEN '".$_GET["since"]."' AND '".$_GET["until"]."' ";
	}else{
		if(!empty($_GET["since"])){
			$add .="AND created > '".$_GET["since"]."' ";
		}
		if(!empty($_GET["until"])){
			$add .="AND created < '".$_GET["until"]."' ";
		}
	}
	
	$add.="AND canceled=0 ORDER BY t_id DESC";
	$post.=$add;
}else{
  $post=" FROM tweets WHERE t_id=1";
}
  $a_search_amount = "SELECT count(*) amount".$post." LIMIT 30";
  $posts = "SELECT t_id".$post." LIMIT 30";
  
  //---ページ数を取得して追加---
  if(!empty($_GET["page"])){
    //ページの最初のツイート＝ページ数＊30＋１番目
    $start = ($_GET["page"]-1) * 30;
    $offset = " OFFSET ".$start;
    $posts .= $offset;
  }

  $rs=$pdo->query($posts);

 // ------------ ページングデータの取得 amount ----------

    $rs2=$pdo->query($a_search_amount);
    $amount = $rs2->fetch(PDO::FETCH_ASSOC);


?>