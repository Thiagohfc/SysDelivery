<?php

namespace App\Controllers;
use App\Models\Pedidos as Pedido;
use App\Models\Clientes as Cliente;
use App\Models\ItensPedido as ItensPedido;
use App\Models\Produtos as Produtos;
use App\Models\Enderecos as Enderecos;

class Pedidos extends BaseController
{
    private $pedidos;
    private $clientes;
    private $itensPedido;
    private $produtos;
    private $enderecos;

    public function __construct()
    {
        $this->pedidos = new Pedido();
        $this->clientes = new Cliente();
        $this->itensPedido = new ItensPedido();
        $this->produtos = new Produtos();
        $this->enderecos = new Enderecos();
        helper('functions');
    }

    public function index(): string
    {
        $data = $this->request->getPost();

        $session = session();
        $usuarioId = $session->get('login')->usuarios_id;
        $usuarioNivel = $session->get('login')->usuarios_nivel;

        $cliente = $this->clientes->select('clientes_id')->where('clientes_usuario_id', $usuarioId)->first();

        if (!$cliente) {
            return redirect()->back()->with('errors', ['Cliente não encontrado para o usuário logado.']);
        }

        if($usuarioNivel == 2){
            $data['title'] = 'Pedidos';
            $data['pedidos'] = $this->pedidos
                ->join('clientes', 'clientes.clientes_id = pedidos.clientes_id')
                ->join('usuarios', 'usuarios.usuarios_id = clientes.clientes_usuario_id')
                ->select('pedidos.*, clientes.*, usuarios.usuarios_nome, usuarios.usuarios_sobrenome')
                ->findAll();
                return view('pedidos/index', $data);
        }elseif($usuarioNivel == 0){
            $data['title'] = 'Meus Pedidos';
            $data['itensPedido'] = $this->itensPedido
                ->join('produtos', 'produtos.produtos_id = itens_pedido.produtos_id')
                ->join('pedidos', 'pedidos.pedidos_id = itens_pedido.pedidos_id')
                ->select('itens_pedido.*, pedidos.*, produtos.produtos_nome')
                ->where('pedidos.clientes_id', $cliente->clientes_id)->findAll();
                return view('pedidos/index', $data);
        }
    }

    public function new(): string
    {
        $session = session();
        $usuarioId = $session->get('login')->usuarios_id;

        $data['title'] = 'Novo Pedido';
        $data['op'] = 'create';
        $data['form'] = 'Cadastrar';

        $data['clientes'] = $this->clientes
            ->join('usuarios', 'usuarios.usuarios_id = clientes.clientes_usuario_id')
            ->select('clientes.*, usuarios.usuarios_nome, usuarios.usuarios_sobrenome, usuarios.usuarios_cpf')
            ->findAll();

        $data['produtos'] = $this->produtos
            ->join('categorias', 'categorias.categorias_id = produtos.produtos_categorias_id')
            ->select('produtos.*, categorias.categorias_nome')
            ->findAll();

        $data['enderecos'] = $this->enderecos
            ->join('cidades', 'cidades.cidades_id = enderecos.enderecos_cidade_id')
            ->join('usuarios', 'usuarios.usuarios_id = enderecos.enderecos_usuario_id')
            ->select('enderecos.*, cidades.cidades_nome, cidades.cidades_uf, usuarios.usuarios_nome, usuarios.usuarios_sobrenome')
            ->where('usuarios.usuarios_id', $usuarioId)
            ->findAll();

        $data['itens_pedido'] = $this->itensPedido
            ->join('produtos', 'produtos.produtos_id = itens_pedido.produtos_id')
            ->select('itens_pedido.*, produtos.produtos_nome, produtos.produtos_preco_venda')
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

    public function createPedido(){
        $data = $this->request->getPost();

        $session = session();
        $usuarioId = $session->get('login')->usuarios_id;

        $cliente = $this->clientes->select('clientes_id')->where('clientes_usuario_id', $usuarioId)->first();

        if (!$cliente) {
            return redirect()->back()->with('errors', ['Cliente não encontrado para o usuário logado.']);
        }

        if (!$this->validate([
            'status' => 'required',
            'produtos' => 'required',
            'quantidades' => 'required',
        ])) {
            $data['msg'] = msg('Erro ao validar os dados do pedido.', 'danger');
            return view('pedidos', $data);
        }

        $produtos = $data['produtos'];
        $quantidades = $data['quantidades'];
        $total = 0;

        $db = \Config\Database::connect();
        $db->transStart();

        $pedidoData = [
            'clientes_id' => $cliente->clientes_id,
            'data_pedido' => date('Y-m-d H:i:s'),
            'status' => $data['status'],
            'observacoes' => $data['observacoes'] ?? null,
            'total_pedido' => 0
        ];
        $this->pedidos->save($pedidoData);
        $pedidos_id = $this->pedidos->getInsertID();

        foreach ($produtos as $index => $produto_id) {
            $quantidade = $quantidades[$index] ?? 0;
            if ($quantidade > 0) {
                $produto = $this->produtos->find($produto_id);
                if ($produto) {
                    $preco_unitario = moedaDolar($produto->produtos_preco_venda);
                    $subtotal = $preco_unitario * $quantidade;
                    $total += $subtotal;

                    $this->itensPedido->save([
                        'pedidos_id' => $pedidos_id,
                        'produtos_id' => $produto_id,
                        'quantidade' => $quantidade,
                        'preco_unitario' => $preco_unitario
                    ]);
                }
            }
        }

        if ($total == 0) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('errors', ['Nenhum item válido foi adicionado.']);
        }

        $this->pedidos->update($pedidos_id, [
            'total_pedido' => $total
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('errors', ['Erro ao salvar pedido e itens.']);
        }

        return redirect()->to('/pedidos')->with('msg', msg('Pedido e itens cadastrados com sucesso!', 'success'));
    }

    public function show($id)
    {
        $data['title'] = 'Detalhes do Pedido';
        $data['pedidos'] = $this->pedidos
            ->join('clientes', 'clientes.clientes_id = pedidos.clientes_id')
            ->join('usuarios', 'usuarios.usuarios_id = clientes.clientes_usuario_id')
            ->select('pedidos.*, clientes.*, usuarios.usuarios_nome, usuarios.usuarios_sobrenome')
            ->find($id);
        $data['itens_pedido'] = $this->itensPedido
            ->join('produtos', 'produtos.produtos_id = itens_pedido.produtos_id')
            ->where('itens_pedido.pedidos_id', $id)
            ->select('itens_pedido.*, produtos.produtos_nome, produtos.produtos_preco')
            ->findAll();

        if (!$data['pedidos']) {
            return redirect()->to('/pedidos')->with('msg', msg('Pedido não encontrado!', 'danger'));
        }

        return view('pedidos/show', $data);
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

        if (!$this->validate([
            'clientes_id' => 'required',
            'status' => 'required',
            'observacoes' => 'permit_empty',
            'total_pedido' => 'required|decimal'
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

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