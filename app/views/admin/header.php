<body>
<form enctype="multipart/form-data" name="insertForm" id="insertForm"
      method="POST" action="admin.php?page=insert" onsubmit="return checkInsert();">
    <span class="fa fa-close"></span>
    <input type="text" id="prodNameI" name="prodNameI" placeholder="Product name"/><br/>
    <select id="prodCatI" name="prodCatI">
        <option value="-1">Choose category..</option>
        <?php
        use App\Controllers\PageSectionController;
        global $database;
        $categories = new PageSectionController($database, "categories");
        $cat = $categories->getAll();
        foreach($cat as $c):
            ?>
            <option value="<?=$c->id?>"><?=$c->name?></option>
        <?php endforeach; ?>
    </select><br/>
    <label>Product price:</label><input type="text" id="prodPriceI" name="prodPriceI" placeholder="Dish price"/><br/>
    <label>Product old price:</label><input type="text" id="oldPriceI" name="oldPriceI" placeholder="Regular price"/><br/>
    <select id="hotProdI" name="hotProdI">
        <option value="0">Not hot</option>
        <option value="1">Hot</option>
    </select><br/>
    <label>Product image:</label><input type="file" name="imgProdI" id="imgProdI"/><br/>
    <p class="error"></p>
    <input type="submit" value="Insert" id="proceedIns" name="proceedIns"/>
</form>
<form enctype="multipart/form-data" name="updateForm" id="updateForm"
      method="POST" action="admin.php?page=update" onsubmit="return checkUpdate();">
    <span class="fa fa-close"></span>
    <input type="hidden" id="idProd" name="idProd"/>
    <input type="text" id="prodName" name="prodName" placeholder="Product name"/><br/>
    <select id="prodCat" name="prodCat">
        <option value="-1">Choose category..</option>
        <?php
        $categories = new PageSectionController($database, "categories");
        $cat = $categories->getAll();
        foreach($cat as $c):
            ?>
            <option value="<?=$c->id?>"><?=$c->name?></option>
        <?php endforeach; ?>
    </select><br/>
    <label>Product price:</label><input type="text" id="prodPrice" name="prodPrice" placeholder="Product price"/><br/>
    <label>Product old price:</label><input type="text" id="oldPrice" name="oldPrice" placeholder="Product old price"/><br/>
    <select id="hotProd" name="hotProd">
        <option value="0">Not hot</option>
        <option value="1">Hot</option>
    </select><br/>
    <label>Product image:</label><input type="file" name="imgProd" id="imgProd"/><br/>
    <p class="error"></p>
    <p class="success"> </p>
    <input type="submit" value="Update" id="proceedUpd" name="proceedUpd"/>
</form>
<div id="wrapper">
    <div id="helloUsername">
        <a href='admin.php?page=logout'><span class="fa fa-sign-out"></span></a>
        <p id="helloAdmin">Hello <?php echo $_SESSION['user']->username ?>&nbsp;!&nbsp;<span class='fa fa-star-o'></span></p>
    </div>
