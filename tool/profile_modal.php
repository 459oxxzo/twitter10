<?php

$pro_sql="SELECT u.u_id,d.nickname,description,location,icon FROM users u,user_details d WHERE u.u_id = d.u_id  AND u.u_id = :u_id";
$pro_stmt=$pdo->prepare($pro_sql);
$pro_stmt->bindValue(":u_id",$_SESSION["u_id"],PDO::PARAM_STR);
$pro_stmt->execute();
$pro_row=$pro_stmt->fetch(PDO::FETCH_ASSOC);//連想配列化
?>

<div id="profile_modal_on">
  <!-- ここから投稿フォーム -->
  <div id="profile_form">
    <button id="profile_modal_off">✖</button><h3>ユーザー情報編集</h3>

    <section class="profile_modal_container">  

  <form action="api/make_profile.php" method="post" enctype="multipart/form-data">
  <p><button type="submit" id="submit_btn" disabled>登録</button></p>
		<table id="profile_table">
		<tr><th>ユーザID</th>
			<td><?php echo $_SESSION["u_id"]; ?></td></tr>
			
		<tr><th><label for="nickname">ニックネーム</label></th>
			<td><input type="text" id="nickname" name="nickname" value="<?php echo $pro_row["nickname"]; ?>"></td></tr>
		
		<tr><th><label for="descripion">プロフィール</label></th>
			<td><textarea rows="6" cols="18" id="description" name="description"><?php echo $pro_row["description"]; ?></textarea></td></tr>
		
    <tr><th><label for="location">住所</label></th>
			<td><input type="text" id="location" name="location" value="<?php echo $pro_row["location"]; ?>"></td></tr>

    <tr><th><label for="icon">アイコン</label><img src="icon/<?php echo $pro_row["icon"]?>" style="height:50px"></th>
			<td><input type="file" id="icon" name="icon"><div id="preview"><img src="" ></div></td></tr>
		</table>
		
	</form>


    </section>

  </div>
  <!-- ここまで -->
</div>
<!-- モーダルエンド -->