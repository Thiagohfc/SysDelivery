<?php

namespace App\Controllers;
use App\Models\Vendas as Venda;
use App\Models\Pedidos as Pedido;

class Vendas extends BaseController
{
    private $vendas;
    private $pedidos;

    public function __construct()
    {
        $this->vendas = new Venda();
        $this->pedidos = new Pedido();
        helper('functions');
    }

    public function index(): string
    {
        $data['title'] = 'Vendas';
        $data['vendas'] = $this->vendas
            ->select('vendas.*, usuarios.usuarios_nome, usuarios.usuarios_sobrenome, pedidos.data_pedido')
            ->join('pedidos', 'vendas.pedidos_id = pedidos.pedidos_id')
            ->join('clientes', 'pedidos.clientes_id = clientes.clientes_id')
            ->join('usuarios', 'clientes.clientes_usuario_id = usuarios.usuarios_id')
            ->findAll();

        return view('vendas/index', $data);
    }

    public function new(): string
    {
        $data['title'] = 'Nova Venda';
        $data['op'] = 'create';
        $data['form'] = 'Cadastrar';
        $data['pedidos'] = $this->pedidos
            ->select('pedidos.*, usuarios.usuarios_nome, usuarios.usuarios_sobrenome')
            ->join('usuarios', 'usuarios.usuarios_id = pedidos.clientes_id')
            ->findAll();
        $data['venda'] = (object) [
            'pedidos_id' => '',
            'data_venda' => date('Y-m-d H:i:s'),
            'forma_pagamento' => '',
            'valor_total' => '0.00',
            'observacoes' => '',
            'vendas_id' => ''
        ];
        return view('vendas/form', $data);
    }

    public function create()
    {
        if (!$this->validate([
            'pedidos_id' => 'required',
            'forma_pagamento' => 'required'
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->vendas->save([
            'pedidos_id' => $this->request->getPost('pedidos_id'),
            'data_venda' => date('Y-m-d H:i:s'),
            'forma_pagamento' => $this->request->getPost('forma_pagamento'),
            'valor_total' => moedaDolar($this->request->getPost('valor_total')),
            'observacoes' => $this->request->getPost('observacoes')
        ]);

        return redirect()->to('/vendas')->with('msg', msg('Venda cadastrada com sucesso!', 'success'));
    }

    public function delete($id)
    {
        $this->vendas->delete($id);
        return redirect()->to('/vendas')->with('msg', msg('Venda deletada com sucesso!', 'success'));
    }

    public function edit($id)
    {
        $data['title'] = 'Editar Venda';
        $data['pedidos'] = $this->pedidos
            ->select('pedidos.*, usuarios.usuarios_nome, usuarios.usuarios_sobrenome')
            ->join('usuarios', 'usuarios.usuarios_id = pedidos.clientes_id')
            ->findAll();
        $data['venda'] = $this->vendas->find($id);
        $data['op'] = 'update';
        $data['form'] = 'Alterar';
        return view('vendas/form', $data);
    }

    public function update()
    {
        $id = $this->request->getPost('vendas_id');

        $this->vendas->update($id, [
            'pedidos_id' => $this->request->getPost('pedidos_id'),
            'forma_pagamento' => $this->request->getPost('forma_pagamento'),
            'valor_total' => moedaDolar($this->request->getPost('valor_total')),
            'observacoes' => $this->request->getPost('observacoes')
        ]);

        return redirect()->to('/vendas')->with('msg', msg('Venda alterada com sucesso!', 'success'));
    }

    public function search()
    {
        $search = $this->request->getPost('pesquisar');
        $data['vendas'] = $this->vendas
            ->select('vendas.*, usuarios.usuarios_nome, usuarios.usuarios_sobrenome, pedidos.data_pedido')
            ->join('pedidos', 'vendas.pedidos_id = pedidos.pedidos_id')
            ->join('clientes', 'pedidos.clientes_id = clientes.clientes_id')
            ->join('usuarios', 'clientes.clientes_usuario_id = usuarios.usuarios_id')
            ->like('forma_pagamento', $search)
            ->orLike('observacoes', $search)
            ->findAll();

        $total = count($data['vendas']);
        $data['msg'] = msg("Dados encontrados: {$total}", 'success');
        $data['title'] = 'Vendas';
        return view('vendas/index', $data);
    }
}