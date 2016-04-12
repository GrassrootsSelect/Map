<?php
namespace GRSelect;
class RoutineGenerator {
    private $path = '';
    private $resources = array();
    private $list = array();

    public function __construct($path, array $resources=array()) {
        $this->path = $path;
        $this->resources = $resources;
    }

    private function buildList() {
        foreach (new \DirectoryIterator($this->path) as $fileInfo) {
            if (
                $fileInfo->isDot() === true ||
                $fileInfo->getExtension() != 'php'
            ) {
                continue;
            }
            $this->list[$fileInfo->getBasename('.'.$fileInfo->getExtension())] = $fileInfo->getPath().DIRECTORY_SEPARATOR.$fileInfo->getBasename();
        }
    }

    public function addResource($name, $resource) {
        $this->resources[$name] = $resource;
    }

    public function generate($name, array $argumentNames=array()) {
        if (is_string($name) === false) {
            throw new \Exception('Invalid routine name');
        }
        if (count($this->list) === 0) {
            $this->buildList();
        }

        if (array_key_exists($name, $this->list) === false) {
            throw new \Exception('Routine "'.$name.'" not found');
        }

        $resources = $this->resources;
        $filename = $this->path.$name.'.php';
        if (count($argumentNames) > 0) {
            $functionString = '
                    $out = function ($' . implode(', $', $argumentNames) . ') use ($resources, $filename) {
                        extract($resources);
                        return include $filename;
                    };
                ';
        } else {
            $functionString = '
                    $out = function () use ($resources, $filename) {
                        extract($resources);
                        return include $filename;
                    };
                ';
        }
        eval($functionString);
        return $out;
    }
}


?>