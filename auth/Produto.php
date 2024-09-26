<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require '../includes/db.php';

class Produto {
    private $pdo;
    private $nome;
    private $preco;
    private $descricao;
    private $quantidade;
    private $fornecedor_id;

    public function __construct($nome, $preco, $descricao, $quantidade, $fornecedor_id) {
        global $pdo;
        $this->pdo = $pdo;
        $this->nome = $nome;
        $this->preco = $preco;
        $this->descricao = $descricao;
        $this->quantidade = $quantidade;
        $this->fornecedor_id = $fornecedor_id;
    }

    public function salvar() {
        $stmt = $this->pdo->prepare("INSERT INTO produtos (nome, preco, descricao, quantidade, fornecedor_id) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$this->nome, $this->preco, $this->descricao, $this->quantidade, $this->fornecedor_id]);
    }
}
?>
