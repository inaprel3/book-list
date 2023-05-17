<?php
    include(__DIR__ . "/../auth/check-auth.php");

    require_once '../model/autorun.php';
    $myModel = Model\Data::makeModel(Model\Data::FILE);
    $myModel->setCurrentUser($_SESSION['user']);
    if(!$user = $myModel->readUser($_GET['username'])) {
        die($myModel->getError());
    }

    if ($_POST) {
        //формуємо рядок прав доступу
        $rights = "";
        for($i=0; $i<9; $i++) {
            if($_POST['right' . $i]) {
                $rights .= "1";
            } else {
                $rights .= "0";
            }
        }
        
    $user = (new \Model\User())
        ->setUserName($_POST['user_name'])
        ->setPassword($_POST['pwd'])
        ->setRights($rights);
        if (!$myModel->writeUser($user)) {
            die ($myModel->getError());
        } else {
            header('Location: index.php');
        }
    }

    require_once '../view/autorun.php';
    $myView = \View\AuthorListView::makeView(\View\AuthorListView::SIMPLEVIEW);
    $myView->showUserEditForm($user);
?>