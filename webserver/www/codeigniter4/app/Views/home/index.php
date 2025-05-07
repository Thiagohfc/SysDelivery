<?php session() ?>
<?= $this->extend('Templates') ?>
<?= $this->section('content') ?>

<!--Abre Produtos-->
<div id="produtos" class="container">

<h2 class="border-bottom mt-3 border-2 border-primary">Produtos</h1>

<div class="col mt-3 mb-3">
    <form class="d-flex" role="search">
        <input class="form-control me-2" type="search"
            placeholder="Pesquisar" aria-label="Search">
        <button class="btn btn-outline-success" type="submit">
            <i class="bi bi-search"></i>
        </button>
    </form>
</div>

<?php 

for($i=0; $i < count($all_produtos); $i++){
?>  
    <h1><?= $all_produtos[$i]['categorias_nome']?></h1> 
    
    <div class="row">


        <?php 
        if(empty($all_produtos[$i]['produtos'])){
            echo '<div class="col mb-3 pb-4 mb-sm-0">
                    Ainda não há produtos cadastrados para esta categoria!
                </div>';
        }else{

        foreach($all_produtos[$i]['produtos'] as $produto){

        ?>
            <!-- card 1 -->
            <div class="col-sm-3 mb-3 pb-4 mb-sm-0">
                <div class="card">
                    <img src="<?= base_url('assets/'.$produto->imgprodutos_link) ?>" class="card-img-top">
                    <div class="card-body">
                        <h5 class="card-title"><?= $produto->produtos_nome; ?> </h5>
                        <h5 class="card-title"><b class="text-danger"> R$ <?= $produto->produtos_preco_venda; ?> </b></h5>
                        <p class="card-text "><?= $produto->produtos_descricao ?></p>
                        <p class="text-center"><a href="#" class="btn btn-primary">Comprar <i class="bi bi-basket2-fill"></i></a></p>
                    </div>
                </div>
            </div>

        <?php } }?>

    </div>

<?php } ?>

</div>

<!--Fecha Produtos-->

<?= $this->endSection('content') ?>
