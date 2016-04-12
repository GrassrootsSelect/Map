<?php
namespace GRSelect;
class Mysql implements iData {
    protected $connection = null;
    protected $database = null;
    protected $table = null;
    protected $class = '';
    protected $cache = array();

    public function __construct(MysqlConnection $connection, $database, $table, $class) {
        $this->connection = $connection;
        $this->database = $database;
        $this->table = $table;
        $this->class = $class;
    }
    public function write() {
        foreach ($this->cache as $item) {
            $values = $item->toArray();
            $fields = array();
            foreach ($values as $key=>$v) {
                $fields[$key] = ':'.$key;
            }

            $sql = '
                '.(is_numeric($item->id) === true ? 'REPLACE' : 'INSERT').' INTO '.$this->database.'.'.$this->table.' (
                    '.implode(',', array_keys($fields)).'
                ) VALUES (
                    '.implode(',', $fields).'
                )
            ';
            $this->connection->query($sql, $values);
        }
    }

    public function all() {
        $ids = $this->connection->fields('SELECT id FROM '.$this->database.'.'.$this->table);
        $out = array();
        foreach ($ids as $id) {
            $out[$id] = $this->row($id);
        }
        return $out;
    }

    public function truncate() {
        $sql = '
            DELETE FROM '.$this->table.'.'.$this->table.'
        ';
        $this->connection->query($sql);
    }

    public function row($id) {
        if (array_key_exists($id, $this->cache) === false) {
            $data = $this->connection->row('SELECT * FROM '.$this->database.'.'.$this->table.' WHERE id = :id', array(
                'id' => $id
            ));
            if (!$data || is_array($data) === false) {
                return null;
            }
            $className = $this->class;
            $this->cache[$id] = new $className($data);
        }
        return $this->cache[$id];
    }

    public function add(array $values=null, $id=false) {
        if ($id === false) {
            $id = uniqid('p');
        }
        $className = $this->class;
        $obj = new $className($values);
        $this->cache[$id] = $obj;
        return $obj;
    }

    public function where(array $conditions) {
        $sql = '
            SELECT
              *
            FROM
              '.$this->database.'.'.$this->table.'
            WHERE
        ';

        $values = array();
        $conditionsParts = array();
        foreach ($conditions as $condition) {
            $vkey = uniqid('c');
            $values[$vkey] = $condition[1];
            $conditionsParts[] = '`'.$condition[0].'` = :'.$vkey;
        }
        $sql .= implode(' AND ', $conditionsParts);

        $dataArray = $this->connection->rows($sql, $values);
        if (is_array($dataArray) === false) {
            $dataArray = array();
        }
        $className = $this->class;
        $out = array();
        foreach ($dataArray as $data) {
            if (array_key_exists($data['id'], $this->cache) === false) {
                $this->cache[$data['id']] = new $className($data);
            }
            $out[$data['id']] = $this->cache[$data['id']];
        }
        return $out;
    }
}
?>