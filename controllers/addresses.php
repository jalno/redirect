<?php
namespace packages\redirect\controllers;
use \packages\base\db;
use \packages\base\http;
use \packages\base\NotFound;
use \packages\base\view\error;
use \packages\base\db\parenthesis;
use \packages\base\views\FormError;
use \packages\base\inputValidation;
use \packages\base\db\duplicateRecord;
use \packages\userpanel;
use \packages\redirect\view;
use \packages\redirect\address;
use \packages\redirect\controller;
use \packages\redirect\authorization;
class addresses extends controller{
	protected $authentication = true;
	public function search(){
		authorization::haveOrFail('search');
		$view = view::byName("\\packages\\redirect\\views\\address\\search");
		$address = new address();
		$inputsRules = [
			'id' => [
				'type' => 'number',
				'optional' => true,
				'empty' => true
			],
			'source' => [
				'type' => 'string',
				'optional' => true,
				'empty' => true
			],
			'destination' => [
				'type' => 'string',
				'optional' => true,
				'empty' => true
			],
			'type' => [
				'type' => 'number',
				'optional' => true,
				'empty' => true,
				'values' => [
					address::permanent,
					address::temporary,
				]
			],
			'status' => [
				'type' => 'number',
				'optional' => true,
				'empty' => true,
				'values' => [
					address::active,
					address::deactive,
				]
			],
			'word' => [
				'type' => 'string',
				'optional' => true,
				'empty' => true
			],
			'comparison' => [
				'values' => ['equals', 'startswith', 'contains'],
				'default' => 'contains',
				'optional' => true
			]
		];
		$this->response->setStatus(true);
		try{
			$inputs = $this->checkinputs($inputsRules);
			foreach(['id', 'source', 'type', 'destination' , 'status'] as $item){
				if(isset($inputs[$item]) and $inputs[$item]){
					$comparison = $inputs['comparison'];
					if(in_array($item, ['id', 'type', 'status'])){
						$comparison = 'equals';
					}
					$address->where($item, $inputs[$item], $comparison);
				}
			}
			if(isset($inputs['word']) and $inputs['word']){
				$parenthesis = new parenthesis();
				foreach(['source', 'destination'] as $item){
					if(!isset($inputs[$item]) or !$inputs[$item]){
						$parenthesis->where($item, $inputs['word'], $inputs['comparison'], 'OR');
					}
				}
				$address->where($parenthesis);
			}
		}catch(inputValidation $error){
			$view->setFormError(FormError::fromException($error));
			$this->response->setStatus(false);
		}
		$address->pageLimit = $this->items_per_page;
		$addresses = $address->paginate($this->page, 'redirect_addresses.*');
		$view->setDataList($addresses);
		$view->setPaginate($this->page, db::totalCount(), $this->items_per_page);
		$this->response->setStatus(true);
		$this->response->setView($view);
		return $this->response;
	}
	public function add(){
		authorization::haveOrFail('add');
		$view = view::byName("\\packages\\redirect\\views\\address\\add");
		$view->setDataForm(false, 'regexr');
		$this->response->setStatus(true);
		$this->response->setView($view);
		return $this->response;
	}
	public function store(){
		authorization::haveOrFail('add');
		$view = view::byName("\\packages\\redirect\\views\\address\\add");
		$inputsRules = [
			'source' => [
				'type' => 'string'
			],
			'regexr' => [
				'type' => 'bool',
				'empty' => true
			],
			'type' => [
				'type' => 'number',
				'values' => [address::permanent, address::temporary]
			],
			'destination' => [
				'type' => 'string'
			],
			'status' => [
				'type' => 'number',
				'values' => [address::active, address::deactive]
			],
		];
		try{
			$this->response->setStatus(false);
			$inputs = $this->checkinputs($inputsRules);
			if(address::where('source', $inputs['source'])->has()){
				throw new duplicateRecord('source');
			}
			if($inputs['regexr']){
				if(!preg_match('/\\/^\\/.+\\/\\i/', $inputs['source'])){
					throw new inputValidation('source');
				}
				if(!@preg_match($inputs['source'], null)){
					throw new inputValidation('source');
				}
			}else{
				if(substr($inputs['source'], 0, 4) != 'http'){
					throw new inputValidation('source');
				}
			}
			if(substr($inputs['destination'], 0, 4) != 'http'){
				throw new inputValidation('destination');
			}
			$address = new address();
			foreach(['source', 'type', 'destination', 'status'] as $item){
				$address->$item = $inputs[$item];
			}
			$address->save();
			$this->response->setStatus(true);
			$this->response->Go(userpanel\url('settings/redirects/edit/'.$address->id));
		}catch(inputValidation $error){
			$view->setFormError(FormError::fromException($error));
		}catch(duplicateRecord $error){
			$view->setFormError(FormError::fromException($error));
		}
		$view->setDataForm($this->inputsvalue($inputsRules));
		$this->response->setView($view);
		return $this->response;
	}
}
