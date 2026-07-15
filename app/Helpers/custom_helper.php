<?php

/**
 * Custom Helper
 * Berisi fungsi-fungsi bantuan (helper) khusus untuk aplikasi ini.
 */

if (!function_exists('format_rupiah')) {
    /**
     * Format angka menjadi format Rupiah
     * 
     * @param float|int $angka Angka yang akan diformat
     * @return string Format Rupiah (contoh: Rp 10.000)
     */
    function format_rupiah($angka)
    {
        return 'Rp ' . number_format($angka, 0, ',', '.');
    }
}

if (!function_exists('hitung_selisih_hari')) {
    /**
     * Menghitung selisih hari antara dua tanggal (dalam format Y-m-d)
     * 
     * @param string $tanggal_awal Tanggal yang akan dihitung (biasanya jadwal)
     * @param string $tanggal_akhir Tanggal pembanding (biasanya hari ini)
     * @return float Selisih dalam hari
     */
    function hitung_selisih_hari($tanggal_awal, $tanggal_akhir = null)
    {
        if ($tanggal_akhir === null) {
            $tanggal_akhir = date('Y-m-d');
        }
        
        $tgl_awal = strtotime($tanggal_awal);
        $tgl_akhir = strtotime($tanggal_akhir);
        
        return ($tgl_awal - $tgl_akhir) / (60 * 60 * 24);
    }
}
