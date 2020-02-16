<?php
    if(isset($_SESSION['user'])){
        $loggedIn=true;
    }else{
        $loggedIn=false;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Cool Stuff - <?= $title ?></title>
    <meta charset="utf-8"/>
    <meta name="description" content="">
    <meta name="keywords" content=""/>
    <meta name="author" content="">
    <link href="https://fonts.googleapis.com/css?family=Bungee+Inline|Carter+One=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="app/assets/css/style.css"/>
</head>
