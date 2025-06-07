<?php

namespace App\Controllers;
use App\Models\Usuarios as Usuarios_login;
use App\Models\Produtos as Produtos_login;
use App\Models\Imgprodutos as Imagem_produtos_login;

class Login extends BaseController
{
    private $data;
    private $usuarios;
    private $produtos;
    private $imagem_produtos;
    public $session;
    public function __construct(){
        helper('functions');
        $this->session = \Config\Services::session();
        $this->usuarios = new Usuarios_login();
        $this->produtos = new Produtos_login();
        $this->imagem_produtos = new Imagem_produtos_login();
        $this->data['title'] = 'Login';
        $this->data['msg'] = ''; 
    }
    public function index(): string
    { 
        return view('login',$this->data);
    }

    public function logar()
    {
        $login = $_REQUEST['login'];
        $senha = md5($_REQUEST['senha']);
        $this->data['usuarios'] = $this->usuarios->where('usuarios_cpf',$login)->
        orWhere('usuarios_email',$login)->where('usuarios_senha',$senha)->find();
        $this->data['produtos'] = $this->produtos->findAll();
        $this->data['imgprodutos'] = $this->imagem_produtos
        ->join('produtos', 'produtos.produtos_id = imgprodutos_produtos_id')
        ->select('imgprodutos.*, produtos.*')->find();
        if($this->data['usuarios'] == []){
            $this->data['msg'] = msg('O usuário ou a senha são invalidos!','danger');
            return view('login',$this->data);

        }else{
            if($this->data['usuarios'][0]->usuarios_email == $login  OR
               $this->data['usuarios'][0]->usuarios_cpf == $login AND
               $this->data['usuarios'][0]->usuarios_senha == $senha ){
                $infoSession = (object)[
                    'usuarios_id' => $this->data['usuarios'][0]->usuarios_id,
                    'usuarios_nivel' => $this->data['usuarios'][0]->usuarios_nivel,
                    'usuarios_nome' => $this->data['usuarios'][0]->usuarios_nome,
                    'usuarios_sobrenome' => $this->data['usuarios'][0]->usuarios_sobrenome,
                    'usuarios_cpf' => $this->data['usuarios'][0]->usuarios_cpf,
                    'usuarios_email' => $this->data['usuarios'][0]->usuarios_email,
                    'logged_in' => TRUE
                ];
                $this->session->set('login', $infoSession);

                if($this->data['usuarios'][0]->usuarios_nivel == 0){
                    
                    return view('user/index',$this->data);
                }
                elseif($this->data['usuarios'][0]->usuarios_nivel == 1){
                    return view('funcionario/index',$this->data);
                }
                elseif($this->data['usuarios'][0]->usuarios_nivel == 2){
                    return view('admin/index',$this->data);
                }else{
                    $this->data['msg'] = msg('Houve um problema com o seu acesso. Procure a Gerência de TI!','danger');
                    return view('login',$this->data);
                }
            }else{
                $this->data['msg'] = msg('O usuário ou a senha são invalidos!','danger');
                return view('login',$this->data);
            }
        }
    }

    public function logout()
    {
        // $this->session->remove('login');
        $this->data['msg'] = msg('Usuário desconectado','success');
        // return redirect()->route('home');
        // //return redirect()->to('home');
        session()->destroy(); // Destrói todos os dados da sessão
        //return redirect('/'); // Redireciona para a página inicial
        return redirect()->to('/home')->with('msg', msg('Usuário desconectado','success'));
    }



}
