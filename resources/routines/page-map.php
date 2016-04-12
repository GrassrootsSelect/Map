<?php
    $layoutResources = $wordpressResources();

    $data = array(

    );
    $layoutResources['content'] = $templateEngine->template(
        'map',
        $data
    );
    $layoutResources['css'] = 'map';
    $layoutResources['js'] = array(
        "//d3js.org/d3.v3.min",
        "//d3js.org/queue.v1.min",
        "//d3js.org/topojson.v1.min",
        'map',
    );
    return $templateEngine->template(
        'oneColumnLayout',
        $layoutResources
    );
?>