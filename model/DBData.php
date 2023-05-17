<?php
namespace Model;

class DBData extends Data {
    private $db;

    public function __construct(MySQLdb $db) {
        $this->db=$db;
        $this->db->connect();
    }

    protected function getBooks($authorId) {
        $books = array();
        $book_arr = $this->db->getArrFromQuery("SELECT id, bookTitle, bookDatePublish, id_auth, readStatus, bookGenre FROM Books WHERE id_auth = $authorId");///
        if ($book_arr) {///
            foreach ($book_arr as $book_row) {///

                $book = (new Book());///
                $book->setId($book_row['id']);
                $book->setBookTitle($book_row['bookTitle']);
                //var_dump($book);
                $book->setBookDatePublish(new \DateTime($book_row['bookDatePublish']));
                $book->setAuthorId($book_row['id_auth']);
                    //var_dump($book);
                    $book->setReadStatus($book_row['readStatus']);
                if ($book_row['bookGenre'] == 'кіберпанк') {
                    $book->setIsGenreCyberpank();
                } else {
                    $book->setIsGenreFantasy();
                }
                $books[] = $book;
            }
        }
        return $books;
    }

    protected function getBook($authorId, $id) {
        $book = new Book();///
        $book_arr = $this->db->getArrFromQuery("SELECT id, bookTitle, bookDatePublish, id_auth, readStatus, bookGenre FROM Books WHERE id = $id");///
        //echo "SELECT id, bookTitle, bookDatePublish, id_auth, readStatus, bookGenre FROM Books WHERE id = $id";
        if ($book_arr && count($book_arr) > 0) {///
            $book_row = $book_arr[0];
            $book///
                ->setId($book_row['id'])
                ->setBookTitle($book_row['bookTitle'])
                ->setBookDatePublish(new \DateTime($book_row['bookDatePublish']))
                ->setAuthorId($book_row['id_auth']);
                $book->setReadStatus($book_row['readStatus']);
            if ($book_row['bookGenre'] == 'кіберпанк') {
                $book->setIsGenreCyberpank();
            } else {
                $book->setIsGenreFantasy();
            }
        }
        return $book;///
    }

    protected function getAuthors() {
        
        $authors = array();
        $auth_arr = $this->db->getArrFromQuery("SELECT id_auth, authorName, authorYear, authorCountry FROM Authors");///
        if ($auth_arr) {///
            foreach ($auth_arr as $auth_row) {
                $author = (new Author())
                    ->setId($auth_row["id_auth"])
                    ->setAuthorName($auth_row["authorName"])
                    ->setAuthorYear($auth_row["authorYear"])
                    ->setAuthorCountry($auth_row["authorCountry"]);
                $authors[] = $author;
            }
        }
        return $authors;
    }

    protected function getAuthor($id) {
        //echo "SELECT id, authorName, authorYear, authorCountry FROM Authors WHERE id = $id";
        $author = new Author();
        $auth_arr = $this->db->getArrFromQuery("SELECT id_auth, authorName, authorYear, authorCountry FROM Authors WHERE id_auth = $id");///
        if ($auth_arr && count($auth_arr) > 0) {///
            $auth_row = $auth_arr[0];
            $author
                ->setId($auth_row["id_auth"])
                ->setAuthorName($auth_row["authorName"])
                ->setAuthorYear($auth_row["authorYear"])
                ->setAuthorCountry($auth_row["authorCountry"]);
        }
        return $author;
    }

    protected function getUsers() {
        $users=array();
        if($user_arr=$this->db->getArrFromQuery("SELECT id, username, passwd, rights FROM Users")) {
            foreach($user_arr as $user_row) {
                $user=(new User())
                    ->setUsername($user_row["username"])
                    ->setPassword($user_row["passwd"])
                    ->setRights($user_row["rights"]);
                $users[]=$user;
            }
        }
        return $users;
    }

    protected function getUser($id) {
        $user=new User();
        if($users=$this->db->getArrFromQuery("SELECT id, username, passwd, rights FROM Users WHERE username='".$id."'")) {
            if(count($users)>0) {
                $user_row=$users[0];
                $user
                    ->setUsername($user_row["username"])
                    ->setPassword($user_row["passwd"])
                    ->setRights($user_row["rights"]);
            }
        }
        return $user;
    }

    protected function setBook(Book $book) {
        $readStatus=0;
        if($book->isReadStatus()) {
            $readStatus=1;
        }
        $bookGenre="фентезі";
        if($book->isGenreCyberpank()) {
            $bookGenre="кіберпанк";
        }
        $sql="UPDATE Books SET bookTitle='".$book->getBookTitle()."', bookDatePublish='".$book->getBookDatePublish()->format("d.m.Y")."', id_auth=".$book->getAuthorId().", readStatus=".$readStatus.", bookGenre='".$bookGenre."' WHERE id=".$book->getId();///Y-m-d
        $this->db->runQuery($sql);
    }

    protected function delBook(Book $book) {
        $sql="DELETE FROM Books WHERE id=".$book->getId();
        $this->db->runQuery($sql);
    }

    protected function insBook(Book $book) {
        $readStatus=0;
        if($book->isReadStatus()) {
            $readStatus=1;
        }
        $bookGenre="фентезі";
        if($book->isGenreCyberpank()) {
            $bookGenre="кіберпанк";
        }
        $sql="INSERT INTO Books(bookTitle, bookDatePublish, id_auth, readStatus, bookGenre) VALUES('".$book->getBookTitle()."','".$book->getBookDatePublish()->format("d.m.Y")."',".$book->getAuthorId().",".$readStatus.",'".$bookGenre."')";
        $this->db->runQuery($sql);
    }

    protected function setAuthor(Author $author) {
        $sql="UPDATE Authors SET authorName='".$author->getAuthorName()."', authorYear='".$author->getAuthorYear()."', authorCountry='".str_replace("'","\'",$author->getAuthorCountry())."' WHERE id=".$author->getId();
        $this->db->runQuery($sql);
    }

    protected function delAuthor($authorId) {
        $sql="DELETE FROM Authors WHERE id=".$authorId;
        $this->db->runQuery($sql);
    }

    protected function setUser(User $user) {
        $sql="UPDATE Users SET rights='".$user->getRights()."', passwd='".$user->getPassword()."' WHERE username='".$user->getUsername()."'";
        $this->db->runQuery($sql);
    }

    protected function insAuthor() {
        $sql="INSERT INTO Authors (authorName, authorYear, authorCountry) VALUES('new','','')";
        $this->db->runQuery($sql);
    }

    /*protected function getLatestAuthorId() {//
        //$id_auth = 1; 
        $res = $this->db->getArrFromQuery("SELECT id FROM Authors ORDER BY id DESC LIMIT 1");
        $id_auth = $res[0]['id'];
        return $id_auth;
    }*/
}
?>