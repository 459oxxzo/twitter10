<?php
/*------------------------------------

------------------------------------*/

session_start();
require_once("../tool/config.php");

  $sql3="DELETE FROM likes WHERE t_id = :t_id AND u_id = :u_id";
  $stmt3=$pdo->prepare($sql3);
  $stmt3->bindvalue(":t_id",$_GET["t_id"],PDO::PARAM_INT);
  $stmt3->bindvalue(":u_id",$_GET["u_id"],PDO::PARAM_STR);
  $stmt3->execute();
  
var_dump($_GET);

  $sql="UPDATE tweets SET likes = likes - 1  WHERE t_id =:t_id";
  $stmt=$pdo->prepare($sql);
  $stmt->bindvalue(":t_id",$_GET["t_id"],PDO::PARAM_INT);
  $stmt->execute();

  $sql2="SELECT likes FROM tweets WHERE t_id = :t_id";
  $stmt2=$pdo->prepare($sql2);
  $stmt2->bindValue(":t_id",$_GET["t_id"],PDO::PARAM_STR);
  $stmt2->execute();
  $row=$stmt2->fetch(PDO::FETCH_ASSOC);

  $js = json_encode($row);
  echo $js;

?>