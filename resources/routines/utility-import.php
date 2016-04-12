<?php
    // imports states and districts from json data used to generate the maps
    $stateData = file_get_contents(BASE_PATH.'public'.DIRECTORY_SEPARATOR.'js'.DIRECTORY_SEPARATOR.'us-states-extended.json');
    $stateData = json_decode($stateData, true);
    $stateData = array_pop($stateData);

print '<pre>';

    $states = \GRSelect\DataFactory::database(
        'localhost',
        MYSQL_DB_USER,
        MYSQL_DB_PASS,
        'grs_data',
        'states',
        'State'
    );
    $states->truncate();

    foreach ($stateData as $state) {
        //var_dump($state);
        $states->add(array(
            'abbreviation'=>$state['properties']['abbr'],
            'name'=>$state['properties']['name'],
            'id'=>$state['id'],
            'geometry'=>json_encode($state['geometry'])
        ));
    }

    $states->write();

    $districtData = file_get_contents(BASE_PATH.'public'.DIRECTORY_SEPARATOR.'js'.DIRECTORY_SEPARATOR.'us-congress-113.json');
    $districtData = json_decode($districtData, true);
    $districtData = $districtData['objects']['districts']['geometries'];


    $districts = \GRSelect\DataFactory::database(
        'localhost',
        MYSQL_DB_USER,
        MYSQL_DB_PASS,
        'grs_data',
        'districts',
        'District'
    );
    $districts->truncate();


    foreach ($districtData as $district) {
        $id = substr($district['id'], 0, 2);
        $stateId = substr($district['id'], 2, 2);
        $districts->add(array(
            'stateId'=>intval($stateId),
            'number'=>$id,
            'scope'=>'federal'
        ));
    }

    $districts->write();

    return 'out';

?>