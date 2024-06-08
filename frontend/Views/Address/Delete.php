<?php
namespace themes\clipone\Views\Redirect\Address;
use \packages\base\Translator;
use \packages\userpanel;
use \themes\clipone\ViewTrait;
use \themes\clipone\Navigation;
use \themes\clipone\Views\FormTrait;
use \packages\redirect\Views\Address\Delete as RedirectDelete;
class Delete extends RedirectDelete{
	use ViewTrait, FormTrait;
	protected $address;
	function __beforeLoad(){
		$this->address = $this->getAddress();
		$this->setTitle([
			Translator::trans('redirect.settings'),
			Translator::trans('redirects'),
			Translator::trans('redirect.address.delete')
		]);
		Navigation::active("settings/redirects");
	}
}
