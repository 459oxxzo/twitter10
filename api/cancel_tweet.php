<?php
session_start();
require_once("../tool/config.php");

  $sql="UPDATE tweets SET canceled = 1 WHERE t_id =:t_id";
  $stmt=$pdo->prepare($sql);
  $stmt->bindvalue(":t_id",$_GET["t_id"],PDO::PARAM_INT);
  $r=$stmt->execute();
  $js = json_encode($r);
  echo $js;

?>