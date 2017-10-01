<?php

namespace Gini\ORM\Bank;

use \Gini\ORM\Object;

class City extends Object
{
    public $province = 'object:bank/province';
    public $name = 'string:40';
    public $code = 'string:10';
    
    protected static $db_index = [
        'unique:province,name',
        'code',
    ];
}
