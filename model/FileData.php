<?php
    namespace Model;

    class FileData extends Data { 
        const DATA_PATH = __DIR__ . '/../data/';
        const BOOK_FILE_TEMPLATE = '/^book-\d\d.txt\z/';
        const AUTHOR_FILE_TEMPLATE = '/^author-\d\d\z/'; 

        protected function getBooks($authorId) {
            $Books = array();
            $counts = scandir(self::DATA_PATH . trim($authorId));
            foreach ($counts as $node) {
                if (preg_match(self::BOOK_FILE_TEMPLATE, $node)) {
                    $Books[] = $this->getBook($authorId, $node);
                }
            }
            return $Books;
        }
        protected function getBook($authorId, $id) {
            $f = fopen(self::DATA_PATH . trim($authorId) . "/" . $id, "r");
            $rowStr = fgets($f);
            $rowArr = explode(";", $rowStr);
            $Book = (new Book())
                ->setId(trim($id))
                ->setBookTitle(trim($rowArr[0]))
                ->setBookDatePublish (new \DateTime($rowArr[1]))//setDatePublish
                ->setReadStatus($rowArr[2]);//setRead
                if(trim($rowArr[3] == 'кіберпанк')) {
                    $Book->setIsGenreCyberpank();//setGenreCyberpank
                } else {
                    $Book->setIsGenreFantasy();//setGenreFantasy
                }
            fclose($f);
            return $Book;
        }

        protected function getAuthors() {
            $authors = array();
            $counts = scandir(self::DATA_PATH);
            foreach ($counts as $node) {
                if (preg_match(self::AUTHOR_FILE_TEMPLATE, $node)) {
                    $authors[] = $this->getAuthor($node);
                }
            }
            return $authors;
        }
        protected function getAuthor($id) {
            $f = fopen(self::DATA_PATH . trim($id) . "/author.txt", "r"); 
            $authStr = fgets($f); /*повертає рядок, прочитаний із файлу, на який вказує потік*/
            $authArr = explode(";", $authStr); /*повертає масив розділених рядків*/
            fclose($f); 										

            $author = (new Author())
                ->setId(trim($id))
                ->setAuthorName(trim($authArr[0]))
                ->setAuthorYear($authArr[1])
                ->setAuthorCountry(trim($authArr[2]));
            return $author;
        }

        protected function getUsers() {
            $users = array();
            $f = fopen(self::DATA_PATH . "users.txt", "r");
            while(!feof($f)) {
                $rowStr = fgets($f);
                $rowArr = explode(";", $rowStr);
                if (count($rowArr) == 3) {
                    $user = (new User()) 
                        ->setUserName(trim($rowArr[0]))
                        ->setPassword(trim($rowArr[1]))
                        ->setRights(substr($rowArr[2],0,9));
                    $users[] = $user;
                }
            }
            fclose($f);
            return $users;
        }
        protected function getUser($id) {
            $users = $this->getUsers();
            foreach($users as $user) {
                if ($user->getUserName() == $id) {
                    return $user;
                }
            }
            return false;
        }
        protected function setBook(Book $book) {
            $f = fopen(self::DATA_PATH . $book->getAuthorId() . "/" . $book->getId(), "w");
            $read = 0;
            if ($book->isReadStatus()) {//isRead
                $read = 1;
            }
            $genre = 'фентезі';
            if ($book->isGenreCyberpank()) {
                $genre = 'кіберпанк';
            }
            $authArr = array($book->getBookTitle(), $genre, $book->getBookDatePublish()->format('d.m.Y'), $read,);//getDatePublish
            $authStr = implode(";", $authArr);
            fwrite($f, $authStr);
            fclose($f);
        }
        protected function delBook(Book $book) {
            unlink(self::DATA_PATH . $book->getAuthorId() . "/" . $book->getId());
        }
        protected function insBook(Book $book) {
            //визначаємо останній файл книги автора
            $path = self::DATA_PATH . $book->getAuthorId();
            $counts = scandir($path);
            $i = 0;
            foreach ($counts as $node) {
                if (preg_match(self::BOOK_FILE_TEMPLATE, $node)) {
                    $last_file = $node;
                }
            }
            //отримуємо індекс останнього файлу та збільшуємо на 1
            $file_index = (String)(((int)substr($last_file, -6, 2)) + 1);
            if (strlen($file_index) == 1) {
                $file_index = "0" . $file_index;
            }
            //формуємо ім'я нового файлу
            $newFileName = "book-" . $file_index . ".txt";

            $book->setId($newFileName);
            $this->setBook($book);
        }
        protected function setAuthor(Author $author) {
            $f = fopen(self::DATA_PATH . $author->getId() . "/author.txt", "w");
            $authArr = array($author->getAuthorName(), $author->getAuthorYear(), $author->getAuthorCountry(),);
            $authStr = implode(";", $authArr);
            fwrite($f, $authStr);
            fclose($f);
        }
        protected function setUser(User $user) {
            $users = $this->getUsers();
            $found = false;
            foreach ($users as $key => $oneUser) {
                if ($user->getUserName() == $oneUser->getUserName()) {
                    $found = true;
                    break;
                }
            }
            if ($found) {
                $users[$key] = $user;
                $f = fopen(self::DATA_PATH . "users.txt", "w");
                foreach($users as $oneUser) {
                    $authArr = array($oneUser->getUserName(), $oneUser->getPassword(), $oneUser->getRights() . "\r\n",);
                    $authStr = implode(";", $authArr);
                    fwrite($f, $authStr);
                }
                fclose($f);
            }
        }
        protected function delAuthor($authorId) {
            $dirName = self::DATA_PATH . $authorId;
            $counts = scandir($dirName);
            $i = 0;
            foreach ($counts as $node) {
                @unlink($dirName . "/" . $node);
            }
            @rmdir($dirName);
        }
        protected function insAuthor() {
            //визначаємо останню папку автора
            $path = self::DATA_PATH;
            $counts = scandir($path);
            foreach ($counts as $node) {
                if (preg_match(self::AUTHOR_FILE_TEMPLATE, $node)) {
                    $last_author = $node;
                }
            }
            //отримуємо індекс останньої папки та збільшуємо на 1
            $author_index = (String)(((int)substr($last_author, -1, 2)) + 1);
            if (strlen($author_index) == 1) {
                $author_index = "0" . $author_index;
            }
            //формуємо ім'я нової папки
            $newAuthorName = "author-" . $author_index;

            mkdir($path . $newAuthorName);
            $f = fopen($path . trim($newAuthorName) . "/author.txt", "w");
            fwrite($f, "New; ; ");
            fclose($f);
        }
    }
?>