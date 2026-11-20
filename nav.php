<?php
// Inclui este ficheiro depois de exigirLogin(). Define $paginaAtual antes de incluir.
$paginaAtual = $paginaAtual ?? '';
$icones = [
  'dashboard.php' => '<rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/>',
  'admin.php'     => '<circle cx="12" cy="8" r="6"/><path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"/>',
  'cursos.php'    => '<path d="M21.42 10.922a1 1 0 0 0-.019-1.838L12.83 5.18a2 2 0 0 0-1.66 0L2.6 9.08a1 1 0 0 0 0 1.832l8.57 3.908a2 2 0 0 0 1.66 0z"/><path d="M22 10v6"/><path d="M6 12.5V16a6 3 0 0 0 12 0v-3.5"/>',
  'estoque.php'   => '<path d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z"/><path d="M12 22V12"/><path d="m3.3 7 8.7 5 8.7-5"/>',
  'tarefas.php'   => '<path d="m3 17 2 2 4-4"/><path d="m3 7 2 2 4-4"/><path d="M13 6h8"/><path d="M13 12h8"/><path d="M13 18h8"/>',
];
$itens = [
  'dashboard.php' => 'Painel',
  'admin.php'     => 'Certificados',
  'cursos.php'    => 'Alunos / Cursos',
  'estoque.php'   => 'Estoque',
  'tarefas.php'   => 'Tarefas',
];
?>
<link href="https://fonts.googleapis.com/css2?family=Geist:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="estilo.css">
<aside class="sidebar">
  <div class="brand">
    <div class="brand-mark">P</div>
    <div class="brand-txt">Previne<small>Painel de gestão</small></div>
  </div>
  <nav class="menu">
    <?php foreach ($itens as $link => $nome): ?>
      <a href="<?= $link ?>" class="<?= $paginaAtual === $link ? 'ativo' : '' ?>"><svg viewBox="0 0 24 24"><?= $icones[$link] ?></svg><?= $nome ?></a>
    <?php endforeach; ?>
  </nav>
  <div class="side-foot">
    <div class="user">Sessão iniciada<b><?= htmlspecialchars($_SESSION['admin_utilizador'] ?? '') ?></b></div>
    <a class="sair" href="logout.php"><svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>Sair</a>
  </div>
</aside>
