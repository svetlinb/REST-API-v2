<?php
namespace configs;

/**
 * Utility class
 * User: svetlin.betsinski
 * Date: 19.2.2015 г.
 * Time: 11:38 ч.
 */

class Config {

    public static function dbConfig() {
      return  $config = array(
            'db' => array(
                'dsn' => 'localhost',
                'dbName' => 'rest-api',
                'dbUser' => 'root',
                'dbPass' => ''
            )
        );
    }

}