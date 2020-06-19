<?php 
session_start();
require('dbconnect.php');

if(isset($_SESSION['id'])){
    $id = $_REQUEST['id'];

    $messages = $db->prepare('SELECT * FROM posts2 WHERE id=?');
    $messages->execute(array($id));
    $message = $messages->fetch();

    if($message['member_id']==$_SESSION['id']){
        $del = $db->prepare('DELETE FROM posts2 WHERE id=?');
        $del->execute(array($id));
    }
}
header('Location:gourmet.php');
exit();
?>