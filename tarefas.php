<?php
require 'config.php';
require 'auth.php';
exigirLogin();
$paginaAtual = 'tarefas.php';

$sucesso = ''; $erro = '';

$editando = null;
if (isset($_GET['editar'])) {
    $stmt = $pdo->prepare("SELECT * FROM tarefas WHERE id = ?");
    $stmt->execute([(int)$_GET['editar']]);
    $editando = $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guardar'])) {
    verificarCsrf();
    $id = (int)($_POST['id'] ?? 0);
    $titulo = trim($_POST['titulo'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $responsavel = trim($_POST['responsavel'] ?? '');
    $prazo = $_POST['prazo'] ?: null;
    $prioridade = $_POST['prioridade'] ?? 'media';
    $estado = $_POST['estado'] ?? 'pendente';

    if (!$titulo) {
        $erro = 'O título da tarefa é obrigatório.';
    } elseif ($id) {
        $stmt = $pdo->prepare("UPDATE tarefas SET titulo=?, descricao=?, responsavel=?, prazo=?, prioridade=?, estado=? WHERE id=?");
        $stmt->execute([$titulo, $descricao, $responsavel, $prazo, $prioridade, $estado, $id]);
        $sucesso = "Tarefa \"$titulo\" atualizada.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO tarefas (titulo, descricao, responsavel, prazo, prioridade, estado) VALUES (?,?,?,?,?,?)");
        $stmt->execute([$titulo, $descricao, $responsavel, $prazo, $prioridade, $estado]);
        $sucesso = "Tarefa \"$titulo\" criada.";
    }
    $editando = null;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['apagar'])) {
    verificarCsrf();
    $stmt = $pdo->prepare("DELETE FROM tarefas WHERE id = ?");
    $stmt->execute([(int)$_POST['apagar']]);
    header('Location: tarefas.php');
    exit;
}

// Marcar como concluída rapidamente
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['concluir'])) {
    verificarCsrf();
    $stmt = $pdo->prepare("UPDATE tarefas SET estado = 'concluida' WHERE id = ?");
    $stmt->execute([(int)$_POST['concluir']]);
    header('Location: tarefas.php');
    exit;
}

$filtro = $_GET['filtro'] ?? '';
if ($filtro && in_array($filtro, ['pendente','em_progresso','concluida'])) {
    $stmt = $pdo->prepare("SELECT * FROM tarefas WHERE estado = ? ORDER BY FIELD(prioridade,'alta','media','baixa'), prazo ASC");
    $stmt->execute([$filtro]);
} else {
    $stmt = $pdo->query("SELECT * FROM tarefas ORDER BY estado != 'concluida' DESC, FIELD(prioridade,'alta','media','baixa'), prazo ASC");
}
$lista = $stmt->fetchAll(PDO::FETCH_ASSOC);

$rotulosEstado = ['pendente' => 'Pendente', 'em_progresso' => 'Em progresso', 'concluida' => 'Concluída'];
$tagsEstado = ['pendente' => 'tag-vermelho', 'em_progresso' => 'tag-amarelo', 'concluida' => 'tag-verde'];
$rotulosPrioridade = ['baixa' => 'Baixa', 'media' => 'Média', 'alta' => 'Alta'];
?>
<!DOCTYPE html>
<html lang="pt-MZ">
<head>
<meta charset="UTF-8">
<title>Tarefas — Previne</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="noindex, nofollow">
</head>
<body>
<?php include 'nav.php'; ?>
<div class="wrap">

  <div class="card">
    <h2><?= $editando ? 'Editar tarefa' : 'Nova tarefa' ?></h2>
    <?php if ($sucesso): ?><div class="msg-ok"><?= htmlspecialchars($sucesso) ?></div><?php endif; ?>
    <?php if ($erro): ?><div class="msg-err"><?= htmlspecialchars($erro) ?></div><?php endif; ?>

    <form method="post">
      <?= csrfCampo() ?>
      <input type="hidden" name="guardar" value="1">
      <input type="hidden" name="id" value="<?= $editando['id'] ?? '' ?>">
      <label>Título *</label>
      <input type="text" name="titulo" required value="<?= htmlspecialchars($editando['titulo'] ?? '') ?>">
      <label>Descrição</label>
      <textarea name="descricao"><?= htmlspecialchars($editando['descricao'] ?? '') ?></textarea>
      <div class="grid3">
        <div><label>Responsável</label><input type="text" name="responsavel" value="<?= htmlspecialchars($editando['responsavel'] ?? '') ?>"></div>
        <div><label>Prazo</label><input type="date" name="prazo" value="<?= htmlspecialchars($editando['prazo'] ?? '') ?>"></div>
        <div><label>Prioridade</label>
          <select name="prioridade">
            <?php foreach ($rotulosPrioridade as $val => $lbl): ?>
              <option value="<?= $val ?>" <?= (($editando['prioridade'] ?? 'media') === $val) ? 'selected' : '' ?>><?= $lbl ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <label>Estado</label>
      <select name="estado">
        <?php foreach ($rotulosEstado as $val => $lbl): ?>
          <option value="<?= $val ?>" <?= (($editando['estado'] ?? 'pendente') === $val) ? 'selected' : '' ?>><?= $lbl ?></option>
        <?php endforeach; ?>
      </select>

      <button type="submit"><?= $editando ? 'Guardar alterações' : 'Criar tarefa' ?></button>
      <?php if ($editando): ?><a href="tarefas.php" class="btn btn-secundario">Cancelar</a><?php endif; ?>
    </form>
  </div>

  <div class="card">
    <h2>Tarefas (<?= count($lista) ?>)</h2>
    <div style="margin-bottom:14px;">
      <a href="tarefas.php" class="btn <?= $filtro === '' ? '' : 'btn-secundario' ?>" style="padding:7px 14px;font-size:.8rem;">Todas</a>
      <a href="tarefas.php?filtro=pendente" class="btn <?= $filtro === 'pendente' ? '' : 'btn-secundario' ?>" style="padding:7px 14px;font-size:.8rem;">Pendentes</a>
      <a href="tarefas.php?filtro=em_progresso" class="btn <?= $filtro === 'em_progresso' ? '' : 'btn-secundario' ?>" style="padding:7px 14px;font-size:.8rem;">Em progresso</a>
      <a href="tarefas.php?filtro=concluida" class="btn <?= $filtro === 'concluida' ? '' : 'btn-secundario' ?>" style="padding:7px 14px;font-size:.8rem;">Concluídas</a>
    </div>
    <table>
      <tr><th>Título</th><th>Responsável</th><th>Prazo</th><th>Prioridade</th><th>Estado</th><th></th></tr>
      <?php foreach ($lista as $t): ?>
      <tr>
        <td><?= htmlspecialchars($t['titulo']) ?></td>
        <td><?= htmlspecialchars($t['responsavel'] ?? '—') ?></td>
        <td><?= $t['prazo'] ? htmlspecialchars(date('d/m/Y', strtotime($t['prazo']))) : '—' ?></td>
        <td><?= $rotulosPrioridade[$t['prioridade']] ?></td>
        <td><span class="tag <?= $tagsEstado[$t['estado']] ?>"><?= $rotulosEstado[$t['estado']] ?></span></td>
        <td class="acoes">
          <?php if ($t['estado'] !== 'concluida'): ?><form method="post"><?= csrfCampo() ?><input type="hidden" name="concluir" value="<?= $t['id'] ?>"><button type="submit" class="linkbtn">Concluir</button></form><?php endif; ?>
          <a href="tarefas.php?editar=<?= $t['id'] ?>">Editar</a>
          <form method="post" onsubmit="return confirm('Apagar esta tarefa?')"><?= csrfCampo() ?><input type="hidden" name="apagar" value="<?= $t['id'] ?>"><button type="submit" class="linkbtn apagar">Apagar</button></form>
        </td>
      </tr>
      <?php endforeach; ?>
      <?php if (!$lista): ?><tr><td colspan="6" style="color:#737373;">Nenhuma tarefa encontrada.</td></tr><?php endif; ?>
    </table>
  </div>

</div>
</body>
</html>
