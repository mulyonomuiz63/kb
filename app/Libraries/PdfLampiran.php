<?php

namespace App\Libraries;

class PdfLampiran
{
    /**
     * Buka PDF yang hanya dikunci owner-password (bisa dibuka tanpa sandi).
     * Mengembalikan isi PDF tanpa enkripsi agar watermark bisa dipasang.
     */
    public function bukaEnkripsiPemilik(string $path): string
    {
        $pdf = file_get_contents($path);
        if ($pdf === false || strncmp($pdf, '%PDF-', 5) !== 0) {
            throw new \RuntimeException('Berkas PDF tidak valid.');
        }

        if (!preg_match('/\/Encrypt\s+\d+\s+\d+\s+R/', $pdf)) {
            return $pdf;
        }

        $entries = $this->bacaXref($pdf);
        $encryptId = $this->idEnkripsi($pdf);
        $encryptObj = $this->potongObjek($pdf, $entries[$encryptId][0]);
        $fileKey = $this->kunciFile($encryptObj);

        $objects = [];
        $gens = [];
        foreach ($entries as $id => [$offset, $gen]) {
            if ($id === 0) {
                continue;
            }
            $gens[$id] = $gen;
            $objects[$id] = $this->salinObjek($pdf, $offset, $fileKey, $id === $encryptId);
        }

        return $this->susunUlang($pdf, $objects, $gens);
    }

    private function idEnkripsi(string $pdf): int
    {
        if (!preg_match('/\/Encrypt\s+(\d+)\s+\d+\s+R/', $pdf, $m)) {
            throw new \RuntimeException('Kamus enkripsi PDF tidak ditemukan.');
        }

        return (int) $m[1];
    }

    private function kunciFile(string $encryptObj): string
    {
        if (!preg_match('/\/R\s+(\d+)/', $encryptObj, $rev) || (int) $rev[1] !== 6) {
            throw new \RuntimeException('Jenis enkripsi PDF ini belum didukung.');
        }
        if (!preg_match('/\/U\s*<\s*([0-9A-Fa-f\s]+)>/', $encryptObj, $uMatch)) {
            throw new \RuntimeException('Data kunci PDF tidak lengkap.');
        }
        if (!preg_match('/\/UE\s*<\s*([0-9A-Fa-f\s]+)>/', $encryptObj, $ueMatch)) {
            throw new \RuntimeException('Data kunci PDF tidak lengkap.');
        }

        $u = hex2bin(preg_replace('/\s+/', '', $uMatch[1]));
        $ue = hex2bin(preg_replace('/\s+/', '', $ueMatch[1]));
        if ($u === false || $ue === false || strlen($u) < 48 || strlen($ue) !== 32) {
            throw new \RuntimeException('Data kunci PDF rusak.');
        }

        $cek = $this->hashRevisi6('', substr($u, 32, 8), '');
        if (substr($cek, 0, 32) !== substr($u, 0, 32)) {
            throw new \RuntimeException('PDF terkunci dengan sandi dan tidak dapat diberi watermark.');
        }

        $keyHash = $this->hashRevisi6('', substr($u, 40, 8), '');
        $fileKey = openssl_decrypt(
            $ue,
            'aes-256-cbc',
            substr($keyHash, 0, 32),
            OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING,
            str_repeat("\0", 16)
        );
        if (!is_string($fileKey) || strlen($fileKey) !== 32) {
            throw new \RuntimeException('Kunci PDF tidak dapat dibuka.');
        }

        return $fileKey;
    }

    private function hashRevisi6(string $password, string $salt, string $userKey): string
    {
        $password = substr($password, 0, 127);
        $k = hash('sha256', $password . $salt . $userKey, true);
        $round = 0;

        while (true) {
            $round++;
            $k1 = str_repeat($password . $k . $userKey, 64);
            $encrypted = openssl_encrypt(
                $k1,
                'aes-128-cbc',
                substr($k, 0, 16),
                OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING,
                substr($k, 16, 16)
            );
            if (!is_string($encrypted)) {
                throw new \RuntimeException('Gagal menghitung kunci PDF.');
            }

            $mod = 0;
            for ($i = 0; $i < 16; $i++) {
                $mod = ($mod * 256 + ord($encrypted[$i])) % 3;
            }
            $algo = $mod === 0 ? 'sha256' : ($mod === 1 ? 'sha384' : 'sha512');
            $k = hash($algo, $encrypted, true);

            if ($round >= 64 && ord($encrypted[strlen($encrypted) - 1]) <= ($round - 32)) {
                return $k;
            }
        }
    }

    private function bacaXref(string $pdf): array
    {
        if (!preg_match('/startxref\s+(\d+)/', $pdf, $m)) {
            throw new \RuntimeException('Indeks PDF tidak ditemukan.');
        }

        $pos = (int) $m[1];
        $trailerPos = strpos($pdf, 'trailer', $pos);
        if ($trailerPos === false) {
            throw new \RuntimeException('Trailer PDF tidak ditemukan.');
        }

        $bagian = substr($pdf, $pos, $trailerPos - $pos);
        $baris = preg_split('/\r\n|\n|\r/', trim($bagian));
        if (!$baris || trim($baris[0]) !== 'xref') {
            throw new \RuntimeException('PDF memakai indeks terkompresi yang belum didukung.');
        }

        $entries = [];
        $i = 1;
        $total = count($baris);
        while ($i < $total) {
            if (!preg_match('/^(\d+)\s+(\d+)$/', trim($baris[$i]), $sub)) {
                break;
            }
            $awal = (int) $sub[1];
            $jumlah = (int) $sub[2];
            $i++;
            for ($n = 0; $n < $jumlah && $i < $total; $n++, $i++) {
                $item = $baris[$i];
                if (!preg_match('/^(\d{10})\s+(\d{5})\s+([nf])/', $item, $entri)) {
                    continue;
                }
                if ($entri[3] === 'n') {
                    $entries[$awal + $n] = [(int) $entri[1], (int) $entri[2]];
                }
            }
        }

        if ($entries === []) {
            throw new \RuntimeException('Objek PDF tidak dapat dibaca.');
        }

        return $entries;
    }

    private function potongObjek(string $pdf, int $offset): string
    {
        if (!preg_match('/^\d+\s+\d+\s+obj/', substr($pdf, $offset, 40))) {
            throw new \RuntimeException('Objek PDF rusak.');
        }
        $akhir = strpos($pdf, 'endobj', $offset);
        if ($akhir === false) {
            throw new \RuntimeException('Objek PDF tidak utuh.');
        }

        return substr($pdf, $offset, $akhir - $offset);
    }

    private function salinObjek(string $pdf, int $offset, string $fileKey, bool $biarkanTerenkripsi): string
    {
        if (!preg_match('/^(\d+)\s+(\d+)\s+obj/', substr($pdf, $offset, 40), $kepala)) {
            throw new \RuntimeException('Objek PDF rusak.');
        }

        $setelahObj = $offset + strlen($kepala[0]);
        $batasCari = $setelahObj + 80000;
        $streamPos = strpos($pdf, "stream\n", $setelahObj);
        $punyaStream = $streamPos !== false && $streamPos < $batasCari;

        if ($punyaStream) {
            $kamus = substr($pdf, $setelahObj, $streamPos - $setelahObj);
            if (preg_match('/\/Length(?!\d)\s+(\d+)/', $kamus, $panjang)) {
                if ($biarkanTerenkripsi) {
                    $akhir = strpos($pdf, 'endobj', $streamPos);
                    return substr($pdf, $offset, ($akhir + 6) - $offset) . "\n";
                }

                $data = substr($pdf, $streamPos + 7, (int) $panjang[1]);
                $terbuka = $this->dekripsiStream($data, $fileKey);
                $kamus = preg_replace('/\/Length(?!\d)\s+\d+/', '/Length ' . strlen($terbuka), $kamus, 1);

                return $kepala[1] . ' ' . $kepala[2] . ' obj' . $kamus . "stream\n" . $terbuka . "\nendstream\nendobj\n";
            }
        }

        $akhir = strpos($pdf, 'endobj', $setelahObj);
        if ($akhir === false) {
            throw new \RuntimeException('Objek PDF tidak utuh.');
        }

        return substr($pdf, $offset, ($akhir + 6) - $offset) . "\n";
    }

    private function dekripsiStream(string $data, string $fileKey): string
    {
        if (strlen($data) < 32) {
            return $data;
        }

        $terbuka = openssl_decrypt(substr($data, 16), 'aes-256-cbc', $fileKey, OPENSSL_RAW_DATA, substr($data, 0, 16));
        if (!is_string($terbuka)) {
            throw new \RuntimeException('Isi PDF tidak dapat dibuka untuk watermark.');
        }

        return $terbuka;
    }

    private function susunUlang(string $pdfAsli, array $objects, array $gens): string
    {
        if (!preg_match('/\/Root\s+(\d+)\s+(\d+)\s+R/', $pdfAsli, $root)) {
            throw new \RuntimeException('Akar PDF tidak ditemukan.');
        }

        $info = '';
        if (preg_match('/\/Info\s+(\d+)\s+(\d+)\s+R/', $pdfAsli, $infoMatch)) {
            $info = ' /Info ' . $infoMatch[1] . ' ' . $infoMatch[2] . ' R';
        }

        $hasil = "%PDF-1.7\n";
        $offset = [];
        $maxId = max(array_keys($objects));
        for ($id = 1; $id <= $maxId; $id++) {
            if (!isset($objects[$id])) {
                continue;
            }
            $offset[$id] = strlen($hasil);
            $hasil .= $objects[$id];
            if (substr($hasil, -1) !== "\n") {
                $hasil .= "\n";
            }
        }

        $xrefPos = strlen($hasil);
        $hasil .= 'xref' . "\n" . '0 ' . ($maxId + 1) . "\n";
        $hasil .= "0000000000 65535 f \n";
        for ($id = 1; $id <= $maxId; $id++) {
            if (!isset($offset[$id])) {
                $hasil .= "0000000000 00000 f \n";
                continue;
            }
            $gen = $gens[$id] ?? 0;
            $hasil .= sprintf("%010d %05d n \n", $offset[$id], $gen);
        }

        $hasil .= "trailer\n<< /Size " . ($maxId + 1) . ' /Root ' . $root[1] . ' ' . $root[2] . ' R' . $info . " >>\n";
        $hasil .= "startxref\n" . $xrefPos . "\n%%EOF\n";

        return $hasil;
    }
}
