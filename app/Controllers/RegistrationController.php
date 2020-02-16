<?php


namespace App\Controllers;
use App\Models\DB;
use App\Models\Error;
use App\Models\User;

class RegistrationController
{
    private $database;

    public function __construct($database){
        $this->database = $database;
    }

    public function register($request)
    {
        if (isset($request['send'])) {

            $errors = false;
            $name = $_POST['name'];
            $pass = $_POST['pass'];
            $pass1 = $_POST['pass1'];
            $email = $_POST['email'];
            $tel = $_POST['tel'];
            $town = $_POST['town'];
            $selectedGender = $request['selectedGender'];
            $reName = "/^[A-Za-z][a-z]{5,15}[\d]{1,5}$/";
            $rePass = "/^[\d\w]{4,13}$/";
            $reTel = "/^06[\d]\-[\d]{3}\-[\d]{3,4}$/";
            $reTown = "/^[A-Z][a-z]{3,}(\s[A-Z][a-z]{2,})*$/";
            if ($request['sendViaMail'] == 'send') {
                $sendViaMail = 1;
            } else {
                $sendViaMail = 0;
            }
            if ((empty($name)) || (!preg_match($reName, $name))) {
                $errors = true;
            }
            if ((empty($pass)) || (!preg_match($rePass, $pass))) {
                $errors = true;
            }
            if ((empty($pass1)) || (!$pass1 == $pass)) {
                $errors = true;
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors = true;
            }
            if ((empty($tel)) || (!preg_match($reTel, $tel))) {
                $errors = true;
            }
            if (!preg_match($reTown, $town)) {
                $errors = true;
            }
            if (empty($town)) {
                $errors = false;
            }
            if ($selectedGender == null) {
                $errors = true;
            }
            if ($errors == true) {
                $code = 422;
            }else{
                $user = new User($this->database);
                $dataArray = [];
                $pass = md5($pass);
                array_push($dataArray,[$name, $pass, $tel, $email, $selectedGender, $sendViaMail]);
                //var_dump($dataArray);
                $code = $user->registerUser($dataArray[0]);
            }
        }else{
            $code = 404;
        }
        if(($code >= 400) && ($code <= 500)){
            $error = new Error($code);
            $error->writeInError($code);
        }
        http_response_code($code);
    }

}