<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\ReservasiModel;

class ReservasiController extends BaseController
{
    public function index()
    {
        $session = session();
        $id_user = $session->get('id_user');

        $data = [];

        if ($id_user) {
            $reservasiModel = new ReservasiModel();

            // Cari pesanan terakhir dari user ini
            $pesanan_terakhir = $reservasiModel->where('id_user', $id_user)
                ->orderBy('id_reservasi', 'DESC')
                ->first();

            // =======================================================
            // FITUR PENYAPU OTOMATIS (LAZY UPDATE EXPIRATION)
            // =======================================================
            if ($pesanan_terakhir && in_array($pesanan_terakhir['status_reservasi'], ['Pending', 'Dikonfirmasi'])) {

                // Ubah tanggal jadwal dan tanggal hari ini menjadi format waktu (timestamp) untuk dibandingkan
                $tanggal_jadwal = strtotime($pesanan_terakhir['tanggal_jadwal']);
                $tanggal_sekarang = strtotime(date('Y-m-d'));

                // Jika hari ini sudah LEWAT dari tanggal jadwal kedatangan
                if ($tanggal_sekarang > $tanggal_jadwal) {

                    $status_baru = 'Selesai'; // Status diubah agar meja lepas dan form terbuka lagi

                    // Bebaskan meja jika sebelumnya sudah dikonfirmasi
                    if (!empty($pesanan_terakhir['no_meja'])) {
                        $db = \Config\Database::connect();
                        $db->table('meja')->where('no_meja', $pesanan_terakhir['no_meja'])->update(['status_meja' => 'Tersedia']);
                    }

                    // 1. Update ke database secara permanen
                    $reservasiModel->update($pesanan_terakhir['id_reservasi'], ['status_reservasi' => $status_baru]);

                    // 2. Update variabel sementara di memori agar halaman yang sedang dimuat langsung membuka form
                    $pesanan_terakhir['status_reservasi'] = $status_baru;
                }
            }

            $data['cek_reservasi'] = $pesanan_terakhir;
        } else {
            $data['cek_reservasi'] = null;
        }

        return view('v_reservasi', $data);
    }

    public function simpan_reservasi()
    {
        $reservasiModel = new ReservasiModel();
        $id_user_login = session()->get('id_user');

        if (!$id_user_login) {
            return redirect()->back()->with('error', 'Sesi login berakhir. Silakan login terlebih dahulu.');
        }

        $data = [
            'id_user' => $id_user_login,
            'jumlah_tamu' => $this->request->getPost('jumlah_tamu'),
            'whatsapp' => $this->request->getPost('whatsapp'),
            'kelas_meja' => $this->request->getPost('kelas_meja'),
            'tanggal_jadwal' => $this->request->getPost('tanggal_jadwal'),
            'waktu_jadwal' => $this->request->getPost('waktu_jadwal'),
            'catatan' => $this->request->getPost('catatan'),
            'status_reservasi' => 'Pending',
            'no_meja' => null,
            'id_karyawan' => null
        ];

        // 1. Simpan ke database
        $reservasiModel->insert($data);

        // 2. Beri pesan sukses
        session()->setFlashdata('reservasi_sukses', 'Reservasi berhasil dikirim! Silakan tunggu konfirmasi email.');

        // 3. Kembalikan ke halaman reservasi
        return redirect()->to('reservasi');
    }

    public function batal_reservasi($id_reservasi)
    {
        $reservasiModel = new ReservasiModel();
        $id_user_login = session()->get('id_user');

        if (!$id_user_login) {
            return redirect()->back()->with('error', 'Sesi berakhir. Silakan login.');
        }

        $reservasi = $reservasiModel->find($id_reservasi);

        if ($reservasi && $reservasi['id_user'] == $id_user_login) {

            $tanggal_jadwal = strtotime($reservasi['tanggal_jadwal']);
            $tanggal_sekarang = strtotime(date('Y-m-d'));
            $selisih_hari = ($tanggal_jadwal - $tanggal_sekarang) / (60 * 60 * 24); // <-- tambahkan titik koma di sini

            if ($selisih_hari >= 1) {
                // Lepaskan status meja di tabel meja jika sebelumnya sudah dikonfirmasi
                if (!empty($reservasi['no_meja'])) {
                    $db = \Config\Database::connect();
                    $db->table('meja')->where('no_meja', $reservasi['no_meja'])->update(['status_meja' => 'Tersedia']);
                }

                $reservasiModel->update($id_reservasi, [
                    'status_reservasi' => 'Dibatalkan',
                    'no_meja' => null, // lepas meja dari reservasi
                    'id_karyawan' => null, // lepas juga assignment karyawan kalau ada
                ]);
                session()->remove('sudah_reservasi');

                session()->setFlashdata('batal_sukses', 'Reservasi berhasil dibatalkan. Meja telah dilepas.');
            } else {
                session()->setFlashdata('batal_gagal', 'Pembatalan gagal. Sudah melewati batas maksimal H-1.');
            }
        }

        return redirect()->to('reservasi');
    }

    // ======================================================
    // FUNGSI UNTUK MENAMPILKAN HALAMAN RIWAYAT RESERVASI
    // ======================================================
    public function riwayat()
    {
        $session = session();
        $id_user = $session->get('id_user');

        if (!$id_user) {
            return redirect()->to('/')->with('error', 'Silakan login terlebih dahulu untuk melihat riwayat.');
        }

        $reservasiModel = new \App\Models\ReservasiModel();

        $data['riwayat_reservasi'] = $reservasiModel->where('id_user', $id_user)
            ->orderBy('id_reservasi', 'DESC')
            ->findAll();

        return view('v_riwayat_reservasi', $data);
    }

    // ======================================================
    // FUNGSI UNTUK MENAMPILKAN HALAMAN RIWAYAT PEMESANAN
    // ======================================================
    public function riwayat_pemesanan()
    {
        $session = session();
        $id_user = $session->get('id_user');

        if (!$id_user) {
            return redirect()->to('/')->with('error', 'Silakan login terlebih dahulu untuk melihat riwayat pemesanan.');
        }

        $db = \Config\Database::connect();

        $builder = $db->table('reservasi');
        $builder->select('reservasi.id_reservasi, reservasi.tanggal_jadwal, reservasi.metode_pembayaran, ulasan.rating, ulasan.komentar');
        $builder->join('detail_pesanan', 'detail_pesanan.id_reservasi = reservasi.id_reservasi');

        $builder->join('ulasan', 'ulasan.id_reservasi = reservasi.id_reservasi', 'left');

        $builder->where('reservasi.id_user', $id_user);
        $builder->groupBy('reservasi.id_reservasi');
        $builder->orderBy('reservasi.id_reservasi', 'DESC');

        $riwayat_pemesanan = $builder->get()->getResultArray();

        foreach ($riwayat_pemesanan as &$rp) {
            $items = $db->table('detail_pesanan')
                ->join('menu', 'menu.id_menu = detail_pesanan.id_menu')
                ->where('id_reservasi', $rp['id_reservasi'])
                ->get()->getResultArray();

            $rp['items'] = $items;

            $total_harga = 0;
            $nama_menu_arr = [];
            foreach ($items as $i) {
                $total_harga += $i['subtotal'];
                $kuantitas = $i['jumlah_pesanan'];

                $nama_menu_arr[] = $i['nama_menu'] . ' (' . $kuantitas . 'x)';
            }

            $rp['ringkasan_menu'] = implode('<br> ', $nama_menu_arr);
            $rp['total_pembayaran'] = $total_harga + ($total_harga * 0.10);
        }

        $data['riwayat_pemesanan'] = $riwayat_pemesanan;

        return view('v_riwayat_pemesanan', $data);
    }

    // FUNGSI UNTUK DETAIL STRUK AJAX
    public function get_struk($id_reservasi)
    {
        $session = session();
        if (!$session->get('id_user')) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized']);
        }

        $reservasiModel = new \App\Models\ReservasiModel();
        $reservasi = $reservasiModel->find($id_reservasi);

        if (!$reservasi || $reservasi['id_user'] != $session->get('id_user')) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Data tidak ditemukan']);
        }

        $db = \Config\Database::connect();
        $builder = $db->table('detail_pesanan');
        $builder->select('detail_pesanan.*, menu.nama_menu, menu.harga');
        $builder->join('menu', 'menu.id_menu = detail_pesanan.id_menu');
        $builder->where('id_reservasi', $id_reservasi);
        $detailPesanan = $builder->get()->getResultArray();

        if (empty($detailPesanan)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Belum ada pesanan untuk reservasi ini.']);
        }

        $subtotal_produk = 0;
        foreach ($detailPesanan as $item) {
            $subtotal_produk += $item['subtotal'];
        }
        $pajak = $subtotal_produk * 0.10;
        $total_akhir = $subtotal_produk + $pajak;

        return $this->response->setJSON([
            'status' => 'success',
            'reservasi' => $reservasi,
            'items' => $detailPesanan,
            'kalkulasi' => [
                'subtotal' => $subtotal_produk,
                'pajak' => $pajak,
                'total' => $total_akhir
            ]
        ]);
    }

    // ======================================================
    // FUNGSI API UNTUK CALENDAR (JSON)
    // ======================================================
    public function api_calendar()
    {
        $reservasiModel = new \App\Models\ReservasiModel();

        // Mengambil semua data reservasi
        $reservasi = $reservasiModel->findAll();

        $events = [];
        foreach ($reservasi as $res) {
            // Asumsi 1 slot reservasi memakan waktu 2 jam
            $start = $res['tanggal_jadwal'] . 'T' . $res['waktu_jadwal'];
            $end = $res['tanggal_jadwal'] . 'T' . date('H:i:s', strtotime('+2 hours', strtotime($res['waktu_jadwal'])));

            // Pewarnaan berdasarkan status ~reservasi
            $color = '#7f8c8d'; // Default (Selesai/Dibatalkan)
            if ($res['status_reservasi'] == 'Dikonfirmasi') {
                $color = '#27ae60'; // Hijau
            } elseif ($res['status_reservasi'] == 'Pending') {
                $color = '#f39c12'; // Kuning
            }

            $events[] = [
                'id' => $res['id_reservasi'],
                'title' => 'Reservasi: ' . $res['nama_pemesan'] . ' (Meja ' . ($res['no_meja'] ?? '-') . ')',
                'start' => $start,
                'end' => $end,
                'color' => $color,
                'extendedProps' => [
                    'status' => $res['status_reservasi'],
                    'jumlah_tamu' => $res['jumlah_tamu'],
                    'whatsapp' => $res['whatsapp']
                ]
            ];
        }

        return $this->response->setJSON($events);
    }
}
