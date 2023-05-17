<?php
    namespace Model; //простір імен для всіх класів моделей

    class Author {
        private $id;
        private $authorName; ///number
        private $authorYear; ///starosta
        private $authorCountry; ///department

        public function getId() {
            return $this->id;
        }
        public function setId($id) {
            $this->id = $id;
            return $this;
        }
        public function getAuthorName() {
            return $this->authorName;
        }
        public function setAuthorName($authorName) {
            $this->authorName = $authorName;
            return $this;
        }
        public function getAuthorYear() {
            return $this->authorYear;
        }
        public function setAuthorYear($authorYear) {
            $this->authorYear = $authorYear;
            return $this;
        }
        public function getAuthorCountry() {
            return $this->authorCountry;
        }
        public function setAuthorCountry($authorCountry) {
            $this->authorCountry = $authorCountry;
            return $this;
        }
    }
?>