<?php 
session_start();
require('dbconnect.php');

if(isset($_SESSION['id'])){
    $id = $_REQUEST['id'];

    $messages = $db->prepare('SELECT * FROM posts3 WHERE id=?');
    $messages->execute(array($id));
    $message = $messages->fetch();

    if($message['member_id']==$_SESSION['id']){
        $del = $db->prepare('DELETE FROM posts3 WHERE id=?');
        $del->execute(array($id));
    }
}
header('Location:shopping.php');
exit();
?>