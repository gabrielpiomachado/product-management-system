<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require '../includes/db.php';
require 'Fornecedor.php';

$message = '';
$alert_class = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $contato = $_POST['contato'];
    $cnpj = $_POST['cnpj'];

    try {
        $fornecedor = new Fornecedor($nome, $contato, $cnpj);
        
        if ($fornecedor->salvar()) {
            $message = "Fornecedor cadastrado com sucesso!";
            $alert_class = 'alert-success';
        } else {
            $message = "Erro ao cadastrar fornecedor. CNPJ já existe.";
            $alert_class = 'alert-danger';
        }
    } catch (Exception $e) {
        $message = $e->getMessage();  // Mostra a mensagem de erro da validação
        $alert_class = 'alert-danger';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Fornecedor</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php include '../views/header.php'; ?>
    <div class="container mt-5">
        <h2>Cadastro de Fornecedor</h2>
        <?php if ($message): ?>
            <div class="alert <?php echo $alert_class; ?>" role="alert">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome do Fornecedor</label>
                <input type="text" class="form-control" id="nome" name="nome" required>
            </div>
            <div class="mb-3">
                <label for="contato" class="form-label">Contato</label>
                <input type="text" class="form-control" id="contato" name="contato" required placeholder="(99) 9999-9999">
            </div>
            <div class="mb-3">
                <label for="cnpj" class="form-label">CNPJ</label>
                <input type="text" class="form-control" id="cnpj" name="cnpj" required placeholder="99.999.999/0001-99">
            </div>
            <button type="submit" class="btn btn-primary">Cadastrar Fornecedor</button>
        </form>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-mask-plugin/1.14.16/jquery.mask.min.js"></script>
    <script>
        $(document).ready(function(){
            $('#contato').mask('(00) 0000-0000'); // Máscara para o telefone
            $('#cnpj').mask('00.000.000/0000-00'); // Máscara para o CNPJ
        });
    </script>

    </div>
    <?php include '../views/footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
