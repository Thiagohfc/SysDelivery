<?php

namespace App\Controllers;

use App\Models\Enderecos as Endereco;
use App\Models\Cidades as Cidade;
use App\Models\Usuarios as Usuario;

class Enderecos extends BaseController
{
    private $enderecos;
    private $cidades;
    private $usuarios;

    public function __construct()
    {
        $this->enderecos = new Endereco();
        $this->cidades = new Cidade();
        $this->usuarios = new Usuario();
        helper('functions');
    }

    public function index(): string
    {
        $data['title'] = 'Endereços';
        $data['enderecos'] = $this->enderecos
            ->join('cidades', 'enderecos_cidade_id = cidades_id')
            ->join('usuarios', 'enderecos_usuario_id = usuarios_id')
            ->findAll();

        return view('enderecos/index', $data);
    }

    public function new(): string
    {
        $data['title'] = 'Novo Endereço';
        $data['op'] = 'create';
        $data['form'] = 'Cadastrar';
        $data['cidades'] = $this->cidades->findAll();
        $data['usuarios'] = $this->usuarios->findAll();
        $data['enderecos'] = (object)[
            'enderecos_rua' => '',
            'enderecos_numero' => '',
            'enderecos_complemento' => '',
            'enderecos_status' => '',
            'enderecos_cidade_id' => '',
            'enderecos_usuario_id' => ''
        ];

        return view('enderecos/form', $data);
    }

    public function create()
    {
        $validationRules = [
            'enderecos_rua' => 'required|max_length[255]',
            'enderecos_numero' => 'required|max_length[10]',
            'enderecos_status' => 'required|in_list[0,1]',
            'enderecos_cidade_id' => 'required|integer',
            'enderecos_usuario_id' => 'required|integer',
        ];

        if (!$this->validate($validationRules)) {
            $data['enderecos'] = (object) $this->request->getPost();
            $data['title'] = 'Novo Endereço';
            $data['form'] = 'Cadastrar';
            $data['op'] = 'create';
            $data['cidades'] = $this->cidades->findAll();
            $data['usuarios'] = $this->usuarios->findAll();
            return view('enderecos/form', $data);
        }

        $this->enderecos->save($this->request->getPost());

        $data['msg'] = msg('Cadastrado com sucesso!', 'success');
        $data['enderecos'] = $this->enderecos
            ->join('cidades', 'enderecos_cidade_id = cidades_id')
            ->join('usuarios', 'enderecos_usuario_id = usuarios_id')
            ->findAll();
        $data['title'] = 'Endereços';

        return view('enderecos/index', $data);
    }


    public function delete($id)
    {
        $this->enderecos->where('enderecos_id', (int)$id)->delete();
        $data['msg'] = msg('Deletado com sucesso!', 'success');
        $data['enderecos'] = $this->enderecos
            ->join('cidades', 'enderecos_cidade_id = cidades_id')
            ->join('usuarios', 'enderecos_usuario_id = usuarios_id')
            ->findAll();
        $data['title'] = 'Endereços';
        return view('enderecos/index', $data);
    }

    public function edit($id)
    {
        $data['enderecos'] = $this->enderecos->find($id);
        $data['cidades'] = $this->cidades->findAll();
        $data['usuarios'] = $this->usuarios->findAll();
        $data['title'] = 'Editar Endereço';
        $data['form'] = 'Editar';
        $data['op'] = 'update';
        return view('enderecos/form', $data);
    }

    public function update()
    {
        $dataForm = [
            'enderecos_rua' => $_REQUEST['enderecos_rua'],
            'enderecos_numero' => $_REQUEST['enderecos_numero'],
            'enderecos_complemento' => $_REQUEST['enderecos_complemento'],
            'enderecos_status' => $_REQUEST['enderecos_status'],
            'enderecos_cidade_id' => $_REQUEST['enderecos_cidade_id'],
            'enderecos_usuario_id' => $_REQUEST['enderecos_usuario_id'],
        ];

        $this->enderecos->update($_REQUEST['enderecos_id'], $dataForm);

        $data['msg'] = msg('Endereço alterado com sucesso!', 'success');
        $data['enderecos'] = $this->enderecos
            ->join('cidades', 'enderecos_cidade_id = cidades_id')
            ->join('usuarios', 'enderecos_usuario_id = usuarios_id')
            ->findAll();
        $data['title'] = 'Endereços';

        return view('enderecos/index', $data);
    }

    public function search()
    {
        $pesquisar = $_REQUEST['pesquisar'] ?? '';

        $data['enderecos'] = $this->enderecos
            ->join('cidades', 'enderecos_cidade_id = cidades_id')
            ->join('usuarios', 'enderecos_usuario_id = usuarios_id')
            ->like('enderecos_rua', $pesquisar)
            ->orLike('enderecos_numero', $pesquisar)
            ->orLike('enderecos_complemento', $pesquisar)
            ->find();

        $total = count($data['enderecos']);
        $data['msg'] = msg("Endereços encontrados: {$total}", 'success');
        $data['title'] = 'Endereços';

        return view('enderecos/index', $data);
    }
}