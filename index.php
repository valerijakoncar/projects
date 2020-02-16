<?php
session_start();
///session_destroy();
    require_once "app/config/database.php";
    require_once "app/config/autoload.php";

    use App\Models\DB;
    use App\Controllers\PageController;
    use App\Controllers\LoginController;
    use App\Controllers\RegistrationController;
    use App\Controllers\ProductsController;

    $database= new DB(SERVER, DATABASE,USERNAME,PASSWORD);
    $pageController= new PageController($database);
//var_dump($_SESSION['users']);
    if(isset($_GET['page'])){
        switch($_GET['page']){
            case 'home':
                $pageController->home();
                break;
            case 'contact':
                $pageController->contact();
                break;
            case 'author':
                $pageController->author();
                break;
            case 'login':
                $login= new LoginController($database);
                $login->login($_POST);
                break;
            case 'logout':
                $logout = new LoginController($database);
                $logout->logout();
                break;
            case 'registration':
                $registration = new RegistrationController($database);
                $registration->register($_POST);
                break;
            case 'limit':
                $products= new ProductsController($database);
                $result = $products->getProducts($_GET['limit']);
                header("Content-Type: application/json");
                echo json_encode($result);
                break;
            case 'pag':
                $products= new ProductsController($database);
                $result = $products->paginationCount();
                header("Content-Type: application/json");
                echo json_encode($result);
                break;
            case 'search':
                $products= new ProductsController($database);
                $result = $products->getProductsSearch($_POST['value']);
                header("Content-Type: application/json");
                echo json_encode($result);
                break;
            case 'filter':
                $products= new ProductsController($database);
                $result = $products->getProductsFiltered($_POST);
                header("Content-Type: application/json");
                echo json_encode($result);
                break;
            case 'addWish':
                if(isset($_SESSION['user'])){
                    $products= new ProductsController($database);
                    $result = $products->addProdToWishlist($_GET['prodId']);
                    break;
                }
            case 'wishlist':
                if(isset($_SESSION['user'])){
                    $products= new ProductsController($database);
                    $result = $products->getWishlist();
                    header("Content-Type: application/json");
                    echo json_encode($result);
                    break;
                }
            case 'deleteOrder':
                $products= new ProductsController($database);
                $result = $products->deleteOrder($_GET['idProd']);
                break;
            case 'changeQuantity':
                $products= new ProductsController($database);
                $result = $products->updateOrder($_GET);
                break;

        }
    }else{
        $pageController->home();
    }


?>



