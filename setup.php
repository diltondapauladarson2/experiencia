<?php
require 'config.php';
session_start();

$mensagem = '';
$feito = false;

// Segurança: só permite correr se ainda não existir nenhum admin
$stmt = $pdo->query("SELECT COUNT(*) FROM admin_users");
$jaExiste = $stmt->fetchColumn() > 0;

if ($jaExiste) {
    die('Já existe um utilizador admin configurado. Por segurança, apaga este ficheiro setup.php do servidor.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $utilizador = trim($_POST['utilizador'] ?? '');
    $password = $_POST['password'] ?? '';

    if (strlen($utilizador) < 3 || strlen($password) < 6) {
        $mensagem = 'Utilizador (mín. 3 caracteres) e password (mín. 6 caracteres) são obrigatórios.';
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO admin_users (utilizador, password_hash) VALUES (?, ?)");
        $stmt->execute([$utilizador, $hash]);
        $feito = true;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-MZ">
<head>
<meta charset="UTF-8">
<title>Configuração inicial — Previne</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="noindex, nofollow">
<link href="https://fonts.googleapis.com/css2?family=Geist:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="estilo.css">
</head>
<body class="centro">
<div class="box">
<?php if ($feito): ?>
  <h1>Utilizador criado ✅</h1>
  <div class="msg">Podes agora entrar em <strong>login.php</strong> com o utilizador e password que definiste.</div>
  <p><strong>IMPORTANTE:</strong> apaga o ficheiro <code>setup.php</code> do servidor agora.</p>
<?php else: ?>
  <h1>Configuração inicial</h1>
  <p>Cria o utilizador de administrador do Painel Previne.</p>
  <?php if ($mensagem): ?><div class="msg"><?= htmlspecialchars($mensagem) ?></div><?php endif; ?>
  <form method="post">
    <label>Utilizador</label>
    <input type="text" name="utilizador" required>
    <label>Password</label>
    <input type="password" name="password" required>
    <button type="submit">Criar utilizador</button>
  </form>
<?php endif; ?>
</div>
</body>
</html>
