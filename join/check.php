<?php
session_start();
require('dbconnect.php');
//入力画面に正しくデータがない場合、初期画面に戻す
if(!isset($_SESSION['join'])){ 
	header('Location: 10index.php');
	exit();
	}

if(!empty($_POST)){
	$statement = $db->prepare('INSERT INTO members SET name=?, email=?, password=?, created=NOW()');
	echo $statement->execute(array(
		$_SESSION['join']['name'],
		$_SESSION['join']['email'],
		sha1($_SESSION['join']['password']),
	));
	unset($_SESSION['join']);//dbに重複しないように消去

	header('Location: thanks.php');
	exit();
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>会員登録</title>

	<link rel="stylesheet" href="check.css" />
</head>
<body>
<div id="wrap">
<div id="head">
<h1>会員登録</h1>
</div>

<div id="content">
	<p>記入した内容を確認して、「登録する」ボタンをクリックしてください</p>
	<form action="" method="post">
		<input type="hidden" name="action" value="submit" />	
		<div class="middle">	
			<div class="name-g">
				<p class="name-p">名前</p>		
				<div class="namebox">	
					<?php print(htmlspecialchars($_SESSION['join']['name'],ENT_QUOTES));?>  
				</div>
			</div>
			<div class="mail-g">		      
				<p class="mail-p">メールアドレス</p>
				<div class="mailbox">		
					<?php print(htmlspecialchars($_SESSION['join']['email'],ENT_QUOTES));?>     
				</div>
			</div>
			<div class="pass-g">
					<p class="pass-p">パスワード</p>
				<div class="passwordbox">
					【表示されません】
				</div>
			</div>
		</div>			
				<a class="last" href="index.php?action=rewrite">&laquo;&nbsp;書き直す</a> | <input type="submit" value="登録する" /></div>
	</form>
</div>
	<div class="wrapper">
		<footer>
			<div class="footer">
				Copyright© taka's portfolio All Rights Reserved.
			</div>	
		</footer>
	</div>
</body>
</html>
