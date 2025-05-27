<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\Clientes as clientes_model;
use App\Models\Usuarios as usuarios_model;

class Clientes extends BaseController
{
    private $clientes;
    private $usuarios;

    public function __construct(){
        $this->clientes = new clientes_model();
        $this->usuarios = new usuarios_model();
        helper('functions');
    }

    public function index(): string
    {
        $data['title'] = 'Clientes';
        $data['clientes'] = $this->clientes->findAll();
        return view('clientes/index', $data);
    }

    public function new(): string
    {
        $data['title'] = 'Clientes';
        $data['op'] = 'create';
        $data['form'] = 'cadastrar';
        $data['clientes'] = (object) [
            'clientes_id' => '',
            'clientes_usuario_id' => '',
            'clientes_observacoes' => ''
        ];
        $data['usuarios'] = $this->usuarios->findAll();
        return view('clientes/form', $data);
    }

    public function create()
    {
        $this->clientes->save([
            'usuario_id' => $_REQUEST['usuario_id'],
            'clientes_observacoes' => $_REQUEST['clientes_observacoes']
        ]);

        $data['msg'] = msg('Cliente cadastrado com sucesso!', 'success');
        $data['clientes'] = $this->clientes->findAll();
        $data['title'] = 'Clientes';
        return view('clientes/index', $data);
    }

    public function delete($id)
    {
        $this->clientes->delete($id);
        $data['msg'] = msg('Deletado com sucesso!', 'success');
        $data['clientes'] = $this->clientes->findAll();
        $data['title'] = 'Clientes';
        return view('clientes/index', $data);
    }

    public function edit($id)
    {
        $data['cliente'] = $this->clientes->find($id);
        $data['usuarios'] = $this->usuarios->findAll();
        $data['title'] = 'Editar Cliente';
        $data['form'] = 'Alterar';
        $data['op'] = 'update';
        return view('clientes/form', $data);
    }

    public function update()
    {
        $this->clientes->update($_REQUEST['clientes_id'], [
            'usuario_id' => $_REQUEST['usuario_id'],
            'clientes_observacoes' => $_REQUEST['clientes_observacoes']
        ]);

        $data['msg'] = msg('Alterado com sucesso!', 'success');
        $data['clientes'] = $this->clientes->findAll();
        $data['title'] = 'Clientes';
        return view('clientes/index', $data);
    }
}
