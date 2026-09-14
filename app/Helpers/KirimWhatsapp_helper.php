<?php
if (!function_exists('kirim_wa')) {
    /**
     * Mengirim pesan WhatsApp menggunakan API Watzap.id
     * 
     * @param string $phone Nomor telepon tujuan
     * @param string $message Isi pesan yang akan dikirim
     * @return array|mixed Hasil response API
     */
    function kirim_wa($phone, $message) {
        $api_key    = setting('whatsapp_token'); 
        $number_key = setting('whatsapp_secret_key'); 
        
        $api_key    = !empty($api_key) ? $api_key : 'YOUR_WATZAP_API_KEY';
        $number_key = !empty($number_key) ? $number_key : 'YOUR_WATZAP_NUMBER_KEY';

        // ==========================================
        // PERBAIKAN 1: Auto-Format Nomor HP
        // ==========================================
        // Hapus semua karakter selain angka (spasi, strip, tanda +)
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // Jika nomor diawali dengan '0', ubah menjadi '62'
        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        }
        // Jika nomor diawali dengan '8', tambahkan '62' di depannya
        if (substr($phone, 0, 1) === '8') {
            $phone = '62' . $phone;
        }
        // ==========================================

        $url = setting('whatsapp_url');

        $data = [
            "api_key"    => $api_key,
            "number_key" => $number_key,
            "phone_no"   => $phone,
            "message"    => $message
        ];

        $curl = curl_init();
        
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($data), 
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json"
            ],
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
        ]);

        $result = curl_exec($curl);

        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
            curl_close($curl);
            return [
                'status'  => false,
                'message' => 'Request failed: ' . $error_msg
            ];
        }

        curl_close($curl);

        $response = json_decode($result, true);
        
        // ==========================================
        // PERBAIKAN 2: Log / Debugging (Opsional)
        // ==========================================
        // Jika Anda ingin melihat respon asli dari Watzap saat testing, 
        // Anda bisa uncomment baris di bawah ini:
        // log_message('info', 'Watzap Response: ' . $result);
        
        return $response;
    }
}