<?php
session_start();
if(!isset($_SESSION['isLog'])){
    header("Location: ../index.php");
}else{
    echo 'logged with :'.$_SESSION['login'];
}
?>