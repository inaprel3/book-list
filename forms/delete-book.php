<?php
	include(__DIR__ . "/../auth/check-auth.php");

	require_once '../model/autorun.php';
	$myModel = Model\Data::makeModel(Model\Data::FILE);
	$myModel->setCurrentUser($_SESSION['user']);

	$book = (new \Model\Book())->setId($_GET['file'])->setAuthorId($_GET['author']);
	if (!$myModel->removeBook($book)) {
		die($myModel->getError());
	} else {
		header('Location: ../index.php?author=' . $_GET['author']);
	}
?>