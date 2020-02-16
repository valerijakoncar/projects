<div class="product" id="<?=$product->id?>">
    <?php
    if($product->hot){
        echo " <div class=\"hot\">Hot !</div>";
    }
    ?>
    <span class="fa fa-shopping-bag"></span>
    <img class="prodPic" alt="<?=$product->alt?>" src="<?php echo $product->path."". $product->picName?>"/>
    <div class="prodData">
        <h2 class="prodName"><?=$product->name?></h2>
        <span class="prodPrice"><?=$product->price?><span class="dollar">$</span></span>
        <?php
        if($product->oldPrice > 1){
            ?>
            <del><?=$product->oldPrice?></del>
        <?php } ?>
    </div>
</div>
