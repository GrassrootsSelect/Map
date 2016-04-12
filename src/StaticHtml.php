<?php
    namespace GRSelect;
    class StaticHtml {
        private $path;
        public function __construct ($path) {
            $this->path = $path;
        }
        public function get($name) {
            if (file_exists($this->path.$name.'.html') === false) {
                return '';
            }
            return file_get_contents($this->path.$name.'.html');
        }
    }
?>