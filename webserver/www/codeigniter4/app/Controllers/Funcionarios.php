<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\Funcionarios as funcionarios_model;
use App\Models\Usuarios as usuarios_model;

class Funcionarios extends BaseController
{
    private $funcionarios;
    private $usuarios;

    public function __construct()
    {
        $this->funcionarios = new funcionarios_model();
        $this->usuarios = new usuarios_model();
        helper('functions');
    }

    public function index(): string
    {
        $data['title'] = 'Funcionários';
        $data['funcionarios'] = $this->funcionarios->findAll();
        return view('funcionarios/index', $data);
    }

    public function new(): string
    {
        $data['title'] = 'Funcionários';
        $data['op'] = 'create';
        $data['form'] = 'cadastrar';
        $data['funcionarios'] = (object) [
            'funcionarios_id' => '',
            'funcionarios_usuario_id' => '',
            'funcionarios_cargo' => '',
            'funcionarios_salario' => '',
            'funcionarios_data_admissao' => '',
            'funcionarios_observacoes' => ''
        ];
        $data['usuarios'] = $this->usuarios->findAll();
        return view('funcionarios/form', $data);
    }

    public function create()
    {
        $this->funcionarios->save([
            'funcionarios_usuario_id' => $_REQUEST['funcionarios_usuario_id'],
            'funcionarios_cargo' => $_REQUEST['funcionarios_cargo'],
            'funcionarios_salario' => moedaDolar($_REQUEST['funcionarios_salario']),
            'funcionarios_data_admissao' => moedaDolar($_REQUEST['funcionarios_data_admissao']),
            'funcionarios_observacoes' => $_REQUEST['funcionarios_observacoes']
        ]);

        $data['msg'] = msg('Funcionário cadastrado com sucesso!', 'success');
        $data['funcionarios'] = $this->funcionarios->findAll();
        $data['title'] = 'Funcionários';
        return view('funcionarios/index', $data);
    }

    public function delete($id)
    {
        $this->funcionarios->delete($id);
        $data['msg'] = msg('Funcionário deletado com sucesso!', 'success');
        $data['funcionarios'] = $this->funcionarios->findAll();
        $data['title'] = 'Funcionários';
        return view('funcionarios/index', $data);
    }

    public function edit($id)
    {
        $data['funcionario'] = $this->funcionarios->find($id);
        $data['usuarios'] = $this->usuarios->findAll();
        $data['title'] = 'Editar Funcionário';
        $data['op'] = 'edit';
        $data['form'] = 'editar';
        return view('funcionarios/form', $data);
    }
    public function update()
    {
        $this->funcionarios->update($_REQUEST['funcionarios_id'], [
            'funcionarios_usuario_id' => $_REQUEST['funcionarios_usuario_id'],
            'funcionarios_cargo' => $_REQUEST['funcionarios_cargo'],
            'funcionarios_salario' => moedaDolar($_REQUEST['funcionarios_salario']),
            'funcionarios_data_admissao' => moedaDolar($_REQUEST['funcionarios_data_admissao']),
            'funcionarios_observacoes' => $_REQUEST['funcionarios_observacoes']
        ]);

        $data['msg'] = msg('Funcionário atualizado com sucesso!', 'success');
        $data['funcionarios'] = $this->funcionarios->findAll();
        $data['title'] = 'Funcionários';
        return view('funcionarios/index', $data);
    }
}
