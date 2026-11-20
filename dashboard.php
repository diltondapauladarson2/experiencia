<?php
require 'config.php';
require 'auth.php';
exigirLogin();
$paginaAtual = 'dashboard.php';

$totalCertificados = $pdo->query("SELECT COUNT(*) FROM certificados")->fetchColumn();
$totalAlunos = $pdo->query("SELECT COUNT(*) FROM cursos_alunos")->fetchColumn();
$totalConcluidos = $pdo->query("SELECT COUNT(*) FROM cursos_alunos WHERE estado='concluido'")->fetchColumn();
$totalEstoqueBaixo = $pdo->query("SELECT COUNT(*) FROM estoque WHERE quantidade <= quantidade_minima")->fetchColumn();
$totalTarefasPendentes = $pdo->query("SELECT COUNT(*) FROM tarefas WHERE estado != 'concluida'")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="pt-MZ">
<head>
<meta charset="UTF-8">
<title>Painel — Previne</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="noindex, nofollow">
</head>
<body>
<?php include 'nav.php'; ?>
<div class="wrap">
  <div class="stats">
    <div class="stat-box"><div class="num"><?= $totalCertificados ?></div><div class="lbl">Certificados emitidos</div></div>
    <div class="stat-box"><div class="num"><?= $totalAlunos ?></div><div class="lbl">Alunos registados</div></div>
    <div class="stat-box"><div class="num"><?= $totalConcluidos ?></div><div class="lbl">Cursos concluídos</div></div>
    <div class="stat-box"><div class="num"><?= $totalEstoqueBaixo ?></div><div class="lbl">Itens em estoque baixo</div></div>
    <div class="stat-box"><div class="num"><?= $totalTarefasPendentes ?></div><div class="lbl">Tarefas pendentes</div></div>
  </div>

  <div class="card">
    <h2>Acesso rápido</h2>
    <p style="color:#737373;font-size:.9rem;margin-bottom:16px;">Usa o menu acima para navegar entre Certificados, Alunos/Cursos, Estoque e Tarefas.</p>
    <a href="admin.php" class="btn" style="margin-right:8px;">+ Novo certificado</a>
    <a href="cursos.php" class="btn btn-secundario" style="margin-right:8px;">+ Novo aluno</a>
    <a href="estoque.php" class="btn btn-secundario" style="margin-right:8px;">+ Item de estoque</a>
    <a href="tarefas.php" class="btn btn-secundario">+ Nova tarefa</a>
  </div>
</div>
</body>
</html>
