            <div id="footer">
                <div id="footerPic">
                    <div id="footCont">
                        <?php
                        use App\Controllers\PageSectionController;
                        global $database;
                        $footer = new PageSectionController($database, "general_info");
                        $f = $footer->getAll();
                        // $infos=getGeneralInfo();
                        foreach($f as $item):
                            ?>
                            <div class="information">
                                <h4><?= $item->headline?> <span class="fa
                        <?php
                                    if($item->headline == "Contact"){
                                        $fa = " fa-phone";
                                    }else if($item->headline == "Headquarters Address"){
                                        $fa = " fa-address-book";
                                    }
                                    ?><?=$fa?>"></span></h4>
                                <?=$item->info?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <p id="copyright">Copyright ©&nbsp;&nbsp;Cool&nbsp;&nbsp;Stuff&nbsp;&nbsp;2020 | <a href="dok.pdf">Documentation</a> | <a href="#">GitHub</a></p>
                </div>
            </div>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script type="text/javascript" src="app/assets/js/main.js"></script>

    </body>
 </html>
