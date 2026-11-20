<?php
require 'config.php';
require 'auth.php';
exigirLogin();
$paginaAtual = 'admin.php';

$sucesso = '';
$erro = '';
$novoCodigo = '';

// Adicionar novo certificado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['adicionar'])) {
    verificarCsrf();
    $nome = trim($_POST['nome_aluno'] ?? '');
    $empresa = trim($_POST['empresa'] ?? '');
    $curso = trim($_POST['curso'] ?? '');
    $data_emissao = $_POST['data_emissao'] ?? '';
    $carga = trim($_POST['carga_horaria'] ?? '');
    $instrutor = trim($_POST['instrutor'] ?? '');

    if (!$nome || !$curso || !$data_emissao) {
        $erro = 'Nome, curso e data de emissão são obrigatórios.';
    } else {
        $codigo = gerarCodigo($pdo);
        $stmt = $pdo->prepare("INSERT INTO certificados (codigo, nome_aluno, empresa, curso, data_emissao, carga_horaria, instrutor) VALUES (?,?,?,?,?,?,?)");
        $stmt->execute([$codigo, $nome, $empresa, $curso, $data_emissao, $carga, $instrutor]);
        $sucesso = "Certificado criado com sucesso para $nome.";
        $novoCodigo = $codigo;
    }
}

// Apagar certificado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['apagar'])) {
    verificarCsrf();
    $stmt = $pdo->prepare("DELETE FROM certificados WHERE id = ?");
    $stmt->execute([(int)$_POST['apagar']]);
    header('Location: admin.php');
    exit;
}

$lista = $pdo->query("SELECT * FROM certificados ORDER BY criado_em DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-MZ">
<head>
<meta charset="UTF-8">
<title>Painel de Certificados — Previne</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="noindex, nofollow">
</head>
<body>
<?php include 'nav.php'; ?>
<div class="wrap">

  <div class="card">
    <h2>Adicionar novo certificado</h2>
    <?php if ($sucesso): ?><div class="msg-ok"><?= htmlspecialchars($sucesso) ?></div><?php endif; ?>
    <?php if ($erro): ?><div class="msg-err"><?= htmlspecialchars($erro) ?></div><?php endif; ?>

    <?php if ($novoCodigo): ?>
    <div class="qr-preview">
      <img src="https://api.qrserver.com/v1/create-qr-code/?size=160x160&data=<?= urlencode(SITE_URL . '/verificar.php?codigo=' . $novoCodigo) ?>" alt="QR Code">
      <div>
        <div style="font-size:.8rem;color:#737373;">Código gerado</div>
        <div class="codigo" style="font-size:1.1rem;"><?= htmlspecialchars($novoCodigo) ?></div>
        <div style="font-size:.78rem;color:#737373;margin-top:4px;">Coloca este QR no certificado do aluno (imprime a imagem acima ou usa o link direto).</div>
      </div>
    </div>
    <?php endif; ?>

    <form method="post">
      <?= csrfCampo() ?>
      <input type="hidden" name="adicionar" value="1">
      <div class="grid">
        <div><label>Nome do aluno *</label><input type="text" name="nome_aluno" required></div>
        <div><label>Empresa / Instituição</label><input type="text" name="empresa"></div>
        <div><label>Curso / Formação *</label><input type="text" name="curso" required placeholder="Ex: Primeiros Socorros Básico"></div>
        <div><label>Data de emissão *</label><input type="date" name="data_emissao" required></div>
        <div><label>Carga horária</label><input type="text" name="carga_horaria" placeholder="Ex: 8 horas"></div>
        <div><label>Instrutor</label><input type="text" name="instrutor"></div>
      </div>
      <button type="submit">Gerar certificado e QR</button>
    </form>
  </div>

  <div class="card">
    <h2>Certificados emitidos (<?= count($lista) ?>)</h2>
    <table>
      <tr><th>Código</th><th>Aluno</th><th>Curso</th><th>Data</th><th></th></tr>
      <?php foreach ($lista as $c): ?>
      <tr>
        <td class="codigo"><?= htmlspecialchars($c['codigo']) ?></td>
        <td><?= htmlspecialchars($c['nome_aluno']) ?></td>
        <td><?= htmlspecialchars($c['curso']) ?></td>
        <td><?= htmlspecialchars(date('d/m/Y', strtotime($c['data_emissao']))) ?></td>
        <td class="acoes">
          <a href="verificar.php?codigo=<?= urlencode($c['codigo']) ?>" target="_blank">Ver</a>
          <form method="post" onsubmit="return confirm('Apagar este certificado?')"><?= csrfCampo() ?><input type="hidden" name="apagar" value="<?= $c['id'] ?>"><button type="submit" class="linkbtn apagar">Apagar</button></form>
        </td>
      </tr>
      <?php endforeach; ?>
      <?php if (!$lista): ?><tr><td colspan="5" style="color:#737373;">Ainda não há certificados.</td></tr><?php endif; ?>
    </table>
  </div>

</div>
</body>
</html>
