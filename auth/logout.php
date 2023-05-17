<?php
    session_start();
    unset($_SESSION['user']);
    header('Location: /book-list/auth/login.php');
?>