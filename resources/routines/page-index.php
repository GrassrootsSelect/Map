<?php
    include BASE_PATH."public/blog/wp-blog-header.php";
    $homeUrl = home_url();
    $templateDir = get_bloginfo('template_directory');
    $themeRelativePath = substr_replace($templateDir, "", 0, strlen($homeUrl));
    $id=131;
    $post = get_post($id);

    return $templateEngine->template(
        'index',
        array(
            'includeHeaderPath'=>BASE_PATH.'public/blog/'.$themeRelativePath.'/header.php',
            'includeFooterPath'=>BASE_PATH.'public/blog/'.$themeRelativePath.'/footer.php',
            'sidebar'=>$templateEngine->template('sidebar'),
            'content'=>$post->post_content,
            'baseUrl'=>BASE_URL
        )
    );
?>