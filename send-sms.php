<?php
require_once('../../config/config.inc.php');

header('Content-Type: application/json');

$mobile = $_POST['mobile'] ?? '';
if (!$mobile) {
    echo json_encode(['success' => false, 'message' => 'شماره موبایل الزامی است.']);
    exit;
}

// فرض می‌کنیم که API Key و Template ID در تنظیمات ماژول ذخیره شده‌اند
$apiKey = Configuration::get('SMS_IR_API_KEY');
$templateId = Configuration::get('SMS_IR_TEMPLATE_ID');

if (!$apiKey || !$templateId) {
    echo json_encode(['success' => false, 'message' => 'تنظیمات API انجام نشده است.']);
    exit;
}

// کد تایید تصادفی
$code = rand(10000, 99999);

$url = 'https://api.sms.ir/v1/send/verify';
$data = [
    'mobile' => $mobile,
    'templateId' => (int)$templateId,
    'parameters' => [['name' => 'Code', 'value' => (string)$code]]
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'X-API-KEY: ' . $apiKey
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$error = curl_error($ch);
curl_close($ch);

if ($error) {
    echo json_encode(['success' => false, 'message' => 'خطای اتصال: ' . $error]);
    exit;
}

$result = json_decode($response, true);

if (isset($result['status']) && $result['status'] == 1) {
    // ذخیره کد تایید در کوکی یا دیتابیس (برای سادگی در اینجا در کوکی ذخیره می‌شود)
    setcookie('verification_code_' . $mobile, $code, time() + 300, '/'); // 5 دقیقه اعتبار

    echo json_encode(['success' => true, 'message' => 'کد تایید ارسال شد.']);
} else {
    echo json_encode(['success' => false, 'message' => 'خطا در ارسال پیامک.']);
}
