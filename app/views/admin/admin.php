<div id="prodAdm">
    <div id="absProd"><div id="headlineProd"><h1>Products</h1></div>
        <input type="button" value="New +" id="insertDish" name="insertProd"/>
        <div id="searchHolderA">
            <span class="fa fa-search"></span>
            <input type="search" id="searchBtnA" name="searchBtnA" autocomplete="on" placeholder="Please search here..."/>
        </div>
        <table id="tableProd">
            <tr>
                <th>Id</th>
                <th>Dish</th>
                <th>Name</th>
                <th>Price</th>
                <th>Modify or Delete?</th>
            </tr>
            <?php
                use App\Controllers\ProductsController;
                global $database;
                $controller = new ProductsController($database);
                $products = $controller->getProductsAdmin();
                foreach($products as $p):
            ?>
            <tr class='p'>
                <td class='idProd'><?=$p->id?></td>
                <td><div class="picHold"><img src='<?=$p->path."".$p->picName?>' alt='<?=$p->alt?>' class='smallPic'/>
                    <?php
                    if($p->hot){
                    ?>
                    <div class="hotAdm">Hot !</div>
                <?php }?>
                    </div></td>
                <td>
                    <p class='prodNameA'><?=$p->name?></p></td><td><p class='price'><?=$p->price?>$</p>
                    <?php
                    if($p->oldPrice > 1){
                    ?>
                    <del><?=$p->oldPrice?></del>
                <?php } ?>
                <td><input type='button' class='btnUpd' data-id='<?=$p->id?>' value='Update'/><br/>
                    <input type='button' class='btnDel' onClick='deleteDish()' data-id='<?=$p->id?>' value='Delete'/>
                </td>
            </tr>

            <?php endforeach; ?>
        </table>
        <div id="paginationA">
            <ul>
                <?php
                $controller1 = new ProductsController($database);
                $numOfPages = $controller1->paginationCountA();
                for($i = 0; $i < $numOfPages; $i++):
                ?>
                <li>
                    <a href="#" class="paginationLinksA" data-limit="<?= $i ?>"><?= $i+1 ?></a>
                </li>
                <?php endfor;   ?>
            </ul>
        </div>
    </div>

</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="app/assets/js/admin.js" type="text/javascript"></script>
