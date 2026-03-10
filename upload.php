<?php
// upload.php — модифікований варіант, який шукає vendor/autoload.php в кількох місцях
// Пам'ятай: роби бекап оригіналу перед заміною.

function log_debug($msg) {
    file_put_contents(__DIR__ . '/upload.log', date("[Y-m-d H:i:s] ") . $msg . "\n", FILE_APPEND);
}

log_debug("=== Новий запит ===");

// Шукаємо автозавантажувач composer у декількох місцях
$possible = [
    __DIR__ . '/vendor/autoload.php',                // поточна тека
    __DIR__ . '/../blockly/vendor/autoload.php',     // сусідня тека blockly
    __DIR__ . '/../../blockly/vendor/autoload.php',  // ще один рівень (на випадок різної структури)
    __DIR__ . '/phpseclib/vendor/autoload.php',      // інша можлива структура
];

$autoloadFound = false;
foreach ($possible as $p) {
    if (is_file($p)) {
        require_once $p;
        log_debug("Завантажено автозавантажувач: $p");
        $autoloadFound = true;
        break;
    }
}

if (!$autoloadFound) {
    $msg = "❌ Не знайдено vendor/autoload.php. Перевір, де лежить папка vendor. Шукали: " . implode('; ', $possible);
    log_debug($msg);
    http_response_code(500);
    echo $msg;
    exit;
}

use phpseclib3\Net\SFTP;

$data = json_decode(file_get_contents("php://input"), true);
log_debug("Вхідні дані: " . json_encode($data));

$comp = preg_replace("/[^0-9]/", "", $data['comp'] ?? "");
$fileNameRaw = $data['fileName'] ?? '';
// дозволяємо розширення .lua і символи безпечні
$fileName = preg_replace("/[^a-zA-Z0-9_\-\.]/", "", $fileNameRaw);
$code = $data['code'] ?? '';

if (!$comp || !$fileName || !$code) {
    log_debug("⛔ Обов’язкові поля відсутні або порожні.");
    http_response_code(400);
    echo "⛔ Обов'язкові поля відсутні або порожні.";
    exit;
}

// Облікові дані: спроба взяти з ENV, інакше використовувати хардкод (як раніше)
$host = getenv('SFTP_HOST') ?: 'wing79.panel.godlike.host';
$port = getenv('SFTP_PORT') ?: 2022;
$user = getenv('SFTP_USER') ?: '6qszhvg5.06b2a90e';
$pass = getenv('SFTP_PASS') ?: 'qKAs2FBwt5Fx.QR';

// Підключення SFTP
$sftp = new SFTP($host, (int)$port);
if (!$sftp->login($user, $pass)) {
    log_debug("❌ Логін не вдався до $host:$port як $user");
    http_response_code(403);
    echo "❌ Невірний логін або пароль (SFTP).";
    exit;
}

// Віддалений шлях
$dirPath = "world/computercraft/computer/$comp";
$remoteFile = rtrim($dirPath, '/') . '/' . $fileName;

// Якщо теки немає — створимо (рекурсивно)
if (!$sftp->is_dir($dirPath)) {
    log_debug("Тека $dirPath не існує — пробуємо створити.");
    // mkdir(path, mode, recursive) — phpseclib приймає третій параметр recursive
    $created = $sftp->mkdir($dirPath, -1, true);
    if ($created) {
        log_debug("✅ Віддалена тека створена: $dirPath");
    } else {
        log_debug("❌ Не вдалося створити віддалену теку: $dirPath. Можливо, недостатньо прав.");
        // Але спробуємо все одно записати — помилка буде оброблена нижче
    }
}

log_debug("🔎 Пробуємо зберегти файл: $remoteFile");

// Важливо: явно вказуємо бінарний режим (phpseclib сам визначає), передаємо контент
if ($sftp->put($remoteFile, $code)) {
    log_debug("✅ Файл збережено у: $remoteFile");
    echo "✅ Файл збережено у: $remoteFile";
} else {
    log_debug("❌ Помилка при збереженні файлу $remoteFile. lastSFTPError: " . json_encode($sftp->getSFTPError()));
    http_response_code(500);
    echo "❌ Не вдалося зберегти файл. Перевір права та шлях на віддаленому сервері.";
}
