<?php session() ?>
<?= $this->extend('Templates_admin') ?>
<?= $this->section('content') ?>

<div class="container">

    <h1><?= $titulo ?></h1>


    <table class="table table-hover">
        <tr>
            <th>ID</th>
            <th>Cidade</th>
            <th>Estado</th>
            <th colspan="2">
                <a href="<?= base_url('cidades/new') ?>">
                    <i class="bi bi-plus-circle"></i>
                    Novo
                </a>
            </th>
        </tr>

        <?php 
        echo base_url();
        foreach($cidades as $value){
            ?>

        <tr>
            <td><?= $value['id'] ?></td>
            <td><?= $value['cidade'] ?></td>
            <td><?= $value['uf'] ?></td>
            <td><a href="<?= base_url('cidades/edit/'.$value['id']) ?>">
                    <i class="bi bi-pencil-square"></i>
                    Alterar
                </a></td>
            <td><a href="#">
                    <i class="bi bi-x-circle"></i>
                    Excluir
                </a></td>

        </tr>

        <?php } ?>

    </table>

</div>

<?= $this->endSection('content') ?>
