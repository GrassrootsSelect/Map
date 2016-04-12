<?php $uid = uniqid(); ?><!DOCTYPE html>
<html lang="en-US">
<head>
    <meta name='viewport' content="width=device-width, initial-scale=1" />
    <title>Grassroots Select</title>
    <?php
        include $data["includeHeaderPath"];
    ?>
    <?php
    if (array_key_exists('css', $data) == true) {
        if (is_array($data['css']) === true) {
            foreach ($data['css'] as $css) {
                print '<link rel="stylesheet" href="'.(strpos($css, '//') === false ? $data["baseUrl"].'css/' : '').$css.'.css'.(strpos($css, '?') === false ? '?'.$uid : '').'" type="text/css" media="all" />';
            }
        } else {
            print '<link rel="stylesheet" href="'.(strpos($data['css'], '//') === false ? $data["baseUrl"].'css/' : '').$data['css'].'.css'.(strpos($data['css'], '?') === false ? '?'.$uid : '').'" type="text/css" media="all" />';
        }
    }
    if (array_key_exists('js', $data) == true) {
        if (is_array($data['js']) === true) {
            foreach ($data['js'] as $js) {
                print '<script type="text/javascript" src="'.(strpos($js, '//') === false ? $data["baseUrl"].'js/' : '').$js.'.js'.(strpos($js, '?') === false ? '?'.$uid : '').'" ></script>';
            }
        } else {
            print '<script type="text/javascript" src="'.(strpos($data['js'], '//') === false ? $data["baseUrl"].'css/' : '').$data['js'].'.js'.(strpos($data['js'], '?') === false ? '?'.$uid : '').'"></script>';
        }
    }
    ?>
</div><?php //ending .header wrapper established in included WP header ?>

    <div id="content-container" class="content-boxed layout-right">
        <div style="max-width: 100%;" id="content" class="content">
            <article id="post-24" class="blog-non-single-post theme-post-entry post-24 post type-post status-publish format-standard hentry category-uncategorized">
                <div id="content-wrapper" class="static-body post-content no-thumbnail">
                    <?=($data['content'])?>
                </div>
                <div class="clear"></div>
            </article>
        </div>
        <div class="clear"></div>
    </div>
    <div class="clear"></div>
</div><?php //ending #page-wrapper ?>
<?php
    include $data["includeFooterPath"];
?>
