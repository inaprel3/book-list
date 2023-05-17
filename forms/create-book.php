<?php
	include(__DIR__ . "/../auth/check-auth.php");

	if ($_POST) {
		require_once '../model/autorun.php';
		$myModel = Model\Data::makeModel(Model\Data::FILE);
		$myModel->setCurrentUser($_SESSION['user']);

		$book = (new \Model\Book())
			->setAuthorId($_GET['id_auth'])//author
			->setBookTitle($_POST['bookTitle'])
			->setBookDatePublish(new DateTime($_POST['bookDatePublish']))//setDatePublish, date_publish
			->setReadStatus($_POST['readStatus'])//readStatus, read
			->setIsGenreFantasy();//setGenreFantasy
		if ($_POST['bookGenre'] == 'кіберпанк') { //genre
			$book->setIsGenreCyberpank();//setGenreCyberpank
		}
		if (!$myModel->addBook($book)) {
			die ($myModel->getError());
		} else {
			header('Location: ../index.php?author=' . $_GET['author']);
		}
	}

	require_once '../view/autorun.php';
	$myView = \View\AuthorListView::makeView(\View\AuthorListView::SIMPLEVIEW);
	$myView->showBookCreateForm();
?>