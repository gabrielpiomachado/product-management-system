<?php
require '../includes/db.php';
session_start();

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    // Validação do email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Email inválido!";
    } else {
        // Verificar se o email já está registrado
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error_message = "O email já está registrado!";
        } else {
            // Gerar salt e hash da senha
            $salt = bin2hex(random_bytes(16));
            $hashed_password = hash('sha256', $salt . $password);

            // Inserir o usuário no banco de dados
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password, salt) VALUES (?, ?, ?, ?)");
            if ($stmt->execute([$username, $email, $hashed_password, $salt])) {
                // Redirecionar para login se o cadastro for bem-sucedido
                header('Location: login.php');
                exit();
            } else {
                $error_message = "Erro ao registrar usuário.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Cadastro de Usuário</h2>

        <?php if ($error_message): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Nome de Usuário</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Senha</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Cadastrar</button>
        </form>

        <div class="mt-3">
            <p>Já possui uma conta? <a href="login.php" class="btn btn-secondary">Faça login</a></p>
        </div>
    </div>
</body>
</html>
