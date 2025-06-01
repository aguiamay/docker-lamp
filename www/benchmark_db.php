<?php
$host = 'localhost:3306';
$user = 'root';
$pass = '';
$db   = 'benchdb';

$mysqli = new mysqli($host, $user, $pass);
if ($mysqli->connect_errno) {
    die("Falha na conexão: " . $mysqli->connect_error);
}

// Cria banco e tabela
$mysqli->query("CREATE DATABASE IF NOT EXISTS $db");
$mysqli->select_db($db);
$mysqli->query("DROP TABLE IF EXISTS bench");
$mysqli->query("CREATE TABLE bench (
    id INT AUTO_INCREMENT PRIMARY KEY,
    txt VARCHAR(255),
    longtxt TEXT,
    num INT,
    dt DATETIME
)");

$N = 20000;
$now = date('Y-m-d H:i:s');

// INSERT benchmark
$start = microtime(true);
for ($i = 0; $i < $N; $i++) {
    $txt = "Teste $i";
    $longtxt = str_repeat("Lorem ipsum $i ", 10);
    $num = rand(1, 1000000);
    $mysqli->query("INSERT INTO bench (txt, longtxt, num, dt) VALUES ('$txt', '$longtxt', $num, '$now')");
}
$insert_time = microtime(true) - $start;


// SELECT benchmark (X leituras)
$numero_leituras = 500;
$start = microtime(true);
$primeiro_count = null;
for ($j = 0; $j < $numero_leituras; $j++) {
    $res = $mysqli->query("SELECT * FROM bench WHERE num > 500000 ORDER BY dt DESC, num ASC LIMIT 500");
    $count = 0;
    while ($row = $res->fetch_assoc()) {
        $count++;
    }

    if ($j === 0) {
        $primeiro_count = $count;
    }
}
$select_time = microtime(true) - $start;

// DELETE benchmark
$start = microtime(true);
$mysqli->query("DELETE FROM bench");
$delete_time = microtime(true) - $start;

function tempo_friendly($tempo)
{
    if ($tempo < 1) {
        return round($tempo * 1000, 2) . ' ms';
    }
    return round($tempo, 3) . ' s';
}

$insert_friendly = tempo_friendly($insert_time);
$select_friendly = tempo_friendly($select_time);
$delete_friendly = tempo_friendly($delete_time);

echo <<<HTML
<style>
table { border-collapse: collapse; margin: 30px 0; font-family: Arial, sans-serif; }
th, td { border: 1px solid #ccc; padding: 10px 18px; text-align: left; }
th { background: #f4f4f4; }
caption { font-size: 1.2em; margin-bottom: 10px; font-weight: bold; }
</style>
<table>
    <caption>Resultado do Benchmark MySQL - [ LOCAL ]</caption>
    <tr>
        <th>Operação</th>
        <th>Tempo (segundos)</th>
        <th>Registros</th>
    </tr>
    <tr>
        <td>Inserção</td>
        <td>{$insert_friendly}</td>
        <td>{$N}</td>
    </tr>
    <tr>
        <td>Leitura (100x)</td>
        <td>{$select_friendly}</td>
        <td>{$numero_leituras} x {$primeiro_count}</td>
    </tr>
    <tr>
        <td>Remoção</td>
        <td>{$delete_friendly}</td>
        <td>{$N}</td>
    </tr>
</table>
HTML;

$mysqli->close();
