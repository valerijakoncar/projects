<?php


namespace App\Controllers;


class Controller
{
    protected function view($fileName, $data = []){
        // [ "proizvodi", "title"]
        extract($data);
        if($fileName == "admin"){
            include "app/views/admin/head.php";//ili require onceeeee ako bude bilo greske
            include "app/views/admin/header.php";
            include "app/views/admin/$fileName.php";
        }else{
            include "app/views/fixed/head.php";//ili require onceeeee ako bude bilo greske
            include "app/views/fixed/header.php";
            include "app/views/pages/$fileName.php";
            include "app/views/fixed/footer.php";
        }

    }
}