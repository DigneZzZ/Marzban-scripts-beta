<?php
$url = isset($_GET['url']) ? $_GET['url'] : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $deviceType = isset($_POST['deviceType']) ? $_POST['deviceType'] : null;
    $lang = isset($_POST['lang']) ? $_POST['lang'] : 'en';

    $texts = [
        'en' => [
            'title' => 'Telegram In-App Browser Detected',
            'message' => 'Open the app on your default browser.',
            'error' => 'Error: Unable to determine device type.',
            'url_error_title' => 'URL not provided',
            'url_error_message' => 'Please provide a URL in the query parameters.',
            'url_error_example' => 'Example: <code>?url=https://example.com</code>',
            'url_error_usage' => 'Usage: <br /> <code>&lt;a href="https://r.openode.xyz/?url=https://example.com"&gt;link&lt;/a&gt;</code>'
        ],
        'ru' => [
            'title' => 'Обнаружено использование встроенного браузера Telegram',
            'message' => 'Для корректной работы сервиса, пожалуйста,откройте данную страницу в приложении Safari',
            'error' => 'Ошибка: Не удалось определить тип устройства.',
            'url_error_title' => 'URL не передан',
            'url_error_message' => 'Пожалуйста, передайте URL в параметрах запроса.',
            'url_error_example' => 'Пример: <code>?url=https://example.com</code>',
            'url_error_usage' => 'Использование: <br /> <code>&lt;a href="https://r.openode.xyz/?url=https://example.com"&gt;ссылка&lt;/a&gt;</code>'
        ],
        'fa' => [
            'title' => 'تشخیص استفاده از مرورگر درون برنامه ای تلگرام',
            'message' => 'برنامه را در مرورگر پیش فرض خود باز کنید.',
            'error' => 'خطا: عدم توانایی در تعیین نوع دستگاه.',
            'url_error_title' => 'URL ارائه نشده است',
            'url_error_message' => 'لطفاً URL را در پارامترهای درخواست ارائه دهید.',
            'url_error_example' => 'مثال: <code>?url=https://example.com</code>',
            'url_error_usage' => 'استفاده: <br /> <code>&lt;a href="https://r.openode.xyz/?url=https://example.com"&gt;لینک&lt;/a&gt;</code>'
        ]
    ];

    $texts = $texts[$lang] ?? $texts['en'];

    if ($deviceType) {
        if ($deviceType === 'android') {
            $arrow = '<div class="arrow android">⇨</div>';
        } elseif ($deviceType === 'ios') {
            $arrow = '<div class="arrow ios">⇨</div>';
        } else {
            $arrow = '';
        }
        
        echo '
        <h1>' . $texts['title'] . '</h1>
        <p class="info-message">' . $texts['message'] . '</p>
        ' . $arrow;
    } else {
        echo '<h1>' . $texts['error'] . '</h1>';
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Open in Default Browser</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: 'Fira Code', monospace; display: none; flex-direction: column; justify-content: center; align-items: center; height: 100vh; margin: 0; background-color: #1e1e1e; color: #c5c8c6; text-align: center; position: relative; }
        h1, p { margin: 20px; }
        .info-message { margin: 20px; font-size: 16px; color: #ffcc00; }
        .arrow { position: absolute; font-size: 100px; color: #ffcc00; }
        .arrow.ios { bottom: 20px; right: 20px; transform: rotate(45deg); }
        .arrow.android { top: 20px; right: 20px; transform: rotate(-45deg); }
    </style>
</head>
<body>
    <div id="telegram-browser-content">
        <h1>Loading...</h1>
    </div>
    <script>
        function isTelegram() {
            var userAgent = window.navigator.userAgent || window.navigator.vendor || window.opera;
            return (userAgent.indexOf("Telegram") !== -1 || typeof window.TelegramWebviewProxy !== 'undefined');
        }

        function getDeviceType() {
            var userAgent = window.navigator.userAgent || window.navigator.vendor || window.opera;
            if (/android/i.test(userAgent)) {
                return 'android';
            } else if (/iPad|iPhone|iPod/.test(userAgent) && !window.MSStream) {
                return 'ios';
            }
            return 'unknown';
        }

        function getLanguage() {
            var lang = navigator.language || navigator.userLanguage;
            if (lang.startsWith('ru')) {
                return 'ru';
            } else if (lang.startsWith('fa')) {
                return 'fa';
            }
            return 'en';
        }

        window.onload = function() {
            if (isTelegram()) {
                var deviceType = getDeviceType();
                var lang = getLanguage();
                var xhr = new XMLHttpRequest();
                xhr.open('POST', '', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function () {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        document.getElementById('telegram-browser-content').innerHTML = xhr.responseText;
                        document.body.style.display = 'flex';
                    }
                };
                xhr.send('deviceType=' + deviceType + '&lang=' + lang);
            } else {
                <?php if ($url): ?>
                    window.location.href = "<?= $url ?>";
                <?php else: ?>
                    var lang = getLanguage();
                    var messages = {
                        'en': {
                            'title': 'URL not provided',
                            'message': 'Please provide a URL in the query parameters.',
                            'example': 'Example: <code>?url=https://example.com</code>',
                            'usage': 'Usage: <br /> <code>&lt;a href="https://r.openode.xyz/?url=https://example.com"&gt;link&lt;/a&gt;</code>'
                        },
                        'ru': {
                            'title': 'URL не передан',
                            'message': 'Пожалуйста, передайте URL в параметрах запроса.',
                            'example': 'Пример: <code>?url=https://example.com</code>',
                            'usage': 'Использование: <br /> <code>&lt;a href="https://r.openode.xyz/?url=https://example.com"&gt;ссылка&lt;/a&gt;</code>'
                        },
                        'fa': {
                            'title': 'URL ارائه نشده است',
                            'message': 'لطفاً URL را در پارامترهای درخواست ارائه دهید.',
                            'example': 'مثال: <code>?url=https://example.com</code>',
                            'usage': 'استفاده: <br /> <code>&lt;a href="https://r.openode.xyz/?url=https://example.com"&gt;لینک&lt;/a&gt;</code>'
                        }
                    };

                    var messagesToShow = messages[lang] || messages['en'];
                    document.getElementById('telegram-browser-content').innerHTML = `
                        <h1>${messagesToShow.title}</h1>
                        <p class="info-message">${messagesToShow.message}</p>
                        <p class="info-message">${messagesToShow.example}</p>
                        <p class="info-message">${messagesToShow.usage}</p>
                    `;
                    document.body.style.display = 'flex';
                <?php endif; ?>
            }
        };
    </script>
</body>
</html>
