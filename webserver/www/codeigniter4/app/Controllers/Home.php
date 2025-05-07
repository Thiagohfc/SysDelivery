<?php

namespace App\Controllers;
use App\Models\Produtos as Produtos_model;
use App\Models\Categorias as Categorias_model;
use App\Models\Imgprodutos as Imgprodutos_model;

class Home extends BaseController
{
    private $produtos;
    private $categorias;
    private $imagens;

    public function __construct(){
        $this->produtos = new Produtos_model();
        $this->categorias = new Categorias_model();
        $this->imagens = new Imgprodutos_model();
        helper('functions');
    }


    public function index()
    {   
        $data['titulo'] = "Home";
        $categorias = $this->categorias->findAll();
        $data['all_produtos'] = []; 

        for($i=0; $i < count($categorias);$i++){
            $categoria = array(
                "categorias_id" => $categorias[$i]->categorias_id,
                "categorias_nome" => $categorias[$i]->categorias_nome,
                "produtos" => $this->produtos->join('imgprodutos', 'imgprodutos_produtos_id = produtos_id')->join('categorias', 'produtos_categorias_id = categorias_id')->where('categorias_id', (int) $categorias[$i]->categorias_id)->find()
            );
            $data['all_produtos'][$i] = $categoria;
        };
        return view('home/index',$data);
    }

}
