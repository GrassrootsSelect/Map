<?php
    $subject = str_replace('.', '_', $subject);
    $subject = str_replace(DIRECTORY_SEPARATOR, '_', $subject);

    $item = str_replace('.', '_', $item);
    $item = str_replace(DIRECTORY_SEPARATOR, '_', $item);

    $filePath = BASE_PATH.'resources'.DIRECTORY_SEPARATOR.'html'.DIRECTORY_SEPARATOR.$subject.DIRECTORY_SEPARATOR.$item.'.html';
    if (file_exists($filePath) === false) {
        return 'Sorry, the static resource "'.($subject.DIRECTORY_SEPARATOR.$item).'" you requested is not available';
    }
    return file_get_contents($filePath);
?>