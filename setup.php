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
<style>
  body{font-family:Inter,Arial,sans-serif;background:#F4F6F5;display:flex;align-items:center;justify-content:center;min-height:100vh;margin:0;}
  .box{background:#fff;padding:36px;border-radius:16px;box-shadow:0 8px 40px rgba(36,49,62,.16);max-width:400px;width:90%;}
  h1{font-size:1.3rem;color:#24313E;margin-bottom:6px;}
  p{color:#6b7a8a;font-size:.9rem;margin-bottom:20px;}
  label{display:block;font-size:.85rem;font-weight:600;color:#24313E;margin-bottom:6px;}
  input{width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:8px;margin-bottom:16px;font-size:.95rem;}
  button{width:100%;background:#C8102E;color:#fff;border:none;padding:12px;border-radius:8px;font-weight:700;cursor:pointer;font-size:.95rem;}
  .msg{background:#fdecec;color:#9c0c24;padding:10px 14px;border-radius:8px;margin-bottom:16px;font-size:.85rem;}
  .ok{background:#eef7e6;color:#5a8a2c;}
</style>
</head>
<body>
<div class="box">
<?php if ($feito): ?>
  <h1>Utilizador criado ✅</h1>
  <div class="msg ok">Podes agora entrar em <strong>login.php</strong> com o utilizador e password que definiste.</div>
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
