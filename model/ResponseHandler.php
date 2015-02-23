<?php
namespace model;

class ResponseHandler {
	
	/*
	 * Send output header with JSON result
	 */
	public function sendHeader($data, $status) {
		header("HTTP/1.1 " . $status . " " . $this->responseStatus($status));
		echo  json_encode($data);
		exit;
	}
	
	/*
	 * Return needed response status
	 */
	public function responseStatus($code) {
		$status = array(
				200 => 'OK',  
            	201 => 'Created', 
				400 => 'Bad Request',  
            	403 => 'Forbidden',
				404 => 'Not Found',
				500 => 'Internal Server Error'
		);
		return ($status[$code])?$status[$code]:$status[500];
	}

}

?>