<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Enderecos extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'rua' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'numero' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
            ],
            'complemento' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'cidade_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'usuario_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('cidade_id', 'cidades', 'cidades_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('usuario_id', 'usuarios', 'usuarios_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('enderecos');
    }

    public function down()
    {
        $this->forge->dropTable('enderecos');
    }
}