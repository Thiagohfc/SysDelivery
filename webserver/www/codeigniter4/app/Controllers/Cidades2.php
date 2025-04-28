<?php

namespace App\Controllers;



class Cidades extends BaseController
{   

    // Método index: Chama a página inicial referente ao form
    public function index()
    {   
        $data['titulo'] = "Cidades";
        $data['form'] = 'listar';
        $data['cidades'] = array(
            ['id'=>'1', 'cidade'=>'Ceres','uf'=>'Go'],
            ['id'=>'2', 'cidade'=>'Rialma','uf'=>'Go'],
            ['id'=>'3', 'cidade'=>'Rubiataba','uf'=>'Go'],
            ['id'=>'4', 'cidade'=>'São Paulo','uf'=>'SP'],
            ['id'=>'5', 'cidade'=>'Rio de Janeiro','uf'=>'RJ']

        );

        return view('cidades/index',$data);
    }

    // Método new: Chama o formulário de cadastro
    public function new(){
        $data['titulo'] = "Cidades";
        $data['form'] = 'cadastrar';
        $data['cidade'] = [
            'id' => '',
            'cidade' => '',
            'uf' => ''
        ];
        return view('cidades/form',$data);

    }

    // Método Casdastrar
    public function create(){
        $data['titulo'] = "Cidades";
        $data['form'] = 'listar';
        $cidade = [
            'id'=> 6,
            'cidade' => $_POST['cidades_nome'],
            'uf'   => $_POST['cidades_uf']
        ];

        $data['cidades'] = array(
            ['id'=>'1', 'cidade'=>'Ceres','uf'=>'Go'],
            ['id'=>'2', 'cidade'=>'Rialma','uf'=>'Go'],
            ['id'=>'3', 'cidade'=>'Rubiataba','uf'=>'Go'],
            ['id'=>'4', 'cidade'=>'São Paulo','uf'=>'SP'],
            ['id'=>'5', 'cidade'=>'Rio de Janeiro','uf'=>'RJ']

        );
        array_push($data['cidades'], $cidade);


        return view('cidades/index',$data);

    }

    // fazer a chamado do formulario edit
    public function edit($id){
        $data['titulo'] = "Cidades";
        $data['form'] = 'alterar';
        $data['cidades'] = array(
            ['id'=>'1', 'cidade'=>'Ceres','uf'=>'Go'],
            ['id'=>'2', 'cidade'=>'Rialma','uf'=>'Go'],
            ['id'=>'3', 'cidade'=>'Rubiataba','uf'=>'Go'],
            ['id'=>'4', 'cidade'=>'São Paulo','uf'=>'SP'],
            ['id'=>'5', 'cidade'=>'Rio de Janeiro','uf'=>'RJ']

        );
        $data['cidade'] = $data['cidades'][$id-1];

        return view('cidades/form',$data);

    }

}
