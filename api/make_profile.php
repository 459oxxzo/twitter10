<?php
	session_start();//セッション（ログイン情報）を持っていない人間がアクセスできないように
	if(empty($_SESSION["login"])){//セッションが存在し、かつlogin==true以外は
		header("Location:../login.php");//ログインページに追い出す
		exit("1");
  }
  // var_dump($_FILES);
  //array(1) { ["icon"]=> array(5) { ["name"]=> string(23) "2015-10-20 05.00.50.jpg" ["type"]=> string(10) "image/jpeg" ["tmp_name"]=> string(24) "C:\xampp\tmp\php719D.tmp" ["error"]=> int(0) ["size"]=> int(8881) } } 3
  // var_dump($_SESSION);

    require_once("../tool/config.php");
    $sql1="UPDATE user_details SET nickname = :nickname,description = :description, location = :location";
    $sql2=", icon = :icon "; 
    $sql3=" WHERE u_id = :u_id";
    $sql="";

    if($_FILES["icon"]["error"]!=0){
        echo "アイコンのアップロードに失敗しました";
        $sql=$sql1.$sql3;

    }elseif(!empty($_FILES["icon"])){
      $types=["image/jpeg"=>".jpg","image/png"=>".png","image/gif"=>".gif"];
      $type= mime_content_type($_FILES["icon"]["tmp_name"]);
        
      //拡張子を決める
      foreach($types as $key=>$val){
        if($type == $key){
          $filename = $_SESSION["u_id"].$val;
          $_SESSION["icon"]=$filename;
          //アップロードされたファイルを移動させる
          move_uploaded_file($_FILES["icon"]["tmp_name"],"../icon/".$filename);	
          break;//一つ見つけたら処理終わり
        }
      }
      $sql=$sql1.$sql2.$sql3;
    }else{
      $sql=$sql1.$sql3;
    }
    //echo "SQL=".$sql;

		$stmt=$pdo->prepare($sql);
		$stmt->bindValue(":u_id",$_SESSION["u_id"],PDO::PARAM_STR);
    $stmt->bindValue(":nickname",$_POST["nickname"],PDO::PARAM_STR);
    $stmt->bindValue(":description",$_POST["description"],PDO::PARAM_STR);
    $stmt->bindValue(":location",$_POST["location"],PDO::PARAM_STR);

    if($_FILES["icon"]["error"]==0){
      $stmt->bindValue(":icon",$_SESSION["icon"],PDO::PARAM_STR);
      echo "------icon-------";
    }
    


    $stmt->execute();
    var_dump($stmt);

	header("Location:../index.php");
	exit("3");
?>