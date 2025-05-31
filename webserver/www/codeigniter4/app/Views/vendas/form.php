<?php
helper('functions');
session();
// if(isset($_SESSION['login'])){
//     $login = $_SESSION['login'];
//     print_r($login);
//     if($login->usuarios_nivel == 1){

?>

<?= $this->extend('Templates_admin') ?>
<?= $this->section('content') ?>

<div class="container pt-4 pb-5 bg-light">
    <h2 class="border-bottom border-2 border-primary">
        <?= ucfirst($form) . ' ' . $title ?>
    </h2>

    <form action="<?= base_url('vendas/' . $op); ?>" method="post">

        <!-- SELECT DE PEDIDOS -->
        <div class="mb-3">
            <label for="pedidos_id" class="form-label">Pedido</label>
            <select class="form-select" name="pedidos_id" id="pedidos_id" required>
                <option value="">Selecione um pedido</option>
                <?php foreach ($pedidos as $pedido): ?>
                <option value="<?= $pedido->pedidos_id ?>"
                    <?= isset($venda->pedidos_id) && $venda->pedidos_id == $pedido->pedidos_id ? 'selected' : '' ?>>
                    <?= esc('Pedido #' . $pedido->pedidos_id . ' - ' . $pedido->usuarios_nome . ' ' . $pedido->usuarios_sobrenome) ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Data da Venda -->
        <div class="mb-3">
            <label for="data_venda" class="form-label">Data da Venda</label>
            <input type="datetime-local" class="form-control" name="data_venda"
                value="<?= isset($venda->data_venda) ? date('Y-m-d\TH:i', strtotime($venda->data_venda . ' -3 hours')) : ''; ?>"
                id="data_venda" required>
        </div>

        <!-- Forma de Pagamento -->
        <div class="mb-3">
            <label for="forma_pagamento" class="form-label">Forma de Pagamento</label>
            <select class="form-select" name="forma_pagamento" id="forma_pagamento" required>
                <option value="">Selecione</option>
                <option value="dinheiro"
                    <?= isset($venda->forma_pagamento) && $venda->forma_pagamento == 'dinheiro' ? 'selected' : '' ?>>
                    Dinheiro</option>
                <option value="cartao_credito"
                    <?= isset($venda->forma_pagamento) && $venda->forma_pagamento == 'cartao_credito' ? 'selected' : '' ?>>
                    Cartão de Crédito</option>
                <option value="cartao_debito"
                    <?= isset($venda->forma_pagamento) && $venda->forma_pagamento == 'cartao_debito' ? 'selected' : '' ?>>
                    Cartão de Débito</option>
                <option value="pix"
                    <?= isset($venda->forma_pagamento) && $venda->forma_pagamento == 'pix' ? 'selected' : '' ?>>
                    Pix</option>
                <option value="boleto"
                    <?= isset($venda->forma_pagamento) && $venda->forma_pagamento == 'boleto' ? 'selected' : '' ?>>
                    Boleto</option>
            </select>
        </div>

        <!-- Valor Total -->
        <div class="mb-3">
            <label for="valor_total" class="form-label">Valor Total (R$)</label>
            <input type="number" step="0.01" class="form-control" name="valor_total"
                value="<?= $venda->valor_total ?? ''; ?>" id="valor_total" required>
        </div>

        <!-- Observações -->
        <div class="mb-3">
            <label for="observacoes" class="form-label">Observações</label>
            <textarea class="form-control" name="observacoes" id="observacoes"
                rows="4"><?= $venda->observacoes ?? ''; ?></textarea>
        </div>

        <!-- ID oculto -->
        <input type="hidden" name="vendas_id" value="<?= $venda->vendas_id ?? ''; ?>">

        <div class="mb-3">
            <button class="btn btn-success" type="submit">
                <?= ucfirst($form) ?> <i class="bi bi-floppy"></i>
            </button>
        </div>
    </form>
</div>

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