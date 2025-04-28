<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {   
        $data['titulo'] = "Home";
        // $data['conteudo'] = "Seja bem vindo ao SYS Delivery!";
        return view('home/index',$data);
    }

}
