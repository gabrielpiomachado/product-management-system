<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require '../includes/db.php';

// Verifica se um produto deve ser removido da cesta
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remover'])) {
    $produtoId = intval($_POST['produto_id']);
    if (isset($_SESSION['cesta']) && in_array($produtoId, $_SESSION['cesta'])) {
        // Remove o produto da cesta
        $_SESSION['cesta'] = array_diff($_SESSION['cesta'], [$produtoId]);
    }
}

/// Exibe os produtos na cesta, se houver
$cestaProdutos = [];
$totalCesta = 0;
$totalQuantidade = 0;

if (!empty($_SESSION['cesta'])) {
    // Pega os IDs dos produtos na cesta
    $produtosIds = implode(',', array_map('intval', $_SESSION['cesta']));

    // Consulta para buscar detalhes dos produtos na cesta
    $stmt = $pdo->query("SELECT id, nome, preco, quantidade, descricao FROM produtos WHERE id IN ($produtosIds)");
    $cestaProdutos = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cesta de Produtos</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php include '../views/header.php'; ?>

    <div class="container mt-5">
        <h2>Cesta de Produtos</h2>

        <?php if (!empty($cestaProdutos)): ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Preço</th>
                        <th>Quantidade</th>
                        <th>Descrição</th>
                        <th>Total</th>
                        <th>Remover</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cestaProdutos as $produto): 
                        $subtotal = $produto['preco'] * $produto['quantidade']; // Cálculo do subtotal
                        $totalCesta += $subtotal; // Soma ao total da cesta
                        $totalQuantidade += $produto['quantidade']; // Soma ao total de quantidades
                    ?>
                        <tr>
                            <td><?php echo htmlspecialchars($produto['nome']); ?></td>
                            <td>R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></td>
                            <td><?php echo $produto['quantidade']; ?></td>
                            <td><?php echo htmlspecialchars($produto['descricao']); ?></td>
                            <td>R$ <?php echo number_format($subtotal, 2, ',', '.'); ?></td>
                            <td>
                                <form method="POST">
                                    <input type="hidden" name="produto_id" value="<?php echo $produto['id']; ?>">
                                    <button type="submit" name="remover" class="btn btn-danger">Retirar Produto</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td><strong>Total de Itens na Cesta:</strong></td>
                        <td><strong><?php echo $totalQuantidade; ?></strong></td>
                        <td><strong>Valor Total da Cesta:</strong></td>
                        <td colspan="2"><strong>R$ <?php echo number_format($totalCesta, 2, ',', '.'); ?></strong></td>
                    </tr>
                </tfoot>
            </table>

        <?php else: ?>
            <p>Cesta vazia.</p>
        <?php endif; ?>
    </div>

    <?php include '../views/footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
