<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require '../includes/db.php';

class Fornecedor {
    private $pdo;
    private $nome;
    private $contato;
    private $cnpj;

    public function __construct($nome, $contato, $cnpj) {
        global $pdo;
        $this->pdo = $pdo;
        $this->nome = $nome;
        $this->contato = $contato;
        $this->cnpj = $cnpj;
    }

    // Verifica se o CNPJ já existe no banco de dados
    private function cnpjExiste() {
        $stmt = $this->pdo->prepare("SELECT id FROM fornecedores WHERE cnpj = ?");
        $stmt->execute([$this->cnpj]);
        return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
    }

    // Valida o formato do contato (99) 9999-9999
    private function validarContato() {
        return preg_match("/^\(\d{2}\) \d{4}-\d{4}$/", $this->contato);
    }

    // Valida o formato do CNPJ 99.999.999/0001-99
    private function validarCNPJ() {
        return preg_match("/^\d{2}\.\d{3}\.\d{3}\/\d{4}-\d{2}$/", $this->cnpj);
    }

    public function salvar() {
        // Verifica se o CNPJ já está cadastrado
        if ($this->cnpjExiste()) {
            return false;  // CNPJ já existe
        }

        // Valida o formato do contato
        if (!$this->validarContato()) {
            throw new Exception("Formato de contato inválido. O formato deve ser (99) 9999-9999.");
        }

        // Valida o formato do CNPJ
        if (!$this->validarCNPJ()) {
            throw new Exception("Formato de CNPJ inválido. O formato deve ser 99.999.999/0001-99.");
        }

        // Continua com o cadastro do fornecedor
        $stmt = $this->pdo->prepare("INSERT INTO fornecedores (nome, contato, cnpj) VALUES (?, ?, ?)");
        return $stmt->execute([$this->nome, $this->contato, $this->cnpj]);
    }
}
?>
