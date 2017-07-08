<?php
namespace packages\redirect\views\address;
use \packages\redirect\views\form;
class search extends form{
	public function setAddress(address $address){
		$this->setData($address, 'address');
	}
	protected function getAddress():address{
		return $this->getData('address');
	}
}
