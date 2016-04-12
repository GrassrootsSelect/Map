<?php
namespace GRSelect\ValueObjects;
class Issue extends Base {
    public $id = null;
    public $title = '';
    public $description = '';
    public $stateId = 0;
    public $candidateId = 0;
    public $districtId = 0;
    public $localeId = 0;
    public $type = '';
    public $scope = '';
}
?>