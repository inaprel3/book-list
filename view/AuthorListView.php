<?php
    namespace View;

    abstract class AuthorListView {
        const SIMPLEVIEW = 0;
        const BOOTSTRAPVIEW = 1;
        private $user;

        public function setCurrentUser(\Model\User $user) {
            $this->user=$user;
        }
        public function checkRight($object, $right) {
            return $this->user->checkRight($object, $right); 
        }

        public abstract function showMainForm($authors, \Model\Author $author, $books);
        public abstract function showAuthorEditForm(\Model\Author $author);
        public abstract function showBookEditForm(\Model\Book $book);
        public abstract function showBookCreateForm();
        public abstract function showLoginForm();
        public abstract function showAdminForm($users);
        public abstract function showUserEditForm(\Model\User $user);

        public static function makeView($type) {
            if ($type == self::SIMPLEVIEW) {
                return new MyView();
            }
            elseif ($type == self::BOOTSTRAPVIEW) {
                return new BootstrapView();
            }
            return new MyView;
        }
    }
?>