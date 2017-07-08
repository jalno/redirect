<?php
namespace packages\redirect\views\address;
use \packages\redirect\authorization;
use \packages\redirect\views\listview;
use \packages\base\views\traits\form as formTrait;
class search extends listview{
	use formTrait;
	protected $canAdd;
	protected $canEdit;
	protected $canDel;
	static protected $navigation;
	function __construct(){
		$this->canAdd = authorization::is_accessed('add');
		$this->canEdit = authorization::is_accessed('edit');
		$this->canDel = authorization::is_accessed('delete');
	}
	public function getAddressLists(){
		return $this->dataList;
	}
	public static function onSourceLoad(){
		self::$navigation = authorization::is_accessed('search');
	}
}
