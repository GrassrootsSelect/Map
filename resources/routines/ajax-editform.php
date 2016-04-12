<?php

// lol
$states = \GRSelect\DataFactory::database(
    'localhost',
    MYSQL_DB_USER,
    MYSQL_DB_PASS,
    'grs_data',
    'states',
    'State'
);
$candidates = \GRSelect\DataFactory::database(
    'localhost',
    MYSQL_DB_USER,
    MYSQL_DB_PASS,
    'grs_data',
    'candidates',
    'Candidate'
);
$votingDates = \GRSelect\DataFactory::database(
    'localhost',
    MYSQL_DB_USER,
    MYSQL_DB_PASS,
    'grs_data',
    'votingDates',
    'VotingDate'
);
$districts = \GRSelect\DataFactory::database(
    'localhost',
    MYSQL_DB_USER,
    MYSQL_DB_PASS,
    'grs_data',
    'districts',
    'District'
);
$issues = \GRSelect\DataFactory::database(
    'localhost',
    MYSQL_DB_USER,
    MYSQL_DB_PASS,
    'grs_data',
    'issues',
    'Issue'
);
$resources = \GRSelect\DataFactory::database(
    'localhost',
    MYSQL_DB_USER,
    MYSQL_DB_PASS,
    'grs_data',
    'resources',
    'Resource'
);

$html = 'invalid item or type';


$item = explode('-', $item);
if (count($item) != 2) {
    return $html;
}

switch ($item[0]) {
    case 'Candidate':
        $candidate = $candidates->row($item[1]);
        $data = $candidate->toArray();
        $data['states'] = $states->all();
        $data['districts'] = $districts->all();
        $data['candidates'] = $candidates->all();
        $data['locales'] = array();
        $html = $templateEngine->template(
            'edit-candidate',
            $data
        );
        break;
    case 'District':
        $district = $districts->row($item[1]);
        $data = $district->toArray();
        $data['states'] = $states->all();
        $html = $templateEngine->template(
            'edit-district',
            $data
        );
        break;
    case 'Issue':
        $candidate = $candidates->row($item[1]);
        $data = $candidate->toArray();
        $data['states'] = $states->all();
        $data['districts'] = $districts->all();
        $data['candidates'] = $candidates->all();
        $data['locales'] = array();
        $html = $templateEngine->template(
            'edit-candidate',
            $data
        );
        break;
    case 'Resource':
        $candidate = $candidates->row($item[1]);
        $data = $candidate->toArray();
        $data['states'] = $states->all();
        $data['districts'] = $districts->all();
        $data['candidates'] = $candidates->all();
        $data['locales'] = array();
        $html = $templateEngine->template(
            'edit-candidate',
            $data
        );
        break;
    case 'State':
        $state = $states->row($item[1]);
        $data = $state->toArray();
        $html = $templateEngine->template(
            'edit-state',
            $data
        );
        break;
    case 'VotingDate':
        $candidate = $candidates->row($item[1]);
        $data = $candidate->toArray();
        $data['states'] = $states->all();
        $data['districts'] = $districts->all();
        $data['candidates'] = $candidates->all();
        $data['locales'] = array();
        $html = $templateEngine->template(
            'edit-candidate',
            $data
        );
        break;
}

return $html;
?>