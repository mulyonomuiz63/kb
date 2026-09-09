<?php
if (!function_exists('kirim_wa')) {
    /**
     * Mengirim pesan WhatsApp menggunakan Wablas API
     * 
     * @param string $phone Nomor telepon tujuan (format: 628xxxxxxxxxx)
     * @param string $message Isi pesan yang akan dikirim
     * @param string $token Token API Wablas (opsional jika sudah diatur di dalam fungsi)
     * @param string $secret_key Secret Key Wablas (opsional)
     * @return array|mixed Hasil response API
     */
    function kirim_wa($phone, $message) {
        $token = setting('whatsapp_token'); // Ambil token dari pengaturan aplikasi
        $secret_key = setting('whatsapp_secret_key'); // Ambil secret key dari pengaturan aplikasi
        // Konfigurasi default kredensial jika tidak disertakan saat pemanggilan fungsi
        $token = !empty($token) ? $token : 'your_api_token_here';
        $secret_key = !empty($secret_key) ? $secret_key : 'your_secret_key_here';
        
        $encoded_message = urlencode($message);
        $flag = "instant";

        $url = "https://smg.wablas.com/api/send-message?token={$token}.{$secret_key}&phone={$phone}&message={$encoded_message}&flag={$flag}";

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

        $result = curl_exec($curl);

        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
            curl_close($curl);
            return [
                'status' => false,
                'message' => 'Request failed: ' . $error_msg
            ];
        }

        curl_close($curl);

        // Mengembalikan hasil decode JSON dari response API
        return json_decode($result, true);
    }
}