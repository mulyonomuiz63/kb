<?php

namespace App\Controllers;

class Affiliate extends BaseController
{
    public function redirect(string $shortCode, string $kodeUnik): \CodeIgniter\HTTP\RedirectResponse
    {
        $db = \Config\Database::connect();

        // 1. Cari link berdasarkan short_code di tabel affiliate_links
        $link = $db->table('affiliate_links')
                   ->where('short_code', $shortCode)
                   ->get()
                   ->getRowObject();

        if (empty($link)) {
            return redirect()->to('/');
        }

        // 2. Cek apakah link sudah expired
        if (!empty($link->expired_at) && strtotime($link->expired_at) < time()) {
            return redirect()->to('/');
        }

        // 3. Cek apakah affiliate-nya approved di tabel affiliates
        $affiliate = $db->table('affiliates')
                        ->where('kode_affiliate', $link->kode_affiliate)
                        ->where('status', 1)
                        ->get()
                        ->getRowObject();

        if (empty($affiliate)) {
            return redirect()->to('/');
        }

        // 4. Simpan ke session
        session()->set([
            'ref_short_code'     => $shortCode,
            'ref_kode_unik'      => $kodeUnik,
            'ref_kode_affiliate' => $link->kode_affiliate,
            'ref_paket_id'       => $link->paket_id,
            'ref_id_affiliate'   => $affiliate->id_affiliate,
            'ref_user_id'        => $affiliate->user_id,
        ]);

        // 5. Redirect ke halaman paket jika ada paket_id, atau ke registrasi
        if (!empty($link->paket_id)) {
            return redirect()->to('sw-siswa/transaksi/pesan/' . encrypt_url($link->paket_id));
        }

        return redirect()->to('auth/registrasi');
    }
}
