document.addEventListener('DOMContentLoaded', function () {
    const loginForm = document.getElementById('mobile-login-form');
    const verifyForm = document.getElementById('verify-code-form');

    if (loginForm) {
        loginForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const mobile = document.getElementById('mobile').value.trim();

            if (!mobile || !/^\d{10,11}$/.test(mobile)) {
                alert('لطفاً شماره موبایل معتبر وارد کنید.');
                return;
            }

            fetch('/modules/mobilelogin/send-sms.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ mobile })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('کد تایید به شماره شما ارسال شد.');
                    window.location.href = '/index.php?controller=authentication&step=verify';
                } else {
                    alert('خطا: ' + data.message);
                }
            })
            .catch(error => {
                alert('خطای شبکه. لطفاً دوباره تلاش کنید.');
            });
        });
    }

    if (verifyForm) {
        verifyForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const code = document.getElementById('code').value.trim();

            if (!code || code.length !== 5) {
                alert('کد تایید باید ۵ رقمی باشد.');
                return;
            }

            fetch('/modules/mobilelogin/verify-code.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ code })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('ورود موفقیت‌آمیز!');
                    window.location.href = '/index.php?controller=my-account';
                } else {
                    alert('کد نادرست است. دوباره تلاش کنید.');
                }
            })
            .catch(error => {
                alert('خطای شبکه. لطفاً دوباره تلاش کنید.');
            });
        });
    }
});
