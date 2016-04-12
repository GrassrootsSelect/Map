<?php
    namespace GRSelect;
    class State extends BaseDataClass {
        public $id = '';
        public $name = '';
        public $abbreviation = '';
        public $extraText = '';
        public $geometry = '';

        public function getCandidates(\GRSelect\BaseDataClass $candidates) {

        }
    }
?>