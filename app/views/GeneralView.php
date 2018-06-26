<?php

require_once "conf/config.inc.php";

class GeneralView
{
	private $model, $controller, $slimApp;

	public function __construct($controller, $model, $slimApp) {
		$this->controller = $controller;
		$this->model = $model;
		$this->slimApp = $slimApp;		
	}

	public function output($encoding){
		//prepare json response
		if($encoding == ENCODING_JSON) {
			$jsonResponse = json_encode($this->model->apiResponse);
			$this->slimApp->response->headers->set('Content-Type',$encoding);
			$this->slimApp->response->write($jsonResponse);
		}
		
		if($encoding == ENCODING_XML) {
			$xmlResponse = $this->encodeToXml($this->model->apiResponse, $this->model->modelname);
			$this->slimApp->response->headers->set('Content-Type',$encoding);
			$this->slimApp->response->write($xmlResponse);
		}
	}
	
	/*
	 * function to encode an associative array into xml
	 * $array String: input of associative array
	 * return $xml: String - xml output
	 */
	private function encodeToXml($array, $model) {
		
		$xml = null;
		if(! empty($array)&& is_array($array)) {
			if(sizeof($array) > 1) { //if more than one element than add top xml element 
				
				$xml .= "<" . $model . "s>";
				foreach ($array as $key=>$value) {
					if(!empty($value)) {
						
						//set tag
						if(is_numeric($key))
							$tag = $model;
						else $tag = $key;
						
						$xml .= "<" . $tag . ">";
						
						//set value
						if(is_array($value))
							$xml .= $this->encodeInnerArrayToXml($value);
						else $xml .= $value;
						
						$xml .= "</" . $tag . ">";
					}
				}
				$xml .= "</" . $model . "s>";
			}
			else {
				foreach ($array as $key=>$value) {
					if(!empty($value)) {
						//set tag
						if(is_numeric($key))
							$tag = $model;
						else $tag = $key;
						
						$xml .= "<" . $tag . ">";
						
						//set value
						if(is_array($value))
							$xml .= $this->encodeInnerArrayToXml($value);
						else $xml .= $value;
						
						$xml .= "</" . $tag . ">";
					}
				}
			}
		}
		
		//if response body is nearly empty apart from model name return null
		if($xml == "<" . $model . "s></" . $model . "s>")
			$xml = null;
		return $xml;
	}
	
	//inner associative array
	private function encodeInnerArrayToXml($array) {
		
		$xml = null; 
		if(! empty($array) && is_array($array)) {
			foreach ($array as $key=>$value) {
				$xml .= "<" . $key . ">";
				
				if(is_array($value)) { //call recursively for array in array
					$xml .= $this->encodeInnerArrayToXml($value);
				}
				else {
					$xml .= $value;
				}
				
				$xml .= "</" . $key . ">";
			}
		}
		return $xml;
	}
}
?>