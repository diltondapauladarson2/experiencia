<?php
// Inclui este ficheiro depois de exigirLogin(). Define $paginaAtual antes de incluir.
$paginaAtual = $paginaAtual ?? '';
$itens = [
    'dashboard.php'  => ['Painel', 'fa-gauge'],
    'admin.php'      => ['Certificados', 'fa-certificate'],
    'cursos.php'     => ['Alunos / Cursos', 'fa-user-graduate'],
    'estoque.php'    => ['Estoque', 'fa-boxes-stacked'],
    'tarefas.php'    => ['Tarefas', 'fa-list-check'],
];
?>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
  :root{--red:#C8102E;--red-dark:#9c0c24;--dark:#24313E;--green:#6FA83A;--bg:#F4F6F5;}
  *{box-sizing:border-box;}
  body{font-family:Inter,Arial,sans-serif;background:var(--bg);margin:0;color:var(--dark);}
  header.topbar{background:var(--dark);color:#fff;padding:0 24px;}
  .topbar-inner{max-width:1100px;margin:0 auto;display:flex;justify-content:space-between;align-items:center;height:60px;flex-wrap:wrap;}
  .topbar h1{font-size:1rem;margin:0;font-weight:700;}
  .topbar-user{color:rgba(255,255,255,.75);font-size:.82rem;text-decoration:none;}
  nav.tabs{background:#1a232e;overflow-x:auto;}
  nav.tabs .tabs-inner{max-width:1100px;margin:0 auto;display:flex;}
  nav.tabs a{color:rgba(255,255,255,.7);padding:12px 18px;font-size:.85rem;text-decoration:none;white-space:nowrap;border-bottom:3px solid transparent;display:flex;align-items:center;gap:7px;}
  nav.tabs a:hover{color:#fff;}
  nav.tabs a.ativo{color:#fff;border-bottom-color:var(--red);}
  .wrap{max-width:1100px;margin:0 auto;padding:28px 20px 60px;}
  .card{background:#fff;border-radius:14px;padding:24px;box-shadow:0 4px 24px rgba(36,49,62,.08);margin-bottom:24px;}
  .card h2{font-size:1.05rem;margin-bottom:16px;font-family:'Sora',sans-serif;}
  .grid{display:grid;grid-template-columns:1fr 1fr;gap:14px;}
  .grid3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px;}
  @media(max-width:600px){.grid,.grid3{grid-template-columns:1fr;}}
  label{display:block;font-size:.82rem;font-weight:600;margin-bottom:5px;}
  input,select,textarea{width:100%;padding:9px 11px;border:1px solid #ddd;border-radius:8px;font-size:.9rem;margin-bottom:12px;font-family:inherit;}
  textarea{resize:vertical;min-height:60px;}
  button, .btn{background:var(--red);color:#fff;border:none;padding:11px 22px;border-radius:8px;font-weight:700;cursor:pointer;font-size:.9rem;text-decoration:none;display:inline-block;}
  button:hover, .btn:hover{background:var(--red-dark);}
  .btn-secundario{background:#e6e9ec;color:var(--dark);}
  .btn-secundario:hover{background:#d8dce0;}
  .msg-ok{background:#eef7e6;color:#5a8a2c;padding:10px 14px;border-radius:8px;margin-bottom:16px;font-size:.85rem;}
  .msg-err{background:#fdecec;color:#9c0c24;padding:10px 14px;border-radius:8px;margin-bottom:16px;font-size:.85rem;}
  table{width:100%;border-collapse:collapse;font-size:.85rem;}
  th,td{text-align:left;padding:10px 8px;border-bottom:1px solid #eee;}
  th{color:#6b7a8a;font-weight:600;font-size:.78rem;text-transform:uppercase;}
  .acoes a{margin-right:10px;font-size:.8rem;color:var(--dark);text-decoration:underline;}
  .acoes a.apagar{color:var(--red);}
  .acoes form{display:inline;margin:0;}
  .linkbtn{background:none;color:var(--dark);border:none;padding:0;margin-right:10px;font-size:.8rem;text-decoration:underline;cursor:pointer;font-weight:400;font-family:inherit;}
  .linkbtn:hover{background:none;color:#000;}
  .linkbtn.apagar{color:var(--red);}
  .tag{display:inline-block;padding:3px 10px;border-radius:50px;font-size:.74rem;font-weight:700;}
  .tag-vermelho{background:#fdecec;color:#9c0c24;}
  .tag-amarelo{background:#fff6df;color:#a3760a;}
  .tag-verde{background:#eef7e6;color:#5a8a2c;}
  .stats{display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:14px;margin-bottom:24px;}
  .stat-box{background:#fff;border-radius:14px;padding:20px;box-shadow:0 4px 24px rgba(36,49,62,.08);}
  .stat-box .num{font-size:1.8rem;font-weight:800;font-family:'Sora',sans-serif;color:var(--dark);}
  .stat-box .lbl{font-size:.8rem;color:#6b7a8a;margin-top:4px;}
</style>
<header class="topbar">
  <div class="topbar-inner">
    <h1>Painel Previne</h1>
    <a class="topbar-user" href="logout.php">Sair (<?= htmlspecialchars($_SESSION['admin_utilizador'] ?? '') ?>)</a>
  </div>
</header>
<nav class="tabs">
  <div class="tabs-inner">
    <?php foreach ($itens as $link => $info): ?>
      <a href="<?= $link ?>" class="<?= $paginaAtual === $link ? 'ativo' : '' ?>"><i class="fas <?= $info[1] ?>"></i> <?= $info[0] ?></a>
    <?php endforeach; ?>
  </div>
</nav>
