<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $preco = str_replace(',', '.', $_POST['preco']);
    $descricao = $_POST['descricao'];
    $quantidade = $_POST['quantidade'];
    $fornecedor_id = $_POST['fornecedor'];

    $stmt = $pdo->prepare("UPDATE produtos SET nome = ?, preco = ?, descricao = ?, quantidade = ?, fornecedor_id = ? WHERE id = ?");
    if ($stmt->execute([$nome, $preco, $descricao, $quantidade, $fornecedor_id, $id])) {
        header('Location: ../index.php'); // Redireciona após salvar
        exit();
    } else {
        echo "Erro ao atualizar produto.";
    }
} else {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM produtos WHERE id = ?");
    $stmt->execute([$id]);
    $produto = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$produto) {
        echo "Produto não encontrado.";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Produto</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Editar Produto</h2>
        <form method="POST">
            <input type="hidden" name="id" value="<?php echo $produto['id']; ?>">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome do Produto</label>
                <input type="text" class="form-control" id="nome" name="nome" value="<?php echo $produto['nome']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="preco" class="form-label">Preço</label>
                <input type="text" class="form-control" id="preco" name="preco" value="<?php echo number_format($produto['preco'], 2, ',', '.'); ?>" required>
            </div>
            <div class="mb-3">
                <label for="quantidade" class="form-label">Quantidade</label>
                <input type="number" class="form-control" id="quantidade" name="quantidade" value="<?php echo $produto['quantidade']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="fornecedor" class="form-label">Fornecedor</label>
                <select class="form-control" id="fornecedor" name="fornecedor" required>
                    <option value="">Selecione um fornecedor</option>
                    <?php
                    // Carregar fornecedores
                    $stmt = $pdo->query("SELECT id, nome FROM fornecedores");
                    $fornecedores = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($fornecedores as $fornecedor):
                    ?>
                        <option value="<?php echo $fornecedor['id']; ?>" <?php if ($fornecedor['id'] == $produto['fornecedor_id']) echo 'selected'; ?>>
                            <?php echo $fornecedor['nome']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="descricao" class="form-label">Descrição</label>
                <textarea class="form-control" id="descricao" name="descricao" required><?php echo $produto['descricao']; ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
        </form>
    </div>
</body>
</html>
