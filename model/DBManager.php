<?php
/*
 * DBManager class care for the database connection.
 * It's intentionally not used Singelton pattern, because it is considered
 * as a bad practice. Related info about this matter can be found here:
 * http://stackoverflow.com/questions/8776788/best-practice-on-php-singleton-classes
 */

namespace model;

use \PDO;

class DBManager {
	protected $instances;
    protected $params;

    /*
     * Init params
     */
	public function __construct($params) {
        $this->params = $params;
	}

    /*
     * Handle DB connection
     */
	public function getDB() {
        if (empty($this->instances['db']) || !is_a($this->instances['db'], 'PDO')) {
            $this->instances['db'] = new PDO("mysql:host={$this->params['db']['dsn']};dbname={$this->params['db']['dbName']}",
                $this->params['db']['dbUser'], $this->params['db']['dbPass']);
        }
        return $this->instances['db'];
	}

}

?>