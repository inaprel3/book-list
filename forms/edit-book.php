<?php
  include(__DIR__ . "/../auth/check-auth.php");

  require_once '../model/autorun.php';
  $myModel = Model\Data::makeModel(Model\Data::FILE);
  $myModel->setCurrentUser($_SESSION['user']);

  if ($_POST) {
    $book = (new \Model\Book())
      ->setId($_GET['file'])
      ->setAuthorId($_GET['id_auth'])//author
      ->setBookTitle($_POST['bookTitle'])
      ->setBookDatePublish(new DateTime($_POST['bookDatePublish']))//setDatePublish, date_publish
			->setReadStatus($_POST['readStatus'])//setRead, read
      ->setIsGenreFantasy();//setGenreFantasy
		if ($_POST['bookGenre'] == 'кіберпанк') {//genre
			$book->setIsGenreCyberpank();//setGenreCyberpank
		}
		if (!$myModel->writeBook($book)) {
			die ($myModel->getError());
		} else {
			header('Location: ../index.php?author=' . $_GET['author']);
		}
  }
  $book = $myModel->readBook($_GET['author'], $_GET['file']);

  require_once '../view/autorun.php';
  $myView = \View\AuthorListView::makeView(\View\AuthorListView::SIMPLEVIEW);
  $myView->setCurrentUser($myModel->getCurrentUser());
  $myView->showBookEditForm($book);
?>