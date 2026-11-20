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
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
  :root{--red:#C8102E;--red-dark:#9c0c24;--dark:#24313E;--green:#6FA83A;--bg:#FAFAF9;}
  *{box-sizing:border-box;margin:0;padding:0;}
  body{font-family:'Inter',sans-serif;background:linear-gradient(135deg,#1a232e,#24313E 45%,#7a0f22);min-height:100vh;display:flex;align-items:center;justify-content:center;padding:24px;}
  h1,h2{font-family:'Sora',sans-serif;}
  .card{background:#fff;border-radius:20px;max-width:480px;width:100%;padding:36px;box-shadow:0 12px 50px rgba(0,0,0,.35);text-align:center;}
  .status-icon{width:72px;height:72px;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 18px;font-size:1.8rem;color:#fff;}
  .valido .status-icon{background:var(--green);}
  .invalido .status-icon{background:var(--red);}
  .valido h1{color:var(--green);font-size:1.3rem;margin-bottom:4px;}
  .invalido h1{color:var(--red);font-size:1.3rem;margin-bottom:4px;}
  .sub{color:#6b7a8a;font-size:.85rem;margin-bottom:24px;}
  .detalhes{text-align:left;background:#F4F6F5;border-radius:12px;padding:18px 20px;margin-bottom:20px;}
  .linha{display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid #e6e9ec;font-size:.88rem;}
  .linha:last-child{border-bottom:none;}
  .linha span:first-child{color:#6b7a8a;}
  .linha span:last-child{font-weight:600;color:var(--dark);text-align:right;}
  .codigo-box{font-family:monospace;font-weight:700;letter-spacing:1px;color:var(--red);font-size:1rem;margin-bottom:20px;}
  .footer-link{font-size:.8rem;color:#6b7a8a;}
  .footer-link a{color:var(--red);font-weight:600;}
  .logo{font-family:'Sora',sans-serif;font-weight:800;color:var(--dark);font-size:1.1rem;margin-bottom:20px;}
  .logo span{color:var(--red);}
</style>
</head>
<body>
<div class="card <?= $cert ? 'valido' : 'invalido' ?>">
  <div class="logo">PRE<span>VINE</span></div>

  <?php if ($cert): ?>
    <div class="status-icon"><i class="fas fa-check"></i></div>
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
    <div class="status-icon"><i class="fas fa-times"></i></div>
    <h1>Certificado Não Encontrado</h1>
    <p class="sub">O código informado não corresponde a nenhum certificado emitido pela Previne. Se acha que isto é um erro, contacte-nos.</p>
  <?php endif; ?>

  <p class="footer-link">Dúvidas? <a href="https://wa.me/258868606060">Fale connosco no WhatsApp</a></p>
</div>
</body>
</html>
