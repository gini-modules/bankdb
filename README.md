# Bank DB

## 提供JSON-RPC API获取银行信息
1. `Bank.getBranches`
    ```php
    // 检索支行信息
    $branches = $rpc->Bank->getBranches('中国银行');
    $branches = $rpc->Bank->getBranches(['province'=>'天津']);
    $branches = $rpc->Bank->getBranches(['city'=>'贵阳']);
    ```
2. `Bank.getBranch`
    ```php
    // 获取支行具体信息
    $branch = $rpc->Bank->getBranch('中国银行');
    ```