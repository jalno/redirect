<?php

namespace packages\redirect;

use packages\base\DB\DBObject;

class Address extends DBObject
{
    public const active = 1;
    public const deactive = 2;
    public const permanent = 301;
    public const temporary = 302;
    protected $dbTable = 'redirect_addresses';
    protected $primaryKey = 'id';
    protected $dbFields = [
        'source' => ['type' => 'text', 'required' => true],
        'type' => ['type' => 'int', 'required' => true],
        'destination' => ['type' => 'text', 'required' => true],
        'hits' => ['type' => 'int'],
        'status' => ['type' => 'int', 'required' => true],
    ];

    public function isRegex(): bool
    {
        return preg_match('/^\\/.+\\/i?$/', $this->source);
    }

    public function hit()
    {
        ++$this->hits;

        return $this->save();
    }
}
