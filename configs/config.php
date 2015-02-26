<?php
namespace configs;

/**
 * Utility class
 * User: svetlin.betsinski
 * Date: 19.2.2015 г.
 * Time: 11:38 ч.
 */

class Config {

    public function getConfig() {
      return  $config = array(
            'db' => array(
                'dsn' => 'localhost',
                'dbName' => 'rest-api',
                'dbUser' => 'root',
                'dbPass' => ''
            ),
            'routes' => array(
                'get' => array(
                    'jobs/([0-9]+)',
                    'jobs/show',
                    'candidates/show',
                    'candidates/review/([0-9]+)',
                    'candidates/search/([0-9]+)'
                ),
                'put' => array(
                    'jobs/([0-9]+)'
                ),
                'post' => array(
                    'jobs/([0-9]+)'
                ),
                'delete' => array(
                    'jobs/([0-9]+)'
                )
            )
        );
    }

}