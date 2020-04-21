<?php
  session_start();
  require_once("tool/config.php");
// var_dump($_SESSION);

$sql="SELECT u.u_id,d.nickname,description,location,icon FROM users u,user_details d WHERE u.u_id = d.u_id  AND u.u_id = :u_id";
$stmt=$pdo->prepare($sql);
$stmt->bindValue(":u_id",$_SESSION["u_id"],PDO::PARAM_STR);
$stmt->execute();
$row=$stmt->fetch(PDO::FETCH_ASSOC);//連想配列化
?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="utf-8">
	<title>ユーザー情報編集</title>
  <link rel="stylesheet" href="css/style.css">
  <script src="../jquery-3.4.1.min.js"></script>
</head>
<body>
	<div id="container">
	<h2>ユーザー情報編集</h2>
	<!-- <?php var_dump($_SESSION); ?> -->
	<form action="api/make_profile.php" method="post" enctype="multipart/form-data">
		<table>
		<tr><th>ユーザID</th>
			<td><?php echo $_SESSION["u_id"]; ?></td></tr>
			
		<tr><th><label for="nickname">ニックネーム</label></th>
			<td><input type="text" id="nickname" name="nickname" value="<?php echo $row["nickname"]; ?>"></td></tr>
		
		<tr><th><label for="descripion">プロフィール</label></th>
			<td><textarea rows="5" cols="22" id="description" name="description"><?php echo $row["description"]; ?></textarea></td></tr>
		
    <tr><th><label for="location">住所</label></th>
			<td><input type="text" id="location" name="location" value="<?php echo $row["location"]; ?>"></td></tr>

    <tr><th><label for="icon">アイコン</label><img src="icon/<?php echo $row["icon"]?>" style="height:50px"></th>
			<td><input type="file" id="icon" name="icon"><div id="preview"><img src="" ></div></td></tr>
		</table>
		<p><button type="submit" id="submit_btn" disabled>登録</button></p>
	</form>
  <script>

$(function() {
  //$('input[type=file]').after('<span></span>');
	//nicknameの必須化
	if($("#nickname").val().length>0){
		$('#submit_btn').prop('disabled', false);
	}
	$("#nickname").keyup(function(){
		var num = $(this).val().length;
		var str = $(this).val();
		console.log(num);
		if(num>0){
			$('#submit_btn').prop('disabled', false);
		}else{
			$('#submit_btn').prop('disabled', true);
		}
	});

  // アップロードするファイルを選択
  $('input[type=file]').change(function() {
    var file = $(this).prop('files')[0];
    // $('span').html('ファイル名:' + file.name + ' / 種類:' + file.type);
		// console.log(file);
		if(file.size > 500000){
				$('#preview').text("ファイルが大きすぎます");
		}else{
			var reader = new FileReader();
			reader.readAsDataURL(file);
			reader.onload = function() {
				$('#preview').children('img').attr('src', reader.result );
			}
		}

  });
});

	
</script>
</body>
</html>

