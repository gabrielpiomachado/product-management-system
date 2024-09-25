<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
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
                <td><input type='checkbox' name='produtos_selecionados[]' value='{$produto['id']}'></td>
                <td>{$produto['nome']}</td>
                <td>R$ " . number_format($produto['preco'], 2, ',', '.') . "</td>
                <td>{$produto['quantidade']}</td>
                <td>{$produto['fornecedor']}</td>
                <td>{$produto['descricao']}</td>
                <td>
                    <a href='auth/editar_produto.php?id={$produto['id']}' class='btn btn-warning'>Editar</a>
                    <button class='btn btn-danger' onclick='deletarProduto({$produto['id']})'>Excluir</button>
                </td>
            </tr>
        ";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ação para excluir o produto
    if ($_POST['action'] == 'delete' && isset($_POST['id'])) {
        $id = $_POST['id'];
        $stmt = $pdo->prepare("DELETE FROM produtos WHERE id = ?");
        $stmt->execute([$id]);
        echo "Produto excluído com sucesso!";
        exit();
    }
    
    // Ação para adicionar produtos à cesta
    if ($_POST['action'] == 'adicionar_cesta' && !empty($_POST['produtos_selecionados'])) {
        $produtosSelecionados = $_POST['produtos_selecionados'];

        // Associar a cesta ao usuário autenticado
        $user_id = $_SESSION['user_id'];
        if (!isset($_SESSION['cesta'])) {
            $_SESSION['cesta'] = [];
        }

        foreach ($produtosSelecionados as $produto_id) {
            // Verificar se o produto já está na cesta
            if (!in_array($produto_id, $_SESSION['cesta'])) {
                $_SESSION['cesta'][] = $produto_id;
            }
        }

        echo "Produtos enviados para a cesta com sucesso!";
        exit();
    }
}
?>
