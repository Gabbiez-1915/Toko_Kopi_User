<?php

namespace App\Libraries;

/**
 * TransaksiLibrary
 * 
 * Library kustom untuk menangani logika perhitungan transaksi, pajak, dll.
 * Library ini memisahkan logika bisnis dari Controller agar kode lebih bersih (Clean Code).
 */
class TransaksiLibrary
{
    /**
     * Menghitung total belanja beserta pajak
     *
     * @param float|int $subtotal Total belanja sebelum pajak
     * @param float $persentase_pajak Persentase pajak dalam desimal (contoh: 0.10 untuk 10%)
     * @return array Mengembalikan array berisi subtotal, pajak, dan total_akhir
     */
    public function hitungTotalDanPajak($subtotal, $persentase_pajak = 0.10)
    {
        $pajak = $subtotal * $persentase_pajak;
        $total_akhir = $subtotal + $pajak;

        return [
            'subtotal' => $subtotal,
            'pajak' => $pajak,
            'total_akhir' => $total_akhir
        ];
    }
}
