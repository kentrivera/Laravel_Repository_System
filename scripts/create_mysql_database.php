<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->safeLoad();

$host = $_ENV['DB_HOST'] ?? '127.0.0.1';
$port = (int) ($_ENV['DB_PORT'] ?? 3306);
$username = $_ENV['DB_USERNAME'] ?? 'root';
$password = $_ENV['DB_PASSWORD'] ?? '';
$database = $_ENV['DB_DATABASE'] ?? 'lara_repo_file_manager';

if (!preg_match('/^[A-Za-z0-9_]+$/', $database)) {
    fwrite(STDERR, "Invalid DB_DATABASE '{$database}'. Use letters/numbers/underscore only." . PHP_EOL);
    exit(1);
}

$mysqli = @new mysqli($host, $username, $password, '', $port);

if ($mysqli->connect_errno) {
    fwrite(STDERR, 'MySQL connect failed: ' . $mysqli->connect_error . PHP_EOL);
    exit(1);
}

$sql = 'CREATE DATABASE IF NOT EXISTS ' . $database . ' CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci';

if (!$mysqli->query($sql)) {
    fwrite(STDERR, 'Create DB failed: ' . $mysqli->error . PHP_EOL);
    exit(1);
}

echo 'Database ready: ' . $database . PHP_EOL;
