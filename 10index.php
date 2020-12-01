<?php
session_start();
require('join/dbconnect.php');

if(isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()){
  $_SESSION['time'] = time();

  $members = $db->prepare('SELECT * FROM members WHERE id=?');
  $members->execute(array($_SESSION['id']));
  $member = $members->fetch();
} else{
header('Location: login.php');
exit();
}

if(!empty($_POST)){
  if($_POST['message'] !== ''){
    $message = $db->prepare('INSERT INTO posts SET member_id=?,
    message=?, created=NOW()');
    $message->execute(array(
    $member['id'],
    $_POST['message']
    ));

    header('Location: 10index.php');//更新を押しても重複登録しない
    exit();
  }
}
$page = $_REQUEST['page'];
if($page ==''){
  $page = 1;
}
$page = max($page,1);



$counts = $db->query('SELECT COUNT(*) AS cnt FROM posts');
$cnt = $counts->fetch();
$maxPage = ceil($cnt['cnt'] / 5);
$page = min($page, $maxPage);

$start = ($page - 1) * 5;


$posts = $db->prepare('SELECT m.name,  p. * FROM members
m, posts p WHERE m.id=p.member_id ORDER BY p.created DESC LIMIT ?,5');
$posts->bindParam(1, $start, PDO::PARAM_INT);
$posts->execute();

?>
<!doctype html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>オリジナル掲示板</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <!-- fontawesome CSS -->
		<script src="https://kit.fontawesome.com/b77477b1b4.js" crossorigin="anonymous"></script>
		<!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
		<link rel="stylesheet" type="text/CSS" href="original.css" media="all">
	</head>
	<body>
    <header>
        <h1>Takaya Hattori</h1>
        <ul class="side">
            <li><a href="logout.php">ログアウト</a></li>
        </ul>
    </header>
    <div class="top-image"></div>
    <div class="container">
        <div class="main">
            <div class="intro-title">ひとことづぶやきCAFE</div>
            <div class="intro">                
                <div class="intro-p">何でも良いので日々思ったこと、考えていることを、
                    ゆる～くつぶやくための掲示板<br><i class="fa fa-twitter-square" aria-hidden="true"></i>
                </div>
            </div>
            <div class="twit">
                <div class="tw">
                <?php print(htmlspecialchars($member['name'], ENT_QUOTES));?>さん  つぶやきを投稿する
                </div>
                <form action="" method="post">
                    <div class="">
                        <textarea name="message" cols="100" rows="8" maxlength="400"
                            placeholder="140字以内で入力してください">
                        </textarea>
                    </div>
                    <input type="submit" value="つぶやく">
                </form>
                <?php foreach($posts as $post): ?>
                <div class="msg">               
                        <span class="name">名前：<?php print(htmlspecialchars($post['name'], ENT_QUOTES)); ?></span></p>
                        <p><?php print(htmlspecialchars($post['message'], ENT_QUOTES)); ?>
                        <p class=""><?php print(htmlspecialchars($post['created'], ENT_QUOTES)); ?></a>
                                <a href="delete.php?id=<?php print(htmlspecialchars($post['id']));?>"style="color: #F33;">[削除]</a>
                        </p>  
                </div>
                <?php endforeach; ?> 
             </div>    
        </div>
        <ul class="paging">
            <?php if($page > 1): ?>
            <li><a class="link" href="10index.php?page=<?php print($page-1);?>">前のページへ</a></li>
            <?php else: ?>
            <?php endif; ?>
            <?php if($page < $maxPage): ?>
            <li><a class="link" href="10index.php?page=<?php print($page+1); ?>">次のページへ</a></li>
            <?php else: ?>        
            <?php endif; ?>
        </ul>
    </div>
	<footer>
		<div class="footer">
			Copyright© taka's portfolio All Rights Reserved.
		</div>	
	</footer>
	
    </body>
</html>

		