<?php
// ====== CONFIGURAÇÃO — preenche com os dados da tua base de dados na Hostinger ======
// hPanel > Bases de Dados > MySQL Databases (o hPanel mostra estes 4 valores quando crias a BD)

define('DB_HOST', 'localhost');
define('DB_NAME', 'u000000000_certificados');   // nome da tua base de dados
define('DB_USER', 'u000000000_admin');           // utilizador da BD
define('DB_PASS', 'A_TUA_PASSWORD_AQUI');        // password da BD

// URL pública base do site (sem barra no fim)
define('SITE_URL', 'https://previnemz.com');

// ====== NÃO PRECISAS DE MEXER DAQUI PARA BAIXO ======
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    die('Erro de ligação à base de dados. Verifica os dados em config.php.');
}
