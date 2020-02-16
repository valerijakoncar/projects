<?php


namespace App\Models;
use App\Models\DB;

class User
{
    private $db;

    public function __construct(DB $database){
        $this->db=$database;
    }

    public function getUser($username, $password){
        $data = $this->db->executeOneRow("SELECT id, username, role_id FROM users WHERE  username = ? AND pass = md5(?)",[$username, $password]);
        if(count($data) == 2){  //znaci da je prosledjen i user objekat i code(200)
            $code = $data[1];
            $user = $data[0];
            $data['user'] = $user;
        }else{ // znaci da je prosledjen niz koji ima jedan element i kod iz reda 4 ili 5 sto znaci da se desila greska
            $code = $data[0];
            $errorText="";
            switch($code){
                case 403:
                    $errorText = "You're not registrated.";
                    break;
                case 409:
                    $errorText = "Exception happened.";
                    break;
                case 500:
                    $errorText = "There was an error.Try again.";
                    break;
            }
            $data['error'] = $errorText;
        }
        $data['code'] = $code;
        return $data;
    }

    public function registerUser($data){
         $prepare = $this->db->conn->prepare("INSERT INTO users (username, pass, phone, email, gender, send_via_mail, role_id)
            VALUES (?, ?, ?, ?, ?, ?, 2)");
        try{
            $prepare->execute($data);
            $success = $prepare->rowCount();
            if($success){
                $code = 201;
            }else{
                $code = 409;
            }
        }catch (\PDOException $ex){
            $code = 409;
        }

        return $code;
    }
}