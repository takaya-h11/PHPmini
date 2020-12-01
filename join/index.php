<?php 
session_start();
require('dbconnect.php');


if(!empty($_POST)){
	if($_POST['name'] === ''){  
		$error['name'] = 'blank'; //'blankで一度受けて、下で表示させる' SESSIONを使うためここに記載
	}
	if($_POST['email'] === ''){  
		$error['email'] = 'blank'; 
	}
	if(strlen($_POST['password']) <4){
		$error['password'] = 'length';  //パスワードに文字数制限　strlen文字を数える「 
	}
	if($_POST['password'] === ''){  
		$error['password'] = 'blank'; 
	}
	//アカウントの重複チェック//
	if(empty($error)){
		$member = $db->prepare('SELECT COUNT(*) AS cnt
		FROM members WHERE email=?');
		$member->execute(array($_POST['email']));
		$record = $member->fetch();
		if ($record['cnt'] >0){
			$error['email'] = 'duplicate';
		}
	}



	if(empty($error)){
	$_SESSION['join']= $_POST;  //エラーがない時にSEIIONする
	header('Location: check.php');
	exit();
	}
	
}   



if($_REQUEST['action'] =='rewrite' && isset($_SESSION['join'])){
	$_POST = $_SESSION['join'];
}
?>


<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<script src="https://kit.fontawesome.com/b77477b1b4.js" crossorigin="anonymous"></script>
	<title>会員登録</title>

	<link rel="stylesheet" href="login.css"/>
</head>
<body>
<div class="wrap">
	<div class="top-image"></div>	
	<div class="head">

		<div class="intro-title">ひとことづぶやきCAFE</div>
		<div class="intro-title">ようこそ<i class="fa fa-apple" aria-hidden="true"></i></div>			
	</div>
</div>			
<div class="content">
	<div class="middle-content">
		<p class="form">次のフォームに必要事項をご記入ください。</p>
		<form action="" method="post" enctype="multipart/form-data">
			<dl>
				<dt>名前<span class="required1">※必須</span></dt>
				<dd>
					<input type="text" name="name" size="70" maxlength="50" 
					value="<?php print(htmlspecialchars($_POST['name'],ENT_QUOTES));
					//value属性に入力した内容を表示?> "/>
					<?php if($error['name'] === 'blank'): ?> 
					<p class="error">*名前を入力してください</p>
					<?php endif;?>	
				</dd>
				<dt>メールアドレス<span class="required">※必須</span></dt>
				<dd>
					<input type="text" name="email" size="70" maxlength="50" 
					value="<?php print(htmlspecialchars($_POST['email'],ENT_QUOTES));?>" />
					<?php if($error['email'] === 'blank'): ?> 
					<p class="error">*メールアドレスを入力してください</p>
					<?php endif;?>	
					<?php if ($error['email'] === 'duplicate'): ?>
					<p class="error">*指定されたメールアドレスはすでに登録されています</p>
				<?php endif; ?>		
				<dt>パスワード<span class="required2">※必須(4文字以上でお願いします。)</span></dt>
				<dd>
					<input type="password" name="password" size="70"  maxlength="20" 
					value="<?php print(htmlspecialchars($_POST['password'],ENT_QUOTES));?>" />
					<?php if($error['password'] === 'length'): ?> 
					<p class="error">*パスワードは４文字以上にしてください</p>
					<?php endif;?>				
					<?php if($error['password'] === 'blank'): ?> 
					<p class="error">*パスワードを入力してください</p>
					<?php endif;?>	
				</dd>
			</dl>
			<div class="bottom">
				<input class="btn" type="submit" value="入力内容を確認する" />
			</div>
			<div class="link">※会員登録済みの方は<a class="page" href="../login.php">こちら</a></div>
		</form>
	</div>	
</div>
	<footer>
		<div class="footer">
			Copyright© taka's portfolio All Rights Reserved.
		</div>	
	</footer>
</body>
</html>
