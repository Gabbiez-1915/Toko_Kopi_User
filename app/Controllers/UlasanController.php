<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\UlasanModel;

class UlasanController extends BaseController
{
    public function simpan()
    {
        $session = session();
        $id_user = $session->get('id_user');

        // Pastikan user sudah login
        if (!$id_user) {
            return redirect()->to('/')->with('error', 'Silakan login terlebih dahulu untuk memberikan ulasan.');
        }

        $ulasanModel = new UlasanModel();

        // Tangkap data dari form modal ulasan
        $id_reservasi = $this->request->getPost('id_reservasi');
        $rating       = $this->request->getPost('rating');
        $komentar     = $this->request->getPost('komentar');

        // Kemas data untuk dimasukkan ke database
        $data = [
            'id_reservasi'   => $id_reservasi,
            'id_user'        => $id_user,
            'rating'         => $rating,
            'komentar'       => $komentar,
            'tanggal_ulasan' => date('Y-m-d H:i:s')
        ];

        // Eksekusi simpan ke database
        $ulasanModel->insert($data);

        // Kembalikan pelanggan ke halaman riwayat pemesanan beserta pesan sukses
        return redirect()->to('reservasi/riwayat_pemesanan')->with('success', 'Terima kasih! Ulasan dan rating Anda berhasil disimpan.');
    }
}
