<?php
namespace api;

use model\DBManager;
use model\Http;
use model\RequestHandler;
use model\ResponseHandler;
use model\Validator;

class Router {
    private $responseHandler;
    private $requestHandler;
    private $validator;
    private $http;

	/*
	 * Send needed headers.
	 * Init variables and validate user input.
	 */
	public function __construct($config){
        $this->dbResource = new DBManager($config);
        $this->validator = new Validator();
        $this->requestHandler = new RequestHandler($this->validator);
        $this->responseHandler = new ResponseHandler();
        $this->http = new Http($this->dbResource, $this->validator, $this->responseHandler);
	}
	
	/*
	 * Process request
	 */
	public function process() {
	  try {
        $this->requestHandler->sentHeaders();
        $this->requestHandler->parseMethod();
        $this->requestHandler->parseRequest();
		$this->http->setRoutes();
		$this->http->handle($this->requestHandler->getMethod(), $this->requestHandler->getQuery());
	  }catch(\Exception $e){
        $this->responseHandler->sendHeader($e->getMessage(), $e->getCode());
	  }
	}


}
?>