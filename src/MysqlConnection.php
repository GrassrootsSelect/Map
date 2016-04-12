<?php
    namespace GRSelect;
	class MysqlConnection {
        protected $link = null;

        protected $exceptionOnError = false;
        protected $last_error = '';
        protected $last_error_code = false;
        protected $last_query = false;

        protected $last_statement = false;

        public $queries = array();
        public $queryCount = 0;
        protected $logQueries = false;

        function __construct (
            $host,
            $user,
            $password,
            $database,
            $exceptions = false,
            $log = true
        ) {
            $options = array(
                \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"
            );
            $pdo = @(
            new \PDO(
                'mysql:host=' . $host . ';dbname=' . $database . ';charset=utf8',
                $user,
                $password,
                $options
            )
            );
            $pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
            $this->link = $pdo;

            $this->setLogging($log);
            $this->setExceptions($exceptions);
        }

        /**
         * Checks if this instance has an active connection
         * @return bool
         */
        public function isConnected () {
            if ($this->link != null) {
                return true;
            }

            return false;
        }

        /**
         * Set attribute on internal PDO link, see PDO docs
         * @param $item
         * @param $value
         */
        public function setAttribute ($item, $value) {
            return $this->link->setAttribute($item, $value);
        }


        /**
         * Use PDO::quote to manually escape a value
         * @param $item
         * @param bool $arg
         * @return bool
         */
        public function quote ($item, $arg = false) {
            if ($arg) {
                return $this->link->quote($item, $arg);
            }

            return $this->link->quote($item);
        }

        /**
         * Naive simulator of PDO parameter replacement
         * @param $sql
         * @param array $values
         * @return string
         */
        public function test ($sql, array $values = null) {
            $keys = array();
            if (is_array($values) === true) {
                foreach ($values as $key => &$value) {
                    if (is_string($key)) {
                        $keys[] = '/:' . $key . '/';
                    } else {
                        $keys[] = '/[?]/';
                    }
                    $value = '"' . (is_array($value) ? json_encode($value) : $value) . '"';
                }
                $sql = preg_replace($keys, $values, $sql, 1, $count);
            }

            return $sql;
        }


        /**
         * Fetch a single field
         * @param $sql
         * @param array $values
         * @return null|string
         */
        public function field ($sql, array $values = null) {
            if (!$this->link) {
                return $this->returnError('No database connection, cannot retrieve field', 0);
            }

            $this->resetError();
            $start = $this->getTime();

            $result = null;
            $this->logQuery($this->test($sql, $values), $start);
            try {
                $statement = $this->makeAndExecute($sql, $values);
                if (!$statement) {
                    return $this->returnError('Invalid arguments, cannot retrieve field', 0);
                }
                $result = $statement->fetchColumn();
                $statement->closeCursor();
            } catch (\PDOException $err) {
                return $this->returnError($err->getMessage(), $err->getCode());
            }
            return $result;
        }


        /**
         * Fetch an array of fields
         * @param $sql
         * @param array $values
         * @return array|null
         */
        public function fields ($sql, array $values = null) {
            if (!$this->link) {
                return $this->returnError('No database connection, cannot retrieve field', 0);
            }

            $this->resetError();
            $start = $this->getTime();
            $result = null;
            $this->logQuery($this->test($sql, $values), $start);
            try {
                $statement = $this->makeAndExecute($sql, $values);
                if (!$statement) {
                    return $this->returnError('Invalid arguments, cannot retrieve fields', 0);
                }
                $result = $statement->fetchAll(\PDO::FETCH_COLUMN, 0);
                $statement->closeCursor();
            } catch (\PDOException $err) {
                return $this->returnError($err->getMessage(), $err->getCode());
            }

            return $result;
        }


        /**
         * Fetch an array of fields, indexed by specified field
         * @param $sql
         * @param array $values
         * @param bool $key
         * @return array|null
         */
        public function keyFields ($sql, array $values = null, $key = false) {
            if (!$this->link) {
                return $this->returnError('No database connection, cannot retrieve key fields', 0);
            }

            $this->resetError();
            $start = $this->getTime();

            $out = null;
            $this->logQuery($this->test($sql, $values), $start);

            try {
                $statement = $this->makeAndExecute($sql, $values);
                if (!$statement) {
                    return $this->returnError('Invalid arguments, cannot retrieve key fields', 0);
                }
                $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
                $statement->closeCursor();
                $out = array();
                foreach ($result as $r) {
                    $out[$r[$key]] = array_shift($r);
                }
            } catch (\PDOException $err) {
                return $this->returnError($err->getMessage(), $err->getCode());
            }
            return $out;
        }


        /**
         * Fetch one row
         * @param $sql
         * @param array $values
         * @return mixed|null
         */
        public function row ($sql, array $values = null) {
            if (!$this->link) {
                return $this->returnError('No database connection, cannot retrieve row', 0);
            }

            $this->resetError();
            $start = $this->getTime();

            $result = null;
            $this->logQuery($this->test($sql, $values), $start);

            try {
                $statement = $this->makeAndExecute($sql, $values);
                if (!$statement) {
                    return $this->returnError('Invalid arguments, cannot retrieve row', 0);
                }
                $result = $statement->fetch(\PDO::FETCH_ASSOC);
                $statement->closeCursor();
            } catch (\PDOException $err) {
                return $this->returnError($err->getMessage(), $err->getCode());
            }

            return $result;
        }


        /**
         * Fetch an array of rows
         * @param $sql
         * @param array $values
         * @param bool $key
         * @return array|null
         */
        public function rows ($sql, array $values = null, $key = false) {
            if (!$this->link) {
                return $this->returnError('No database connection, cannot retrieve rows', 0);
            }

            $this->resetError();
            $start = $this->getTime();

            $result = null;
            $this->logQuery($this->test($sql, $values), $start);

            try {
                $statement = $this->makeAndExecute($sql, $values);
                if (!$statement) {
                    return $this->returnError('Invalid arguments, cannot retrieve rows', 0);
                }
                $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
                $statement->closeCursor();
            } catch (\PDOException $err) {
                return $this->returnError($err->getMessage(), $err->getCode());
            }
            return $result;
        }

        /**
         * Fetch an array of rows
         * @param $sql
         * @param array $values
         * @param bool $key
         * @return array|null
         */
        public function indexRows ($sql, array $values = null, $key = false) {
            if (!$this->link) {
                return $this->returnError('No database connection, cannot retrieve rows', 0);
            }

            $this->resetError();
            $start = $this->getTime();

            $result = null;
            $this->logQuery($this->test($sql, $values), $start);

            try {
                $statement = $this->makeAndExecute($sql, $values);
                if (!$statement) {
                    return $this->returnError('Invalid arguments, cannot retrieve rows', 0);
                }
                $result = $statement->fetchAll(\PDO::FETCH_NUM);
                $statement->closeCursor();
            } catch (\PDOException $err) {
                return $this->returnError($err->getMessage(), $err->getCode());
            }

            return $result;
        }

        /**
         * Fetch an array of rows, indexed by the specified field
         * @param $sql
         * @param array $values
         * @param bool $key
         * @return array|null
         */
        public function keyRows ($sql, array $values = null, $key = false) {
            if (!$this->link) {
                return $this->returnError('No database connection, cannot retrieve key rows', 0);
            }

            $this->resetError();
            $start = $this->getTime();

            $out = null;
            $this->logQuery($this->test($sql, $values), $start);

            try {
                $statement = $this->makeAndExecute($sql, $values);
                if (!$statement) {
                    return $this->returnError('Invalid arguments, cannot retrieve key rows', 0);
                }
                $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
                $statement->closeCursor();

                $out = array();
                foreach ($result as $r) {
                    $out[$r[$key]] = $r;
                }
            } catch (\PDOException $err) {
                return $this->returnError($err->getMessage(), $err->getCode());
            }

            return $out;
        }


        /**
         * Issue an UPDATE query
         * @param $sql
         * @param array $values
         * @return bool
         */
        public function update ($sql, array $values = null) {
            if (!$this->link) {
                return $this->returnError('No database connection, cannot update', 0);
            }

            $this->resetError();
            $start = $this->getTime();

            $result = false;
            $this->logQuery($this->test($sql, $values), $start);

            try {
                $statement = $this->makeAndExecute($sql, $values);
                if (!$statement) {
                    return $this->returnError('Invalid arguments, cannot update', 0);
                }
                $statement->closeCursor();
                $result = true;
            } catch (\PDOException $err) {
                return $this->returnError($err->getMessage(), $err->getCode());
            }


            if (!$result || $statement->rowCount() < 1) {
                return $result;
            }

            return true;
        }

        /**
         * Alias for update()
         * @param $sql
         * @param array $values
         * @return bool
         */
        public function delete ($sql, array $values = null) {
            return $this->update($sql, $values);
        }

        /**
         * Issue an INSERT statement, return new id
         * @param $sql
         * @param array $values
         * @return bool|string
         */
        public function insert ($sql, array $values = null) {
            if (!$this->link) {
                return $this->returnError('No database connection, cannot insert', 0);
            }

            $this->resetError();
            $start = $this->getTime();

            $result = false;
            $this->logQuery($this->test($sql, $values), $start);

            try {
                $statement = $this->makeAndExecute($sql, $values);
                if (!$statement) {
                    return $this->returnError('Invalid arguments, cannot insert', 0);
                }
                $statement->closeCursor();
                $result = true;
            } catch (\PDOException $err) {
                return $this->returnError($err->getMessage(), $err->getCode());
            }


            if (!$result || $statement->rowCount() < 1) {
                return $result;
            }

            return $this->link->lastInsertId();
        }

        /**
         * Issue an arbitary query
         * @param $sql
         * @param array $values
         * @return bool|string
         */
        public function query ($sql, array $values = null, $log=true) {
            if (!$this->link) {
                return $this->returnError('No database connection, cannot update', 0);
            }

            $this->resetError();
            $start = $this->getTime();

            $result = false;
            $this->logQuery($this->test($sql, $values), $start);

            try {
                $statement = $this->makeAndExecute($sql, $values);
                if (!$statement) {
                    return $this->returnError('Invalid arguments, cannot update', 0);
                }
                $result = $statement->fetch(\PDO::FETCH_ASSOC);
                $statement->closeCursor();
            } catch (\PDOException $err) {
                return $this->returnError($err->getMessage(), $err->getCode());
            }


            return $result;
        }

        /**
         * Prepare a query for execution
         * @param $sql
         * @return bool|PDOStatement
         */
        public function prepare ($sql) {
            $statement = false;
            try {
                $statement = $this->link->prepare($sql);
                $this->last_statement = $statement;
            } catch (\PDOException $err) {
                return $this->returnError($err->getMessage(), $err->getCode());
            }

            return $statement;
        }

        /**
         * Create a statement, or recycle the last_statement
         * @param $sql
         * @param $values
         * @return bool|null|PDOStatement
         */
        protected function makeAndExecute ($sql, $values) {
            $statement = null;
            if (is_array($sql) && $this->last_statement !== false) {
                $values = $sql;
                $sql = false;
                $statement = $this->last_statement;
            } elseif (is_string($sql)) {
                $statement = $this->link->prepare($sql);
            } else {
                $this->last_statement = false;
            }
            if (!$statement) {
                return $statement;
            }

            if (is_null($values)) {
                $statement->execute();
            } else {
                $statement->execute($values);
            }
            if (is_array($values)) {
                $this->last_statement = $statement;
            } else {
                $this->last_statement = false;
            }

            return $statement;
        }

        /**
         * Return details about the last error, if any
         * @return array|bool
         */
        public function error () {
            if ($this->last_error_code === false) {
                return false;
            }

            return array('message' => $this->last_error, 'code' => $this->last_error_code);
        }

        /**
         * Return details about the last query, if any
         * @return string|bool
         */
        public function lastQuery() {
            return $this->last_query;
        }

        /**
         * Turn internal logging on or off
         * @param bool $logging
         */
        public function setLogging ($logging = false) {
            $this->logQueries = $logging;
        }

        /**
         * Turn exceptions on errors on or off
         * @param bool $exceptions
         */
        public function setExceptions ($exceptions = false) {
            $this->exceptionOnError = $exceptions;
        }

        /**
         * Get the internal query log
         * @return array
         */
        public function getLog () {
            return $this->queries;
        }

        /**
         * Get the internal query count
         * @return int
         */
        public function getQueryCount () {
            return $this->queryCount;
        }

        /**
         * Used internally to log queries, if query logging is active
         * @param $sql
         * @param $start
         * @return bool
         */
        protected function logQuery ($sql, $start) {
            $this->last_query = $sql;
            if ($this->logQueries !== true) {
                return true;
            }
            $this->queries[] = array(
                'sql'  => $sql,
                'time' => $this->getTimeDif($this->getTime(), $start)
            );
            $this->queryCount++;

            return true;
        }
        private function getTime() {
            return microtime(true);
        }
        private function getTimeDif($timea, $timeb) {
            return round($timea - $timeb,5)*1000;
        }

        /**
         * Clear internal errors
         */
        protected function resetError () {
            $this->last_error_code = false;
            $this->last_error = '';
        }

        /**
         * Set the internal error message and number, throw an exception if class state indicates
         * @param $message
         * @param $number
         * @return null
         * @throws \Exception
         */
        protected function returnError ($message, $number) {
            $this->last_error = $message;;
            $this->last_error_code = $number;
            if ($this->exceptionOnError) {
                throw new \Exception($message, $number);
            }

            return null;
        }

        public function hasTable() {
            return true;
        }
        public function getTable($database, $table) {
            return $this->from($database, $table);
        }
        public function from($database, $table, $class){
            return new Mysql($this, $database, $table, $class);
        }
    }
?>
