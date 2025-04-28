<?php session() ?>
<?= $this->extend('Templates') ?>
<?= $this->section('content') ?>

<!--Abre Produtos-->
<div id="produtos" class="container">

<h1 class="col mt-3">Produtos</h1>

<div class="col mt-3 mb-3">
    <form class="d-flex" role="search">
        <input class="form-control me-2" type="search"
            placeholder="Pesquisar" aria-label="Search">
        <button class="btn btn-outline-success" type="submit">
            <i class="bi bi-search"></i>
        </button>
    </form>
</div>

<div class="row">

<?php 
$produtos = ['hamburguer1.png','hamburguer2.png','hamburguer3.png','hamburguer4.png','hamburguer5.png','pizza1.png','pizza2.png','pizza3.png','pizza4.png'];
for($i=0; $i < count($produtos);$i++){

?>
    <!-- card 1 -->
    <div class="col-sm-3 mb-3 pb-4 mb-sm-0">
        <div class="card">
            <img src="<?= base_url('assets/images/'.$produtos[$i]) ?>" class="card-img-top">
            <div class="card-body">
                <h5 class="card-title"><?= $produtos[$i] ?> </h5>
                <h5 class="card-title"><b class="text-danger"> R$ 50,00 </b></h5>
                <p class="card-text ">With supporting text below as a
                    natural lead-in to additional content.</p>
                <p class="text-center"><a href="#" class="btn btn-primary">Comprar <i class="bi bi-basket2-fill"></i></a></p>
            </div>
        </div>
    </div>



<?php } ?>

</div>

</div>

<!--Fecha Produtos-->

<?= $this->endSection('content') ?>
