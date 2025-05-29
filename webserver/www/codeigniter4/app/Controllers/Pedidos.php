<?php

namespace App\Controllers;
use App\Models\Pedidos as Pedido;
use App\Models\Clientes as Cliente;

class Pedidos extends BaseController
{
    private $pedidos;
    private $clientes;

    public function __construct()
    {
        $this->pedidos = new Pedido();
        $this->clientes = new Cliente();
        helper('functions');
    }

    public function index(): string
    {
        $data['title'] = 'Pedidos';
        $data['pedidos'] = $this->pedidos
            ->join('clientes', 'clientes.clientes_id = pedidos.clientes_id')
            ->join('usuarios', 'usuarios.usuarios_id = clientes.clientes_usuario_id')
            ->select('pedidos.*, clientes.*, usuarios.usuarios_nome, usuarios.usuarios_sobrenome')
            ->findAll();

        return view('pedidos/index', $data);
    }

    public function new(): string
    {
        $data['title'] = 'Novo Pedido';
        $data['op'] = 'create';
        $data['form'] = 'Cadastrar';
        $data['clientes'] = $this->clientes
            ->join('usuarios', 'usuarios.usuarios_id = clientes.clientes_usuario_id')
            ->select('clientes.*, usuarios.usuarios_nome, usuarios.usuarios_sobrenome, usuarios.usuarios_cpf')
            ->findAll();
        $data['pedidos'] = (object) [
            'clientes_id' => '',
            'data_pedido' => date('Y-m-d\TH:i'),
            'status' => 'aguardando',
            'observacoes' => '',
            'total_pedido' => '0.00',
            'pedidos_id' => ''
        ];
        return view('pedidos/form', $data);
    }

    public function create()
    {
        if (!$this->validate([
            'clientes_id' => 'required',
            'status' => 'required'
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->pedidos->save([
            'clientes_id' => $this->request->getPost('clientes_id'),
            'data_pedido' => date('Y-m-d H:i:s'),
            'status' => $this->request->getPost('status'),
            'observacoes' => $this->request->getPost('observacoes'),
            'total_pedido' => moedaDolar($this->request->getPost('total_pedido'))
        ]);

        return redirect()->to('/pedidos')->with('msg', msg('Pedido cadastrado com sucesso!', 'success'));
    }

    public function delete($id)
    {
        $this->pedidos->delete($id);
        return redirect()->to('/pedidos')->with('msg', msg('Pedido deletado com sucesso!', 'success'));
    }

    public function edit($id)
    {
        $data['title'] = 'Editar Pedido';
        $data['clientes'] = $this->clientes
            ->join('usuarios', 'usuarios.usuarios_id = clientes.clientes_usuario_id')
            ->select('clientes.*, usuarios.usuarios_nome, usuarios.usuarios_sobrenome, usuarios.usuarios_cpf')
            ->findAll();
        $data['pedidos'] = $this->pedidos->find($id);
        $data['op'] = 'update';
        $data['form'] = 'Alterar';
        return view('pedidos/form', $data);
    }

    public function update()
    {
        $id = $this->request->getPost('pedidos_id');

        $this->pedidos->update($id, [
            'clientes_id' => $this->request->getPost('clientes_id'),
            'status' => $this->request->getPost('status'),
            'observacoes' => $this->request->getPost('observacoes'),
            'total_pedido' => moedaDolar($this->request->getPost('total_pedido'))
        ]);

        return redirect()->to('/pedidos')->with('msg', msg('Pedido alterado com sucesso!', 'success'));
    }

    public function search()
    {
        $search = $this->request->getPost('pesquisar');
        $data['pedidos'] = $this->pedidos
            ->join('clientes', 'clientes.clientes_id = pedidos.clientes_id')
            ->like('clientes.cliente_nome', $search)
            ->orLike('pedidos.status', $search)
            ->findAll();
        
        $total = count($data['pedidos']);
        $data['msg'] = msg("Dados encontrados: {$total}", 'success');
        $data['title'] = 'Pedidos';
        return view('pedidos/index', $data);
    }
}