<?php
session_start();
require_once("../tool/config.php");
//$f = $_GET["flag"];

  $sql="SELECT count(*) cnt FROM likes WHERE t_id=:t_id AND u_id=:u_id";
  $stmt=$pdo->prepare($sql);
  $stmt->bindvalue(":t_id",$_GET["t_id"],PDO::PARAM_INT);
  $stmt->bindvalue(":u_id",$_SESSION["u_id"],PDO::PARAM_STR);
  $stmt->execute();
 
  $flag = $stmt->fetch(PDO::FETCH_ASSOC);

	if($flag["cnt"] > 0){
	  $sql3="DELETE FROM likes WHERE t_id = :t_id AND u_id = :u_id";
	  $stmt3=$pdo->prepare($sql3);
	  $stmt3->bindvalue(":t_id",$_GET["t_id"],PDO::PARAM_INT);
	  $stmt3->bindvalue(":u_id",$_SESSION["u_id"],PDO::PARAM_STR);
	  $stmt3->execute();
	  
	  $sql="UPDATE tweets SET likes = likes - 1  WHERE t_id =:t_id";
	  $stmt=$pdo->prepare($sql);
	  $stmt->bindvalue(":t_id",$_GET["t_id"],PDO::PARAM_INT);
	  $stmt->execute();

	}else{

	  $sql3="INSERT INTO likes(t_id,u_id) VALUES (:t_id,:u_id)";
	  $stmt3=$pdo->prepare($sql3);
	  $stmt3->bindvalue(":t_id",$_GET["t_id"],PDO::PARAM_INT);
	  $stmt3->bindvalue(":u_id",$_SESSION["u_id"],PDO::PARAM_STR);
	  $stmt3->execute();
	  
	  $sql="UPDATE tweets SET likes = likes + 1  WHERE t_id =:t_id";
	  $stmt=$pdo->prepare($sql);
	  $stmt->bindvalue(":t_id",$_GET["t_id"],PDO::PARAM_INT);
	  $stmt->execute();
	}

  $sql2="SELECT likes FROM tweets WHERE t_id = :t_id";
  $stmt2=$pdo->prepare($sql2);
  $stmt2->bindValue(":t_id",$_GET["t_id"],PDO::PARAM_STR);
  $stmt2->execute();
  $row=$stmt2->fetch(PDO::FETCH_ASSOC);

  $js = json_encode($row);
  echo $js;

?>