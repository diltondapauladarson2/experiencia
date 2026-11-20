<?php
require 'config.php';
require 'auth.php';
exigirLogin();
$paginaAtual = 'estoque.php';

$sucesso = ''; $erro = '';

$editando = null;
if (isset($_GET['editar'])) {
    $stmt = $pdo->prepare("SELECT * FROM estoque WHERE id = ?");
    $stmt->execute([(int)$_GET['editar']]);
    $editando = $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guardar'])) {
    verificarCsrf();
    $id = (int)($_POST['id'] ?? 0);
    $item = trim($_POST['item'] ?? '');
    $categoria = trim($_POST['categoria'] ?? '');
    $quantidade = (int)($_POST['quantidade'] ?? 0);
    $unidade = trim($_POST['unidade'] ?? '');
    $qtdMinima = (int)($_POST['quantidade_minima'] ?? 0);
    $notas = trim($_POST['notas'] ?? '');

    if (!$item) {
        $erro = 'O nome do item é obrigatório.';
    } elseif ($id) {
        $stmt = $pdo->prepare("UPDATE estoque SET item=?, categoria=?, quantidade=?, unidade=?, quantidade_minima=?, notas=? WHERE id=?");
        $stmt->execute([$item, $categoria, $quantidade, $unidade, $qtdMinima, $notas, $id]);
        $sucesso = "Item \"$item\" atualizado.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO estoque (item, categoria, quantidade, unidade, quantidade_minima, notas) VALUES (?,?,?,?,?,?)");
        $stmt->execute([$item, $categoria, $quantidade, $unidade, $qtdMinima, $notas]);
        $sucesso = "Item \"$item\" adicionado ao estoque.";
    }
    $editando = null;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['apagar'])) {
    verificarCsrf();
    $stmt = $pdo->prepare("DELETE FROM estoque WHERE id = ?");
    $stmt->execute([(int)$_POST['apagar']]);
    header('Location: estoque.php');
    exit;
}

$lista = $pdo->query("SELECT * FROM estoque ORDER BY item ASC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-MZ">
<head>
<meta charset="UTF-8">
<title>Estoque — Previne</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="noindex, nofollow">
</head>
<body>
<?php include 'nav.php'; ?>
<div class="wrap">

  <div class="card">
    <h2><?= $editando ? 'Editar item' : 'Adicionar item ao estoque' ?></h2>
    <?php if ($sucesso): ?><div class="msg-ok"><?= htmlspecialchars($sucesso) ?></div><?php endif; ?>
    <?php if ($erro): ?><div class="msg-err"><?= htmlspecialchars($erro) ?></div><?php endif; ?>

    <form method="post">
      <?= csrfCampo() ?>
      <input type="hidden" name="guardar" value="1">
      <input type="hidden" name="id" value="<?= $editando['id'] ?? '' ?>">
      <div class="grid">
        <div><label>Item *</label><input type="text" name="item" required placeholder="Ex: Kit de primeiros socorros" value="<?= htmlspecialchars($editando['item'] ?? '') ?>"></div>
        <div><label>Categoria</label><input type="text" name="categoria" placeholder="Ex: Material médico" value="<?= htmlspecialchars($editando['categoria'] ?? '') ?>"></div>
      </div>
      <div class="grid3">
        <div><label>Quantidade</label><input type="number" name="quantidade" min="0" value="<?= htmlspecialchars($editando['quantidade'] ?? 0) ?>"></div>
        <div><label>Unidade</label><input type="text" name="unidade" placeholder="Ex: unidades, caixas" value="<?= htmlspecialchars($editando['unidade'] ?? '') ?>"></div>
        <div><label>Quantidade mínima (alerta)</label><input type="number" name="quantidade_minima" min="0" value="<?= htmlspecialchars($editando['quantidade_minima'] ?? 0) ?>"></div>
      </div>
      <label>Notas</label>
      <textarea name="notas"><?= htmlspecialchars($editando['notas'] ?? '') ?></textarea>

      <button type="submit"><?= $editando ? 'Guardar alterações' : 'Adicionar item' ?></button>
      <?php if ($editando): ?><a href="estoque.php" class="btn btn-secundario">Cancelar</a><?php endif; ?>
    </form>
  </div>

  <div class="card">
    <h2>Itens em estoque (<?= count($lista) ?>)</h2>
    <table>
      <tr><th>Item</th><th>Categoria</th><th>Quantidade</th><th>Unidade</th><th>Estado</th><th></th></tr>
      <?php foreach ($lista as $e): $baixo = $e['quantidade'] <= $e['quantidade_minima']; ?>
      <tr>
        <td><?= htmlspecialchars($e['item']) ?></td>
        <td><?= htmlspecialchars($e['categoria'] ?? '—') ?></td>
        <td><?= (int)$e['quantidade'] ?></td>
        <td><?= htmlspecialchars($e['unidade'] ?? '—') ?></td>
        <td><?php if ($baixo): ?><span class="tag tag-vermelho">Estoque baixo</span><?php else: ?><span class="tag tag-verde">OK</span><?php endif; ?></td>
        <td class="acoes">
          <a href="estoque.php?editar=<?= $e['id'] ?>">Editar</a>
          <form method="post" onsubmit="return confirm('Apagar este item?')"><?= csrfCampo() ?><input type="hidden" name="apagar" value="<?= $e['id'] ?>"><button type="submit" class="linkbtn apagar">Apagar</button></form>
        </td>
      </tr>
      <?php endforeach; ?>
      <?php if (!$lista): ?><tr><td colspan="6" style="color:#737373;">Nenhum item no estoque.</td></tr><?php endif; ?>
    </table>
  </div>

</div>
</body>
</html>
