<?php
    namespace GRSelect;
    class DataFactory {
        static $cache = array();
        static function json ($path, $class) {
            if (array_key_exists($path, self::$cache) === true) {
                return self::$cache[$path];
            }
            self::$cache[$path] = new Json(
                $path,
                $class
            );
            return self::$cache[$path];
        }

        static function database(
            $host,
            $user,
            $pass,
            $database,
            $table,
            $class
        ){
            $class = __NAMESPACE__.'\\ValueObjects\\'.$class;
            $connectionCacheKey = sha1($host.$user.$pass);
            if (array_key_exists($connectionCacheKey, self::$cache) == false) {
                $connection = new MysqlConnection($host, $user, $pass, $database);
                self::$cache[$connectionCacheKey] = $connection;
            }

            $objectCacheKey = sha1($database.$table);
            if (array_key_exists($objectCacheKey, self::$cache) == false) {
                self::$cache[$objectCacheKey] = new Mysql(self::$cache[$connectionCacheKey], $database, $table, $class);
            }
            return self::$cache[$objectCacheKey];
        }
    }
?>