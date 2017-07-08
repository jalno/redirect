<?php
namespace packages\redirect\listeners\settings;
use \packages\userpanel\usertype\permissions;
class usertype{
	public function permissions_list(){
		$permissions = array(
			'search',
			'add',
			'delete',
			'edit'
		);
		foreach($permissions as $permission){
			permissions::add('redirect_'.$permission);
		}
	}
}
