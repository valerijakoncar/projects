<?php


namespace App\Models;


class Error
{
    private $code;

    public function __construct($code){
        $this->code = $code;
    }

    public function writeInError(){
        $file = fopen("app/data/errors.txt", "a");
        $write = basename($_SERVER['REQUEST_URI']) . "\t" . $this->code . "\t" . $_SERVER['REMOTE_ADDR'] . "\t" . date("d.m.Y H:i:s"). "\n";
        fwrite($file, $write);
        fclose($file);
    }
}