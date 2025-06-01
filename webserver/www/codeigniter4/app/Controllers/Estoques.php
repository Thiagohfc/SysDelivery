<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Estoques as ModelEstoques;
use App\Models\Produtos as ModelProdutos;

class Estoques extends BaseController
{
    private $estoque;
    private $produto;

    public function __construct()
    {
        $this->estoque = new ModelEstoques();
        $this->produto = new ModelProdutos();
        helper('functions');  
    }

    public function index()
    {
        $data['title'] = 'Estoque';
        $data['estoques'] = $this->estoque
            ->select('estoques.*, produtos.produtos_nome')
            ->join('produtos', 'produtos.produtos_id = estoques.produto_id')
            ->findAll();

        return view('estoques/index', $data);
    }

    public function new()
    {
        $data['title'] = 'Estoque';
        $data['op'] = 'create';
        $data['form'] = 'Cadastrar';
        $data['estoques'] = (object) [
            'produto_id' => '',
            'quantidade' => ''
        ];
        $data['produtos'] = $this->produto->findAll();
        return view('estoques/form', $data);
    }

    public function create()
    {
        if (
            !$this->validate([
                'produto_id' => 'required',
                'quantidade' => 'required|integer',
            ])
        ) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->estoque->save([
            'produto_id' => $this->request->getPost('produto_id'),
            'quantidade' => $this->request->getPost('quantidade'),
        ]);

        return redirect()->to('/estoques')->with('msg', msg('Cadastrado com Sucesso!', 'success'));
    }

    public function delete($id)
    {
        $this->estoque->delete((int) $id);
        return redirect()->to('/estoques')->with('msg', msg('Deletado com Sucesso!', 'success'));
    }

    public function edit($id)
    {
        $data['estoques'] = $this->estoque->find($id);
        $data['produtos'] = $this->produto->findAll();
        $data['title'] = 'Estoque';
        $data['form'] = 'Alterar';
        $data['op'] = 'update';
        return view('estoques/form', $data);
    }

    public function update()
    {
        if (
            !$this->validate([
                'produto_id' => 'required',
                'quantidade' => 'required|integer',
            ])
        ) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->estoque->update($this->request->getPost('estoques_id'), [
            'produto_id' => $this->request->getPost('produto_id'),
            'quantidade' => $this->request->getPost('quantidade')
        ]);

        return redirect()->to('/estoques')->with('msg', msg('Alterado com Sucesso!', 'success'));
    }

    public function search()
    {
        $search = $this->request->getPost('pesquisar');
        $data['estoques'] = $this->estoque
            ->select('estoques.*, produtos.produtos_nome')
            ->join('produtos', 'produtos.produtos_id = estoques.produto_id')
            ->like('produtos.produtos_nome', $search)
            ->findAll();

        $total = count($data['estoques']);

        return view('estoques/index', [
            'estoques' => $data['estoques'],
            'title' => 'Estoque',
            'msg' => msg("Dados encontrados: {$total}", 'success')
        ]);
    }
}