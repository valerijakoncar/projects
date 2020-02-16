<?php


namespace App\Models;


class DB
{
    private $server;
    private $database;
    private $username;
    private $password;

    public $conn;

    public function __construct($server, $database, $username, $password){
        $this->database = $database;
        $this->server = $server;
        $this->username = $username;
        $this->password = $password;

        $this->connect();
    }

    private function connect(){
        $this->conn = new \PDO("mysql:host={$this->server};dbname={$this->database};charset=utf8", $this->username, $this->password);

        $this->conn->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_OBJ);

        $this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    public function executeQuery($query){
        return $this->conn->query($query)->fetchAll();
    }

    public function executeOneRow($query, $params){
        $prepare = $this->conn->prepare($query);
       try{
            $prepare->execute($params);
            $success = $prepare->rowCount();
            //echo "voo je iz DB success :";
            //var_dump($success);
           if($success){
                   $code = 200;
                   $user = $prepare->fetch();
                   $data[] = $user;
           }else{
               //if($type == 0) {
                   $code = 403;
           }
       }catch(\PDOException $ex){
           $code = 409;
       }
        $data[] = $code;

        return $data;
    }

}