<?php

namespace App\Models;

use CodeIgniter\Model;

class FuncionariosModel extends Model
{
    protected $table            = 'funcionarios';
    protected $primaryKey       = 'funcionarios_id';

    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;

    protected $allowedFields    = [
        'funcionarios_usuario_id',
        'funcionarios_cargo',
        'funcionarios_salario',
        'funcionarios_data_admissao',
        'funcionarios_observacoes'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
}
