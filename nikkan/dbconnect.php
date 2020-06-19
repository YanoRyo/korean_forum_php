<?php 
try{
    $db = new PDO('mysql:dbname=nikkan_db;host=localhost;charset=utf8','root','root');
}catch(PDOException $e){
    echo '接続エラー:'. $e->getMessage();
}
?>