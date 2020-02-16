<div id="wrapper">
            <div id="mainContent">
                <div id="leftWrapper">
                    <div id="left">
                        <div id="priceSortHolder">
                            <select id="priceSort" name="priceSort">
                                <option value="0">Sort by price</option>
                                <option value="ASC">Ascending</option>
                                <option value="DESC">Descending</option>
                            </select>
                            <!--<span class="fa fa-angle-down"></span>-->
                        </div>
                        <ul id="leftCategories">
                            <?php
                            foreach($c as $catItem){
                                ?>
                                <li><a href="#" data-id="<?=$catItem->id?>"><?=$catItem->name?><span class="fa fa-angle-right"></span></a></li>
                            <?php } ?>
                        </ul>
                        <div id="priceRange">
                            <label for="price">Price :</label><br/>
                            <input type="range" id="price" name="price" min="14" max="60"/>
                            <span id="priceValue"></span>
                        </div>
                    </div>
                </div>
                <div id="products">
                    <div id="searchHolder">
                        <span class="fa fa-search"></span>
                        <input type="search" id="searchBtn" name="searchBtn" autocomplete="on" placeholder="Please search here..."/>
                    </div>
                    <div id="prod">
                        <?php
                        use App\Controllers\ProductsController;
                        global $database;
                        if(isset($_GET['category'])){
                            $products = new ProductsController($database);
                            $result = $products->getCategoryProducts($_GET['category']);
                        }else {
                            $products = new ProductsController($database);
                            $limit = (isset($_GET['limit'])) ? $_GET['limit'] : 0;
                            $result = $products->getProducts($limit);
                        }
                            $i = 0;
                            $resultLength = count($result);
                            foreach($result as $product):
                                $i++;
                                $shouldOpen = ($i-1) % 4 == 0;
                                if(($i == 1) || $shouldOpen){
                                    echo "<div class=\"prodRow\">";
                                }
                                include "app/views/partials/product.php";
                            if($i % 4 == 0){
                                echo "</div>";
                            }else if(($resultLength % 4 != 0) && ($i == $resultLength)){
                                echo "</div>";
                            }
                            endforeach;
                          ?>
                    </div>
                    <div id="paginationDiv">
                        <ul id="pagination">
                            <?php
                            if(!isset($_GET['category'])){
                            global $i;
                            //var_dump($products);
                            $numOfPages = $products->paginationCount();
                            for($i = 0; $i < $numOfPages; $i++):
                                ?>
                                <li>
                                    <a href="#" class="paginationLinks" data-limit="<?= $i ?>"><?= $i+1 ?></a>
                                </li>
                            <?php endfor;  } ?>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
