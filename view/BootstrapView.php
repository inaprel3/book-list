<?php
    namespace View;

    class BootstrapView extends AuthorListView {
        const ASSETS_FOLDER = 'view/bootstrap/';

        private function showUserInfo() {
            ?>
            <div class="container user-info">
                <div class="row">
                    <div class="col-md-12 col-md-offset-12 text-center lead">
                        <p><span style="color: white; font-size: 18pt; background: rgba(48, 48, 48, 0.74)">Hello <?php echo $_SESSION['user']; ?> !</span></p>
                        <?php if ($this->checkRight('user','admin')): ?>
                        <a class="btn btn-primary" href="?action=admin" style="font-size: 18pt">Адміністрування</a>
                        <?php endif; ?>
                        <a class="btn btn-info" href="?action=logout" style="font-size: 18pt">Logout</a>
                    </div>
                </div>
            </div>
        <?php
        }

        private function showAuthors($authors) {
            ?>
            <div class="container author-list">
                <div class="row">
                    <form name='author-form' method='get' class="col-xs-offset-2 col-xs-8 col-sm-offset-3 col-sm-6">
                        <?php if($this->CheckRight('author', 'create')):?>
                        <p><a style="font-size: 18pt" class='btn btn-success' href="?action=create-author">Додати автора</a></p>
                        <?php endif; ?>
                        <div class="form-author">
                            <label for="author" style="color: white; font-size: 18pt; background: rgba(48, 48, 48, 0.74)">Автор: </label>
                            <select style="font-size: 18pt" name="author" class="form-control" onchange="document.forms['author-form'].submit();">
                                <option value=""></option>
                                <?php
                                    foreach ($authors as $curauthor) {
                                        echo "<option " . (($curauthor->getId() == $_GET['author']) ? "selected":"") . "value='" . $curauthor->getId() . "''>" . $curauthor->getAuthorName() . "</option>";
                                }?>
                            </select>
                        </div>
		            </form>
                </div>
            </div>
        <?php
        }

        private function showAuthor(\Model\Author $author) {
            ?>
            <div class="container author-info">
                <div class="text-right">
                    <p><h3 style="text-shadow: black 1px 1px 0, black 2px 2px 0, 
	black 3px 3px 0, black 4px 4px 0, black 5px 5px 0" class="col-xs-12">ПІБ: <span class='text-primary'><?php echo trim($author->getAuthorName()); ?></span> 
		            </h3></p>
		            <h3 style="text-shadow: black 1px 1px 0, black 2px 2px 0, 
	black 3px 3px 0, black 4px 4px 0, black 5px 5px 0" class="col-xs-12">Рік народження: <span class='text-danger'><?php echo trim($author->getAuthorYear()); ?></span> 
		            </h3>
		            <p><h3 style="text-shadow: black 1px 1px 0, black 2px 2px 0, 
	black 3px 3px 0, black 4px 4px 0, black 5px 5px 0" class="col-xs-12">Країна: <span class='text-success'><?php echo trim($author->getAuthorCountry()); ?></span> 
		            </h3></p>
		            <div class='control col-xs-12'>
		                <?php if($this->CheckRight('author', 'edit')):?>
			                <a style="font-size: 18pt" class="btn btn-primary" href="?action=edit-author-form&author=<?php echo trim($_GET['author']); ?>">Редагувати автора</a>
			            <?php endif; ?>
			            <?php if($this->CheckRight('author', 'delete')):?> 
			                <a style="font-size: 18pt" class="btn btn-danger" href="?action=delete-author&author=<?php echo $_GET['author']; ?>">Видалити автора</a>
			            <?php endif; ?>
                    </div>
                </div>
		    </div>
        <?php
        }

        private function showBooks($books) {
            ?>
            <section class="container books">
                <div class="row text-center">
                    <?php if($_GET['author']): ?>
                    <?php if($this->CheckRight('book', 'create')):?> 
		            <div class="col-xs-12 col-md-2 col-md-offset-1 text-center add-book">
			            <p><a style="font-size: 18pt" class="btn btn-success" href="?action=create-book-form&author=<?php echo trim($_GET['author']); ?>">Додати книгу</a></p>
		            </div>
                </div>
		        <?php endif; ?><!--col-xs-offset-2 col-xs-8 col-sm-offset-3 col-sm-6-->
                <div class="text-left col-xs-offset-2 col-xs-8">
                    <form name='books-filter' method='post'>
                        <div class="col-xs-12">
			                <label style="color: white; font-size: 18pt; background: rgba(48, 48, 48, 0.74)" for ="bookTitleFilter">Фільтрувати за назвою:</label>
                            <input class="form-control" type="text" name="bookTitleFilter" value='<?php echo strip_tags(trim($_POST['bookTitleFilter']) ?? '');?>'>
			                <input type="submit" value="Фільтрувати">
                        </div>
		            </form>
                </div>
                <div class="row text-center table-books">
                    <table class="table col-xs-10">
			            <thead>
				            <tr style="font-size: 18pt; color: white; background: rgba(48, 48, 48, 0.74)"><label style="color: white; font-size: 18pt; background: rgba(48, 48, 48, 0.74)">Книги:</label>
					            <th>№</th>
					            <th>Назва:</th>
                                <th>Жанр:</th>
					            <th>Дата публікації:</th>
					            <th></th>
				            </tr>
			            </thead>
			            <tbody>
                            <?php if (count($books) > 0): ?>
                            <?php foreach ($books as $key => $book): ?>
				            <?php if(!trim($_POST['bookTitleFilter']) || stristr($book->getBookTitle(), trim($_POST['bookTitleFilter']))): ?>
				            <?php $row_class = '';
					        if ($book->isGenreCyberpank()) {
						        $row_class = 'bg-info';
					        }
					        if ($book->isGenreFantasy()) { 
						        $row_class = 'bg-danger';
					        }
				            ?>

				            <tr style="font-size: 18pt; color: white; background: rgba(48, 48, 48, 0.74)" class = '<?php echo $row_class; ?>'>
					            <td><?php echo ($key + 1); ?></td>
					            <td><?php echo trim($book->getBookTitle()); ?></td> 
					            <td><?php echo trim($book->isGenreCyberpank())?'кіберпанк':'фентезі'; ?></td>
					            <td>
                                    <?php echo date_format($book->getBookDatePublish(), 'd.m.Y'); ?><!--Y-->
                                </td>
					            <td>
						            <?php if($this->CheckRight('book', 'edit')):?> 
						                <a style="font-size: 18pt" class="btn btn-primary btn-xs" href='?action=edit-book-form&author=<?php echo trim($_GET['author']); ?>&file=<?php echo $book->getId();?>'>Редагувати</a>
						            <?php endif; ?>
						            <?php if($this->CheckRight('book', 'delete')):?> 
						                <a style="font-size: 18pt" class="btn btn-danger btn-xs" href='?action=delete-book&author=<?php echo trim($_GET['author']) ?>&file=<?php echo $book->getId();?>'>Видалити</a>
						            <?php endif; ?>
					            </td>
				            </tr>  
				        <?php endif; ?>
				        <?php endforeach; ?>
                        <?php endif; ?>
			            </tbody>
		            </table>
                    <?php endif; ?>
                </div>
            </section>
            <?php
        }

        public function showMainForm($authors, \Model\Author $author, $books) {
            ?>
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset="UTF-8">
                <link rel="stylesheet" type="text/css" href="<?php echo self::ASSETS_FOLDER; ?>css/bootstrap.css">
	            <link rel="stylesheet" type="text/css" href="<?php echo self::ASSETS_FOLDER; ?>css/bootstrap.min.css">
	            <link rel="stylesheet" type="text/css" href="<?php echo self::ASSETS_FOLDER; ?>css/main.css">
	            <script src="<?php echo self::ASSETS_FOLDER; ?>js/jquery.min.js"></script>
                <script src="<?php echo self::ASSETS_FOLDER; ?>js/bootstrap.min.js"></script>
                <title>Document</title>
            </head>
            <body>
                <header>
		            <?php $this->showUserInfo();?>
                    <?php
                        if ($this->checkRight('author', 'view')) {
                            $this->showAuthors($authors);
                            if ($_GET['author'])$this->showAuthor($author);
                    }?>
	            </header>
                <?php
                    if($this->checkRight('book', 'view')) {
                        $this->showBooks($books);
                }?>
            </body>
            </html>
            <?php
        }

        public function showAuthorEditForm(\Model\Author $author) {
            ?>
            <!DOCTYPE html>
            <html>
            <head>
	            <title>Редагування автора</title>
                <link rel="stylesheet" type="text/css" href="<?php echo self::ASSETS_FOLDER; ?>css/bootstrap.css">
	            <link rel="stylesheet" type="text/css" href="<?php echo self::ASSETS_FOLDER; ?>css/bootstrap.min.css">
                <script src="<?php echo self::ASSETS_FOLDER; ?>js/jquery.min.js"></script>
                <script src="<?php echo self::ASSETS_FOLDER; ?>js/bootstrap.min.js"></script>
            </head>
            <body>
            <div class="container">
                <div class="row">
	                <div class="col-xs-12 col-sm-8 col-md-6 col-lg-4">
	                    <form name='edit-author' method='post' style="font-size: 18pt; color: white" action="?action=edit-author&author=<?php echo $_GET['author'];?>">
                            <p><a class="btn btn-info btn-sm pull-center" style="font-size: 18pt" href="index.php?author=<?php echo $_GET['author'];?>">На головну</a>
		                    <div class="form-author"></p>
                                <label for='authorName'>ПІБ: </label>
                                    <p><input type="text" name="authorName" value="<?php echo $author->getAuthorName(); ?>"></p><!--class="form-control"-->
                            </div>
		                    <div class="form-author">
                                <label for='authorYear'>Рік народження: </label>
                                    <p><input type="text" name="authorYear" value="<?php echo $author->getAuthorYear(); ?>"></p>
		                    </div>
		                    <div class="form-author">
			                    <label for='authorCountry'>Країна: </label>
                                    <p><input type="text" name="authorCountry" value="<?php echo $author->getAuthorCountry(); ?>"></p>
		                    </div>
                            <p><button type="submit" class="btn btn-success pull-right" style="font-size: 18pt">Змінити</button></p>
	                    </form>
                    </div>
                </div>
            </div>
            </body>
            </html>
            <?php
        }

        public function showBookEditForm(\Model\Book $book) {
            ?>
            <!DOCTYPE html>
            <html>
            <head>
                <title>Редагування книги</title>
                <link rel="stylesheet" type="text/css" href="<?php echo self::ASSETS_FOLDER; ?>css/bootstrap.css">
                <link rel="stylesheet" type="text/css" href="<?php echo self::ASSETS_FOLDER; ?>css/checkbox.css">
                <script src="<?php echo self::ASSETS_FOLDER; ?>js/jquery.min.js"></script>
                <script src="<?php echo self::ASSETS_FOLDER; ?>js/bootstrap.min.js"></script>
                <link rel="stylesheet" type="text/css" href="<?php echo self::ASSETS_FOLDER; ?>css/bootstrap.min.css">
            </head>
            <body>
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12 col-sm-8 col-md-6 col-lg-4">
                            <form name='edit-book' method='post' style="color: white; font-size: 18pt" action="?action=edit-book&file=<?php echo $_GET['file'];?>&author<?php echo $_GET['author'];?>">
                                <p><a class="btn btn-info pull-center btn-sm" style="font-size: 18pt" href="index.php?author=<?php echo $_GET['author'];?>">На головну</a></p>
                                <div class="form-author">
                                    <label for='bookTitle' style="background: rgba(48, 48, 48, 0.74)">Назва книги: </label>
                                    <input class="form-control" type="text" name="bookTitle" value="<?php echo trim($book->getBookTitle()); ?>">
                                </div>
                                <div class="form-author">
                                    <label for='date_publish' style="background: rgba(48, 48, 48, 0.74)">Дата публікації: </label>
                                    <input class="form-control" type="date" name="date_publish" value="<?php echo $book->getBookDatePublish()->format('d.m.Y'); ?>">
                                </div>
                                <div class="form-author">
                                    <label for='genre' style="background: rgba(48, 48, 48, 0.74)">Жанр: </label>
                                    <select class="form-control" name="genre">
                                        <option disabled>Жанр</option>
                                        <option <?php echo (trim($book->isGenreCyberpank()))?"selected":""; ?> value="кіберпанк">кіберпанк</option>
                                        <option <?php echo (trim($book->isGenreFantasy()))?"selected":""; ?> value="фантастика">фантастика</option>
                                    </select>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" <?php echo ($book->isReadStatus())?"checked":""; ?> name="read" value=1 style="font-size: 18pt">Прочитана
                                    </label>
                                </div>
                                <p><button type="submit" class="btn btn-success pull-right" style="font-size: 18pt">Змінити</button></p>
                            </form>
                        </div>
                    </div>
                </div>
            </body>
            </html>
            <?php
        }

        public function showBookCreateForm() {
            ?>
            <!DOCTYPE html>
            <html>
            <head>
	            <title>Додавання книги</title>
                <link rel="stylesheet" type="text/css" href="<?php echo self::ASSETS_FOLDER; ?>css/bootstrap.css">
                <link rel="stylesheet" type="text/css" href="<?php echo self::ASSETS_FOLDER; ?>css/bootstrap.min.css">
                <link rel="stylesheet" type="text/css" href="<?php echo self::ASSETS_FOLDER; ?>css/checkbox.css">
                <script src="<?php echo self::ASSETS_FOLDER; ?>js/jquery.min.js"></script>
                <script src="<?php echo self::ASSETS_FOLDER; ?>js/bootstrap.min.js"></script>
            </head>
            <body>
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12 col-sm-8 col-md-6 col-lg-4">
                            <form name='edit-book' method='post' action="?action=create-book&author=<?php echo $_GET['author'];?>">
                                <a class="btn btn-info pull-center btn-sm" href="index.php?author=<?php echo $_GET['author'];?>">На головну</a>
		                        <div class="form-author">
	                                <label for='bookTitle'>Назва книги: </label>
	                                <input class="form-control" type="text" name="bookTitle">
	                            </div>
	                            <div class="form-author">
	                                <label for='date_publish'>Дата публікації: </label>
	                                <input class="form-control" type="date" name="date_publish">
	                            </div>
	                            <div class="form-author">
	                                <label for='genre'>Жанр книги: </label>
	                                <select class="form-control" name="genre">
	                                    <option disabled>Жанр</option>
	                                    <option value="кіберпанк">кіберпанк</option>
	                                    <option value="фантастика">фантастика</option>
	                                    <option value="інше">інше</option>
	                                </select>
	                            </div>
	                            <div class="checkbox">
                                    <label><input type="checkbox" name="read" value=1 style="font-size: 18pt">Прочитана</label>
	                            </div>
                                <button type="submit" class="btn btn-success pull-right" style="font-size: 18pt">Додати</button>
	                        </form>
                        </div>
                    </div>
                </div>
            </body>
            </html>
            <?php
        }

        public function showLoginForm() {
            ?>
            <!DOCTYPE html>
            <html>
            <head>
                <title>Аутентифікація</title>
                <link rel="stylesheet" type="text/css" href="<?php echo self::ASSETS_FOLDER; ?>css/bootstrap.css">
                <link rel="stylesheet" type="text/css" href="<?php echo self::ASSETS_FOLDER; ?>css/bootstrap.min.css">
                <link rel="stylesheet" type="text/css" href="<?php echo self::ASSETS_FOLDER; ?>css/login.css">
                <script src="<?php echo self::ASSETS_FOLDER; ?>js/jquery.min.js"></script>
                <script src="<?php echo self::ASSETS_FOLDER; ?>js/bootstrap.min.js"></script>
            </head>
            <body>
                <form method="post" action="?action=checkLogin">
                    <div class="container">
                        <div class="row text-center">
                            <div class="col-sm-6 col-md-4 col-lg-3 col-sm-offset-3 col-md-offset-4">
                                <div class="form-author">
                                    <p><input name="username" placeholder="username" class="form-control" style="font-size: 18pt"></p>
                                </div>
                                <div class="form-author">
                                    <p><input type="password" name="password" placeholder="password" class="form-control" style="font-size: 18pt"></p>
                                </div>
                                <p><button type="submit" class="btn btn-default" style="font-size: 18pt">Login</button></p>
                            </div>
                        </div>
                    </div>
                </form>
            </body>
            </html>
            <?php
        }

        public function showAdminForm($users) {
            ?>
            <head>
            <link rel="stylesheet" type="text/css" href="<?php echo self::ASSETS_FOLDER; ?>css/bootstrap.css">
            <link rel="stylesheet" type="text/css" href="<?php echo self::ASSETS_FOLDER; ?>css/bootstrap.min.css">
            <link rel="stylesheet" type="text/css" href="<?php echo self::ASSETS_FOLDER; ?>css/main.css">
            </head>
            <div class="container admin-form">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3 text-center lead">
                        <p><h1 style="color: white; font-size: 18pt; background: rgba(48, 48, 48, 0.74)">Адміністративна панель</h1></p>
                        <p><a style="font-size: 18pt; background: rgba(241, 241, 241, 0.66)" class="btn btn-primary" href="?action=admin-authors">Адміністрування авторів </a></p>
                        <p><a style="font-size: 18pt; background: rgba(241, 241, 241, 0.66)" class="btn btn-info" href="?action=logout">Вихід</a></p>
                    </div>
                </div>
            </div>
            <?php
        }
    
        public function showUserEditForm($user) { /**/
            ?>
            <head>
            <link rel="stylesheet" type="text/css" href="<?php echo self::ASSETS_FOLDER; ?>css/bootstrap.min.css">
            </head>
            <div class="container user-edit-form">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3 text-center lead">
                        <h2>Редагування користувача</h2>
                        <form method="post" action="?action=edit-user">
                            <input type="hidden" name="id" value="<?php echo $user->getId(); ?>">
                            <div class="form-group">
                                <label for="username">Ім'я користувача</label>
                                <input type="text" class="form-control" name="username" value="<?php echo $user->getUsername(); ?>">
                            </div>
                            <div class="form-group">
                                <label for="password">Пароль</label><!-- ? -->
                                <input type="password" class="form-control" name="password">
                            </div>
                            <div class="form-group">
                                <label for="role">Роль</label><!-- ? -->
                                <select class="form-control" name="role">
                                    <option value="user" <?php echo ($user->getRole() === 'user') ? 'selected' : ''; ?>>Користувач</option>
                                    <option value="admin" <?php echo ($user->getRole() === 'admin') ? 'selected' : ''; ?>>Адміністратор</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <input type="submit" class="btn btn-primary" value="Зберегти" style="font-size: 18pt">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php
        }
    }
?>