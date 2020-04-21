<?php 


	//目的： 送信されてきた２つのIDから、それぞれのフォロー、フォロワーを抽出し、それらのフォロー関係を分類した結果を返す。

	//案１：API側で全部のデータが分類されセットされたjsonを作る
	
	//案２：JS側から４回条件を変えてapiを呼び出し、得られた結果を別のグルーバル変数に入れて最後に計算して処理する
	
	//案３：全部同一ページ内で処理
	
	//下は処理が意味不明なものになりそうだったため１を選択するも詰まって３に変更
	

//ちゃんと値が送られてきていれば
if(!empty($_GET["xid"]) || !empty($_GET["yid"])){
	// if(!empty($_GET["xid"]) && !empty($_GET["id"])){

//Xがフォローするユーザーを取得
	$xga="SELECT saki FROM follows WHERE moto = :u_id";
	$s_xga=$pdo->prepare($xga);
	$s_xga->bindValue(":u_id",$_GET["xid"],PDO::PARAM_STR);
	$s_xga->execute();
	$r_xga=$s_xga->fetchAll(PDO::FETCH_ASSOC);

	//重複・差分計算をする方法が配列しか見つからなかった(連想配列のままだと。キーと値両方が一致しないと一致とみなされない)ので、しかたなく結果を一旦配列に変換する
	$arr_xga=array();
	foreach($r_xga AS $key=>$val){
		array_push($arr_xga,$val["saki"]);
	}

	//Xをフォロー
	$xwo="SELECT moto FROM follows WHERE saki = :u_id";
	$s_xwo=$pdo->prepare($xwo);
	$s_xwo->bindValue(":u_id",$_GET["xid"],PDO::PARAM_STR);
	$s_xwo->execute();
	$r_xwo=$s_xwo->fetchAll(PDO::FETCH_ASSOC);

	//同上で配列化
	$arr_xwo=array();
	foreach($r_xwo AS $key=>$val){
		array_push($arr_xwo,$val["moto"]);
	}
	//重複を取る 相互フォロー
	$arr_x1= array_intersect($arr_xga,$arr_xwo);
	//差分を取る　Xがフォロー
	$arr_x2=array_diff($arr_xga,$arr_x1);
	//差分を取る　Xをフォロー
	$arr_x3=array_diff($arr_xwo,$arr_x1);

	$e = array_diff_assoc($arr_xga,$arr_x1);

	//Yがフォローするユーザーを取得
	$yga="SELECT saki FROM follows WHERE moto = :u_id";
	$s_yga=$pdo->prepare($yga);
	$s_yga->bindValue(":u_id",$_GET["yid"],PDO::PARAM_STR);
	$s_yga->execute();
	$r_yga=$s_yga->fetchAll(PDO::FETCH_ASSOC);
	$arr_yga=array();
	foreach($r_yga AS $key=>$val){
		array_push($arr_yga,$val["saki"]);
	}

	//Yをフォロー
	$ywo="SELECT moto FROM follows WHERE saki = :u_id";
	$s_ywo=$pdo->prepare($ywo);
	$s_ywo->bindValue(":u_id",$_GET["yid"],PDO::PARAM_STR);
	$s_ywo->execute();
	$r_ywo=$s_ywo->fetchAll(PDO::FETCH_ASSOC);
	
	$arr_ywo=array();
	foreach($r_ywo AS $key=>$val){
		array_push($arr_ywo,$val["moto"]);
	}

	//重複を取る Yと相互フォロー
	$arr_y1= array_intersect($arr_yga,$arr_ywo);
	//差分を取る　Yがフォロー
	$arr_y2=array_diff($arr_yga,$arr_y1);
	//差分を取る　Yをフォロー
	$arr_y3=array_diff($arr_ywo,$arr_y1);

	//それぞれの重複をarray_intersectで抜き出す
	//３＊３通り
	$arr_x1y1=array();
	$arr_x1y1= array_intersect($arr_x1,$arr_y1);
	$arr_x1y2=array();
	$arr_x1y2= array_intersect($arr_x1,$arr_y2);
	$arr_x1y3=array();
	$arr_x1y3= array_intersect($arr_x1,$arr_y3);
	$arr_x2y1=array();
	$arr_x2y1= array_intersect($arr_x2,$arr_y1);
	$arr_x2y2=array();
	$arr_x2y2= array_intersect($arr_x2,$arr_y2);
	$arr_x2y3=array();
	$arr_x2y3= array_intersect($arr_x2,$arr_y3);
	$arr_x3y1=array();
	$arr_x3y1= array_intersect($arr_x3,$arr_y1);
	$arr_x3y2=array();
	$arr_x3y2= array_intersect($arr_x3,$arr_y2);
	$arr_x3y3=array();
	$arr_x3y3= array_intersect($arr_x3,$arr_y3);

	// echo "arr_x1 ";
	// var_dump($arr_x1);
	// echo "<br/>"."arr_y1 ";
	// var_dump($arr_y1);
	// echo "<br/>"."arr_x1y1 ";
	// var_dump($arr_x1y1);
	// echo "<br/>"."arr_x1y2 ";
	// var_dump($arr_x1y2);
	// echo "<br/>"."arr_x1y3 ";
	// var_dump($arr_x1y3);
	// echo "<br/>"."arr_x2y1 ";
	// var_dump($arr_x2y1);
	// echo "<br/>"."arr_x3y1 ";
	// var_dump($arr_x3y1);


	//以上、９つの配列を
	//どうやって返せばいいのか！

	//下だと、一件だけの配列になっていた（上書き？）
	// $rs = array();
	// array_push($rs,$arr_x1y1);
	// array_push($rs,$arr_x1y2);
	// array_push($rs,$arr_x1y3);
	// array_push($rs,$arr_x2y1);
	// array_push($rs,$arr_x2y2);
	// array_push($rs,$arr_x2y3);
	// array_push($rs,$arr_x3y1);
	// array_push($rs,$arr_x3y2);
	// array_push($rs,$arr_x3y3);

	// $rs2 = array();
	// $rs2 = array_push($rs2,$rs);
	
/*
array(9) {
	[0]=> array(1) { [3]=> string(3) "fff" } 
	→4件あるはずなのに１つしか入っていない
		array(1) { [3]=> string(3) "fff" } array(1) { [4]=> string(3) "ggg" } array(1) { [2]=> string(3) "eee" } array(1) { [1]=> string(3) "ddd" }
		
		SELECT a.saki FROM (SELECT * FROM follows WHERE moto = 'aaa') a, (SELECT * FROM follows WHERE saki = 'aaa') b WHERE a.saki= b.moto
		でもこの４件が得られる

	[1]=> array(1) { [4]=> string(3) "ggg" } 
	[2]=> array(1) { [2]=> string(3) "eee" } 
	[3]=> array(1) { [1]=> string(3) "ddd" } 
	[4]=> array(0) { } 
	[5]=> array(0) { } 
	[6]=> array(0) { } 
	[7]=> array(0) { } 
	[8]=> array(1) { [1]=> string(3) "ccc" } } 






	*/
// }
}
	// $json = json_encode($rs);
	// echo $json;
	
?>