<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: auth/login.php');
    exit();
}

require '../includes/db.php';

/// Exibe os produtos na cesta, se houver
$cestaProdutos = [];
if (!empty($_SESSION['cesta'])) {
    // Pega os IDs dos produtos na cesta
    $produtosIds = implode(',', array_map('intval', $_SESSION['cesta']));

    // Consulta para buscar detalhes dos produtos na cesta
    $stmt = $pdo->query("SELECT id, nome, preco, descricao FROM produtos WHERE id IN ($produtosIds)");
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
                        <th>Descrição</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cestaProdutos as $produto): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($produto['nome']); ?></td>
                            <td>R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></td>
                            <td><?php echo htmlspecialchars($produto['descricao']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
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
