<?php
/*------------------------------------
login.phpの新規登録画面で、入力された
ユーザーネームが使用可能かチェックする
------------------------------------*/
require_once("../tool/config.php");

$sql="SELECT * FROM users WHERE u_id = :u_id";
$stmt=$pdo->prepare($sql);
$stmt->bindValue(":u_id",$_GET["u_id"],PDO::PARAM_STR);
$stmt->execute();
$row=$stmt->fetch(PDO::FETCH_ASSOC);


if(!$row){
  $rs = ['r'=>1];
  $js = json_encode($rs);
  echo $js;
  //値がないなら利用可能
}else{
  $rs = ['r'=>0];
  $js = json_encode($rs);
  echo $js;
  //値があるなら利用不可
};


?>