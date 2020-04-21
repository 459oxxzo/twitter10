<!DOCTYPE>
<html lang="ja">
<head>
	<meta charset="utf-8">
	<title>登録フォーム</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>
	<div id="container">
	<h2>ユーザー登録</h2>
	<form action="make_user.php" method="post">
		<table>
		<tr><th><label for="u_id">ユーザ名（必須）</label></th>
			<td><input type="text" id="u_id" name="u_id"></td></tr>
			
		<tr><th><label for="nickname">ニックネーム（必須）</label></th>
			<td><input type="text" id="nickname" name="nickname"></td></tr>
				
		<tr><th><label for="pass">パスワード（必須）</label></th>
			<td><input type="password" id="pass" name="pass"></td></tr>
		</table>
		<p><button type="submit">登録</button></p>
	</form>
</body>
</html>