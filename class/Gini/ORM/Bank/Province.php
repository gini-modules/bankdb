<?php

namespace Gini\ORM\Bank;

use \Gini\ORM\Object;

class Province extends Object
{
    public $name = 'string:40';
    public $code = 'string:10';
    
    protected static $db_index = [
        'unique:name',
        'unique:code',
    ];
}
