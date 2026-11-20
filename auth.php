<?php
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => !empty($_SERVER['HTTPS']),
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

function exigirLogin() {
    if (empty($_SESSION['admin_logado'])) {
        header('Location: login.php');
        exit;
    }
}

function csrfToken() {
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrfCampo() {
    return '<input type="hidden" name="csrf" value="' . htmlspecialchars(csrfToken()) . '">';
}

function verificarCsrf() {
    $enviado = $_POST['csrf'] ?? '';
    if (!$enviado || !hash_equals($_SESSION['csrf'] ?? '', $enviado)) {
        http_response_code(403);
        die('Pedido inválido (token de segurança). Volta atrás, recarrega a página e tenta de novo.');
    }
}

function gerarCodigo($pdo) {
    do {
        $codigo = 'PREV-' . strtoupper(substr(bin2hex(random_bytes(4)), 0, 6));
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM certificados WHERE codigo = ?");
        $stmt->execute([$codigo]);
    } while ($stmt->fetchColumn() > 0);
    return $codigo;
}
