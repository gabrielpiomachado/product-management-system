<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$message = '';
$alert_class = '';

require '../includes/db.php';
require 'Produto.php';

$fornecedores = [];
$stmt = $pdo->query("SELECT id, nome FROM fornecedores");
$fornecedores = $stmt->fetchAll(PDO::FETCH_ASSOC); // Recupera todos os fornecedores

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $preco = str_replace(',', '.', $_POST['preco']);
    $descricao = $_POST['descricao'];
    $quantidade = $_POST['quantidade'];
    $fornecedor_id = $_POST['fornecedor'];

    $produto = new Produto($nome, $preco, $descricao, $quantidade, $fornecedor_id);
    
    if ($produto->salvar()) {
        $message = "Produto cadastrado com sucesso!";
        $alert_class = 'alert-success';
    } else {
        $message = "Erro ao cadastrar produto.";
        $alert_class = 'alert-danger';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Produto</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php include '../views/header.php'; ?>
    <div class="container mt-5">
        <h2>Cadastro de Produto</h2>
        <?php if ($message): ?>
            <div class="alert <?php echo $alert_class; ?>" role="alert">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome do Produto</label>
                <input type="text" class="form-control" id="nome" name="nome" required>
            </div>
            <div class="mb-3">
                <label for="preco" class="form-label">Preço</label>
                <input type="text" class="form-control" id="preco" name="preco" required>
            </div>
            <div class="mb-3">
                <label for="quantidade" class="form-label">Quantidade</label>
                <input type="number" class="form-control" id="quantidade" name="quantidade" required>
            </div>
            <div class="mb-3">
                <label for="fornecedor" class="form-label">Fornecedor</label>
                <select class="form-control" id="fornecedor" name="fornecedor" required>
                    <option value="">Selecione um fornecedor</option>
                    <?php foreach ($fornecedores as $fornecedor): ?>
                        <option value="<?php echo $fornecedor['id']; ?>"><?php echo $fornecedor['nome']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="descricao" class="form-label">Descrição</label>
                <textarea class="form-control" id="descricao" name="descricao" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Cadastrar Produto</button>
        </form>

        <div class="mt-3">
            <p>Não tem um fornecedor cadastrado? <a href="cadastro_fornecedor.php" class="btn btn-secondary">Cadastre um fornecedor</a></p>
        </div>
    </div>
    <?php include '../views/footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
