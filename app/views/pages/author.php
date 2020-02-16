<div id="wrapper">
    <div id="author">
        <h2 id="headlineAuthor">Author</h2>
        <div id="author1">
            <div id="picAuthor"></div>
            <div id="authorAbout">
                <?php
                global $database;
                 use App\Controllers\PageSectionController;
                 $inf = new PageSectionController($database, "author");
                 $info = $inf->getAll();
                 //var_dump($info);
                 echo $info->text;
                ?>
            </div>

        </div>
    </div>
</div>
