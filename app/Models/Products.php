<?php


namespace App\Models;
use App\Models\DB;

class Products
{
    private $db;
    public $offset = 12;

    public function __construct(DB $database){
        $this->db = $database;
    }

    public function getProducts($limit=0){
        $prepare = $this->db->conn->prepare("SELECT p.id,p.name, p.hot, p.cat_id, i.alt, i.path, i.name AS picName, pr.price, pr.oldPrice FROM products p 
                                        INNER JOIN images i ON p.img_id = i.id INNER JOIN price pr ON p.price_id = pr.id LIMIT :limit, :offset");
        $limit = $limit * $this->offset;
        $prepare->bindParam(":limit", $limit, \PDO::PARAM_INT);
        $prepare->bindParam(":offset", $this->offset, \PDO::PARAM_INT);
        try{
            $prepare->execute();
            $rows = $prepare->rowCount();
            $products = $prepare->fetchAll();
            if($rows > 0){
                $code = 200;
            }else{
                $code = 500;
            }
        }catch(\PDOException $ex){
            $code = 409;
        }
        $data = [];
        $data[] = $products;
        $data[] = $code;
        return $data;
    }

    public function paginationCount(){
        $numOfProducts = $this->db->conn->query("SELECT COUNT(*) AS numOfProducts FROM products")->fetch();
        return ceil($numOfProducts->numOfProducts / $this->offset);
    }

    public function getProductsSearch($searched){
        $searched = strtoupper($searched);
        $stringToSearch = "%$searched%";
        //var_dump($stringToSearch);
        $prepare = $this->db->conn->prepare("SELECT p.id, p.name, p.hot, p.cat_id, i.alt, i.path, i.name AS picName, pr.price, pr.oldPrice FROM products p 
                                        INNER JOIN images i ON p.img_id = i.id INNER JOIN price pr ON p.price_id = pr.id WHERE p.name LIKE ?");
        try{
            $prepare->execute([$stringToSearch]);
            $rows = $prepare->rowCount();
            $products = $prepare->fetchAll();
            if($rows > 0){
                $code = 200;
            }else{
                $code = 404;
            }
        }catch(\PDOException $ex){
            $code = 409;
        }
        $data = [];
        $data[] = $products;
        $data[] = $code;

        return $data;
    }

    public function getProductsFiltered($request)
    {
        $catId = (int)$request['categoryId'];
        $priceSort = $request['priceSort'];
        $price = (int)$request['price'];
        $query = "SELECT p.id,p.name, p.hot, p.cat_id, i.alt, i.path, i.name AS picName, pr.price, pr.oldPrice FROM products p 
                                        INNER JOIN images i ON p.img_id = i.id INNER JOIN price pr ON p.price_id = pr.id WHERE
                                         pr.price <= :price ";
        if ($catId) {
            //$catId=1;
            $query .= "AND p.cat_id = :catId ";
        }
        if($priceSort == 'ASC'){
            $query.="ORDER BY pr.price ASC";
        }else if($priceSort == 'DESC'){
            $query .= "ORDER BY pr.price DESC";
        }
        $prepare = $this->db->conn->prepare($query);
        try {
            if ($catId) {
                $prepare->bindParam(":catId", $catId);
            }
             $prepare->bindParam(":price", $price);
            // $prepare->bindParam(":priceSort", $priceSort);
            // var_dump($prepare);
            $prepare->execute();
            $rows = $prepare->rowCount();
            $products = $prepare->fetchAll();
            if($rows > 0){
               $code = 200;
           }else{
               $code = 404;
           }
       }catch(\PDOException $ex){
           $code = 409;
       }

       $data = [];
       $data[] = $products;
       $data[] = $code;

       return $data;

    }

    public function getCategoryProducts($idC){
        $prepare = $this->db->conn->prepare("SELECT p.id,p.name, p.hot, p.cat_id, i.alt, i.path, i.name AS picName, pr.price, pr.oldPrice FROM products p 
                                        INNER JOIN images i ON p.img_id = i.id INNER JOIN price pr ON p.price_id = pr.id WHERE p.cat_id = ?");
        try {
            $success = $prepare->execute([$idC]);
            if($success){
                $code = 200;
                $products = $prepare->fetchAll();
            }else{
                $code = 500;
            }
        }catch(\PDOException $e){
            $code= 409;
        }
        //var_dump($products);
        $data = [];
        $data[] = $products;
        $data[] = $code;
        return $data;
    }

    public function addProdToWishlist($prodId){
       $prodId = (int)$prodId;
       $userId = (int)$_SESSION['user']->id;
       try{
           $prepare = $this->db->conn->prepare("SELECT * FROM  wishlist WHERE user_id=? AND product_id=?");
           //var_dump($prepare);
           $prepare->execute([$userId, $prodId]);
           $rows = $prepare->rowCount();
           if($rows == 0){
               $prepare1= $this->db->conn->prepare("INSERT INTO wishlist VALUES (NULL, ?, 1, ?)");
               try{
                   $success = $prepare1->execute([ $prodId, $userId]);
                   if($success){
                       $code = 201;
                       $lastId = $this->db->conn->lastInsertId();
                       var_dump($lastId);
                   }else{
                       $code = 500;
                   }
               }catch(\PDOException $ex){
                   $code = 409;
               }
           }
       }catch (\PDOException $ex){
           $code= 409;
       }
      return $code;
    }

    public function getWishlist($userId){
        $prepare = $this->db->conn->prepare("SELECT p.id,p.name, p.hot, p.cat_id, i.alt, i.path, i.name AS picName, pr.price, pr.oldPrice, w.quantity FROM products p 
                                        INNER JOIN images i ON p.img_id = i.id INNER JOIN price pr ON p.price_id = pr.id INNER JOIN wishlist w ON w.product_id = p.id");
        try{
            $success = $prepare->execute([$userId]);
            if($success){
                $code = 200;
                $result = $prepare->fetchAll();
            }else{
                $code = 500;
            }

        }catch (\PDOException $ex){
            $code= 409;
        }
        $data = [];
        $data[] = $result;
        $data[] = $code;
        return $data;
    }

    function deleteOrder($prodId){
        $userId = $_SESSION['user']->id;
        $prepare = $this->db->conn->prepare("DELETE FROM wishlist WHERE product_id = ? AND user_id = ?");
        try{
            $success = $prepare->execute([$prodId, $userId]);
            if($success){
                $code = 204;
            }else{
                $code = 500;
            }
        }catch (\PDOException $ex){
            $code= 409;
        }
        return $code;
    }

    public function updateOrder($quantity, $prId){
        $userId = $_SESSION['user']->id;
        $prepare = $this->db->conn->prepare("UPDATE wishlist SET quantity = ? WHERE product_id = ? AND user_id = ?");
        try{
            $success = $prepare->execute([$quantity, $prId, $userId]);
            if($success){
                $code = 204;
            }else{
                $code = 500;
            }
        }catch (\PDOException $ex){
            $code= 409;
        }
        return $code;
    }

    public function getProductsAdmin($limit){
        $prepare = $this->db->conn->prepare("SELECT p.id,p.name, p.hot, p.cat_id, i.alt, i.path, i.name AS picName, pr.price, pr.oldPrice FROM products p 
                                     INNER JOIN images i ON p.img_id = i.id INNER JOIN price pr ON p.price_id = pr.id LIMIT :limit, :offset");
        $offset = 5;
        $limit = $limit * $offset;
        $prepare->bindParam(":limit", $limit, \PDO::PARAM_INT);
        $prepare->bindParam(":offset", $offset, \PDO::PARAM_INT);
        try{
            $prepare->execute();
            $rows = $prepare->rowCount();
            $products = $prepare->fetchAll();
            if($rows > 0){
                $code = 200;
            }else{
                $code = 500;
            }
        }catch(\PDOException $ex){
            $code = 409;
        }
        $data = [];
        $data[] = $products;
        $data[] = $code;
        return $data;
    }

    public function paginationCountA(){
        $numOfProducts = $this->db->conn->query("SELECT COUNT(*) AS numOfProducts FROM products")->fetch();
        return ceil($numOfProducts->numOfProducts / 5);
    }

    public function getProduct($id){
        $result = $this->db->executeOneRow("SELECT p.id,p.name, p.hot, p.cat_id, i.alt, i.path, i.name AS picName, pr.price, pr.oldPrice FROM products p 
                                     INNER JOIN images i ON p.img_id = i.id INNER JOIN price pr ON p.price_id = pr.id WHERE p.id = ? ",[$id]);
        return $result;
    }

    public function updateProducts($request){
       // var_dump($request);
       // var_dump($_FILES);
        if(isset($request['proceedUpd'])) {
            $id = $request['idProd'];
            $prodName = $request['prodName'];
            $prodPrice = $request['prodPrice'];
            $oldPrice = $request['oldPrice'];
            $hot = $request['hotProd'];
            $catId = $request['prodCat'];

            if (!empty($_FILES['imgProd']['name'])) {
                $fileName = $_FILES['imgProd']['name'];
                $finalFileName = time() . "_" . $fileName;
                $size = $_FILES['imgProd']['size'];
                $tmpName = $_FILES['imgProd']['tmp_name'];
                $folder = 'app/assets/images/edited/';
                $type = $_FILES['imgProd']['type'];
                $error = $_FILES['imgProd']['error'];


                list($width, $height) = getimagesize($tmpName);

                if ($type == "image/jpeg") {
                    $img = imagecreatefromjpeg($tmpName);
                } else if ($type == "image/jpg") {
                    $img = imagecreatefromjpg($tmpName);
                } else if ($type == "image/png") {
                    $img = imagecreatefrompng($tmpName);
                }
                //Kreiranje nove slike u koloru
                $newWidth = 120;
                $procentage = $newWidth / $width;
                $newHeight = $height * $procentage;
                $empty_image = imagecreatetruecolor($newWidth, $newHeight);
                imagecopyresampled($empty_image, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                $new_image = $empty_image;


                $smallerFileName = 'small_' . $finalFileName;
                $path = $folder . $finalFileName;
                $smallerFilePath = $folder . $smallerFileName;

                switch ($type) {
                    case 'image/jpeg':
                        imagejpeg($new_image, $smallerFilePath, 75);
                        break;
                    case 'image/png':
                        imagepng($new_image, $smallerFilePath);
                        break;
                    case 'image/jpg':
                        imagejpg($new_image, $smallerFilePath);
                        break;
                }

                // $path_original_image = 'assets/images/'.$name;

                if (move_uploaded_file($tmpName, $path)) {
                    echo "Slika je upload-ovana na server!";
                }

                $query = "INSERT INTO images (name,alt,type,size,path)
             VALUES(:nameP,:altP,:typeP,:sizeP,:pathP)";
                $alt = "product";
                $prepare = $this->db->conn->prepare($query);
                $prepare->bindParam("nameP", $smallerFileName);
                $prepare->bindParam("altP", $alt);
                $prepare->bindParam("typeP", $type);
                $prepare->bindParam("sizeP", $size);
                $prepare->bindParam("pathP", $folder);
                try {
                    $result = $prepare->execute();
                    if ($result) {
                        $picId = $this->db->conn->lastInsertId();
                        $query1 = "UPDATE products SET img_id=:idpic WHERE id=:id3";
                        $res3 = $this->db->conn->prepare($query1);
                        $res3->bindParam(":idpic", $picId);
                        $res3->bindParam("id3", $id);
                        try {
                            $resultProd = $res3->execute();
                            if ($resultProd) {
                                //echo"PROSLO DOBRO";
                            }
                        } catch (\PDOException $ex) {
                            $code = 409;
                        }
                    }
                } catch (\PDOException $ex) {
                    $code = 409;
                }
            }
                $query2="UPDATE price  SET oldPrice=:oldPr,price=:prodPr WHERE id=:idP";
             try{
                 //var_dump($oldPrice);
                 //var_dump($sale);
                 $p=$this->db->conn->prepare($query2);
                 $p->bindParam(":oldPr",$oldPrice);
                 $p->bindParam(":prodPr",$prodPrice);
                 $p->bindParam("idP",$id);
                 //echo"pre";
                 $res=$p->execute();
                 //echo"posle";
                 //var_dump($result1);
                 // echo('kkk');
                 if($res){
                     $query3="UPDATE products SET name=:pName, cat_id=:cId, hot=:hot WHERE id=:id";
                 }
                 $catId=(int)$catId;
                 $hot = (int)$hot;
                 $res2=$this->db->conn->prepare($query3);
                 $res2->bindParam(":pName",$prodName);
                 $res2->bindParam(":cId",$catId);
                 $res2->bindParam(":hot",$hot);
                 $res2->bindParam(":id",$id);
                 $result2=$res2->execute();
                 //var_dump($result2);
                 if($result2){
                     $code=204;
                     //echo"uspesan update";
                 }
             }catch(PDOException $e){
                 $code=500;
                 echo($e->getMessage());
             }
             return $code;
               // http_response_code($code);
               // header("Location: ../../../admin.php");*/

        }
    }

    public function insertProducts($request)
    {
        if (isset($request['proceedIns'])) {
            $prodName = $request['prodNameI'];
            $prodPrice = $request['prodPriceI'];
            $oldPrice = $request['oldPriceI'];
            $hot = $request['hotProdI'];
            $catId = $request['prodCatI'];

            if (!empty($_FILES['imgProdI']['name'])) {
                $fileName = $_FILES['imgProdI']['name'];
                $finalFileName = time() . "_" . $fileName;
                $size = $_FILES['imgProdI']['size'];
                $tmpName = $_FILES['imgProdI']['tmp_name'];
                $folder = 'app/assets/images/edited/';
                $type = $_FILES['imgProdI']['type'];
                $error = $_FILES['imgProdI']['error'];


                list($width, $height) = getimagesize($tmpName);

                if ($type == "image/jpeg") {
                    $img = imagecreatefromjpeg($tmpName);
                } else if ($type == "image/jpg") {
                    $img = imagecreatefromjpg($tmpName);
                } else if ($type == "image/png") {
                    $img = imagecreatefrompng($tmpName);
                }

                $newWidth = 120;
                $procentage = $newWidth / $width;
                $newHeight = $height * $procentage;
                $empty_image = imagecreatetruecolor($newWidth, $newHeight);
                imagecopyresampled($empty_image, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                $new_image = $empty_image;


                $smallerFileName = 'small_' . $finalFileName;
                $path = $folder . $finalFileName;
                $smallerFilePath = $folder . $smallerFileName;

                switch ($type) {
                    case 'image/jpeg':
                        imagejpeg($new_image, $smallerFilePath, 75);
                        break;
                    case 'image/png':
                        imagepng($new_image, $smallerFilePath);
                        break;
                    case 'image/jpg':
                        imagejpg($new_image, $smallerFilePath);
                        break;
                }

                // $path_original_image = 'assets/images/'.$name;

                if (move_uploaded_file($tmpName, $path)) {
                    echo "Slika je upload-ovana na server!";
                }

                $query = "INSERT INTO images (name,alt,type,size,path)
             VALUES(:nameP,:altP,:typeP,:sizeP,:pathP)";
                $alt = "product";
                $prepare = $this->db->conn->prepare($query);
                $prepare->bindParam("nameP", $smallerFileName);
                $prepare->bindParam("altP", $alt);
                $prepare->bindParam("typeP", $type);
                $prepare->bindParam("sizeP", $size);
                $prepare->bindParam("pathP", $folder);
                try {
                    $result = $prepare->execute();
                    if ($result) {
                        $picId = $this->db->conn->lastInsertId();
                        $query1 = "INSERT INTO price VALUES (NULL, ?, ?)";
                        $p= $this->db->conn->prepare($query1);
                        try{
                            $p->execute([$prodPrice, $oldPrice]);
                            $idPrice = $this->db->conn->lastInsertId();
                            $query2 = "INSERT INTO products VALUES (NULL, ?, ?, ?, ?, ?)";
                            $prepare = $this->db->conn->prepare($query2);
                            try{
                                $success = $prepare->execute([$prodName, $catId, $hot, $picId, $idPrice]);
                                if($success){
                                    $code = 201;
                                    $idOfProduct = $this->db->conn->lastInsertId();
                                }else{
                                    $code = 500;
                                }
                            }catch (\PDOException $e){
                                $code = 409;
                            }
                        }catch (\PDOException $e){
                            $code = 409;
                        }
                    }
                }catch(\PDOException $e){
                    $code = 409;
                }
            }else{
                $code = 422;
            }
        }
        $data = [];
        $data[] = $idOfProduct;
        $data[] = $code;
        return $data;
    }

    public function deleteProducts($id){
        try{
            $pre = $this->db->conn->prepare("DELETE FROM wishlist WHERE product_id = ?");
            $pre->execute([$id]);
            $prepare1 = $this->db->conn->prepare("SELECT img_id FROM products WHERE id = ?");
            $prepare1->execute([$id]);
            //var_dump($a);
            $idPic = $prepare1->fetch();
            //var_dump($idPic);
            $idPic = $idPic->img_id;
            //echo "sss";
            var_dump($idPic);
            $prepare2 = $this->db->conn->prepare("SELECT price_id FROM products WHERE id = ?");
            $prepare2->execute([$id]);
            //var_dump($b);
            $idPrice = $prepare2->fetch();
            $idPrice = (int)($idPrice->price_id);
            $prepare = $this->db->conn->prepare("DELETE FROM products WHERE id = ?");
            try{
                $success = $prepare->execute([$id]);
                if($success){
                    $prepare3 = $this->db->conn->prepare("DELETE FROM images WHERE id= ?");
                    $prepare3->execute([$idPic]);
                    $prepare4 = $this->db->conn->prepare("DELETE FROM price WHERE id = ?");
                    $prepare4->execute([$idPrice]);
                    $code = 204;
                }else{
                    $code = 500;
                }
            }catch(\PDOException $ex){
                $code = 409;
            }
        }catch(\PDOException $ex){
            $code = 409;
        }





        return $code;
    }
}
