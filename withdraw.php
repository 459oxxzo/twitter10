<?php
session_start();

if(empty($_SESSION["login"])){
  header("Location:login.php");
  exit();
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="utf-8">
	<title>退会処理</title>
	<script src="../jquery-3.4.1.min.js"></script>
</head>
<body>
  <form action="api/remove_account.php" method="post" id="f1">
    <p>退会するアカウントのパスワードを入力してください</p>
    <p>
      <input type="password" name="pass" id="pass">
      <button type="submit">退会する</button>
    </p>
    <p id="msg">
      <?php 
        if(!empty($_SESSION["fail_auth"])){
          echo $_SESSION["fail_auth"];
        }
      ?></p>
  </form>
	
	<script>
		$(function(){
			$('#f1').on('submit',function(e){
        	var rs = window.confirm('アカウントを消去します。この処理は取り消すことができません');
          if(rs){
            console.log("ok");
          }else{
            e.preventDefault();
            window.alert('退会処理を中止しました'); 
        	}
			});
		});
		
	</script>
</body>
</html>
