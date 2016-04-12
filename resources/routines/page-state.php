<?php
    $layoutResources = $wordpressResources();

    $data = array(

    );
    $layoutResources['content'] = $staticHtml->get('state'.DIRECTORY_SEPARATOR.'MI');


    return $templateEngine->template(
        'oneColumnLayout',
        $layoutResources
    );
?>