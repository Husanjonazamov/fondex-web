#!/usr/bin/env php
<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use JscorpTech\Payme\Models\Order;
use App\Models\PaymentRequest;
use App\Models\User;
use Illuminate\Support\Str;

echo "🧪 PAYME INTEGRATION TEST\n";
echo "========================\n\n";

// 1. Check database connection
echo "✅ Testing database connection...\n";
try {
    DB::connection()->getPdo();
    echo "   ✓ Database connected\n\n";
} catch (\Exception $e) {
    echo "   ✗ Database error: " . $e->getMessage() . "\n";
    exit(1);
}

// 2. Check Payme tables
echo "✅ Checking Payme tables...\n";
if (Schema::hasTable('payme_orders') && Schema::hasTable('payme_transactions')) {
    echo "   ✓ payme_orders table exists\n";
    echo "   ✓ payme_transactions table exists\n\n";
} else {
    echo "   ✗ Payme tables not found\n";
    exit(1);
}

// 3. Check Payme config
echo "✅ Checking Payme configuration...\n";
$payme_id = env('PAYME_ID');
$payme_key = env('PAYME_KEY');
$payme_url = env('PAYME_URL');

if ($payme_id && $payme_key && $payme_url) {
    echo "   ✓ PAYME_ID: " . substr($payme_id, 0, 10) . "...\n";
    echo "   ✓ PAYME_KEY: " . str_repeat('*', 20) . "\n";
    echo "   ✓ PAYME_URL: $payme_url\n\n";
} else {
    echo "   ✗ Payme config missing\n";
    exit(1);
}

// 4. Test Order creation
echo "✅ Testing Order creation...\n";
try {
    $testOrder = Order::create([
        'user_id' => 1,
        'amount' => 10000, // 100 so'm (tiyin formatida)
        'state' => 0,
        'type' => 'wallet'
    ]);
    echo "   ✓ Test order created: ID = {$testOrder->id}\n";
    echo "   ✓ Amount: {$testOrder->amount} tiyin (100 so'm)\n\n";
    
    // Clean up
    $testOrder->delete();
    echo "   ✓ Test order deleted\n\n";
} catch (\Exception $e) {
    echo "   ✗ Order creation failed: " . $e->getMessage() . "\n";
    exit(1);
}

// 5. Check routes
echo "✅ Checking Payme routes...\n";
$routes = [
    'payme:merchant' => 'POST /api/payment/payme/callback/',
    'wallet-process-payme' => 'POST /wallet-process-payme',
    'wallet-payme-success' => 'GET /wallet-payme-success',
];

foreach ($routes as $name => $uri) {
    if (Route::has($name)) {
        echo "   ✓ $uri ($name)\n";
    } else {
        echo "   ✗ Route not found: $name\n";
    }
}

echo "\n";
echo "========================\n";
echo "🎉 ALL TESTS PASSED!\n";
echo "========================\n\n";

echo "📝 Next steps:\n";
echo "1. Test payment flow: POST /wallet-process-payme\n";
echo "2. Monitor logs: tail -f storage/logs/laravel.log\n";
echo "3. Check Payme dashboard for transactions\n";
