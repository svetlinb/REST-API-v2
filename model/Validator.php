<?php
namespace model;


class Validator {
    protected $params;

    /*
     * Sanitize user input
     */
    public function sanitizeInput($params){
        $this->params = is_array($params) ? filter_var_array($params, FILTER_SANITIZE_STRING)
            : filter_var(trim($params), FILTER_SANITIZE_STRING);
        $this->params = (is_array($this->params)) ? array_filter($this->params) : $this->params;

        return $this->params;
    }

    /*
     * Check argument range
     */
    public function validateHttpMethod($httpMethod) {
        $validMethods = array("get", "post", "put", "delete");

        return in_array($httpMethod, $validMethods);
    }
} 