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
$hasStreetNumber = false;
foreach ($firstLocationResult['address_components'] as $addressResults) {
    foreach ($addressResults['types'] as $resultType) {
        if ($resultType == 'street_number') {
            $hasStreetNumber = true;
            break;
        }
    }
}

if ($hasStreetNumber !== true) {
    die(json_encode(array(
        'status'=>'ERROR',
        'message'=>'Sorry, the address you entered could not be precisely located. Check the spelling, and ensure it is your complete address.'
    )));
}

$location = $firstLocationResult['geometry']['location'];

$districtLocationRawData = file_get_contents('http://congress.api.sunlightfoundation.com/districts/locate?latitude='.$location['lat'].'&longitude='.$location['lng'].'&apikey='.SUNLIGHT_FOUNDATION_API_KEY);
$districtLocationData = @json_decode(
    $districtLocationRawData,
    true
);

if (is_array($districtLocationData) === false || $districtLocationData['count'] < 1) {
    die(json_encode(array(
        'status'=>'ERROR',
        'message'=>'There was a problem looking up that address. There might be a problem with one of our lookup services, but make sure it is entered correctly.  (e02)'
    )));
}

$firstPollingLocationResult = array_shift($districtLocationData['results']);

$theState = $states->row($firstPollingLocationResult['state']);

die(json_encode(array(
    'status'=>'OK',
    'message'=>$templateEngine->template(
        'districtInfo',
        array(
            'rawDistrict'=>$firstPollingLocationResult['district'],
            'stateName'=>$theState->name,
            'gsState'=>false,
            'gsDistrict'=>false,
            'hasSenate'=>false,
            'hasHouse'=>false,
            'baseUrl'=>BASE_URL
        )
    ),
)));
?>