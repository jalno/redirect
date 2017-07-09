<?php
namespace packages\redirect;
use \packages\base\json;
use \packages\base\router;
use \packages\base\router\rule;
$address = new address();
$address->where('status', address::active);
$addresses = [];
foreach($address->get() as $address){
	$rule = new rule();
	$rule->setController(controllers\redirect::class, 'redirector');
	if($address->isRegex()){
		$rule->setRegex($address->source);
	}else{
		$url = parse_url($address->source);
		$rule->addScheme($url['scheme']);
		$rule->addDomain($url['host']);
		$rule->setAbsolute(true);
		$rule->setPath(substr($url['path'], 1));
	}
	router::addRule($rule);
}