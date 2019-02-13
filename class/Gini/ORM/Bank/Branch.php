<?php

namespace Gini\ORM\Bank;

use \Gini\ORM\Object;

class Branch extends Object
{
    public $name = 'string:40';
    public $code = 'string:40';

    public $company = 'object:bank/company';
    public $city = 'object:bank/city';
    public $province = 'object:bank/province';

    public $keywords = 'string:120';
    
    protected static $db_index = [
        'unique:name',
        'unique:code',
        'fulltext:keywords',
        'company,province,city'  // labmai vip 需要联动搜索获取银行信息，添加银行、省、市复合索引
    ];
}
