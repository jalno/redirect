<?php

namespace packages\redirect\Controllers;

use packages\base\DB;
use packages\base\DB\DuplicateRecord;
use packages\base\DB\Parenthesis;
use packages\base\InputValidation;
use packages\base\NotFound;
use packages\base\Views\FormError;
use packages\redirect\Address;
use packages\redirect\Authorization;
use packages\redirect\Controller;
use packages\redirect\View;
use packages\redirect\Views\Address as AddressView;
use packages\userpanel;

class Addresses extends Controller
{
    protected $authentication = true;

    public function search()
    {
        Authorization::haveOrFail('search');
        $view = View::byName(AddressView\Search::class);
        $address = new Address();
        $inputsRules = [
            'id' => [
                'type' => 'number',
                'optional' => true,
                'empty' => true,
            ],
            'source' => [
                'type' => 'string',
                'optional' => true,
                'empty' => true,
            ],
            'destination' => [
                'type' => 'string',
                'optional' => true,
                'empty' => true,
            ],
            'type' => [
                'type' => 'number',
                'optional' => true,
                'empty' => true,
                'values' => [
                    Address::permanent,
                    Address::temporary,
                ],
            ],
            'status' => [
                'type' => 'number',
                'optional' => true,
                'empty' => true,
                'values' => [
                    Address::active,
                    Address::deactive,
                ],
            ],
            'word' => [
                'type' => 'string',
                'optional' => true,
                'empty' => true,
            ],
            'comparison' => [
                'values' => ['equals', 'startswith', 'contains'],
                'default' => 'contains',
                'optional' => true,
            ],
        ];
        $this->response->setStatus(true);
        try {
            $inputs = $this->checkinputs($inputsRules);
            foreach (['id', 'source', 'type', 'destination', 'status'] as $item) {
                if (isset($inputs[$item]) and $inputs[$item]) {
                    $comparison = $inputs['comparison'];
                    if (in_array($item, ['id', 'type', 'status'])) {
                        $comparison = 'equals';
                    }
                    $address->where($item, $inputs[$item], $comparison);
                }
            }
            if (isset($inputs['word']) and $inputs['word']) {
                $parenthesis = new Parenthesis();
                foreach (['source', 'destination'] as $item) {
                    if (!isset($inputs[$item]) or !$inputs[$item]) {
                        $parenthesis->where($item, $inputs['word'], $inputs['comparison'], 'OR');
                    }
                }
                $address->where($parenthesis);
            }
        } catch (InputValidation $error) {
            $view->setFormError(FormError::fromException($error));
            $this->response->setStatus(false);
        }
        $address->pageLimit = $this->items_per_page;
        $addresses = $address->paginate($this->page, 'redirect_addresses.*');
        $view->setDataList($addresses);
        $view->setPaginate($this->page, DB::totalCount(), $this->items_per_page);
        $this->response->setStatus(true);
        $this->response->setView($view);

        return $this->response;
    }

    public function add()
    {
        Authorization::haveOrFail('add');
        $view = View::byName(AddressView\Add::class);
        $view->setDataForm(false, 'regexr');
        $this->response->setStatus(true);
        $this->response->setView($view);

        return $this->response;
    }

    public function store()
    {
        Authorization::haveOrFail('add');
        $view = View::byName(AddressView\Add::class);
        $inputsRules = [
            'source' => [
            ],
            'regexr' => [
                'type' => 'bool',
                'empty' => true,
            ],
            'type' => [
                'type' => 'number',
                'values' => [Address::permanent, Address::temporary],
            ],
            'destination' => [
                'type' => 'string',
            ],
            'status' => [
                'type' => 'number',
                'values' => [Address::active, Address::deactive],
            ],
        ];
        try {
            $this->response->setStatus(false);
            $inputs = $this->checkinputs($inputsRules);
            if (Address::where('source', $inputs['source'])->has()) {
                throw new DuplicateRecord('source');
            }
            if ($inputs['regexr']) {
                if (!preg_match('/^\\/.+\\/i?$/', $inputs['source'])) {
                    throw new InputValidation('source');
                }
                if (false === !@preg_match($inputs['source'], null)) {
                    throw new InputValidation('source');
                }
            } else {
                if ('http' != substr($inputs['source'], 0, 4)) {
                    throw new InputValidation('source');
                }
            }
            if ('http' != substr($inputs['destination'], 0, 4)) {
                throw new InputValidation('destination');
            }
            $address = new Address();
            foreach (['source', 'type', 'destination', 'status'] as $item) {
                $address->$item = $inputs[$item];
            }
            $address->save();
            $this->response->setStatus(true);
            $this->response->Go(userpanel\url('settings/redirects/edit/'.$address->id));
        } catch (InputValidation $error) {
            $view->setFormError(FormError::fromException($error));
        } catch (DuplicateRecord $error) {
            $view->setFormError(FormError::fromException($error));
        }
        $view->setDataForm($this->inputsvalue($inputsRules));
        $this->response->setView($view);

        return $this->response;
    }

    public function edit(array $data)
    {
        Authorization::haveOrFail('edit');
        if (!$address = Address::byId($data['address'])) {
            throw new NotFound();
        }
        $view = View::byName(AddressView\Edit::class);
        $view->setAddress($address);
        $view->setDataForm($address->isRegex(), 'regexr');
        $this->response->setStatus(true);
        $this->response->setView($view);

        return $this->response;
    }

    public function update(array $data)
    {
        Authorization::haveOrFail('edit');
        if (!$address = Address::byId($data['address'])) {
            throw new NotFound();
        }
        $view = View::byName(AddressView\Edit::class);
        $view->setAddress($address);
        $inputsRules = [
            'source' => [
                'optional' => true,
            ],
            'regexr' => [
                'type' => 'bool',
                'empty' => true,
                'optional' => true,
            ],
            'type' => [
                'type' => 'number',
                'values' => [Address::permanent, Address::temporary],
                'optional' => true,
            ],
            'destination' => [
                'type' => 'string',
                'optional' => true,
            ],
            'status' => [
                'type' => 'number',
                'values' => [Address::active, Address::deactive],
                'optional' => true,
            ],
        ];
        try {
            $this->response->setStatus(false);
            $inputs = $this->checkinputs($inputsRules);
            if (isset($inputs['source'])) {
                if ($inputs['source']) {
                    if (Address::where('source', $inputs['source'])->where('id', $address->id, '!=')->has()) {
                        throw new DuplicateRecord('source');
                    }
                } else {
                    unset($inputs['source']);
                }
            }
            if (isset($inputs['regexr'], $inputs['source'])) {
                if ($inputs['regexr']) {
                    if (!preg_match('/^\\/.+\\/i?$/', $inputs['source'])) {
                        throw new InputValidation('source');
                    }
                    if (false === @preg_match($inputs['source'], null)) {
                        throw new InputValidation('source');
                    }
                } else {
                    if ('http' != substr($inputs['source'], 0, 4)) {
                        throw new InputValidation('source');
                    }
                }
            }
            if (isset($inputs['destination'])) {
                if ($inputs['destination']) {
                    if ('http' != substr($inputs['destination'], 0, 4)) {
                        throw new InputValidation('destination');
                    }
                } else {
                    unset($inputs['destination']);
                }
            }
            foreach (['source', 'type', 'destination', 'status'] as $item) {
                if (isset($inputs[$item])) {
                    $address->$item = $inputs[$item];
                }
            }
            $address->save();
            $this->response->setStatus(true);
        } catch (InputValidation $error) {
            $view->setFormError(FormError::fromException($error));
        } catch (DuplicateRecord $error) {
            $view->setFormError(FormError::fromException($error));
        }
        $view->setDataForm($this->inputsvalue($inputsRules));
        $this->response->setView($view);

        return $this->response;
    }

    public function delete(array $data)
    {
        Authorization::haveOrFail('delete');
        if (!$address = Address::byId($data['address'])) {
            throw new NotFound();
        }
        $view = View::byName(AddressView\Delete::class);
        $view->setAddress($address);
        $this->response->setStatus(true);
        $this->response->setView($view);

        return $this->response;
    }

    public function terminate(array $data)
    {
        Authorization::haveOrFail('delete');
        if (!$address = Address::byId($data['address'])) {
            throw new NotFound();
        }
        $view = View::byName(AddressView\Delete::class);
        $address->delete();
        $this->response->setStatus(true);
        $this->response->Go(userpanel\url('settings/redirects'));
        $this->response->setView($view);

        return $this->response;
    }
}
