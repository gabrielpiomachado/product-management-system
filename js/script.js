$(document).ready(function() {
    // Função para carregar os produtos via AJAX
    function carregarProdutos() {
        $.ajax({
            url: '/TrabalhoPelegrin/ajax_produtos.php',
            type: 'GET',
            success: function(response) {
                $('#produtos-lista').html(response);  // Atualiza a lista de produtos
            }
        });
    }

    // Função para deletar um produto
    window.deletarProduto = function(id) { // Mudança aqui para ser acessível globalmente
        if (confirm('Tem certeza que deseja excluir este produto?')) {
            $.ajax({
                url: '/TrabalhoPelegrin/ajax_produtos.php',
                type: 'POST',
                data: { action: 'delete', id: id },
                success: function(response) {
                    alert(response); // Mostra a mensagem de sucesso
                    carregarProdutos();  // Recarrega a lista de produtos após excluir
                },
                error: function() {
                    alert("Erro ao excluir o produto.");
                }
            });
        }
    };

    // Função para enviar produtos para a cesta
    $('#enviar-cesta').click(function() {
        var produtosSelecionados = [];
        $('input[name="produtos_selecionados[]"]:checked').each(function() {
            produtosSelecionados.push($(this).val());
        });

        if (produtosSelecionados.length > 0) {
            $.ajax({
                url: '/TrabalhoPelegrin/ajax_produtos.php',
                type: 'POST',
                data: { action: 'adicionar_cesta', produtos_selecionados: produtosSelecionados },
                success: function(response) {
                    alert("Produtos enviados para a cesta com sucesso!");
                },
                error: function() {
                    alert("Erro ao enviar produtos para a cesta.");
                }
            });
        } else {
            alert("Nenhum produto foi selecionado.");
        }
    });

    // Carregar produtos ao iniciar a página
    carregarProdutos();
});
