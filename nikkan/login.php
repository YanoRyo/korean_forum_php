<?php
require('dbconnect.php');
session_start();

if(!empty($_POST)){
    if($_POST['name'] !=='' && $_POST['password'] !==''){
        $login = $db->prepare('SELECT * FROM users WHERE name=? AND password=?');
        $login->execute(array($_POST['name'],sha1($_POST['password'])));
        $users = $login->fetch();
        if($users){
            $_SESSION['id'] = $users['id'];
            $_SESSION['time'] = time();
            header('Location:homepage.html');
            exit();
        }else{
            $error['login'] = 'failed';
        }
    }else{
        $error['login'] = 'blank';
    }
    if (strlen($_POST['password']) < 4){
		$error['login'] = 'length';
    }
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
<title>ログイン画面</title>
</head>
<body>
    <div class="container">
        <header>
            <h1>ログイン画面</h1>
        </header>
    </div>
    <hr>
    <div class="container">
        <form action="" method="POST" class="row">
            <div class="col-sm-8 col-sm-offset-2">
                <p>ニックネームとパスワードを記入してを記入してログインしてくだささい。</p>
                <div class="form-group">
                    <label for="name">ニックネーム<span class="label label-danger">必須</span></label>
                    <input type="text" id="name" name="name" class="form-control" placeholder="例)山田太郎" value="<?php print(htmlspecialchars($_POST['name'],ENT_QUOTES));?>"/>
                    <?php if ($error['login']==='blank'):?>
                    <p class="error">*ニックネームをを入力してください</p>
                    <?php endif;?>
                    <?php if ($error['login']==='failed'):?>
                    <p class="error">*ログインに失敗しました</p>
                    <?php endif;?>
                </div>
                <div class="form-group">
                    <label for="password">パスワード<span class="label label-danger">必須</span></label>
                    <input type="password" name="password" class="form-control" id="password" placeholder="Password" value="<?php print(htmlspecialchars($_POST['password'],ENT_QUOTES));?>"/>
                    <?php if ($error['login']==='blank'):?>
                    <p class="error">*パスワードをを入力してください</p>
                    <?php endif;?>
                    <?php if ($error['login'] === 'length'):?>
			        <p class="error">*パスワードを4文字以上入力してください</p>
			        <?php endif;?>
                </div>
                <button type="submit" class="btn btn-default">ログインする</button>
            </div>
        </form>
    </div>
</body>
</html>