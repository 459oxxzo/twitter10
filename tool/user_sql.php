<?php

  
  //表示するツイートの取得
  //tweetsから本人の削除していないツイート抽出
  $t_sql_a = "SELECT t_id,created FROM tweets WHERE u_id='".$_GET["u_id"]."' AND canceled=0";
  //re_tweetsからユーザーのリツイートしたt_id抽出
  $t_sql_b = "SELECT t_id,created FROM re_tweets WHERE u_id='".$_GET["u_id"]."'";
  //リツイートしたツイートの内容取得（削除済除く）
  $t_sql_c = "SELECT ll.t_id,ll.created FROM ($t_sql_b) ll LEFT JOIN tweets rr ON ll.t_id=rr.t_id WHERE rr.canceled=0";
  //投稿もしくはリツイート時間順に重複削除し並べる
  $posts= "SELECT * FROM ($t_sql_a union $t_sql_c) aa ORDER BY created DESC LIMIT 30";


    //ページ数を取得して追加
  if(!empty($_GET["page"])){
    //ページの最初のツイート＝ページ数＊30＋１番目
    $start = ($_GET["page"]-1) * 30;
    $offset = " OFFSET ".$start;
    $posts .= $offset;
  }
  //結果取得(t_id,createdのみ)
  $rs=$pdo->query($posts);
  /*
  ------------ ページングデータの取得 amount ----------
  */
    $index_amount= "SELECT count(*) amount FROM ($t_sql_a union $t_sql_c) aa ORDER BY created DESC";
    $rs2=$pdo->query($index_amount);
    $amount = $rs2->fetch(PDO::FETCH_ASSOC);
  
/*ユーザー情報の取得*/
    $user_sql = "SELECT *,(SELECT count(*) cnt FROM tweets WHERE u_id=:u_id AND canceled=0) cnt FROM user_details u WHERE u.u_id =:u_id";
    $dstmt=$pdo->prepare($user_sql);
    $dstmt->bindValue(":u_id",$_GET["u_id"],PDO::PARAM_STR);
    $dstmt->execute();
    $ud = $dstmt->fetch(PDO::FETCH_ASSOC);
  
    
/*フォロー関係の値を取得*/
    //このユーザがフォロー　
    //countとGroup byを使うとゼロ件の時nullが戻ってしまうため、一度条件に合う行を抽出してからcount
    $follow_a = "SELECT count(*) follow FROM (SELECT moto FROM `follows` WHERE moto='".$_GET['u_id']."') a";

    //このユーザをフォロー
    $follow_b = "SELECT count(*) follower FROM (SELECT saki FROM `follows` WHERE saki='".$_GET['u_id']."') b";

    //あなたがユーザをフォロー1/0
    $follow_c = "SELECT count(*) yn FROM follows WHERE moto='".$_SESSION['u_id']."' AND saki='".$_GET['u_id']."'";
    //統合
    $follow_sql = "SELECT * FROM ($follow_a) x,($follow_b) y, ($follow_c) z";
    //データベースに接続
    $rsf=$pdo->query($follow_sql);
    $fdata=$rsf->fetch(PDO::FETCH_ASSOC);

?>