<?php

namespace packages\redirect\Views\Address;

use packages\redirect\Address;
use packages\redirect\Views\Form;

class Edit extends Form
{
    public function setAddress(Address $address)
    {
        $this->setData($address, 'address');
        $this->setDataForm($address->toArray());
    }

    protected function getAddress(): Address
    {
        return $this->getData('address');
    }
}
