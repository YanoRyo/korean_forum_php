<?php 
require('dbconnect.php');
session_start();

if(empty($_REQUEST['id'])){
    header('Location:gourmet.php');
    exit();
}

$posts = $db->prepare('SELECT u.name,u.picture,p.* FROM users u,posts2 p WHERE u.id=p.member_id AND p.id=?');
$posts->execute(array($_REQUEST['id']));
?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>ひとこと掲示板</title>

	<link rel="stylesheet" href="style.css" />
</head>

<body>
<div id="wrap">
  <div id="head">
    <h1>ひとこと掲示板</h1>
  </div>
  <div id="content">
  <p>&laquo;<a href="gourmet.php">一覧にもどる</a></p>

  <?php if ($post = $posts->fetch()): ?>
    <div class="msg">
    <img src="users_picture/<?php print(htmlspecialchars($post['picture'], ENT_QUOTES));?>" />
    <p><?php print(htmlspecialchars($post['message'], ENT_QUOTES)); ?><span class="name">（<?php print(htmlspecialchars($post['name'], ENT_QUOTES)); ?>）</span></p>
    <p class="day"><?php print(htmlspecialchars($post['created'], ENT_QUOTES)); ?></p>
    </div>
  <?php else:?>
    <p>その投稿は削除されたか、URLが間違えています</p>
  <?php endif;?>
  </div>
</div>
</body>
</html>
