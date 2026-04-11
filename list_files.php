<?php
require_once __DIR__ . '/../blockly/vendor/autoload.php';
use phpseclib3\Net\SFTP;

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

function error_exit($msg) {
    http_response_code(400);
    echo json_encode(["error" => $msg]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$comp = preg_replace("/[^0-9]/", "", $data['comp'] ?? "");
$file = preg_replace("/[^a-zA-Z0-9_\-\.]/", "", $data['file'] ?? "");

if (!$comp) error_exit("Номер комп’ютера не вказано.");

$host = getenv('SFTP_HOST') ?: '46.225.227.42';
$port = getenv('SFTP_PORT') ?: 2022;
$user = getenv('SFTP_USER') ?: 'admin.3c4202c1';
$pass = getenv('SFTP_PASS') ?: '';

$sftp = new SFTP($host, $port);
if (!$sftp->login($user, $pass)) error_exit("SFTP логін не вдався.");

$dir = "world/computercraft/computer/$comp";

$action = $data['action'] ?? '';

if ($action === 'delete' && $file) {
    // 🗑️ Видалити файл
    $path = "$dir/$file";
    if (!$sftp->file_exists($path)) error_exit("Файл не знайдено: $file");
    if (!$sftp->delete($path)) error_exit("Не вдалося видалити файл: $file");
    echo json_encode(["ok" => true]);
} elseif ($file) {
    // 🔹 Якщо передано "file" — повертаємо вміст файлу
    $path = "$dir/$file";
    if (!$sftp->file_exists($path)) error_exit("Файл не знайдено: $file");
    $content = $sftp->get($path);
    echo json_encode(["name" => $file, "content" => $content]);
} else {
    // 🔹 Інакше — повертаємо список файлів у теці
    $list = $sftp->nlist($dir);
    if ($list === false) error_exit("Не вдалося прочитати теку $dir");

    $luaFiles = array_values(array_filter($list, fn($f) => str_ends_with($f, '.lua')));
    echo json_encode(["files" => $luaFiles]);
}
