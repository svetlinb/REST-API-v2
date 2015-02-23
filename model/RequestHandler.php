<?php
namespace model;


class RequestHandler {
    private $method;
    private $params;
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

    public function parseRequest() {
        if($_SERVER['QUERY_STRING']) {
            $this->params = $this->validator->sanitizeInput($_SERVER['QUERY_STRING']);
        }
    }

    public function parseMethod() {
        $this->method = strtolower($_SERVER["REQUEST_METHOD"]);
        $this->method = $this->validator->sanitizeInput($this->method);
    }

    public function getMethod() {
        return $this->method;
    }

    public function getQuery() {
        return $this->params;
    }
} 