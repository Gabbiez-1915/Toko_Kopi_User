<?php

namespace App\Models;

use CodeIgniter\Model;

class UlasanModel extends Model
{
    protected $table            = 'ulasan';
    protected $primaryKey       = 'id_ulasan';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    
    protected $allowedFields    = [
        'id_reservasi', 
        'id_user', 
        'rating', 
        'komentar', 
        'tanggal_ulasan'
    ];

    // Karena kita menginputkan tanggal_ulasan secara manual, matikan auto timestamp
    protected $useTimestamps = false;
}
