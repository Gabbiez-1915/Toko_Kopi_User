<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        // ==========================================
        // 1. Seeding Tabel USER (Pelanggan)
        // ==========================================
        $user = [
            ['username' => 'budi_santoso', 'email' => 'budi@gmail.com', 'password' => password_hash('user123', PASSWORD_DEFAULT), 'role' => 'Customer'],
            ['username' => 'siti_aminah', 'email' => 'siti@gmail.com', 'password' => password_hash('user123', PASSWORD_DEFAULT), 'role' => 'Customer'],
        ];
        $this->db->table('user')->insertBatch($user);
    }
}
