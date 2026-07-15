<?php

namespace App\Models;

use CodeIgniter\Model;

class MenuModel extends Model
{
    // 1. Tentukan nama tabel sesuai di Migration
    protected $table            = 'menu';
    
    // 2. Tentukan Primary Key nya
    protected $primaryKey       = 'id_menu';
    
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;

    // 3. Masukkan semua nama kolom (kecuali id_menu) ke dalam allowedFields
    protected $allowedFields    = [
        'nama_menu', 
        'kategori', 
        'harga', 
        'status_ketersediaan', 
        'is_bestseller', 
        'foto_menu'
    ];

    // Dates (Kosongkan atau set false jika di migration tidak pakai created_at/updated_at)
    protected $useTimestamps = false;

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // =========================================================================
    // TIPS TAMBAHAN: Anda bisa membuat fungsi custom di sini jika diperlukan
    // =========================================================================
    
    // Contoh: Fungsi untuk mengambil menu yang statusnya "Tersedia" saja
    public function getMenuAktif()
    {
        return $this->where('status_ketersediaan', 'Tersedia')->findAll();
    }
}
