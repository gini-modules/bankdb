<?php

namespace Gini\Controller\API;

class Bank
{
    private function _getBranchInfo($branch, $province=null, $city=null, $company=null) {
        $province or $province = $branch->province;
        $city or $city = $branch->city;
        $company or $company = $branch->company;
        
        return [
            'name' => $branch->name,
            'code' => $branch->code,
            'province' => $province->id ? [
                'name' => $province->name,
                'code' => $province->code,
            ] : false,
            'city' => $city->id ? [
                'name' => $city->name,
                'code' => $city->code,
            ] : false,
            'company' => $company->id ? [
                'name' => $company->name,
                'code' => $company->code,
            ] : false,
        ];
    }

    private function _splitWords($str)
    {
        if (function_exists('\friso_split')) {
            $data = \friso_split($str, ['mode' => \FRISO_COMPLEX]);
            $words = array_unique(array_map(function($v){ return $v['word']; }, $data));
            return implode(' ', $words);
        }
        return $str;
    }

    public function actionGetBranches($criteria, $start=0, $perPage=1000)
    {
        class_exists('\\Gini\\Those');
        if (is_string($criteria)) {
            $criteria = ['*' => $criteria];
        }

        $branches = those('bank/branch');
        if (isset($criteria['*'])) {
            $words = $this->_splitWords($criteria);
            $branches = $branches->whose('keywords')->isRelatedTo($words);
        }

        if (isset($criteria['province'])) {
            $branches = $branches->whose('province')->is(a('bank/province', ['name' => $criteria['province']]));
        }

        if (isset($criteria['province_code'])) {
            $branches = $branches->whose('province')->is(a('bank/province', ['code' => $criteria['province_code']]));
        }

        if (isset($criteria['city'])) {
            $branches = $branches->whose('city')->is(a('bank/city', ['name' => $criteria['city']]));
        }

        if (isset($criteria['city_code'])) {
            $branches = $branches->whose('city')->is(a('bank/city', ['code' => $criteria['city_code']]));
        }

        if (isset($criteria['company'])) {
            $branches = $branches->whose('company')->is(a('bank/company', ['name' => $criteria['company']]));
        }

        if (isset($criteria['company_code'])) {
            $branches = $branches->whose('company')->is(a('bank/company', ['code' => $criteria['company_code']]));
        }

        $branches->limit(max(0, $start), min($perPage, 1000));
        $branch_ids = $branches->get('id', 'id');
        $provinces = those('bank/province')->whose('id')->isIn(array_unique(
            $branches->get('id', 'province_id')
        ));
        $cities = those('bank/city')->whose('id')->isIn(array_unique(
            $branches->get('id', 'city_id')
        ));
        $companies = those('bank/company')->whose('id')->isIn(array_unique(
            $branches->get('id', 'company_id')
        ));

        return array_map(function($branch) use ($provinces, $cities, $companies) {
            return $this->_getBranchInfo($branch, 
                $provinces[$branch->province_id],
                $cities[$branch->city_id],
                $companies[$branch->company_id]
            );
        }, iterator_to_array($branches, false));
    }

    public function actionGetBranch($name)
    {
        class_exists('\\Gini\\Those');
        $branch = a('bank/branch', ['name' => $name]);
        return $this->_getBranchInfo($branch);
    }

}
