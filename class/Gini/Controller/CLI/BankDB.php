<?php

namespace Gini\Controller\CLI;

class Bank extends \Gini\Controller\CLI
{
    public function actionImportProvince(array $args) {
        class_exists('\Gini\Those');
        list($file) = $args;
        $fh = fopen($file, 'r');
        while ($row = fgetcsv($fh,0,"\t")) {
            echo J($row)."\n";
            $province = a('bank/province', ['code' => $row[0]]);
            $province->name = $row[1];
            $province->code = $row[0];
            $province->save();
        }
    }

    public function actionImportAreaCode(array $args) {
        class_exists('\Gini\Those');
        list($file) = $args;
        $fh = fopen($file, 'r');
        $provinces = [];
        $cities = [];
        while ($row = fgetcsv($fh,0,"\t")) {
            echo J($row)."\n";
            if (!isset($provinces[$row[1]])) {
                $province = a('bank/province', ['code' => $row[2]]);
                $province->name = $row[1];
                $province->code = $row[2];
                $province->save();
                $provinces[$row[1]] = true;
            }

            $city = a('bank/city');
            $city->province = $province;
            $city->name = $row[3];
            $city->code = $row[0];
            $city->save();
        }
    }

    public function actionImportCompany(array $args) {
        class_exists('\Gini\Those');
        list($file) = $args;
        $fh = fopen($file, 'r');
        while ($row = fgetcsv($fh,0,"\t")) {
            echo J($row)."\n";
            $company = a('bank/company', ['code' => $row[0]]);
            $company->name = $row[1];
            $company->code = $row[0];
            $company->save();
        }
    }

    public function actionImportBankInfo(array $args) {
        class_exists('\Gini\Those');
        list($file) = $args;
        $fh = fopen($file, 'r');
        while ($row = fgetcsv($fh,0,"\t")) {
            echo J($row)."\n";
            $company = a('bank/company', ['code' => $row[0]]);
            $city = a('bank/city', ['code' => $row[1]]);
            
            $branch = a('bank/branch');
            $branch->province = $city->province;
            $branch->city = $city;
            $branch->company = $company;
            $branch->name = $row[3];
            $branch->code = $row[2];
            $branch->save();
        }
    }

    private function _splitWords($str)
    {
        if (function_exists('\friso_split')) {
            $data = \friso_split($str, ['mode' => \FRISO_COMPLEX]);
            $words = array_unique(array_map(function($v){ return $v['word']; }, $data));
            return $words;
        }
        return [$str];
    }

    public function actionUpdateIndex() {
        class_exists('\Gini\Those');
        $db = a('bank/branch')->db();
        $SQL = 'SELECT bb.id AS id, bb.name AS name, bc.name AS bc_name,bcp.name AS bcp_name,bp.name AS bp_name FROM bank_branch AS bb LEFT JOIN bank_city AS bc ON bb.city_id = bc.id LEFT JOIN bank_company AS bcp ON bb.company_id = bcp.id LEFT JOIN bank_province AS bp ON bb.province_id = bp.id';
        $start = 0; $per_page = 1000;
        $i = 0;
        for (;;) {
            $st = $db->query($SQL . " LIMIT $start, $per_page");
            if (!$st || $st->count() == 0) break;
            $start += $per_page;
            while ($row = $st->row(\PDO::FETCH_OBJ)) {
                $name = strtr($row->name, [
                    $row->bp_name => '', $row->bc_name => '', $row->bcp_name => '',
                ]);
                $origWords = "{$row->bp_name}{$row->bc_name}{$row->bcp_name} {$name}";
                $words = implode(' ', array_filter($this->_splitWords($origWords), function($v) {
                    return !in_array($v, [
                            '有限公司', '股份', '分行', '支行', '中国',
                            '中华', '人民共和国', '银行', '金库', '支库', '国家',
                        ]);
                    }));
                $db->query('UPDATE bank_branch SET keywords=? WHERE id=?', null, [$words, $row->id]);
                printf("%6d. %s\n", $i, $words);
                $i ++;
            }
        }

    }
}