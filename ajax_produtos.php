<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Listar os produtos
    $stmt = $pdo->query("SELECT p.id, p.nome, p.preco, p.quantidade, p.descricao, f.nome AS fornecedor 
                         FROM produtos p 
                         LEFT JOIN fornecedores f ON p.fornecedor_id = f.id");
    $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($produtos as $produto) {
        echo "
            <tr>
                <td>{$produto['nome']}</td>
                <td>R$ " . number_format($produto['preco'], 2, ',', '.') . "</td>
                <td>{$produto['quantidade']}</td>
                <td>{$produto['fornecedor']}</td>
                <td>{$produto['descricao']}</td>
                <td>
                    <button class='btn btn-warning' onclick='editarProduto({$produto['id']})'>Editar</button>
                    <button class='btn btn-danger' onclick='deletarProduto({$produto['id']})'>Excluir</button>
                </td>
            </tr>
        ";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_POST['action'] == 'delete' && isset($_POST['id'])) {
        // Deletar o produto pelo ID
        $id = $_POST['id'];
        $stmt = $pdo->prepare("DELETE FROM produtos WHERE id = ?");
        $stmt->execute([$id]);
        echo "Produto excluído com sucesso!";
    }
}
?>
