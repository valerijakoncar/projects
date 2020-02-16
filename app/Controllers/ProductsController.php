<?php


namespace App\Controllers;
use App\Controllers\ActivityController;
use App\Models\Activity;
use App\Models\DB;
use App\Models\Error;
use App\Models\Products;

class ProductsController
{
    private $database;

    public function __construct(DB $db){
        $this->database = $db;
    }

    public function getProducts($limit=0){
        $products = new Products($this->database);
        $result = $products->getProducts($limit);
        $productsFinal = $result[0];
        $code = $result[1];
        //$first = substr($code, 0, 1);
        if(($code >= 400) && ($code <= 500)){
            $error = new Error($code);
            $error->writeInError($code);
        }
        http_response_code($code);
        return $productsFinal;
    }

    public function paginationCount(){
        $products = new Products($this->database);
        $paginationCount = $products->paginationCount();
        if($paginationCount > 0){
            $code = 200;
        }else{
            $code = 500;
        }
        if(($code >= 400) && ($code <= 500)){
            $error = new Error($code);
            $error->writeInError($code);
        }
        http_response_code($code);
        return $paginationCount;
    }

    public function getProductsSearch($searched){
        $productsModel = new Products($this->database);
        $result = $productsModel->getProductsSearch($searched);
        $products = $result[0];
        $code = $result[1];
        if(($code >= 400) && ($code <= 500)){
            $error = new Error($code);
            $error->writeInError($code);
        }
        http_response_code($code);
        return $products;
    }

    public function getProductsFiltered($request){
        $productsModel = new Products($this->database);
        $result = $productsModel->getProductsFiltered($request);
        $products = $result[0];
        $code = $result[1];
        //var_dump($products);
        if(($code >= 400) && ($code <= 500)){
            $error = new Error($code);
            $error->writeInError($code);
        }
       http_response_code($code);
        return $products;
    }

    public function getCategoryProducts($idC){
        $productsModel = new Products($this->database);
        $result = $productsModel->getCategoryProducts($idC);
        $products = $result[0];
        $code = $result[1];
        //var_dump($products);
        if(($code >= 400) && ($code <= 500)){
            $error = new Error($code);
            $error->writeInError($code);
        }

        http_response_code($code);
        return $products;
       // header("Location:index.php");
    }

    public function addProdToWishlist($prodId){
        $productsModel = new Products($this->database);
        $code = $productsModel->addProdToWishlist($prodId);
        if(($code >= 400) && ($code <= 500)){
            $error = new Error($code);
            $error->writeInError($code);
        }
        http_response_code($code);
    }

    public function getWishlist(){
        $productsModel = new Products($this->database);
        $userId = $_SESSION['user']->id;
        $result = $productsModel->getWishlist($userId);
        $products = $result[0];
        $code = $result[1];
        if(($code >= 400) && ($code <= 500)){
            $error = new Error($code);
            $error->writeInError($code);
        }
       http_response_code($code);
       return $products;
    }

    public function deleteOrder($prodId){
        $productsModel = new Products($this->database);
        $code = $productsModel->deleteOrder($prodId);
        if(($code >= 400) && ($code <= 500)){
            $error = new Error($code);
            $error->writeInError($code);
        }
        http_response_code($code);
    }

    public function updateOrder($request){
        $quantity = $request['quantity'];
        $prId = $request['prodId'];
        $productsModel = new Products($this->database);
        $code = $productsModel->updateOrder($quantity, $prId);
        if(($code >= 400) && ($code <= 500)){
            $error = new Error($code);
            $error->writeInError($code);
        }
        http_response_code($code);
    }

    public function getProductsAdmin($limit = 0){
        $products = new Products($this->database);
        $result = $products->getProductsAdmin($limit);
        $productsFinal = $result[0];
        $code = $result[1];
        //$first = substr($code, 0, 1);
        if(($code >= 400) && ($code <= 500)){
            $error = new Error($code);
            $error->writeInError($code);
        }
        http_response_code($code);
        return $productsFinal;
    }

    public function paginationCountA(){
        $products = new Products($this->database);
        $paginationCount = $products->paginationCountA();
        if($paginationCount > 0){
            $code = 200;
        }else{
            $code = 500;
        }
        if(($code >= 400) && ($code <= 500)){
            $error = new Error($code);
            $error->writeInError($code);
        }
        http_response_code($code);
        return $paginationCount;
    }

    public function getProduct($request){
        if($request['send']){
            //var_dump($request);
            $id = $request['id'];
            $products = new Products($this->database);
            $result = $products->getProduct($id);
            $product = $result[0];
            $code = $result[1];
            if(($code >= 400) && ($code <= 500)){
                $error = new Error($code);
                $error->writeInError($code);
            }
            http_response_code($code);
            return $product;
        }
    }

    public function updateProducts($request){
        $id = $request['idProd'];
        $controller = new Products($this->database);
        $code = $controller->updateProducts($request);
        if(($code >= 400) && ($code <= 500)){
            $error = new Error($code);
            $error->writeInError($code);
        }else if($code >= 200){
            $activity = new ActivityController();
            $text = $_SESSION['user']->username . " updated product with id : " . $id;
            $activity->write($text);
        }
        http_response_code($code);
    }

    public function insertProducts($request){
        $controller = new Products($this->database);
        $r = $controller->insertProducts($request);
        $id = $r[0];
        $code = $r[1];
        if(($code >= 400) && ($code <= 500)){
            $error = new Error($code);
            $error->writeInError($code);
        }else if($code >= 200){
            $activity = new ActivityController();
            $text = $_SESSION['user']->username . " inserted product with id : " . $id;
            $activity->write($text);
        }
        http_response_code($code);
    }

    public function deleteProducts($id){
        $controller = new Products($this->database);
        $code = $controller->deleteProducts($id);
        if(($code >= 400) && ($code <= 500)){
            $error = new Error($code);
            $error->writeInError($code);
        }else if($code >= 200){
            $activity = new ActivityController();
            $text = $_SESSION['user']->username . " deleted product with id : " . $id;
            $activity->write($text);
        }
        http_response_code($code);
    }

}