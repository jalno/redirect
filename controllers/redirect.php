<?php
namespace packages\redirect\controllers;
use \packages\base\http;
use \packages\base\response;
use \packages\base\controller;
use \packages\redirect\address;
class redirect extends controller{
	private $response;
	public function __construct(){
		$this->response = new response();
	}
	public function redirector(){
		$uri = http::getURL();
		$address = new address();
		$address->where('status', address::active);
		foreach($address->get() as $address){
			if($address->isRegex() and $destination = preg_replace($address->source, $address->destination, $uri)){
				$address->hit();
				$this->response->setHttpCode($address->type);
				$this->response->Go($destination);
			}elseif($address->source == $uri){
				$address->hit();
				$this->response->setHttpCode($address->type);
				$this->response->Go($address->destination);
			}
			/*if(($address->isRegex() and preg_match($address->source, $uri)) or $address->source == $uri){
				$address->hits++;
				$address->save();
				$this->response->setHttpCode($address->type);
				$this->response->Go($address->destination);
				break;
			}*/
		}
		return $this->response;
	}
}