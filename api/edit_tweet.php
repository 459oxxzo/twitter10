<?php
session_start();
require_once("../tool/config.php");
//require_once("../tool/function.php");

if(!empty($_POST["content"])){
  var_dump($_POST);
  var_dump($_FILES);
  $sql="UPDATE tweets SET content=:content WHERE t_id=:t_id";
  $stmt=$pdo->prepare($sql);
  $stmt->bindvalue(":t_id",$_POST["t_id"],PDO::PARAM_INT);
  $stmt->bindvalue(":content",$_POST["content"],PDO::PARAM_STR);
  $stmt->execute();
}
if(!empty($_FILES["img"])){

  if($_FILES["img"]["error"]!=0){
    header("Location: ".$_SERVER['HTTP_REFERER']);
    exit();
  }

  $types=["image/jpeg"=>".jpg","image/png"=>".png","image/gif"=>".gif"];
  $type= mime_content_type($_FILES["img"]["tmp_name"]);
    
  //拡張子を決める
  $m_source="";
  foreach($types as $key=>$val){
    if($type == $key){
      $m_source = md5($_FILES["img"]["tmp_name"].time()).$val;
      $filename = "../entities/imgs/".$m_source;
      //md5($_FILES["img"]["tmp_name"].time()).$val;
      $_SESSION["filename"]=$filename;
      move_uploaded_file($_FILES["img"]["tmp_name"],$filename);	
      break;
    }
  }
  $sqla="SELECT * FROM entities WHERE t_id=".$_POST["t_id"];
  $rs=$pdo->query($sqla);
  $row=$rs->fetch(PDO::FETCH_ASSOC);
  //var_dump($row);
  if(!empty($row["m_source"])){
    $sqlb="UPDATE entities SET m_type='".e."',m_source='".$m_source."' WHERE t_id=".$_POST["t_id"];
  }else{
    $sql2="INSERT INTO entities(t_id,m_type,m_source)";
    $sql2.=" VALUES((SELECT t_id FROM tweets WHERE u_id ='";
  }
  //foreach
  //ツイートした本人の最新投稿（つまりさっきの）のt_id取る
  //画像の情報をツイートと紐づけて保存
  // 
  // $sql2.=$_SESSION["u_id"]."' ORDER BY t_id DESC LIMIT 1),";
  // $sql2.="'img','".$m_source."')";
  
  // $stmt2 = $pdo->query($sql2);
}




  $uri = $_SERVER['HTTP_REFERER'];
  echo $uri;
  header("Location: ".$uri);
?>
