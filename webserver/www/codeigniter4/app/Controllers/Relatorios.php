<?php

namespace App\Controllers;

use App\Models\Usuarios as Usuarios_model;
use App\Models\Produtos as Produtos_model;
use App\Models\Enderecos as Enderecos_model;
use App\Models\Cidades as Cidades_model;
use App\Models\Categorias as Categorias_model;
use App\Libraries\RelatorioPDF;

class Relatorios extends BaseController
{
    protected $usuarios;
    protected $produtos;
    protected $enderecos;
    protected $cidades;
    protected $categorias;

    public function __construct()
    {
        $this->usuarios = new Usuarios_model();
        $this->produtos = new Produtos_model();
        $this->enderecos = new Enderecos_model();
        $this->cidades = new Cidades_model();
        $this->categorias = new Categorias_model();
    }

    public function index(int $id)
    {
        require_once(APPPATH . 'Libraries/RelatorioPDF.php');

        $pdf = new \RelatorioPDF();
        $pdf->AliasNbPages();
        $pdf->SetMargins(25, 25, 20);
        $pdf->SetAutoPageBreak(true, 20);
        $pdf->AddPage('P', 'A4');

        $pdf->SetFont('Arial', 'B', 15);

        if ($id == 1) {
            $dados = $this->usuarios->findAll();
            $titulo = 'Relatório de Usuários';
            $pdf->Cell(0, 10, utf8_decode($titulo), 0, 1, 'C');
            $pdf->Ln(3);

            $pdf->SetFillColor(220, 220, 220);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetDrawColor(0, 0, 0);

            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(20, 10, 'ID', 1, 0, 'L', true);
            $pdf->Cell(70, 10, 'Nome', 1, 0, 'L', true);
            $pdf->Cell(70, 10, 'CPF', 1, 0, 'L', true);
            $pdf->Ln();

            $pdf->SetFont('Arial', '', 12);
            foreach ($dados as $item) {
                $pdf->Cell(20, 8, $item->usuarios_id, 1);
                $pdf->Cell(70, 8, utf8_decode($item->usuarios_nome . ' ' . $item->usuarios_sobrenome), 1);
                $pdf->Cell(70, 8, utf8_decode($item->usuarios_cpf), 1);
                $pdf->Ln();
            }

            $pdf->Output('I', 'RelatorioUsuarios.pdf');
        } elseif ($id == 2) {
            $pdf->SetMargins(15, 25, 20);
            $dados = $this->produtos->findAll();
            $titulo = 'Relatório de Produtos';
            $pdf->Cell(0, 10, utf8_decode($titulo), 0, 1, 'C');
            $pdf->Ln(3);

            $pdf->SetFillColor(220, 220, 220);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetDrawColor(0, 0, 0);

            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(15, 10, 'ID', 1, 0, 'L', true);
            $pdf->Cell(40, 10, 'Nome', 1, 0, 'L', true);
            $pdf->Cell(50, 10, utf8_decode('Descrição'), 1, 0, 'L', true);
            $pdf->Cell(25, 10, 'Custo (R$)', 1, 0, 'L', true);
            $pdf->Cell(25, 10, 'Venda (R$)', 1, 0, 'L', true);
            $pdf->Cell(25, 10, 'Categoria ID', 1, 0, 'L', true);
            $pdf->Ln();

            $pdf->SetFont('Arial', '', 10);
            foreach ($dados as $item) {
                $pdf->Cell(15, 8, $item->produtos_id, 1);
                $pdf->Cell(40, 8, utf8_decode($item->produtos_nome), 1);
                $pdf->Cell(50, 8, utf8_decode($item->produtos_descricao), 1);
                $pdf->Cell(25, 8, 'R$ ' . number_format($item->produtos_preco_custo, 2, ',', '.'), 1);
                $pdf->Cell(25, 8, 'R$ ' . number_format($item->produtos_preco_venda, 2, ',', '.'), 1);
                $pdf->Cell(25, 8, $item->produtos_categorias_id, 1);
                $pdf->Ln();
            }

            $pdf->Output('I', 'RelatorioProdutos.pdf');
        } elseif ($id == 3) {
            $pdf->SetMargins(2, 25, 20);
            $dados = $this->enderecos->findAll();
            $titulo = 'Relatório de Endereços';
            $pdf->Cell(0, 10, utf8_decode($titulo), 0, 1, 'C');
            $pdf->Ln(3);

            $pdf->SetFillColor(220, 220, 220);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetDrawColor(0, 0, 0);

            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(15, 10, 'ID', 1, 0, 'L', true);
            $pdf->Cell(40, 10, 'Rua', 1, 0, 'L', true);
            $pdf->Cell(25, 10, utf8_decode('Número'), 1, 0, 'L', true);
            $pdf->Cell(50, 10, 'Complemento', 1, 0, 'L', true);
            $pdf->Cell(25, 10, 'Status', 1, 0, 'L', true);
            $pdf->Cell(25, 10, 'Cidade ID', 1, 0, 'L', true);
            $pdf->Cell(25, 10, utf8_decode('Usuário ID'), 1, 0, 'L', true);
            $pdf->Ln();

            $pdf->SetFont('Arial', '', 10);
            foreach ($dados as $item) {
                $status = ($item->enderecos_status == '1') ? 'Ativo' : 'Inativo';
                $pdf->Cell(15, 8, $item->enderecos_id, 1);
                $pdf->Cell(40, 8, utf8_decode($item->enderecos_rua), 1);
                $pdf->Cell(25, 8, utf8_decode($item->enderecos_numero), 1);
                $pdf->Cell(50, 8, utf8_decode($item->enderecos_complemento), 1);
                $pdf->Cell(25, 8, utf8_decode($status), 1);
                $pdf->Cell(25, 8, utf8_decode($item->enderecos_cidade_id), 1);
                $pdf->
                Cell(25, 8, utf8_decode($item->enderecos_usuario_id), 1);
                $pdf->Ln();
            }
            $pdf->Output('I', 'RelatorioEnderecos.pdf');
        } elseif ($id == 4) {
            $pdf->SetMargins(65, 25, 10);
            $dados = $this->cidades->findAll();
            $titulo = 'Relatório de Cidades';
            $pdf->Cell(0, 10, utf8_decode($titulo), 0, 1, 'C');
            $pdf->Ln(3);

            $pdf->SetFillColor(220, 220, 220);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetDrawColor(0, 0, 0);

            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(15, 10, 'ID', 1, 0, 'L', true);
            $pdf->Cell(40, 10, 'Nome', 1, 0, 'L', true);
            $pdf->Cell(25, 10, 'Estado', 1, 0, 'L', true);
            $pdf->Ln();

            $pdf->SetFont('Arial', '', 10);
            foreach ($dados as $item) {
                $pdf->Cell(15, 8, $item->cidades_id, 1);
                $pdf->Cell(40, 8, utf8_decode($item->cidades_nome), 1);
                $pdf->Cell(25, 8, utf8_decode($item->cidades_uf), 1);
                $pdf->Ln();
            }

            $pdf->Output('I', 'RelatorioCidades.pdf');
        } elseif ($id == 5) {
            $pdf->SetMargins(60, 25, 20);
            // Relatório de Categorias
            $dados = $this->categorias->findAll();
            $titulo = 'Relatório de Categorias';
            $pdf->Cell(0, 10, utf8_decode($titulo), 0, 1, 'C');
            $pdf->Ln(3);

            // Cabeçalho da tabela
            $pdf->SetFillColor(220, 220, 220);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetDrawColor(0, 0, 0);

            // Definindo a fonte e o tamanho
            $pdf->SetFont('Arial', 'B', 12);

            // Definindo as colunas
            $pdf->Cell(20, 10, 'ID', 1,
                0, 'L', true);
            $pdf->Cell(70, 10, 'Nome', 1,
                0, 'L', true);
            $pdf->Ln();
            // Definindo a fonte e o tamanho
            $pdf->SetFont('Arial', '', 12);
            // Adicionando os dados
            foreach ($dados as $item) {
                $pdf->Cell(20, 8, $item->categorias_id, 1);
                $pdf->Cell(70, 8, utf8_decode($item->categorias_nome), 1);
                $pdf->Ln();
            }
            // Saída do PDF
            $pdf->Output('I', 'RelatorioCategorias.pdf');
        }
        else {
            echo "Relatório não encontrado.";
        }
        exit;
    }
}
