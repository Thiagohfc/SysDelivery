<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Estoques as ModelEstoques;
use App\Models\Produtos as ModelProdutos;
use CodeIgniter\HTTP\ResponseInterface;

class Estoques extends BaseController
{
    private $estoque;
    private $produto;
    public function __construct(){
        $this->estoque = new ModelEstoques();
        $this->produto = new ModelProdutos();

        $data ['title'] = 'Estoque';
    }

    public function index()
    {
        $data['title'] = 'Estoque';
        $data['estoques'] = $this->estoque->findAll();
        return view('estoques/index', $data);
    }
    public function new(){
        $data['title'] = 'Estoque';
        $data['op'] = 'create';
        $data['form'] = 'cadastrar';
        $data['estoques'] = (object) [
            'produto_id' => '',
            'quantidade' => ''
        ];
        return view('estoques/form', $data);
    }
    public function create(){
        if(!$this->validate([
            'quantidade' => 'required',
        ])) {
            
            $data['estoques'] = (object) [
                'quantidade' => $_REQUEST['quantidade'],
            ];
            
            $data['title'] = 'Estoque';
            $data['form'] = 'Cadastrar';
            $data['op'] = 'create';
            return view('estoques/form',$data);
        }


        $this->estoque->save([
            'quantidade' => $_REQUEST['quantidade']

        ]);
        
        $data['msg'] = msg('Cadastrado com Sucesso!','success');
        $data['estoques'] = $this->estoque->findAll();
        $data['title'] = 'Estoque';
        return view('estoques/index',$data);
    }
    public function delete($id)
    {

        $this->estoque->where('estoques_id', (int) $id)->delete();
        $data['msg'] = msg('Deletado com Sucesso!','success');
        $data['estoques'] = $this->estoque->findAll();
        $data['title'] = 'Estoque';
        return view('estoques/index',$data);
    }
    public function edit($id)
    {
        $data['estoques'] = $this->estoque->find(['estoques_id' => (int) $id])[0];
        $data['produtos'] = $this->produto->find(['produtos_id' => (int) $id])[0];
        $data['title'] = 'Estoque';
        $data['form'] = 'Alterar';
        $data['op'] = 'update';
        return view('estoques/form',$data);
    }

    public function update()
    {
        $dataForm = [
            'produtos_id' => $_REQUEST['produtos_id'],
            'quantidade' => $_REQUEST['quantidade']
        ];

        $this->estoque->update($_REQUEST['estoque_id'], $dataForm);
        $data['msg'] = msg('Alterado com Sucesso!','success');
        $data['estoques'] = $this->estoque->findAll();
        $date['produtos'] = $this->produto->findAll();
        $data['title'] = 'Estoque';
        return view('estoques/index',$data);
    }
    public function search()
    {
        $data['estoques'] = $this->estoque->like('estoques_id', $_REQUEST['pesquisar'])->find();
        $total = count($data['estoques']);
        $data['msg'] = msg("Dados Encontrados: {$total}",'success');
        $data['title'] = 'Estoque';
        return view('categorias/index',$data);
    }
}
