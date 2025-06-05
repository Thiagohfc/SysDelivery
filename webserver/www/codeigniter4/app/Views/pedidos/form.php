<?php
helper('functions');
session();
// if(isset($_SESSION['login'])){
//     $login = $_SESSION['login'];
//     print_r($login);
//     if($login->usuarios_nivel == 2){

?>

<?= $this->extend('Templates_user') ?>
<?= $this->section('content') ?>

<div class="container pt-4 pb-5 bg-light">
    <h2 class="border-bottom border-2 border-primary">
        <?= ucfirst($form) . ' ' . $title ?>
    </h2>

    <form action="<?= base_url('pedidos/createPedido'); ?>" method="post">

        <input type="hidden" name="status" id="status" value="aguardando">

        <!-- Lista de Produtos -->
        <div class="mb-3">
            <label for="produtos" class="form-label">Produtos</label>
            <div id="produtos-container">

                <div class="row mb-2 produto-item">
                    <div class="col-md-4">
                        <select name="produtos[]" class="form-select produto-select" required>
                            <option value="">Selecione um produto</option>
                            <?php foreach ($produtos as $produto): ?>
                            <option value="<?= $produto->produtos_id ?>"
                                data-preco="<?= $produto->produtos_preco_venda ?>">
                                <?= esc($produto->produtos_nome) ?> - R$
                                <?= number_format($produto->produtos_preco_venda, 2, ',', '.') ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <div class="input-group">
                            <button type="button" class="btn btn-outline-secondary btn-minus">-</button>
                            <input type="number" name="quantidades[]" class="form-control text-center quantidade"
                                value="1" min="1" required>
                            <button type="button" class="btn btn-outline-secondary btn-plus">+</button>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <input type="text" class="form-control preco-final" value="R$ 0,00" readonly>
                    </div>

                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger btn-remove">Remover</button>
                    </div>
                </div>
            </div>

            <button type="button" class="btn btn-secondary mt-2" id="add-produto">+ Produto</button>
        </div>

        <!-- Total do Pedido -->
        <div class="mb-3">
            <label for="total_pedido" class="form-label">Total do Pedido (R$)</label>
            <input type="text" class="form-control" name="total_pedido" id="total_pedido" readonly required>
        </div>

        <!-- Observações -->
        <div class="mb-3">
            <label for="observacoes" class="form-label">Observações (opcional)</label>
            <textarea class="form-control" name="observacoes" id="observacoes" rows="3"></textarea>
        </div>

        <!-- Lista de Endereços -->
        <div class="mb-3">
            <label for="enderecos_id" class="form-label">Endereço de Entrega</label>
            <select class="form-select" name="enderecos_id" id="enderecos_id" required>
                <option value="">Selecione um endereço</option>
                <?php foreach ($enderecos as $endereco): ?>
                <option value="<?= $endereco->enderecos_id ?>">
                    <?= esc($endereco->enderecos_rua . ', ' . $endereco->enderecos_status) ?> -
                    <?= esc($endereco->cidades_nome . ' - ' . $endereco->cidades_uf) ?> -
                    <?= esc($endereco->usuarios_nome . ' ' . $endereco->usuarios_sobrenome) ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <button class="btn btn-success" type="submit">
                Finalizar Pedido <i class="bi bi-cart-check"></i>
            </button>
        </div>
    </form>
</div>

<!-- SCRIPT PARA FUNCIONALIDADES -->
<script>
function atualizarPrecoFinal(item) {
    const produtoSelect = item.querySelector('.produto-select');
    const preco = parseFloat(produtoSelect.selectedOptions[0]?.getAttribute('data-preco') || 0);
    const quantidade = parseInt(item.querySelector('.quantidade').value) || 1;
    const precoFinal = preco * quantidade;
    item.querySelector('.preco-final').value = `R$ ${precoFinal.toFixed(2).replace('.', ',')}`;
    atualizarTotalPedido();
}

function atualizarTotalPedido() {
    const items = document.querySelectorAll('.produto-item');
    let total = 0;
    items.forEach(item => {
        const produtoSelect = item.querySelector('.produto-select');
        const preco = parseFloat(produtoSelect.selectedOptions[0]?.getAttribute('data-preco') || 0);
        const quantidade = parseInt(item.querySelector('.quantidade').value) || 1;
        total += preco * quantidade;
    });
    document.getElementById('total_pedido').value = `R$ ${total.toFixed(2).replace('.', ',')}`;
}

document.getElementById('add-produto').addEventListener('click', () => {
    const container = document.getElementById('produtos-container');
    const item = container.querySelector('.produto-item').cloneNode(true);

    item.querySelector('.produto-select').value = '';
    item.querySelector('.quantidade').value = '1';
    item.querySelector('.preco-final').value = 'R$ 0,00';

    container.appendChild(item);
});

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('btn-remove')) {
        const items = document.querySelectorAll('.produto-item');
        if (items.length > 1) {
            e.target.closest('.produto-item').remove();
            atualizarTotalPedido();
        }
    }

    if (e.target.classList.contains('btn-plus') || e.target.classList.contains('btn-minus')) {
        const item = e.target.closest('.produto-item');
        const input = item.querySelector('.quantidade');
        let val = parseInt(input.value) || 1;

        if (e.target.classList.contains('btn-plus')) {
            input.value = val + 1;
        } else if (e.target.classList.contains('btn-minus') && val > 1) {
            input.value = val - 1;
        }

        atualizarPrecoFinal(item);
    }
});

document.addEventListener('input', function(e) {
    if (e.target.classList.contains('quantidade')) {
        const item = e.target.closest('.produto-item');
        atualizarPrecoFinal(item);
    }
});

document.addEventListener('change', function(e) {
    if (e.target.classList.contains('produto-select')) {
        const item = e.target.closest('.produto-item');
        atualizarPrecoFinal(item);
    }
});

window.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.produto-item').forEach(atualizarPrecoFinal);
});
</script>

<?= $this->endSection() ?>

<?php
//     }else{

//         $data['msg'] = msg("Sem permissão de acesso!","danger");
//         echo view('login',$data);
//     }
// }else{

//     $data['msg'] = msg("O usuário não está logado!","danger");
//     echo view('login',$data);
// }

?>