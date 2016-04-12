<?php
namespace GRSelect;
class Candidate extends BaseDataClass {
    public $id ='';
    public $name = '';
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