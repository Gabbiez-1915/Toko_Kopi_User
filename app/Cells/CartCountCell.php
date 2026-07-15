<?php

namespace App\Cells;

class CartCountCell
{
    public function render(): string
    {
        $session = session();
        
        // Jika belum login, kembalikan teks kosong (jangan kembalikan angka 0)
        if (!$session->get('id_user')) {
            return ''; 
        }

        $cartLib = new \ci4shoppingcart\Libraries\Cart();
        $cartCount = $cartLib->total_items();
        
        // Jika ada pesanan, kembalikan teks HTML berisi desain angka merah
        if ($cartCount > 0) {
            return '<span class="badge" style="position: absolute; top: 5px; right: -5px; background: #e74c3c; color: white; font-size: 10px; border-radius: 50%; padding: 2px 5px;">' . $cartCount . '</span>';
        }
        
        // Jika keranjang kosong, kembalikan teks kosong
        return '';
    }
}
