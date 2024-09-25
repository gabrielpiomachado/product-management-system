// Função para carregar os produtos via AJAX
function carregarProdutos() {
    $.ajax({
        url: '/TrabalhoPelegrin/ajax_produtos.php', // Revisar
        type: 'GET',
        success: function(response) {
            $('#produtos-lista').html(response);  // Atualiza a lista de produtos
        }
    });
}

// Função para deletar um produto
function deletarProduto(id) {
    if (confirm('Tem certeza que deseja excluir este produto?')) {
        $.ajax({
            url: '/TrabalhoPelegrin/ajax_produtos.php', // Revisar
            type: 'POST',
            data: { action: 'delete', id: id },
            success: function(response) {
                carregarProdutos();  // Recarrega a lista de produtos após excluir
            }
        });
    }
}

// Carregar produtos quando o documento estiver pronto
$(document).ready(function() {
    carregarProdutos();
});
