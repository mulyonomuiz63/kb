<?php
if (!function_exists('kirim_wa')) {
    /**
     * Mengirim pesan WhatsApp menggunakan API Watzap.id
     * 
     * @param string $phone Nomor telepon tujuan (format: 628xxxxxxxxxx, tanpa tanda +)
     * @param string $message Isi pesan yang akan dikirim
     * @return array|mixed Hasil response API
     */
    function kirim_wa($phone, $message) {
        // Ambil dari pengaturan aplikasi Anda
        // Sesuaikan parameter setting() jika Anda sudah mengubah namanya di database
        $api_key    = setting('whatsapp_token'); // Mapping ke API Key Watzap
        $number_key = setting('whatsapp_secret_key'); // Mapping ke Number Key Watzap
        
        // Konfigurasi default kredensial jika tidak disertakan
        $api_key    = !empty($api_key) ? $api_key : 'YOUR_WATZAP_API_KEY';
        $number_key = !empty($number_key) ? $number_key : 'YOUR_WATZAP_NUMBER_KEY';

        // Endpoint Watzap.id untuk kirim pesan teks
        $url = "https://api.watzap.id/v1/waba_send_message_template";

        // Payload data dalam bentuk array
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
            CURLOPT_POSTFIELDS => json_encode($data), // Watzap wajib dikirim sebagai JSON
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json"
            ],
            // Nonaktifkan verifikasi SSL lokal (Opsional jika server sering bermasalah SSL)
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

        // Watzap mengembalikan response JSON, kita decode menjadi array
        return json_decode($result, true);
    }
}