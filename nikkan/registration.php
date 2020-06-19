<?php

session_start();
require('dbconnect.php');
// 入力画面に空白がないかを調べる
if (!empty($_POST)){
    if ($_POST['name'] === ''){
        $error['name'] ='blank';
    }
    if ($_POST['password'] === ''){
        $error['password'] ='blank';
    }
    if (strlen($_POST['password']) < 4){
		$error['password'] = 'length';
    }
    if (!isset($_POST['nationality'])){
        $error['nationality'] = 'blank';
    }
    // アカウントの重複チェック
    // if (empty($error)){
    //     $user1 = $db->prepare('SELECT COUNT (*) as cnt FROM users WHERE name=?');
    //     $user1->execute(array($_POST['name']));
    //     $record1 = $user1->fetch();
    //     if ($record1['cnt'] > 0){
    //         $error['name'] = 'duplicate';
    //     }
    // }
    // if (empty($error)){
    //     $user2 = $db->prepare('SELECT COUNT (*) as cnt FROM users WHERE password=?');
    //     $user2->execute(array($_POST['password']));
    //     $record2 = $user2->fetch();
    //     if ($record2['cnt'] > 0){
    //         $error['password'] = 'duplicate';
    //     }
    // }
    $fileName = $_FILES['image']['name'];
	if (!empty($fileName)){
		$ext = substr($fileName,-3);
		if ($ext != 'jpg' && $ext != 'gif' && $ext != 'png'){
			$error['image'] = 'type';
		}
    }
    
    // エラーがなかったらセッションにポストのデータを入れる
    if(empty($error)){
        $image = date('YmdHis'). $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'],'users_picture/'.$image);
        $_SESSION['user'] = $_POST;
        $_SESSION['user']['image'] = $image;
        header('Location: check.php');
        exit();
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
        <form action="" method="POST" class="row" enctype="multipart/form-data">
            <div class="col-sm-8 col-sm-offset-2">
                <p>この度は本サイトをご利用いただきありがとうございます。恐れ入りますが、
                     以下のフォームにご記入いただき、送信していただけると幸いです。</p>
                <div class="form-group">
                    <label for="name">ニックネーム<span class="label label-danger">必須</span></label>
                    <input type="text" id="name" name="name" class="form-control" placeholder="例)山田太郎" value="<?php print(htmlspecialchars($_POST['name'],ENT_QUOTES));?>"/>
                    <?php if ($error['name']==='blank'):?>
                    <p class="error">*ニックネームをを入力してください</p>
                    <?php endif;?>
                </div>
                <div class="form-group">
                    <label for="password">パスワード<span class="label label-danger">必須</span></label>
                    <input type="password" name="password" class="form-control" id="password" placeholder="Password" value="<?php print(htmlspecialchars($_POST['password'],ENT_QUOTES));?>"/>
                    <?php if ($error['password']==='blank'):?>
                    <p class="error">*パスワードをを入力してください</p>
                    <?php endif;?>
                    <?php if ($error['password'] === 'length'):?>
			        <p class="error">*パスワードを4文字以上入力してください</p>
			        <?php endif;?>
                  </div>
                <div class="form-group">
                    <label>国籍<span class="label label-danger">必須</span></label>
                    <div>
                        <label class="radio">
                            <input type="radio" name="nationality" value="日本人"<?php if(!empty($_POST['nationality']) && $_POST['nationality']==="日本人"){echo'checked';} ?>/>日本
                        </label>
                        <label class="radio">
                            <input type="radio" name="nationality" value="韓国人"<?php if(!empty($_POST['nationality']) && $_POST['nationality']==="韓国人"){echo'checked';} ?>/>韓国
                        </label>
                        <?php if($error['nationality'] ==='blank'):?>
                        <p class="error">*国籍を入力してください</p>
                        <?php endif;?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="InputFile">プロフィール写真<span class="label label-success">任意</span></label>
                    <input type="file" name='image' id="InputFile" size="35" value="test">
                    <?php if ($error['image'] === 'type'):?>
			        <p class="error">*写真を「.jpg]または「.gif」または[.png」または[.jpeg」で入力してください</p>
			        <?php endif;?>
			        
                </div>
                <button type="submit" class="btn btn-default">送信する</button>
            </div>
        </form>
    </div>
</body>
</html>