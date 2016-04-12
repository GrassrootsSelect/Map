<?php
    namespace GRSelect;
    interface iData{
        public function write();
        public function row($id);
        public function add(array $values=null, $id=false);
        public function where(array $conditions);
    }
?>