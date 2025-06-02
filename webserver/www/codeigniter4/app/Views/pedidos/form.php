<?php
helper('functions');
session();
if(isset($_SESSION['login'])){
    $login = $_SESSION['login'];
    print_r($login);
    if($login->usuarios_nivel == 2){

        ?>

        <?= $this->extend('Templates_admin') ?>
        <?= $this->section('content') ?>

        <div class="container pt-4 pb-5 bg-light">
            <h2 class="border-bottom border-2 border-primary">
                <?= ucfirst($form) . ' ' . $title ?>
            </h2>

            <form action="<?= base_url('pedidos/' . $op); ?>" method="post">

                <!-- SELECT DE CLIENTES -->
                <div class="mb-3">
                    <label for="clientes_id" class="form-label">Cliente</label>
                    <select class="form-select" name="clientes_id" id="clientes_id" required>
                        <option value="">Selecione um cliente</option>
                        <?php foreach ($clientes as $cliente): ?>
                        <option value="<?= $cliente->clientes_id ?>"
                            <?= isset($pedidos->clientes_id) && $pedidos->clientes_id == $cliente->clientes_id ? 'selected' : '' ?>>
                            <?= esc($cliente->usuarios_nome . ' ' . $cliente->usuarios_sobrenome) ?> -
                            <?= esc($cliente->usuarios_cpf) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Data do Pedido -->
                <div class="mb-3">
                    <label for="data_pedido" class="form-label">Data do Pedido</label>
                    <input type="datetime-local" class="form-control" name="data_pedido"
                        value="<?= isset($pedidos->data_pedido) ? date('Y-m-d\TH:i', strtotime($pedidos->data_pedido . ' -3 hours')) : ''; ?>"
                        id="data_pedido" required>
                </div>

                <!-- Status -->
                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" name="status" id="status" required>
                        <option value="">Selecione o status</option>
                        <option value="aguardando"
                            <?= isset($pedidos->status) && $pedidos->status == 'aguardando' ? 'selected' : '' ?>>Aguardando
                        </option>
                        <option value="em andamento"
                            <?= isset($pedidos->status) && $pedidos->status == 'em andamento' ? 'selected' : '' ?>>Em andamento
                        </option>
                        <option value="concluido"
                            <?= isset($pedidos->status) && $pedidos->status == 'concluido' ? 'selected' : '' ?>>Concluído
                        </option>
                        <option value="cancelado"
                            <?= isset($pedidos->status) && $pedidos->status == 'cancelado' ? 'selected' : '' ?>>Cancelado
                        </option>
                    </select>
                </div>

                <!-- Total -->
                <div class="mb-3">
                    <label for="total_pedido" class="form-label">Total do Pedido (R$)</label>
                    <input type="number" step="0.01" class="form-control" name="total_pedido"
                        value="<?= $pedidos->total_pedido ?? ''; ?>" id="total_pedido" required>
                </div>

                <!-- Observações -->
                <div class="mb-3">
                    <label for="observacoes" class="form-label">Observações</label>
                    <textarea class="form-control" name="observacoes" id="observacoes"
                        rows="4"><?= $pedidos->observacoes ?? ''; ?></textarea>
                </div>

                <!-- ID oculto -->
                <input type="hidden" name="pedidos_id" value="<?= $pedidos->pedidos_id ?? ''; ?>">

                <div class="mb-3">
                    <button class="btn btn-success" type="submit">
                        <?= ucfirst($form) ?> <i class="bi bi-floppy"></i>
                    </button>
                </div>
            </form>
        </div>

        <?= $this->endSection() ?>

        <?php
    } elseif($login->usuarios_nivel == 0) {

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
                            <div class="col-md-6">
                                <select name="produtos[]" id="produtos[]" class="form-select" required>
                                    <option value="">Selecione um produto</option>
                                    <?php foreach ($produtos as $produto): ?>
                                        <option value="<?= $produto->produtos_id ?>">
                                            <?= esc($produto->produtos_nome) ?> - R$ <?= number_format($produto->produtos_preco_venda, 2, ',', '.') ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="number" name="quantidades[]" id="quantidades[]" class="form-control" min="1" placeholder="Quantidade" required>
                            </div>
                            <div class="col-md-3">
                                <button type="button" class="btn btn-danger btn-remove">Remover</button>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-secondary mt-2" id="add-produto">+ Produto</button>
                </div>

                <!-- Observações -->
                <div class="mb-3">
                    <label for="observacoes" class="form-label">Observações (opcional)</label>
                    <textarea class="form-control" name="observacoes" id="observacoes" rows="3"></textarea>
                </div>

                <!-- Lista de Enderecos -->
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

            <script>
                document.getElementById('add-produto').addEventListener('click', () => {
                    const container = document.getElementById('produtos-container');
                    const item = container.querySelector('.produto-item').cloneNode(true);
                    item.querySelector('select').value = '';
                    item.querySelector('input').value = '';
                    container.appendChild(item);
                });

                document.addEventListener('click', function(e) {
                    if (e.target.classList.contains('btn-remove')) {
                        const items = document.querySelectorAll('.produto-item');
                        if (items.length > 1) {
                            e.target.closest('.produto-item').remove();
                        }
                    }
                });
            </script>
        </div>

        <?= $this->endSection() ?>

        <?php

    }else{

        $data['msg'] = msg("Sem permissão de acesso!","danger");
        echo view('login',$data);
    }
}else{

    $data['msg'] = msg("O usuário não está logado!","danger");
    echo view('login',$data);
}

?>