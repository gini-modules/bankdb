# Bank DB

## 提供JSON-RPC API获取银行信息
1. `Bank.getBranches`
    ```php
    // 检索支行信息
    $branches = $rpc->BankDB->getBranches('中国银行');
    $branches = $rpc->BankDB->getBranches(['province'=>'天津']);
    $branches = $rpc->BankDB->getBranches(['city'=>'贵阳']);
    ```
2. `Bank.getBranch`
    ```php
    // 获取支行具体信息
    $branch = $rpc->BankDB->getBranch('中国银行');
    ```

## 配置信息
```bash
# sample of APP/.env
DB_DSN=mysql:dbname=bankdb;host=127.0.0.1
DB_USER=my_user
DB_PASS=my_pass
```

## 函数
1. `\Gini\BankDB::getBranch($name)`
    ```php
    $branch = \Gini\BankDB::getBranch('中国工商银行天津市解放北路支行');
    ```
2. `\Gini\BankDB::getBranches($critiera, $start=0, $perPage=1000)`
    ```php
    $branches = \Gini\BankDB::getBranches('天津', 0, 20);
    $branches = \Gini\BankDB::getBranches([
        '*' => '白堤路',
        'city' => '天津市',
    ], 1, 5);
    ```
