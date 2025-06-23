<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class RedisTestController extends Controller
{
    /**
     * 測試 Redis 連接
     *
     * @return \Illuminate\Http\Response
     */
    public function testRedis()
    {
        // 現有代碼保持不變...
        $results = [];

        // 1. 基本的字符串操作
        Redis::set('test_key', 'Hello Redis!');
        $results['string'] = [
                'key' => 'test_key',
            'value' => Redis::get('test_key'),
            'description' => '基本的字符串鍵值對'
        ];

        // 2. 使用 Hash 存儲多個鍵值對映射
        Redis::hset('user:1001', 'name', '張三');
        Redis::hset('user:1001', 'email', 'zhang@example.com');
        Redis::hset('user:1001', 'age', '28');

        $results['hash'] = [
            'key' => 'user:1001',
            'values' => Redis::hgetall('user:1001'),
            'name_only' => Redis::hget('user:1001', 'name'),
            'description' => 'Hash 結構可以在一個鍵下存儲多個欄位值對'
        ];

        // 3. 列表操作
        Redis::del('recent_logins'); // 先刪除，以防之前有測試數據
        Redis::lpush('recent_logins', 'user_123');
        Redis::lpush('recent_logins', 'user_456');
        Redis::lpush('recent_logins', 'user_789');

        $results['list'] = [
            'key' => 'recent_logins',
            'values' => Redis::lrange('recent_logins', 0, -1),
            'length' => Redis::llen('recent_logins'),
            'description' => '列表結構可以存儲有序的元素集合'
        ];

        // 4. 集合操作
        Redis::del('active_users'); // 先刪除，以防之前有測試數據
        Redis::sadd('active_users', 'user_111');
        Redis::sadd('active_users', 'user_222');
        Redis::sadd('active_users', 'user_333');
        Redis::sadd('active_users', 'user_111'); // 重複添加，集合會自動去重

        $results['set'] = [
            'key' => 'active_users',
            'values' => Redis::smembers('active_users'),
            'count' => Redis::scard('active_users'),
            'contains_user_111' => Redis::sismember('active_users', 'user_111'),
            'description' => '集合結構可以存儲唯一的元素，自動去重'
        ];

        // 5. 有序集合
        Redis::del('leaderboard'); // 先刪除，以防之前有測試數據
        Redis::zadd('leaderboard', 100, 'player_1');
        Redis::zadd('leaderboard', 85, 'player_2');
        Redis::zadd('leaderboard', 150, 'player_3');
        Redis::zadd('leaderboard', 125, 'player_4');

        $results['sorted_set'] = [
            'key' => 'leaderboard',
            'all_players' => Redis::zrange('leaderboard', 0, -1, 'WITHSCORES'),
            'top_players' => Redis::zrevrange('leaderboard', 0, 2, 'WITHSCORES'), // 前3名
            'player_1_rank' => Redis::zrevrank('leaderboard', 'player_1') + 1, // +1 因為索引從0開始
            'description' => '有序集合可以為每個元素關聯一個分數，並按分數排序'
        ];

        // 6. 設置過期時間
        Redis::set('temp_token', 'abc123');
        Redis::expire('temp_token', 60); // 60秒後過期

        $results['expiration'] = [
            'key' => 'temp_token',
            'value' => Redis::get('temp_token'),
            'ttl' => Redis::ttl('temp_token'), // 剩餘生存時間(秒)
            'description' => '可以為鍵設置過期時間，過期後自動刪除'
        ];

        // 7. 原子計數器
        Redis::set('page_views', 0);
        Redis::incr('page_views');
        Redis::incr('page_views');
        Redis::incrby('page_views', 3);

        $results['counter'] = [
            'key' => 'page_views',
            'value' => Redis::get('page_views'),
            'description' => '原子計數器，可以安全地增加或減少數值'
        ];

        // 8. 使用管道(Pipeline)批量操作，提高效率
        $responses = Redis::pipeline(function ($pipe) {
            $pipe->set('pipeline_test', 'value1');
            $pipe->get('pipeline_test');
            $pipe->hset('pipeline_hash', 'field1', 'value1');
            $pipe->hget('pipeline_hash', 'field1');
        });

        $results['pipeline'] = [
            'responses' => $responses,
            'description' => '管道允許一次性發送多個命令，減少網絡往返'
        ];

        // 返回結果
        return response()->json([
            'success' => true,
            'message' => 'Redis 高級功能測試成功',
            'timestamp' => now()->toDateTimeString(),
            'results' => $results
        ]);
    }

    /**
     * 演示 Redis 在實際應用中的用例
     *
     * @return \Illuminate\Http\Response
     */
    public function practicalUseCases()
    {
        // 現有代碼保持不變...
        $results = [];

        // 1. 用戶會話管理
        $sessionId = 'sess_' . uniqid();
        Redis::hmset($sessionId, [
            'user_id' => 1001,
            'username' => '張三',
            'login_time' => now()->toDateTimeString(),
            'ip_address' => request()->ip()
        ]);
        Redis::expire($sessionId, 3600); // 1小時後過期

        $results['session_management'] = [
            'session_id' => $sessionId,
            'session_data' => Redis::hgetall($sessionId),
            'ttl' => Redis::ttl($sessionId),
            'description' => '使用Redis存儲會話數據，比文件會話更快且可擴展'
        ];

        // 2. 訪問頻率限制 (Rate Limiting)
        $ip = request()->ip();
        $key = "rate_limit:$ip";
        Redis::incr($key);

        // 第一次設置才會添加過期時間
        if (Redis::ttl($key) < 0) {
            Redis::expire($key, 60); // 60秒窗口
        }

        $results['rate_limiting'] = [
            'ip' => $ip,
            'requests_in_window' => Redis::get($key),
            'window_reset_in' => Redis::ttl($key) . ' seconds',
            'description' => '限制API請求頻率，防止濫用'
        ];

        // 3. 緩存查詢結果
        $cacheKey = 'cached_products';

        // 檢查緩存中是否已存在
        if (!Redis::exists($cacheKey)) {
            // 實際應用中，這裡會是數據庫查詢
            $products = [
                ['id' => 1, 'name' => '產品A', 'price' => 99],
                ['id' => 2, 'name' => '產品B', 'price' => 199],
                ['id' => 3, 'name' => '產品C', 'price' => 299],
            ];

            // 將結果序列化後存入Redis
            Redis::set($cacheKey, json_encode($products));
            Redis::expire($cacheKey, 300); // 5分鐘過期

            $source = '數據庫';
        } else {
            // 從緩存中獲取
            $products = json_decode(Redis::get($cacheKey), true);
            $source = '緩存';
        }

        $results['caching'] = [
            'data_source' => $source,
            'products' => $products,
            'cache_ttl' => Redis::ttl($cacheKey),
            'description' => '緩存頻繁訪問的數據，減少數據庫負載'
        ];

        // 4. 消息隊列簡單實現
        Redis::rpush('task_queue', json_encode(['task' => 'send_email', 'to' => 'user@example.com']));
        Redis::rpush('task_queue', json_encode(['task' => 'generate_report', 'type' => 'monthly']));

        // 模擬消費者處理
        $nextTask = Redis::lpop('task_queue');

        $results['message_queue'] = [
            'queued_tasks' => Redis::llen('task_queue') + 1, // +1 因為我們已經取出一個
            'next_task' => json_decode($nextTask, true),
            'description' => '簡單的消息隊列實現，用於異步任務處理'
        ];

        // 返回結果
        return response()->json([
            'success' => true,
            'message' => 'Redis 實際應用案例演示',
            'timestamp' => now()->toDateTimeString(),
            'results' => $results
        ]);
    }

    /**
     * 演示信用卡交易緩存系統 - 修正版
     * 可以通過交易序號或商店訂單編號查詢交易資料
     *
     * @return \Illuminate\Http\Response
     */
    public function creditCardTransactionCache()
    {
        $results = [];

        // 清除之前的測試數據
        Redis::del('Exists:tx:merchant_123:TX789');  // 交易序號存在標誌
        Redis::del('Exists:order:merchant_123:ORDER123');  // 商店訂單編號存在標誌
        Redis::del('sys:tx:merchant_123:TX789');  // 交易序號對應的交易數據
        Redis::del('sys:order:merchant_123:ORDER123');  // 商店訂單編號對應的交易數據
        Redis::del('map:tx_to_order:merchant_123:TX789');  // 交易序號到商店訂單編號的映射
        Redis::del('map:order_to_tx:merchant_123:ORDER123');  // 商店訂單編號到交易序號的映射
        Redis::del('sub:tx:merchant_123:TX789');  // 交易序號對應的信用卡數據

        // 模擬數據庫中的交易記錄 (TS表)
        $transactionData = [
            'ts_sys_id' => 'SYS123456', // 系統生成的唯一ID，作為主鍵
            'transaction_id' => 'TX789', // 交易序號 (您的系統流水號)
            'store_order_id' => 'ORDER123', // 商店訂單編號 (商店自己的訂單流水號)
            'merchant_id' => 'merchant_123',
            'store_id' => 'STORE456', // 商店編號
            'amount' => 1250.75,
            'currency' => 'TWD',
            'status' => 'completed',
            'timestamp' => now()->toDateTimeString(),
            'customer_id' => 'CUST456',
            'payment_method' => 'credit_card'
        ];

        // 模擬數據庫中的信用卡資料 (TC表)
        $creditCardData = [
            'tc_id' => 'TC987654', // 信用卡表主鍵
            'ts_sys_id' => 'SYS123456', // 外鍵，關聯到TS表
            'card_id' => 'CARD789',
            'merchant_id' => 'merchant_123',
            'store_id' => 'STORE456', // 商店編號
            'card_type' => 'VISA',
            'last_four' => '1234',
            'expiry_date' => '12/25',
            'cardholder_name' => '王小明',
            'is_default' => true
        ];

        // 1. 存儲交易記錄緩存 - 使用交易序號作為主要鍵
        // 1.1 設置交易序號存在標誌
        Redis::set('Exists:tx:merchant_123:TX789', 1);

        // 1.2 設置商店訂單編號存在標誌
        Redis::set('Exists:order:merchant_123:ORDER123', 1);

        // 1.3 存儲交易數據 - 通過交易序號訪問
        Redis::hmset('sys:tx:merchant_123:TX789', $transactionData);

        // 1.4 存儲交易數據 - 通過商店訂單編號訪問 (這是一個指向相同數據的引用)
        Redis::set('sys:order:merchant_123:ORDER123', 'sys:tx:merchant_123:TX789');

        // 1.5 建立交易序號和商店訂單編號之間的雙向映射
        Redis::set('map:tx_to_order:merchant_123:TX789', 'ORDER123');
        Redis::set('map:order_to_tx:merchant_123:ORDER123', 'TX789');

        // 1.6 存儲信用卡數據 - 通過交易序號關聯
        Redis::hmset('sub:tx:merchant_123:TX789', $creditCardData);

        // 1.7 設置所有鍵的過期時間 (例如24小時)
        $keys = [
            'Exists:tx:merchant_123:TX789',
            'Exists:order:merchant_123:ORDER123',
            'sys:tx:merchant_123:TX789',
            'sys:order:merchant_123:ORDER123',
            'map:tx_to_order:merchant_123:TX789',
            'map:order_to_tx:merchant_123:ORDER123',
            'sub:tx:merchant_123:TX789'
        ];

        foreach ($keys as $key) {
            Redis::expire($key, 86400);
        }

        // 2. 模擬查詢流程 - 通過交易序號查詢
        $results['query_by_transaction_id'] = $this->queryTransaction('TX789', 'merchant_123', 'transaction');

        // 3. 模擬查詢流程 - 通過商店訂單編號查詢
        $results['query_by_store_order_id'] = $this->queryTransaction('ORDER123', 'merchant_123', 'order');

        // 4. 模擬快速檢查多個交易是否存在
        $transactionIds = ['TX789', 'TX790', 'TX791'];
        $existingTransactions = [];

        foreach ($transactionIds as $txId) {
            $existsKey = 'Exists:tx:merchant_123:' . $txId;
            if (Redis::exists($existsKey)) {
                $existingTransactions[] = $txId;
            }
        }

        $results['bulk_check_transactions'] = [
            'checked_transactions' => $transactionIds,
            'existing_transactions' => $existingTransactions,
            'description' => '快速檢查多個交易序號是否存在'
        ];

        // 5. 模擬快速檢查多個商店訂單是否存在
        $orderIds = ['ORDER123', 'ORDER124', 'ORDER125'];
        $existingOrders = [];

        foreach ($orderIds as $orderId) {
            $existsKey = 'Exists:order:merchant_123:' . $orderId;
            if (Redis::exists($existsKey)) {
                $existingOrders[] = $orderId;
            }
        }

        $results['bulk_check_orders'] = [
            'checked_orders' => $orderIds,
            'existing_orders' => $existingOrders,
            'description' => '快速檢查多個商店訂單編號是否存在'
        ];

        // 6. 模擬使用Redis進行交易計數和統計
        Redis::incr('stats:merchant_123:total_transactions');
        Redis::hincrby('stats:merchant_123:transactions_by_store', 'STORE456', 1);
        Redis::hincrby('stats:merchant_123:amount_by_store', 'STORE456', 1250);

        $results['transaction_stats'] = [
            'merchant_id' => 'merchant_123',
            'total_transactions' => Redis::get('stats:merchant_123:total_transactions'),
            'transactions_by_store' => Redis::hgetall('stats:merchant_123:transactions_by_store'),
            'amount_by_store' => Redis::hgetall('stats:merchant_123:amount_by_store'),
            'description' => '使用Redis進行交易統計'
        ];

        // 返回結果
        return response()->json([
            'success' => true,
            'message' => '信用卡交易緩存系統演示 - 支持通過交易序號或商店訂單編號查詢',
            'timestamp' => now()->toDateTimeString(),
            'results' => $results
        ]);
    }

    /**
     * 輔助方法：查詢交易數據
     *
     * @param string $id 交易序號或商店訂單編號
     * @param string $merchantId 商戶ID
     * @param string $type 查詢類型 (transaction 或 order)
     * @return array 查詢結果
     */
    private function queryTransaction($id, $merchantId, $type)
    {
        $prefix = ($type === 'transaction') ? 'tx' : 'order';
        $idType = ($type === 'transaction') ? '交易序號' : '商店訂單編號';

        // 步驟1: 檢查是否存在
        $existsKey = 'Exists:' . $prefix . ':' . $merchantId . ':' . $id;
        $exists = Redis::exists($existsKey);

        if ($exists) {
            // 步驟2: 獲取交易數據
            if ($type === 'transaction') {
                // 直接通過交易序號獲取數據
                $dataKey = 'sys:' . $prefix . ':' . $merchantId . ':' . $id;
                $transaction = Redis::hgetall($dataKey);

                // 獲取關聯的信用卡數據
                $cardKey = 'sub:' . $prefix . ':' . $merchantId . ':' . $id;
                $creditCard = Redis::hgetall($cardKey);

                // 獲取關聯的商店訂單編號
                $orderId = Redis::get('map:tx_to_order:' . $merchantId . ':' . $id);
            } else {
                // 通過商店訂單編號獲取交易序號
                $txId = Redis::get('map:order_to_tx:' . $merchantId . ':' . $id);

                // 通過交易序號獲取數據
                $dataKey = 'sys:tx:' . $merchantId . ':' . $txId;
                $transaction = Redis::hgetall($dataKey);

                // 獲取關聯的信用卡數據
                $cardKey = 'sub:tx:' . $merchantId . ':' . $txId;
                $creditCard = Redis::hgetall($cardKey);

                $orderId = $id;
            }

            $dataSource = '緩存';
        } else {
            // 步驟3: 如果緩存中不存在，則從數據庫獲取 (這裡模擬)
            // 在實際應用中，這裡會查詢數據庫

            // 模擬數據庫中的交易記錄 (TS表)
            $transaction = [
                'ts_sys_id' => 'SYS123456', // 系統生成的唯一ID，作為主鍵
                'transaction_id' => 'TX789', // 交易序號 (您的系統流水號)
                'store_order_id' => 'ORDER123', // 商店訂單編號 (商店自己的訂單流水號)
                'merchant_id' => $merchantId,
                'store_id' => 'STORE456', // 商店編號
                'amount' => 1250.75,
                'currency' => 'TWD',
                'status' => 'completed',
                'timestamp' => now()->toDateTimeString(),
                'customer_id' => 'CUST456',
                'payment_method' => 'credit_card'
            ];

            // 模擬數據庫中的信用卡資料 (TC表)
            $creditCard = [
                'tc_id' => 'TC987654', // 信用卡表主鍵
                'ts_sys_id' => 'SYS123456', // 外鍵，關聯到TS表
                'card_id' => 'CARD789',
                'merchant_id' => $merchantId,
                'store_id' => 'STORE456', // 商店編號
                'card_type' => 'VISA',
                'last_four' => '1234',
                'expiry_date' => '12/25',
                'cardholder_name' => '王小明',
                'is_default' => true
            ];

            // 獲取交易序號和商店訂單編號
            $txId = ($type === 'transaction') ? $id : $transaction['transaction_id'];
            $orderId = ($type === 'order') ? $id : $transaction['store_order_id'];

            // 步驟4: 將數據存入緩存
            // 4.1 設置存在標誌
            Redis::set('Exists:tx:' . $merchantId . ':' . $txId, 1);
            Redis::set('Exists:order:' . $merchantId . ':' . $orderId, 1);

            // 4.2 存儲交易數據
            Redis::hmset('sys:tx:' . $merchantId . ':' . $txId, $transaction);
            Redis::set('sys:order:' . $merchantId . ':' . $orderId, 'sys:tx:' . $merchantId . ':' . $txId);

            // 4.3 建立映射關係
            Redis::set('map:tx_to_order:' . $merchantId . ':' . $txId, $orderId);
            Redis::set('map:order_to_tx:' . $merchantId . ':' . $orderId, $txId);

            // 4.4 存儲信用卡數據
            Redis::hmset('sub:tx:' . $merchantId . ':' . $txId, $creditCard);

            // 4.5 設置過期時間
            $keys = [
                'Exists:tx:' . $merchantId . ':' . $txId,
                'Exists:order:' . $merchantId . ':' . $orderId,
                'sys:tx:' . $merchantId . ':' . $txId,
                'sys:order:' . $merchantId . ':' . $orderId,
                'map:tx_to_order:' . $merchantId . ':' . $txId,
                'map:order_to_tx:' . $merchantId . ':' . $orderId,
                'sub:tx:' . $merchantId . ':' . $txId
            ];

            foreach ($keys as $key) {
                Redis::expire($key, 86400);
            }

            $dataSource = '數據庫';
        }

        // 返回查詢結果
        return [
            'id' => $id,
            'id_type' => $idType,
            'merchant_id' => $merchantId,
            'exists_in_cache' => $exists ? 'Yes' : 'No',
            'data_source' => $dataSource,
            'transaction_data' => $transaction,
            'credit_card_data' => $creditCard,
            'related_ids' => [
                'transaction_id' => $transaction['transaction_id'],
                'store_order_id' => $transaction['store_order_id']
            ],
            'cache_keys' => [
                'exists' => 'Exists:' . $prefix . ':' . $merchantId . ':' . $id,
                'transaction' => 'sys:tx:' . $merchantId . ':' . $transaction['transaction_id'],
                'credit_card' => 'sub:tx:' . $merchantId . ':' . $transaction['transaction_id'],
                'mapping' => ($type === 'transaction')
                    ? 'map:tx_to_order:' . $merchantId . ':' . $id
                    : 'map:order_to_tx:' . $merchantId . ':' . $id
            ]
        ];
    }
}
