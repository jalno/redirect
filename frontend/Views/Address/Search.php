<?php
namespace themes\clipone\Views\Redirect\Address;
use \packages\base\Packages;
use \packages\base\View\Error;
use \packages\base\Translator;
use \packages\userpanel;
use \packages\redirect\Address;
use \themes\clipone\ViewTrait;
use \themes\clipone\Navigation;
use \themes\clipone\Views\ListTrait;
use \themes\clipone\Views\FormTrait;
use \themes\clipone\Navigation\MenuItem;
use \packages\redirect\Views\Address\Search as RedirectList;
class Search extends RedirectList{
	use ViewTrait, ListTrait, FormTrait;
	function __beforeLoad(){
		$this->setTitle([
			Translator::trans('redirect.settings'),
			Translator::trans('redirects')
		]);
		$this->setButtons();
		Navigation::active("settings/redirects");
		if(empty($this->getaddressLists())){
			$this->addNotFoundError();
		}
	}
	private function addNotFoundError(){
		$error = new Error();
		$error->setType(error::NOTICE);
		$error->setCode('redirect.address.notfound');
		if($this->canAdd){
			$error->setData([
				[
					'type' => 'btn-success',
					'txt' => Translator::trans('redirect.address.add'),
					'link' => userpanel\url('settings/redirects/add')
				]
			], 'btns');
		}
		$this->addError($error);
	}
	public function setButtons(){
		$this->setButton('edit', $this->canEdit, [
			'title' => Translator::trans('redirect.address.edit'),
			'icon' => 'fa fa-edit',
			'classes' => ['btn', 'btn-xs', 'btn-teal']
		]);
		$this->setButton('delete', $this->canDel, [
			'title' => Translator::trans('redirect.address.delete'),
			'icon' => 'fa fa-times',
			'classes' => ['btn', 'btn-xs', 'btn-bricky']
		]);
	}
	protected function getStatusForSelect():array{
		return [
			[
				'title' => Translator::trans("redirect.search.choose"),
				'value' => ''
			],
			[
				'title' => Translator::trans("redirect.address.status.active"),
				'value' => Address::active
			],
			[
				'title' => Translator::trans("redirect.address.status.deactive"),
				'value' => Address::deactive
			]
		];
	}
	protected function getTypeForSelect():array{
		return [
			[
				'title' => Translator::trans("redirect.search.choose"),
				'value' => ''
			],
			[
				'title' => Translator::trans("redirect.address.status.permanent"),
				'value' => Address::permanent
			],
			[
				'title' => Translator::trans("redirect.address.status.temporary"),
				'value' => Address::temporary
			]
		];
	}
	public static function onSourceLoad(){
		parent::onSourceLoad();
		if(parent::$navigation){
			if($settings = Navigation::getByName("settings")){
				$item = new MenuItem("redirects");
				$item->setTitle(Translator::trans('redirects'));
				$item->setURL(userpanel\url('settings/redirects'));
				$item->setIcon('fa fa-external-link');
				$settings->addItem($item);
			}
		}
	}
	public function getComparisonsForSelect(){
		return [
			[
				'title' => Translator::trans('search.comparison.contains'),
				'value' => 'contains'
			],
			[
				'title' => Translator::trans('search.comparison.equals'),
				'value' => 'equals'
			],
			[
				'title' => Translator::trans('search.comparison.startswith'),
				'value' => 'startswith'
			]
		];
	}
}
