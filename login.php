<?php
require 'config.php';
require 'auth.php';

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $utilizador = trim($_POST['utilizador'] ?? '');
    $password = $_POST['password'] ?? '';

    verificarCsrf();
    $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE utilizador = ?");
    $stmt->execute([$utilizador]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['admin_logado'] = true;
        $_SESSION['admin_utilizador'] = $user['utilizador'];
        header('Location: dashboard.php');
        exit;
    } else {
        sleep(1);
        $erro = 'Utilizador ou password incorretos.';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-MZ">
<head>
<meta charset="UTF-8">
<title>Login — Painel de Certificados Previne</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="noindex, nofollow">
<style>
  body{font-family:Inter,Arial,sans-serif;background:linear-gradient(135deg,#1a232e,#24313E 45%,#7a0f22);display:flex;align-items:center;justify-content:center;min-height:100vh;margin:0;}
  .box{background:#fff;padding:36px;border-radius:16px;box-shadow:0 8px 40px rgba(0,0,0,.3);max-width:380px;width:90%;}
  h1{font-size:1.3rem;color:#24313E;margin-bottom:4px;}
  p{color:#6b7a8a;font-size:.85rem;margin-bottom:22px;}
  label{display:block;font-size:.85rem;font-weight:600;color:#24313E;margin-bottom:6px;}
  input{width:100%;padding:10px 12px;border:1px solid #ddd;border-radius:8px;margin-bottom:16px;font-size:.95rem;box-sizing:border-box;}
  button{width:100%;background:#C8102E;color:#fff;border:none;padding:12px;border-radius:8px;font-weight:700;cursor:pointer;font-size:.95rem;}
  button:hover{background:#9c0c24;}
  .erro{background:#fdecec;color:#9c0c24;padding:10px 14px;border-radius:8px;margin-bottom:16px;font-size:.85rem;}
</style>
</head>
<body>
<div class="box">
  <h1>Painel de Certificados</h1>
  <p>Previne — Saúde & Serviços</p>
  <?php if ($erro): ?><div class="erro"><?= htmlspecialchars($erro) ?></div><?php endif; ?>
  <form method="post">
      <?= csrfCampo() ?>
    <label>Utilizador</label>
    <input type="text" name="utilizador" required autofocus>
    <label>Password</label>
    <input type="password" name="password" required>
    <button type="submit">Entrar</button>
  </form>
</div>
</body>
</html>
