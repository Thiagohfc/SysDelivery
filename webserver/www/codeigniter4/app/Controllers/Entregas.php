<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Enderecos;
use App\Models\Entregas as ModelsEntregas;
use App\Models\Funcionarios;
use App\Models\Pedidos;

helper('functions');

class Entregas extends BaseController
{
    private $entregas;
    private $pedido;
    private $endereco;
    private $funcionario;

    public function __construct()
    {
        $this->entregas = new ModelsEntregas();
        $this->pedido = new Pedidos();
        $this->endereco = new Enderecos();
        $this->funcionario = new Funcionarios();
    }

    private function getEntregasComJoins()
    {
        return $this->entregas
            ->select('entregas.*, pedidos.*, enderecos.enderecos_rua, usuarios.usuarios_nome AS funcionario_nome')
            ->join('pedidos', 'pedido_id = pedidos_id')
            ->join('enderecos', 'endereco_id = enderecos_id')
            ->join('funcionarios', 'funcionario_id = funcionarios_id')
            ->join('usuarios', 'usuarios.usuarios_id = funcionarios.funcionarios_usuario_id')
            ->findAll();
    }


    public function index(): string
    {
        $data['title'] = 'Entregas';
        $data['msg'] = session()->getFlashdata('msg') ?? '';
        $data['entregas'] = $this->getEntregasComJoins();
        return view('entregas/index', $data);
    }

    public function new(): string
    {
        $data['title'] = 'Nova Entrega';
        $data['op'] = 'create';
        $data['form'] = 'Cadastrar';
        $data['pedidos'] = $this->pedido->findAll();
        $data['enderecos'] = $this->endereco->findAll();
        $data['funcionarios'] = $this->funcionario
            ->select('funcionarios.funcionarios_id, usuarios.usuarios_nome')
            ->join('usuarios', 'usuarios.usuarios_id = funcionarios.funcionarios_usuario_id')
            ->findAll();

        $data['entregas'] = (object) [
            'pedido_id' => '',
            'endereco_id' => '',
            'funcionario_id' => '',
            'status_entrega' => ''
        ];
        return view('entregas/form', $data);
    }

    public function create()
    {
        $post = $this->request->getPost();

        if (
            !$this->validate([
                'pedido_id' => 'required|is_natural_no_zero',
                'endereco_id' => 'required|is_natural_no_zero',
                'funcionario_id' => 'required|is_natural_no_zero',
                'status_entrega' => 'required|in_list[A CAMINHO,ENTREGUE,CANCELADO]',
            ])
        ) {
            return redirect()->back()->withInput()->with('msg', msg('Erro na validação!', 'danger'));
        }

        $this->entregas->save($post);

        return redirect()->to('/entregas')->with('msg', msg('Entrega cadastrada com sucesso!', 'success'));
    }

    public function delete($id)
    {
        $this->entregas->delete((int) $id);
        return redirect()->to('/entregas')->with('msg', msg('Entrega deletada com sucesso!', 'success'));
    }

    public function edit($id)
    {
        $data['entrega'] = $this->entregas->find($id);
        $data['pedidos'] = $this->pedido->findAll();
        $data['enderecos'] = $this->endereco->findAll();
        $data['funcionarios'] = $this->funcionario
            ->select('funcionarios.funcionarios_id, usuarios.usuarios_nome')
            ->join('usuarios', 'usuarios.usuarios_id = funcionarios.funcionarios_usuario_id')
            ->findAll();

        $data['title'] = 'Editar Entrega';
        $data['form'] = 'Editar';
        $data['op'] = 'update';
        return view('entregas/form', $data);
    }

    public function update()
    {
        $post = $this->request->getPost();

        if (
            !$this->validate([
                'pedido_id' => 'required|is_natural_no_zero',
                'endereco_id' => 'required|is_natural_no_zero',
                'funcionario_id' => 'required|is_natural_no_zero',
                'status_entrega' => 'required|in_list[A CAMINHO,ENTREGUE,CANCELADO]',
            ])
        ) {
            return redirect()->back()->withInput()->with('msg', msg('Erro na validação!', 'danger'));
        }

        $this->entregas->update($post['entregas_id'], $post);

        return redirect()->to('/entregas')->with('msg', msg('Entrega atualizada com sucesso!', 'success'));
    }

    public function search()
    {
        $pesquisar = $this->request->getPost('pesquisar') ?? '';

        $data['entregas'] = $this->entregas
            ->join('pedidos', 'pedido_id = pedidos_id')
            ->join('enderecos', 'endereco_id = enderecos_id')
            ->join('funcionarios', 'funcionario_id = funcionarios_id')
            ->like('status_entrega', $pesquisar)
            ->find();

        $total = count($data['entregas']);
        $data['msg'] = msg("Entregas encontradas: {$total}", 'success');
        $data['title'] = 'Entregas';

        return view('entregas/index', $data);
    }
}