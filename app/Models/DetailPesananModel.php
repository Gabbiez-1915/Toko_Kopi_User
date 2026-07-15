<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailPesananModel extends Model
{
    protected $table            = 'detail_pesanan';
    protected $primaryKey       = 'id_detail_pesanan';
    protected $returnType       = 'array';
    
    // Menggunakan id_reservasi sesuai ERD Anda, dan ada catatan_menu
    protected $allowedFields    = ['id_reservasi', 'id_menu', 'jumlah_pesanan', 'subtotal', 'catatan_menu']; 
}
