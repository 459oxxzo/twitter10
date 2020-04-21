<?php
	//ログイン処理の前に、それまでのログインデータを消しておく。
	//またはログアウト
	
	session_start();
	//セッションを削除するためにはセッションにアクセスできないといけないので
	//セッションのID番号だけはPCに保管されている。それを使うとセッションハイジャックが可能。
	//セッションの番号は明示的に書き換えないとずっとそのまま→危険。
	//いまの処理はクッキーの保存期間を決めてないので固定化され続けている
	//サーバ側のセッションデータ（買い物かごの中身など）を初期化
	$login_msg="";
	if(!empty($_SESSION["login_fail"])){
		$login_msg=$_SESSION["login_fail"];
	}
	
	$_SESSION=[];
	//配列を空にする
	
	setcookie(session_name(),'',time()-60);
	//クッキー名(session_nameで調べることができる),書き込まれている値（空にすると消える）,
	//第三引数＝賞味期限。過去を指定すれば、その瞬間に削除される
	
	session_destroy();
	//最後にサーバー側のセッションデータを削除
	//サーバを操作することはできないが、サーバとのつながりやサーバ側データの再利用は終わらせられる
	//この時点では通信が切れただけ。セッションとしては終わっているが、データ自体はサーバにまだある。
	//セッション系のデータは大事故につながりかねないので、しっかり破片も残さず消すこと
?>

<!DOCTYPE>
<html lang="ja">
<head>
	<meta charset="utf-8">
	<title>ログインフォーム</title>
	<link rel="stylesheet" href="css/login.css">
	<link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
	<script src="../jquery-3.4.1.min.js"></script>
</head>
<body>
	<main>
	<div id="hero">
		<table id="left">
			<tr><td><i class="fas fa-search"></i></td><td>あなたの「好き」をフォローしましょう</td></tr>
			<tr><td><i class="fas fa-user-friends"></i</td><td>話題のトピックを追いかけましょう。</td></tr>
			<tr><td><i class="far fa-comment"></i></td><td>会話に参加しましょう。</td></tr>
		</table>
	</div>
	<div id="container">
		
	<div class="right_wrappar">
		<p class="logo"><i class="fab fa-twitter"></i></p>
		<h2>「いま」起きていることを見つけよう</h2>
		<p>Twitterをはじめよう</p>
		
	</div>
	
			<table id="login_form">
				<tr>
					<th colspan="2"><button id="register_button">ユーザー登録</button></th>
				</tr>
			<form action="api/login_auth.php" method="post">
				<tr>
					<th colspan="2"><button type="submit" id="login_button">ログイン</button></th>
				</tr>
				<tr>
					<th colspan="2"><p><?php echo $login_msg; ?></p></th>
				</tr>
					
				<tr>
					<th><label for="u_id">ユーザ名</label></th>
					<th><label for="pass">パスワード</label></th>
					<!--<th rowspan="2"><button type="submit" >ログイン</button></th>-->
				</tr>
				<tr>
					<td><input type="text" id="u_id" name="u_id"></td>
					<td><input type="password" id="pass" name="pass"></td>
					<td></td>
				</tr>
			</form>
				<tr>
					<td></td>
					<td>パスワードをお忘れですか？</td>
					<td></td>
				</tr>
			</table>
		
	
</div>

<!--
  ◆ モーダルウインドウ 
-->
<div class="modal_on"><div>
	<div id="register_form">
    <button class="modal_off">✖</button>
		<h1>ユーザー登録</h1>
		<form action="api/register_user.php" method="post">
			<table>
			<tr><td></td><td class="msg_td" id="u_msg">英数３文字以上</td><tr>
			<tr><th><label for="rg_u_id">ユーザ名（必須）</label></th>
				<td><input type="text" id="rg_u_id" name="u_id"></td></tr>
			
			<tr><th><label for="rg_mail">メールアドレス（必須）</label></th>
				<td><input type="text" id="rg_mail" name="mail" disabled></td></tr>
			<tr><td></td><td class="msg_td" id="p_msg">英数３文字以上</td><tr>		
			<tr><th><label for="rg_pass">パスワード（必須）</label></th>
				<td><input type="password" id="rg_pass" name="pass"></td></tr>
			</table>
			<p><button type="submit" id="rg_btn" disabled>登録</button></p>
		</form>
		<div>
			<input type="checkbox" name="clear" id="u_clear">
			<input type="checkbox" name="clear" id="p_clear">
		</div>
  </div>
</div>
</main>

<footer>
<a href="404.php">Twitterについて</a>
<a href="404.php">ヘルプセンター</a>
<a href="404.php">利用規約</a>
<a href="404.php">プライバシーポリシー</a>
<a href="404.php">Cookie</a>
<a href="404.php">広告情報</a>
<a href="404.php">ブログ</a>
<a href="404.php">ステータス</a>
<a href="404.php">求人</a>
<a href="404.php">ブランド</a>
<a href="404.php">広告</a>
<a href="404.php">マーケティング</a>
<a href="404.php">ビジネス</a>
<a href="404.php">開発者</a>
<a href="404.php">プロフィール一覧</a>
<a href="404.php">設定</a>
<div>
	© 2020 Twitter, Inc.
</div>
</footer>


<script>
$(function() {
	$("#register_button").on("click",function(){
		$(".modal_on").css({'display':'block'});
	});
	//閉じる時にすべての値を初期化しておく
	$('.modal_off').on('click',function(){
		$(".modal_on").css({'display':'none'});
		$(".modal_top").css({'display':'none'});
		$("#rg_u_id").val("");
		$("#rg_pass").val("");
		$('#u_msg').text("英数３文字以上");
		$('#u_clear').prop('checked',false);
		$('#p_msg').text("英数３文字以上");
		$('#p_clear').prop('checked',false);
		able_checker();
	});

	//ユーザーネームが正しいか既存のものでないか検証
	//正しい未使用の名前なら登録可能に
	$("#rg_u_id").keyup(function(){
		var num = $(this).val().length;
		var str = $(this).val();
		//不正な文字が使われていないか※不正な文字です

		//文字数が３文字以上か
		if(num>2){
			$.ajax({
				url:"api/check_existing.php",
				data:{'u_id':str},
				type:"get",
				dataType:"json",
			}).done(function(data){

				if(data['r']==1){
					$('#u_clear').prop('checked',true);
					$('#u_msg').text("利用可能な名前です");
					able_checker(); 
				}else{
					$('#u_msg').text("既存の名前です");
					$('#u_clear').prop('checked',false);
					able_checker();
				}
			}).fail(function(){
				console.log("NG");
			});
		}else{
				$('#u_msg').text("英数３文字以上");
				$('#u_clear').prop('checked',false);
				able_checker();
		}
	});

	//パスワードが３文字以上か
	$("#rg_pass").keyup(function(){
		var num = $(this).val().length;
		var str = $(this).val();
	
		if(num>2){
				$('#p_clear').prop('checked',true);
				$('#p_msg').text("利用可能なパスワードです");
				able_checker();
		}else{
				$('#p_msg').text("英数３文字以上");
				$('#p_clear').prop('checked',false);
				able_checker();
		}
	});

	//JQからの値変更ではイベントが発火しないようなので
	//登録ボタンの有効化は関数化して呼び出す形にする
	function able_checker(){
		if($('#u_clear').prop('checked') && $('#p_clear').prop('checked'))
		{
			$('#rg_btn').prop('disabled', false);
		}else{
			$('#rg_btn').prop('disabled', true);
		}  
	}

});

</script>
</body>
</html>