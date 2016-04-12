<?php
namespace GRSelect\ValueObjects;
class Candidate extends Base {
    public $id = null;
    public $name = '';
    public $description = '';
    public $party = '';
    public $image = '';
    public $stateId = 0;
    public $districtId = 0;
    public $localeId = 0;
    public $scope = '';
    public $executive = 0;

    // legacy fields
    public $district = null;
    public $state = null;
    public $seat = null;
    public $points = array();
    public $issues = array();
}
?>