<?php
session_start();
require_once("../tool/config.php");

$sql0="SELECT count(*) AS cnt FROM follows WHERE saki=:saki AND moto=:moto";
$stmt0=$pdo->prepare($sql0);
$stmt0->bindvalue(":saki",$_GET["u_id"],PDO::PARAM_STR);
$stmt0->bindvalue(":moto",$_SESSION["u_id"],PDO::PARAM_STR);
$stmt0->execute();
$flag = $stmt0->fetch(PDO::FETCH_ASSOC);

//データベースにすでに列があるかで分岐
if($flag["cnt"]>0){
//削除処理

  $sql1="DELETE FROM follows WHERE moto=:moto AND saki=:saki";
  $stmt1=$pdo->prepare($sql1);
  $stmt1->bindvalue(":saki",$_GET["u_id"],PDO::PARAM_STR);
  $stmt1->bindvalue(":moto",$_SESSION["u_id"],PDO::PARAM_STR);
  $r1=$stmt1->execute();
  
  $json = json_encode($r1);
  echo $json;
}else{
//追加処理
  $sql="INSERT INTO follows(moto,saki) VALUES (:moto,:saki)";
  $stmt=$pdo->prepare($sql);
  $stmt->bindvalue(":moto",$_SESSION["u_id"],PDO::PARAM_STR);
  $stmt->bindvalue(":saki",$_GET["u_id"],PDO::PARAM_STR);
  $r=$stmt->execute();
  $json = json_encode($r);
  echo $json;
}



?>