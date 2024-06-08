<?php
namespace packages\redirect;
use \packages\userpanel\Authorization as UserPanelAuthorization;
use \packages\userpanel\Authentication;
class Authorization extends UserPanelAuthorization{
	static function is_accessed($permission, $prefix = 'redirect'){
		return parent::is_accessed($permission, $prefix);
	}
	static function haveOrFail($permission, $prefix = 'redirect'){
		parent::haveOrFail($permission, $prefix);
	}
}
