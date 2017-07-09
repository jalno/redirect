<?php
namespace packages\redirect\views\address;
use \packages\redirect\address;
use \packages\redirect\views\form;
class delete extends form{
	public function setAddress(address $address){
		$this->setData($address, 'address');
		$this->setDataForm($address->toArray());
	}
	protected function getAddress():address{
		return $this->getData('address');
	}
}
