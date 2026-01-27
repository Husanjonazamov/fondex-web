#!/usr/bin/env php
<?php

/**
 * TO'LOV STATUS API TEST
 * 
 * Bu skript payment status API'larini test qiladi
 */

echo "\n";
echo "🧪 PAYMENT STATUS API TEST\n";
echo "==========================\n\n";

$baseUrl = getenv('APP_URL') ?: 'http://localhost:8092';

// Test 1: To'lov statusini tekshirish
echo "✅ Test 1: To'lov statusini tekshirish\n";
echo "   URL: POST {$baseUrl}/api/payment/check-status\n";

$ch = curl_init("{$baseUrl}/api/payment/check-status");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['payment_id' => '1']));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode == 200 || $httpCode == 404) {
    $data = json_decode($response, true);
    echo "   ✓ Response: HTTP {$httpCode}\n";
    echo "   ✓ Payment Status: " . ($data['payment_status'] ?? 'unknown') . "\n";
    echo "   ✓ Is Paid: " . (($data['is_paid'] ?? false) ? 'true' : 'false') . "\n";
} else {
    echo "   ✗ Failed: HTTP {$httpCode}\n";
    echo "   Response: {$response}\n";
}

echo "\n";

// Test 2: Foydalanuvchi to'lovlari
echo "✅ Test 2: Foydalanuvchi to'lovlari\n";
echo "   URL: POST {$baseUrl}/api/payment/user-payments\n";

$ch = curl_init("{$baseUrl}/api/payment/user-payments");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['user_id' => '1']));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode == 200) {
    $data = json_decode($response, true);
    echo "   ✓ Response: HTTP {$httpCode}\n";
    echo "   ✓ Payments Count: " . ($data['count'] ?? 0) . "\n";
    
    if (isset($data['payments']) && count($data['payments']) > 0) {
        echo "   ✓ Latest Payment:\n";
        $latest = $data['payments'][0];
        echo "      - ID: " . ($latest['payment_id'] ?? 'N/A') . "\n";
        echo "      - Status: " . ($latest['payment_status'] ?? 'N/A') . "\n";
        echo "      - Amount: " . ($latest['amount'] ?? 'N/A') . "\n";
    }
} else {
    echo "   ✗ Failed: HTTP {$httpCode}\n";
    echo "   Response: {$response}\n";
}

echo "\n";

// Test 3: Noto'g'ri payment_id
echo "✅ Test 3: Mavjud bo'lmagan to'lov\n";
echo "   URL: POST {$baseUrl}/api/payment/check-status\n";

$ch = curl_init("{$baseUrl}/api/payment/check-status");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['payment_id' => '999999']));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode == 404) {
    $data = json_decode($response, true);
    echo "   ✓ Response: HTTP {$httpCode}\n";
    echo "   ✓ Message: " . ($data['message'] ?? 'unknown') . "\n";
    echo "   ✓ Status: " . ($data['payment_status'] ?? 'unknown') . "\n";
} else {
    echo "   ✗ Unexpected response: HTTP {$httpCode}\n";
}

echo "\n";

// Test 4: Bo'sh payment_id
echo "✅ Test 4: Bo'sh payment_id\n";
echo "   URL: POST {$baseUrl}/api/payment/check-status\n";

$ch = curl_init("{$baseUrl}/api/payment/check-status");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([]));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode == 400) {
    $data = json_decode($response, true);
    echo "   ✓ Response: HTTP {$httpCode}\n";
    echo "   ✓ Message: " . ($data['message'] ?? 'unknown') . "\n";
} else {
    echo "   ✗ Unexpected response: HTTP {$httpCode}\n";
}

echo "\n==========================\n";
echo "🎉 TESTLAR BAJARILDI!\n";
echo "==========================\n\n";

// Polling simulation misoli
echo "📝 POLLING SIMULATION MISOLI:\n";
echo "------------------------------\n";
echo "Mobil dasturda quyidagicha ishlatish mumkin:\n\n";
echo <<<'CODE'
// Dart/Flutter
Timer.periodic(Duration(seconds: 3), (timer) async {
  var response = await http.post(
    Uri.parse('$baseUrl/api/payment/check-status'),
    body: json.encode({'payment_id': paymentId}),
  );
  
  if (response.statusCode == 200) {
    var data = json.decode(response.body);
    if (data['is_paid'] == true) {
      timer.cancel();
      // TO'LOV MUVAFFAQIYATLI!
      Navigator.push(context, SuccessPage());
    }
  }
});

CODE;

echo "\n";
