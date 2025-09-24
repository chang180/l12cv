<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Predis\Connection\ConnectionException;
use RedisException;

class RedisTestController extends Controller
{
    /**
     * 測試 Redis 連接
     *
     * @return JsonResponse
     */
    public function testRedis(): JsonResponse
    {
        // 檢查 Redis 連接
        $connectionCheck = $this->checkRedisConnection();
        if (!$connectionCheck['success']) {
            return response()->json($connectionCheck, 503);
        }

        $results = [];

        try {
            // 1. 基本連接測試
            $ping = Redis::ping();
            $results['connection'] = [
                'status' => $ping ? 'connected' : 'failed',
                'server_host' => config('database.redis.default.host'),
                'server_port' => config('database.redis.default.port'),
                'database' => config('database.redis.default.database'),
                'description' => 'Redis 連接狀態測試'
            ];

            // 2. 基本的字符串操作測試
            $testKey = 'demo_key_' . time();
            $testValue = 'Hello Redis Demo!';
            Redis::set($testKey, $testValue);
            $retrievedValue = Redis::get($testKey);

            $results['basic_test'] = [
                'key' => $testKey,
                'value' => $retrievedValue,
                'test_passed' => $testValue === $retrievedValue,
                'description' => '基本的字符串鍵值對讀寫測試'
            ];

            // 清理測試數據
            Redis::del($testKey);

            // 3. 獲取 Redis 基本信息
            $info = Redis::info();
            $results['redis_info'] = [
                'version' => $info['redis_version'] ?? 'unknown',
                'connected_clients' => $info['connected_clients'] ?? 'unknown',
                'used_memory_human' => $info['used_memory_human'] ?? 'unknown',
                'total_commands_processed' => $info['total_commands_processed'] ?? 'unknown',
                'uptime_in_seconds' => $info['uptime_in_seconds'] ?? 'unknown',
                'description' => 'Redis 服務器基本信息'
            ];

            // 返回成功結果
            return response()->json([
                'success' => true,
                'message' => 'Redis 測試完成',
                'timestamp' => now()->toDateTimeString(),
                'results' => $results
            ]);

        } catch (ConnectionException $e) {
            return response()->json([
                'success' => false,
                'error_type' => 'connection_error',
                'message' => 'Redis 連接錯誤',
                'details' => $e->getMessage(),
                'timestamp' => now()->toDateTimeString()
            ], 503);

        } catch (RedisException $e) {
            return response()->json([
                'success' => false,
                'error_type' => 'redis_error',
                'message' => 'Redis 操作錯誤',
                'details' => $e->getMessage(),
                'timestamp' => now()->toDateTimeString()
            ], 500);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error_type' => 'general_error',
                'message' => '未知錯誤',
                'details' => $e->getMessage(),
                'timestamp' => now()->toDateTimeString()
            ], 500);
        }
    }

    /**
     * 清除 Redis 緩存數據
     *
     * @return JsonResponse
     */
    public function clearRedis(): JsonResponse
    {
        // 檢查 Redis 連接
        $connectionCheck = $this->checkRedisConnection();
        if (!$connectionCheck['success']) {
            return response()->json($connectionCheck, 503);
        }

        try {
            // 獲取所有符合應用前綴的鍵
            $prefix = config('database.redis.options.prefix', 'laravel_database_');
            $keys = Redis::keys($prefix . '*');

            $clearedCount = 0;
            if (!empty($keys)) {
                // 批量刪除所有鍵
                Redis::del($keys);
                $clearedCount = count($keys);
            }

            // 創建一筆測試數據
            $demoKey = 'demo_key_' . time();
            $demoValue = 'Hello Redis Demo! Created at ' . now()->toDateTimeString();
            Redis::set($demoKey, $demoValue);
            Redis::expire($demoKey, 3600); // 1小時過期

            return response()->json([
                'success' => true,
                'message' => 'Redis 緩存清除完成',
                'cleared_keys_count' => $clearedCount,
                'demo_data' => [
                    'key' => $demoKey,
                    'value' => Redis::get($demoKey),
                    'ttl_seconds' => Redis::ttl($demoKey),
                    'expires_at' => now()->addSeconds(Redis::ttl($demoKey))->toDateTimeString()
                ],
                'timestamp' => now()->toDateTimeString()
            ]);

        } catch (ConnectionException $e) {
            return response()->json([
                'success' => false,
                'error_type' => 'connection_error',
                'message' => 'Redis 連接錯誤，無法清除緩存',
                'details' => $e->getMessage(),
                'timestamp' => now()->toDateTimeString()
            ], 503);

        } catch (RedisException $e) {
            return response()->json([
                'success' => false,
                'error_type' => 'redis_error',
                'message' => 'Redis 操作錯誤',
                'details' => $e->getMessage(),
                'timestamp' => now()->toDateTimeString()
            ], 500);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error_type' => 'general_error',
                'message' => '清除緩存時發生未知錯誤',
                'details' => $e->getMessage(),
                'timestamp' => now()->toDateTimeString()
            ], 500);
        }
    }

    /**
     * 檢查 Redis 連接和配置
     *
     * @return array
     */
    private function checkRedisConnection(): array
    {
        // 1. 檢查 Redis 配置是否存在
        $redisConfig = config('database.redis');
        if (empty($redisConfig) || empty($redisConfig['default'])) {
            return [
                'success' => false,
                'error_type' => 'configuration_error',
                'message' => 'Redis 未配置',
                'details' => '未找到 Redis 配置，請檢查 config/database.php 中的 Redis 設置',
                'suggestions' => [
                    '確保 config/database.php 包含 Redis 配置',
                    '檢查環境變數 REDIS_HOST, REDIS_PORT, REDIS_PASSWORD',
                    '運行 php artisan config:cache 更新配置緩存'
                ],
                'timestamp' => now()->toDateTimeString()
            ];
        }

        // 2. 檢查必要的配置項
        $defaultConfig = $redisConfig['default'];
        if (empty($defaultConfig['host']) && empty($defaultConfig['url'])) {
            return [
                'success' => false,
                'error_type' => 'configuration_error',
                'message' => 'Redis 主機配置缺失',
                'details' => 'Redis 配置中缺少主機地址 (host) 或連接URL (url)',
                'current_config' => [
                    'host' => $defaultConfig['host'] ?? 'not set',
                    'port' => $defaultConfig['port'] ?? 'not set',
                    'url' => $defaultConfig['url'] ?? 'not set'
                ],
                'timestamp' => now()->toDateTimeString()
            ];
        }

        // 3. 嘗試連接 Redis
        try {
            $ping = Redis::ping();
            if (!$ping) {
                return [
                    'success' => false,
                    'error_type' => 'connection_error',
                    'message' => 'Redis 連接失敗',
                    'details' => 'ping 命令無響應',
                    'server_info' => [
                        'host' => $defaultConfig['host'] ?? $defaultConfig['url'],
                        'port' => $defaultConfig['port'] ?? 'default',
                        'database' => $defaultConfig['database'] ?? 0
                    ],
                    'timestamp' => now()->toDateTimeString()
                ];
            }
        } catch (ConnectionException $e) {
            return [
                'success' => false,
                'error_type' => 'connection_error',
                'message' => 'Redis 伺服器連接失敗',
                'details' => $e->getMessage(),
                'server_info' => [
                    'host' => $defaultConfig['host'] ?? $defaultConfig['url'],
                    'port' => $defaultConfig['port'] ?? 'default',
                    'database' => $defaultConfig['database'] ?? 0
                ],
                'suggestions' => [
                    '檢查 Redis 服務器是否正在運行',
                    '確認網路連接和防火牆設置',
                    '驗證主機地址和端口號是否正確',
                    '檢查 Redis 認證設置（如果有密碼）'
                ],
                'timestamp' => now()->toDateTimeString()
            ];
        } catch (RedisException $e) {
            return [
                'success' => false,
                'error_type' => 'redis_error',
                'message' => 'Redis 擴展錯誤',
                'details' => $e->getMessage(),
                'suggestions' => [
                    '檢查 Redis PHP 擴展是否正確安裝',
                    '確認 PHP Redis 擴展版本兼容性'
                ],
                'timestamp' => now()->toDateTimeString()
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error_type' => 'general_error',
                'message' => '未知的 Redis 連接錯誤',
                'details' => $e->getMessage(),
                'timestamp' => now()->toDateTimeString()
            ];
        }

        return ['success' => true];
    }
}