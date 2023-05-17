<?php
    namespace Model;

    class Book {
        const CYBERPANK = 0;
        const FANTASY = 1;

        private $id;
        private $bookTitle; ///name
        private $bookDatePublish; ///dob    date_publish
        private $bookGenre; ///gender   genre
        private $readStatus; ///privilege read
        private $authorId; //посилання на автора, який написав книгу

        public function getId() {
            return $this->id;
        }
        public function setId($id) {
            $this->id = $id;
            return $this;
        }
        public function getBookTitle() {
            return $this->bookTitle;
        }
        public function setBookTitle($bookTitle) {
            $this->bookTitle = $bookTitle;
            return $this;
        }
        public function getBookDatePublish() {
            return $this->bookDatePublish;
        }
        public function setBookDatePublish($bookDatePublish) {
            $this->bookDatePublish = $bookDatePublish;
            return $this;
        }
        public function isGenreCyberpank() {
            return ($this->bookGenre == self::CYBERPANK);
        }
        public function isGenreFantasy() {
            return !($this->bookGenre == self::FANTASY);
        }
        public function setIsGenreCyberpank() {
            $this->bookGenre = self::CYBERPANK;
            return $this;
        }
        public function setIsGenreFantasy() {
            $this->bookGenre = self::FANTASY;
            return $this;
        }
        public function isReadStatus() {
            return $this->readStatus;
        }
        public function setReadStatus($readStatus) {
            $this->readStatus = $readStatus;
            return $this;
        }
        public function getAuthorId() {
            return $this->authorId;
        }
        public function setAuthorId($authorId) {
            $this->authorId = $authorId;
            return $authorId;
        }
    }
?>