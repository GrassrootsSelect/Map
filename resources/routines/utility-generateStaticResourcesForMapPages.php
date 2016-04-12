<?php
print '<pre>';

    $staticResourcePath = BASE_PATH.'resources'.DIRECTORY_SEPARATOR.'html'.DIRECTORY_SEPARATOR.$subject.DIRECTORY_SEPARATOR;
    $result = 'undefined error';
    switch ($subject) {
        case 'statePreview':
            $states = \GRSelect\DataFactory::database(
                'localhost',
                'root',
                '***REMOVED***',
                'grs_data',
                'states',
                'State'
            );
            $candidates = \GRSelect\DataFactory::database(
                'localhost',
                'root',
                '***REMOVED***',
                'grs_data',
                'candidates',
                'Candidate'
            );
            $votingDates = \GRSelect\DataFactory::database(
                'localhost',
                'root',
                '***REMOVED***',
                'grs_data',
                'votingDates',
                'VotingDate'
            );

            $result = array();
            foreach ($states->all() as $state) {

                $candidateObjects = $candidates->where(array(
                    array('stateId', $state->id)
                ));

                $counts = array(
                    'national' => 0,
                    'state' => 0,
                    'district' => 0,
                    'locale' => 0
                );
                foreach ($candidateObjects as $candidate) {
                    $counts[$candidate->scope]++;
                }

                $phrases = array();
                if ($counts['national'] > 0) {
                    $phrases[] = $counts['national'] . ' federal seat' . ($counts['state'] > 1 ? 's' : '') . ' up for election';
                }
                if ($counts['state'] > 0) {
                    $phrases[] = $counts['state'] . ' state seat' . ($counts['state'] > 1 ? 's' : '') . ' up for grabs';
                }
                if ($counts['district'] > 0) {
                    $phrases[] = $counts['district'] . ' open district-level position' . ($counts['state'] > 1 ? 's' : '') . '';
                }
                if ($counts['locale'] > 0) {
                    $phrases[] = $counts['locale'] . ' local race' . ($counts['state'] > 1 ? 's' : '') . '';
                }
                if (count($phrases) > 0) {
                    $phrases[count($phrases) - 1] = 'and ' . $phrases[count($phrases) - 1];
                }

                $stateVotingDates = $votingDates->where(array(
                    array('stateId', $state->id),
                    array('scope','state')
                ));

                file_put_contents($staticResourcePath.$state->abbreviation.'.html', $templateEngine->template(
                    'static-statePreview',
                    array(
                        'stateName' => $state->name,
                        'raceCountdown' => implode(', ', $phrases),
                        'issueCount' => 4,
                        'resourceCount' => 3,
                        'dates' => $stateVotingDates
                    )
                ));
            }
            $result = 'We\'re good!';

            break;
        default:
            $result = 'error: unknown type, did not generate';
    }



    return $result;
?>