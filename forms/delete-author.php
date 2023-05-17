<?php
	include(__DIR__ . "/../auth/check-auth.php");

	require_once '../model/autorun.php';
	$myModel = Model\Data::makeModel(Model\Data::FILE);
	$myModel->setCurrentUser($_SESSION['user']);

	if (!$myModel->removeAuthor($_GET['author'])) {
		die($myModel->getError());
	} else {
		header('Location: ../index.php');
	}
?>