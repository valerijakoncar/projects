<?php

namespace App\Controllers;
use App\Controllers\ActivityController;
use App\Models\DB;
use App\Models\Error;
use App\Models\User;

class LoginController
{
    private $database;

    public function __construct(DB $database)
    {
        $this->database = $database;
    }

    public function login($request){
        if(isset($request['logIn'])){
            $valid = true;
           $username = $request['logUsername'];
            $password = $request['logPass'];
            $reUsername="/^[A-Za-z][a-z]{5,15}[\d]{1,5}$/";
            $rePass="/^[\d\w]{5,13}$/";

            if((empty($username)) || (!preg_match($reUsername,$username))){
                $valid=false;
            }
            if((empty($password)) || (!preg_match($rePass,$password))){
                $valid=false;
            }
            if($valid == false){
                $code=422;
                $_SESSION['errors'] = "Your data is not in valid format.";
            }else{
                $user = new User($this->database);
                //echo "uslo u else napravljen user";
                $result = $user->getUser($username, $password);
                //var_dump($result);
                $code = $result['code'];
                if($result['code'] == 200){ //sve je proslo OK imamo usera i response je 200
                    $_SESSION['user'] = $result['user'];
                    echo( $_SESSION['user']->username);
                    unset($_SESSION['errors']);
                }else{
                    $_SESSION['errors'] = $result['error'];
                    echo( $_SESSION['errors']);

                    //echo( $_SESSION['user']->username);
                }
            }
        }else{
            $code = 404;
            $_SESSION['errors'] = "Page not found.";
        }
        if(($code >= 400) && ($code <= 500)){
            $error = new Error($code);
            $error->writeInError($code);
        }else{
            $activity = new ActivityController();
            $activity->loggedInUser();
        }
        http_response_code($code);
        header("Location: index.php");
    }

    public function logout(){
        $activity = new ActivityController();
        $activity->loggedOutUser();
        unset($_SESSION['user']);
        header("Location: index.php");
    }
}