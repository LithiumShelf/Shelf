<section data-role="page">
    <div data-role="header">
        <h1>Shelf</h1>
    </div><!-- /header -->
    <div data-role="navbar" data-grid="c" data-position="fixed">
        <ul>
            <?php
                //if(!$url){
                //    $url = $_GET['url'];
                //}

                //Print the pages to the navigation menu.
                for ($x=0; $x<4; $x++){?>
                    <li><a href="<?= strtolower($pages[$x]) ?>"
                    <?php if(strtolower($pages[$x]) == strtolower($page)){echo 'class="ui-btn-active"';};?>><?= ucwords($pages[$x]) ?></a></li>
                <?php        
                }
            ?>

        </ul>
   </div><!-- /navbar -->
