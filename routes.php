<?php
    $routePrefix = ''; // if you need to add /public to the route for servers without the docroot pointed at /public

    $routineGenerator = new GRSelect\RoutineGenerator(RESOURCE_PATH.'routines'.DIRECTORY_SEPARATOR, array(
        'templateEngine'=>new \grmule\tpldotphp\PhpEngine(
            [
                TEMPLATE_PATH
            ],
            null,
            true
        ),
        'candidates'=>GRSelect\DataFactory::json(DATA_PATH.'primaryCandidates.json', '\GRSelect\Candidate'),
        'states'=>GRSelect\DataFactory::json(DATA_PATH.'states.json', '\GRSelect\State'),
        'districts'=>GRSelect\DataFactory::json(DATA_PATH.'districts.json', '\GRSelect\District'),
        'staticHtml'=>new \GRSelect\StaticHtml(BASE_PATH.'resources'.DIRECTORY_SEPARATOR.'html'.DIRECTORY_SEPARATOR)
    ));

    // utility to bootstrap wordpress and load the header/footer
    $routineGenerator->addResource('wordpressResources', $routineGenerator->generate('utility-getWordpressResources', array()));

    $app->get($routePrefix.'/', $routineGenerator->generate('page-index', array()));
    $app->get($routePrefix.'/map/', $routineGenerator->generate('page-map', array()));
    $app->get($routePrefix.'/state/{state}', $routineGenerator->generate('page-state', array('state')));

    $app->get($routePrefix.'/editor/', $routineGenerator->generate('page-editor', array()));
    $app->get($routePrefix.'/editform/{item}', $routineGenerator->generate('ajax-editform', array('item')));
    $app->get($routePrefix.'/tree/{item}', $routineGenerator->generate('ajax-treeloader', array('item')));
    $app->get($routePrefix.'/import/', $routineGenerator->generate('utility-import', array()));


    $app->get($routePrefix.'/static/{subject}/{item}/', $routineGenerator->generate('resource-static-html', array(
        'subject',
        'item'
    )));

    $app->get($routePrefix.'/addressLookup/{address}', $routineGenerator->generate('ajax-lookupAddress', array('address')));
    $app->get($routePrefix.'/lookupState/{address}', $routineGenerator->generate('ajax-lookupState', array('address')));

    $app->get($routePrefix.'/generate/{subject}', $routineGenerator->generate('utility-generateStaticResourcesForMapPages', array('subject')));

?>