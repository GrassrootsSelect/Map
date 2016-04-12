<!DOCTYPE html>
<html lang="en-US">
<head>
    <title>Grassroots Select</title>
    <?php
    include $data["includeHeaderPath"];
    ?>
    <link rel="stylesheet" href="<?=($data["baseUrl"])?>css/index.css" type="text/css" media="all" />

</div><?php //ending .header wrapper established in included WP header ?>

    <div id="content-container" class="content-boxed layout-right">
        <div id="content" class="content">
            <article id="post-24" class="blog-non-single-post theme-post-entry post-24 post type-post status-publish format-standard hentry category-uncategorized">
                <div class="static-body post-content no-thumbnail">
                    <?=($data['content'])?>
                </div>
                <div class="clear"></div>
            </article>
        </div>
        <?=($data["sidebar"])?>
    </div>
    <div class="clear"></div>
    </div><?php //ending #page-wrapper ?>
<?php
    include $data["includeFooterPath"];
?>
