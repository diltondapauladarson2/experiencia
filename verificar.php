<?php
require 'config.php';

$codigo = trim($_GET['codigo'] ?? '');
$cert = null;

if ($codigo) {
    $stmt = $pdo->prepare("SELECT * FROM certificados WHERE codigo = ?");
    $stmt->execute([$codigo]);
    $cert = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="pt-MZ">
<head>
<meta charset="UTF-8">
<title>Verificação de Certificado — Previne</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="Verifique a autenticidade de certificados emitidos pela Previne - Saúde & Serviços Lda.">
<link href="https://fonts.googleapis.com/css2?family=Geist:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="estilo.css">
</head>
<body class="centro">
<div class="card <?= $cert ? 'valido' : 'invalido' ?>">
  <div class="logo">Previne</div>

  <?php if ($cert): ?>
    <div class="status-icon"><svg viewBox="0 0 24 24"><path d="M20 6 9 17l-5-5"/></svg></div>
    <h1>Certificado Válido</h1>
    <p class="sub">Este certificado foi emitido oficialmente pela Previne.</p>
    <div class="codigo-box"><?= htmlspecialchars($cert['codigo']) ?></div>
    <div class="detalhes">
      <div class="linha"><span>Aluno</span><span><?= htmlspecialchars($cert['nome_aluno']) ?></span></div>
      <?php if ($cert['empresa']): ?><div class="linha"><span>Empresa</span><span><?= htmlspecialchars($cert['empresa']) ?></span></div><?php endif; ?>
      <div class="linha"><span>Curso</span><span><?= htmlspecialchars($cert['curso']) ?></span></div>
      <div class="linha"><span>Data de emissão</span><span><?= htmlspecialchars(date('d/m/Y', strtotime($cert['data_emissao']))) ?></span></div>
      <?php if ($cert['carga_horaria']): ?><div class="linha"><span>Carga horária</span><span><?= htmlspecialchars($cert['carga_horaria']) ?></span></div><?php endif; ?>
      <?php if ($cert['instrutor']): ?><div class="linha"><span>Instrutor</span><span><?= htmlspecialchars($cert['instrutor']) ?></span></div><?php endif; ?>
    </div>
  <?php else: ?>
    <div class="status-icon"><svg viewBox="0 0 24 24"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg></div>
    <h1>Certificado Não Encontrado</h1>
    <p class="sub">O código informado não corresponde a nenhum certificado emitido pela Previne. Se acha que isto é um erro, contacte-nos.</p>
  <?php endif; ?>

  <p class="footer-link">Dúvidas? <a href="https://wa.me/258868606060">Fale connosco no WhatsApp</a></p>
</div>
</body>
</html>
