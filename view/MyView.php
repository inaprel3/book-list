<?php
    namespace View;

    class MyView extends AuthorListView {
        private function showAuthors($authors) {
            ?>
            <form name='author-form' method='get'>
                <label for="author">Автор</label>
                <select name="author">
                    <option value=""></option>
                    <?php
                    foreach ($authors as $curauthor) {
                        echo "<option " . (($curauthor->getId() == $_GET['author']) ? "selected":"") . "value='" . $curauthor->getId() . "''>" . $curauthor->getAuthorName() . "</option>";
                    }?>
                </select>
                <input type="submit" value="Перейти">
                <?php if($this->CheckRight('author', 'create')):?>
				    <a href="?action=create-author">Додати автора</a>
			<?php endif; ?>
		</form>
        <?php
        }

        private function showAuthor(\Model\Author $author) {
            ?>
            <h3>ПІБ:<span class='author-name'>
			    <?php echo trim($author->getAuthorName()); ?></span> 
		    </h3>
		    <h3>Рік народження:<span class='author-year'>
			    <?php echo trim($author->getAuthorYear()); ?></span> 
		    </h3>
		    <h3>Країна:<span class='author-country'>
			    <?php echo trim($author->getAuthorCountry()); ?></span> 
		    </h3>
		    <div class='control' style="margin-top: 3%">
		        <?php if($this->CheckRight('author', 'edit')):?>
			        <a href="?action=edit-author-form&author=
                        <?php echo trim($_GET['author']); ?>">Редагувати автора</a>
			    <?php endif; ?>
			    <?php if($this->CheckRight('author', 'delete')):?> 
			        <a href="?action=delete-author&author=
				        <?php echo $_GET['author']; ?>">Видалити автора</a>
			    <?php endif; ?>
		    </div>
            <?php
        }

        private function showBooks($books) {
            ?>
            <section>
                <?php if($_GET['author']): ?>
                <?php if($this->CheckRight('book', 'create')):?> 
		            <div class='control' style="margin-top: 3%">
			            <a href="?action=create-book-form&author=
				            <?php echo trim($_GET['author']); ?>">Додати книгу</a>
		            </div>
		        <?php endif; ?>
                <form name='books-filter' method='post' style="margin-top: 3%">
			        Фільтрувати за назвою <input type="text" name="bookTitleFilter" value='
				        <?php echo strip_tags(trim($_POST['bookTitleFilter']) ?? '');?>'>
			        <input type="submit" value="Фільтрувати">
		        </form>

                <table>
			        <thead>
				        <tr>Книги:
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
				        <?php $row_class = 'row';
					        if ($book->isGenreCyberpank) {
						        $row_class = 'cyberpunk';
					        }
					        if ($book->isGenreFantasy) { 
						        $row_class = 'fantasy';
					        }
				        ?>

				        <tr class = '<?php echo $row_class; ?>'>
					        <td><?php echo ($key + 1); ?></td>
					        <td><?php echo trim($book->getBookTitle()); ?></td> 
					        <td><?php echo trim($book->isGenreCyberpank())?'кіберпанк':'фентезі'; ?></td>
					        <td><?php echo date_format($book->getDatePublish(), 'Y'); ?></td>
					        <td>
						        <?php if($this->CheckRight('book', 'edit')):?> 
						        <a href='?action=edit-book-form&author=
                                    <?php echo trim($_GET['author']); ?>&file=<?php echo $book->getId();?>'>Редагувати</a>
						        <?php endif; ?>
						        |
						        <?php if($this->CheckRight('book', 'delete')):?> 
						        <a href='?action=delete-book&author=
                                    <?php echo trim($_GET['author']) ?>&file=<?php echo $book->getId();?>'>Видалити</a>
						        <?php endif; ?>
					        </td>
				        </tr>  
				    <?php endif; ?>
				    <?php endforeach; ?>
                    <?php endif; ?>
			        </tbody>
		        </table>
                <?php endif; ?>
            </section>
            <?php
        }
        public function showMainForm($authors, \Model\Author $author, $books) {
            ?>
            <!DOCTYPE html>
            <html>
            <head>
	            <title>Автор</title>
	            <link rel="stylesheet" type="text/css" href="css/main-style.css">
	            <link rel="stylesheet" type="text/css" href="css/genre-style.css">
	            <link rel="stylesheet" type="text/css" href="css/author-choose-style.css">
            </head>
            <body>
                <header>
		            <div class="user-info">
			            <span>Hello <?php echo $_SESSION['user']; ?> !</span>
			            <?php if ($this->CheckRight('user','admin')): ?> 
				            <a href="?action=admin">Адміністрування</a>
				        <?php endif; ?>
			            <a href="?action=logout">Logout</a>
		            </div>
		            <?php if ($this->CheckRight('author', 'view')) {
                        $this->showAuthors($authors);
                        if ($_GET['author']) {
                            $this->showAuthor($author);
                        }
                    }
                    ?> 
	            </header>
                <?php
                    if($this->checkRight('book', 'view')) {
                        $this->showBooks($books);
                    }
                ?>
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
	            <link rel="stylesheet" href="css/edit-author-form-style.css">
            </head>
            <body>
	            <a href="index.php?author=<?php echo trim($_GET['author']);?>">На головну</a>
	            <form name='edit-author' method='post' action="?action=edit-author&author=
                    <?php echo $_GET['author'];?>">
		            <div>
                        <label for='authorName'>ПІБ:</label><input type="text" name="authorName" value="
				            <?php echo trim($author->getAuthorName()); ?>">
                    </div>
		            <div>
			            <label for='authorYear'>Рік народження:</label><input type="text" name="authorYear" value="
				            <?php echo trim($author->getAuthorYear()); ?>">
		            </div>
		            <div>
			            <label for='authorCountry'>Країна:</label><input type="text" name="authorCountry" value="
				            <?php echo trim($author->getAuthorCountry()); ?>">
		            </div>
		            <div><input type="submit" name="ok" style="margin-top: 20%; height:50px; width:150px" value="Змінити"></div>
	            </form>
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
                <link rel="stylesheet" type="text/css" href='css/edit-book-form-style.css'>
            </head>
            <body>
                <a href="index.php?author=<?php echo $_GET['author'];?>">На головну</a>
                <form name='edit-book' method='post' action="?action=edit-book&file=
                    <?php echo $_GET['file'];?>&author<?php echo $_GET['author'];?>">
                <div>
                    <label for='bookTitle'>Назва книги:</label>
                    <input type="text" name="bookTitle" value="
                        <?php echo trim($book->getBookTitle()); ?>">
                </div>
                <div>
                    <label for='date_publish'>Дата публікації:</label>
                    <input type="date" name="date_publish" value="
                        <?php echo $book->getBookDatePublish()->format('d.m.Y'); ?>">
                </div>
                <div>
                    <label for='genre'>Жанр:</label>
                    <select name="genre">
                        <option disabled>Жанр</option>
                        <option <?php echo (trim($book->isGenreCyberpank()))?"selected":""; ?> 
                            value="кіберпанк">кіберпанк
                        </option>
                        <option <?php echo (trim($book->isGenreFantasy()))?"selected":""; ?> 
                            value="фантастика">фантастика
                        </option>
                    </select>
                </div>
                <div>
                    <input type="checkbox" <?php echo ($book->isReadStatus())?"checked":""; ?> 
                        name="read" value=1> Прочитана
                </div>
                <div><input type="submit" name="ok" style="margin-top: 20%; height:50px; width:150px" value="Змінити"></div>
                </form>
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
	            <link rel="stylesheet" type="text/css" href='css/edit-book-form-style.css'>
            </head>
            <body>
	            <a href="?author=<?php echo $_GET['author'];?>">На головну</a>
	            <form name='edit-book' method='post' action="?action=create-book&author=
                    <?php echo $_GET['author'];?>">
		        <div>
	                <label for='bookTitle'>Назва книги:</label>
	                <input type="text" name="bookTitle">
	            </div>
	            <div>
	                <label for='date_publish'>Дата публікації:</label>
	                <input type="date" name="date_publish">
	            </div>
	            <div>
	                <label for='genre'>Жанр книги:</label>
	                <select name="genre">
	                    <option disabled>Жанр</option>
	                    <option value="кіберпанк">кіберпанк</option>
	                    <option value="фантастика">фантастика</option>
	                    <option value="інше">інше</option>
	                </select>
	            </div>
	            <div>
	                <input type="checkbox" name="read" value=1> Прочитана
	            </div>
	            <div><input type="submit" name="ok" value="Додати"></div>
	            </form>
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
                <link rel="stylesheet" type="text/css" href="css/login-style.css">
            </head>
            <body>
                <form method="post" action="?action=checkLogin">
                    <p><input align="center" type="text" name="username" placeholder="username"></p>
                    <p><input type="password" name="password" placeholder="password"></p>
                    <p><input type="submit" value="login"></p>
                </form>
            </body>
            </html>
            <?php
        }

        public function showAdminForm($users) {
            ?>
            <!DOCTYPE html>
            <html>
            <head>
                <title>Адміністрування</title>
            </head>
            <body>
            <header>
                <a href="index.php">На головну</a>
                <h1>Адміністрування користувачів</h1>
                <link rel="stylesheet" type="text/css" href="css/main-style.css">
            </header>
            <section>
                <table>
                    <thead>
                    <tr>
                        <th>Користувач</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php foreach($users as $user):?>
                            <?php if($user->getUserName() != $_SESSION['user'] && $user->getUserName() != 'admin' && trim($user->getUserName()) != '' ): ?>
                            <tr>
                                <td><a href="?action=edit-user-form&username=
                                    <?php echo $user->getUserName();?>">
                                    <?php echo $user->getUserName();?>
                                </td>
                            </tr>
                        <?php endif ?> 
                        <?php endforeach;?>
                    </tbody>
                </table>
            </section>
            </body>
            </html>
            <?php
        }

        public function showUserEditForm(\Model\User $user) {
            ?>
            <!DOCTYPE html>
            <html>
            <head>
                <title>Редагування користувача</title>
                <link rel="stylesheet" type="text/css" href="admin.css">
            </head>
            <body>
                <a href="?action=admin">До списку користувачів</a>
                <form name='edit-user' method='post' action="?action=edit-user&user=
                    <?php echo $_GET['user'];?>">
                    <div class='tbl'>
                        <div>
                            <label for='user_name'>Username:</label>
                            <input readonly type="text" name="user_name" value='<?php echo $user->getUserName(); ?>'>
                        </div>
                        <div>
                            <label for='user_pwd'>Password:</label>
                            <input type="text" name="user_pwd" value='<?php echo $user->getPassword(); ?>'>
                        </div>
                    </div>
                    <div><p>Автор:</p>
                        <input type="checkbox" <?php echo ("1" == $user->getRight(0))?"checked":""; ?> 
                            name="right0" value="1"><span>перегляд</span>
                        <input type="checkbox" <?php echo ("1" == $user->getRight(1))?"checked":""; ?> 
                            name="right1" value="1"><span>створення</span>
                        <input type="checkbox" <?php echo ("1" == $user->getRight(2))?"checked":""; ?> 
                            name="right2" value="1"><span>редагування</span>
                        <input type="checkbox" <?php echo ("1" == $user->getRight(3))?"checked":""; ?> 
                            name="right3" value="1"><span>видалення</span>
                    </div>
                    <div><p>Книга:</p>
                        <input type="checkbox" <?php echo ("1" == $user->getRight(4))?"checked":""; ?> 
                            name="right4" value="1"><span>перегляд</span>
                        <input type="checkbox" <?php echo ("1" == $user->getRight(5))?"checked":""; ?> 
                            name="right5" value="1"><span>створення</span>
                        <input type="checkbox" <?php echo ("1" == $user->getRight(6))?"checked":""; ?> 
                            name="right6" value="1"><span>редагування</span>
                        <input type="checkbox" <?php echo ("1" == $user->getRight(7))?"checked":""; ?> 
                            name="right7" value="1"><span>видалення</span>
                    </div>
                    <div><p>Користувачі:</p>
                        <input type="checkbox" <?php echo ("1" == $user->getRight(8))?"checked":""; ?> 
                            name="right8" value="1"><span>адміністрування</span>
                    </div>
                    <div><input type="submit" name="ok" value="змінити"></div>
                </form>
            </body>
            </html>
            <?php
        }
    }
?>