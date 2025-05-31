<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Enderecos;
use App\Models\Entregas as ModelsEntregas;
use App\Models\Funcionarios;
use App\Models\Pedidos;
use CodeIgniter\HTTP\ResponseInterface;

class Entregas extends BaseController
{   
    private $entregas;
    private $pedido;
    private $endereco;
    private $funcionario;
    public function __construct(){
        $this->entregas = new ModelsEntregas();
        $this->pedido = new Pedidos();
        $this->endereco = new Enderecos();
        $this->funcionario = new Funcionarios();
    }
    public function index(): string
    {
        $data['title'] = 'Entregas';
        $data['entrega'] = $this->entregas->join('pedidos','pedido_id = pedidos_id')
                                        ->join('enderecos', 'endereco_id = enderecos_id')
                                        ->join('funcionarios','funcionario_id = funcionarios_id')
                                        ->findAll();
        return view('entregas/index', $data);
    }

    public function new(): string{
        $data['title'] = 'Nova Entrega';
        $data['op'] = 'create';
        $data['form'] = 'Cadastrar';
        $data['pedidos'] = $this->pedido->findAll();
        $data['enderecos'] = $this->endereco->findAll();
        $data['funcionarios'] = $this->funcionario->findAll();
        $data['entrega'] = (object)[
            'status_entrega' => ''
        ];
        return view('entregas/form', $data);
    }

    public function create(): string{
        if (!$this->validate([
            'status_entrega' => 'required|in_list[A CAMINHO,ENTREGUE,CANCELADO]',
        ])) {
            $data['entrega'] = (object)$_REQUEST;
            $data['title'] = 'Nova Entrega';
            $data['form'] = 'Cadastrar';
            $data['op'] = 'create';
            $data['pedidos'] = $this->pedido->findAll();
            $data['enderecos'] = $this->endereco->findAll();
            $data['funcionarios'] = $this->funcionario->findAll();
            return view('entregas/form', $data);
        }

        $this->entregas->save($_REQUEST);

        $data['msg'] = msg('Cadastrado com sucesso!', 'success');
        $data['entrega'] = $this->entregas->join('pedidos','pedido_id = pedidos_id')
                                        ->join('enderecos', 'endereco_id = enderecos_id')
                                        ->join('funcionarios','funcionario_id = funcionarios_id')
                                        ->findAll();
        $data['title'] = 'Entregas';

        return view('entregas/index', $data);
    }

    public function delete($id)
    {
        $this->entregas->where('entregas_id', (int)$id)->delete();
        $data['msg'] = msg('Deletado com sucesso!', 'success');
        $data['entrega'] = $this->entregas->join('pedidos','pedido_id = pedidos_id')
                                        ->join('enderecos', 'endereco_id = enderecos_id')
                                        ->join('funcionarios','funcionario_id = funcionarios_id')
                                        ->findAll();
        $data['title'] = 'Entregas';
        return view('entregas/index', $data);
    }

    public function edit($id)
    {
        $data['entrega'] = $this->entregas->find($id);
        $data['pedidos'] = $this->pedido->findAll();
        $data['enderecos'] = $this->endereco->findAll();
        $data['funcionarios'] = $this->funcionario->findAll();
        $data['title'] = 'Editar Entrega';
        $data['form'] = 'Editar';
        $data['op'] = 'update';
        return view('entregas/form', $data);
    }

    public function update()
    {
        $dataForm = [
            'pedido_id' => $_REQUEST['pedido_id'],
            'endereco_id' => $_REQUEST['endereco_id'],
            'funcionario_id' => $_REQUEST['funcionario_id'],
            'status_entrega' => $_REQUEST['status_entrega'],
        ];

        $this->entregas->update($_REQUEST['entrega_id'], $dataForm);

        $data['msg'] = msg('Entrega alterado com sucesso!', 'success');
        $data['entrega'] = $this->entregas->join('pedidos','pedido_id = pedidos_id')
                                        ->join('enderecos', 'endereco_id = enderecos_id')
                                        ->join('funcionarios','funcionario_id = funcionarios_id')
                                        ->findAll();
        $data['title'] = 'Entrega';

        return view('entregas/index', $data);
    }

    public function search()
    {
        $pesquisar = $_REQUEST['pesquisar'] ?? '';

        $data['entrega'] = $this->entregas->join('pedidos','pedido_id = pedidos_id')
            ->join('enderecos', 'endereco_id = enderecos_id')
            ->join('funcionarios','funcionario_id = funcionarios_id')
            ->like('status_entrega', $pesquisar)
            ->find();

        $total = count($data['entrega']);
        $data['msg'] = msg("Entregas encontradas: {$total}", 'success');
        $data['title'] = 'Entregas';

        return view('entregas/index', $data);
    }
}
