<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ReservasiModel;
use App\Models\DetailPesananModel;
use App\Models\MenuModel;
use ci4shoppingcart\Libraries\Cart;

class DetailPesananController extends BaseController
{
    protected $cart;

    public function __construct()
    {
        $this->cart = new Cart(); // <-- Inisialisasi object library
    }

    public function keranjang()
    {
        $session = session();
        if (!$session->get('id_user')) {
            return redirect()->to('/')->with('error', 'Silakan login untuk mengakses keranjang.');
        }

        $data['isi_keranjang'] = $this->cart->contents();
        $data['total_belanja'] = $this->cart->total();

        // TAMBAHKAN BARIS INI: Kirim Client Key ke View Keranjang
        $data['clientKey'] = env('MIDTRANS_CLIENT_KEY');

        return view('v_keranjang', $data);
    }

    public function tambah()
    {
        $session = session();
        if (!$session->get('id_user')) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Silakan login terlebih dahulu untuk memesan.']);
        }

        $id_menu = $this->request->getPost('id_menu');
        $qty = $this->request->getPost('qty') ?? 1;

        $menuModel = new MenuModel();
        $menu = $menuModel->find($id_menu);

        if (!$menu) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Menu tidak ditemukan.']);
        }

        $this->cart->insert([
            'id' => $menu['id_menu'],
            'qty' => $qty,
            'price' => $menu['harga'],
            'name' => $menu['nama_menu'],
            'options' => [
                'foto_menu' => $menu['foto_menu'],
                'catatan_menu' => $this->request->getPost('catatan_menu')
            ]
        ]);

        // Hitung total keranjang terbaru untuk di-update di badge navbar
        $cartCount = $this->cart->total_items();

        return $this->response->setJSON([
            'status' => 'success',
            'message' => $menu['nama_menu'] . ' berhasil ditambahkan!',
            'cartCount' => $cartCount
        ]);
    }

    public function update_qty($rowid)
    {
        $qty = $this->request->getPost('qty');

        // Update di library ini harus membungkus rowid di dalam array
        $this->cart->update([
            'rowid' => $rowid,
            'qty' => $qty
        ]);

        return redirect()->to('keranjang')->with('success', 'Jumlah pesanan berhasil diperbarui.');
    }

    public function hapus($rowid)
    {
        $this->cart->remove($rowid);
        return redirect()->to('keranjang')->with('success', 'Menu berhasil dihapus dari keranjang.');
    }

    public function checkout()
    {
        $session = session();
        $id_user = $session->get('id_user');

        $reservasiModel = new \App\Models\ReservasiModel();
        $keranjangUser = $this->cart->contents();

        if (empty($keranjangUser)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Keranjang belanja Anda masih kosong.']);
        }

        $reservasiAktif = $reservasiModel->where('id_user', $id_user)
            ->where('status_reservasi', 'Dikonfirmasi')
            ->orderBy('id_reservasi', 'DESC')
            ->first();

        if (!$reservasiAktif) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Buat dan tunggu konfirmasi reservasi meja Anda sebelum checkout.']);
        }

        // Konfigurasi Midtrans
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION') === 'true';
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;
        //menghitung total
        $total_semua = $this->cart->total();
        $pajak = $total_semua * 0.10;
        $total_akhir = $total_semua + $pajak;

        $order_id = 'KDS-' . $reservasiAktif['id_reservasi'] . '-' . time();

        $transaction_details = [
            'order_id' => $order_id,
            'gross_amount' => (int) $total_akhir,
        ];
        //rincian barang
        $item_details = [];
        foreach ($keranjangUser as $item) {
            $item_details[] = [
                'id' => $item['id'],
                'price' => (int) $item['price'],
                'quantity' => $item['qty'],
                'name' => substr($item['name'], 0, 50),
            ];
        }

        $item_details[] = [
            'id' => 'PAJAK10',
            'price' => (int) $pajak,
            'quantity' => 1,
            'name' => 'Pajak Restoran 10%',
        ];
        //data di kirimkan ke midtrans
        $customer_details = [
            'first_name' => $session->get('username'),
            'email' => $session->get('email') ?? 'pelanggan@kopisenja.com',
        ];
        //di bungkus dengan param
        $params = [
            'transaction_details' => $transaction_details,
            'item_details' => $item_details,
            'customer_details' => $customer_details,
        ];
        //mengirimkan param ke midtrans
        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);

            // KEMBALIKAN RESPON JSON, BUKAN VIEW
            return $this->response->setJSON([
                'status' => 'success',
                'snapToken' => $snapToken
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal terhubung ke Midtrans: ' . $e->getMessage()]);
        }
    }

    // Fungsi baru yang dipanggil lewat JavaScript jika pembayaran sukses
    public function success()
    {
        $session = session();
        $id_user = $session->get('id_user');

        // MENANGKAP METODE PEMBAYARAN DARI URL KERANJANG
        $metode_pembayaran = $this->request->getGet('method') ?? 'online_payment';

        $reservasiModel = new \App\Models\ReservasiModel();
        $detailPesananModel = new \App\Models\DetailPesananModel();
        $keranjangUser = $this->cart->contents();

        if (empty($keranjangUser)) {
            return redirect()->to('reservasi/riwayat_pemesanan');
        }

        $reservasiAktif = $reservasiModel->where('id_user', $id_user)
            ->where('status_reservasi', 'Dikonfirmasi')
            ->orderBy('id_reservasi', 'DESC')
            ->first();

        if ($reservasiAktif) {
            $db = \Config\Database::connect();
            $db->transStart();

            // SIMPAN METODE PEMBAYARAN KE TABEL RESERVASI
            $reservasiModel->update($reservasiAktif['id_reservasi'], [
                'metode_pembayaran' => $metode_pembayaran
            ]);

            foreach ($keranjangUser as $item) {
                $dataDetail = [
                    'id_reservasi' => $reservasiAktif['id_reservasi'],
                    'id_menu' => $item['id'],
                    'jumlah_pesanan' => $item['qty'],
                    'subtotal' => $item['subtotal'],
                    'catatan_menu' => $item['options']['catatan_menu'] ?? null
                ];
                $detailPesananModel->insert($dataDetail);
            }

            $db->transComplete();

            if ($db->transStatus() !== false) {
                // KIRIM EMAIL NOTIFIKASI
                $to = $session->get('email');
                if ($to) {
                    $emailService = \Config\Services::email();
                    $emailService->setTo($to);
                    $emailService->setFrom('no-reply@kopisenja.com', 'Kopi Senja');
                    $emailService->setSubject('Konfirmasi Pembayaran dan Detail Pesanan - Kopi Senja');

                    $detailPesananHTML = '<table border="1" cellpadding="5" cellspacing="0" style="border-collapse: collapse; width: 100%; max-width: 600px;">';
                    $detailPesananHTML .= '<tr style="background-color: #f2f2f2;"><th>Menu</th><th>Qty</th><th>Subtotal</th></tr>';
                    foreach ($keranjangUser as $item) {
                        $detailPesananHTML .= '<tr>';
                        $detailPesananHTML .= '<td>' . $item['name'] . '</td>';
                        $detailPesananHTML .= '<td align="center">' . $item['qty'] . '</td>';
                        $detailPesananHTML .= '<td align="right">Rp ' . number_format($item['subtotal'], 0, ',', '.') . '</td>';
                        $detailPesananHTML .= '</tr>';
                    }
                    $detailPesananHTML .= '</table>';

                    $tanggal = date('d-m-Y', strtotime($reservasiAktif['tanggal_jadwal']));
                    $waktu = $reservasiAktif['waktu_jadwal'];
                    $no_meja = !empty($reservasiAktif['no_meja']) ? $reservasiAktif['no_meja'] : 'Belum ditentukan';
                    $username = $session->get('username') ?? 'Pelanggan';

                    $message = "
                    <h3>Pembayaran Berhasil!</h3>
                    <p>Halo {$username},</p>
                    <p>Terima kasih, pembayaran Anda telah kami terima.</p>
                    <p>Berikut adalah detail pesanan Anda:</p>
                    <ul>
                        <li><strong>Tanggal:</strong> {$tanggal}</li>
                        <li><strong>Jam:</strong> {$waktu}</li>
                        <li><strong>No. Meja:</strong> {$no_meja}</li>
                    </ul>
                    <p><strong>Daftar Menu yang Dipesan:</strong></p>
                    {$detailPesananHTML}
                    <br>
                    <p>Terima kasih telah memesan di Kopi Senja.</p>
                    ";

                    $emailService->setMessage($message);
                    $emailService->setMailType('html');
                    $emailService->send();
                }

                $this->cart->destroy();
                return redirect()->to('reservasi/riwayat_pemesanan')->with('payment_success', 'Pembayaran berhasil diverifikasi!');
            } else {
                return redirect()->to('keranjang')->with('error', 'Terjadi kesalahan sistem saat menyimpan pesanan.');
            }
        }

        return redirect()->to('keranjang')->with('error', 'Data reservasi tidak ditemukan.');
    }

    public function update_catatan($rowid)
    {
        $catatan_baru = $this->request->getPost('catatan_menu');

        // 1. Ambil seluruh isi keranjang
        $keranjang = $this->cart->contents();

        // 2. Cek apakah item dengan rowid tersebut ada
        if (isset($keranjang[$rowid])) {
            // 3. Ambil data 'options' yang lama (agar foto_menu tidak hilang)
            $options_lama = $keranjang[$rowid]['options'];

            // 4. Timpa catatan_menu lama dengan yang baru
            $options_lama['catatan_menu'] = $catatan_baru;

            // 5. Update library cart
            $this->cart->update([
                'rowid' => $rowid,
                'options' => $options_lama
            ]);
        }

        return redirect()->to('keranjang')->with('success', 'Catatan pesanan berhasil diperbarui.');
    }
}
