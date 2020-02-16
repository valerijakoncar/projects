<?php
session_start();
require_once "app/config/database.php";
require_once "app/config/autoload.php";

use App\Models\DB;
use App\Controllers\PageController;
use App\Controllers\LoginController;
use App\Controllers\ProductsController;

$database= new DB(SERVER, DATABASE,USERNAME,PASSWORD);
$pageController= new PageController($database);

if(isset($_GET['page'])) {
    switch ($_GET['page']) {
        case 'logout':
            $logout = new LoginController($database);
            $logout->logout();
            break;
        case 'limit':
            $p = new ProductsController($database);
            //$limit = isset($_GET['limit']) ?  ;
            $products = $p->getProductsAdmin($_GET['limit']);
            header("Content-Type: application/json");
            echo (json_encode($products));
            break;
        case 'search':
            $products= new ProductsController($database);
            $result = $products->getProductsSearch($_GET['value']);
            header("Content-Type: application/json");
            echo json_encode($result);
            break;
        case 'clickUpdate':
            $products= new ProductsController($database);
            $result = $products->getProduct($_POST);
            header("Content-Type: application/json");
            echo json_encode($result);
            break;
        case 'update':
            $products= new ProductsController($database);
            $result = $products->updateProducts($_POST);
            header("Location:admin.php");
            break;
        case 'insert':
            $products= new ProductsController($database);
            $result = $products->insertProducts($_POST);
            header("Location:admin.php");
            break;
        case 'delete':
            $products= new ProductsController($database);
            $result = $products->deleteProducts($_POST['id']);
           // header("Location:admin.php");
            break;
         case 'pag':
             $products= new ProductsController($database);
            $result = $products->paginationCountA();
            header("Content-Type: application/json");
            echo json_encode($result);
            break;

    }
}else{
    if(isset($_SESSION['user'])){
        if($_SESSION['user']->role_id != 1){
            $code = 404;
            //echo"Error!Unauthorized access.";
            http_response_code($code);
        }else if($_SESSION['user']->role_id==1){
            $pageController->admin();
        }}
}

?>
