<?php
namespace model;


class Validator {
    protected $params;

    /*
     * Sanitize user input
     *
     * @Return Mixed: Array or String
     */
    public function sanitizeInput($params){
        $this->params = is_array($params) ? filter_var_array($params, FILTER_SANITIZE_STRING)
            : filter_var(trim($params), FILTER_SANITIZE_STRING);
        $this->params = (is_array($this->params)) ? array_filter($this->params) : $this->params;

        return $this->params;
    }

    /*
     * Check argument range
     *
     * @Return Boolean
     */
    public function validateHttpMethod($httpMethod) {
        $validMethods = array("get", "post", "put", "delete");

        return in_array($httpMethod, $validMethods);
    }

    /*
     * Check is record exist in DB
     *
     * @Return Boolean
     */
    public function isIdExist($id, $dbh, $model) {
        if(!is_numeric($id)){
            return false;
        }

        $stmt = $dbh->prepare("SELECT * FROM $model WHERE id=:id ");
        $stmt->setFetchMode(\PDO::FETCH_ASSOC);
        $stmt->execute(array(":id"=>$id));
        $row = $stmt->fetch();

        return !empty($row);
    }
} 