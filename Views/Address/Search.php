<?php
namespace packages\redirect\Views\Address;
use \packages\redirect\Authorization;
use \packages\redirect\Views\ListView;
use \packages\base\Views\Traits\Form as FormTrait;
class Search extends ListView{
	use formTrait;
	protected $canAdd;
	protected $canEdit;
	protected $canDel;
	static protected $navigation;
	function __construct(){
		$this->canAdd = Authorization::is_accessed('add');
		$this->canEdit = Authorization::is_accessed('edit');
		$this->canDel = Authorization::is_accessed('delete');
	}
	public function getAddressLists(){
		return $this->dataList;
	}
	public static function onSourceLoad(){
		self::$navigation = Authorization::is_accessed('search');
	}
}
