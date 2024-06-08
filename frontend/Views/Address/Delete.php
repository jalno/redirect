<?php

namespace themes\clipone\Views\Redirect\Address;

use packages\base\Translator;
use packages\redirect\Views\Address\Delete as RedirectDelete;
use themes\clipone\Navigation;
use themes\clipone\Views\FormTrait;
use themes\clipone\ViewTrait;

class Delete extends RedirectDelete
{
    use ViewTrait;
    use FormTrait;
    protected $address;

    public function __beforeLoad()
    {
        $this->address = $this->getAddress();
        $this->setTitle([
            Translator::trans('redirect.settings'),
            Translator::trans('redirects'),
            Translator::trans('redirect.address.delete'),
        ]);
        Navigation::active('settings/redirects');
    }
}
