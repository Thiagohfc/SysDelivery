<?php
helper('functions');
session();
// if(isset($_SESSION['login'])){
//     $login = $_SESSION['login'];
//     print_r($login);
//     if($login->usuarios_nivel == 2){

?>

<?= $this->extend('Templates_admin') ?>
<?= $this->section('content') ?>

<div class="container pt-4 pb-5 bg-light">
    <h2 class="border-bottom border-2 border-primary">
        <?= ucfirst($form) . ' Entrega' ?>
    </h2>

    <form action="<?= base_url('entregas/' . $op); ?>" method="post">

        <!-- SELECT de Pedido -->
        <div class="mb-3">
            <label for="pedido_id" class="form-label">Pedido</label>
            <select class="form-select" name="pedido_id" id="pedido_id" required>
                <option value="">Selecione um pedido</option>
                <?php foreach ($pedidos as $pedido): ?>
                <option value="<?= $pedido->pedidos_id ?>"
                    <?= isset($entrega->pedido_id) && $entrega->pedido_id == $pedido->pedidos_id ? 'selected' : '' ?>>
                    Pedido #<?= esc($pedido->pedidos_id) ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- SELECT de Funcionário -->
        <div class="mb-3">
            <label for="funcionario_id" class="form-label">Funcionário</label>
            <select class="form-select" name="funcionario_id" id="funcionario_id" required>
                <option value="">Selecione um funcionário</option>
                <?php foreach ($funcionarios as $func): ?>
                <option value="<?= $func->funcionarios_id ?>"
                    <?= isset($entrega->funcionario_id) && $entrega->funcionario_id == $func->funcionarios_id ? 'selected' : '' ?>>
                    <?= esc($func->usuarios_nome ?? 'Sem nome') ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- SELECT de Endereço -->
        <div class="mb-3">
            <label for="endereco_id" class="form-label">Endereço</label>
            <select class="form-select" name="endereco_id" id="endereco_id" required>
                <option value="">Selecione um endereço</option>
                <?php foreach ($enderecos as $end): ?>
                <option value="<?= $end->enderecos_id ?>"
                    <?= isset($entrega->endereco_id) && $entrega->endereco_id == $end->enderecos_id ? 'selected' : '' ?>>
                    <?= esc($end->enderecos_rua ?? 'Sem rua') ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Status da entrega -->
        <div class="mb-3">
            <label for="status_entrega" class="form-label">Status da Entrega</label>
            <select class="form-select" name="status_entrega" id="status_entrega" required>
                <option value="">Selecione o status</option>
                <option value="A CAMINHO"
                    <?= isset($entrega->status_entrega) && $entrega->status_entrega === 'A CAMINHO' ? 'selected' : '' ?>>
                    A CAMINHO</option>
                <option value="ENTREGUE"
                    <?= isset($entrega->status_entrega) && $entrega->status_entrega === 'ENTREGUE' ? 'selected' : '' ?>>
                    ENTREGUE</option>
                <option value="CANCELADO"
                    <?= isset($entrega->status_entrega) && $entrega->status_entrega === 'CANCELADO' ? 'selected' : '' ?>>
                    CANCELADO</option>
            </select>
        </div>

        <!-- ID oculto -->
        <input type="hidden" name="entregas_id"
            value="<?= isset($entrega->entregas_id) ? esc($entrega->entregas_id) : '' ?>">

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