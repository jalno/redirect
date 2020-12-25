<?php
namespace packages\redirect\controllers;

use packages\base\{http, Controller};
use packages\redirect\Address;

class Redirect extends Controller {
	public function redirector(){
		$uri = http::getURL();
		$address = new Address();
		$address->where("status", Address::active);
		foreach($address->get() as $address) {
			if ($address->isRegex() and preg_match($address->source, $uri)) {
				$destination = preg_replace($address->source, $address->destination, $uri);
				$address->hit();
				$this->response->setHttpCode($address->type);
				$this->response->Go($destination);
			} elseif($address->source == $uri) {
				$address->hit();
				$this->response->setHttpCode($address->type);
				$this->response->Go($address->destination);
			}
		}
		return $this->response;
	}
}
