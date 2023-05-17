<?php
    //require_once '../model/autorun.php';
    require_once '../controller/autorun.php';//
    require_once '../data/config.php';
    
    $db=new \Model\MySQLdb();
    //зв'язуємось з БД
    $db->connect();
    //Очищаємо таблиці БД
    /*$db->runQuery("DELETE FROM Books");
    $db->runQuery("DELETE FROM Authors");
    $db->runQuery("DELETE FROM Users");*/

    //створюємо екземляр моделі для роботи з файлами і встановлюємо поточним користувачем адміністратора
    $fileModel=\Model\Data::makeModel(\Model\Data::FILE);
    $fileModel->setCurrentUser('admin');
    //зчитати дані про користувачів з файлу
    $users=$fileModel->readUsers();
    //занести інформацію про кожного в БД
    foreach($users as $user) {
        $db->runQuery("INSERT INTO Users (username, passwd, rights) VALUES ('".$user->getUsername()."', '".$user->getPassword()."', '".$user->getRights()."')");
    }
    //створюємо екземпляр моделі для роботи з базами даних і встановлюємо поточним користувачем адміна
    $dbModel=\Model\Data::makeModel(\Model\Data::DB);
    $dbModel->setCurrentUser('admin');
    
    //зчитуємо дані про авторів з файлів
    $authors=$fileModel->readAuthors();
    foreach($authors as $author) {
        //вносимо дані про кожду з них у БД
        $sql="INSERT INTO Authors (authorName, authorYear, authorCountry) VALUES('".$author->getAuthorName()."', '".$author->getAuthorYear()."', '".$author->getAuthorCountry()."')";
        ///echo $sql ."</br>";
        $db->runQuery($sql);
        //Зчитуємо найбільший id автора, щоб додати книги, які належать цьому автору
        $res=$db->getArrFromQuery("SELECT max(id) id FROM Authors");
        $id_auth=$res[0]['id'];
        //Зчитуємо з файлів книги, які належать цьому автору
        $books=$fileModel->readBooks($author->getId());
        foreach($books as $book) {
            //Встановлюємо айді цього автора в БД для автора книг і додаємо його до БД
            $book->setAuthorId($id_auth);
            $dbModel->addBook($book);
        }
    }
    //від'єднуємось від БД
    $db->disconnect();
?>