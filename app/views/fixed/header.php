<body>
<div id="header">
    <div id="headerHolder">
        <div id="socialNetworks" class="widthHeaderElements">
            <ul id="socialList">

            </ul>
        </div>
        <h1 id="logo" class="widthHeaderElements"><a href="index.php">Cool <span class="fa fa-star"></span> Stuff</a></h1>
        <div id="logReg" class="widthHeaderElements">
            <ul id="logRegList">
                <?php
                 if($loggedIn == true){
                     $username=$_SESSION['user']->username;
                     echo("<li><a href='#' id='hello'>".$username."&nbsp;"."<span class='fa fa-heart-o'></span></a></li>
                                    <li><a href='index.php?page=logout'>Logout?</a></li>");
                 }else{
                ?>
                <li><a href="#" id="logLink">Log In</a></li>
                <li><a href="#" id="regLink">Sign Up</a></li>
                 <?php } ?>
                <?php if($loggedIn == true):?>
                <li><a href="#" id="wishlistLink"><span class="fa fa-shopping-bag"></span> My Wishlist</a></li>
                <?php endif; ?>
            </ul>
            <form id="registrationForm" action="index.php?page=registration" method="POST">
                <table>
                    <tr><td colspan="2"><span class="fa fa-close x"></span></td></tr>
                    <tr><td colspan="2"><p id="headlineReg">Registration</p></td></tr>
                    <tr><td colspan="2"><input type="text" id="regName" name="regName" placeholder="Username *"/><p class="error" id="nameError"></p></td></tr>
                    <tr><td colspan="2"><input type="password" id="regPass" name="regPass" placeholder="Password *"/><p class="error" id="passError"></p></td></tr>
                    <tr><td colspan="2"><input type="password" id="regPass1" name="regPass1" placeholder="Type in password again *"/><p class="error" id="regPass1Error"></p></td></tr>
                    <tr><td><input type="text" id="email" name="email" placeholder="E-mail *"/><p class="error" id="mailError"></p></td>
                        <td class="marginTd"><input type="text" id="tel" name="tel" placeholder="Phone *"/><p class="error" id="telError"></p></td></tr>
                    <tr><td>Gender: *<p class="error" id="genderError"></p></td><td><input type="radio" id="female" name="gender" value="f"/>Female<input type="radio" id="male" name="gender"value="m"/>Male</td></tr>
                    <tr><td colspan="2"><input type="town" id="town" name="town" placeholder="Town"/><p class="error" id="townError"></p></td></tr>
                    <tr><td><input type="text" id="address" name="address" placeholder="Address"/><p class="error" id="addrError"></p></td>
                        <td class="marginTd"><input type="text" id="zip" name="zip" placeholder="Zip"/><p class="error" id="zipError"></p></td></tr>
                    <tr><td colspan="2"><input type="checkbox" id="chbMail" checked="checked "value="send" name="chbMail"/>Send news to email</td></tr>
                    <tr><td colspan="2"><input type="button" id="btnRegistration" value="Registrate"/></td></tr>
                    <tr><td><p class="success"></p></td></tr>
                </table>
            </form>

            <form method="POST" action="index.php?page=login" id="logForm" onSubmit=" return logIn();">
                <span class="fa fa-close x"></span>
                <input type="text" name="logUsername" id="logUsername" placeholder="Name"/><p class="error" id="logUsernameError"></p>
                <input type="password" name="logPass" id="logPass" placeholder="Password"/><p class="error" id="logPassError"></p>
                <input type="submit" name="logIn" id="logIn" value="Log in" />

                <p class="error" id="logError">

                    <?php
                    if(isset($_SESSION['errors'])){
                        echo($_SESSION['errors']);
                    }
                    ?>

                </p>
            </form>

            <div id="cart">

            </div>
        </div>
    </div>
    <div id="menu">
        <ul id="menuList">
            <?php
            use App\Controllers\PageSectionController;
            global $database;
            $menu= new PageSectionController($database, "menu");
            $m=$menu->getAll();
            $categories = new PageSectionController($database, "categories");
            $c = $categories->getAll();
            $i=0;
            foreach($m as $item) {
                ?>
                <?php
                if($item->text=="Home"){
                    $i++;
                    ?>
                    <li class="active"><a href="<?= $item->href ?>" data-active="<?= $i ?>"><?= $item->text ?></a></li>
                    <?php   foreach($c as $catItem){  $i++; ?>
                        <li><a href="index.php?category=<?=$catItem->id?>" data-id="<?=$catItem->id?>" data-active="<?= $i ?>"><?=$catItem->name?></a></li>

                    <?php   }?>
                <?php }else{  $i++; ?>
                    <li><a href="<?= $item->href ?>" data-active="<?= $i ?>"><?= $item->text?></a></li>
                <?php
                } }?>
            <?php
            if($loggedIn == true){
                if($_SESSION['user']->role_id == 1) {
                    echo("<li><a href='admin.php' data-active=\"<?= $i ?>\">Admin</a></li>");
                }
            }
            ?>
        </ul>
    </div>
</div>
