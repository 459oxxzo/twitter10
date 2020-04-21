<?php
	session_start();
	//セッション（ログイン情報）を持っていない人間がアクセスできないように
	if(empty($_SESSION["login"])){
		//セッションが存在し、かつlogin==true以外は
		header("Location:../login.php");
		//ログインページに追い出す
		exit("1");
	}
	if(empty($_POST["content"])){	
		//ツイートが空なら元ページに戻す
		header("Location:../index.php");
		exit("2");
	}
		/*
		ツイートのデータベースへの保管
		*/
		require_once("../tool/config.php");
		// $rplt = "";//ここで定義（値がないかもしれないので入れられない）
		if(!empty($_POST["reply_to"])){
				$rplt = $_POST["reply_to"];
				$sql="INSERT INTO tweets(u_id,content,reply_to) VALUES(:u_id,:content,$rplt)";
		}else{
				$sql="INSERT INTO tweets(u_id,content) VALUES(:u_id,:content)";
		}
		
		$stmt=$pdo->prepare($sql);
		$stmt->bindValue(":u_id",$_SESSION["u_id"],PDO::PARAM_STR);
		$stmt->bindValue(":content",$_POST["content"],PDO::PARAM_STR);
		$stmt->execute();

	//ツイートへのリプライ総数１加算
	if(!empty($_POST["reply_to"])){
		$sql_rp="UPDATE tweets SET replies= (SELECT replies FROM (SELECT replies FROM tweets WHERE t_id =$rplt) AS a)+1 WHERE t_id = $rplt";
		$st_rp = $pdo->query($sql_rp);
	}

	//ユーザーのツイート総数１加算
	$sql_cnt="UPDATE user_details SET tcount = (SELECT tcount FROM (SELECT tcount FROM user_details WHERE u_id ='".$_SESSION["u_id"]."') AS c)+1 WHERE u_id = '".$_SESSION["u_id"]."'";
	$st_cnt = $pdo->query($sql_cnt);

	/*
	送られてきた画像の保存
	*/	
	if(!empty($_FILES["img"])){

		if($_FILES["img"]["error"]!=0){
			header("Location:../index.php");
			exit();
		}

		$types=["image/jpeg"=>".jpg","image/png"=>".png","image/gif"=>".gif"];
		$type= mime_content_type($_FILES["img"]["tmp_name"]);
			
		//拡張子を決める
		$m_source="";
		foreach($types as $key=>$val){
			if($type == $key){
				$m_source = md5($_FILES["img"]["tmp_name"].time()).$val;$filename = "../entities/imgs/".$m_source;
				//md5($_FILES["img"]["tmp_name"].time()).$val;
				$_SESSION["filename"]=$filename;

				//アップロードされたファイルを移動させる
				move_uploaded_file($_FILES["img"]["tmp_name"],$filename);	
				break;
				//一つ見つけたら処理終わり
			}
			//if
		}
		//foreach
		//ツイートした本人の最新投稿（つまりさっきの）のt_id取る
		//画像の情報をツイートと紐づけて保存
		$sql2="INSERT INTO entities(t_id,m_type,m_source)";
		$sql2.=" VALUES((SELECT t_id FROM tweets WHERE u_id ='";
		$sql2.=$_SESSION["u_id"]."' ORDER BY t_id DESC LIMIT 1),";
		$sql2.="'img','".$m_source."')";
		
		$stmt2 = $pdo->query($sql2);
	}
	header("Location:../index.php");
	exit("3");
?>