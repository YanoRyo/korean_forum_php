<?php
require('dbconnect.php');
session_start();
if(isset($_SESSION['id']) && $_SESSION['time'] + 3600 >time()){
    $_SESSION['time'] = time();
    $users = $db->prepare('SELECT * FROM users WHERE id=?');
    $users->execute(array($_SESSION['id']));
    $users = $users->fetch();
}else{
    header('registration.php');
    exit();
}
if(!empty($_POST)){
    if($_POST['message']!==''){
        $message = $db->prepare('INSERT INTO posts4 SET member_id = ?,message=?,reply_message_id=?,created=NOW()');
        $message->execute(array($users['id'],$_POST['message'],$_POST['reply_message_id']));
        header('Location:trend.php');
        exit();
    }
}
$page = $_REQUEST['page'];
if($page == ''){
    $page = 1;
}
$page = max($page,1);
$counts = $db->query('SELECT COUNT(*) AS cnt FROM posts4');
$cnt = $counts->fetch();
$maxPage = ceil($cnt['cnt'] / 5);
$page = min($page,$maxPage);

$start = ($page - 1) * 5;
$posts = $db->prepare('SELECT u.name,u.picture,p.* FROM users u,posts4 p WHERE u.id=p.member_id ORDER BY u.created DESC LIMIT ?,5');
$posts->bindParam(1,$start,PDO::PARAM_INT);
$posts->execute();

if(isset($_REQUEST['res'])){
    // 返信の処理
    $response = $db->prepare('SELECT u.name,u.picture,p.* FROM users u ,posts4 p WHERE u.id=p.member_id AND p.id=?');
    $response->execute(array($_REQUEST['res']));
    
    $table = $response->fetch();
    $message = '@'.$table['name'].' '.$table['message'].'＜';
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"
        integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap-theme.min.css"
        integrity="sha384-6pzBo3FDv/PJ8r2KRkGHifhEocL+1X2rVCTTkUfGk7/0pbek5mMa1upzvWbrUbOZ" crossorigin="anonymous"> 
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js" integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/atraction.css">
    <title>トレンド</title>
</head>
<body>
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="containar">
            <header>
                <div class="row">
                    <div class="col-sm-8 col-sm-offset-2">
                        <h1>Imagine your Korea</h1>
                    </div>
                    <div class="clo-sm-1 align-left">
                        <a href="login.php"><span class="glyphicon glyphicon-user" aria-hidden="true"></span>ログイン</a>
                        <a href="logout.php"><span class="glyphicon glyphicon-user" aria-hidden="true"></span>ログアウト</a>
                    </div>
    
                </div>
            </header>
        </div>
    </nav>
    <div class="top">
        <img src="image/korea-96646_1920.jpg"  alt="サイトへようそこ">
        <p>トレンド</p>
    </div>
    <div id="wrap">
        <div id="content">
          <form action="" method="post">
            <dl>
              <dt><?php print(htmlspecialchars($users['name'],ENT_QUOTES));?>   さん、メッセージをどうぞ</dt>
              <dd>
                <textarea class="form-control" name="message" cols="195" rows="5"><?php print(htmlspecialchars($message,ENT_QUOTES));?></textarea>
                <input type="hidden" name="reply_message_id" value="<?php print(htmlspecialchars($_REQUEST['res'],ENT_QUOTES));?>" />
              </dd>
            </dl>
            <div>
              <p>
                <input type="submit" value="投稿する" />
              </p>
            </div>
          </form>
        <?php foreach($posts as $post):?>
          <div class="msg">
          <img src="users_picture/<?php print(htmlspecialchars($post['picture'],ENT_QUOTES));?>" width="48" height="48" alt="<?php print(htmlspecialchars($post['name'],ENT_QUOTES));?>"/>
          <p><?php print(htmlspecialchars($post['message'],ENT_QUOTES));?><span class="name">（<?php print(htmlspecialchars($post['name'],ENT_QUOTES));?>）</span>[<a href="trend.php?res=<?php print(htmlspecialchars($post['id'],ENT_QUOTES));?>">返信</a>]</p>
          <p class="day"><a href="view4.php?id=<?php print(htmlspecialchars($post['id']));?>"><?php print(htmlspecialchars($post['created'],ENT_QUOTES));?></a>
          <?php if($post['reply_message_id'] > 0):?>
            <a href="view4.php?id=<?php print(htmlspecialchars($post['reply_message_id'],ENT_QUOTES));?>">
            返信元のメッセージ</a>
          <?php endif; ?>
          <?php if($_SESSION['id'] == $post['member_id']):?>
            [<a href="delete4.php?id=<?php print(htmlspecialchars($post['id']));?>"
            style="color: #F33;">削除</a>]
          <?php endif;?>
          </p>
          </div>
        <?php endforeach;?>
      <ul class="paging">
      <?php if($page > 1):?>
        <li><a href="trend.php?page=<?php print($page-1);?>">前のページへ</a></li>
      <?php endif;?>
      <?php if($page < $maxPage):?>
        <li><a href="trend.php?page=<?php print($page+1);?>">次のページへ</a></li>
      <?php endif;?>
      </ul>
        </div>
      </div>
</body>
</html>