<?php
    namespace Model;

    abstract class Data {
        const FILE = 0;
        const DB=1;///

        private $error;
        private $user;  

        public function setCurrentUser($userName) {
            $this->user = $this->readUser($userName);
        }
        public function getCurrentUser() { 
            return $this->user;
        }

        public function checkRight($object, $right) {
            return $this->user->checkRight($object, $right); 
        }
        public function readBooks($authorId) {
            if ($this->user->checkRight('book','view')) { 
                $this->error = "";
                return $this->getBooks($authorId);
            } else {
                $this->error = "You have no permissions to view books";
                return false;
            }
        }
        protected abstract function getBooks($authorId);

        public function readBook($authorId, $id) {
            if($this->checkRight('book', 'view')) {
                $this->error = "";
                return $this->getBook($authorId, $id);
            } else {
                $this->error = "You have no permissions to view book";
                return false;
            }
        }
        protected abstract function getBook($authorId, $id);

        public function readAuthors() {
            if($this->checkRight('author', 'view')) {
                $this->error = "";
                return $this->getAuthors();
            } else {
                $this->error = "You have no permissions to view authors";
                return false;
            }
        }
        protected abstract function getAuthors();

        public function readAuthor($id) {
            if($this->checkRight('author', 'view')) {
                $this->error = "";
                return $this->getAuthor($id);
            } else {
                $this->error = "You have no permissions to view author";
                return false;
            }
        }
        protected abstract function getAuthor($id);

        public function readUsers() {
            if($this->checkRight('user', 'admin')) {
                $this->error = "";
                return $this->getUsers();
            } else {
                $this->error = "You have no permissions to view users";
                return false;
            }
        }
        protected abstract function getUsers();

        public function readUser($id) {
            $this->error = "";
            return $this->getUser($id);
        }
        protected abstract function getUser($id);

        public function writeBook(Book $book) {
            if($this->checkRight('book', 'edit')) {
                $this->error = "";
                $this->setBook($book);
                return true;
            } else {
                $this->error = "You have no permissions to edit books";
                return false;
            }
        }
        protected abstract function setBook(Book $book);
        
        public function writeAuthor(Author $author) {
            if($this->checkRight('author', 'edit')) {
                $this->error = "";
                $this->setAuthor($author);
                return true;
            } else {
                $this->error = "You have no permissions to edit authors";
                return false;
            }
        }
        protected abstract function setAuthor(Author $author);

        public function writeUser(User $user) {
            if($this->checkRight('user', 'admin')) {
                $this->error = "";
                $this->setUser($user);
                return true;
            } else {
                $this->error = "You have no permissions to administrate users";
                return false;
            }
        }
        protected abstract function setUser(User $user);

        public function removeBook(Book $book) {
            if($this->checkRight('book', 'delete')) {
                $this->error = "";
                $this->delBook($book);
                return true;
            } else {
                $this->error = "You have no permissions to delete books";
                return false;
            }
        }
        protected abstract function delBook(Book $book);

        public function addBook(Book $book) {
            if($this->checkRight('book', 'create')) {
                $this->error = "";
                $this->insBook($book);
                return true;
            } else {
                $this->error = "You have no permissions to create books";
                return false;
            }
        }
        protected abstract function insBook(Book $book);

        public function removeAuthor($authorId) {
            if($this->checkRight('author', 'delete')) {
                $this->error = "";
                $this->delAuthor($authorId);
                return true;
            } else {
                $this->error = "You have no permissions to delete authors";
                return false;
            }
        }
        protected abstract function delAuthor($authorId);

        public function addAuthor() {
            if($this->checkRight('author', 'create')) {
                $this->error = "";
                $this->insAuthor();
                return true;
            } else {
                $this->error = "You have no permissions to create authors";
                return false;
            }
        }
        protected abstract function insAuthor();

        public function getError() {
            if ($this->error) {
                return $this->error;
            }
            return false;
        }

        public static function makeModel($type) {
            if ($type == self::FILE) {
                return new FileData();
            }
            elseif ($type==self::DB) {///
                return new DBData(new MySQLdb());///
            }
            return new FileData();
        } 
    }
?>