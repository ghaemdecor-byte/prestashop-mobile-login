<?php
require_once('../../config/config.inc.php');

header('Content-Type: application/json');

$code = $_POST['code'] ?? '';
if (!$code) {
    echo json_encode(['success' => false, 'message' => 'کد تایید الزامی است.']);
    exit;
}

// فرض می‌کنیم که شماره موبایل از کوکی یا فرم قبلی دریافت شده است
// در عمل باید از کوکی یا جلسه استفاده کنید
$mobile = $_COOKIE['last_mobile'] ?? ''; // این بخش برای نمونه است

if (!$mobile) {
    echo json_encode(['success' => false, 'message' => 'شماره موبایل یافت نشد.']);
    exit;
}

// بررسی کد تایید
$savedCode = $_COOKIE['verification_code_' . $mobile] ?? '';

if ($savedCode === $code) {
    // کد صحیح است — ورود کاربر
    // در اینجا باید کاربر را وارد کنید (در عمل باید از کلاس Customer استفاده کنید)
    // برای نمونه فقط یک پیام موفقیت نشان می‌دهیم

    // حذف کوکی‌ها
    setcookie('verification_code_' . $mobile, '', time() - 3600, '/');
    setcookie('last_mobile', '', time() - 3600, '/');

    echo json_encode(['success' => true, 'message' => 'ورود موفقیت‌آمیز!']);
} else {
    echo json_encode(['success' => false, 'message' => 'کد نادرست است.']);
}
