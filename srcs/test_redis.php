
 "Testing Redis Connection...\n";

// Test 1: Check if Redis extension is loaded
if (!extension_loaded('redis')) {
    echo "❌ Redis PHP extension not loaded\n";
    exit(1);
}
echo "✅ Redis PHP extension loaded\n";

// Test 2: Try to connect to Redis
$redis = new Redis();
try {
    $connected = $redis->connect('redis', 6379);
    if ($connected) {
        echo "✅ Connected to Redis server\n";
        
        // Test 3: Basic set/get
        $redis->set('test_key', 'Hello Redis!');
        $value = $redis->get('test_key');
        echo "✅ Set/get test: $value\n";
        
        // Test 4: Check Redis info
        $info = $redis->info();
        echo "✅ Redis version: " . $info['redis_version'] . "\n";
        echo "✅ Connected clients: " . $info['connected_clients'] . "\n";
        
        // Test 5: Check if session handler is Redis
        if (ini_get('session.save_handler') === 'redis') {
            echo "✅ Session handler: redis\n";
            echo "✅ Session path: " . ini_get('session.save_path') . "\n";
        } else {
            echo "❌ Session handler is not redis (" . ini_get('session.save_handler') . ")\n";
        }
        
        // Cleanup
        $redis->del('test_key');
        $redis->close();
        
    } else {
        echo "❌ Could not connect to Redis\n";
    }
} catch (Exception $e) {
    echo "❌ Redis connection failed: " . $e->getMessage() . "\n";
}

// Test 6: Check WordPress constants
echo "\nChecking WordPress Redis configuration:\n";
if (defined('WP_REDIS_HOST')) {
    echo "✅ WP_REDIS_HOST: " . WP_REDIS_HOST . "\n";
} else {
    echo "⚠️  WP_REDIS_HOST not defined\n";
}

if (defined('WP_REDIS_PORT')) {
    echo "✅ WP_REDIS_PORT: " . WP_REDIS_PORT . "\n";
} else {
    echo "⚠️  WP_REDIS_PORT not defined\n";
}

echo "\nTest complete!\n";
?>
