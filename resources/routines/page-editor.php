<?php
    $layoutResources = $wordpressResources();

    $layoutResources['css'] = array(
        'jstree/style.min',
        'select2/select2.min',
        'editor'
    );
    $layoutResources['js'] = array(
        'jstree/jstree.min',
        'select2/select2.full.min',
        'editor'
    );
    $layoutResources['content'] = $templateEngine->template(
        'edit-wrapper',
        array()
    );
    return $templateEngine->template(
        'oneColumnLayout',
        $layoutResources
    );
?>