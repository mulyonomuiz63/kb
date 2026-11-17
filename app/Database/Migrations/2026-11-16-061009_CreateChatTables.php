<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateChatTables extends Migration
{
    public function up()
    {
        // 1. Table: chat_rooms
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'type' => [
                'type'       => 'ENUM',
                'constraint' => ['direct', 'group'],
                'default'    => 'direct',
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => true,
            ],
            'avatar' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'created_by_role' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'siswa',
            ],
            'created_by_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'last_message' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'last_message_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('type');
        $this->forge->addKey('last_message_at');
        $this->forge->createTable('chat_rooms', true);

        // 2. Table: chat_members
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'room_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'user_role' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'role_in_room' => [
                'type'       => 'ENUM',
                'constraint' => ['admin', 'member'],
                'default'    => 'member',
            ],
            'last_read_message_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'default'    => 0,
            ],
            'joined_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['room_id', 'user_role', 'user_id']);
        $this->forge->addKey(['user_role', 'user_id']);
        $this->forge->createTable('chat_members', true);

        // 3. Table: chat_messages
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'room_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'sender_role' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],
            'sender_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'reply_to_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'message' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'file_attachment' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'file_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'file_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'link_preview' => [
                'type' => 'TEXT', // JSON {title, description, image, url}
                'null' => true,
            ],
            'is_edited' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            'is_deleted' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('room_id');
        $this->forge->addKey('reply_to_id');
        $this->forge->addKey(['sender_role', 'sender_id']);
        $this->forge->addKey('created_at');
        $this->forge->createTable('chat_messages', true);
    }

    public function down()
    {
        $this->forge->dropTable('chat_messages', true);
        $this->forge->dropTable('chat_members', true);
        $this->forge->dropTable('chat_rooms', true);
    }
}
