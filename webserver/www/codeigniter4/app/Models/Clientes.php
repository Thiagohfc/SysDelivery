<?php

namespace App\Models;

use CodeIgniter\Model;

class Clientes extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'clientes';
    protected $primaryKey = 'clientes_id';
    protected $useAutoIncrement = true;
    protected $allowedFields = ['clientes_usuario_id', 'clientes_observacoes'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';


}