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
<link href="https://fonts.googleapis.com/css2?family=Geist:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="estilo.css">
</head>
<body class="centro">
<div class="box">
  <div class="logo">Previne</div>
  <h1>Entrar</h1>
  <p>Painel de gestão — Certificados, alunos, estoque e tarefas.</p>
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
