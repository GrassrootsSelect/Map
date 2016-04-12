<?php
header('Content-Type: application/json');

if ( $_GET["id"] === "#" ) {
    $data = array(
        array( "id" => "Candidate", "parent" => "#", "text" => "Candidate" , "children" => true ),
        array( "id" => "District", "parent" => "#", "text" => "District", "children" => true ),
        array( "id" => "Issue", "parent" => "#", "text" => "Issue", "children" => true ),
        array( "id" => "Resource", "parent" => "#", "text" => "Resource", "children" => true ),
        array( "id" => "State", "parent" => "#", "text" => "State", "children" => true ),
        array( "id" => "VotingDate", "parent" => "#", "text" => "Voting Date", "children" => true ),
    );
} else {
    $item = $_GET["id"];
    $data = false;
    switch ($item) {
        case 'Candidate':
            $table= \GRSelect\DataFactory::database(
                'localhost',
                MYSQL_DB_USER,
                MYSQL_DB_PASS,
                'grs_data',
                'candidates',
                'Candidate'
            );
            $nameField = 'name';
            break;
        case 'District':
            $table= \GRSelect\DataFactory::database(
                'localhost',
                MYSQL_DB_USER,
                MYSQL_DB_PASS,
                'grs_data',
                'districts',
                'District'
            );
            $states= \GRSelect\DataFactory::database(
                'localhost',
                MYSQL_DB_USER,
                MYSQL_DB_PASS,
                'grs_data',
                'states',
                'State'
            );
            $nameField = 'number';
            $data = array();
            foreach ($table->all() as $one) {
                $state = $states->row(intval($one->stateId));
                if (!$state) {
                    continue;
                }
                $data[$state->abbreviation.' '.$one->$nameField] = array('id' => $item . '-' . $one->id, 'parent' => $item, 'text' =>$state->abbreviation.' '.$one->$nameField, 'children'=>false);
            }
            ksort($data);
            $data = array_values($data);
            break;
        case 'Issue':
            $table= \GRSelect\DataFactory::database(
                'localhost',
                MYSQL_DB_USER,
                MYSQL_DB_PASS,
                'grs_data',
                'issues',
                'Issue'
            );
            $nameField = 'title';
            break;
        case 'Resource':
            $table= \GRSelect\DataFactory::database(
                'localhost',
                MYSQL_DB_USER,
                MYSQL_DB_PASS,
                'grs_data',
                'resources',
                'Resource'
            );
            $nameField = 'title';
            break;
        case 'State':
            $table = \GRSelect\DataFactory::database(
                'localhost',
                MYSQL_DB_USER,
                MYSQL_DB_PASS,
                'grs_data',
                'states',
                'State'
            );
            $nameField = 'name';
            break;
        case 'VotingDate':
            $table= \GRSelect\DataFactory::database(
                'localhost',
                MYSQL_DB_USER,
                MYSQL_DB_PASS,
                'grs_data',
                'votingDates',
                'VotingDate'
            );
            $nameField = 'headline';
            break;
    }
    if ($data === false) {
        $data = array();
        foreach ($table->all() as $one) {
            $data[] = array('id' => $item . '-' . $one->id, 'parent' => $item, 'text' => $one->$nameField, 'children' => false);
        }
    }
}

echo json_encode( $data);
die();
?>