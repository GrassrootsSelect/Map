<?php
include BASE_PATH."public/blog/wp-blog-header.php";
$homeUrl = home_url();
$templateDir = get_bloginfo('template_directory');
$themeRelativePath = substr_replace($templateDir, "", 0, strlen($homeUrl));
return array(
    'includeHeaderPath'=>BASE_PATH.'public/blog/'.$themeRelativePath.'/header.php',
    'includeFooterPath'=>BASE_PATH.'public/blog/'.$themeRelativePath.'/footer.php',
    'baseUrl'=>BASE_URL
);
?>