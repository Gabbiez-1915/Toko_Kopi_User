<?php

namespace App\Controllers;

use App\Models\UserModel;

class AuthController extends BaseController
{

    public function loginAction()
    {
        // =========================================================================
        // LOGIKA LOGIN
        // =========================================================================

        $model = new UserModel();
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        // Cari user berdasarkan username
        $user = $model->where('username', $username)->first();

        // 1. Cek apakah username ditemukan di database
        if (!$user) {
            return redirect()->back()->with('error', 'Username tidak ditemukan!');
        }

        // 2. Jika username ada, cek apakah password cocok
        if (!password_verify($password, $user['password'])) {
            return redirect()->back()->with('error', 'Kata sandi yang Anda masukkan salah!');
        }

        // 3. Jika username ada DAN password benar, eksekusi login & simpan Session
        session()->set([
            'id_user'    => $user['id_user'], 
            'username'   => $user['username'], 
            'email'      => $user['email'], // Menyimpan email ke session agar otomatis terisi di reservasi
            'role'       => $user['role'], 
            'isLoggedIn' => true
        ]);
        
        // Arahkan ke halaman utama (Beranda) setelah login sukses
        return redirect()->to('/');
    }

    // =========================================================================
    // LOGIKA LOGOUT
    // =========================================================================
    public function logout()
    {
        $session = session();
        // Pastikan email juga ikut dihapus dari memori saat logout
        $session->remove(['id_user', 'username', 'email', 'role', 'isLoggedIn']);
        $session->destroy();

        return redirect()->to('/')->with('success', 'Anda berhasil logout!');
    }

    // =========================================================================
    // LOGIKA REGISTER
    // =========================================================================
    public function registerAction()
    {
        $model = new UserModel();
        
        $emailInput = $this->request->getPost('email');

        // 1. Cek apakah email sudah terdaftar di database
        $cekEmail = $model->where('email', $emailInput)->first();
        if ($cekEmail) {
            // Jika sudah ada, kembalikan dengan pesan error
            return redirect()->back()->with('error', 'Email sudah digunakan! Silakan gunakan email lain.');
        }

        // 2. Jika email belum ada, siapkan data untuk disimpan
        $data = [
            'username' => $this->request->getPost('username'),
            'email'    => $emailInput,
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'     => 'Customer'
        ];

        // 3. Simpan ke database
        $model->insert($data);

        // 4. Kembalikan ke halaman depan dengan pesan sukses
        return redirect()->to('/')->with('success', 'Pendaftaran berhasil! Silakan login.');
    }
}
