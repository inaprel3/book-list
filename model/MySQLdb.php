<?php
namespace Model; 
class MySQLdb {
    public $link;
    public $err;
    public function connect() {
        $this->link=new \mysqli(\Config::$server, \Config::$user, \Config::$pwd, \Config::$db);//db
        if(!$this->link) {
            return false;
        }
        $this->runQuery("SET NAMES 'utf-8'");
        return true;
    }
    public function disconnect() {
        $this->link->close();
        unset($this->link);
    }
    public function runQuery($sql) {
        $res=$this->link->query($sql);
        if(!$res) {
            $this->err=$this->link->error;
        }
        return $res;
    }
    public function getArrFromQuery($sql) {
        $res_arr=[];
        $rs=$this->runQuery($sql);
        if($rs && $rs->num_rows > 0) {///
            while($row=$rs->fetch_assoc()) {
                $res_arr[]=$row;
            }
        }
        return $res_arr;
    }
}
?>