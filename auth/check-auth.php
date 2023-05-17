<?php
    session_start();
    if(!$_SESSION['user']) {
        header('Location: /book-list/auth/login.php');
    }
?>