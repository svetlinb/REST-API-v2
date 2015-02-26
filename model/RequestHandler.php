<?php
namespace model;


class RequestHandler {
    private $method;
    private $params;
    private $query;
    private $validator;

    public function __construct(Validator $validator) {
        $this->validator = $validator;
    }

    public function sentHeaders() {
        header('Content-Type: text/javascript; charset=utf8');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
        header("Content-Type: application/json");
    }

    public function getQuery() {
        if($_SERVER['QUERY_STRING']) {
            $this->query = $this->validator->sanitizeInput($_SERVER['QUERY_STRING']);
        }
        return $this->query;
    }

    public function getMethod() {
        $this->method = strtolower($_SERVER["REQUEST_METHOD"]);
        $this->method = $this->validator->sanitizeInput($this->method);

        return $this->method;
    }

    public function parseQuery() {
        $query = $this->getQuery();
        $this->params = explode("/", $query);

        return $this->params;
    }

} 