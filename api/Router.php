<?php
namespace api;

use configs\Config;
use model\DBManager;
use model\Http;
use model\RequestHandler;
use model\ResponseHandler;
use model\Validator;

class Router {
    private $responseHandler;
    private $requestHandler;
    private $validator;
    private $urlParams;
    private $config;

	/*
	 * Prepare needed classes
	 */
	public function __construct(Config $config){
        $this->config = $config->getConfig();
        $this->dbResource = new DBManager($this->config);
        $this->validator = new Validator();
        $this->requestHandler = new RequestHandler($this->validator);
        $this->responseHandler = new ResponseHandler();
        $this->urlParams = $this->requestHandler->parseQuery();
	}
	
	/*
	 * Process request
	 */
	public function process() {
	  try {
        $this->requestHandler->sentHeaders();
        $this->handleRequest();
	  }catch(\Exception $e){
        $this->responseHandler->sendHeader($e->getMessage(), $e->getCode());
	  }
	}


    public function handleRequest() {
        $isValid = false;
        $method = $this->requestHandler->getMethod();
        $queryString = $this->requestHandler->getQuery();

        if($this->validator->validateHttpMethod($method) && !empty($queryString)) {
            foreach($this->config['routes'][$method] as $regEx) {
                $pattern = str_ireplace("/", "\/", $regEx);

                if(preg_match('@^'.$pattern.'$@i', $queryString, $match)) {
                    $id = (!empty($match[1]) && is_numeric($match[1])) ? $match[1] : null;
                    $this->invokeAction($id);
                    $isValid = true;
                    break;
                }
            }
        }

        if(!$isValid) {
            throw new \Exception("Not found.", 404);
        }
    }

    public function invokeAction($id) {
        $model = $this->getModel();
        $action = $this->getAction();
        $class = new $model($this->dbResource, $this->validator, $this->responseHandler);

        if(!method_exists($class, $action)) {
            throw new \Exception('Not found', 404);
        }

        if($id) {
            $class->$action($id);
        }else {
            $class->$action();
        }
    }

    private function getModel() {
        $class = '\\model\\'.ucfirst($this->urlParams[0]);

        if(!class_exists($class)) {
            throw new \Exception('Invalid argument ' . $class . '.', 400);
        }
        
        return $class;
    }

    private function getAction() {
        $action = (!is_numeric($this->urlParams[1])) ? $this->urlParams[1] : $this->requestHandler->getMethod();

        return $action;
    }
}
?>