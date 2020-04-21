<?php
session_start();
if(empty($_SESSION["login"])){
  header("Location:../login.php");
  exit();
}
//データが転送されているかを確認。なければ戻す
if(empty($_SESSION["u_id"] )|| empty($_POST["pass"])){
	 $_SESSION["fail_auth"]="認証に失敗しました。<br/>正しいパスワードを入力してください";
	header("Location:../withdraw.php");
	exit();
}

require_once("../tool/config.php");

//ユーザー情報を取得し照会する
$sql="SELECT * FROM users WHERE u_id=:u_id";
$stmt=$pdo->prepare($sql);
$stmt->bindValue(":u_id",$_SESSION["u_id"],PDO::PARAM_STR);
$stmt->execute();
$row=$stmt->fetch(PDO::FETCH_ASSOC);

var_dump($_SESSION);
var_dump($row);
//ここから認証
var_dump(password_verify($_POST["pass"],$row["pass"]));

//退会済みでないかの確認を追加(退会異常)
if(password_verify($_POST["pass"],$row["pass"]) && $row["withdrawn"]==0){
echo "-------------------------";
  //退会処理スタート update
  
  //like→like先のtweetのlikes-1
	  $sql1="UPDATE tweets INNER JOIN likes ON tweets.t_id=likes.t_id SET tweets.likes = tweets.likes -1 WHERE likes.u_id=:u_id";
	  $stmt1=$pdo->prepare($sql1);
	  $stmt1->bindvalue(":u_id",$_SESSION["u_id"],PDO::PARAM_STR);
    $stmt1->execute();
    var_dump($stmt1);
echo "---1---<br/>";
  //likesテーブルからの削除処理  
    $sql2="DELETE FROM likes WHERE u_id=:u_id";
    $stmt2=$pdo->prepare($sql2);
	  $stmt2->bindvalue(":u_id",$_SESSION["u_id"],PDO::PARAM_STR);
    $stmt2->execute();
    var_dump($stmt2);
    echo "---2---<br/>";
  //retweet->re先のre-1
	  $sql3="UPDATE tweets t INNER JOIN re_tweets r ON t.t_id=r.t_id SET t.retweets = t.retweets -1 WHERE r.u_id=:u_id";
	  $stmt3=$pdo->prepare($sql3);
	  $stmt3->bindvalue(":u_id",$_SESSION["u_id"],PDO::PARAM_STR);
    $stmt3->execute();
    var_dump($stmt3);echo "---3---<br/>";
  //likesテーブルからの削除処理  
    $sql4="DELETE FROM re_tweets WHERE u_id=:u_id";
    $stmt4=$pdo->prepare($sql4);
	  $stmt4->bindvalue(":u_id",$_SESSION["u_id"],PDO::PARAM_STR);
    $stmt4->execute();
    var_dump($stmt4);echo "---4---<br/>";
  //reply->reply先のrep-1
    $sql5="UPDATE tweets l LEFT JOIN tweets r ON l.reply_to=r.t_id SET r.replies = r.replies -1 WHERE l.u_id=:u_id";
    $stmt5=$pdo->prepare($sql5);
	  $stmt5->bindvalue(":u_id",$_SESSION["u_id"],PDO::PARAM_STR);
    $stmt5->execute();
    var_dump($stmt5);echo "---5---<br/>";
  //tweetsですべてにcanceled=1
    $sql6="UPDATE tweets SET canceled = 1 WHERE u_id=:u_id";
    $stmt6=$pdo->prepare($sql6);
	  $stmt6->bindvalue(":u_id",$_SESSION["u_id"],PDO::PARAM_STR);
    $stmt6->execute();
    var_dump($stmt6);echo "---6---<br/>";
  //users withdrawn=1
    $sql7="UPDATE users SET withdrawn = 1 WHERE u_id=:u_id";
    $stmt7=$pdo->prepare($sql7);
	  $stmt7->bindvalue(":u_id",$_SESSION["u_id"],PDO::PARAM_STR);
    $stmt7->execute();
    var_dump($stmt7);echo "---7---<br/>";
    $_SESSION["login"]="";
    header("Location:../bye.php");
}else{
    $_SESSION["fail_auth"]="認証できませんでした";
    header("Location:../withdraw.php");
}


// 	//ここで、セッションIDの再発行をするとセキュアになる。
// 	//セッションの固定化を避ける唯一の方法
// 	session_regenerate_id();

// 	//ユーザー情報を記憶してからリダイレクト
// 	$_SESSION["login"]=true;
// 	$_SESSION["u_id"]=$row["u_id"];
//   $_SESSION["nickname"]=$row["nickname"];
//   $_SESSION["icon"]=$row["icon"];
	
// 	//header("Location:../index.php");
// 	exit();//念のため
// }else{
// 	//認証失敗
// 	$_SESSION["login"]=false;
//   //ログインフォームに戻す
//   $_SESSION["fail_auth"]="ログインに失敗しました。<br />正しいユーザ名とパスワードを入力してください";
//   //header("Location:../login.php");
//   exit();

//};

?>