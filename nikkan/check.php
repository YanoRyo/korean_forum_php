<?php
session_start();
require('dbconnect.php');
if(!isset($_SESSION['user'])){
    header('Location:registration.php');
    exit();
}
if(!empty($_POST)){
    $statement = $db->prepare('INSERT INTO users SET name=?,password=?,nationality=?,picture=?,created=NOW()');
    echo $statement->execute(array($_SESSION['user']['name'],sha1($_SESSION['user']['password']),$_SESSION['user']['nationality'],$_SESSION['user']['image']));
    header('Location:homepage.html');
    unset($_SESSION['user']);
    exit();

}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap-theme.min.css" integrity="sha384-6pzBo3FDv/PJ8r2KRkGHifhEocL+1X2rVCTTkUfGk7/0pbek5mMa1upzvWbrUbOZ" crossorigin="anonymous">

<link rel="stylesheet" href="css/style.css">
<title>登録画面</title>
</head>
<body>
    <div class="container">
        <header>
            <h1>会員登録</h1>
        </header>
    </div>
    <hr>
    <div class="container">
        <form action="" method="POST" class="row">
            <input type="hidden" name="action" value="submit" />
            <div class="col-sm-8 col-sm-offset-2">
                <p>恐れ入りますが、以下のフォームにご確認いただき、登録していただけると幸いです。</p>
                <div class="form-group">
                    <label for="name">ニックネーム</label>
                    <dd><?php print(htmlspecialchars($_SESSION['user']['name'],ENT_QUOTES));?></dd>
                </div>
                <div class="form-group">
                    <label for="password">パスワード</span></label>
                    <dd>[表示されません]</dd>
                  </div>
                <div class="form-group">
                    <label>国籍</label>
                    <dd><?php print(htmlspecialchars($_SESSION['user']['nationality'],ENT_QUOTES));?></dd>
                </div>
                <div class="form-group">
                    <label for="InputFile">プロフィール写真</label>
                    <dd>
		            <?php if($_SESSION['join']['image'] != ''): ?>
			            <img src = "users_picture/<?php print(htmlspecialchars($_SESSION['user']['image'],ENT_QUOTES));?>">
		            <?php endif;?>
		            </dd>
                </div>
                <button type="submit" class="btn btn-default">登録する</button>
            </div>
        </form>
    </div>
</body>
</html>