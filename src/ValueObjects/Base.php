<?php
namespace GRSelect\ValueObjects;
abstract class Base implements \ArrayAccess {
    final public function  __construct($data) {
        $this->set($data);
    }
    final public function toArray() {
        return get_object_vars($this);
    }
    final public function set(array $values) {
        foreach ($values as $k=>$v) {
            if (property_exists(get_class($this), $k) === true) {
                $this->$k = $v;
            }
        }
    }

    public function offsetExists($offset) {
        return isset($this->$offset);
    }

    public function offsetGet($offset) {
        return $this->$offset;
    }

    public function offsetSet($offset , $value) {
        $this->$offset = $value;
    }

    public function offsetUnset($offset) {
        unset($this->$offset);
    }
}
?>