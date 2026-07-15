<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ReservasiModel;

class GoogleCalendarController extends BaseController
{
    private $client;

    public function __construct()
    {
        if (class_exists('Google\Client')) {
            $this->client = new \Google\Client();
            $this->client->setClientId(env('GOOGLE_CLIENT_ID'));
            $this->client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
            $this->client->setRedirectUri(env('GOOGLE_REDIRECT_URI'));
            $this->client->addScope(\Google\Service\Calendar::CALENDAR_EVENTS);
            // Tambahkan access type offline jika butuh refresh token, tapi untuk kasus sekali pakai (on the fly), tidak wajib.
            // $this->client->setAccessType('offline');
        }
    }

    public function auth($id_reservasi)
    {
        if (!$this->client) {
            return redirect()->back()->with('error', 'Google API Client belum diinstal atau tidak ditemukan.');
        }

        // Simpan id_reservasi ke session untuk digunakan setelah callback
        session()->set('gcal_id_reservasi', $id_reservasi);

        // Jika ada email user di session, set sebagai login hint agar otomatis terpilih
        $email = session()->get('email');
        if ($email) {
            $this->client->setLoginHint($email);
        }

        // Buat Auth URL dan arahkan user ke halaman login Google
        $authUrl = $this->client->createAuthUrl();
        return redirect()->to($authUrl);
    }

    public function callback()
    {
        if (!$this->client) {
            return redirect()->to('riwayat_reservasi')->with('gcal_error', 'Google API Client tidak ditemukan.');
        }

        $code = $this->request->getGet('code');
        if (!$code) {
            return redirect()->to('riwayat_reservasi')->with('gcal_error', 'Gagal mendapatkan otorisasi dari akun Google Anda.');
        }

        try {
            // Tukar auth code dengan access token dari Google
            $token = $this->client->fetchAccessTokenWithAuthCode($code);

            if (isset($token['error'])) {
                return redirect()->to('riwayat_reservasi')->with('gcal_error', 'Error dari Google: ' . $token['error']);
            }

            $this->client->setAccessToken($token);

            // Ambil id_reservasi dari session
            $id_reservasi = session()->get('gcal_id_reservasi');
            if (!$id_reservasi) {
                return redirect()->to('riwayat_reservasi')->with('gcal_error', 'Sesi kalender telah berakhir. Silakan ulangi proses.');
            }

            // Ambil data reservasi
            $reservasiModel = new ReservasiModel();
            $reservasi = $reservasiModel->find($id_reservasi);

            if (!$reservasi) {
                return redirect()->to('riwayat_reservasi')->with('gcal_error', 'Data reservasi tidak ditemukan.');
            }

            // Inisialisasi Service Calendar Google
            $service = new \Google\Service\Calendar($this->client);

            // Persiapan Data Waktu Reservasi
            $tanggal = $reservasi['tanggal_jadwal']; // Format: YYYY-MM-DD
            $waktu_mulai = $reservasi['waktu_jadwal']; // Format: HH:MM

            // Gabung jadi DateTime (YYYY-MM-DDTHH:MM:SS)
            $startDateTime = $tanggal . 'T' . date('H:i:s', strtotime($waktu_mulai));

            // Estimasi reservasi selesai dalam 2 jam
            $endDateTimeObj = new \DateTime($startDateTime);
            $endDateTimeObj->add(new \DateInterval('PT2H'));
            $endDateTime = $endDateTimeObj->format('Y-m-d\TH:i:s');

            $event = new \Google\Service\Calendar\Event([
                'summary' => 'Reservasi Kopi Senja - Meja ' . (!empty($reservasi['no_meja']) ? $reservasi['no_meja'] : 'Belum Ditentukan'),
                'location' => 'Kedai Kopi Senja, Semarang',
                'description' => 'Reservasi Meja di Kedai Kopi Senja. Jumlah Tamu: ' . $reservasi['jumlah_tamu'],
                'start' => [
                    'dateTime' => $startDateTime,
                    'timeZone' => 'Asia/Jakarta',
                ],
                'end' => [
                    'dateTime' => $endDateTime,
                    'timeZone' => 'Asia/Jakarta',
                ],
            ]);

            // Insert Event ke kalender utama user
            $calendarId = 'primary';
            $service->events->insert($calendarId, $event);

            // Bersihkan session
            session()->remove('gcal_id_reservasi');

            return redirect()->to('riwayat_reservasi')->with('gcal_success', 'Jadwal telah berhasil ditambahkan ke Google Calendar secara otomatis!');

        } catch (\Exception $e) {
            return redirect()->to('riwayat_reservasi')->with('gcal_error', 'Terjadi kesalahan sistem saat menghubungi Google: ' . $e->getMessage());
        }
    }
}
