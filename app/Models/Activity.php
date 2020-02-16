<?php


namespace App\Models;


class Activity
{
    public function write($write){
        $file = fopen("app/data/activity.txt", "a");
        $text = $_SERVER['REMOTE_ADDR'] . "\t" . $write . "\t" . basename($_SERVER['REQUEST_URI']) . "\t" . date("d.m.Y H:i:s"). "\n";
        fwrite($file, $text);
        fclose($file);
    }

    public function loggedInUser(){
        $file=fopen("app/data/loggedUsers.txt","r");
        $data=file("app/data/loggedUsers.txt");
        @$number=intval(trim($data[0]));
        if($number=='undefined'){
            $number=0;
        }
        // var_dump($number);
        fclose($file);
        $file=fopen("app/data/loggedUsers.txt","w");
        $number++;
        fwrite($file,$number);
        fclose($file);
    }

    public function loggedOutUser(){
        $file=fopen("app/data/loggedUsers.txt","r");
        $data=file("app/data/loggedUsers.txt");
        @$number=intval(trim($data[0]));
        fclose($file);
        $file=fopen("app/data/loggedUsers.txt","w");
        $number--;
        fwrite($file,$number);
        fclose($file);
    }
}