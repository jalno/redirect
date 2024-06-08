<?php
namespace packages\redirect;
use \packages\base\Json;
use \packages\base\Router;
use \packages\base\Router\Rule;
$address = new Address();
$address->where('status', Address::active);
$addresses = [];
foreach($address->get() as $address){
	$rule = new Rule();
	$rule->setController(Controllers\Redirect::class, 'redirector');
	if($address->isRegex()){
		$rule->setRegex($address->source);
	}else{
		$url = parse_url($address->source);
		$rule->addScheme($url['scheme']);
		$rule->addDomain($url['host']);
		$rule->setAbsolute(true);
		$rule->setPath(substr($url['path'], 1));
	}
	Router::addRule($rule);
}