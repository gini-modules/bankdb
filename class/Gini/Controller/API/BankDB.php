<?php

namespace Gini\Controller\API;

class BankDB
{

    public function actionGetBranches($criteria, $start=0, $perPage=1000)
    {
        return \Gini\BankDB::getBranches($criteria, $start, $perPage);
    }

    public function actionGetBranch($name)
    {
        return \Gini\BankDB::getBranch($name);
    }

}
