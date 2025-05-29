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

<div class="container">
    <h2 class="border-bottom border-2 border-primary mt-5 pt-3 mb-4"> <?= $title ?> </h2>

    <?php if (isset($msg))
        echo $msg; ?>

    <form action="<?= base_url('pedidos/search'); ?>" class="d-flex mb-3" role="search" method="post">
        <input class="form-control me-2" name="pesquisar" type="search" placeholder="Pesquisar" aria-label="Search">
        <button class="btn btn-outline-success" type="submit">
            <i class="bi bi-search"></i>
        </button>
    </form>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Data do Pedido</th>
                <th>Status</th>
                <th>Total</th>
                <th>
                    <a class="btn btn-success" href="<?= base_url('pedidos/new'); ?>">
                        Novo <i class="bi bi-plus-circle"></i>
                    </a>
                </th>
            </tr>
        </thead>
        <tbody class="table-group-divider">
            <?php foreach ($pedidos as $pedido): ?>
            <tr>
                <td><?= $pedido->pedidos_id ?></td>
                <td><?= $pedido->usuarios_nome . ' ' . $pedido->usuarios_sobrenome ?></td>
                <td><?= date('d/m/Y H:i', strtotime($pedido->data_pedido)) ?></td>
                <td><?= ucfirst($pedido->status) ?></td>
                <td>R$ <?= number_format($pedido->total_pedido, 2, ',', '.') ?></td>
                <td>
                    <a class="btn btn-primary" href="<?= base_url('pedidos/edit/' . $pedido->pedidos_id); ?>">
                        Editar <i class="bi bi-pencil-square"></i>
                    </a>
                    <a class="btn btn-danger" href="<?= base_url('pedidos/delete/' . $pedido->pedidos_id); ?>">
                        Excluir <i class="bi bi-x-circle"></i>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
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