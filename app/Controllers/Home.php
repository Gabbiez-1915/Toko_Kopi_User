<?php

namespace App\Controllers;
use App\Models\MenuModel;
use App\Models\ReservasiModel;

class Home extends BaseController
{
    public function index()
    {
        $menuModel = new MenuModel();
        $reservasiModel = new ReservasiModel();
        $session = session();
        $id_user = $session->get('id_user');

        // LOGIKA PENGECEKAN STATUS KONFIRMASI DARI ADMIN UNTUK BERANDA
        if ($id_user) {
            $cek_konfirmasi = $reservasiModel->where('id_user', $id_user)
                ->where('status_reservasi', 'Dikonfirmasi')
                ->first();

            if ($cek_konfirmasi) {
                $session->set('sudah_reservasi', true);
            } else {
                $session->remove('sudah_reservasi');
            }
        } else {
            $session->remove('sudah_reservasi');
        }

        $data = [
            'menuData' => $menuModel->findAll()
        ];

        // --- MULAI PENAMBAHAN KODE TESTIMONI ---
        $db = \Config\Database::connect();
        $builder = $db->table('ulasan');
        $builder->select('ulasan.komentar, user.username');
        $builder->join('user', 'user.id_user = ulasan.id_user');
        $builder->where('ulasan.rating', 5);
        $builder->orderBy('ulasan.tanggal_ulasan', 'DESC');
        $builder->limit(10);

        $data['testimoni'] = $builder->get()->getResultArray();
        // --- SELESAI PENAMBAHAN KODE TESTIMONI ---

        return view('v_home', $data);
    }

    // ==========================================
    // FUNGSI UNTUK HALAMAN MENU PELANGGAN
    // ==========================================
    public function menu()
    {
        $menuModel = new MenuModel();
        $reservasiModel = new ReservasiModel();
        $session = session();
        $id_user = $session->get('id_user');

        // LOGIKA PENGECEKAN STATUS KONFIRMASI DARI ADMIN UNTUK HALAMAN MENU
        if ($id_user) {
            $cek_konfirmasi = $reservasiModel->where('id_user', $id_user)
                ->where('status_reservasi', 'Dikonfirmasi')
                ->first();

            if ($cek_konfirmasi) {
                $session->set('sudah_reservasi', true);
            } else {
                $session->remove('sudah_reservasi');
            }
        } else {
            $session->remove('sudah_reservasi');
        }

        $data = [
            'menuData' => $menuModel->findAll()
        ];

        return view('v_menu', $data);
    }
}
