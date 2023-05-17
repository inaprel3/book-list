<?php
    namespace Controller; 
    
    use Model\Data;
    use View\AuthorListView;

    class AuthorListApp {
        private $model;
        private $view;

        public function __construct($modelType, $viewType) {
            session_start();
            $this->model = Data::makeModel($modelType);
            $this->view = AuthorListView::makeView($viewType);
        }

        public function checkAuth() {
            if ($_SESSION['user']) {
                $this->model->setCurrentUser($_SESSION['user']);
                $this->view->setCurrentUser($this->model->getCurrentUser());
            } else {
                header('Location: ?action=login');
            }
        }
        
        public function run() {
            if(!in_array($_GET['action'], array('login','checkLogin'))) {
                $this->checkAuth();
            }
            if ($_GET['action']) {
                switch ($_GET['action']) {
                    case 'login':
                        $this->showLoginForm();
                        break;
                    case 'checkLogin':
                        $this->checkLogin();
                        break;
                    case 'logout':
                        $this->logout();
                        break;
                    case 'create-author':
                        $this->createAuthor();
                        break;
                    case 'edit-author-form':
                        $this->showEditAuthorForm();
                        break;
                    case 'edit-author':
                        $this->editAuthor();
                        break;
                    case 'delete-author':
                        $this->deleteAuthor();
                        break;
                    case 'create-book-form':
                        $this->showCreateBookForm();
                        break;
                    case 'create-book':
                        $this->createBook();
                        break;
                    case 'edit-book-form':
                        $this->showEditBookForm();
                        break;
                    case 'edit-book':
                        $this->editBook();
                        break;
                    case 'delete-book':
                        $this->deleteBook();
                        break;
                    case 'admin':
                        $this->adminUsers();
                        break;
                    case 'edit-user-form':
                        $this->showEditUserForm();
                        break;
                    case 'edit-user':
                        $this->editUser();
                        break;
                    default:
                        $this->showMainForm();
                }
            } else {
                $this->showMainForm();
            }
        }

        private function showLoginForm() {
            $this->view->showLoginForm();
        }
        private function checkLogin() {
            if ($user=$this->model->readUser($_POST['username'])) {
                if ($user->checkPassWord($_POST['password'])) { 
                    session_start();
                    $_SESSION['user'] = $user->getUserName();
                    header('Location: index.php');
                }
            }
        }
        private function logout() {
            unset($_SESSION['user']);
            header('Location: ?action=login');
        }
        private function showMainForm() {
            $authors = array();
            if($this->model->checkRight('author','view')) {
                $authors=$this->model->readAuthors();
            }
            $author = new \Model\Author();
            if($_GET['author'] && $this->model->checkRight('author', 'view')) {
                $author=$this->model->readAuthor($_GET['author']);
            }
            $books = array();
            if($_GET['author'] && $this->model->checkRight('book', 'view')) {
                $books=$this->model->readBooks($_GET['author']);
            }
            $this->view->showMainForm($authors, $author, $books);
        }
        private function createAuthor() {
            if(!$this->model->addAuthor()) {
                die($this->model->getError());
            } else {
                header('Location: index.php');
            }
        } 
        private function showEditAuthorForm() {
            if(!$author=$this->model->readAuthor($_GET['author'])) {
                die($this->model->getError());
            }
            $this->view->showAuthorEditForm($author);
        }
        private function editAuthor() {
            if (!$this->model->writeAuthor((new \Model\Author())
                ->setId(trim($_GET['author']))
                ->setAuthorName(trim($_POST['authorName']))
                ->setAuthorYear(trim($_POST['authorYear']))
                ->setAuthorCountry(trim($_POST['authorCountry']))
            )) {
                die ($this->model->getError());
            } else {
                header('Location: index.php?author=' . trim($_GET['author']));
            }
        }
        private function deleteAuthor() {
            if (!$this->model->removeAuthor($_GET['author'])) {
                die ($this->model->getError());
            } else {
                header('Location: index.php');
            }
        }
        private function showEditBookForm() {
            $book = $this->model->readBook($_GET['author'], $_GET['file']);
            $this->view->showBookEditForm($book);
        }
        private function editBook() {
            $book = (new \Model\Book())
              ->setId($_GET['file'])
              ->setAuthorId($_GET['id_auth'])//author
              ->setBookTitle($_POST['bookTitle'])
              ->setBookDatePublish(new \DateTime($_POST['bookDatePublish']))//setDatePublish, date_publish
              ->setReadStatus($_POST['readStatus'])//setRead, read
              ->setGenreFantasy();//setGenreCyberpank
            if ($_POST['bookGenre'] == 'кіберпанк') {//genre
                $book->setIsGenreCyberpank();//setGenreCyberpank
            }
            if (!$this->model->writeBook($book)) {
                die ($this->model->getError());
            } else {
                header('Location: index.php?author=' . $_GET['author']);
            }
        }
        private function showCreateBookForm() {
            $this->view->showBookCreateForm(); 
        }
        private function createBook() {
            $book = (new \Model\Book())
            ->setAuthorId($_GET['author'])
            ->setBookTitle($_POST['bookTitle'])
            ->setBookDatePublish(new \DateTime($_POST['bookDatePublish']))
            ->setReadStatus($_POST['readStatus'])
            ->setIsGenreFantasy();
            if ($_POST['bookGenre'] == 'кіберпанк') {
                $book->setIsGenreCyberpank();
            }
            if (!$this->model->addBook($book)) {
                die ($this->model->getError());
            } else {
                    header('Location: index.php?author=' . $_GET['author']);
            }
        }
        private function deleteBook() {
            $book = (new \Model\Book())->setId($_GET['file'])->setAuthorId($_GET['id_auth']);//author
            if(!$this->model->removeBook($book)) {
                die($this->model->getError());
            } else {
                header('Location: index.php?author=' . $_GET['author']);
            }
        }
        private function adminUsers() {
            $users = $this->model->readUsers();
            $this->view->showAdminForm($users);
        }
        private function showEditUserForm() {
            if(!$user=$this->model->readUser($_GET['username'])) {
                die($this->model->getError());
            }
            $this->view->showUserEditForm($user);
        }
        private function editUser() {
            $rights="";
            for($i=0; $i<9; $i++) {
                if ($_POST['right' . $i]) {
                    $rights .= "1";
                } else {
                    $rights .= "0";
                }
            }
            $user=(new \Model\User())
            ->setUserName($_POST['user_name'])
            ->setPassword($_POST['pwd'])//? user_pwd
            ->setRights($rights);
            if(!$this->model->writeUser($user)) {
                die($this->model->getError());
            } else {
                header('Location: ?action=admin ');
            }
        }
    }
?>