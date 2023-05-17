<?php
	include(__DIR__ . "/../auth/check-auth.php");

	require_once '../model/autorun.php';
	$myModel = Model\Data::makeModel(Model\Data::FILE);
	$myModel->setCurrentUser($_SESSION['user']);

	if ($_POST) {
		if (!$myModel->writeAuthor((new \Model\Author())
			->setId(trim($_GET['author']))
			->setAuthorName(trim($_GET['authorName']))
			->setAuthorYear(trim($_GET['authorYear']))
			->setAuthorCountry(trim($_GET['authorCountry']))
		)) {
			die ($myModel->getError());
		} else {
			header('Location: ../index.php?author=' . trim($_GET['author']));
		}
	}
	if (!$author = $myModel->readAuthor(trim($_GET['author']))) { 
		die ($myModel->getError());
	}

	require_once '../view/autorun.php';
	$myView = \View\AuthorListView::makeView(\View\AuthorListView::SIMPLEVIEW);
	$myView->setCurrentUser($myModel->getCurrentUser());
	$myView->showAuthorEditForm($author);
?>