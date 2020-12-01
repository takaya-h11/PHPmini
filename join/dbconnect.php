<?php
try{
	$db = new PDO('mysql:dbname=takaaaaaa23_wp1;host=mysql8066.xserver.jp;
	charset=utf8','takaaaaaa23_wp1','03yq8qdry3');
} catch(PDOException $e){
	print('DB接続エラー:' . $e->getMessage());
}
?>