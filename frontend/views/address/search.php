<?php
namespace themes\clipone\views\redirect\address;
use \packages\base\packages;
use \packages\base\view\error;
use \packages\base\translator;
use \packages\userpanel;
use \packages\redirect\address;
use \themes\clipone\viewTrait;
use \themes\clipone\navigation;
use \themes\clipone\views\listTrait;
use \themes\clipone\views\formTrait;
use \themes\clipone\navigation\menuItem;
use \packages\redirect\views\address\search as redirectList;
class search extends redirectList{
	use viewTrait, listTrait, formTrait;
	function __beforeLoad(){
		$this->setTitle([
			translator::trans('redirect.settings'),
			translator::trans('redirects')
		]);
		$this->setButtons();
		navigation::active("settings/redirects");
		if(empty($this->getaddressLists())){
			$this->addNotFoundError();
		}
	}
	private function addNotFoundError(){
		$error = new error();
		$error->setType(error::NOTICE);
		$error->setCode('redirect.address.notfound');
		if($this->canAdd){
			$error->setData([
				[
					'type' => 'btn-success',
					'txt' => translator::trans('redirect.address.add'),
					'link' => userpanel\url('redirects/add')
				]
			], 'btns');
		}
		$this->addError($error);
	}
	public function setButtons(){
		$this->setButton('edit', $this->canEdit, [
			'title' => translator::trans('redirect.address.edit'),
			'icon' => 'fa fa-edit',
			'classes' => ['btn', 'btn-xs', 'btn-teal']
		]);
		$this->setButton('delete', $this->canDel, [
			'title' => translator::trans('redirect.address.delete'),
			'icon' => 'fa fa-times',
			'classes' => ['btn', 'btn-xs', 'btn-bricky']
		]);
	}
	protected function getStatusForSelect():array{
		return [
			[
				'title' => translator::trans("redirect.search.choose"),
				'value' => ''
			],
			[
				'title' => translator::trans("redirect.address.status.active"),
				'value' => address::active
			],
			[
				'title' => translator::trans("redirect.address.status.deactive"),
				'value' => address::deactive
			]
		];
	}
	protected function getTypeForSelect():array{
		return [
			[
				'title' => translator::trans("redirect.search.choose"),
				'value' => ''
			],
			[
				'title' => translator::trans("redirect.address.status.permanent"),
				'value' => address::permanent
			],
			[
				'title' => translator::trans("redirect.address.status.temporary"),
				'value' => address::temporary
			]
		];
	}
	public static function onSourceLoad(){
		parent::onSourceLoad();
		if(parent::$navigation){
			if($settings = navigation::getByName("settings")){
				$item = new menuItem("redirects");
				$item->setTitle(translator::trans('redirects'));
				$item->setURL(userpanel\url('settings/redirects'));
				$item->setIcon('fa fa-external-link');
				$settings->addItem($item);
			}
		}
	}
	public function getComparisonsForSelect(){
		return [
			[
				'title' => translator::trans('search.comparison.contains'),
				'value' => 'contains'
			],
			[
				'title' => translator::trans('search.comparison.equals'),
				'value' => 'equals'
			],
			[
				'title' => translator::trans('search.comparison.startswith'),
				'value' => 'startswith'
			]
		];
	}
}
