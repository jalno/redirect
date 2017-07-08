<?php
namespace packages\redirect;
use \packages\base\db\dbObject;
class address extends dbObject{
	const active = 1;
	const deactive = 2;
	const permanent = 301;
	const temporary = 302;
	protected $dbTable = "redirect_addresses";
	protected $primaryKey = "id";
	protected $dbFields = [
        'source' => ['type' => 'text', 'required' => true],
        'type' => ['type' => 'int', 'required' => true],
        'destination' => ['type' => 'text', 'required' => true],
		'status' => ['type' => 'int', 'required' => true]
	];
}