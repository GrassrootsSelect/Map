<?php
$address = urldecode($address);
$data = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($address).'&key='.GOOGLE_MAPS_API_KEY);
$geodata = @json_decode(
    $data, true
);

if (is_array($geodata) === false || $geodata['status'] != 'OK') {
    die(json_encode(array(
        'status'=>'ERROR',
        'message'=>'There was a problem looking up that address. There might be a problem with one of our lookup services, but make sure it is entered correctly. (e01)'
    )));
}

$firstLocationResult = array_shift($geodata['results']);
$hasState = false;
foreach ($firstLocationResult['address_components'] as $addressResults) {
    foreach ($addressResults['types'] as $resultType) {
        if ($resultType == 'administrative_area_level_1') {
            $hasState = $addressResults['short_name'];
            break;
        }
    }
}

if ($hasState === false) {
    die(json_encode(array(
        'status'=>'ERROR',
        'message'=>'Sorry, the zip you entered could not be located. Check for typos.'
    )));
}
die(json_encode(array(
    'status'=>'OK',
    'state'=>$hasState
)));
?>