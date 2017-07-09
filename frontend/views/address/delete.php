<?php
namespace themes\clipone\views\redirect\address;
use \packages\base\translator;
use \packages\userpanel;
use \themes\clipone\viewTrait;
use \themes\clipone\navigation;
use \themes\clipone\views\formTrait;
use \packages\redirect\views\address\delete as redirectDelete;
class delete extends redirectDelete{
	use viewTrait, formTrait;
	protected $address;
	function __beforeLoad(){
		$this->address = $this->getAddress();
		$this->setTitle([
			translator::trans('redirect.settings'),
			translator::trans('redirects'),
			translator::trans('redirect.address.delete')
		]);
		navigation::active("settings/redirects");
	}
}
