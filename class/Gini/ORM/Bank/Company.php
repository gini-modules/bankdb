<?php

namespace Gini\ORM\Bank;

use \Gini\ORM\Object;

class Company extends Object
{
    public $name = 'string:40';
    public $code = 'string:40';
    
    protected static $db_index = [
        'unique:name',
        'unique:code',
    ];
}
