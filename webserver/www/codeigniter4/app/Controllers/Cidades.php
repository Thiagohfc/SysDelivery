<?php

namespace App\Controllers;
use App\Models\Cidades as Cidades_model;

class Cidades extends BaseController
{   
    // variavel que recebe a instancia do model
    private $cidades; 
    
    // metodo construtor
    public function __construct(){
        $this->cidades = new Cidades_model(); // instancia do model
        $data['title'] = 'Cidades';
        helper('functions'); // chama os metodos auxiliares
    }

    //
    public function index(): string
    {
        $data['title'] = 'Cidades';
        $data['cidades'] = $this->cidades->findAll();
        return view('cidades/index',$data);
    }

    public function new(): string
    {
        $data['title'] = 'Cidades';
        $data['op'] = 'create';
        $data['form'] = 'cadastrar';
        $data['cidades'] = (object) [
            'cidades_nome'=> '',
            'cidades_uf'=> '',
            'cidades_id'=> ''
        ];
        return view('cidades/form',$data);
    }

    
    public function create()
    {

        // Checks whether the submitted data passed the validation rules.
        if(!$this->validate([
            'cidades_nome' => 'required|max_length[255]|min_length[3]',
            'cidades_uf' => 'required|max_length[2]|min_length[2]'
        ])) {
            
            // The validation fails, so returns the form.
            $data['cidades'] = (object) [
                //'cidades_id' => $_REQUEST['cidades_id'],
                'cidades_nome' => $_REQUEST['cidades_nome'],
                'cidades_uf' => $_REQUEST['cidades_uf']
            ];
            
            $data['title'] = 'Cidades';
            $data['form'] = 'Cadastrar';
            $data['op'] = 'create';
            return view('cidades/form',$data);
        }


        $this->cidades->save([
            'cidades_nome' => $_REQUEST['cidades_nome'],
            'cidades_uf' => $_REQUEST['cidades_uf']

        ]);
        
        $data['msg'] = msg('Cadastrado com Sucesso!','success');
        $data['cidades'] = $this->cidades->findAll();
        $data['title'] = 'Cidades';
        return view('cidades/index',$data);

    }

    public function delete($id)
    {

        $this->cidades->where('cidades_id', (int) $id)->delete();
        $data['msg'] = msg('Deletado com Sucesso!','success');
        $data['cidades'] = $this->cidades->findAll();
        $data['title'] = 'Cidades';
        return view('cidades/index',$data);
    }

    public function edit($id)
    {
        $data['cidades'] = $this->cidades->find(['cidades_id' => (int) $id])[0];
        $data['title'] = 'Cidades';
        $data['form'] = 'Alterar';
        $data['op'] = 'update';
        return view('cidades/form',$data);
    }

    public function update()
    {
        $dataForm = [
            'cidades_id' => $_REQUEST['cidades_id'],
            'cidades_nome' => $_REQUEST['cidades_nome'],
            'cidades_uf' => $_REQUEST['cidades_uf']
        ];

        $this->cidades->update($_REQUEST['cidades_id'], $dataForm);
        $data['msg'] = msg('Alterado com Sucesso!','success');
        $data['cidades'] = $this->cidades->findAll();
        $data['title'] = 'Cidades';
        return view('cidades/index',$data);
    }

    public function search()
    {

        $data['cidades'] = $this->cidades->like('cidades_nome', $_REQUEST['pesquisar'])->find();
        $total = count($data['cidades']);
        $data['msg'] = msg("Dados Encontrados: {$total}",'success');
        $data['title'] = 'Cidades';
        return view('cidades/index',$data);

    }

}
