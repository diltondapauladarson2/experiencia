<?php
require 'config.php';
require 'auth.php';
exigirLogin();
$paginaAtual = 'cursos.php';

$sucesso = ''; $erro = '';

// Editar registo existente (carrega dados no formulário)
$editando = null;
if (isset($_GET['editar'])) {
    $stmt = $pdo->prepare("SELECT * FROM cursos_alunos WHERE id = ?");
    $stmt->execute([(int)$_GET['editar']]);
    $editando = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Guardar (novo ou edição)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guardar'])) {
    verificarCsrf();
    $id = (int)($_POST['id'] ?? 0);
    $nome = trim($_POST['nome'] ?? '');
    $idade = $_POST['idade'] !== '' ? (int)$_POST['idade'] : null;
    $sexo = $_POST['sexo'] ?: null;
    $morada = trim($_POST['morada'] ?? '');
    $proveniencia = trim($_POST['proveniencia'] ?? '');
    $curso = trim($_POST['curso'] ?? '');
    $estado = $_POST['estado'] ?? 'nao_escrito';
    $outras = trim($_POST['outras_informacoes'] ?? '');

    if (!$nome) {
        $erro = 'O nome do aluno é obrigatório.';
    } elseif ($id) {
        $stmt = $pdo->prepare("UPDATE cursos_alunos SET nome=?, idade=?, sexo=?, morada=?, proveniencia=?, curso=?, estado=?, outras_informacoes=? WHERE id=?");
        $stmt->execute([$nome, $idade, $sexo, $morada, $proveniencia, $curso, $estado, $outras, $id]);
        $sucesso = "Registo de $nome atualizado.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO cursos_alunos (nome, idade, sexo, morada, proveniencia, curso, estado, outras_informacoes) VALUES (?,?,?,?,?,?,?,?)");
        $stmt->execute([$nome, $idade, $sexo, $morada, $proveniencia, $curso, $estado, $outras]);
        $sucesso = "$nome adicionado com sucesso.";
    }
    $editando = null;
}

// Apagar
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['apagar'])) {
    verificarCsrf();
    $stmt = $pdo->prepare("DELETE FROM cursos_alunos WHERE id = ?");
    $stmt->execute([(int)$_POST['apagar']]);
    header('Location: cursos.php');
    exit;
}

// Filtro por estado
$filtro = $_GET['filtro'] ?? '';
if ($filtro && in_array($filtro, ['nao_escrito','escrito','concluido'])) {
    $stmt = $pdo->prepare("SELECT * FROM cursos_alunos WHERE estado = ? ORDER BY criado_em DESC");
    $stmt->execute([$filtro]);
} else {
    $stmt = $pdo->query("SELECT * FROM cursos_alunos ORDER BY criado_em DESC");
}
$lista = $stmt->fetchAll(PDO::FETCH_ASSOC);

$rotulos = ['nao_escrito' => 'Não escrito', 'escrito' => 'Escrito', 'concluido' => 'Concluído'];
$tags = ['nao_escrito' => 'tag-vermelho', 'escrito' => 'tag-amarelo', 'concluido' => 'tag-verde'];
?>
<!DOCTYPE html>
<html lang="pt-MZ">
<head>
<meta charset="UTF-8">
<title>Alunos / Cursos — Previne</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="noindex, nofollow">
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@700;800&display=swap" rel="stylesheet">
</head>
<body>
<?php include 'nav.php'; ?>
<div class="wrap">

  <div class="card">
    <h2><?= $editando ? 'Editar aluno' : 'Adicionar novo aluno' ?></h2>
    <?php if ($sucesso): ?><div class="msg-ok"><?= htmlspecialchars($sucesso) ?></div><?php endif; ?>
    <?php if ($erro): ?><div class="msg-err"><?= htmlspecialchars($erro) ?></div><?php endif; ?>

    <form method="post">
      <?= csrfCampo() ?>
      <input type="hidden" name="guardar" value="1">
      <input type="hidden" name="id" value="<?= $editando['id'] ?? '' ?>">
      <div class="grid3">
        <div><label>Nome *</label><input type="text" name="nome" required value="<?= htmlspecialchars($editando['nome'] ?? '') ?>"></div>
        <div><label>Idade</label><input type="number" name="idade" min="0" value="<?= htmlspecialchars($editando['idade'] ?? '') ?>"></div>
        <div><label>Sexo</label>
          <select name="sexo">
            <option value="">—</option>
            <option value="M" <?= (($editando['sexo'] ?? '') === 'M') ? 'selected' : '' ?>>Masculino</option>
            <option value="F" <?= (($editando['sexo'] ?? '') === 'F') ? 'selected' : '' ?>>Feminino</option>
          </select>
        </div>
      </div>
      <div class="grid">
        <div><label>Morada</label><input type="text" name="morada" value="<?= htmlspecialchars($editando['morada'] ?? '') ?>"></div>
        <div><label>Proveniência</label><input type="text" name="proveniencia" placeholder="Ex: Chimoio, Manica" value="<?= htmlspecialchars($editando['proveniencia'] ?? '') ?>"></div>
      </div>
      <div class="grid">
        <div><label>Curso</label><input type="text" name="curso" placeholder="Ex: Primeiros Socorros Básico" value="<?= htmlspecialchars($editando['curso'] ?? '') ?>"></div>
        <div><label>Estado do curso</label>
          <select name="estado">
            <?php foreach ($rotulos as $val => $lbl): ?>
              <option value="<?= $val ?>" <?= (($editando['estado'] ?? 'nao_escrito') === $val) ? 'selected' : '' ?>><?= $lbl ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <label>Outras informações</label>
      <textarea name="outras_informacoes"><?= htmlspecialchars($editando['outras_informacoes'] ?? '') ?></textarea>

      <button type="submit"><?= $editando ? 'Guardar alterações' : 'Adicionar aluno' ?></button>
      <?php if ($editando): ?><a href="cursos.php" class="btn btn-secundario">Cancelar</a><?php endif; ?>
    </form>
  </div>

  <div class="card">
    <h2>Alunos registados (<?= count($lista) ?>)</h2>
    <div style="margin-bottom:14px;">
      <a href="cursos.php" class="btn <?= $filtro === '' ? '' : 'btn-secundario' ?>" style="padding:7px 14px;font-size:.8rem;">Todos</a>
      <a href="cursos.php?filtro=nao_escrito" class="btn <?= $filtro === 'nao_escrito' ? '' : 'btn-secundario' ?>" style="padding:7px 14px;font-size:.8rem;">Não escritos</a>
      <a href="cursos.php?filtro=escrito" class="btn <?= $filtro === 'escrito' ? '' : 'btn-secundario' ?>" style="padding:7px 14px;font-size:.8rem;">Escritos</a>
      <a href="cursos.php?filtro=concluido" class="btn <?= $filtro === 'concluido' ? '' : 'btn-secundario' ?>" style="padding:7px 14px;font-size:.8rem;">Concluídos</a>
    </div>
    <table>
      <tr><th>Nome</th><th>Idade</th><th>Sexo</th><th>Proveniência</th><th>Curso</th><th>Estado</th><th></th></tr>
      <?php foreach ($lista as $a): ?>
      <tr>
        <td><?= htmlspecialchars($a['nome']) ?></td>
        <td><?= htmlspecialchars($a['idade'] ?? '—') ?></td>
        <td><?= htmlspecialchars($a['sexo'] ?? '—') ?></td>
        <td><?= htmlspecialchars($a['proveniencia'] ?? '—') ?></td>
        <td><?= htmlspecialchars($a['curso'] ?? '—') ?></td>
        <td><span class="tag <?= $tags[$a['estado']] ?>"><?= $rotulos[$a['estado']] ?></span></td>
        <td class="acoes">
          <a href="cursos.php?editar=<?= $a['id'] ?>">Editar</a>
          <form method="post" onsubmit="return confirm('Apagar este aluno?')"><?= csrfCampo() ?><input type="hidden" name="apagar" value="<?= $a['id'] ?>"><button type="submit" class="linkbtn apagar">Apagar</button></form>
        </td>
      </tr>
      <?php endforeach; ?>
      <?php if (!$lista): ?><tr><td colspan="7" style="color:#6b7a8a;">Nenhum aluno encontrado.</td></tr><?php endif; ?>
    </table>
  </div>

</div>
</body>
</html>
